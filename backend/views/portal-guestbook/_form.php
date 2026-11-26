<?php

/**
 * 模块 A 后台团队及个人留言发布表单。
 *
 * @author wuyi
 * @date 2026-11-26
 */

use backend\models\PortalMessageForm;
use common\models\PortalMessage;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PortalMessageForm */
/* @var $memberOptions array */
?>
<div class="portal-guestbook-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'publisher_type')->dropDownList(PortalMessageForm::getPublisherTypeOptions()) ?>
    <?= $form->field($model, 'member_id')->dropDownList($memberOptions, [
        'prompt' => '团队身份无需选择；成员身份请选择成员',
    ])->hint('成员身份必须关联真实成员；团队身份保存时由服务器清空成员字段。') ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>
    <?= $form->field($model, 'status')->dropDownList(PortalMessage::getStatusOptions()) ?>

    <div class="alert alert-info">
        公开发布者名称由服务器根据团队或成员资料生成；留言来源和后台操作人员不能通过表单修改。
    </div>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('取消', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
