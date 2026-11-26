<?php

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '世界杯淘汰赛晋级图';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/match.css', [
    'depends' => [AppAsset::className()],
]);

/**
 * 国旗渲染。
 */
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
        return '';
    }

    return Html::img(
        '@web/images/flags/' . $code . '.svg',
        [
            'class' => 'bracket-flag',
            'alt' => '',
            'aria-hidden' => 'true',
            'loading' => 'lazy',
        ]
    );
};


/**
 * 渲染单场晋级比赛。
 */
$renderMatch = static function (
    $match,
    $number,
    $winnerId
) use ($renderFlag) {

    $teams = [
        [
            'team' => $match->homeTeam,
            'score' => $match->home_score,
        ],
        [
            'team' => $match->awayTeam,
            'score' => $match->away_score,
        ],
    ];

    $content = Html::tag(
        'div',
        sprintf('MATCH %03d', $number),
        ['class' => 'bracket-match__number']
    );

    foreach ($teams as $item) {

        $team = $item['team'];

        $isWinner = $team !== null
            && $winnerId !== null
            && (int) $team->id === $winnerId;

        $name = $team !== null
            ? $team->name
            : '待定';

        $score = $item['score'] !== null
            ? $item['score']
            : '—';

        $content .= Html::tag(
            'div',

            $renderFlag($team)

            . Html::tag(
                'span',
                Html::encode($name),
                ['class' => 'bracket-match__team-name']
            )

            . Html::tag(
                'strong',
                Html::encode((string) $score),
                ['class' => 'bracket-match__score']
            ),

            [
                'class' => 'bracket-match__team'
                    . ($isWinner ? ' is-winner' : ''),
            ]
        );
    }

    // 点球大战需要单独显示，避免平局比分产生误解。
    if (strpos(
        (string) $match->result_note,
        '点球大战'
    ) !== false) {

        $content .= Html::tag(
            'div',
            Html::encode($match->result_note),
            ['class' => 'bracket-match__note']
        );
    }

    return Html::a(
        $content,
        ['/match/view', 'id' => $match->id],
        ['class' => 'bracket-match']
    );
};

?>

<div class="match-centre bracket-page">

    <!-- 冠军摄影展示区 -->
    <section
        class="bracket-hero"
        aria-labelledby="bracket-title"
    >

        <div class="wc-container bracket-hero__inner">

            <div class="bracket-hero__copy">

                <span class="bracket-hero__eyebrow">
                    THE ROAD TO GLORY / 2026
                </span>

                <h1 id="bracket-title" class="bracket-hero__title">
                    从32强到<br>
                    世界之巅。
                </h1>

                <p class="bracket-hero__description">
                    每一次晋级，都是通往荣耀的一步。
                </p>

                <?php if ($champion !== null): ?>

                    <div class="bracket-champion">

                        <span class="bracket-champion__label">
                            WORLD CUP CHAMPION
                        </span>

                        <div class="bracket-champion__team">

                            <?= $renderFlag($champion) ?>

                            <strong>
                                <?= Html::encode($champion->name) ?>
                            </strong>

                        </div>

                        <?php if ($finalMatch !== null): ?>

                            <?= Html::a(
                                '回顾世界杯决赛 →',
                                [
                                    '/match/view',
                                    'id' => $finalMatch->id,
                                ],
                                ['class' => 'bracket-champion__link']
                            ) ?>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

        <?= Html::img(
            '@web/images/shared/worldcup-trophy-lifted.jpg',
            [
                'class' => 'bracket-hero__image',
                'alt' => '举起的世界杯奖杯',
                'fetchpriority' => 'high',
            ]
        ) ?>

    </section>


    <!-- 浏览模式导航 -->
    <div class="mc-navigation">

        <div class="wc-container">

            <nav
                class="mc-navigation__links"
                aria-label="赛事浏览方式"
            >

                <?= Html::a(
                    '赛程与比分',
                    ['/match/index'],
                    ['class' => 'mc-navigation__link']
                ) ?>

                <?= Html::a(
                    '淘汰赛晋级图',
                    ['/match/bracket'],
                    [
                        'class' => 'mc-navigation__link is-active',
                        'aria-current' => 'page',
                    ]
                ) ?>    

            </nav>

        </div>

    </div>


    <!-- 淘汰赛晋级树 -->
    <section class="bracket-content">

        <div class="wc-container">

            <header class="bracket-content__header">

                <div>

                    <h2>淘汰赛晋级之路</h2>

                    <p>
                        从32强赛至决赛，查看完整晋级路线。
                    </p>

                </div>

                <span class="bracket-scroll-hint">
                    横向滑动查看更多 →
                </span>

            </header>

            <?php if ($bracketValid): ?>

                <div
                    class="bracket-scroll"
                    tabindex="0"
                    aria-label="淘汰赛晋级图，可横向滚动"
                >

                    <div class="bracket-board">

                        <?php foreach ($rounds as $level => $nodes): ?>

                            <div class="bracket-round">

                                <header class="bracket-round__heading">

                                    <span>
                                        <?= sprintf(
                                            '%02d',
                                            $level + 1
                                        ) ?>
                                    </span>

                                    <h3>
                                        <?= Html::encode(
                                            $roundDefinitions[$level]['label']
                                        ) ?>
                                    </h3>

                                    <small>
                                        <?= count($nodes) ?> 场比赛
                                    </small>

                                </header>

                                <div class="bracket-round__body">

                                    <?php foreach (
                                        $nodes as $index => $node
                                    ): ?>

                                        <?php

                                        $classes = [
                                            'bracket-slot',
                                        ];

                                        if ($level > 0) {
                                            $classes[] =
                                                'has-incoming';
                                        }

                                        if ($level < 4) {

                                            $classes[] =
                                                'has-outgoing';

                                            $classes[] =
                                                $index % 2 === 0
                                                    ? 'is-upper'
                                                    : 'is-lower';
                                        }

                                        if ($level === 4) {
                                            $classes[] =
                                                'is-final';
                                        }

                                        ?>

                                        <div class="<?= Html::encode(
                                            implode(' ', $classes)
                                        ) ?>">

                                            <?= $renderMatch(
                                                $node['match'],
                                                $node['number'],
                                                $node['winnerId']
                                            ) ?>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php else: ?>

                <div class="mc-empty">

                    <h3>晋级关系暂不完整</h3>

                    <p>
                        当前比赛数据无法建立完整的淘汰赛晋级路线。
                        可以通过赛事列表查看已有比赛。
                    </p>

                    <?= Html::a(
                        '返回赛程列表 →',
                        ['/match/index'],
                        ['class' => 'mc-empty__link']
                    ) ?>

                </div>

            <?php endif; ?>


            <!-- 季军赛单独展示 -->
            <?php if ($thirdMatch !== null): ?>

                <div class="bracket-third-place">

                    <div>

                        <span class="bracket-third-place__eyebrow">
                            THIRD PLACE
                        </span>

                        <h3>季军争夺战</h3>

                        <p>
                            两场半决赛的失利球队争夺季军。
                        </p>

                    </div>

                    <?= $renderMatch(
                        $thirdMatch,
                        103,
                        $thirdWinnerId
                    ) ?>

                </div>

            <?php endif; ?>

        </div>

    </section>

</div>