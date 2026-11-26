<?php

/**
 * 模块 A 团队门户前台页面。
 *
 * TEAM 404 / Behind the Project
 *
 * @author wuyi
 * @date 2026-11-19
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $team common\models\PortalTeam|null */
/* @var $members common\models\PortalMember[] */
/* @var $teamLogoUrl string|null */
/* @var $memberAvatarUrls array */

$this->title = '团队介绍';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/portal.css', [
    'depends' => [AppAsset::className()],
]);
$this->registerJsFile('@web/js/portal.js', [
    'depends' => [AppAsset::className()],
]);
$teamName = $team !== null
    ? $team->name
    : '团队门户';

$teamDescription = $team !== null
    && trim((string) $team->description) !== ''
        ? $team->description
        : '团队资料正在整理中。';

?>

<div class="portal-page portal-team">

    <!-- ======================================
         01. 团队品牌主视觉
         ====================================== -->

    <<section class="tp-hero"
         aria-labelledby="tp-hero-title">

    <div class="tp-hero__ambient"
         aria-hidden="true"></div>

    <div class="wc-container tp-hero__inner">

            <!-- 顶部辅助标识 -->

            <div class="tp-hero__top">

                <span class="tp-hero__eyebrow">
                    WORLD CUP 2026 / THE TEAM
                </span>

                <span class="tp-hero__edition">
                    NKU / 2026
                </span>

            </div>


            <!-- 404 核心视觉 -->

            <div class="tp-hero__main">

                <div class="tp-hero__identity">

                    <span class="tp-hero__kicker">
                        BEHIND THE PROJECT
                    </span>

                    <h1 id="tp-hero-title"
    class="tp-hero__number"
    aria-label="<?= Html::encode($teamName) ?>"><span class="tp-digit">4</span><span class="tp-digit tp-digit--accent">0</span><span class="tp-digit">4</span></h1>

                    <div class="tp-hero__signature">
                        TEAM 404
                    </div>

                </div>


                <!-- 几何辅助视觉 -->

                <div class="tp-hero__graphic"
                     aria-hidden="true">

                    <svg
                        viewBox="0 0 360 360"
                        xmlns="http://www.w3.org/2000/svg">

                        <!-- 基础坐标网格 -->

                        <g stroke="#34443A"
                           stroke-width="0.8">

                            <?php for ($i = 0; $i <= 8; $i++): ?>

                                <?php $position = 20 + $i * 40; ?>

                                <line
                                    x1="<?= $position ?>"
                                    y1="20"
                                    x2="<?= $position ?>"
                                    y2="340"
                                />

                                <line
                                    x1="20"
                                    y1="<?= $position ?>"
                                    x2="340"
                                    y2="<?= $position ?>"
                                />

                            <?php endfor; ?>

                        </g>


                        <!-- 外部几何轮廓 -->

                        <rect
                            x="60"
                            y="60"
                            width="240"
                            height="240"
                            fill="none"
                            stroke="#66786B"
                            stroke-width="1"
                        />

                        <path
                            d="M60 60 L300 300
                               M300 60 L60 300"
                            fill="none"
                            stroke="#46594B"
                            stroke-width="0.8"
                        />


                        <!-- 同心圆 -->

                        <circle
                            cx="180"
                            cy="180"
                            r="106"
                            fill="none"
                            stroke="#C7F36B"
                            stroke-width="1.4"
                        />

                        <circle
                            cx="180"
                            cy="180"
                            r="77"
                            fill="none"
                            stroke="#627865"
                            stroke-width="1"
                        />

                        <circle
                            cx="180"
                            cy="180"
                            r="36"
                            fill="none"
                            stroke="#627865"
                            stroke-width="1"
                        />


                        <!-- 中心十字定位 -->

<path
    d="M180 44 V316
       M44 180 H316"
    stroke="#738773"
    stroke-width="1"
/>


<!-- 外层动态轨道 -->

<g class="tp-orbit tp-orbit--outer">

    <circle
        cx="180"
        cy="180"
        r="106"
        fill="none"
        stroke="#C7F36B"
        stroke-width="2.4"
        stroke-linecap="round"
        stroke-dasharray="116 550.02"
    />

    <circle
        cx="286"
        cy="180"
        r="4.5"
        fill="#C7F36B"
    />

</g>


<!-- 内层反向动态轨道 -->

