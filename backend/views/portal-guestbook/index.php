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

$this->title = '留言管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-guestbook-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::beginForm(['index'], 'get', ['class' => 'form-inline', 'style' => 'margin-bottom: 15px;']) ?>
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
            'display_name',
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
                'label' => '回复状态',
                'value' => function ($model) {
                    return $model->reply === null ? '未回复' : '已回复';
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {publish} {hide} {reply}',
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
                        return Html::a('<span class="glyphicon glyphicon-comment"></span>', ['reply', 'id' => $model->id], [
                            'title' => $model->reply === null ? '添加回复' : '修改回复',
                        ]);
                    },
                ],
            ],
        ],
    ]) ?>
</div>
