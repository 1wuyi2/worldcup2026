<?php

/**
 * 模块 A 课程资料后台列表页。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use common\models\PortalAssignment;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $category string */
/* @var $status string|int */
/* @var $memberId string|int */
/* @var $memberOptions array */

$this->title = '课程资料管理';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(<<<'JS'
var assignmentForm = $('#backend-assignment-filter-form');
var assignmentCategory = $('#assignment-category');
var assignmentMember = $('#assignment-member');
var assignmentStatus = $('#assignment-status');

assignmentCategory.on('change', function () {
    if ($(this).val() !== 'personal') {
        assignmentMember.val('');
    }

    assignmentForm.trigger('submit');
});

assignmentMember.on('change', function () {
    if ($(this).val() !== '') {
        assignmentCategory.val('personal');
    }

    assignmentForm.trigger('submit');
});

assignmentStatus.on('change', function () {
    assignmentForm.trigger('submit');
});
JS
);
?>
<div class="portal-assignment-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('新增课程资料', ['create'], ['class' => 'btn btn-success']) ?></p>

   <?= Html::beginForm(
    ['index'],
    'get',
    [
        'id' => 'backend-assignment-filter-form',
        'class' => 'form-inline',
        'style' => 'margin-bottom: 15px;',
    ]
) ?>
    <div class="form-group">
        <?= Html::label('资料分类', 'assignment-category', ['class' => 'control-label']) ?>
        <?= Html::dropDownList('category', $category, PortalAssignment::getCategoryOptions(), [
            'id' => 'assignment-category',
            'prompt' => '全部分类',
            'class' => 'form-control',
        ]) ?>
    </div>
    <div class="form-group">
        <?= Html::label('所属成员', 'assignment-member', ['class' => 'control-label']) ?>
        <?= Html::dropDownList('member_id', $memberId, $memberOptions, [
            'id' => 'assignment-member',
            'prompt' => '全部成员',
            'class' => 'form-control',
        ]) ?>
    </div>
    <div class="form-group">
        <?= Html::label('发布状态', 'assignment-status', ['class' => 'control-label']) ?>
        <?= Html::dropDownList('status', $status, PortalAssignment::getStatusOptions(), [
            'id' => 'assignment-status',
            'prompt' => '全部状态',
            'class' => 'form-control',
        ]) ?>
    </div>
    <?= Html::submitButton('筛选', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('重置', ['index'], ['class' => 'btn btn-default']) ?>
    <?= Html::endForm() ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => '暂无符合条件的课程资料。',
        'columns' => [
            'id',
            'title',
            [
                'attribute' => 'category',
                'value' => function ($model) {
                    return $model->getCategoryLabel();
                },
            ],
            [
                'label' => '所属成员',
                'value' => function ($model) {
                    if ($model->category === PortalAssignment::CATEGORY_TEAM) {
                        return '团队作业';
                    }
                    if ($model->member !== null) {
                        return $model->member->name;
                    }
                    return '历史记录：成员待补充';
                },
            ],
            'relative_path',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusLabel();
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {publish} {unpublish} {delete}',
                'contentOptions' => ['style' => 'white-space: nowrap;'],
                'buttons' => [
                    'publish' => function ($url, $model) {
                        if ($model->isPublished()) {
                            return '';
                        }
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['publish', 'id' => $model->id], [
                            'title' => '发布',
                            'data' => ['method' => 'post', 'confirm' => '确定发布该资料吗？系统将先检查文件。'],
                        ]);
                    },
                    'unpublish' => function ($url, $model) {
                        if (!$model->isPublished()) {
                            return '';
                        }
                        return Html::a('<span class="glyphicon glyphicon-eye-close"></span>', ['unpublish', 'id' => $model->id], [
                            'title' => '取消发布',
                            'data' => ['method' => 'post', 'confirm' => '确定取消发布该资料吗？'],
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                            'title' => '删除记录',
                            'data' => [
                                'method' => 'post',
                                'confirm' => '确定删除资料“' . $model->title . '”的数据库记录吗？磁盘文件将保留。',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]) ?>
</div>
