<?php

/**
 * 模块 A 团队门户前台展示控制器。
 *
 * @author wuyi
 * @date 2026-11-19
 */

namespace frontend\controllers;

use common\models\PortalTeam;
use Yii;
use yii\web\Controller;

class TeamController extends Controller
{
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
            $teamLogoUrl = $this->resolveLocalImageUrl($team->logo_path);

            foreach ($members as $member) {
                $memberAvatarUrls[(string) $member->id] = $this->resolveLocalImageUrl($member->avatar_path);
            }
        }

        return $this->render('index', [
            'team' => $team,
            'members' => $members,
            'teamLogoUrl' => $teamLogoUrl,
            'memberAvatarUrls' => $memberAvatarUrls,
        ]);
    }

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

        $relativeFile = str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath);
        $absoluteFile = realpath($webRoot . DIRECTORY_SEPARATOR . $relativeFile);
        if ($absoluteFile === false || !is_file($absoluteFile)) {
            return null;
        }

        $webRootPrefix = rtrim($webRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (strncasecmp($absoluteFile, $webRootPrefix, strlen($webRootPrefix)) !== 0) {
            return null;
        }

        $encodedSegments = array_map('rawurlencode', $segments);

        return Yii::$app->request->baseUrl . '/' . implode('/', $encodedSegments);
    }
}
