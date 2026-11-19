<?php

/**
 * 模块 A 团队成员后台列表页。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '团队成员管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-member-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('新增成员', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('团队资料', ['/portal-team/index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'emptyText' => '暂无成员资料。',
        'columns' => [
            'id',
            [
                'attribute' => 'team_id',
                'label' => '所属团队',
                'value' => function ($model) {
                    return $model->team !== null ? $model->team->name : '未知团队';
                },
            ],
            'name',
            'student_no',
            'role',
            'sort_order',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'contentOptions' => ['style' => 'white-space: nowrap;'],
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => '删除',
                            'data' => [
                                'confirm' => '确定要删除成员“' . $model->name . '”吗？',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]) ?>
</div>
