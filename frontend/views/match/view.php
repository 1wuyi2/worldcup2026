<?php

/**
 * 比赛详情及时间顺序排列的进球。
 * @author lzs
 * @date 2026-11-20
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;

/* @var $match common\models\FootballMatch */

$this->title = $match->fixtureLabel;

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/match.css', [
    'depends' => [AppAsset::className()],
]);

$goals = $match->orderedGoals;

$homeTeam = $match->homeTeam;
$awayTeam = $match->awayTeam;
$stadium = $match->stadium;

$homeName = $homeTeam
    ? $homeTeam->name
    : '待定';

$awayName = $awayTeam
    ? $awayTeam->name
    : '待定';

$hasScore = $match->home_score !== null
    && $match->away_score !== null;


/*
 * 使用本地国旗素材。
 */

$renderFlag = static function (
    $team,
    $class = 'md-team__flag'
) {

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
            ['class' => 'md-flag-fallback']
        );
    }

    return Html::img(
        '@web/images/flags/' . $code . '.svg',
        [
            'class' => $class,
            'alt' => '',
            'aria-hidden' => 'true',
            'width' => 96,
            'height' => 72,
        ]
    );
};


/*
 * 只允许 HTTP 和 HTTPS 类型的外部资料链接。
 */

$renderSource = static function (
    $url,
    $label = '查看来源 ↗'
) {

    if (
        !is_string($url)
        || !filter_var($url, FILTER_VALIDATE_URL)
        || !in_array(
            strtolower(
                (string) parse_url($url, PHP_URL_SCHEME)
            ),
            ['http', 'https'],
            true
        )
    ) {
        return '';
    }

    return Html::a(
        Html::encode($label),
        $url,
        [
            'class' => 'md-source-link',
            'target' => '_blank',
            'rel' => 'noopener noreferrer',
        ]
    );
};

?>

