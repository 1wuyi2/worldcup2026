<?php

/**
 * 模块 B 新闻资讯首页。
 *
 * 保留原有公开新闻查询、分类、标签及分页机制。
 * 采用体育新闻编辑部式布局。
 *
 * @author XPC
 * @date 2026-11-22
 */

use common\models\NewsCategory;
use common\models\NewsTag;
use frontend\assets\AppAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;


$this->title = '世界杯新闻资讯';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/news.css', [
    'depends' => [AppAsset::className()],
]);


// ==========================================
// 1. 获取新闻分类及标签
// ==========================================

$categories = ArrayHelper::map(
    NewsCategory::find()
        ->where([
            'status' => NewsCategory::STATUS_ENABLED,
        ])
        ->orderBy([
            'sort_order' => SORT_ASC,
            'id' => SORT_ASC,
        ])
        ->all(),
    'id',
    'name'
);

$tags = ArrayHelper::map(
    NewsTag::find()
        ->orderBy([
            'name' => SORT_ASC,
            'id' => SORT_ASC,
        ])
        ->all(),
    'id',
    'name'
);


// ==========================================
// 2. 获取新闻数据及分页信息
// ==========================================

$articles = $provider->getModels();

$totalCount = $provider->getTotalCount();

$pagination = $provider->getPagination();


// ==========================================
// 3. 获取当前筛选状态
// ==========================================

$keyword = trim((string) $search->q);

$categoryId = (string) $search->category_id;

$tagId = (string) $search->tag_id;

$hasFilters =
    $keyword !== ''
    || $categoryId !== ''
    || $tagId !== '';

$categoryName = $categories[$categoryId] ?? '';

$tagName = $tags[$tagId] ?? '';


// ==========================================
// 4. 构造焦点报道
// ==========================================

// 仅在未筛选、默认排序且处于第一页时显示。
$showSpotlight =
    !$hasFilters
    && $pagination->getPage() === 0
    && Yii::$app->request->get('sort') === null
    && !empty($articles);

$featuredArticle = $showSpotlight
    ? reset($articles)
    : null;

$latestArticles = $showSpotlight
    ? array_slice($articles, 1, 3)
    : [];


// ==========================================
// 5. 新闻摘要处理
// ==========================================

$makeSummary = static function (
    $article,
    $length = 160
) {

    $summary = trim(
        (string) $article->summary
    );

    if ($summary === '') {

        $summary = trim(
            (string) $article->body
        );

    }

    $summary = preg_replace(
        '/\s+/u',
        ' ',
        $summary
    ) ?? $summary;

    if (
        mb_strlen($summary, 'UTF-8')
        > $length
    ) {

        $summary = mb_substr(
            $summary,
            0,
            $length,
            'UTF-8'
        ) . '…';

    }

    return $summary;

};

?>


