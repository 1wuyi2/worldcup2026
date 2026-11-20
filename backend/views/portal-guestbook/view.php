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
        <?= Html::a('审核发布', ['publish', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'data' => ['method' => 'post', 'confirm' => '确定发布这条留言吗？'],
        ]) ?>
        <?= Html::a('隐藏', ['hide', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => ['method' => 'post', 'confirm' => '确定隐藏这条留言吗？'],
        ]) ?>
        <?= Html::a($model->reply === null ? '添加回复' : '修改回复', ['reply', 'id' => $model->id], [
            'class' => 'btn btn-primary',
        ]) ?>
        <?= Html::a('返回列表', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'display_name',
            ['attribute' => 'content', 'format' => 'ntext'],
            [
                'attribute' => 'status',
                'value' => $model->getStatusLabel(),
            ],
            ['attribute' => 'created_at', 'format' => ['datetime']],
            ['attribute' => 'updated_at', 'format' => ['datetime']],
        ],
    ]) ?>

    <h2>管理员回复</h2>
    <?php if ($model->reply === null): ?>
        <div class="alert alert-info">该留言尚未回复。</div>
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
</div>
