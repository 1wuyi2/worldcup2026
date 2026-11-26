<?php

/**
 * 模块 A 后台留言管理列表页。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use common\models\PortalMessage;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $status string|int */
/* @var $identity string */
/* @var $memberId string|int */
/* @var $memberOptions array */

$this->title = '留言管理';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(<<<'JS'
var guestbookForm = $('#backend-guestbook-filter-form');
var guestbookIdentity = $('#guestbook-identity');
var guestbookMember = $('#guestbook-member');
var guestbookStatus = $('#guestbook-status');

guestbookIdentity.on('change', function () {
    if ($(this).val() !== 'member') {
        guestbookMember.val('');
    }

    guestbookForm.trigger('submit');
});

guestbookMember.on('change', function () {
    if ($(this).val() !== '') {
        guestbookIdentity.val('member');
    }

    guestbookForm.trigger('submit');
});

guestbookStatus.on('change', function () {
    guestbookForm.trigger('submit');
});
JS
);
?>
<div class="portal-guestbook-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('发布新留言', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= Html::beginForm(
    ['index'],
    'get',
    [
        'id' => 'backend-guestbook-filter-form',
        'class' => 'form-inline',
        'style' => 'margin-bottom: 15px;',
    ]
) ?>
    <div class="form-group">
        <?= Html::label('发布身份', 'guestbook-identity', ['class' => 'control-label']) ?>
        <?= Html::dropDownList('identity', $identity, [
            'team' => '团队发布',
            'member' => '成员发布',
            'visitor' => '历史访客',
        ], ['id' => 'guestbook-identity', 'prompt' => '全部来源', 'class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <?= Html::label('发布成员', 'guestbook-member', ['class' => 'control-label']) ?>
        <?= Html::dropDownList('member_id', $memberId, $memberOptions, [
            'id' => 'guestbook-member',
            'prompt' => '全部成员',
            'class' => 'form-control',
        ]) ?>
    </div>
    <div class="form-group">
        <?= Html::label('审核状态', 'guestbook-status', ['class' => 'control-label']) ?>
        <?= Html::dropDownList(
            'status',
            $status,
            PortalMessage::getStatusOptions(),
            ['id' => 'guestbook-status', 'prompt' => '全部状态', 'class' => 'form-control']
        ) ?>
    </div>
    <?= Html::submitButton('筛选', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('重置', ['index'], ['class' => 'btn btn-default']) ?>
    <?= Html::endForm() ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => '暂无符合条件的留言。',
        'columns' => [
            'id',
            [
                'label' => '来源',
                'value' => function ($model) {
                    if ($model->isVisitorMessage()) {
                        return '历史访客';
                    }
                    return $model->member === null ? '团队发布' : '成员发布';
                },
            ],
            [
                'label' => '公开发布者',
                'value' => function ($model) {
                    return $model->getPublisherLabel();
                },
            ],
            [
                'attribute' => 'content',
                'value' => function ($model) {
                    return mb_strlen($model->content, 'UTF-8') > 60
                        ? mb_substr($model->content, 0, 60, 'UTF-8') . '…'
                        : $model->content;
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusLabel();
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {publish} {hide} {reply} {delete}',
                'contentOptions' => ['style' => 'white-space: nowrap;'],
                'buttons' => [
                    'publish' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['publish', 'id' => $model->id], [
                            'title' => '审核发布',
                            'data' => ['method' => 'post', 'confirm' => '确定发布这条留言吗？'],
                        ]);
                    },
                    'hide' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-close"></span>', ['hide', 'id' => $model->id], [
                            'title' => '隐藏留言',
                            'data' => ['method' => 'post', 'confirm' => '确定隐藏这条留言吗？'],
                        ]);
                    },
                    'reply' => function ($url, $model) {
                        if (!$model->isVisitorMessage()) {
                            return '';
                        }
                        return Html::a('<span class="glyphicon glyphicon-comment"></span>', ['reply', 'id' => $model->id], [
                            'title' => $model->reply === null ? '添加回复' : '修改回复',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        if (!$model->isAdminMessage()) {
                            return '';
                        }
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id], [
                            'title' => '修改后台发布留言',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                            'title' => '删除留言',
                            'data' => [
                                'method' => 'post',
                                'confirm' => '确定删除该留言吗？如有历史回复将一并删除，此操作不可撤销。',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]) ?>
</div>
