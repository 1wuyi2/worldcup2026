<?php

/**
 * 模块 B 后台文章详情及发布、撤稿操作。
 * @author XPC
 * @date 2026-11-22
 */

use common\models\NewsArticle;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = $article->title;
$this->params['breadcrumbs'][] = ['label' => '新闻文章管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_nav') ?>
<p>
    <?= Html::a('编辑', ['update', 'id' => $article->id], ['class' => 'btn btn-primary']) ?>
    <?php if ((int) $article->status !== NewsArticle::STATUS_PUBLISHED): ?>
        <?= Html::a('发布', ['publish', 'id' => $article->id], ['class' => 'btn btn-success', 'data' => ['method' => 'post', 'confirm' => '确认发布这篇新闻？']]) ?>
    <?php else: ?>
        <?= Html::a('撤稿', ['withdraw', 'id' => $article->id], ['class' => 'btn btn-warning', 'data' => ['method' => 'post', 'confirm' => '确认撤稿？前台将不再显示这篇新闻。']]) ?>
    <?php endif; ?>
    <?= Html::a('删除', ['delete', 'id' => $article->id], ['class' => 'btn btn-danger', 'data' => ['method' => 'post', 'confirm' => '确认永久删除文章及其标签关联？']]) ?>
</p>
<dl class="dl-horizontal">
    <dt>分类</dt><dd><?= Html::encode($article->category->name) ?>（<?= Html::encode($article->category->statusLabel) ?>）</dd>
    <dt>发布状态</dt><dd><?= Html::encode($article->statusLabel) ?></dd>
    <dt>发布时间</dt><dd><?= Html::encode($article->publishedAtLabel) ?></dd>
    <dt>标签</dt><dd><?= Html::encode(implode('、', ArrayHelper::getColumn($article->tags, 'name')) ?: '暂无标签') ?></dd>
    <dt>来源</dt><dd><?= Html::encode($article->source_name ?: '暂无来源资料') ?>
        <?php if ($article->source_url): ?>
            <?= Html::a('查看来源', $article->source_url, ['rel' => 'noopener noreferrer']) ?>
        <?php endif; ?>
    </dd>
</dl>
<?php if ($article->summary): ?><p class="well"><?= nl2br(Html::encode($article->summary)) ?></p><?php endif; ?>
<div class="news-body"><?= nl2br(Html::encode($article->body)) ?></div>
<p><?= Html::a('返回文章列表', ['index']) ?></p>
