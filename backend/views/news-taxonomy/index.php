<?php

/**
 * 模块 B 分类及标签管理列表。
 * @author XPC
 * @date 2026-11-22
 */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
$columns = ['id', 'name'];
if ($isCategory) {
    $columns[] = 'sort_order';
    $columns[] = ['attribute' => 'status', 'value' => 'statusLabel'];
} else {
    $columns[] = 'slug';
}
$columns[] = [
    'class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',
    'buttons' => [
        'delete' => static function ($url) use ($isCategory) {
            return Html::a('删除', $url, ['data' => [
                'method' => 'post',
                'confirm' => $isCategory ? '确认删除分类？有文章引用的分类不能删除。' : '确认删除标签及其文章关联？文章内容将保留。',
            ]]);
        },
    ],
];
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('/news/_nav') ?>
<p><?= Html::a($isCategory ? '新增分类' : '新增标签', ['create'], ['class' => 'btn btn-success']) ?></p>
<?= GridView::widget([
    'dataProvider' => $provider, 'columns' => $columns,
    'summary' => '第 {begin}–{end} 条，共 {totalCount} 条', 'emptyText' => '暂无记录。',
]) ?>
