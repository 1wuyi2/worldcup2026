<?php

/**
 * 模块 B 后台文章列表与状态筛选。
 * @author XPC
 * @date 2026-11-22
 */

use common\models\NewsArticle;
use common\models\NewsCategory;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = '新闻文章管理';
$this->params['breadcrumbs'][] = $this->title;
$categories = ArrayHelper::map(NewsCategory::find()->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all(), 'id', 'name');
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_nav') ?>
<p><?= Html::a('新增文章', ['create'], ['class' => 'btn btn-success']) ?></p>
<?= Html::beginForm(['index'], 'get', ['class' => 'form-inline']) ?>
    <div class="form-group">
        <?= Html::label('标题关键词', 'news-admin-q') ?>
        <?= Html::textInput('q', $search->q, ['id' => 'news-admin-q', 'class' => 'form-control', 'maxlength' => 200]) ?>
    </div>
    <div class="form-group">
        <?= Html::label('分类', 'news-admin-category') ?>
        <?= Html::dropDownList('category_id', $search->category_id, $categories, ['id' => 'news-admin-category', 'prompt' => '全部分类', 'class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <?= Html::label('状态', 'news-admin-status') ?>
        <?= Html::dropDownList('status', $search->status, NewsArticle::STATUS_LABELS, ['id' => 'news-admin-status', 'prompt' => '全部状态', 'class' => 'form-control']) ?>
    </div>
    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('重置', ['index'], ['class' => 'btn btn-default']) ?>
<?= Html::endForm() ?>
<?= GridView::widget([
    'dataProvider' => $provider,
    'summary' => '第 {begin}–{end} 条，共 {totalCount} 条', 'emptyText' => '暂无文章。',
    'columns' => [
        'id', 'title', ['attribute' => 'category_id', 'value' => 'category.name'],
        ['label' => '标签', 'value' => static function ($article) {
            return implode('、', ArrayHelper::getColumn($article->tags, 'name'));
        }],
        ['attribute' => 'status', 'value' => 'statusLabel'],
        ['attribute' => 'published_at', 'value' => 'publishedAtLabel'],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]) ?>
