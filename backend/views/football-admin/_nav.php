<?php

/**
 * 赛事后台页面导航。
 * @author lzs
 * @date 2026-11-20
 */

use yii\helpers\Html;

$sections = [
    'football-team' => '球队',
    'football-player' => '球员',
    'stadium' => '场馆',
    'match' => '比赛',
    'match-goal' => '进球',
];
?>
<nav aria-label="赛事管理" class="btn-group" style="margin-bottom: 1em">
    <?php foreach ($sections as $controller => $label): ?>
        <?= Html::a($label, ['/' . $controller . '/index'], [
            'class' => 'btn ' . ($controller === Yii::$app->controller->id ? 'btn-primary' : 'btn-default'),
        ]) ?>
    <?php endforeach; ?>
</nav>
