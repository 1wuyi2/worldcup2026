<?php

/**
 * 模块 A 团队成员后台表单。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use common\models\PortalTeam;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PortalMember */

$teamOptions = ArrayHelper::map(
    PortalTeam::find()->orderBy(['id' => SORT_ASC])->all(),
    'id',
    'name'
);
?>
<div class="portal-member-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'team_id')->dropDownList($teamOptions, ['prompt' => '请选择所属团队']) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'student_no')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'role')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'bio')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'avatar_path')
        ->textInput(['maxlength' => true])
        ->hint('填写 frontend/web 下的本地相对路径，例如 images/members/member-1.jpg；外部 URL 不会在前台加载。') ?>
    <?= $form->field($model, 'sort_order')->textInput(['type' => 'number', 'step' => 1]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '保存', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
        <?= Html::a('取消', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
