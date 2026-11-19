<?php

/**
 * 模块 A 团队资料后台表单。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortalTeam */
?>
<div class="portal-team-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>
    <?= $form->field($model, 'logo_path')
        ->textInput(['maxlength' => true])
        ->hint('填写 frontend/web 下的本地相对路径，例如 images/team/logo.png；外部 URL 不会在前台加载。') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '保存', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
        <?= Html::a('取消', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
