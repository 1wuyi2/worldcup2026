<?php

/**
 * 模块 B 公开文章详情。
 *
 * 保留原有新闻分类、发布时间、摘要、
 * 正文、标签及资料来源的展示机制。
 *
 * @author XPC
 * @date 2026-11-22
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;


/* @var $article common\models\NewsArticle */

$this->title = $article->title;

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/news.css', [
    'depends' => [AppAsset::className()],
]);


// ==========================================
// 1. 获取文章基础信息
// ==========================================

$category = $article->category;

$tags = $article->tags;

$summary = trim((string) $article->summary);

$sourceName = trim((string) $article->source_name);

$sourceUrl = trim((string) $article->source_url);


// ==========================================
// 2. 校验外部资料链接
// ==========================================

$hasValidSourceUrl =
    filter_var($sourceUrl, FILTER_VALIDATE_URL)
    && in_array(
        strtolower(
            (string) parse_url(
                $sourceUrl,
                PHP_URL_SCHEME
            )
        ),
        ['http', 'https'],
        true
    );

?>


<div class="newsroom news-detail" id="nd-top">


    <!-- ======================================
         文章顶部导航
         ====================================== -->

    <div class="nd-navigation">

        <div class="wc-container">

            <div class="nd-navigation__inner">

                <?= Html::a(
                    '← 返回新闻资讯',
                    ['/news/index'],
                    ['class' => 'nd-navigation__back']
                ) ?>

                <span>
                    WORLD CUP 2026 / NEWSROOM
                </span>

            </div>

        </div>

    </div>


    <!-- ======================================
         新闻标题及摘要
         ====================================== -->

    <header class="nd-heading">

        <div class="wc-container">

            <div class="nd-heading__inner">


                <!-- 新闻分类 -->

                <div class="nd-heading__category">

                    <?php if ($category !== null): ?>

                        <?= Html::a(
                            Html::encode($category->name),
                            [
                                '/news/index',
                                'category_id' =>
                                    $article->category_id,
                            ]
                        ) ?>

                    <?php else: ?>

                        世界杯资讯

                    <?php endif; ?>

                    <span>WORLD CUP NEWS</span>

                </div>


                <!-- 新闻标题 -->

                <h1 class="nd-heading__title">

                    <?= Html::encode($article->title) ?>

                </h1>


                <!-- 新闻摘要 -->

                <?php if ($summary !== ''): ?>

                    <p class="nd-heading__summary">

                        <?= nl2br(
                            Html::encode($summary)
                        ) ?>

                    </p>

                <?php endif; ?>


                <!-- 发布时间及来源 -->

                <div class="nd-heading__meta">

                    <?php if ($sourceName !== ''): ?>

                        <strong>

                            <?= Html::encode($sourceName) ?>

                        </strong>

                        <span
                            class="nd-heading__separator"
                            aria-hidden="true"
                        ></span>

                    <?php endif; ?>

                    <span>

                        <?= Html::encode(
                            $article->publishedAtLabel
                        ) ?>

                        · 北京时间

                    </span>

                </div>


            </div>

        </div>

    </header>


    <!-- ======================================
         新闻正文与侧栏
         ====================================== -->

    <main class="nd-reading">

        <div class="wc-container nd-reading__layout">


            <!-- 左侧：新闻正文 -->

            <article class="nd-article">

                <div class="nd-article__body">

                    <?= Html::encode(
                        $article->body
                    ) ?>

                </div>


                <!-- ==========================
                     资料来源
                     ========================== -->

                <?php if (
                    $sourceName !== ''
                    || $hasValidSourceUrl
                ): ?>

                    <section class="nd-source">

                        <span class="nr-eyebrow">
                            SOURCE / REFERENCE
                        </span>

                        <h2>资料来源</h2>

                        <p>

                            <?= Html::encode(
                                $sourceName !== ''
                                    ? $sourceName
                                    : '公开资料'
                            ) ?>

                        </p>

                        <?php if ($hasValidSourceUrl): ?>

                            <?= Html::a(
                                '查看原始报道 ↗',
                                $sourceUrl,
                                [
                                    'class' =>
                                        'nd-source__link',
                                    'target' => '_blank',
                                    'rel' =>
                                        'noopener noreferrer',
                                ]
                            ) ?>

                        <?php endif; ?>

                    </section>

                <?php endif; ?>


                <!-- ==========================
                     新闻标签
                     ========================== -->

                <?php if (!empty($tags)): ?>

                    <section class="nd-tags">

                        <span class="nd-tags__label">

                            相关标签

                        </span>

                        <div class="nd-tags__list">

                            <?php foreach ($tags as $tag): ?>

                                <?= Html::a(
                                    '#' . Html::encode(
                                        $tag->name
                                    ),
                                    [
                                        '/news/index',
                                        'tag_id' => $tag->id,
                                    ],
                                    [
                                        'class' =>
                                            'nd-tags__link',
                                    ]
                                ) ?>

                            <?php endforeach; ?>

                        </div>

                    </section>

                <?php endif; ?>

            </article>


            <!-- 右侧：文章资料 -->

            <aside
                class="nd-sidebar"
                aria-labelledby="nd-sidebar-title"
            >

                <header class="nd-sidebar__heading">

                    <span class="nr-eyebrow">

                        ARTICLE INFORMATION

                    </span>

                    <h2 id="nd-sidebar-title">

                        文章信息

                    </h2>

                </header>


                <dl class="nd-facts">


                    <?php if ($category !== null): ?>

                        <div class="nd-facts__item">

                            <dt>新闻分类</dt>

                            <dd>

                                <?= Html::a(
                                    Html::encode(
                                        $category->name
                                    ),
                                    [
                                        '/news/index',
                                        'category_id' =>
                                            $article->category_id,
                                    ]
                                ) ?>

                            </dd>

                        </div>

                    <?php endif; ?>


                    <div class="nd-facts__item">

                        <dt>发布时间</dt>

                        <dd>

                            <?= Html::encode(
                                $article->publishedAtLabel
                            ) ?>

                            <small>北京时间</small>

                        </dd>

                    </div>


                    <?php if (
                        $sourceName !== ''
                        || $hasValidSourceUrl
                    ): ?>

                        <div class="nd-facts__item">

                            <dt>资料来源</dt>

                            <dd>

                                <?= Html::encode(
                                    $sourceName !== ''
                                        ? $sourceName
                                        : '公开资料'
                                ) ?>

                            </dd>

                        </div>

                    <?php endif; ?>


                </dl>


                <!-- 侧栏关联入口 -->

                <div class="nd-sidebar__links">

                    <?php if ($category !== null): ?>

                        <?= Html::a(
                            '浏览同类报道 →',
                            [
                                '/news/index',
                                'category_id' =>
                                    $article->category_id,
                            ]
                        ) ?>

                    <?php endif; ?>

                    <?= Html::a(
                        '浏览全部新闻 →',
                        ['/news/index']
                    ) ?>

                </div>


                <div class="nd-sidebar__edition">

                    <strong>2026</strong>

                    <span>WORLD CUP NEWSROOM</span>

                </div>

            </aside>

        </div>

    </main>


    <!-- ======================================
         文章底部导航
         ====================================== -->

    <div class="nd-bottom">

        <div class="wc-container">

            <div class="nd-bottom__inner">

                <?= Html::a(
                    '← 返回新闻列表',
                    ['/news/index'],
                    ['class' => 'nd-bottom__link']
                ) ?>

                <?= Html::a(
                    '返回顶部 ↑',
                    '#nd-top',
                    ['class' => 'nd-bottom__top']
                ) ?>

            </div>

        </div>

    </div>


</div>