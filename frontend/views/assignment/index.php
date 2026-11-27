<?php

/**
 * 模块 A 前台课程资料中心。
 *
 * TEAM 404 / Coursework Archive
 *
 * @author wuyi
 * @date 2026-11-20
 */

use common\models\PortalAssignment;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $teamAssignments common\models\PortalAssignment[] */
/* @var $personalAssignments common\models\PortalAssignment[] */
/* @var $category string */
/* @var $memberId string|int */
/* @var $memberOptions array */
/* @var $assignmentStats array */

$this->title = '课程资料中心';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/portal.css', [
    'depends' => [AppAsset::className()],
]);

$this->registerJsFile('@web/js/assignment.js', [
    'depends' => [AppAsset::className()],
]);


/* ========================================
   页面展示数据
   ======================================== */

$currentCategory = (string) $category;

$currentMemberId = (string) $memberId;

$teamCount = (int) $assignmentStats['team'];
$personalCount = (int) $assignmentStats['personal'];
$totalCount = (int) $assignmentStats['total'];

$selectedMemberName = isset(
    $memberOptions[$currentMemberId]
)
    ? $memberOptions[$currentMemberId]
    : null;


/*
 * 根据当前分类和成员条件，
 * 组织已经由 Controller 查询完成的数据。
 */

if ($currentCategory === PortalAssignment::CATEGORY_TEAM) {

    $documents = $teamAssignments;

} elseif (
    $currentCategory === PortalAssignment::CATEGORY_PERSONAL
    || $currentMemberId !== ''
) {

    $documents = $personalAssignments;

} else {

    $documents = array_merge(
        $teamAssignments,
        $personalAssignments
    );
}


/*
 * 全部资料按照创建时间倒序展示，
 * 相同时间使用 ID 作为次级排序条件。
 */

usort($documents, function ($a, $b) {

    $timeComparison =
        (int) $b->created_at <=> (int) $a->created_at;

    if ($timeComparison !== 0) {
        return $timeComparison;
    }

    return (int) $b->id <=> (int) $a->id;
});


$resultCount = count($documents);


/* 分类导航 */

$categoryTabs = [
    [
        'value' => '',
        'title' => '全部资料',
        'english' => 'ALL',
        'count' => $totalCount,
    ],
    [
        'value' => PortalAssignment::CATEGORY_TEAM,
        'title' => '团队作业',
        'english' => 'TEAM',
        'count' => $teamCount,
    ],
    [
        'value' => PortalAssignment::CATEGORY_PERSONAL,
        'title' => '个人作业',
        'english' => 'PERSONAL',
        'count' => $personalCount,
    ],
];


/* 当前分类的显示状态 */

$activeCategory = $currentMemberId !== ''
    ? PortalAssignment::CATEGORY_PERSONAL
    : $currentCategory;

?>

