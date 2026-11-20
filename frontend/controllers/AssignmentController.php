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
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AssignmentController extends Controller
{
    public function actionIndex()
    {
        $assignments = PortalAssignment::find()
            ->where(['status' => PortalAssignment::STATUS_PUBLISHED])
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC])
            ->all();

        $groupedAssignments = [
            PortalAssignment::CATEGORY_TEAM => [],
            PortalAssignment::CATEGORY_PERSONAL => [],
        ];
        foreach ($assignments as $assignment) {
            if (isset($groupedAssignments[$assignment->category])) {
                $groupedAssignments[$assignment->category][] = $assignment;
            }
        }

        return $this->render('index', [
            'teamAssignments' => $groupedAssignments[PortalAssignment::CATEGORY_TEAM],
            'personalAssignments' => $groupedAssignments[PortalAssignment::CATEGORY_PERSONAL],
        ]);
    }

    public function actionDownload($id)
    {
        $assignment = PortalAssignment::findOne([
            'id' => $id,
            'status' => PortalAssignment::STATUS_PUBLISHED,
        ]);
        if ($assignment === null) {
            throw new NotFoundHttpException('未找到可下载的课程资料。');
        }

        $resolvedPath = PortalAssignmentFile::resolve($assignment);
        if ($resolvedPath === null) {
            Yii::warning('Published assignment file is missing or unsafe. Assignment ID: ' . $assignment->id, __METHOD__);
            throw new NotFoundHttpException('课程资料文件不存在或暂不可下载。');
        }

        return Yii::$app->response->sendFile(
            $resolvedPath,
            PortalAssignmentFile::buildDownloadName($assignment, $resolvedPath),
            ['inline' => false]
        );
    }
}