<div class="newsroom">


    <!-- ======================================
         新闻编辑部页头
         ====================================== -->

    <header class="nr-masthead">

        <div class="wc-container nr-masthead__inner">

            <div class="nr-masthead__identity">

                <span class="nr-eyebrow">
                    THE WORLD CUP NEWSROOM
                </span>

                <h1 class="nr-masthead__title">

                    世界杯新闻<span>.</span>

                </h1>

                <p>
                    赛场内外，记录足球世界的每一个瞬间。
                </p>

            </div>

            <div class="nr-masthead__edition">

                <strong>2026</strong>

                <span>WORLD CUP EDITION</span>

            </div>

        </div>

    </header>


    <!-- ======================================
         新闻分类导航
         ====================================== -->

    <div class="nr-navigation">

        <div class="wc-container">

            <nav
                class="nr-category-nav"
                aria-label="新闻分类"
            >

                <?= Html::a(
                    '全部资讯',
                    [
                        '/news/index',
                        'q' => $keyword,
                        'tag_id' => $tagId,
                    ],
                    [
                        'class' => 'nr-category-nav__link'
                            . (
                                $categoryId === ''
                                    ? ' is-active'
                                    : ''
                            ),
                        'aria-current' =>
                            $categoryId === ''
                                ? 'page'
                                : null,
                    ]
                ) ?>


                <?php foreach (
                    $categories as $id => $name
                ): ?>

                    <?= Html::a(
                        Html::encode($name),
                        [
                            '/news/index',
                            'category_id' => $id,
                            'q' => $keyword,
                            'tag_id' => $tagId,
                        ],
                        [
                            'class' => 'nr-category-nav__link'
                                . (
                                    $categoryId === (string) $id
                                        ? ' is-active'
                                        : ''
                                ),
                            'aria-current' =>
                                $categoryId === (string) $id
                                    ? 'page'
                                    : null,
                        ]
                    ) ?>

                <?php endforeach; ?>

            </nav>

        </div>

    </div>


    <!-- ======================================
         新闻搜索与标签筛选
         ====================================== -->

    <section
        class="nr-search"
        aria-label="新闻搜索"
    >

        <div class="wc-container">

            <?= Html::beginForm(
                ['/news/index'],
                'get',
                ['class' => 'nr-search__form']
            ) ?>


                <?= Html::hiddenInput(
                    'category_id',
                    $categoryId
                ) ?>


                <div class="nr-search__field">

                    <?= Html::label(
                        '标题关键词',
                        'news-q'
                    ) ?>

                    <?= Html::textInput(
                        'q',
                        $keyword,
                        [
                            'id' => 'news-q',
                            'class' => 'nr-search__input',
                            'maxlength' => 200,
                            'placeholder' =>
                                '搜索你感兴趣的新闻...',
                        ]
                    ) ?>

                </div>


                <div class="nr-search__field">

                    <?= Html::label(
                        '新闻标签',
                        'news-tag'
                    ) ?>

                    <?= Html::dropDownList(
                        'tag_id',
                        $tagId,
                        $tags,
                        [
                            'id' => 'news-tag',
                            'class' => 'nr-search__select',
                            'prompt' => '全部标签',
                        ]
                    ) ?>

                </div>


                <div class="nr-search__actions">

                    <?= Html::submitButton(
                        '搜索资讯',
                        [
                            'class' =>
                                'nr-search__submit',
                        ]
                    ) ?>

                    <?= Html::a(
                        '重置',
                        ['/news/index'],
                        [
                            'class' =>
                                'nr-search__reset',
                        ]
                    ) ?>

                </div>


            <?= Html::endForm() ?>

        </div>

    </section>


    <!-- ======================================
         当前筛选条件
         ====================================== -->

    <?php if ($hasFilters): ?>

        <section class="nr-filter-context">

            <div class="wc-container">

                <div class="nr-filter-context__inner">

                    <span>当前筛选</span>


                    <?php if ($keyword !== ''): ?>

                        <span class="nr-filter-context__item">

                            关键词：
                            <?= Html::encode($keyword) ?>

                            <?= Html::a(
                                '×',
                                [
                                    '/news/index',
                                    'category_id' =>
                                        $categoryId,
                                    'tag_id' => $tagId,
                                ],
                                [
                                    'aria-label' =>
                                        '取消关键词筛选',
                                ]
                            ) ?>

                        </span>

                    <?php endif; ?>


                    <?php if ($categoryName !== ''): ?>

                        <span class="nr-filter-context__item">

                            <?= Html::encode($categoryName) ?>

                            <?= Html::a(
                                '×',
                                [
                                    '/news/index',
                                    'q' => $keyword,
                                    'tag_id' => $tagId,
                                ],
                                [
                                    'aria-label' =>
                                        '取消分类筛选',
                                ]
                            ) ?>

                        </span>

                    <?php endif; ?>


                    <?php if ($tagName !== ''): ?>

                        <span class="nr-filter-context__item">

                            #<?= Html::encode($tagName) ?>

                            <?= Html::a(
                                '×',
                                [
                                    '/news/index',
                                    'q' => $keyword,
                                    'category_id' =>
                                        $categoryId,
                                ],
                                [
                                    'aria-label' =>
                                        '取消标签筛选',
                                ]
                            ) ?>

                        </span>

                    <?php endif; ?>

                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- ======================================
         焦点报道与最新动态
         ====================================== -->

    <?php if ($featuredArticle !== null): ?>

        <section
            class="nr-editorial"
            aria-label="焦点报道与最新动态"
        >

            <div class="wc-container">

                <div class="nr-editorial__layout
                    <?= empty($latestArticles)
                        ? ' nr-editorial__layout--single'
                        : '' ?>">


                    <!-- 左侧焦点报道 -->

                    <article class="nr-featured">

                        <div class="nr-featured__heading">

                            <span class="nr-featured__line"></span>

                            <span>
                                TOP STORY / 焦点报道
                            </span>

                        </div>

                        <div class="nr-featured__content">

                            <span class="nr-featured__category">

                                <?= Html::encode(
                                    $featuredArticle->category
                                        ? $featuredArticle->category->name
                                        : '世界杯资讯'
                                ) ?>

                            </span>

                            <h2>

                                <?= Html::a(
                                    Html::encode(
                                        $featuredArticle->title
                                    ),
                                    [
                                        '/news/view',
                                        'id' =>
                                            $featuredArticle->id,
                                    ]
                                ) ?>

                            </h2>

                            <p>

                                <?= Html::encode(
                                    $makeSummary(
                                        $featuredArticle,
                                        220
                                    )
                                ) ?>

                            </p>

                        </div>

                        <div class="nr-featured__footer">

                            <span>

                                <?= Html::encode(
                                    $featuredArticle->publishedAtLabel
                                ) ?>

                            </span>

                            <?= Html::a(
                                '阅读完整报道 →',
                                [
                                    '/news/view',
                                    'id' =>
                                        $featuredArticle->id,
                                ],
                                [
                                    'class' =>
                                        'nr-featured__more',
                                ]
                            ) ?>

                        </div>

                    </article>


                    <!-- 右侧最新动态 -->

                    <?php if (!empty($latestArticles)): ?>

                        <aside class="nr-latest">

                            <header class="nr-latest__heading">

                                <h2>最新动态</h2>

                                <span>LATEST NEWS</span>

                            </header>


                            <?php foreach (
                                $latestArticles as $index => $article
                            ): ?>

                                <article class="nr-latest__item">

                                    <span class="nr-latest__number">

                                        <?= sprintf(
                                            '%02d',
                                            $index + 2
                                        ) ?>

                                    </span>

                                    <div>

                                        <span class="nr-latest__category">

                                            <?= Html::encode(
                                                $article->category
                                                    ? $article->category->name
                                                    : '世界杯资讯'
                                            ) ?>

                                        </span>

                                        <h3>

                                            <?= Html::a(
                                                Html::encode(
                                                    $article->title
                                                ),
                                                [
                                                    '/news/view',
                                                    'id' => $article->id,
                                                ]
                                            ) ?>

                                        </h3>

                                        <time>

                                            <?= Html::encode(
                                                $article->publishedAtLabel
                                            ) ?>

                                        </time>

                                    </div>

                                </article>

                            <?php endforeach; ?>

                        </aside>

                    <?php endif; ?>


                </div>

            </div>

        </section>

    <?php endif; ?>


    <!-- ======================================
         完整新闻列表
         ====================================== -->

    <section
        class="nr-directory"
        id="nr-directory"
        aria-labelledby="nr-directory-title"
    >

        <div class="wc-container">

            <header class="nr-directory__header">

                <div>

                    <span class="nr-eyebrow">
                        ALL STORIES / NEWS ARCHIVE
                    </span>

                    <h2 id="nr-directory-title">

                        <?= Html::encode(
                            $hasFilters
                                ? (
                                    $categoryName !== ''
                                        ? $categoryName
                                        : '搜索结果'
                                )
                                : '全部报道'
                        ) ?>

                    </h2>

                </div>

                <div class="nr-directory__count">

                    <strong>

                        <?= number_format(
                            (int) $totalCount
                        ) ?>

                    </strong>

                    <span>篇报道</span>

                </div>

            </header>


            <?php if (!empty($articles)): ?>

                <div class="nr-article-list">

                    <?php foreach ($articles as $article): ?>

                        <?= $this->render('_article', [

                            'model' => $article,

                            'keyword' => $keyword,

                            'categoryId' => $categoryId,

                            'tagId' => $tagId,

                        ]) ?>

                    <?php endforeach; ?>

                </div>


                <!-- 分页 -->

                <div class="nr-pagination">

                    <p>

                        第
                        <?= $pagination->getOffset() + 1 ?>

                        –

                        <?= min(
                            $pagination->getOffset()
                                + count($articles),
                            $totalCount
                        ) ?>

                        篇，共

                        <?= number_format(
                            (int) $totalCount
                        ) ?>

                        篇报道

                    </p>


                    <?= LinkPager::widget([

                        'pagination' => $pagination,

                        'maxButtonCount' => 5,

                        'prevPageLabel' => '←',

                        'nextPageLabel' => '→',

                        'options' => [

                            'class' =>
                                'pagination nr-pagination__links',

                        ],

                    ]) ?>

                </div>


            <?php else: ?>


                <!-- 空数据状态 -->

                <div class="nr-empty">

                    <span class="nr-empty__symbol">
                        00
                    </span>

                    <h3>

                        <?= $hasFilters
                            ? '暂无符合条件的新闻'
                            : '暂无已发布新闻' ?>

                    </h3>

                    <p>

                        <?= $hasFilters
                            ? '尝试更换关键词、新闻分类或标签。'
                            : '世界杯新闻资讯将在发布后展示于此。' ?>

                    </p>

                    <?php if ($hasFilters): ?>

                        <?= Html::a(
                            '查看全部资讯 →',
                            ['/news/index'],
                            ['class' => 'nr-empty__link']
                        ) ?>

                    <?php endif; ?>

                </div>


            <?php endif; ?>

        </div>

    </section>

</div>