<div class="portal-page portal-archive">

    <!-- ======================================
         00. 课程资料中心主视觉
         ====================================== -->

    <section class="tp-archive-hero"
             aria-labelledby="tp-archive-title">

        <div class="wc-container">

            <!-- 顶部辅助标识 -->

            <div class="tp-archive-hero__top">

                <span class="tp-archive-hero__eyebrow">
                    TEAM 404 / COURSEWORK
                </span>

                <span class="tp-archive-hero__edition">
                    NKU / 2026
                </span>

            </div>


            <!-- 主视觉主体 -->

            <div class="tp-archive-hero__main">

                <div class="tp-archive-hero__identity">

                    <span class="tp-archive-hero__kicker">
                        THE COURSEWORK ARCHIVE
                    </span>

                    <h1 id="tp-archive-title"
                        class="tp-archive-hero__title">
                        课程资料中心<span>。</span>
                    </h1>

                    <p class="tp-archive-hero__subtitle">
                        记录每一次学习与实践。
                    </p>

                    <p class="tp-archive-hero__description">
                        汇集团队项目成果与成员个人课程作业，
                        所有公开资料均可通过统一的下载入口获取。
                    </p>

                </div>


                <!-- 文档档案主题图形 -->

                <div class="tp-archive-hero__graphic"
                     aria-hidden="true">

                    <svg viewBox="0 0 360 300"
                         xmlns="http://www.w3.org/2000/svg">

                        <defs>

                            <pattern
                                id="tp-archive-grid"
                                width="30"
                                height="30"
                                patternUnits="userSpaceOnUse">

                                <path
                                    d="M30 0H0V30"
                                    fill="none"
                                    stroke="#354B3A"
                                    stroke-width="0.8"
                                />

                            </pattern>

                        </defs>


                        <!-- 背景网格 -->

                        <rect
                            x="15"
                            y="15"
                            width="330"
                            height="270"
                            fill="url(#tp-archive-grid)"
                        />


                        <!-- 后层文档 -->

                        <rect
                            x="130"
                            y="32"
                            width="160"
                            height="210"
                            fill="#19271D"
                            stroke="#61765F"
                            stroke-width="1"
                        />

                        <rect
                            x="108"
                            y="46"
                            width="160"
                            height="210"
                            fill="#19271D"
                            stroke="#829A76"
                            stroke-width="1"
                        />


                        <!-- 主文档 -->

                        <path
                            d="M72 62
                               H176
                               L224 110
                               V270
                               H72
                               Z"
                            fill="#223128"
                            stroke="#C7F36B"
                            stroke-width="1.6"
                            stroke-linejoin="round"
                        />

                        <path
                            d="M176 62
                               V110
                               H224"
                            fill="none"
                            stroke="#C7F36B"
                            stroke-width="1.2"
                        />


                        <!-- 文档正文线 -->

                        <g stroke="#A7B79F"
                           stroke-width="1.2">

                            <line
                                x1="94"
                                y1="137"
                                x2="202"
                                y2="137"
                            />

                            <line
                                x1="94"
                                y1="155"
                                x2="202"
                                y2="155"
                            />

                            <line
                                x1="94"
                                y1="173"
                                x2="176"
                                y2="173"
                            />

                        </g>


                        <!-- 文件类型辅助标识 -->

                        <rect
                            x="94"
                            y="205"
                            width="53"
                            height="23"
                            fill="#C7F36B"
                        />

                        <text
                            x="120.5"
                            y="220"
                            text-anchor="middle"
                            fill="#111815"
                            font-family="Consolas, monospace"
                            font-size="11"
                            font-weight="bold">

                            FILE

                        </text>


                        <!-- 边缘标记 -->

                        <g
                            fill="#98AB98"
                            font-family="Consolas, monospace"
                            font-size="10">

                            <text x="15" y="10">
                                FIG. 02
                            </text>

                            <text
                                x="345"
                                y="10"
                                text-anchor="end">
                                DOCUMENT SYSTEM
                            </text>

                            <text
                                x="345"
                                y="298"
                                text-anchor="end">
                                COURSEWORK ARCHIVE
                            </text>

                        </g>

                    </svg>

                </div>

            </div>


            <!-- 全局作业统计，同时作为分类导航 -->

            <div class="tp-archive-stats">

                <?php foreach ($categoryTabs as $tab): ?>

                    <?php

                    $statRoute = ['/assignment/index'];

                    if ($tab['value'] !== '') {
                        $statRoute['category'] = $tab['value'];
                    }

                    $statRoute['#'] = 'tp-archive-library';

                    ?>

                    <a class="tp-archive-stat"
                       href="<?= Html::encode(
                           Url::to($statRoute)
                       ) ?>">

                        <div class="tp-archive-stat__top">

                            <span class="tp-archive-stat__value">

                                <?= sprintf(
                                    '%02d',
                                    $tab['count']
                                ) ?>

                            </span>

                            <span class="tp-archive-stat__arrow"
                                  aria-hidden="true">↓</span>

                        </div>

                        <span class="tp-archive-stat__label">

                            <?= Html::encode($tab['title']) ?>

                        </span>

                        <span class="tp-archive-stat__english">

                            <?= Html::encode($tab['english']) ?>

                        </span>

                    </a>

                <?php endforeach; ?>

            </div>

        </div>

    </section>


    <!-- ======================================
         01. 课程成果档案库
         ====================================== -->

    <section id="tp-archive-library"
             class="tp-content tp-archive-library"
             aria-labelledby="tp-archive-library-title">

        <div class="wc-container">

            <!-- 章节标识 -->

            <div class="tp-section__meta">

                <span>01 / DOCUMENT ARCHIVE</span>

                <span>
                    <?= sprintf('%02d', $resultCount) ?>
                    RESULTS
                </span>

            </div>


            <!-- 章节标题 -->

            <div class="tp-archive-library__heading">

                <div>

                    <h2 id="tp-archive-library-title"
                        class="tp-section__title">
                        课程成果档案
                    </h2>

                    <p class="tp-archive-library__subtitle">
                        Explore our coursework and project outcomes.
                    </p>

                </div>

                <?= Html::a(
                    '返回团队 <span aria-hidden="true">↗</span>',
                    ['/team/index'],
                    [
                        'class' => 'tp-profile-section-link',
                    ]
                ) ?>

            </div>


            <!-- ======================================
                 分类导航
                 ====================================== -->

            <nav class="tp-archive-tabs"
                 aria-label="课程资料分类">

                <?php foreach ($categoryTabs as $tab): ?>

                    <?php

                    $tabRoute = ['/assignment/index'];

                    if ($tab['value'] !== '') {
                        $tabRoute['category'] = $tab['value'];
                    }

                    $tabRoute['#'] = 'tp-archive-library';

                    $isActive =
                        $activeCategory === $tab['value'];

                    ?>

                    <a class="tp-archive-tab<?= $isActive
                        ? ' is-active'
                        : '' ?>"
                       href="<?= Html::encode(
                           Url::to($tabRoute)
                       ) ?>"
                       <?= $isActive
                           ? 'aria-current="page"'
                           : '' ?>>

                        <span class="tp-archive-tab__title">

                            <?= Html::encode($tab['title']) ?>

                        </span>

                        <span class="tp-archive-tab__count">

                            <?= sprintf('%02d', $tab['count']) ?>

                        </span>

                    </a>

                <?php endforeach; ?>

            </nav>


            <!-- ======================================
                 成员筛选
                 ====================================== -->

            <?php if (
                $activeCategory !== PortalAssignment::CATEGORY_TEAM
            ): ?>

                <?= Html::beginForm(
                    [
                        '/assignment/index',
                        '#' => 'tp-archive-library',
                    ],
                    'get',
                    [
                        'id' => 'tp-archive-filter-form',
                        'class' => 'tp-archive-filters',
                    ]
                ) ?>

                    <!-- 成员筛选始终针对个人作业 -->

                    <?= Html::hiddenInput(
                        'category',
                        PortalAssignment::CATEGORY_PERSONAL
                    ) ?>


                    <div class="tp-archive-filters__field">

                        <?= Html::label(
                            '所属成员',
                            'tp-archive-member',
                            [
                                'class' => 'tp-archive-filters__label',
                            ]
                        ) ?>

                        <?= Html::dropDownList(
                            'member_id',
                            $currentMemberId,
                            $memberOptions,
                            [
                                'id' => 'tp-archive-member',
                                'prompt' => '全部成员',
                                'class' => 'tp-archive-select',
                            ]
                        ) ?>

                        <?= Html::submitButton(
                            '筛选',
                            [
                                'class' => 'tp-archive-filter-submit',
                            ]
                        ) ?>

                    </div>


                    <div class="tp-archive-filters__actions">

                        <?= Html::a(
                            '重置筛选',
                            [
                                '/assignment/index',
                                '#' => 'tp-archive-library',
                            ],
                            [
                                'class' => 'tp-archive-reset',
                            ]
                        ) ?>

                    </div>

                <?= Html::endForm() ?>

            <?php else: ?>

                <div class="tp-archive-team-note">

                    <span>TEAM COURSEWORK / 团队成果</span>

                    <?= Html::a(
                        '重置筛选',
                        [
                            '/assignment/index',
                            '#' => 'tp-archive-library',
                        ],
                        [
                            'class' => 'tp-archive-reset',
                        ]
                    ) ?>

                </div>

            <?php endif; ?>


            <!-- 当前成员及个人主页联动 -->

            <?php if ($selectedMemberName !== null): ?>

                <div class="tp-archive-selection">

                    <div>

                        <span class="tp-archive-selection__label">
                            当前成员
                        </span>

                        <strong>

                            <?= Html::encode(
                                $selectedMemberName
                            ) ?>

                        </strong>

                    </div>

                    <?= Html::a(
                        '查看个人主页
                        <span aria-hidden="true">↗</span>',
                        [
                            '/team/member',
                            'id' => $currentMemberId,
                        ],
                        [
                            'class' => 'tp-profile-section-link',
                        ]
                    ) ?>

                </div>

            <?php endif; ?>


            <!-- ======================================
                 文档列表
                 ====================================== -->

            <?php if (empty($documents)): ?>

                <div class="tp-archive-empty">

                    <svg viewBox="0 0 48 48"
                         width="48"
                         height="48"
                         fill="none"
                         aria-hidden="true">

                        <path
                            d="M5 13H19L24 18H43V39H5V13Z"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linejoin="round"
                        />

                        <path
                            d="M5 23H43"
                            stroke="currentColor"
                            stroke-width="1.5"
                        />

                    </svg>

                    <h3>暂无相关课程资料</h3>

                    <p>

                        <?php if (
                            $activeCategory
                            === PortalAssignment::CATEGORY_TEAM
                        ): ?>

                            团队作业正在整理中，暂无可下载资料。

                        <?php elseif (
                            $selectedMemberName !== null
                        ): ?>

                            该成员暂未发布个人课程作业。

                        <?php else: ?>

                            当前分类暂无已发布的课程资料。

                        <?php endif; ?>

                    </p>

                    <?= Html::a(
                        '查看全部资料 ↗',
                        [
                            '/assignment/index',
                            '#' => 'tp-archive-library',
                        ],
                        [
                            'class' => 'tp-archive-empty__link',
                        ]
                    ) ?>

                </div>

            <?php else: ?>

                <div class="tp-documents">

                    <div class="tp-documents__header"
                         aria-hidden="true">

                        <span>NO.</span>

                        <span>DOCUMENT / 作业名称</span>

                        <span>FORMAT / 格式</span>

                        <span>DOWNLOAD</span>

                    </div>


                    <?php foreach (
                        $documents as $index => $assignment
                    ): ?>

                        <?php

                        $isPersonal =
                            $assignment->category
                            === PortalAssignment::CATEGORY_PERSONAL;

                        $description = trim(
                            (string) $assignment->description
                        );

                        $extension = strtoupper(
                            pathinfo(
                                (string) $assignment->relative_path,
                                PATHINFO_EXTENSION
                            )
                        );

                        if ($extension === '') {
                            $extension = 'FILE';
                        }

                        ?>

                        <article class="tp-document">

                            <span class="tp-document__number">

                                <?= sprintf('%02d', $index + 1) ?>

                            </span>


                            <div class="tp-document__main">

                                <!-- 文件图标 -->

                                <div class="tp-document__icon"
                                     aria-hidden="true">

                                    <svg viewBox="0 0 32 32"
                                         width="30"
                                         height="30"
                                         fill="none">

                                        <path
                                            d="M8 3H19L25 9V29H8V3Z"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            stroke-linejoin="round"
                                        />

                                        <path
                                            d="M19 3V9H25"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                        />

                                        <path
                                            d="M12 15H21
                                               M12 19H21
                                               M12 23H18"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                        />

                                    </svg>

                                </div>


                                <div class="tp-document__identity">

                                    <h3 class="tp-document__title">

                                        <?= Html::encode(
                                            $assignment->title
                                        ) ?>

                                    </h3>


                                    <!-- 作业分类及归属 -->

                                    <div class="tp-archive-document-meta">

                                        <span>

                                            <?= $isPersonal
                                                ? '个人作业'
                                                : '团队作业' ?>

                                        </span>

                                        <span
                                            class="tp-archive-document-meta__dot"
                                            aria-hidden="true">
                                        </span>


                                        <?php if (
                                            $isPersonal
                                            && $assignment->member !== null
                                        ): ?>

                                            <?= Html::a(
                                                Html::encode(
                                                    $assignment->member->name
                                                ),
                                                [
                                                    '/team/member',
                                                    'id' => $assignment->member->id,
                                                ]
                                            ) ?>

                                        <?php elseif ($isPersonal): ?>

                                            <span>

                                                <?= Html::encode(
                                                    trim(
                                                        (string) $assignment->owner_label
                                                    ) !== ''
                                                        ? $assignment->owner_label
                                                        : '成员关联待补充'
                                                ) ?>

                                            </span>

                                        <?php else: ?>

                                            <span>

                                                <?= Html::encode(
                                                    trim(
                                                        (string) $assignment->owner_label
                                                    ) !== ''
                                                        ? $assignment->owner_label
                                                        : '团队成果'
                                                ) ?>

                                            </span>

                                        <?php endif; ?>

                                    </div>


                                    <!-- 可选作业说明 -->

                                    <?php if ($description !== ''): ?>

                                        <p class="tp-document__description">

                                            <?= nl2br(
                                                Html::encode($description)
                                            ) ?>

                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- 附件格式 -->

                            <span class="tp-document__format">

                                <?= Html::encode($extension) ?>

                            </span>


                            <!-- 统一安全下载 -->

                            <?= Html::a(
                                '<svg viewBox="0 0 24 24"
                                      width="20"
                                      height="20"
                                      fill="none"
                                      aria-hidden="true">
                                    <path d="M12 3V15
                                             M7 10L12 15L17 10
                                             M4 16V21H20V16"
                                          stroke="currentColor"
                                          stroke-width="1.6"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                </svg>
                                <span>下载</span>',
                                [
                                    '/assignment/download',
                                    'id' => $assignment->id,
                                ],
                                [
                                    'class' => 'tp-document__download',
                                    'aria-label' => '下载'
                                        . $assignment->title,
                                ]
                            ) ?>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <!-- 列表底部统计 -->

            <div class="tp-profile-section-footer">

                <span class="tp-profile-section-footer__meta">

                    <?= sprintf('%02d', $resultCount) ?>

                    DOCUMENTS AVAILABLE

                </span>

                <span class="tp-archive-download-note">
                    所有附件均通过系统统一校验后下载
                </span>

            </div>

        </div>

    </section>


    <!-- ======================================
         02. 页面收尾
         复用个人主页收尾组件
         ====================================== -->

    <section class="tp-profile-closing"
             aria-labelledby="tp-archive-closing-title">

        <div class="wc-container">

            <div class="tp-profile-closing__meta">

                <span>TEAM 404 / COURSEWORK ARCHIVE</span>

                <span>NKU / 2026</span>

            </div>

            <a class="tp-profile-closing__link"
               href="<?= Html::encode(
                   Url::to(['/team/index'])
               ) ?>">

                <div>

                    <h2 id="tp-archive-closing-title"
                        class="tp-profile-closing__title">
                        了解我们的团队<span>。</span>
                    </h2>

                    <p class="tp-profile-closing__subtitle">
                        Meet the people behind the work.
                    </p>

                </div>

                <span class="tp-profile-closing__arrow"
                      aria-hidden="true">↗</span>

            </a>

        </div>

    </section>

</div>