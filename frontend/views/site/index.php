<?php

/* @var $this yii\web\View */

use frontend\assets\AppAsset;
use yii\helpers\Html;

$this->title = 'WorldCup2026';
$this->params['hideBreadcrumbs'] = true;
$this->params['fullBleed'] = true;
$this->registerCssFile('@web/css/home.css', [
    'depends' => [AppAsset::className()],
]);

$this->registerJsFile('@web/js/home.js', [
    'depends' => [AppAsset::className()],
    'position' => \yii\web\View::POS_END,
]);

// 根据 FIFA 球队代码加载本地国旗。
$renderTeamFlag = static function ($team, $class = '') {
    if ($team === null) {
        return '';
    }

    $code = strtoupper(trim((string) $team->country_code));

    if (!preg_match('/^[A-Z]{3}$/D', $code)) {
        return '';
    }

    $file = Yii::getAlias('@webroot/images/flags/')
        . $code
        . '.svg';

    if (!is_file($file)) {
        return '';
    }

    return Html::img(
        '@web/images/flags/' . $code . '.svg',
        [
            'class' => $class,
            'alt' => '',
            'aria-hidden' => 'true',
            'width' => 80,
            'height' => 60,
            'loading' => 'lazy',
        ]
    );
};
?>
<div class="site-index site-index--home">
    <section id="home-hero" class="home-hero" aria-labelledby="home-hero-title">
        <?= Html::img('@web/images/home/hero-original.jpg', [
            'class' => 'home-hero__media',
            'alt' => '',
            'aria-hidden' => 'true',
        ]) ?>
        <div class="home-hero__shade" aria-hidden="true"></div>

        <div class="wc-container home-hero__inner">
            <div class="home-hero__content">
                <p class="home-hero__eyebrow">THE GLOBAL PITCH · 2026</p>
                <h1 id="home-hero-title" class="home-hero__title">
                    <span>WORLD CUP</span>
                    <strong>2026</strong>
                </h1>
                <p class="home-hero__subtitle">每一场比赛，都值得铭记。</p>
                <div class="home-hero__actions">
                    <?= Html::a(
                        '探索赛事 <span aria-hidden="true">→</span>',
                        ['/match/index'],
                        ['class' => 'btn home-hero__primary']
                    ) ?>
                    <?= Html::a(
                        '了解参赛球队',
                        ['/football/teams'],
                        ['class' => 'btn home-hero__secondary']
                    ) ?>
                </div>
            </div>
        </div>

                <div class="home-hero__edge" aria-hidden="true"></div>

        <!-- Hero 滚动引导 -->
        <a
            href="#home-matches"
            class="home-hero__scroll-cue"
            aria-label="向下浏览赛事精选"
        >
            <span class="home-hero__scroll-label">
                SCROLL TO EXPLORE
            </span>

            <span
                class="home-hero__scroll-line"
                aria-hidden="true"
            ></span>
        </a>

    </section>
