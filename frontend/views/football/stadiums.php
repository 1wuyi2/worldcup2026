<?php

/**
 * 世界杯场馆列表。
 * @author lzs
 * @date 2026-11-20
 */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = '世界杯场馆';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_nav') ?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'formatter' => ['class' => \yii\i18n\Formatter::class, 'nullDisplay' => '暂无资料'],
    'summary' => '第 {begin}–{end} 条，共 {totalCount} 条',
    'emptyText' => '暂无场馆资料',
    'columns' => ['name', 'city', 'capacity:integer', 'source_url:url'],
]) ?>
