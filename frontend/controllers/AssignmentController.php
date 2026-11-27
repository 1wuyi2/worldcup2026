<?php

/**
 * 模块 A 前台课程资料展示与安全下载控制器。
 *
 * @author wuyi
 * @date 2026-11-20
 */

namespace frontend\controllers;

use common\components\PortalAssignmentFile;
use common\models\PortalAssignment;
use common\models\PortalMember;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AssignmentController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET'],
                    'download' => ['GET', 'HEAD'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $category = Yii::$app->request->get('category', '');

        if (
            !is_string($category)
            || (
                $category !== ''
                && !array_key_exists(
                    $category,
                    PortalAssignment::getCategoryOptions()
                )
            )
        ) {
            throw new BadRequestHttpException(
                '无效的作业分类筛选参数。'
            );
        }

        $selectedMember = null;
        $memberId = Yii::$app->request->get('member_id', '');

        if ($memberId !== '') {
            if (
                (!is_string($memberId) && !is_int($memberId))
                || !ctype_digit((string) $memberId)
                || (int) $memberId < 1
            ) {
                throw new BadRequestHttpException(
                    '无效的成员筛选参数。'
                );
            }

            $selectedMember = PortalMember::findOne([
                'id' => (string) $memberId,
            ]);

            if ($selectedMember === null) {
                throw new NotFoundHttpException(
                    '未找到指定的团队成员。'
                );
            }

            $memberId = $selectedMember->id;

            // 指定成员时，统一切换为个人作业筛选。
            $category = PortalAssignment::CATEGORY_PERSONAL;
        }

        // 查询公开的团队作业。
        $teamAssignments = [];

        if (
            $category === ''
            || $category === PortalAssignment::CATEGORY_TEAM
        ) {
            $teamAssignments = PortalAssignment::find()
                ->where([
                    'status' => PortalAssignment::STATUS_PUBLISHED,
                    'category' => PortalAssignment::CATEGORY_TEAM,
                    'member_id' => null,
                ])
                ->orderBy([
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ])
                ->all();
        }

        // 查询公开且关联真实成员的个人作业。
        $personalAssignments = [];

        if (
            $category === ''
            || $category === PortalAssignment::CATEGORY_PERSONAL
        ) {
            $personalQuery = PortalAssignment::find()
                ->alias('a')
                ->innerJoinWith('member m')
                ->where([
                    'a.status' => PortalAssignment::STATUS_PUBLISHED,
                    'a.category' => PortalAssignment::CATEGORY_PERSONAL,
                ])
                ->orderBy([
                    'a.created_at' => SORT_DESC,
                    'a.id' => SORT_DESC,
                ]);

            if ($selectedMember !== null) {
                $personalQuery->andWhere([
                    'a.member_id' => $selectedMember->id,
                ]);
            }

            $personalAssignments = $personalQuery->all();
        }

        $memberOptions = [];

        foreach (
            PortalMember::find()
                ->orderBy([
                    'sort_order' => SORT_ASC,
                    'id' => SORT_ASC,
                ])
                ->all() as $member
        ) {
            $memberOptions[$member->id] = $member->name;
        }
        // 统计全部已发布的团队作业，不受当前筛选条件影响。
$teamAssignmentCount = (int) PortalAssignment::find()
    ->where([
        'status' => PortalAssignment::STATUS_PUBLISHED,
        'category' => PortalAssignment::CATEGORY_TEAM,
        'member_id' => null,
    ])
    ->count();

// 统计关联有效成员的已发布个人作业。
$personalAssignmentCount = (int) PortalAssignment::find()
    ->alias('a')
    ->innerJoinWith('member', false)
    ->where([
        'a.status' => PortalAssignment::STATUS_PUBLISHED,
        'a.category' => PortalAssignment::CATEGORY_PERSONAL,
    ])
    ->count('a.id');

$assignmentStats = [
    'team' => $teamAssignmentCount,
    'personal' => $personalAssignmentCount,
    'total' => $teamAssignmentCount + $personalAssignmentCount,
];

        return $this->render('index', [
            'teamAssignments' => $teamAssignments,
            'personalAssignments' => $personalAssignments,
            'category' => $category,
            'memberId' => $memberId,
            'memberOptions' => $memberOptions,
            'assignmentStats' => $assignmentStats,
        ]);
    }

    public function actionDownload($id)
    {
        // 防止非法编号或数组参数进入查询。
        if (
            (!is_string($id) && !is_int($id))
            || !ctype_digit((string) $id)
            || (int) $id < 1
        ) {
            throw new BadRequestHttpException(
                '无效的课程资料编号。'
            );
        }

        $assignment = PortalAssignment::find()
            ->with('member')
            ->where([
                'id' => (string) $id,
                'status' => PortalAssignment::STATUS_PUBLISHED,
            ])
            ->one();

        if ($assignment === null) {
            throw new NotFoundHttpException(
                '未找到可下载的课程资料。'
            );
        }

        // 下载权限必须与前台公开展示条件一致。
        if (
            $assignment->category === PortalAssignment::CATEGORY_TEAM
        ) {
            if ($assignment->member_id !== null) {
                throw new NotFoundHttpException(
                    '未找到可下载的课程资料。'
                );
            }
        } elseif (
            $assignment->category === PortalAssignment::CATEGORY_PERSONAL
        ) {
            if ($assignment->member === null) {
                throw new NotFoundHttpException(
                    '未找到可下载的课程资料。'
                );
            }
        } else {
            throw new NotFoundHttpException(
                '未找到可下载的课程资料。'
            );
        }

        // 继续使用原有安全路径解析组件。
        $resolvedPath = PortalAssignmentFile::resolve($assignment);

        if ($resolvedPath === null) {
            Yii::warning(
                'Published assignment file is missing or unsafe. Assignment ID: '
                    . $assignment->id,
                __METHOD__
            );

            throw new NotFoundHttpException(
                '课程资料文件不存在或暂不可下载。'
            );
        }

        return Yii::$app->response->sendFile(
            $resolvedPath,
            PortalAssignmentFile::buildDownloadName(
                $assignment,
                $resolvedPath
            ),
            ['inline' => false]
        );
    }
}