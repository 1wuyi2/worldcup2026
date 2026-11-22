<?php

/**
 * 模块 B 分类与标签维护表单。
 * @author XPC
 * @date 2026-11-22
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $isCategory ? '新闻分类管理' : '新闻标签管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('/news/_nav') ?>
<?php $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model) ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?php if ($isCategory): ?>
    <?= $form->field($model, 'sort_order')->input('number', ['step' => 1])->hint('数值越小，分类越靠前。') ?>
    <?= $form->field($model, 'status')->dropDownList([1 => '启用', 0 => '停用'])
        ->hint('停用后，该分类中的文章不会在公开列表和详情中展示；重新启用后恢复。') ?>
<?php else: ?>
    <?= $form->field($model, 'slug')->textInput(['maxlength' => true])->hint('唯一标识，例如 world-cup、team-news。') ?>
<?php endif; ?>
<p>
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('取消', ['index'], ['class' => 'btn btn-default']) ?>
</p>
<?php ActiveForm::end(); ?>
