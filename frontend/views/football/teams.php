<?php

/**
 * 国家队列表。
 * @author lzs
 * @date 2026-11-20
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;

$this->title = '世界杯国家队';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/football.css', [
    'depends' => [AppAsset::className()],
]);


/*
 * 渲染本地国家队国旗。
 */

$renderFlag = static function ($team) {

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
            ['class' => 'ft-team__flag-fallback']
        );
    }

    return Html::img(
        '@web/images/flags/' . $code . '.svg',
        [
            'class' => 'ft-team__flag',
            'alt' => '',
            'aria-hidden' => 'true',
            'loading' => 'lazy',
            'width' => 80,
            'height' => 60,
        ]
    );
};


/*
 * 校验原始资料链接。
 */

$isValidSource = static function ($url) {

    return is_string($url)
        && filter_var($url, FILTER_VALIDATE_URL)
        && in_array(
            strtolower(
                (string) parse_url($url, PHP_URL_SCHEME)
            ),
            ['http', 'https'],
            true
        );
};

?>

<div class="football-explore football-teams">

    <!-- ======================================
         国家队摄影页头
         ====================================== -->

    <section
        class="ft-hero"
        aria-labelledby="ft-hero-title"
    >

        <div class="ft-hero__content">

            <div class="ft-hero__copy">

                <span class="ft-hero__eyebrow">
                    THE WORLD IN PLAY / 2026
                </span>

                <h1 id="ft-hero-title" class="ft-hero__title">
                    世界，<br>
                    为足球而来。
                </h1>

                <p class="ft-hero__description">
                    不同的旗帜，共同的足球梦想。
                    探索汇聚于世界杯舞台的国家队。
                </p>


                <!-- 数据库真实统计 -->

                <div class="ft-hero__stats">

                    <div class="ft-hero__stat">

                        <strong>
                            <?= number_format($totalTeams) ?>
                        </strong>

                        <span>支国家队</span>

                    </div>

                    <div class="ft-hero__stat">

                        <strong>
                            <?= number_format($totalGroups) ?>
                        </strong>

                        <span>个小组</span>

                    </div>

                </div>

            </div>

        </div>


        <!-- 右侧真实摄影 -->

        <div class="ft-hero__visual">

            <?= Html::img(
                '@web/images/team/team-hero-center-circle.jpg',
                [
                    'class' => 'ft-hero__image',
                    'alt' => '足球运动员在球场中心圆附近集结',
                    'fetchpriority' => 'high',
                ]
            ) ?>

            <span class="ft-hero__caption">
                ONE GAME. ONE WORLD.
            </span>

        </div>

    </section>


    <!-- ======================================
         探索世界杯公共导航
         ====================================== -->

    <div class="football-navigation">

        <div class="wc-container">

            <?= $this->render('_nav') ?>

        </div>

    </div>


    <!-- ======================================
         国家队名录
         ====================================== -->

    <section
        class="ft-directory"
        aria-labelledby="ft-directory-title"
    >

        <div class="wc-container">

            <header class="ft-directory__header">

                <div>

                    <h2 id="ft-directory-title">
                        参赛国家队
                    </h2>

                    <p>
                        按世界杯小组浏览球队，
                        或搜索你关注的国家队。
                    </p>

                </div>


                <!-- 球队搜索 -->

                <?= Html::beginForm(
                    ['/football/teams'],
                    'get',
                    ['class' => 'ft-search']
                ) ?>

                    <?= Html::hiddenInput(
                        'group',
                        $selectedGroup
                    ) ?>

                    <?= Html::label(
                        '搜索国家队',
                        'ft-keyword',
                        ['class' => 'sr-only']
                    ) ?>

                    <?= Html::textInput(
                        'keyword',
                        $keyword,
                        [
                            'id' => 'ft-keyword',
                            'class' => 'ft-search__input',
                            'placeholder' => '国家队名称或代码',
                            'maxlength' => 100,
                        ]
                    ) ?>

                    <?= Html::submitButton(
                        '搜索',
                        ['class' => 'ft-search__submit']
                    ) ?>

                <?= Html::endForm() ?>

            </header>


            <!-- ======================================
                 小组导航
                 ====================================== -->

            <nav
                class="ft-group-nav"
                aria-label="世界杯小组筛选"
            >

                <?= Html::a(
                    '全部',
                    [
                        '/football/teams',
                        'keyword' => $keyword,
                    ],
                    [
                        'class' => 'ft-group-nav__link'
                            . (
                                $selectedGroup === ''
                                    ? ' is-active'
                                    : ''
                            ),
                        'aria-current' =>
                            $selectedGroup === ''
                                ? 'page'
                                : null,
                    ]
                ) ?>

                <?php foreach ($groups as $group): ?>

                    <?php
                    $active = $selectedGroup === $group;
                    ?>

                    <?= Html::a(
                        Html::encode($group),
                        [
                            '/football/teams',
                            'group' => $group,
                            'keyword' => $keyword,
                        ],
                        [
                            'class' => 'ft-group-nav__link'
                                . ($active ? ' is-active' : ''),
                            'aria-current' =>
                                $active ? 'page' : null,
                        ]
                    ) ?>

                <?php endforeach; ?>

            </nav>


            <!-- 查询结果说明 -->

            <div class="ft-results">

                <span>

                    <?php if ($keyword !== ''): ?>

                        搜索「<?= Html::encode($keyword) ?>」 ·

                    <?php endif; ?>

                    <?php if ($selectedGroup !== ''): ?>

                        <?= Html::encode($selectedGroup) ?> 组 ·

                    <?php endif; ?>

                    共 <?= number_format($filteredCount) ?> 支球队

                </span>

                <?php if (
                    $keyword !== ''
                    || $selectedGroup !== ''
                ): ?>

                    <?= Html::a(
                        '清除筛选',
                        ['/football/teams'],
                        ['class' => 'ft-results__reset']
                    ) ?>

                <?php endif; ?>

            </div>


            <!-- ======================================
                 小组及国家队
                 ====================================== -->

            <?php if (!empty($groupedTeams)): ?>

                <div class="ft-groups">

                    <?php foreach (
                        $groupedTeams as $group => $teams
                    ): ?>

                        <section class="ft-group">

                            <header class="ft-group__heading">

                                <span class="ft-group__letter">

                                    <?= Html::encode($group) ?>

                                </span>

                                <div class="ft-group__title">

                                    <h3>

                                        <?= Html::encode(
                                            $group === '未分组'
                                                ? $group
                                                : $group . ' 组'
                                        ) ?>

                                    </h3>

                                    <span>

                                        <?= count($teams) ?> 支球队

                                    </span>

                                </div>

                            </header>


                            <div class="ft-group__teams">

                                <?php foreach ($teams as $team): ?>

                                    <article class="ft-team">

                                        <?= Html::a(
                                            $renderFlag($team),
                                            [
                                                '/football/players',
                                                'team_id' => $team->id,
                                            ],
                                            [
                                                'class' => 'ft-team__flag-link',
                                                'aria-label' =>
                                                    '查看'
                                                    . $team->name
                                                    . '球员',
                                            ]
                                        ) ?>

                                        <div class="ft-team__identity">

                                            <span class="ft-team__code">

                                                <?= Html::encode(
                                                    $team->country_code
                                                ) ?>

                                            </span>

                                            <h4>

                                                <?= Html::a(
                                                    Html::encode(
                                                        $team->name
                                                    ),
                                                    [
                                                        '/football/players',
                                                        'team_id' => $team->id,
                                                    ],
                                                    [
                                                        'class' =>
                                                            'ft-team__name',
                                                    ]
                                                ) ?>

                                            </h4>

                                        </div>


                                        <!-- 业务关联入口 -->

                                        <div class="ft-team__links">

                                            <?= Html::a(
                                                '球员名单',
                                                [
                                                    '/football/players',
                                                    'team_id' => $team->id,
                                                ]
                                            ) ?>

                                            <?= Html::a(
                                                '相关赛事 ↗',
                                                [
                                                    '/match/index',
                                                    'team_id' => $team->id,
                                                ]
                                            ) ?>

                                        </div>


                                        <!-- 原始资料来源 -->

                                        <?php if (
                                            $isValidSource($team->source_url)
                                        ): ?>

                                            <?= Html::a(
                                                '资料来源 ↗',
                                                $team->source_url,
                                                [
                                                    'class' =>
                                                        'ft-team__source',
                                                    'target' => '_blank',
                                                    'rel' =>
                                                        'noopener noreferrer',
                                                ]
                                            ) ?>

                                        <?php endif; ?>

                                    </article>

                                <?php endforeach; ?>

                            </div>

                        </section>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="ft-empty">

                    <h3>未找到符合条件的国家队</h3>

                    <p>
                        尝试使用其他球队名称、代码或小组进行搜索。
                    </p>

                    <?= Html::a(
                        '查看全部国家队 →',
                        ['/football/teams'],
                        ['class' => 'ft-empty__link']
                    ) ?>

                </div>

            <?php endif; ?>


            <!-- 页面底部说明 -->

            <div class="ft-directory__footer">

                <p>
                    国家队资料根据项目数据库中收录的
                    世界杯参赛球队信息展示。
                </p>

                <?= Html::a(
                    '探索世界杯球员 →',
                    ['/football/players'],
                    ['class' => 'ft-directory__next']
                ) ?>

            </div>

        </div>

    </section>

</div>