<g class="tp-orbit tp-orbit--inner">

    <circle
        cx="180"
        cy="180"
        r="77"
        fill="none"
        stroke="#98AB98"
        stroke-width="1.6"
        stroke-dasharray="24 96.95"
    />

    <circle
        cx="103"
        cy="180"
        r="3.5"
        fill="#98AB98"
    />

</g>


<!-- 扫描区域限制 -->

<defs>

    <clipPath id="tp-hero-scan-clip">

        <rect
            x="20"
            y="20"
            width="320"
            height="320"
        />

    </clipPath>

</defs>


<!-- 动态扫描线 -->

<g clip-path="url(#tp-hero-scan-clip)">

    <g class="tp-scan-line">

        <rect
            x="40"
            y="168"
            width="280"
            height="12"
            fill="#C7F36B"
            opacity="0.035"
        />

        <line
            x1="40"
            y1="180"
            x2="320"
            y2="180"
            stroke="#C7F36B"
            stroke-width="1"
        />

    </g>

</g>


<!-- 中心呼吸光环 -->

<circle
    class="tp-center-pulse"
    cx="180"
    cy="180"
    r="20"
    fill="none"
    stroke="#C7F36B"
    stroke-width="1"
/>


<!-- 中心定位点 -->

<rect
    class="tp-center-point"
    x="175"
    y="175"
    width="10"
    height="10"
    fill="#C7F36B"
/>


                        <!-- 边缘标注 -->

                        <g
                            fill="#98AB98"
                            font-family="Consolas, monospace"
                            font-size="10">

                            <text x="20" y="12">
                                FIG. 01
                            </text>

                            <text
                                x="340"
                                y="12"
                                text-anchor="end">
                                TEAM SYSTEM
                            </text>

                            <text x="20" y="355">
                                NKU / CS
                            </text>

                        </g>

                        <circle
    class="tp-online-indicator"
    cx="249"
    cy="351.5"
    r="2.8"
    fill="#C7F36B"
/>

<text
    x="340"
    y="355"
    fill="#C7F36B"
    font-family="Consolas, monospace"
    font-size="10"
    text-anchor="end">

    SYSTEM ONLINE

</text>

                    </svg>

                </div>

            </div>


            <!-- 团队资料与操作入口 -->

            <div class="tp-hero__bottom">

                <div class="tp-hero__summary">

                    <h2 class="tp-hero__name">

                        <?= Html::encode($teamName) ?>

                    </h2>

                    <p class="tp-hero__description">

                        <?= nl2br(
                            Html::encode($teamDescription)
                        ) ?>

                    </p>

                    <?php if ($teamLogoUrl !== null): ?>

                        <?= Html::img($teamLogoUrl, [
                            'alt' => $teamName . ' 团队图片',
                            'style' => 'display:block;max-width:180px;max-height:100px;margin-top:20px;object-fit:contain;',
                        ]) ?>

                    <?php endif; ?>

                </div>

                <div class="tp-hero__actions">

                    <?= Html::a(
                        '认识团队 <span aria-hidden="true">↓</span>',
                        '#tp-members',
                        [
                            'class' => 'tp-button tp-button--primary',
                        ]
                    ) ?>

                    <?= Html::a(
    '探索项目成果 <span aria-hidden="true">↓</span>',
    '#tp-work',
    [
        'class' => 'tp-button tp-button--outline',
    ]
) ?>

                </div>

            </div>

        </div>

    </section>


    <!-- ======================================
     02. 团队成员
     ====================================== -->