</div>

        <!-- 首页赛事精选 -->
    <section
        id="home-matches"
        class="home-matches"
        aria-labelledby="home-matches-title"
    >
        <div class="wc-container">

            <div class="home-matches__heading">
                <div>
                    <p class="home-matches__eyebrow">WORLD CUP 2026 / MATCHES</p>
                    <h2 id="home-matches-title">赛事精选<span class="home-matches__dot">.</span></h2>
                    <p class="home-matches__intro">回顾绿茵场上的精彩对决。</p>
                </div>

                <?= Html::a(
                    '查看全部赛程 <span aria-hidden="true">↗</span>',
                    ['/match/index'],
                    ['class' => 'home-matches__more']
                ) ?>
            </div>

            <?php if (empty($featuredMatches)): ?>
                <div class="home-matches__empty">
                    暂无已完赛的比赛记录。
                </div>
            <?php else: ?>

                <?php
                // 第一场作为焦点赛事，其余作为紧凑列表。
                $mainMatch = $featuredMatches[0];
                $otherMatches = array_slice($featuredMatches, 1);
                ?>

                <a class="home-match-feature"
                   href="<?= \yii\helpers\Url::to(['/match/view', 'id' => $mainMatch->id]) ?>">

                    <div class="home-match-feature__meta">
                        <span>FEATURED MATCH</span>
                        <span><?= Html::encode($mainMatch->stage) ?></span>
                    </div>

                    <div class="home-match-feature__scoreboard">

    <!-- 主队 -->
    <div class="home-match-feature__team">

        <?= $renderTeamFlag(
            $mainMatch->homeTeam,
            'home-match-feature__flag'
        ) ?>

        <span class="home-match-feature__code">
            <?= Html::encode($mainMatch->homeTeam->country_code) ?>
        </span>

        <h3>
            <?= Html::encode($mainMatch->homeTeam->name) ?>
        </h3>

    </div>

    <!-- 比分 -->
    <div class="home-match-feature__result">

        <strong>
            <?= Html::encode($mainMatch->scoreLabel) ?>
        </strong>

        <span>
            <?= Html::encode($mainMatch->statusLabel) ?>
        </span>

    </div>

    <!-- 客队 -->
    <div class="home-match-feature__team home-match-feature__team--away">

        <?= $renderTeamFlag(
            $mainMatch->awayTeam,
            'home-match-feature__flag'
        ) ?>

        <span class="home-match-feature__code">
            <?= Html::encode($mainMatch->awayTeam->country_code) ?>
        </span>

        <h3>
            <?= Html::encode($mainMatch->awayTeam->name) ?>
        </h3>

    </div>

</div>

                    <div class="home-match-feature__bottom">
                        <span><?= Html::encode($mainMatch->shanghaiKickoff) ?> · 北京时间</span>
                        <span>
                            <?= Html::encode(
                                $mainMatch->stadium === null
                                    ? '场馆待补充'
                                    : $mainMatch->stadium->name
                            ) ?>
                        </span>
                        <span class="home-match-feature__detail">比赛详情 ↗</span>
                    </div>
                </a>

                <?php if (!empty($otherMatches)): ?>
                    <div class="home-match-list">
                        <?php foreach ($otherMatches as $match): ?>
                            <a class="home-match-list__item"
                               href="<?= \yii\helpers\Url::to(['/match/view', 'id' => $match->id]) ?>">

                                <div class="home-match-list__meta">
                                    <span><?= Html::encode($match->stage) ?></span>
                                    <small><?= Html::encode($match->shanghaiKickoff) ?></small>
                                </div>

                                <div class="home-match-list__teams">

    <span class="home-match-list__team">

        <?= $renderTeamFlag(
            $match->homeTeam,
            'home-match-list__flag'
        ) ?>

        <span>
            <?= Html::encode($match->homeTeam->name) ?>
        </span>

    </span>

    <span class="home-match-list__versus">—</span>

    <span class="home-match-list__team">

        <?= $renderTeamFlag(
            $match->awayTeam,
            'home-match-list__flag'
        ) ?>

        <span>
            <?= Html::encode($match->awayTeam->name) ?>
        </span>

    </span>

</div>

                                <strong class="home-match-list__score">
                                    <?= Html::encode($match->scoreLabel) ?>
                                </strong>

                                <span class="home-match-list__arrow" aria-hidden="true">↗</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </section>
