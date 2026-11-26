<?php

/**
 * 新闻列表文章条目。
 *
 * 保留新闻标题、摘要、分类、标签及详情入口。
 *
 * @author XPC
 * @date 2026-11-22
 */

use yii\helpers\Html;


// ==========================================
// 1. 获取新闻摘要
// ==========================================

$summary = trim(
    (string) $model->summary
);

if ($summary === '') {

    $summary = trim(
        (string) $model->body
    );

}

$summary = preg_replace(
    '/\s+/u',
    ' ',
    $summary
) ?? $summary;

if (mb_strlen($summary, 'UTF-8') > 180) {

    $summary = mb_substr(
        $summary,
        0,
        180,
        'UTF-8'
    ) . '…';

}


// ==========================================
// 2. 格式化发布时间
// ==========================================

$publishedDate = null;

if ($model->published_at !== null) {

    $publishedDate =
        (new \DateTimeImmutable(
            '@' . $model->published_at
        ))
        ->setTimezone(
            new \DateTimeZone('Asia/Shanghai')
        );

}

?>


<article class="nr-article">


    <!-- 左侧新闻日期 -->

    <div class="nr-article__date">

        <?php if ($publishedDate !== null): ?>

            <time datetime="<?= Html::encode(
                $publishedDate->format('c')
            ) ?>">

                <strong>

                    <?= Html::encode(
                        $publishedDate->format('d')
                    ) ?>

                </strong>

                <span>

                    <?= Html::encode(
                        $publishedDate->format('n')
                    ) ?>月

                    /

                    <?= Html::encode(
                        $publishedDate->format('Y')
                    ) ?>

                </span>

            </time>

        <?php else: ?>

            <span>暂无日期</span>

        <?php endif; ?>

    </div>


    <!-- 中间新闻内容 -->

    <div class="nr-article__content">


        <!-- 新闻分类及时间 -->

        <div class="nr-article__meta">

            <?php if ($model->category !== null): ?>

                <?= Html::a(
                    Html::encode(
                        $model->category->name
                    ),
                    [
                        '/news/index',
                        'category_id' =>
                            $model->category_id,
                        'q' => $keyword,
                        'tag_id' => $tagId,
                    ],
                    [
                        'class' =>
                            'nr-article__category',
                    ]
                ) ?>

            <?php endif; ?>


            <?php if ($publishedDate !== null): ?>

                <span class="nr-article__time">

                    <?= Html::encode(
                        $publishedDate->format('H:i')
                    ) ?>

                </span>

            <?php endif; ?>


            <?php if ($model->source_name): ?>

                <span class="nr-article__source">

                    <?= Html::encode(
                        $model->source_name
                    ) ?>

                </span>

            <?php endif; ?>

        </div>


        <!-- 新闻标题 -->

        <h3 class="nr-article__title">

            <?= Html::a(
                Html::encode($model->title),
                [
                    '/news/view',
                    'id' => $model->id,
                ]
            ) ?>

        </h3>


        <!-- 新闻摘要 -->

        <?php if ($summary !== ''): ?>

            <p class="nr-article__summary">

                <?= Html::encode($summary) ?>

            </p>

        <?php endif; ?>


        <!-- 新闻标签 -->

        <?php if (!empty($model->tags)): ?>

            <div class="nr-article__tags">

                <?php foreach ($model->tags as $tag): ?>

                    <?= Html::a(
                        '#' . Html::encode($tag->name),
                        [
                            '/news/index',
                            'tag_id' => $tag->id,
                            'q' => $keyword,
                            'category_id' => $categoryId,
                        ],
                        [
                            'class' =>
                                'nr-article__tag',
                        ]
                    ) ?>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>


    <!-- 右侧阅读入口 -->

    <?= Html::a(
        '↗',
        [
            '/news/view',
            'id' => $model->id,
        ],
        [
            'class' => 'nr-article__arrow',
            'aria-label' =>
                '阅读完整报道：' . $model->title,
        ]
    ) ?>


</article>