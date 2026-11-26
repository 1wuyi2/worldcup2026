<?php

/**
 * 模块 A 课程资料后台表单。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use common\models\PortalAssignment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PortalAssignmentForm */
/* @var $memberOptions array */

$phpUploadLimit = ini_get('upload_max_filesize');
$phpPostLimit = ini_get('post_max_size');
?>
<div class="portal-assignment-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'category')->dropDownList(PortalAssignment::getCategoryOptions()) ?>
    <?= $form->field($model, 'member_id')->dropDownList($memberOptions, [
        'prompt' => '团队作业无需选择；个人作业请选择成员',
    ])->hint('个人作业必须关联真实成员；团队作业保存时会由服务器清空该字段。') ?>
    <?= $form->field($model, 'owner_label')
        ->textInput(['maxlength' => true])
        ->hint('个人作业保存时会自动使用关联成员姓名覆盖此快照；团队作业可保留兼容显示文字。') ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'relative_path')
        ->textInput(['maxlength' => true])
        ->hint('登记项目内已有文件时填写，例如 data/team/report.pdf。上传新文件后系统会自动生成此路径。') ?>
    <?= $form->field($model, 'uploadFile')
        ->fileInput(['accept' => '.pdf,.doc,.docx,.ppt,.pptx,.zip,.txt'])
        ->hint(
            '可上传 PDF、DOC/DOCX、PPT/PPTX、ZIP、TXT，应用限制 20 MB；'
            . '当前 PHP upload_max_filesize=' . Html::encode($phpUploadLimit)
            . '，post_max_size=' . Html::encode($phpPostLimit)
            . '。编辑时上传新文件会替换数据库路径，但不会删除旧文件。'
        ) ?>
    <?= $form->field($model, 'status')->dropDownList(PortalAssignment::getStatusOptions()) ?>

    <div class="alert alert-warning">
        选择“已发布”时，文件必须已实际存在且通过安全路径检查；建议先保存为未发布，再核对后发布。
    </div>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('取消', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
