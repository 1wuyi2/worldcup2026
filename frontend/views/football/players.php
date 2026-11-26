<?php

/**
 * 球员资料，可按球队查看。
 * @author lzs
 * @date 2026-11-20
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = $team === null
    ? '世界杯球员'
    : $team->name . ' · 球员';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/football.css', [
    'depends' => [AppAsset::className()],
]);

$players = $provider->getModels();
$filteredCount = $provider->getTotalCount();
$pagination = $provider->getPagination();

$selectedTeamId = $team !== null
    ? (string) $team->id
    : '';

$hasFilters = $team !== null
    || $keyword !== ''
    || $position !== '';


/*
 * 本地国旗渲染。
 */

$renderFlag = static function (
    $team,
    $class = 'fp-player__flag'
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
            ['class' => 'fp-flag-fallback']
        );
    }

    return Html::img(
        '@web/images/flags/' . $code . '.svg',
        [
            'class' => $class,
            'alt' => '',
            'aria-hidden' => 'true',
            'loading' => 'lazy',
        ]
    );
};


/*
 * 校验外部资料链接。
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

<div class="football-explore football-players">

    <!-- ======================================
         球员页面摄影页头
         ====================================== -->

    <section
        class="fp-hero"
        aria-labelledby="fp-hero-title"
    >

        <?= Html::img(
            '@web/images/player/player-ball-control.jpg',
            [
                'class' => 'fp-hero__image',
                'alt' => '',
                'aria-hidden' => 'true',
                'fetchpriority' => 'high',
                'width' => 3849,
                'height' => 2249,
            ]
        ) ?>

        <div
            class="fp-hero__shade"
            aria-hidden="true"
        ></div>

        <div class="wc-container fp-hero__inner">

            <div class="fp-hero__copy">

                <span class="fp-hero__eyebrow">
                    THE PLAYERS / 2026
                </span>

                <h1 id="fp-hero-title" class="fp-hero__title">
                    绿茵之上，<br>
                    每一个名字。
                </h1>

                <p class="fp-hero__description">
                    从守护球门到攻入进球，
                    认识世界杯舞台上的每一位球员。
                </p>

                <div class="fp-hero__stat">

                    <strong>
                        <?= number_format($totalPlayers) ?>
                    </strong>

                    <span>名参赛球员</span>

                </div>

            </div>

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
         球员名录
         ====================================== -->

    <section
        class="fp-directory"
        aria-labelledby="fp-directory-title"
    >

        <div class="wc-container">


            <!-- 名录标题 -->

            <header class="fp-directory__header">

                <div class="fp-directory__identity">

                    <?php if ($team !== null): ?>

                        <?= $renderFlag(
                            $team,
                            'fp-directory__flag'
                        ) ?>

                    <?php endif; ?>

                    <div>

                        <h2 id="fp-directory-title">

                            <?= Html::encode(
                                $team === null
                                    ? '球员名录'
                                    : $team->name . ' · 球员名单'
                            ) ?>

                        </h2>

                        <p>

                            <?php if ($team !== null): ?>

                                <?= Html::encode(
                                    $team->country_code
                                ) ?>

                                <?php if ($team->group_name): ?>

                                    · <?= Html::encode(
                                        $team->group_name
                                    ) ?> 组

                                <?php endif; ?>

                                · <?= number_format(
                                    $teamPlayerCount
                                ) ?> 名球员

                            <?php else: ?>

                                按姓名、国家队或场上位置查找球员。

                            <?php endif; ?>

                        </p>

                    </div>

                </div>

                <?php if ($team !== null): ?>

                    <?= Html::a(
                        '查看球队赛事 ↗',
                        [
                            '/match/index',
                            'team_id' => $team->id,
                        ],
                        ['class' => 'fp-directory__team-link']
                    ) ?>

                <?php endif; ?>

            </header>


            <!-- ======================================
                 姓名及球队筛选
                 ====================================== -->

            <?= Html::beginForm(
                ['/football/players'],
                'get',
                ['class' => 'fp-filters']
            ) ?>

                <?= Html::hiddenInput(
                    'position',
                    $position
                ) ?>

                <div class="fp-filters__field">

                    <?= Html::label(
                        '球员姓名',
                        'fp-keyword'
                    ) ?>

                    <?= Html::textInput(
                        'keyword',
                        $keyword,
                        [
                            'id' => 'fp-keyword',
                            'class' => 'fp-filters__input',
                            'placeholder' => '输入球员姓名',
                            'maxlength' => 100,
                        ]
                    ) ?>

                </div>

                <div class="fp-filters__field">

                    <?= Html::label(
                        '所属国家队',
                        'fp-team'
                    ) ?>

                    <?= Html::dropDownList(
                        'team_id',
                        $selectedTeamId,
                        $teamOptions,
                        [
                            'id' => 'fp-team',
                            'class' => 'fp-filters__input',
                            'prompt' => '全部国家队',
                        ]
                    ) ?>

                </div>

                <div class="fp-filters__actions">

                    <?= Html::submitButton(
                        '查找球员',
                        ['class' => 'fp-filters__submit']
                    ) ?>

                    <?php if ($hasFilters): ?>

                        <?= Html::a(
                            '重置',
                            ['/football/players'],
                            ['class' => 'fp-filters__reset']
                        ) ?>

                    <?php endif; ?>

                </div>

            <?= Html::endForm() ?>


            <!-- ======================================
                 场上位置导航
                 ====================================== -->

            <nav
                class="fp-position-nav"
                aria-label="球员位置筛选"
            >

                <?= Html::a(
                    '全部位置',
                    [
                        '/football/players',
                        'team_id' => $selectedTeamId,
                        'keyword' => $keyword,
                    ],
                    [
                        'class' => 'fp-position-nav__link'
                            . (
                                $position === ''
                                    ? ' is-active'
                                    : ''
                            ),
                        'aria-current' =>
                            $position === ''
                                ? 'page'
                                : null,
                    ]
                ) ?>

                <?php foreach ($positions as $item): ?>

                    <?php
                    $isActive = $position === $item;
                    ?>

                    <?= Html::a(
                        Html::encode($item),
                        [
                            '/football/players',
                            'team_id' => $selectedTeamId,
                            'keyword' => $keyword,
                            'position' => $item,
                        ],
                        [
                            'class' => 'fp-position-nav__link'
                                . (
                                    $isActive
                                        ? ' is-active'
                                        : ''
                                ),
                            'aria-current' =>
                                $isActive ? 'page' : null,
                        ]
                    ) ?>

                <?php endforeach; ?>

            </nav>


            <!-- 查询结果数量 -->

            <div class="fp-results">

                <span>

                    <?php if ($keyword !== ''): ?>

                        搜索「<?= Html::encode($keyword) ?>」 ·

                    <?php endif; ?>

                    <?php if ($position !== ''): ?>

                        <?= Html::encode($position) ?> ·

                    <?php endif; ?>

                    共 <?= number_format(
                        (int) $filteredCount
                    ) ?> 名球员

                </span>

                <span>

                    <?= $team !== null
                        ? '按球衣号码排列'
                        : '按球员姓名排列' ?>

                </span>

            </div>


            <!-- ======================================
                 球员列表
                 ====================================== -->

            <?php if (!empty($players)): ?>

                <div class="fp-roster">


                    <!-- 列表标题 -->

                    <div class="fp-roster__heading">

                        <span>号码</span>

                        <span>球员姓名</span>

                        <span>国家队</span>

                        <span>位置</span>

                        <span>资料</span>

                    </div>


                    <?php foreach ($players as $player): ?>

                        <?php

                        $playerTeam = $player->team;

                        $number = $player->number !== null

                            ? str_pad(
                                (string) $player->number,
                                2,
                                '0',
                                STR_PAD_LEFT
                            )

                            : '—';

                        $positionClasses = [
                            '门将' => 'gk',
                            '后卫' => 'df',
                            '中场' => 'mf',
                            '前锋' => 'fw',
                        ];

                        $positionClass =
                            $positionClasses[$player->position]
                            ?? 'other';

                        ?>

                        <article class="fp-player">


                            <!-- 球衣号码 -->

                            <div class="fp-player__number">

                                <?= Html::encode($number) ?>

                            </div>


                            <!-- 球员姓名 -->

                            <div class="fp-player__identity">

                                <h3>

                                    <?= Html::encode(
                                        $player->name
                                    ) ?>

                                </h3>

                            </div>


                            <!-- 所属国家队 -->

                            <div class="fp-player__nationality">

                                <?php if ($playerTeam !== null): ?>

                                    <?= Html::a(
                                        $renderFlag($playerTeam)

                                        . Html::tag(
                                            'span',
                                            Html::encode(
                                                $playerTeam->name
                                            )
                                        ),

                                        [
                                            '/football/players',
                                            'team_id' =>
                                                $playerTeam->id,
                                        ],

                                        [
                                            'class' =>
                                                'fp-player__team',
                                        ]
                                    ) ?>

                                <?php else: ?>

                                    <span>暂无球队资料</span>

                                <?php endif; ?>

                            </div>


                            <!-- 场上位置 -->

                            <div class="fp-player__position">

                                <span class="fp-position-marker fp-position-marker--<?= $positionClass ?>"></span>

                                <?= Html::encode(
                                    $player->position
                                    ?: '暂无资料'
                                ) ?>

                            </div>


                            <!-- 原始资料链接 -->

                            <div class="fp-player__source">

                                <?php if (
                                    $isValidSource(
                                        $player->source_url
                                    )
                                ): ?>

                                    <?= Html::a(
                                        '资料来源 ↗',
                                        $player->source_url,
                                        [
                                            'target' => '_blank',
                                            'rel' =>
                                                'noopener noreferrer',
                                            'class' =>
                                                'fp-player__source-link',
                                        ]
                                    ) ?>

                                <?php else: ?>

                                    <span>—</span>

                                <?php endif; ?>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>


                <!-- ======================================
                     分页
                     ====================================== -->

                <div class="fp-pagination">

                    <p class="fp-pagination__summary">

                        第
                        <?= $pagination->getOffset() + 1 ?>
                        –
                        <?= min(
                            $pagination->getOffset()
                                + count($players),
                            $filteredCount
                        ) ?>

                        名，共
                        <?= number_format(
                            (int) $filteredCount
                        ) ?>
                        名球员

                    </p>

                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                        'maxButtonCount' => 5,
                        'prevPageLabel' => '←',
                        'nextPageLabel' => '→',
                        'options' => [
                            'class' =>
                                'pagination fp-pagination__links',
                        ],
                    ]) ?>

                </div>

            <?php else: ?>


                <!-- 无查询结果 -->

                <div class="ft-empty">

                    <h3>未找到符合条件的球员</h3>

                    <p>
                        尝试更换球员姓名、国家队或场上位置。
                    </p>

                    <?= Html::a(
                        '查看全部球员 →',
                        ['/football/players'],
                        ['class' => 'ft-empty__link']
                    ) ?>

                </div>

            <?php endif; ?>


            <!-- ======================================
                 页面底部
                 ====================================== -->

            <div class="fp-directory__footer">

                <p>
                    球员姓名、球衣号码及场上位置，
                    均根据项目数据库收录的参赛名单展示。
                </p>

                <?= Html::a(
                    '返回国家队名录 →',
                    ['/football/teams'],
                    ['class' => 'fp-directory__team-link']
                ) ?>

            </div>

        </div>

    </section>

</div>