<section id="tp-members"
         class="tp-content tp-members"
         aria-labelledby="tp-members-title">

    <div class="wc-container">

        <!-- 章节编号 -->

        <div class="tp-section__meta">

            <span>01 / PEOPLE</span>

            <span>
                <?= sprintf('%02d', count($members)) ?>
                MEMBERS
            </span>

        </div>


        <!-- 章节标题 -->

        <div class="tp-members__heading">

            <div>

                <h2 id="tp-members-title"
                    class="tp-section__title">
                    团队成员
                </h2>

                <p class="tp-members__subtitle">
                    Meet the people behind the project.
                </p>

            </div>

            <span class="tp-members__edition">
                TEAM / 404
            </span>

        </div>


        <!-- 成员列表 -->

        <?php if (empty($members)): ?>

            <div class="tp-empty">
                成员资料正在整理中。
            </div>

        <?php else: ?>

            <div class="tp-members__grid">

                <?php foreach ($members as $index => $member): ?>

                    <?php

                    $avatarUrl = isset(
                        $memberAvatarUrls[(string) $member->id]
                    )
                        ? $memberAvatarUrls[(string) $member->id]
                        : null;

                    $profileRoute = [
                        'member',
                        'id' => $member->id,
                    ];

                    $memberNumber = sprintf(
                        '%02d',
                        $index + 1
                    );

                    $memberInitial = mb_substr(
                        trim((string) $member->name),
                        0,
                        1,
                        'UTF-8'
                    );

                    $memberRole = trim(
                        (string) $member->role
                    );

                    $memberBio = trim(
                        (string) $member->bio
                    );

                    $studentNo = trim(
                        (string) $member->student_no
                    );

                    ?>

                    <article class="tp-member">

                        <!-- 成员头像展示区 -->

                        <a class="tp-member__portrait"
                           href="<?= Html::encode(
                               \yii\helpers\Url::to($profileRoute)
                           ) ?>"
                           aria-label="<?= Html::encode(
                               '查看' . $member->name . '的个人主页'
                           ) ?>">

                            <div class="tp-member__portrait-meta">

                                <span>MEMBER</span>

                                <span>
                                    <?= $memberNumber ?>
                                </span>

                            </div>

                            <div class="tp-member__avatar-stage">

                                <div class="tp-member__avatar-frame">

                                    <?php if ($avatarUrl !== null): ?>

                                        <?= Html::img($avatarUrl, [
                                            'alt' => $member->name . '的头像',
                                            'class' => 'tp-member__avatar',
                                            'loading' => 'lazy',
                                            'decoding' => 'async',
                                        ]) ?>

                                    <?php else: ?>

                                        <span class="tp-member__initial"
                                              aria-hidden="true">

                                            <?= Html::encode(
                                                $memberInitial
                                            ) ?>

                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>

                            <div class="tp-member__portrait-footer">

                                <span>TEAM 404</span>

                                <span class="tp-member__portrait-arrow"
                                      aria-hidden="true">↗</span>

                            </div>

                        </a>


                        <!-- 成员身份 -->

                        <div class="tp-member__identity">

                            <h3 class="tp-member__name">

                                <?= Html::a(
                                    Html::encode($member->name),
                                    $profileRoute
                                ) ?>

                            </h3>

                            <span class="tp-member__student-no">

                                <?= $studentNo !== ''
                                    ? 'NO. ' . Html::encode($studentNo)
                                    : '学号未提供' ?>

                            </span>

                        </div>


                        <!-- 项目职责 -->

                        <div class="tp-member__role">

                            <span class="tp-member__label">
                                PROJECT ROLE / 项目职责
                            </span>

                            <p class="tp-member__role-text">

                                <?= $memberRole !== ''
                                    ? nl2br(Html::encode($memberRole))
                                    : '职责待补充' ?>

                            </p>

                        </div>


                        <!-- 个人介绍 -->

                        <div class="tp-member__bio">

                            <p>

                                <?= $memberBio !== ''
                                    ? nl2br(Html::encode($memberBio))
                                    : '个人介绍待补充。' ?>

                            </p>

                        </div>


                        <!-- 个人主页入口 -->

                        <?= Html::a(
                            '个人主页 <span aria-hidden="true">↗</span>',
                            $profileRoute,
                            [
                                'class' => 'tp-member__profile-link',
                                'aria-label' => '查看'
                                    . $member->name
                                    . '的个人主页',
                            ]
                        ) ?>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>
<!-- ======================================
     03. 项目介绍 / THE PROJECT
     ====================================== -->