<div class="match-centre match-detail">

    <!-- ======================================
         比赛记分牌
         ====================================== -->

    <section
        class="md-hero"
        aria-label="比赛比分"
    >

        <?= Html::img(
            '@web/images/match/match-pitch-top-wide.jpg',
            [
                'class' => 'md-hero__image',
                'alt' => '',
                'aria-hidden' => 'true',
                'fetchpriority' => 'high',
            ]
        ) ?>

        <div
            class="md-hero__shade"
            aria-hidden="true"
        ></div>

        <div class="wc-container md-hero__inner">

            <!-- 返回赛事列表 -->

            <div class="md-hero__top">

                <?= Html::a(
                    '← 返回赛程',
                    ['/match/index'],
                    ['class' => 'md-hero__back']
                ) ?>

                <?php if ($match->source_match_key): ?>

                    <span class="md-hero__number">

                        <?= Html::encode(
                            $match->source_match_key
                        ) ?>

                    </span>

                <?php endif; ?>

            </div>


            <!-- 比赛阶段及时间 -->

            <header class="md-hero__heading">

                <span class="md-hero__eyebrow">
                    WORLD CUP 2026
                </span>

                <h1 class="md-hero__title">

                    <?= Html::encode($match->stage) ?>

                </h1>

                <p class="md-hero__kickoff">

                    <?= Html::encode(
                        $match->shanghaiKickoff
                    ) ?>

                    · 北京时间

                </p>

            </header>


            <!-- 双方球队及比分 -->

            <div class="md-scoreboard">

                <div class="md-team md-team--home">

                    <?= $renderFlag($homeTeam) ?>

                    <strong class="md-team__name">

                        <?= Html::encode($homeName) ?>

                    </strong>

                    <?php if ($homeTeam): ?>

                        <span class="md-team__code">

                            <?= Html::encode(
                                $homeTeam->country_code
                            ) ?>

                        </span>

                        <?= Html::a(
                            '查看球队球员 ↗',
                            [
                                '/football/players',
                                'team_id' => $homeTeam->id,
                            ],
                            ['class' => 'md-team__link']
                        ) ?>

                    <?php endif; ?>

                </div>


                <div class="md-score">

                    <?php if ($hasScore): ?>

                        <div class="md-score__numbers">

                            <strong>
                                <?= (int) $match->home_score ?>
                            </strong>

                            <span aria-hidden="true">–</span>

                            <strong>
                                <?= (int) $match->away_score ?>
                            </strong>

                        </div>

                    <?php else: ?>

                        <div class="md-score__pending">
                            VS
                        </div>

                    <?php endif; ?>

                    <span class="md-score__status">

                        <?= Html::encode(
                            $match->statusLabel
                        ) ?>

                    </span>

                    <?php if ($match->result_note): ?>

                        <p class="md-score__note">

                            <?= Html::encode(
                                $match->result_note
                            ) ?>

                        </p>

                    <?php endif; ?>

                </div>


                <div class="md-team md-team--away">

                    <?= $renderFlag($awayTeam) ?>

                    <strong class="md-team__name">

                        <?= Html::encode($awayName) ?>

                    </strong>

                    <?php if ($awayTeam): ?>

                        <span class="md-team__code">

                            <?= Html::encode(
                                $awayTeam->country_code
                            ) ?>

                        </span>

                        <?= Html::a(
                            '查看球队球员 ↗',
                            [
                                '/football/players',
                                'team_id' => $awayTeam->id,
                            ],
                            ['class' => 'md-team__link']
                        ) ?>

                    <?php endif; ?>

                </div>

            </div>


                        <!-- 场馆信息及关联跳转 -->

            <?php if ($stadium !== null): ?>

                <div class="md-hero__venue">

                    <?= Html::a(
                        Html::encode(
                            $stadium->city
                            . ' · '
                            . $stadium->name
                        ) . ' ↗',
                        [
                            '/football/stadiums',
                            '#' => 'stadium-' . (int) $stadium->id,
                        ],
                        [
                            'class' => 'md-hero__venue-link',
                            'title' => '查看场馆资料',
                        ]
                    ) ?>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- ======================================
         赛事中心公共栏目导航
         ====================================== -->

    <div class="mc-navigation">

        <div class="wc-container">

            <nav
                class="mc-navigation__links"
                aria-label="赛事浏览方式"
            >

                <?= Html::a(
                    '赛程与比分',
                    ['/match/index'],
                    [
                        'class' =>
                            'mc-navigation__link is-active',
                    ]
                ) ?>

                <?= Html::a(
                    '淘汰赛晋级图',
                    ['/match/bracket'],
                    ['class' => 'mc-navigation__link']
                ) ?>

            </nav>

        </div>

    </div>


    <!-- ======================================
         比赛详细内容
         ====================================== -->

    <section class="md-content">

        <div class="wc-container md-content__layout">


            <!-- 左侧：比赛事件 -->

            <div class="md-events">

                <header class="md-section-heading">

                    <h2>比赛事件</h2>

                    <p>按比赛时间排列的进球记录。</p>

                </header>

                <?php if ($goals === []): ?>

                    <div class="md-events-empty">

                        <span
                            class="md-events-empty__symbol"
                            aria-hidden="true"
                        >
                            —
                        </span>

                        <h3>暂无进球记录</h3>

                        <p>

                            <?= $hasScore
                                && (int) $match->home_score === 0
                                && (int) $match->away_score === 0
                                ? '本场比分为 0 - 0。'
                                : '当前数据库尚未提供本场进球事件。' ?>

                        </p>

                    </div>

                <?php else: ?>


                    <!-- 时间线双方球队标题 -->

                    <div class="md-events__teams">

                        <div class="md-events__team">

                            <?= $renderFlag(
                                $homeTeam,
                                'md-events__flag'
                            ) ?>

                            <?= Html::encode($homeName) ?>

                        </div>

                        <span>进球时间</span>

                        <div class="md-events__team">

                            <?= Html::encode($awayName) ?>

                            <?= $renderFlag(
                                $awayTeam,
                                'md-events__flag'
                            ) ?>

                        </div>

                    </div>


                    <!-- 进球时间线 -->

                    <div class="md-timeline">

                        <?php foreach ($goals as $goal): ?>

                            <?php

                            $side = 'unknown';

                            if (
                                (int) $goal->team_id
                                === (int) $match->home_team_id
                            ) {

                                $side = 'home';

                            } elseif (
                                (int) $goal->team_id
                                === (int) $match->away_team_id
                            ) {

                                $side = 'away';

                            }

                            $minute = $goal->minute_text
                                ? $goal->minute_text . '′'
                                : '—';

                            ?>

                            <article class="md-event md-event--<?= $side ?>">

                                <div class="md-event__minute">

                                    <?= Html::encode($minute) ?>

                                </div>

                                <div class="md-event__marker">

                                    <span aria-hidden="true">
                                        ⚽
                                    </span>

                                </div>

                                <div class="md-event__body">

                                    <strong class="md-event__scorer">

                                        <?= Html::encode(
                                            $goal->scorer_name
                                        ) ?>

                                    </strong>

                                    <span class="md-event__type">

                                        <?= Html::encode(
                                            $goal->typeLabel
                                        ) ?>

                                    </span>

                                    <span class="md-event__team">

                                        记分归属：

                                        <?= Html::encode(
                                            $goal->team
                                                ? $goal->team->name
                                                : '未知球队'
                                        ) ?>

                                    </span>

                                    <?= $renderSource(
                                        $goal->source_url
                                    ) ?>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                    <div class="md-events__status">

                        <?= Html::encode(
                            $match->statusLabel
                        ) ?>

                    </div>

                <?php endif; ?>

                <p class="md-events__notice">
                    进球事件根据数据库已收录记录展示。
                    点球大战结果以赛果说明为准。
                </p>

            </div>


            <!-- 右侧：比赛资料 -->

            <aside
                class="md-information"
                aria-labelledby="md-info-title"
            >

                <header class="md-section-heading">

                    <h2 id="md-info-title">
                        比赛资料
                    </h2>

                    <p>比赛相关基础信息。</p>

                </header>

                <dl class="md-facts">

                    <div class="md-facts__item">

                        <dt>比赛阶段</dt>

                        <dd>

                            <?= Html::encode(
                                $match->stage
                            ) ?>

                        </dd>

                    </div>

                    <div class="md-facts__item">

                        <dt>开球时间</dt>

                        <dd>

                            <?= Html::encode(
                                $match->shanghaiKickoff
                            ) ?>

                            <small>北京时间</small>

                        </dd>

                    </div>

                                        <div class="md-facts__item">

                        <dt>比赛场馆</dt>

                        <dd>

                            <?php if ($stadium !== null): ?>

                                <?= Html::a(
                                    Html::encode($stadium->name) . ' ↗',
                                    [
                                        '/football/stadiums',
                                        '#' => 'stadium-' . (int) $stadium->id,
                                    ],
                                    [
                                        'class' => 'md-facts__venue-link',
                                        'title' => '查看场馆资料',
                                    ]
                                ) ?>

                            <?php else: ?>

                                暂无场馆资料

                            <?php endif; ?>

                        </dd>

                    </div>

                    <?php if ($stadium !== null): ?>

                        <div class="md-facts__item">

                            <dt>所在城市</dt>

                            <dd>

                                <?= Html::encode(
                                    $stadium->city
                                ) ?>

                            </dd>

                        </div>

                        <?php if ($stadium->capacity !== null): ?>

                            <div class="md-facts__item">

                                <dt>场馆容量</dt>

                                <dd>

                                    <?= number_format(
                                        (int) $stadium->capacity
                                    ) ?>

                                    人

                                </dd>

                            </div>

                        <?php endif; ?>

                    <?php endif; ?>

                    <div class="md-facts__item">

                        <dt>比赛状态</dt>

                        <dd>

                            <?= Html::encode(
                                $match->statusLabel
                            ) ?>

                        </dd>

                    </div>

                    <?php if ($match->result_note): ?>

                        <div class="md-facts__item">

                            <dt>赛果说明</dt>

                            <dd>

                                <?= Html::encode(
                                    $match->result_note
                                ) ?>

                            </dd>

                        </div>

                    <?php endif; ?>

                </dl>


                <!-- 数据来源 -->

                <div class="md-information__source">

                    <?php if ($match->source_url): ?>

                        <?= $renderSource(
                            $match->source_url,
                            '查看比赛原始资料 ↗'
                        ) ?>

                    <?php endif; ?>

                                        <?php if ($stadium !== null): ?>

                        <?= Html::a(
                            '浏览本场比赛场馆 →',
                            [
                                '/football/stadiums',
                                '#' => 'stadium-' . (int) $stadium->id,
                            ],
                            ['class' => 'md-source-link']
                        ) ?>

                        <?= Html::a(
                            '查看该场馆全部赛事 →',
                            [
                                '/match/index',
                                'stadium_id' => $stadium->id,
                            ],
                            ['class' => 'md-source-link']
                        ) ?>

                    <?php else: ?>

                        <?= Html::a(
                            '探索世界杯场馆 →',
                            ['/football/stadiums'],
                            ['class' => 'md-source-link']
                        ) ?>

                    <?php endif; ?>

                </div>

            </aside>

        </div>


        <!-- 页面底部返回入口 -->

        <div class="wc-container">

            <div class="md-bottom">

                <?= Html::a(
                    '← 返回赛程与比分',
                    ['/match/index'],
                    ['class' => 'md-bottom__link']
                ) ?>

                <?= Html::a(
                    '查看淘汰赛晋级图 →',
                    ['/match/bracket'],
                    ['class' => 'md-bottom__link']
                ) ?>

            </div>

        </div>

    </section>

</div>