<!-- 探索世界杯 -->
<section id="home-explore" class="home-explore" aria-labelledby="home-explore-title">
    <div class="wc-container">

        <header class="home-explore__header">
            <p class="home-explore__eyebrow">BEYOND THE MATCH</p>

            <h2 id="home-explore-title" class="home-explore__title">
                探索世界杯<span>.</span>
            </h2>

            <p class="home-explore__description">
                从国家队到球场，发现比赛之外的精彩。
            </p>
        </header>

        <?php
        $exploreStats = [
            [
                'key' => 'teams',
                'label' => '参赛球队',
                'english' => 'NATIONAL TEAMS',
                'route' => ['/football/teams'],
            ],
            [
                'key' => 'players',
                'label' => '参赛球员',
                'english' => 'PLAYERS',
                'route' => ['/football/players'],
            ],
            [
                'key' => 'stadiums',
                'label' => '比赛场馆',
                'english' => 'VENUES',
                'route' => ['/football/stadiums'],
            ],
        ];
        ?>

        <!-- 世界杯数据概览 -->
        <div class="home-explore__stats">
            <?php foreach ($exploreStats as $stat): ?>
                <a
                    class="home-explore__stat"
                    href="<?= Html::encode(\yii\helpers\Url::to($stat['route'])) ?>"
                >
                    <span class="home-explore__stat-english">
                        <?= Html::encode($stat['english']) ?>
                    </span>

                    <strong class="home-explore__stat-number">
                        <?= number_format((int) ($worldCupStats[$stat['key']] ?? 0)) ?>
                    </strong>

                    <span class="home-explore__stat-bottom">
                        <span><?= Html::encode($stat['label']) ?></span>

                        <span
                            class="home-explore__arrow"
                            aria-hidden="true"
                        >↗</span>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- 国家队巡礼 -->
        <div class="home-explore__teams">
            <div class="home-explore__teams-header">
                <div>
                    <p class="home-explore__section-label">
                        THE NATIONAL TEAMS
                    </p>

                    <h3>国家队巡礼</h3>
                </div>

                <?= Html::a(
                    '探索全部球队 <span aria-hidden="true">↗</span>',
                    ['/football/teams'],
                    ['class' => 'home-explore__all-link']
                ) ?>
            </div>

            <?php if (!empty($featuredTeams)): ?>
                <div class="home-explore__team-grid">

                    <?php foreach ($featuredTeams as $team): ?>
                        <?php
                        $countryCode = strtoupper((string) $team->country_code);

                        $hasFlag = preg_match('/^[A-Z]{3}$/D', $countryCode)
                            && is_file(
                                Yii::getAlias('@webroot/images/flags/')
                                . $countryCode
                                . '.svg'
                            );
                        ?>

                        <a
                            class="home-explore__team"
                            href="<?= Html::encode(\yii\helpers\Url::to([
                                '/football/players',
                                'team_id' => $team->id,
                            ])) ?>"
                        >
                            <div class="home-explore__team-top">

                                <span class="home-explore__flag">
                                    <?php if ($hasFlag): ?>
                                        <?= Html::img(
                                            '@web/images/flags/' . $countryCode . '.svg',
                                            [
                                                'alt' => '',
                                                'loading' => 'lazy',
                                                'width' => 64,
                                                'height' => 48,
                                            ]
                                        ) ?>
                                    <?php else: ?>
                                        <span class="home-explore__flag-fallback">
                                            <?= Html::encode($countryCode ?: '—') ?>
                                        </span>
                                    <?php endif; ?>
                                </span>

                                <span class="home-explore__team-code">
                                    <?= Html::encode($countryCode) ?>
                                </span>
                            </div>

                            <strong class="home-explore__team-name">
                                <?= Html::encode($team->name) ?>
                            </strong>

                            <div class="home-explore__team-bottom">
                                <span class="home-explore__team-group">
                                    <?= $team->group_name
                                        ? 'GROUP ' . Html::encode($team->group_name)
                                        : '未分组' ?>
                                </span>

                                <span
                                    class="home-explore__arrow"
                                    aria-hidden="true"
                                >↗</span>
                            </div>
                        </a>

                    <?php endforeach; ?>

                </div>
            <?php else: ?>
                <p class="home-explore__empty">
                    暂无参赛球队数据。
                </p>
            <?php endif; ?>
        </div>

    </div>
