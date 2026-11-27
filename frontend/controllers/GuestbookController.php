<?php

/**
 * 模块 A 前台留言板控制器。
 *
 * @author wuyi
 * @date 2026-11-20
 */

namespace frontend\controllers;

use common\models\PortalMember;
use common\models\PortalMessage;
use common\models\PortalTeam;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class GuestbookController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $identity = Yii::$app->request->get('identity', '');
        if (!in_array($identity, ['', 'team', 'member'], true)) {
            throw new BadRequestHttpException('无效的留言身份筛选参数。');
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

    // 指定成员时，统一进入个人留言筛选状态。
    $identity = 'member';
    $memberId = $selectedMember->id;
}

        $query = PortalMessage::findPublishedAdmin()
            ->with('member')
            ->orderBy(['created_at' => SORT_DESC, 'id' => SORT_DESC]);
        if ($identity === 'team') {
            $query->andWhere(['member_id' => null]);
        } elseif ($identity === 'member') {
            $query->andWhere(['not', ['member_id' => null]]);
        }
        if ($selectedMember !== null) {
            $query->andWhere(['member_id' => $selectedMember->id]);
        }

        $dataProvider = new ActiveDataProvider([
    'query' => $query,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => false,
]);

        $team = PortalTeam::find()->orderBy(['id' => SORT_ASC])->one();
        $teamLogoUrl = $team === null ? null : $this->resolveLocalImageUrl($team->logo_path);
        $memberOptions = [];
        $memberAvatarUrls = [];
        foreach (PortalMember::find()->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all() as $member) {
            $memberOptions[$member->id] = $member->name;
            $memberAvatarUrls[(string) $member->id] = $this->resolveLocalImageUrl($member->avatar_path);
        }
        // 统计全部后台已发布留言，不受当前筛选和分页影响。
$teamMessageCount = (int) PortalMessage::findPublishedAdmin()
    ->andWhere(['member_id' => null])
    ->count();

$personalMessageCount = (int) PortalMessage::findPublishedAdmin()
    ->andWhere(['not', ['member_id' => null]])
    ->count();

$messageStats = [
    'team' => $teamMessageCount,
    'personal' => $personalMessageCount,
    'total' => $teamMessageCount + $personalMessageCount,
];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'team' => $team,
            'teamLogoUrl' => $teamLogoUrl,
            'memberAvatarUrls' => $memberAvatarUrls,
            'identity' => $identity,
            'memberId' => $memberId,
            'memberOptions' => $memberOptions,
            'messageStats' => $messageStats,
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

        $absoluteFile = realpath($webRoot . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath));
        if ($absoluteFile === false || !is_file($absoluteFile)) {
            return null;
        }

        $webRootPrefix = rtrim($webRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (strncasecmp($absoluteFile, $webRootPrefix, strlen($webRootPrefix)) !== 0) {
            return null;
        }

        return Yii::$app->request->baseUrl . '/' . implode('/', array_map('rawurlencode', $segments));
    }
}
