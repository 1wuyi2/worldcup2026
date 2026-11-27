<?php

/**
 * 模块 A 团队成员个人主页。
 *
 * TEAM 404 / Member Profile
 *
 * @author wuyi
 * @date 2026-11-26
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $member common\models\PortalMember */
/* @var $team common\models\PortalTeam|null */
/* @var $avatarUrl string|null */
/* @var $assignments common\models\PortalAssignment[] */
/* @var $messages common\models\PortalMessage[] */

$this->title = $member->name . '的个人主页';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/portal.css', [
    'depends' => [AppAsset::className()],
]);


/* ========================================
   页面展示数据
   ======================================== */

$teamName = $team !== null
    ? $team->name
    : '暂无团队资料';

$studentNo = trim((string) $member->student_no);

$memberRole = trim((string) $member->role);

$memberBio = trim((string) $member->bio);

$memberInitial = mb_substr(
    trim((string) $member->name),
    0,
    1,
    'UTF-8'
);

$assignmentCount = count($assignments);

$messageCount = count($messages);

?>

<div class="portal-page portal-profile">

    <!-- ======================================
         00. 个人主视觉
         ====================================== -->

    <section class="tp-profile-hero"
             aria-labelledby="tp-profile-title">

        <div class="wc-container">

            <!-- 顶部导航 -->

            <div class="tp-profile-hero__top">

                <span class="tp-profile-hero__eyebrow">
                    TEAM 404 / MEMBER PROFILE
                </span>

                <?= Html::a(
                    '<span aria-hidden="true">←</span> 返回团队',
                    ['/team/index'],
                    [
                        'class' => 'tp-profile-back',
                    ]
                ) ?>

            </div>


            <!-- 个人身份信息 -->

            <div class="tp-profile-hero__main">

                <!-- 头像展示底板 -->

                <div class="tp-profile-portrait">

                    <div class="tp-profile-portrait__meta">

                        <span>MEMBER PROFILE</span>

                        <span>
                            NKU / CS
                        </span>

                    </div>


                    <div class="tp-profile-portrait__stage">

                        <div class="tp-profile-portrait__frame">

                            <?php if ($avatarUrl !== null): ?>

                                <?= Html::img($avatarUrl, [
                                    'alt' => $member->name . '的头像',
                                    'class' => 'tp-profile-portrait__image',
                                    'decoding' => 'async',
                                ]) ?>

                            <?php else: ?>

                                <span
                                    class="tp-profile-portrait__initial"
                                    aria-hidden="true">

                                    <?= Html::encode(
                                        $memberInitial
                                    ) ?>

                                </span>

                            <?php endif; ?>

                        </div>

                    </div>


                    <div class="tp-profile-portrait__footer">

                        <span>TEAM 404</span>

                        <span>2026</span>

                    </div>

                </div>


                <!-- 姓名、学号、职责 -->

                <div class="tp-profile-identity">

                    <span class="tp-profile-identity__kicker">
                        DEVELOPER PROFILE
                    </span>

                    <h1 id="tp-profile-title"
                        class="tp-profile-identity__name">

                        <?= Html::encode($member->name) ?>

                    </h1>

                    <div class="tp-profile-identity__meta">

                        <span>

                            <?= $studentNo !== ''
                                ? 'NO. ' . Html::encode($studentNo)
                                : '学号未提供' ?>

                        </span>

                        <span class="tp-profile-identity__dot"
                              aria-hidden="true"></span>

                        <?= Html::a(
                            Html::encode($teamName),
                            ['/team/index']
                        ) ?>

                    </div>


                    <div class="tp-profile-identity__role">

                        <span class="tp-profile-identity__label">
                            PROJECT ROLE / 项目职责
                        </span>

                        <p>

                            <?= $memberRole !== ''
                                ? nl2br(Html::encode($memberRole))
                                : '项目职责待补充。' ?>

                        </p>

                    </div>

                </div>

            </div>


            <!-- 真实数据概览 -->

            <div class="tp-profile-stats">

                <a href="#tp-profile-assignments"
                   class="tp-profile-stat">

                    <div class="tp-profile-stat__top">

                        <span class="tp-profile-stat__value">

                            <?= sprintf('%02d', $assignmentCount) ?>

                        </span>

                        <span class="tp-profile-stat__arrow"
                              aria-hidden="true">↓</span>

                    </div>

                    <span class="tp-profile-stat__label">
                        已发布个人作业
                    </span>

                    <span class="tp-profile-stat__english">
                        ASSIGNMENTS
                    </span>

                </a>


                <a href="#tp-profile-messages"
                   class="tp-profile-stat">

                    <div class="tp-profile-stat__top">

                        <span class="tp-profile-stat__value">

                            <?= sprintf('%02d', $messageCount) ?>

                        </span>

                        <span class="tp-profile-stat__arrow"
                              aria-hidden="true">↓</span>

                    </div>

                    <span class="tp-profile-stat__label">
                        已发布个人留言
                    </span>

                    <span class="tp-profile-stat__english">
                        REFLECTIONS
                    </span>

                </a>


                <a href="<?= Html::encode(
                    \yii\helpers\Url::to(['/team/index'])
                ) ?>"
                   class="tp-profile-stat tp-profile-stat--team">

                    <div class="tp-profile-stat__top">

                        <span class="tp-profile-stat__value">

                            <?= Html::encode($teamName) ?>

                        </span>

                        <span class="tp-profile-stat__arrow"
                              aria-hidden="true">↗</span>

                    </div>

                    <span class="tp-profile-stat__label">
                        所属团队
                    </span>

                    <span class="tp-profile-stat__english">
                        THE TEAM
                    </span>

                </a>

            </div>

        </div>

    </section>


    <!-- ======================================
         01. 个人介绍
         ====================================== -->

    <section id="tp-profile-about"
             class="tp-content tp-profile-about"
             aria-labelledby="tp-profile-about-title">

        <div class="wc-container">

            <div class="tp-section__meta">

                <span>01 / ABOUT ME</span>

                <span>PERSONAL PROFILE</span>

            </div>


            <div class="tp-profile-about__layout">

                <div>

                    <h2 id="tp-profile-about-title"
                        class="tp-section__title">
                        关于我
                    </h2>

                    <p class="tp-profile-about__subtitle">
                        A little about myself.
                    </p>

                </div>


                <div class="tp-profile-about__body">

                    <p>

                        <?= $memberBio !== ''
                            ? nl2br(Html::encode($memberBio))
                            : '个人介绍待补充。' ?>

                    </p>

                </div>

            </div>

        </div>

    </section>


       <!-- ======================================
         02. 个人课程作业 / ASSIGNMENTS
         ====================================== -->

    <section id="tp-profile-assignments"
             class="tp-content tp-profile-assignments"
             aria-labelledby="tp-profile-assignments-title">

        <div class="wc-container">

            <!-- 章节标识 -->

            <div class="tp-section__meta">

                <span>02 / ASSIGNMENTS</span>

                <span>
                    <?= sprintf('%02d', $assignmentCount) ?>
                    FILES
                </span>

            </div>


            <!-- 章节标题 -->

            <div class="tp-profile-section-heading">

                <h2 id="tp-profile-assignments-title"
                    class="tp-section__title">
                    个人课程作业
                </h2>

                <p class="tp-profile-section-subtitle">
                    Coursework &amp; Practice.
                </p>

            </div>


            <!-- 文档列表 -->

            <?php if (empty($assignments)): ?>

                <div class="tp-empty">
                    该成员暂未发布个人课程作业。
                </div>

            <?php else: ?>

                <div class="tp-documents">

                    <!-- 文档列表表头 -->

                    <div class="tp-documents__header"
                         aria-hidden="true">

                        <span>NO.</span>

                        <span>DOCUMENT / 作业名称</span>

                        <span>FORMAT / 格式</span>

                        <span>DOWNLOAD</span>

                    </div>


                    <!-- 文档记录 -->

                    <?php foreach (
                        $assignments as $index => $assignment
                    ): ?>

                        <?php

                        // 仅提取文件扩展名用于前台展示。
                        // 不直接暴露附件的实际存储路径。
                        $fileExtension = strtoupper(
                            pathinfo(
                                (string) $assignment->relative_path,
                                PATHINFO_EXTENSION
                            )
                        );

                        if ($fileExtension === '') {
                            $fileExtension = 'FILE';
                        }

                        $description = trim(
                            (string) $assignment->description
                        );

                        ?>

                        <article class="tp-document">

                            <!-- 列表编号 -->

                            <span class="tp-document__number">

                                <?= sprintf('%02d', $index + 1) ?>

                            </span>


                            <!-- 文件图标及作业信息 -->

                            <div class="tp-document__main">

                                <div class="tp-document__icon"
                                     aria-hidden="true">

                                    <svg
                                        viewBox="0 0 32 32"
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
                                            stroke-linejoin="round"
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

                                    <?php if ($description !== ''): ?>

                                        <p class="tp-document__description">

                                            <?= nl2br(
                                                Html::encode($description)
                                            ) ?>

                                        </p>

                                    <?php else: ?>

                                        <span class="tp-document__category">
                                            个人课程作业
                                        </span>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- 文件格式 -->

                            <span class="tp-document__format">

                                <?= Html::encode($fileExtension) ?>

                            </span>


                            <!-- 安全下载入口 -->

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


            <!-- 作业中心入口 -->

            <div class="tp-profile-section-footer">

                <span class="tp-profile-section-footer__meta">

                    <?= sprintf('%02d', $assignmentCount) ?>

                    DOCUMENTS AVAILABLE

                </span>

                <?= Html::a(
                    '前往课程资料中心
                    <span aria-hidden="true">↗</span>',
                    [
                        '/assignment/index',
                        'category' => 'personal',
                        'member_id' => $member->id,
                    ],
                    [
                        'class' => 'tp-profile-section-link',
                    ]
                ) ?>

            </div>

        </div>

    </section>


    <!-- ======================================
         03. 学习心得 / REFLECTIONS
         ====================================== -->

    <section id="tp-profile-messages"
             class="tp-content tp-profile-reflections"
             aria-labelledby="tp-profile-messages-title">

        <div class="wc-container">

            <!-- 章节标识 -->

            <div class="tp-section__meta">

                <span>03 / REFLECTIONS</span>

                <span>
                    <?= sprintf('%02d', $messageCount) ?>
                    MESSAGES
                </span>

            </div>


            <!-- 章节标题 -->

            <div class="tp-profile-section-heading">

                <h2 id="tp-profile-messages-title"
                    class="tp-section__title">
                    学习心得
                </h2>

                <p class="tp-profile-section-subtitle">
                    Notes from the journey.
                </p>

            </div>


            <!-- 留言阅读区域 -->

            <?php if (empty($messages)): ?>

                <div class="tp-empty">
                    该成员暂未发布个人留言。
                </div>

            <?php else: ?>

                <div class="tp-reflections">

                    <?php foreach (
                        $messages as $index => $message
                    ): ?>

                        <?php

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

                        <article class="tp-reflection">

                            <!-- 左侧：日期及作者 -->

                            <aside class="tp-reflection__meta">

                                <div class="tp-reflection__quote"
                                     aria-hidden="true">
                                    “
                                </div>

                                <time class="tp-reflection__date"
                                      datetime="<?= Html::encode(
                                          $dateValue
                                      ) ?>">

                                    <span class="tp-reflection__year">

                                        <?= Html::encode($year) ?>

                                    </span>

                                    <span class="tp-reflection__month-day">

                                        <?= Html::encode($monthDay) ?>

                                    </span>

                                </time>


                                <div class="tp-reflection__author">

                                    <span class="tp-reflection__number">

                                        REFLECTION /

                                        <?= sprintf('%02d', $index + 1) ?>

                                    </span>

                                    <span class="tp-reflection__author-name">

                                        <?= Html::encode(
                                            $member->name
                                        ) ?>

                                    </span>

                                </div>

                            </aside>


                            <!-- 右侧：完整留言正文 -->

                            <div class="tp-reflection__body">

                                <div class="tp-reflection__text"><?= Html::encode(
                                    (string) $message->content
                                ) ?></div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <!-- 留言板入口 -->

            <div class="tp-profile-section-footer">

                <span class="tp-profile-section-footer__meta">
                    VOICES &amp; REFLECTIONS
                </span>

                <?= Html::a(
                    '查看全部个人留言
                    <span aria-hidden="true">↗</span>',
                    [
                        '/guestbook/index',
                        'identity' => 'member',
                        'member_id' => $member->id,
                    ],
                    [
                        'class' => 'tp-profile-section-link',
                    ]
                ) ?>

            </div>

        </div>

    </section>


    <!-- ======================================
         04. 个人主页收尾
         ====================================== -->

    <section class="tp-profile-closing"
             aria-labelledby="tp-profile-closing-title">

        <div class="wc-container">

            <div class="tp-profile-closing__meta">

                <span>TEAM 404 / MEMBER PROFILE</span>

                <span>NKU / 2026</span>

            </div>

            <a class="tp-profile-closing__link"
               href="<?= Html::encode(
                   \yii\helpers\Url::to(['/team/index'])
               ) ?>">

                <div>

                    <h2 id="tp-profile-closing-title"
                        class="tp-profile-closing__title">
                        认识更多团队成员<span>。</span>
                    </h2>

                    <p class="tp-profile-closing__subtitle">
                        Meet the rest of the team.
                    </p>

                </div>

                <span class="tp-profile-closing__arrow"
                      aria-hidden="true">↗</span>

            </a>

        </div>

    </section>

</div>