<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $match common\models\FootballMatch */

// 根据球队代码读取已有本地国旗。
$renderFlag = static function ($team) {

    if ($team === null) {
        return '';
    }

    $code = strtoupper(
        trim((string) $team->country_code)
    );

    if (
        !preg_match('/^[A-Z]{3}$/D', $code)
        || !is_file(
            Yii::getAlias('@webroot/images/flags/')
            . $code
            . '.svg'
        )
    ) {
        return Html::tag(
            'span',
            Html::encode($code ?: '—'),
            ['class' => 'mc-match__flag-fallback']
        );
    }

    return Html::img(
        '@web/images/flags/' . $code . '.svg',
        [
            'class' => 'mc-match__flag',
            'alt' => '',
            'aria-hidden' => 'true',
            'loading' => 'lazy',
            'width' => 48,
            'height' => 36,
        ]
    );
};

$kickoff = $match->shanghaiKickoff;

$homeName = $match->homeTeam
    ? $match->homeTeam->name
    : '待定';

$awayName = $match->awayTeam
    ? $match->awayTeam->name
    : '待定';

$hasScore = $match->home_score !== null
    && $match->away_score !== null;

$score = $hasScore
    ? $match->scoreLabel
    : '—';

$statusClass = 'mc-match__status--' . (int) $match->status;

?>

<a
    class="mc-match"
    href="<?= Html::encode(
        Url::to(['/match/view', 'id' => $match->id])
    ) ?>"
>

    <!-- 比赛时间及阶段 -->
    <div class="mc-match__meta">

        <span class="mc-match__time">
            <?= Html::encode(substr($kickoff, 11, 5)) ?>
        </span>

        <span class="mc-match__stage">
            <?= Html::encode($match->stage) ?>
        </span>

    </div>

    <!-- 核心对阵信息 -->
    <div class="mc-match__fixture">

        <div class="mc-match__team mc-match__team--home">

            <span class="mc-match__team-name">
                <?= Html::encode($homeName) ?>
            </span>

            <?= $renderFlag($match->homeTeam) ?>

        </div>

        <div class="mc-match__result">

            <strong class="mc-match__score">
                <?= Html::encode($score) ?>
            </strong>

            <span class="mc-match__status <?= $statusClass ?>">
                <?= Html::encode($match->statusLabel) ?>
            </span>

        </div>

        <div class="mc-match__team mc-match__team--away">

            <?= $renderFlag($match->awayTeam) ?>

            <span class="mc-match__team-name">
                <?= Html::encode($awayName) ?>
            </span>

        </div>

    </div>

    <!-- 场馆 -->
    <div class="mc-match__venue">

        <?= Html::encode(
            $match->stadium
                ? $match->stadium->name
                : '场馆待补充'
        ) ?>

    </div>

    <span class="mc-match__arrow" aria-hidden="true">
        →
    </span>

</a>