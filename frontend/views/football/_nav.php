<?php

use yii\helpers\Html;

$currentAction = Yii::$app->controller->action->id;

$sections = [
    'teams' => '国家队',
    'players' => '球员',
    'stadiums' => '场馆',
];

?>

<nav
    class="football-subnav"
    aria-label="探索世界杯栏目"
>

    <?php foreach ($sections as $action => $label): ?>

        <?php $isActive = $currentAction === $action; ?>

        <?= Html::a(
            Html::encode($label),
            ['/football/' . $action],
            [
                'class' => 'football-subnav__link'
                    . ($isActive ? ' is-active' : ''),
                'aria-current' => $isActive
                    ? 'page'
                    : null,
            ]
        ) ?>

    <?php endforeach; ?>

</nav>