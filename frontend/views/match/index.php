<?php

/**
 * 比赛列表和比分。
 * @author lzs
 * @date 2026-11-20
 */

use common\models\FootballMatch;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = '世界杯赛程与比分';
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('/football/_nav') ?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'formatter' => ['class' => \yii\i18n\Formatter::class, 'nullDisplay' => '暂无资料'],
    'summary' => '第 {begin}–{end} 条，共 {totalCount} 条',
    'emptyText' => '暂无赛程',
    'columns' => [
        ['attribute' => 'match_time', 'label' => '开球时间（北京时间）', 'value' => 'shanghaiKickoff'],
        'stage',
        [
            'label' => '对阵',
            'format' => 'raw',
            'value' => static function (FootballMatch $match) {
                return Html::a(Html::encode($match->fixtureLabel), ['view', 'id' => $match->id]);
            },
        ],
        ['label' => '比分', 'value' => 'scoreLabel'],
        ['attribute' => 'status', 'value' => 'statusLabel'],
        ['attribute' => 'stadium_id', 'value' => 'stadium.name'],
    ],
]) ?>
