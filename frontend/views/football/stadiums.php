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
    'emptyText' => '暂无场馆资料',
    'columns' => ['name', 'city', 'capacity:integer', 'source_url:url'],
]) ?>
