<?php

/**
 * 赛事前台栏目导航。
 * @author lzs
 * @date 2026-11-20
 */

use yii\helpers\Html;

$sections = [
    'football/teams' => '球队',
    'football/players' => '球员',
    'football/stadiums' => '场馆',
    'match/index' => '赛程与比分',
];
$route = Yii::$app->controller->route;
?>
<nav aria-label="世界杯赛事" class="btn-group" style="margin-bottom: 1em">
    <?php foreach ($sections as $section => $label): ?>
        <?= Html::a($label, ['/' . $section], [
            'class' => 'btn ' . ($section === $route ? 'btn-primary' : 'btn-default'),
        ]) ?>
    <?php endforeach; ?>
</nav>
