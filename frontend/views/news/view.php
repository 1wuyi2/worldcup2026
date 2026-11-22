<?php

/**
 * 模块 B 公开文章详情，正文及标题安全输出。
 * @author XPC
 * @date 2026-11-22
 */

use yii\helpers\Html;

$this->title = $article->title;
$this->params['breadcrumbs'][] = ['label' => '世界杯新闻资讯', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<article>
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Html::encode($article->category->name), ['index', 'category_id' => $article->category_id]) ?>
        · <?= Html::encode($article->publishedAtLabel) ?>（北京时间）
    </p>
    <p>
        <?php foreach ($article->tags as $tag): ?>
            <?= Html::a(Html::encode($tag->name), ['index', 'tag_id' => $tag->id], ['class' => 'label label-default']) ?>
        <?php endforeach; ?>
    </p>
    <?php if ($article->summary): ?><p class="well"><?= nl2br(Html::encode($article->summary)) ?></p><?php endif; ?>
    <div class="news-body"><?= nl2br(Html::encode($article->body)) ?></div>
    <?php if ($article->source_name || $article->source_url): ?>
        <p>来源：<?= Html::encode($article->source_name ?: '公开资料') ?>
            <?php if ($article->source_url): ?>
                <?= Html::a('查看原始来源', $article->source_url, ['rel' => 'noopener noreferrer']) ?>
            <?php endif; ?>
        </p>
    <?php endif; ?>
</article>
<p><?= Html::a('返回新闻列表', ['index'], ['class' => 'btn btn-default']) ?></p>
