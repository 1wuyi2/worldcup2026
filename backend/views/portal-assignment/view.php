<?php

/**
 * 模块 A 课程资料后台详情页。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PortalAssignment */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '课程资料管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-assignment-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->isPublished()): ?>
            <?= Html::a('取消发布', ['unpublish', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => ['method' => 'post', 'confirm' => '确定取消发布该资料吗？'],
            ]) ?>
        <?php else: ?>
            <?= Html::a('发布', ['publish', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => ['method' => 'post', 'confirm' => '确定发布该资料吗？系统将先检查文件。'],
            ]) ?>
        <?php endif; ?>
        <?= Html::a('删除记录', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'method' => 'post',
                'confirm' => '仅删除数据库记录，磁盘文件会保留。确定继续吗？',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'category',
                'value' => $model->getCategoryLabel(),
            ],
            [
                'attribute' => 'member_id',
                'value' => $model->category === \common\models\PortalAssignment::CATEGORY_TEAM
                    ? '团队作业无需成员'
                    : ($model->member !== null ? $model->member->name : '历史记录：成员关联待补充'),
            ],
            'owner_label',
            'relative_path',
            'description:ntext',
            [
                'attribute' => 'status',
                'value' => $model->getStatusLabel(),
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
</div>