</section>
<!-- 首页新闻资讯 -->
<section id="home-news" class="home-news" aria-labelledby="home-news-title">
    <div class="wc-container">

        <!-- 区域标题 -->
        <header class="home-news__header">

            <div>
                <p class="home-news__eyebrow">
                    WORLD CUP 2026 / NEWSROOM
                </p>

                <h2 id="home-news-title" class="home-news__title">
                    世界杯资讯<span>.</span>
                </h2>

                <p class="home-news__description">
                    关注赛场内外，了解世界杯动态。
                </p>
            </div>

            <?= Html::a(
                '查看全部新闻 <span aria-hidden="true">↗</span>',
                ['/news/index'],
                ['class' => 'home-news__all-link']
            ) ?>

        </header>

        <!-- 新闻图文布局 -->
        <div class="home-news__layout">

            <!-- 左侧：新闻栏目主题摄影 -->
            <figure class="home-news__visual">

                <div class="home-news__photo">

                    <?= Html::img(
                        '@web/images/home/newsroom.jpg',
                        [
                            'class' => 'home-news__image',
                            'alt' => '世界杯赛场看台上热情欢呼的球迷',
                            'loading' => 'lazy',
                            'width' => 2048,
                            'height' => 1365,
                        ]
                    ) ?>

                    <div
                        class="home-news__shade"
                        aria-hidden="true"
                    ></div>

                    <div class="home-news__photo-content">

                        <p class="home-news__photo-label">
                            BEYOND THE PITCH
                        </p>

                        <p class="home-news__photo-title">
                            足球，不止于比赛。
                        </p>

                    </div>

                </div>

                <figcaption class="home-news__caption">
                    WORLD CUP CULTURE
                    <span></span>
                    足球与世界
                </figcaption>

            </figure>

            <!-- 右侧：最新新闻 -->
            <div class="home-news__feed">

                <div class="home-news__feed-header">

                    <h3>最新发布</h3>

                    <span>LATEST NEWS</span>

                </div>

                <?php if (!empty($latestNews)): ?>

                    <div class="home-news__list">

                        <?php foreach ($latestNews as $article): ?>

                            <?php
                            // 优先使用新闻摘要，摘要为空时使用正文。
                            $excerpt = trim(
                                (string) ($article->summary ?: $article->body)
                            );

                            // 去除多余换行与空白。
                            $excerpt = preg_replace('/\s+/u', ' ', $excerpt);

                            // 限制首页摘要长度。
                            if (mb_strlen($excerpt, 'UTF-8') > 76) {
                                $excerpt = mb_substr(
                                    $excerpt,
                                    0,
                                    76,
                                    'UTF-8'
                                ) . '…';
                            }
                            ?>

                            <a
                                class="home-news__article"
                                href="<?= Html::encode(
                                    \yii\helpers\Url::to([
                                        '/news/view',
                                        'id' => $article->id,
                                    ])
                                ) ?>"
                            >

                                <div class="home-news__article-meta">

                                    <span class="home-news__category">
                                        <?= Html::encode(
                                            $article->category
                                                ? $article->category->name
                                                : '新闻资讯'
                                        ) ?>
                                    </span>

                                    <time>
                                        <?= Html::encode(
                                            substr(
                                                $article->publishedAtLabel,
                                                0,
                                                10
                                            )
                                        ) ?>
                                    </time>

                                </div>

                                <h4 class="home-news__article-title">
                                    <?= Html::encode($article->title) ?>
                                </h4>

                                <p class="home-news__excerpt">
                                    <?= Html::encode($excerpt) ?>
                                </p>

                                <span
                                    class="home-news__article-arrow"
                                    aria-hidden="true"
                                >↗</span>

                            </a>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <!-- 暂无已发布新闻时的空状态 -->
                    <div class="home-news__empty">

                        <span class="home-news__empty-label">
                            THE NEWSROOM
                        </span>

                        <h4>暂无新闻发布</h4>

                        <p>
                            最新世界杯资讯将在这里呈现。
                        </p>

                        <?= Html::a(
                            '进入新闻中心 <span aria-hidden="true">↗</span>',
                            ['/news/index'],
                            ['class' => 'home-news__empty-link']
                        ) ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>