<section id="tp-project"
         class="tp-content tp-project"
         aria-labelledby="tp-project-title">

    <div class="wc-container">

        <!-- 章节编号 -->

        <div class="tp-section__meta">

            <span>02 / THE PROJECT</span>

            <span>WORLDCUP2026</span>

        </div>


        <!-- 项目介绍 -->

        <div class="tp-project__heading">

            <div>

                <h2 id="tp-project-title"
                    class="tp-section__title">
                    关于我们的项目
                </h2>

                <p class="tp-project__subtitle">
                    From data to experience.
                </p>

            </div>

            <div class="tp-project__intro">

                <p>
                    WorldCup2026 是我们在《互联网数据库开发》
                    课程中完成的团队项目。我们以2026世界杯为主题，
                    将数据库技术、Web 应用开发与体育赛事信息相结合，
                    构建集赛事数据、新闻资讯及团队成果展示于一体的网站。
                </p>

                <p>
                    项目采用 Yii2 Advanced 框架，
                    通过模块化开发组织前后台业务，
                    并使用 MySQL / MariaDB 管理和维护数据。
                </p>

            </div>

        </div>


        <!-- ABC 业务模块 -->

        <?php

        $projectModules = [
            [
                'number' => 'A',
                'english' => 'TEAM PORTAL',
                'title' => '团队门户',
                'description' => '团队介绍、成员档案、课程作业与留言展示',
                'route' => '#tp-members',
            ],
            [
                'number' => 'B',
                'english' => 'NEWSROOM',
                'title' => '新闻资讯',
                'description' => '新闻文章、分类标签、内容发布与资讯阅读',
                'route' => ['/news/index'],
            ],
            [
                'number' => 'C',
                'english' => 'FOOTBALL DATA',
                'title' => '足球业务',
                'description' => '国家队、球员、比赛赛程与场馆数据',
                'route' => ['/match/index'],
            ],
        ];

        ?>

        <div class="tp-project__modules">

            <?php foreach ($projectModules as $module): ?>

                <a class="tp-module"
                   href="<?= Html::encode(
                       \yii\helpers\Url::to($module['route'])
                   ) ?>">

                    <div class="tp-module__number"
                         aria-hidden="true">

                        <?= Html::encode($module['number']) ?>

                    </div>

                    <div class="tp-module__identity">

                        <span class="tp-module__english">

                            <?= Html::encode($module['english']) ?>

                        </span>

                        <h3 class="tp-module__title">

                            <?= Html::encode($module['title']) ?>

                        </h3>

                    </div>

                    <div class="tp-module__description">

                        <?= Html::encode($module['description']) ?>

                    </div>

                    <span class="tp-module__arrow"
                          aria-hidden="true">↗</span>

                </a>

            <?php endforeach; ?>

        </div>


        <!-- 技术栈辅助标识 -->

        <div class="tp-project__stack">

            <span class="tp-project__stack-label">
                TECHNOLOGY STACK
            </span>

            <div class="tp-project__stack-items">

                <span>Yii2</span>

                <span>PHP</span>

                <span>MariaDB</span>

                <span>HTML / CSS / JS</span>

                <span>Bootstrap</span>

            </div>

        </div>

    </div>

</section>


<!-- ======================================
     04. 项目成果 / EXPLORE OUR WORK
     ====================================== -->

<section id="tp-work"
         class="tp-work"
         aria-labelledby="tp-work-title">

    <div class="wc-container">

        <!-- 章节编号 -->

        <div class="tp-work__meta">

            <span>03 / EXPLORE OUR WORK</span>

            <span>TEAM 404</span>

        </div>


        <!-- 成果区域标题 -->

        <div class="tp-work__heading">

            <h2 id="tp-work-title"
                class="tp-work__title">
                从项目到成果<span>。</span>
            </h2>

            <p class="tp-work__subtitle">
                记录我们的学习、实践与开发历程。
            </p>

        </div>


        <!-- 成果入口 -->

        <?php

        $workEntries = [
            [
                'number' => '01',
                'english' => 'COURSE ASSIGNMENTS',
                'title' => '课程作业',
                'description' => '团队成果与个人课程实践',
                'route' => ['/assignment/index'],
            ],
            [
                'number' => '02',
                'english' => 'VOICES & REFLECTIONS',
                'title' => '留言板',
                'description' => '团队声音与个人学习心得',
                'route' => ['/guestbook/index'],
            ],
        ];

        ?>

        <div class="tp-work__entries">

            <?php foreach ($workEntries as $entry): ?>

                <a class="tp-work-entry"
                   href="<?= Html::encode(
                       \yii\helpers\Url::to($entry['route'])
                   ) ?>">

                    <span class="tp-work-entry__number">

                        <?= Html::encode($entry['number']) ?>

                    </span>

                    <div class="tp-work-entry__identity">

                        <span class="tp-work-entry__english">

                            <?= Html::encode($entry['english']) ?>

                        </span>

                        <h3 class="tp-work-entry__title">

                            <?= Html::encode($entry['title']) ?>

                        </h3>

                    </div>

                    <span class="tp-work-entry__description">

                        <?= Html::encode($entry['description']) ?>

                    </span>

                    <span class="tp-work-entry__arrow"
                          aria-hidden="true">↗</span>

                </a>

            <?php endforeach; ?>

        </div>


        <!-- 团队页面收尾标识 -->

        <div class="tp-work__signature">

            <span>BUILT BY TEAM 404</span>

            <span>NKU / 2026</span>

        </div>

    </div>

</section>

</div>