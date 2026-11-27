<?php

/**
 * 模块 A 前台留言板页面。
 *
 * TEAM 404 / Voices & Reflections
 *
 * @author wuyi
 * @date 2026-11-20
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $team common\models\PortalTeam|null */
/* @var $teamLogoUrl string|null */
/* @var $memberAvatarUrls array */
/* @var $identity string */
/* @var $memberId string|int */
/* @var $memberOptions array */
/* @var $messageStats array */

$this->title = '留言板';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/portal.css', [
    'depends' => [AppAsset::className()],
]);

$this->registerJsFile('@web/js/guestbook.js', [
    'depends' => [AppAsset::className()],
]);


/* ========================================
   页面展示数据
   ======================================== */

$messages = $dataProvider->getModels();

$resultCount = $dataProvider->getTotalCount();

$pagination = $dataProvider->getPagination();

$currentIdentity = (string) $identity;

$currentMemberId = (string) $memberId;

$teamCount = (int) $messageStats['team'];
$personalCount = (int) $messageStats['personal'];
$totalCount = (int) $messageStats['total'];

$teamName = $team !== null
    ? $team->name
    : '团队';


/* 当前筛选成员 */

$selectedMemberName = isset(
    $memberOptions[$currentMemberId]
)
    ? $memberOptions[$currentMemberId]
    : null;


/* 身份分类导航 */

$identityTabs = [
    [
        'value' => '',
        'title' => '全部留言',
        'english' => 'ALL VOICES',
        'count' => $totalCount,
    ],
    [
        'value' => 'team',
        'title' => '团队留言',
        'english' => 'TEAM MESSAGE',
        'count' => $teamCount,
    ],
    [
        'value' => 'member',
        'title' => '个人留言',
        'english' => 'REFLECTIONS',
        'count' => $personalCount,
    ],
];


/* 分页后的连续编号 */

$messageOffset = $pagination !== false
    ? $pagination->getOffset()
    : 0;

?>