</section>
<!-- 首页团队门户 -->
<section id="home-portal" class="home-portal" aria-labelledby="home-portal-title">
    <div class="wc-container">

        <div class="home-portal__inner">

            <!-- 顶部栏目标识 -->
            <div class="home-portal__heading">

                <span>BEHIND THE PROJECT</span>

                <span>WORLD CUP / 2026</span>

            </div>

            <div class="home-portal__layout">

                <!-- 左侧：项目介绍 -->
                <div class="home-portal__intro">

                    <h2
                        id="home-portal-title"
                        class="home-portal__title"
                    >
                        不止于足球，<br>
                        <span>也关乎创造。</span>
                    </h2>

                    <p class="home-portal__slogan">
                        BUILT BY STUDENTS. INSPIRED BY FOOTBALL.
                    </p>

                    <p class="home-portal__description">
                        WorldCup2026 是一个融合世界杯数据、
                        新闻资讯与团队协作的互联网数据库开发课程项目。
                        我们希望通过技术与设计，让足球世界更加触手可及。
                    </p>

                    <?= Html::a(
                        '了解项目背后的团队 <span aria-hidden="true">↗</span>',
                        ['/team/index'],
                        ['class' => 'home-portal__intro-link']
                    ) ?>

                </div>

                <!-- 右侧：团队门户功能入口 -->
                <?php
                $portalLinks = [
                    [
                        'number' => '01',
                        'english' => 'THE TEAM',
                        'title' => '团队介绍',
                        'description' => '认识项目背后的开发者',
                        'route' => ['/team/index'],
                    ],
                    [
                        'number' => '02',
                        'english' => 'COURSEWORK',
                        'title' => '课程作业',
                        'description' => '浏览学习资料与项目成果',
                        'route' => ['/assignment/index'],
                    ],
                    [
                        'number' => '03',
                        'english' => 'GUESTBOOK',
                        'title' => '留言交流',
                        'description' => '分享你的想法与建议',
                        'route' => ['/guestbook/index'],
                    ],
                ];
                ?>

                <nav
                    class="home-portal__links"
                    aria-label="团队门户功能入口"
                >

                    <?php foreach ($portalLinks as $item): ?>

                        <a
                            class="home-portal__link"
                            href="<?= Html::encode(
                                \yii\helpers\Url::to($item['route'])
                            ) ?>"
                        >

                            <span class="home-portal__link-number">
                                <?= Html::encode($item['number']) ?>
                            </span>

                            <span class="home-portal__link-content">

                                <span class="home-portal__link-english">
                                    <?= Html::encode($item['english']) ?>
                                </span>

                                <strong class="home-portal__link-title">
                                    <?= Html::encode($item['title']) ?>
                                </strong>

                                <span class="home-portal__link-description">
                                    <?= Html::encode($item['description']) ?>
                                </span>

                            </span>

                            <span
                                class="home-portal__link-arrow"
                                aria-hidden="true"
                            >↗</span>

                        </a>

                    <?php endforeach; ?>

                </nav>

            </div>

            <!-- 区域底部项目标识 -->
            <div class="home-portal__signature">

                <span>WORLDCUP2026 / UNIVERSITY PROJECT</span>

                <span>互联网数据库开发</span>

            </div>

        </div>

    </div>
</section>
<!-- 首页阅读进度指示 -->
<div
    class="home-reading-progress"
    aria-hidden="true"
>
    <span class="home-reading-progress__bar"></span>
</div>

<!-- 首页悬浮章节导航 -->
<nav
    class="home-section-nav"
    aria-label="首页章节导航"
>
    <span class="home-section-nav__label">
        EXPLORE
    </span>

    <a
        href="#home-hero"
        class="home-section-nav__link is-active"
        aria-current="location"
    >
        <span class="home-section-nav__text">首页</span>
        <span class="home-section-nav__indicator"></span>
    </a>

    <a
        href="#home-matches"
        class="home-section-nav__link"
    >
        <span class="home-section-nav__text">赛事</span>
        <span class="home-section-nav__indicator"></span>
    </a>

    <a
        href="#home-explore"
        class="home-section-nav__link"
    >
        <span class="home-section-nav__text">探索</span>
        <span class="home-section-nav__indicator"></span>
    </a>

    <a
        href="#home-news"
        class="home-section-nav__link"
    >
        <span class="home-section-nav__text">资讯</span>
        <span class="home-section-nav__indicator"></span>
    </a>

    <a
        href="#home-portal"
        class="home-section-nav__link"
    >
        <span class="home-section-nav__text">团队</span>
        <span class="home-section-nav__indicator"></span>
    </a>

    <span class="home-section-nav__counter">
        01 / 05
    </span>
</nav>