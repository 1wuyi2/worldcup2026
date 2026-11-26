<?php

/**
 * 模块 A 后台留言详情页。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PortalMessage */

$this->title = '留言 #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '留言管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-guestbook-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->isAdminMessage()): ?>
            <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a('审核发布', ['publish', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'data' => ['method' => 'post', 'confirm' => '确定发布这条留言吗？'],
        ]) ?>
        <?= Html::a('隐藏', ['hide', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => ['method' => 'post', 'confirm' => '确定隐藏这条留言吗？'],
        ]) ?>
        <?php if ($model->isVisitorMessage()): ?>
            <?= Html::a($model->reply === null ? '添加回复' : '修改回复', ['reply', 'id' => $model->id], [
                'class' => 'btn btn-primary',
            ]) ?>
        <?php endif; ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'post',
                'confirm' => '确定删除该留言吗？如有历史回复将一并删除。',
            ],
        ]) ?>
        <?= Html::a('返回列表', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'source_type',
                'value' => $model->isVisitorMessage()
                    ? '历史访客留言'
                    : ($model->member === null ? '后台团队留言' : '后台个人留言'),
            ],
            [
                'attribute' => 'display_name',
                'label' => '公开发布者',
                'value' => $model->getPublisherLabel(),
            ],
            [
                'attribute' => 'member_id',
                'value' => $model->member === null ? '无' : $model->member->name,
            ],
            [
                'attribute' => 'created_by_user_id',
                'value' => $model->createdByUser === null
                    ? '未保留或对应用户不存在'
                    : $model->createdByUser->username,
            ],
            ['attribute' => 'content', 'format' => 'ntext'],
            [
                'attribute' => 'status',
                'value' => $model->getStatusLabel(),
            ],
            ['attribute' => 'created_at', 'format' => ['datetime']],
            ['attribute' => 'updated_at', 'format' => ['datetime']],
        ],
    ]) ?>

    <?php if ($model->isVisitorMessage()): ?>
        <h2>历史管理员回复</h2>
        <?php if ($model->reply === null): ?>
            <div class="alert alert-info">该历史访客留言尚未回复。</div>
        <?php else: ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <p><?= nl2br(Html::encode($model->reply->content)) ?></p>
                    <small class="text-muted">
                        最近更新：<?= Html::encode(Yii::$app->formatter->asDatetime($model->reply->updated_at)) ?>
                    </small>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">后台主动发布的团队或个人留言不使用回复功能。</div>
    <?php endif; ?>
</div>