<div class="portal-page portal-voices">

    <!-- ======================================
         00. 留言板主视觉
         ====================================== -->

    <section class="tp-voices-hero"
             aria-labelledby="tp-voices-title">

        <div class="wc-container">

            <!-- 顶部辅助标识 -->

            <div class="tp-voices-hero__top">

                <span>TEAM 404 / VOICES</span>

                <span>NKU / 2026</span>

            </div>


            <!-- 大型英文标题 -->

            <div class="tp-voices-hero__typography"
                 aria-hidden="true">

                <div class="tp-voices-hero__english">

                    <span>VOICES &amp;</span>

                    <span class="tp-voices-hero__accent">
                        REFLECTIONS<span>.</span>
                    </span>

                </div>

                <div class="tp-voices-hero__quote">
                    ”
                </div>

            </div>


            <!-- 中文标题及介绍 -->

            <div class="tp-voices-hero__intro">

                <div>

                    <div class="tp-voices-hero__kicker">

                        <span></span>

                        THOUGHTS ALONG THE WAY

                    </div>

                    <h1 id="tp-voices-title"
                        class="tp-voices-hero__title">
                        留言与思考
                    </h1>

                </div>


                <div class="tp-voices-hero__description">

                    <p>
                        关于学习、实践、协作，
                        以及我们在项目开发过程中的一些思考。
                    </p>

                    <p>
                        这里记录由团队及成员发布的真实声音。
                    </p>

                </div>

            </div>


            <!-- 全局留言统计 -->

            <div class="tp-voices-stats">

                <?php foreach ($identityTabs as $tab): ?>

                    <?php

                    $route = ['/guestbook/index'];

                    if ($tab['value'] !== '') {
                        $route['identity'] = $tab['value'];
                    }

                    $route['#'] = 'tp-voices-archive';

                    ?>

                    <a class="tp-voices-stat"
                       href="<?= Html::encode(Url::to($route)) ?>">

                        <div class="tp-voices-stat__top">

                            <span class="tp-voices-stat__value">

                                <?= sprintf('%02d', $tab['count']) ?>

                            </span>

                            <span class="tp-voices-stat__arrow"
                                  aria-hidden="true">↓</span>

                        </div>

                        <span class="tp-voices-stat__label">

                            <?= Html::encode($tab['title']) ?>

                        </span>

                        <span class="tp-voices-stat__english">

                            <?= Html::encode($tab['english']) ?>

                        </span>

                    </a>

                <?php endforeach; ?>

            </div>

        </div>

    </section>


    <!-- ======================================
         01. 留言筛选工具栏
         ====================================== -->

    <section id="tp-voices-archive"
             class="tp-voices-toolbar"
             aria-labelledby="tp-voices-archive-title">

        <div class="wc-container">

            <div class="tp-voices-toolbar__meta">

                <span>01 / MESSAGE ARCHIVE</span>

                <span>

                    <?= sprintf('%02d', $resultCount) ?>

                    RESULTS

                </span>

            </div>


            <!-- 标题 -->

            <div class="tp-voices-toolbar__heading">

                <div>

                    <h2 id="tp-voices-archive-title"
                        class="tp-voices-toolbar__title">
                        来自团队的声音
                    </h2>

                    <p class="tp-voices-toolbar__subtitle">
                        Voices from the team.
                    </p>

                </div>

                <?= Html::a(
                    '返回团队 <span aria-hidden="true">↗</span>',
                    ['/team/index'],
                    [
                        'class' => 'tp-voices-toolbar__back',
                    ]
                ) ?>

            </div>


            <!-- 身份分类 -->

            <nav class="tp-voices-tabs"
                 aria-label="留言身份分类">

                <?php foreach ($identityTabs as $tab): ?>

                    <?php

                    $route = ['/guestbook/index'];

                    if ($tab['value'] !== '') {
                        $route['identity'] = $tab['value'];
                    }

                    $route['#'] = 'tp-voices-archive';

                    $isActive =
                        $currentIdentity === $tab['value'];

                    ?>

                    <a class="tp-voices-tab<?= $isActive
                        ? ' is-active'
                        : '' ?>"
                       href="<?= Html::encode(Url::to($route)) ?>"
                       <?= $isActive
                           ? 'aria-current="page"'
                           : '' ?>>

                        <span class="tp-voices-tab__title">

                            <?= Html::encode($tab['title']) ?>

                        </span>

                        <span class="tp-voices-tab__count">

                            <?= sprintf('%02d', $tab['count']) ?>

                        </span>

                    </a>

                <?php endforeach; ?>

            </nav>


            <!-- 成员筛选 -->

            <?php if ($currentIdentity !== 'team'): ?>

                <?= Html::beginForm(
                    [
                        '/guestbook/index',
                        '#' => 'tp-voices-archive',
                    ],
                    'get',
                    [
                        'id' => 'tp-voices-filter-form',
                        'class' => 'tp-voices-filters',
                    ]
                ) ?>

                    <?= Html::hiddenInput(
                        'identity',
                        'member'
                    ) ?>

                    <div class="tp-voices-filters__field">

                        <?= Html::label(
                            '发布成员',
                            'tp-voices-member',
                            [
                                'class' => 'tp-voices-filters__label',
                            ]
                        ) ?>

                        <?= Html::dropDownList(
                            'member_id',
                            $currentMemberId,
                            $memberOptions,
                            [
                                'id' => 'tp-voices-member',
                                'prompt' => '全部成员',
                                'class' => 'tp-voices-select',
                            ]
                        ) ?>

                        <?= Html::submitButton(
                            '筛选',
                            [
                                'class' => 'tp-voices-filter-submit',
                            ]
                        ) ?>

                    </div>

                    <?= Html::a(
                        '重置筛选',
                        [
                            '/guestbook/index',
                            '#' => 'tp-voices-archive',
                        ],
                        [
                            'class' => 'tp-voices-reset',
                        ]
                    ) ?>

                <?= Html::endForm() ?>

            <?php else: ?>

                <div class="tp-voices-team-note">

                    <span>TEAM MESSAGE / 团队寄语</span>

                    <?= Html::a(
                        '重置筛选',
                        [
                            '/guestbook/index',
                            '#' => 'tp-voices-archive',
                        ],
                        [
                            'class' => 'tp-voices-reset',
                        ]
                    ) ?>

                </div>

            <?php endif; ?>


            <!-- 当前成员提示 -->

            <?php if ($selectedMemberName !== null): ?>

                <div class="tp-voices-selection">

                    <div>

                        <span>当前发布成员</span>

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
                            'class' => 'tp-voices-toolbar__back',
                        ]
                    ) ?>

                </div>

            <?php endif; ?>

        </div>

    </section>


    <!-- ======================================
         02. 留言阅读区域
         ====================================== -->

    <section class="tp-content tp-voices-reading"
             aria-label="已发布留言">

        <div class="wc-container">

            <?php if (empty($messages)): ?>

                <!-- 空状态 -->

                <div class="tp-voices-empty">

                    <svg viewBox="0 0 48 48"
                         width="48"
                         height="48"
                         fill="none"
                         aria-hidden="true">

                        <path
                            d="M7 8H41V33H24L13 42V33H7V8Z"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linejoin="round"
                        />

                        <path
                            d="M15 17H33M15 24H29"
                            stroke="currentColor"
                            stroke-width="1.5"
                            stroke-linecap="round"
                        />

                    </svg>

                    <h2>暂无相关留言</h2>

                    <p>

                        <?php if ($currentIdentity === 'team'): ?>

                            团队暂未发布公开留言。

                        <?php elseif ($selectedMemberName !== null): ?>

                            该成员暂未发布个人留言。

                        <?php else: ?>

                            当前分类暂无已发布留言。

                        <?php endif; ?>

                    </p>

                    <?= Html::a(
                        '查看全部留言 ↗',
                        [
                            '/guestbook/index',
                            '#' => 'tp-voices-archive',
                        ],
                        [
                            'class' => 'tp-voices-empty__link',
                        ]
                    ) ?>

                </div>

            <?php else: ?>

                <div class="tp-voices-messages">

                    <?php foreach ($messages as $index => $message): ?>

                        <?php

                        /*
                         * 保留原有发布身份及本地头像解析逻辑。
                         */

                        $isMemberMessage =
                            $message->member !== null;

                        $publisherName = $isMemberMessage
                            ? $message->member->name
                            : (
                                $team !== null
                                ? $teamName
                                : $message->display_name
                            );

                        $imageUrl = $isMemberMessage
                            ? (
                                $memberAvatarUrls[
                                    (string) $message->member->id
                                ] ?? null
                            )
                            : $teamLogoUrl;

                        $publisherInitial = mb_substr(
                            trim((string) $publisherName),
                            0,
                            1,
                            'UTF-8'
                        );

                        $messageNumber = sprintf(
                            '%02d',
                            $messageOffset + $index + 1
                        );

                        $year = Yii::$app->formatter->asDate(
                            $message->created_at,
                            'php:Y'
                        );

                        $monthDay = Yii::$app->formatter->asDate(
                            $message->created_at,
                            'php:m.d'
                        );

                        $dateValue = Yii::$app->formatter->asDate(
                            $message->created_at,
                            'php:Y-m-d'
                        );

                        ?>

                        <article class="tp-voice<?= !$isMemberMessage
                            ? ' tp-voice--team'
                            : '' ?>">

                            <!-- 留言编号及身份 -->

                            <div class="tp-voice__header">

                                <span>

                                    MESSAGE / <?= $messageNumber ?>

                                </span>

                                <span class="tp-voice__type">

                                    <?= $isMemberMessage
                                        ? 'PERSONAL REFLECTION'
                                        : 'TEAM MESSAGE' ?>

                                </span>

                            </div>


                            <div class="tp-voice__layout">

                                <!-- 左侧：发布者档案 -->

                                <aside class="tp-voice__author">

                                    <div class="tp-voice__avatar">

                                        <?php if ($imageUrl !== null): ?>

                                            <?= Html::img($imageUrl, [
                                                'alt' => $publisherName,
                                                'loading' => 'lazy',
                                                'decoding' => 'async',
                                            ]) ?>

                                        <?php elseif ($isMemberMessage): ?>

                                            <span>

                                                <?= Html::encode(
                                                    $publisherInitial
                                                ) ?>

                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="tp-voice__team-mark">
                                                404
                                            </span>

                                        <?php endif; ?>

                                    </div>


                                    <h3 class="tp-voice__name">

                                        <?php if ($isMemberMessage): ?>

                                            <?= Html::a(
                                                Html::encode($publisherName),
                                                [
                                                    '/team/member',
                                                    'id' => $message->member->id,
                                                ]
                                            ) ?>

                                        <?php else: ?>

                                            <?= Html::a(
                                                Html::encode($publisherName),
                                                ['/team/index']
                                            ) ?>

                                        <?php endif; ?>

                                    </h3>


                                    <span class="tp-voice__role">

                                        <?= $isMemberMessage
                                            ? '个人留言'
                                            : '团队留言' ?>

                                    </span>


                                    <?php if ($isMemberMessage): ?>

                                        <?= Html::a(
                                            '个人主页 ↗',
                                            [
                                                '/team/member',
                                                'id' => $message->member->id,
                                            ],
                                            [
                                                'class' => 'tp-voice__profile-link',
                                            ]
                                        ) ?>

                                    <?php else: ?>

                                        <?= Html::a(
                                            '团队介绍 ↗',
                                            ['/team/index'],
                                            [
                                                'class' => 'tp-voice__profile-link',
                                            ]
                                        ) ?>

                                    <?php endif; ?>


                                    <!-- 发布日期 -->

                                    <time class="tp-voice__date"
                                          datetime="<?= Html::encode(
                                              $dateValue
                                          ) ?>">

                                        <span class="tp-voice__year">

                                            <?= Html::encode($year) ?>

                                        </span>

                                        <span class="tp-voice__month-day">

                                            <?= Html::encode($monthDay) ?>

                                        </span>

                                    </time>

                                </aside>


                                <!-- 右侧：留言正文 -->

                                <div class="tp-voice__body">

                                    <div class="tp-voice__quote"
                                         aria-hidden="true">
                                        “
                                    </div>

                                    <div class="tp-voice__text"><?= Html::encode(
                                        (string) $message->content
                                    ) ?></div>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>


                <!-- ======================================
                     分页
                     ====================================== -->

                <?php if (
                    $pagination !== false
                    && $pagination->getPageCount() > 1
                ): ?>

                    <div class="tp-voices-pagination">

                        <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'options' => [
                                'class' => 'pagination tp-voices-pager',
                            ],
                            'prevPageLabel' => '←',
                            'nextPageLabel' => '→',
                            'maxButtonCount' => 5,
                        ]) ?>

                    </div>

                <?php endif; ?>

            <?php endif; ?>


            <!-- 阅读区域底部标识 -->

            <div class="tp-profile-section-footer">

                <span class="tp-profile-section-footer__meta">

                    <?= sprintf('%02d', $resultCount) ?>

                    PUBLISHED MESSAGES

                </span>

                <span class="tp-voices-reading__note">
                    团队与成员授权发布 · 公开展示
                </span>

            </div>

        </div>

    </section>


    <!-- ======================================
         03. 页面收尾
         ====================================== -->

    <section class="tp-profile-closing"
             aria-labelledby="tp-voices-closing-title">

        <div class="wc-container">

            <div class="tp-profile-closing__meta">

                <span>TEAM 404 / VOICES &amp; REFLECTIONS</span>

                <span>NKU / 2026</span>

            </div>

            <a class="tp-profile-closing__link"
               href="<?= Html::encode(
                   Url::to(['/team/index'])
               ) ?>">

                <div>

                    <h2 id="tp-voices-closing-title"
                        class="tp-profile-closing__title">
                        了解我们的团队<span>。</span>
                    </h2>

                    <p class="tp-profile-closing__subtitle">
                        Meet the people behind the project.
                    </p>

                </div>

                <span class="tp-profile-closing__arrow"
                      aria-hidden="true">↗</span>

            </a>

        </div>

    </section>

</div>