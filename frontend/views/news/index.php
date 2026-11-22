<?php

/**
 * 模块 B 公开新闻筛选表单及分页列表，沿用现有页面样式。
 * @author XPC
 * @date 2026-11-22
 */

use common\models\NewsCategory;
use common\models\NewsTag;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = '世界杯新闻资讯';
$this->params['breadcrumbs'][] = $this->title;
$categories = ArrayHelper::map(NewsCategory::find()->where(['status' => NewsCategory::STATUS_ENABLED])
    ->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all(), 'id', 'name');
$tags = ArrayHelper::map(NewsTag::find()->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC])->all(), 'id', 'name');
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= Html::beginForm(['index'], 'get', ['class' => 'form-inline']) ?>
    <div class="form-group">
        <?= Html::label('标题关键词', 'news-q') ?>
        <?= Html::textInput('q', $search->q, ['id' => 'news-q', 'class' => 'form-control', 'maxlength' => 200]) ?>
    </div>
    <div class="form-group">
        <?= Html::label('分类', 'news-category') ?>
        <?= Html::dropDownList('category_id', $search->category_id, $categories, ['id' => 'news-category', 'prompt' => '全部分类', 'class' => 'form-control']) ?>
    </div>
    <div class="form-group">
        <?= Html::label('标签', 'news-tag') ?>
        <?= Html::dropDownList('tag_id', $search->tag_id, $tags, ['id' => 'news-tag', 'prompt' => '全部标签', 'class' => 'form-control']) ?>
    </div>
    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('重置', ['index'], ['class' => 'btn btn-default']) ?>
<?= Html::endForm() ?>
<?= ListView::widget([
    'dataProvider' => $provider, 'itemView' => '_article',
    'summary' => '第 {begin}–{end} 条，共 {totalCount} 条',
    'emptyText' => '暂无符合条件的已发布新闻。',
    'layout' => "{summary}\n{items}\n{pager}",
]) ?>
