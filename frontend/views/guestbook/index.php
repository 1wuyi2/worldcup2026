<?php

/**
 * 模块 A 前台留言板页面。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $team common\models\PortalTeam|null */
/* @var $teamLogoUrl string|null */
/* @var $memberAvatarUrls array */
/* @var $identity string */
/* @var $memberId string|int */
/* @var $memberOptions array */

$this->title = '留言板';
$this->params['breadcrumbs'][] = $this->title;

$messages = $dataProvider->getModels();

$this->registerJs(<<<'JS'
var messageIdentity = $('#public-message-identity');
var messageMember = $('#public-message-member');
var messageForm = $('#public-message-filter-form');

messageIdentity.on('change', function () {
    if ($(this).val() !== 'member') {
        messageMember.val('');
    }

    messageForm.trigger('submit');
});

messageMember.on('change', function () {
    if ($(this).val() !== '') {
        messageIdentity.val('member');
    }

    messageForm.trigger('submit');
});
JS
);
?>

<div class="guestbook-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="lead">
        这里展示由团队或成员授权、经后台发布的留言。
    </p>

    <?= Html::beginForm(
    ['index'],
    'get',
    [
        'id' => 'public-message-filter-form',
        'class' => 'form-inline',
        'style' => 'margin-bottom: 20px;',
    ]
) ?>

    <div class="form-group">

        <?= Html::label(
            '发布身份',
            'public-message-identity',
            ['class' => 'control-label']
        ) ?>

        <?= Html::dropDownList(
            'identity',
            $identity,
            [
                'team' => '团队留言',
                'member' => '个人留言',
            ],
            [
                'id' => 'public-message-identity',
                'prompt' => '全部留言',
                'class' => 'form-control',
            ]
        ) ?>

    </div>

    <div class="form-group">

        <?= Html::label(
            '发布成员',
            'public-message-member',
            ['class' => 'control-label']
        ) ?>

        <?= Html::dropDownList(
            'member_id',
            $memberId,
            $memberOptions,
            [
                'id' => 'public-message-member',
                'prompt' => '全部成员',
                'class' => 'form-control',
            ]
        ) ?>

    </div>

    <?= Html::submitButton(
        '筛选',
        ['class' => 'btn btn-primary']
    ) ?>

    <?= Html::a(
        '重置',
        ['index'],
        ['class' => 'btn btn-default']
    ) ?>

    <?= Html::endForm() ?>

    <?php if (
        $memberId !== ''
        && isset($memberOptions[$memberId])
    ): ?>

        <p>
            当前成员：

            <strong>
                <?= Html::encode($memberOptions[$memberId]) ?>
            </strong>

            <?= Html::a(
                '查看个人主页',
                [
                    '/team/member',
                    'id' => $memberId,
                ]
            ) ?>
        </p>

    <?php endif; ?>

    <h2>已发布留言</h2>

    <?php if (empty($messages)): ?>

        <div class="alert alert-info">
            目前没有符合条件的已发布留言。
        </div>

    <?php else: ?>

        <?php foreach ($messages as $message): ?>

            <?php

            $isMemberMessage = $message->member !== null;

            $publisherName = $isMemberMessage
                ? $message->member->name
                : (
                    $team !== null
                        ? $team->name
                        : $message->display_name
                );

            $imageUrl = $isMemberMessage
                ? (
                    isset($memberAvatarUrls[(string) $message->member->id])
                        ? $memberAvatarUrls[(string) $message->member->id]
                        : null
                )
                : $teamLogoUrl;

            $publisherUrl = $isMemberMessage
                ? [
                    '/team/member',
                    'id' => $message->member->id,
                ]
                : ['/team/index'];

            ?>

            <div class="panel panel-default">

                <div class="panel-heading">

                    <strong>
                        <?= Html::a(
                            Html::encode($publisherName),
                            $publisherUrl
                        ) ?>
                    </strong>

                    <span class="label label-default">
                        <?= $isMemberMessage
                            ? '个人留言'
                            : '团队留言' ?>
                    </span>

                    <span class="text-muted pull-right">

                        <?= Html::encode(
                            Yii::$app->formatter->asDatetime(
                                $message->created_at
                            )
                        ) ?>

                    </span>

                </div>

                <div class="panel-body">

                    <?php if ($imageUrl !== null): ?>

                        <?= Html::a(
                            Html::img($imageUrl, [
                                'alt' => $publisherName,
                                'class' => 'img-circle',
                                'style' => 'width: 56px; height: 56px; object-fit: cover;',
                            ]),
                            $publisherUrl,
                            [
                                'class' => 'pull-left',
                                'style' => 'margin-right: 15px;',
                            ]
                        ) ?>

                    <?php else: ?>

                        <?= Html::a(
                            Html::encode(
                                mb_substr(
                                    (string) $publisherName,
                                    0,
                                    1,
                                    'UTF-8'
                                )
                            ),
                            $publisherUrl,
                            [
                                'class' => 'pull-left text-center',
                                'style' => 'width: 56px;
                                            height: 56px;
                                            line-height: 56px;
                                            margin-right: 15px;
                                            border: 1px solid #ccc;
                                            border-radius: 50%;',
                                'aria-label' => '查看发布者主页',
                            ]
                        ) ?>

                    <?php endif; ?>

                    <p>
                        <?= nl2br(
                            Html::encode($message->content)
                        ) ?>
                    </p>

                    <div class="clearfix"></div>

                </div>

            </div>

        <?php endforeach; ?>

        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
        ]) ?>

    <?php endif; ?>

</div>