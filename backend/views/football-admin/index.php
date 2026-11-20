<?php

/**
 * 赛事后台列表。
 * @author lzs
 * @date 2026-11-20
 */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = $sectionTitle;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_nav') ?>
<p><?= Html::a('新增记录', ['create'], ['class' => 'btn btn-success']) ?></p>

<?= GridView::widget([
    'dataProvider' => $provider,
    'emptyText' => '暂无记录',
    'tableOptions' => ['class' => 'table table-striped table-bordered'],
    'columns' => array_merge($columns, [[
        'class' => ActionColumn::class,
        'template' => '{update} {delete}',
    ]]),
]) ?>
