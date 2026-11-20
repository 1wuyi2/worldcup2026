<?php

/**
 * 国家队列表。
 * @author lzs
 * @date 2026-11-20
 */

use common\models\FootballTeam;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = '世界杯球队';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_nav') ?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'emptyText' => '暂无球队资料',
    'columns' => [
        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => static function (FootballTeam $team) {
                return Html::a(Html::encode($team->name), ['players', 'team_id' => $team->id]);
            },
        ],
        'country_code',
        'group_name',
        'source_url:url',
    ],
]) ?>
