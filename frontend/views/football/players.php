<?php

/**
 * 球员资料，可按球队查看。
 * @author lzs
 * @date 2026-11-20
 */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = $team === null ? '世界杯球员' : $team->name . ' · 球员';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_nav') ?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'emptyText' => '暂无球员资料',
    'columns' => ['name', ['attribute' => 'team_id', 'value' => 'team.name'], 'number', 'position', 'source_url:url'],
]) ?>
