<?php

/**
 * 比赛列表和比分。
 * @author lzs
 * @date 2026-11-20
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = '世界杯赛程与比分';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/match.css', [
    'depends' => [AppAsset::className()],
]);

$matches = $provider->getModels();
$filteredCount = $provider->getTotalCount();

$pagination = $provider->getPagination();

$hasFilters = $phase !== 'all'
    || $stage !== ''
    || $teamId !== ''
    || $status !== ''
    || $stadiumId !== '';

$listTitle = $hasFilters
    ? '筛选结果'
    : '全部赛事';

if ($phase !== 'all') {
    $listTitle = $stageGroups[$phase]['label'];
}

if ($stage !== '') {
    $listTitle = $stage;
}

$sections = [
    '/match/index' => '赛程与比分',
    '/match/bracket' => '淘汰赛晋级图',
];

?>

<div class="match-centre">

    <!-- ======================================
         赛事中心摄影页头
         ====================================== -->

    <section
        class="mc-hero"
        aria-labelledby="mc-hero-title"
    >

        <?= Html::img(
            '@web/images/match/match-hero-stadium-crowd.jpg',
            [
                'class' => 'mc-hero__image',
                'alt' => '',
                'aria-hidden' => 'true',
                'fetchpriority' => 'high',
                'width' => 3456,
                'height' => 2304,
            ]
        ) ?>

        <div
            class="mc-hero__shade"
            aria-hidden="true"
        ></div>

        <div class="wc-container mc-hero__inner">

            <div class="mc-hero__copy">

                <span class="mc-hero__eyebrow">
                    WORLD CUP 2026 / MATCH CENTRE
                </span>

                <h1 id="mc-hero-title" class="mc-hero__title">
                    赛程与比分
                </h1>

                <p class="mc-hero__description">
                    从小组赛到决赛，铭记每一场对决。
                </p>

            </div>

            <div class="mc-hero__stat">

                <strong>
                    <?= number_format((int) $totalMatches) ?>
                </strong>

                <span>场比赛</span>

            </div>

        </div>

    </section>


    <!-- ======================================
         足球业务栏目导航
         ====================================== -->

    <div class="mc-navigation">

        <div class="wc-container">

            <nav
                class="mc-navigation__links"
                aria-label="世界杯数据栏目"
            >

                <?php foreach ($sections as $route => $label): ?>

                    <?php $active = $route === '/match/index'; ?>

                    <?= Html::a(
                        Html::encode($label),
                        [$route],
                        [
                            'class' => 'mc-navigation__link'
                                . ($active ? ' is-active' : ''),
                            'aria-current' => $active
                                ? 'page'
                                : null,
                        ]
                    ) ?>

                <?php endforeach; ?>

            </nav>

        </div>

    </div>


    <!-- ======================================
         暖白色赛事主体
         ====================================== -->

    <section
        class="mc-content"
        aria-labelledby="mc-list-title"
    >

        <div class="wc-container">

            <!-- 列表标题及结果统计 -->
            <header class="mc-content__header">

                <div>

                    <h2 id="mc-list-title">
    <?= Html::encode($listTitle) ?>
</h2>
                    <p>
                        所有比赛时间均为北京时间。
                    </p>

                </div>

                <div class="mc-content__count">

                    <strong>
                        <?= number_format((int) $filteredCount) ?>
                    </strong>

                    <span>场比赛</span>

                </div>

                        </header>


            <!-- 当前场馆筛选提示 -->

            <?php if ($selectedStadiumName !== null): ?>

                <div class="mc-stadium-context">

                    <div class="mc-stadium-context__identity">

                        <span>当前浏览场馆</span>

                        <strong>
                            <?= Html::encode($selectedStadiumName) ?>
                        </strong>

                    </div>

                    <div class="mc-stadium-context__links">

                        <?= Html::a(
                            '场馆资料 ↗',
                            [
                                '/football/stadiums',
                                '#' => 'stadium-' . (int) $stadiumId,
                            ]
                        ) ?>

                        <?= Html::a(
                            '取消场馆筛选 ×',
                            [
                                '/match/index',
                                'phase' => $phase,
                                'stage' => $stage,
                                'team_id' => $teamId,
                                'status' => $status,
                                'order' => $order,
                            ]
                        ) ?>

                    </div>

                </div>

            <?php endif; ?>


<!-- ======================================
     赛事阶段导航
     ====================================== -->

<nav
    class="mc-phase-nav"
    aria-label="比赛阶段"
>

    <?php foreach ($stageGroups as $key => $group): ?>

        <?php

        $isActive = $phase === $key;

                $route = [
            '/match/index',
            'phase' => $key,
            'team_id' => $teamId,
            'stadium_id' => $stadiumId,
            'status' => $status,
            'order' => $order,
        ];

        ?>

        <?= Html::a(
            Html::encode($group['label']),
            $route,
            [
                'class' => 'mc-phase-nav__link'
                    . ($isActive ? ' is-active' : ''),
                'aria-current' => $isActive
                    ? 'page'
                    : null,
            ]
        ) ?>

    <?php endforeach; ?>

<?= Html::a(
    '完整晋级图 ↗',
    ['/match/bracket'],
    ['class' => 'mc-phase-nav__bracket']
) ?>

</nav>


<!-- ======================================
     小组赛二级导航
     ====================================== -->

<?php if ($phase === 'group'): ?>

    <nav
        class="mc-group-nav"
        aria-label="小组赛分组"
    >

                <?= Html::a(
            '全部小组',
            [
                '/match/index',
                'phase' => 'group',
                'team_id' => $teamId,
                'stadium_id' => $stadiumId,
                'status' => $status,
                'order' => $order,
            ],
            [
                'class' => 'mc-group-nav__link'
                    . ($stage === '' ? ' is-active' : ''),
                'aria-current' => $stage === ''
                    ? 'page'
                    : null,
            ]
        ) ?>

        <?php foreach (
            $stageGroups['group']['stages']
            as $groupStage
        ): ?>

            <?php

            $groupLabel = $groupStage;

            if (
                preg_match(
                    '/([A-L])\s*组/u',
                    $groupStage,
                    $matchesGroup
                )
            ) {

                $groupLabel = $matchesGroup[1] . '组';

            }

            ?>

                        <?= Html::a(
                Html::encode($groupLabel),
                [
                    '/match/index',
                    'phase' => 'group',
                    'stage' => $groupStage,
                    'team_id' => $teamId,
                    'stadium_id' => $stadiumId,
                    'status' => $status,
                    'order' => $order,
                ],
                [
                    'class' => 'mc-group-nav__link'
                        . (
                            $stage === $groupStage
                                ? ' is-active'
                                : ''
                        ),
                    'aria-current' => $stage === $groupStage
                        ? 'page'
                        : null,
                ]
            ) ?>

        <?php endforeach; ?>

    </nav>

<?php endif; ?>


<!-- ======================================
     辅助筛选区域
     ====================================== -->

<?= Html::beginForm(
    ['/match/index'],
    'get',
    ['class' => 'mc-filters mc-filters--refined']
) ?>

    <?= Html::hiddenInput('phase', $phase) ?>

    <?= Html::hiddenInput('stage', $stage) ?>

    <div class="mc-filters__field">

        <?= Html::label(
            '参赛球队',
            'mc-filter-team'
        ) ?>

        <?= Html::dropDownList(
            'team_id',
            $teamId,
            $teamOptions,
            [
                'id' => 'mc-filter-team',
                'class' => 'mc-filters__select',
                'prompt' => '全部球队',
            ]
        ) ?>

    </div>

       <div class="mc-filters__field">

        <?= Html::label(
            '比赛场馆',
            'mc-filter-stadium'
        ) ?>

        <?= Html::dropDownList(
            'stadium_id',
            $stadiumId,
            $stadiumOptions,
            [
                'id' => 'mc-filter-stadium',
                'class' => 'mc-filters__select',
                'prompt' => '全部场馆',
            ]
        ) ?>

    </div>

    <div class="mc-filters__field">

        <?= Html::label(
            '比赛状态',
            'mc-filter-status'
        ) ?>
        <?= Html::dropDownList(
            'status',
            $status,
            $statusOptions,
            [
                'id' => 'mc-filter-status',
                'class' => 'mc-filters__select',
                'prompt' => '全部状态',
            ]
        ) ?>

    </div>

    <div class="mc-filters__field">

        <?= Html::label(
            '排列顺序',
            'mc-filter-order'
        ) ?>

        <?= Html::dropDownList(
            'order',
            $order,
            [
                'asc' => '时间升序',
                'desc' => '时间降序',
            ],
            [
                'id' => 'mc-filter-order',
                'class' => 'mc-filters__select',
            ]
        ) ?>

    </div>

    <div class="mc-filters__actions">

        <?= Html::submitButton(
            '筛选赛事',
            ['class' => 'mc-filters__submit']
        ) ?>

        <?= Html::a(
            '重置',
            ['/match/index'],
            ['class' => 'mc-filters__reset']
        ) ?>

    </div>

<?= Html::endForm() ?>


            <!-- ======================================
                 按日期分组的赛事列表
                 ====================================== -->

            <?php if (!empty($matches)): ?>

                <div class="mc-schedule">

                    <?php

                    $currentDate = null;

                    $weekdays = [
                        '星期日',
                        '星期一',
                        '星期二',
                        '星期三',
                        '星期四',
                        '星期五',
                        '星期六',
                    ];

                    ?>

                    <?php foreach ($matches as $match): ?>

                        <?php

                        // Model 已经完成北京时间转换。
                        $date = substr(
                            $match->shanghaiKickoff,
                            0,
                            10
                        );

                        ?>

                        <?php if ($date !== $currentDate): ?>

                            <?php if ($currentDate !== null): ?>
                                </div>
                            <?php endif; ?>

                            <?php

                            $currentDate = $date;

                            $dateObject = new \DateTimeImmutable(
                                $date,
                                new \DateTimeZone('Asia/Shanghai')
                            );

                            $dateLabel = $dateObject->format('n')
                                . '月'
                                . $dateObject->format('j')
                                . '日';

                            $weekday = $weekdays[
                                (int) $dateObject->format('w')
                            ];

                            ?>

                            <div class="mc-date-group">

                                <header class="mc-date-heading">

                                    <h3>
                                        <?= Html::encode($dateLabel) ?>
                                    </h3>

                                    <span>
                                        <?= Html::encode($weekday) ?>
                                    </span>

                                    <time datetime="<?= Html::encode($date) ?>">
                                        <?= Html::encode($date) ?>
                                    </time>

                                </header>

                        <?php endif; ?>

                        <?= $this->render('_match', [
                            'match' => $match,
                        ]) ?>

                    <?php endforeach; ?>

                    </div>

                </div>


                <!-- 分页与当前记录范围 -->
                <div class="mc-pagination">

                    <p class="mc-pagination__summary">

                        第
                        <?= $pagination->getOffset() + 1 ?>
                        –
                        <?= min(
                            $pagination->getOffset()
                                + count($matches),
                            $filteredCount
                        ) ?>
                        场，共
                        <?= number_format((int) $filteredCount) ?>
                        场比赛

                    </p>

                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                        'maxButtonCount' => 5,
                        'prevPageLabel' => '←',
                        'nextPageLabel' => '→',
                        'options' => [
                            'class' => 'pagination mc-pagination__links',
                        ],
                    ]) ?>

                </div>

            <?php else: ?>

                <!-- 无符合条件的比赛 -->
                <div class="mc-empty">

                    <span class="mc-empty__symbol" aria-hidden="true">
                        —
                    </span>
                    <h3>暂无符合条件的比赛</h3>

                    <p>
                        可以更换球队、比赛场馆、比赛阶段或状态后重新查询。
                    </p>

                    <?= Html::a(
                        '查看全部赛事 →',
                        ['/match/index'],
                        ['class' => 'mc-empty__link']
                    ) ?>

                </div>

            <?php endif; ?>

        </div>

    </section>

</div>