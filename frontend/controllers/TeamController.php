<?php

/**
 * 模块 A 团队门户前台展示控制器。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace frontend\controllers;

use common\models\PortalMember;
use common\models\PortalTeam;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TeamController extends Controller
{
    /**
     * 团队门户仅提供公开展示功能。
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET'],
                    'member' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * 团队介绍及成员列表。
     */
    public function actionIndex()
    {
        $team = PortalTeam::find()
            ->orderBy(['id' => SORT_ASC])
            ->one();

        $members = [];
        $teamLogoUrl = null;
        $memberAvatarUrls = [];

        if ($team !== null) {
            $members = $team->members;
            $teamLogoUrl = $this->resolveLocalImageUrl(
                $team->logo_path
            );

            foreach ($members as $member) {
                $memberAvatarUrls[(string) $member->id] =
                    $this->resolveLocalImageUrl(
                        $member->avatar_path
                    );
            }
        }

        return $this->render('index', [
            'team' => $team,
            'members' => $members,
            'teamLogoUrl' => $teamLogoUrl,
            'memberAvatarUrls' => $memberAvatarUrls,
        ]);
    }

    /**
     * 独立成员个人主页。
     *
     * @param int|string $id 成员编号
     * @return string
     */
    public function actionMember($id)
    {
        // 检查成员编号是否合法。
        if (
            (!is_string($id) && !is_int($id))
            || !ctype_digit((string) $id)
            || (int) $id < 1
        ) {
            throw new BadRequestHttpException(
                '无效的团队成员编号。'
            );
        }

        // 查询成员及个人主页需要展示的关联数据。
        $member = PortalMember::find()
            ->where(['id' => (string) $id])
            ->with([
                'team',
                'publishedPersonalAssignments',
                'publishedMessages',
            ])
            ->one();

        if ($member === null) {
            throw new NotFoundHttpException(
                '未找到指定的团队成员。'
            );
        }

        // 复用已有的本地头像路径校验逻辑。
        $avatarUrl = $this->resolveLocalImageUrl(
            $member->avatar_path
        );

        return $this->render('member', [
            'member' => $member,
            'team' => $member->team,
            'avatarUrl' => $avatarUrl,
            'assignments' => $member->publishedPersonalAssignments,
            'messages' => $member->publishedMessages,
        ]);
    }

    /**
     * 校验并生成前台本地图片 URL。
     */
    private function resolveLocalImageUrl($path)
    {
        if (!is_string($path) || trim($path) === '') {
            return null;
        }

        $normalizedPath = str_replace('\\', '/', trim($path));
        $segments = explode('/', $normalizedPath);

        if (
            strpos($normalizedPath, "\0") !== false
            || strpos($normalizedPath, '/') === 0
            || strpos($normalizedPath, ':') !== false
            || strpos($normalizedPath, '?') !== false
            || strpos($normalizedPath, '#') !== false
            || in_array('..', $segments, true)
            || in_array('.', $segments, true)
        ) {
            return null;
        }

        $webRoot = realpath(Yii::getAlias('@frontend/web'));

        if ($webRoot === false) {
            return null;
        }

        $relativeFile = str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            $normalizedPath
        );

        $absoluteFile = realpath(
            $webRoot . DIRECTORY_SEPARATOR . $relativeFile
        );

        if (
            $absoluteFile === false
            || !is_file($absoluteFile)
        ) {
            return null;
        }

        $webRootPrefix =
            rtrim($webRoot, DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR;

        if (
            strncasecmp(
                $absoluteFile,
                $webRootPrefix,
                strlen($webRootPrefix)
            ) !== 0
        ) {
            return null;
        }

        $encodedSegments = array_map(
            'rawurlencode',
            $segments
        );

        return Yii::$app->request->baseUrl
            . '/'
            . implode('/', $encodedSegments);
    }
}