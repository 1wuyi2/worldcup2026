<?php

/**
 * 模块 B 文章编辑与标签多选表单。
 * @author XPC
 * @date 2026-11-22
 */

use common\models\NewsArticle;
use common\models\NewsCategory;
use common\models\NewsTag;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? '新增新闻文章' : '编辑新闻文章';
$this->params['breadcrumbs'][] = ['label' => '新闻文章管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$categories = [];
foreach (NewsCategory::find()->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all() as $category) {
    $categories[$category->id] = $category->name . ((int) $category->status === 0 ? '（已停用）' : '');
}
$tags = ArrayHelper::map(NewsTag::find()->orderBy(['name' => SORT_ASC, 'id' => SORT_ASC])->all(), 'id', 'name');
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_nav') ?>
<?php if ($categories === []): ?>
    <p class="alert alert-info">请先<?= Html::a('创建新闻分类', ['/news-category/create']) ?>，再保存文章。</p>
<?php endif; ?>
<?php $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model) ?>
<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => '请选择分类']) ?>
<?= $form->field($model, 'summary')->textarea(['rows' => 3])->hint('可留空；列表将显示正文开头的内容。') ?>
<?= $form->field($model, 'body')->textarea(['rows' => 14])->hint('正文使用纯文本，换行会保留；HTML 代码将作为文字显示。') ?>
<?= $form->field($model, 'source_name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'source_url')->textInput(['maxlength' => true, 'type' => 'url']) ?>
<?= $form->field($model, 'tagIds')->checkboxList($tags, ['unselect' => ''])->hint('可选择多个标签，也可不选择。') ?>
<?= $form->field($model, 'status')->dropDownList(NewsArticle::STATUS_LABELS)
    ->hint('选择“已发布”会立即公开，并自动记录首次或重新发布的时间。') ?>
<p>
    <?= Html::submitButton('保存文章', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('取消', ['index'], ['class' => 'btn btn-default']) ?>
</p>
<?php ActiveForm::end(); ?>
