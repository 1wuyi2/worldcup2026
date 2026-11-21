<?php

/**
 * 赛事后台新增及编辑表单。
 * @author lzs
 * @date 2026-11-20
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = ($model->isNewRecord ? '新增 · ' : '编辑 · ') . $sectionTitle;
$this->params['breadcrumbs'][] = ['label' => $sectionTitle, 'url' => ['index']];
?>
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['options' => ['class' => 'football-form']]); ?>
<?= $form->errorSummary($model) ?>

<?php foreach ($fields as $attribute => $fieldSpec): ?>
    <?php
    $field = $form->field($model, $attribute);
    $options = $fieldSpec['options'] ?? [];
    if (isset($fieldSpec['items'])) {
        $field->dropDownList($fieldSpec['items'], $options);
    } elseif (isset($fieldSpec['type'])) {
        $field->input($fieldSpec['type'], $options);
    } else {
        $field->textInput($options + ['maxlength' => true]);
    }
    if (isset($fieldSpec['hint'])) {
        $field->hint($fieldSpec['hint']);
    }
    ?>
    <?= $field ?>
<?php endforeach; ?>

<p>
    <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    <?= Html::a('返回列表', ['index'], ['class' => 'btn btn-default']) ?>
</p>
<?php ActiveForm::end(); ?>
