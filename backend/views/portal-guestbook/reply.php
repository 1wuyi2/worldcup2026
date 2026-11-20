<?php

/**
 * 模块 A 后台留言回复编辑页。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $message common\models\PortalMessage */
/* @var $reply common\models\PortalReply */
/* @var $isNewReply bool */

$this->title = $isNewReply ? '添加管理员回复' : '修改管理员回复';
$this->params['breadcrumbs'][] = ['label' => '留言管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '留言 #' . $message->id, 'url' => ['view', 'id' => $message->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-guestbook-reply">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <div class="panel-heading">
            留言人：<?= Html::encode($message->display_name) ?>
        </div>
        <div class="panel-body">
            <p><?= nl2br(Html::encode($message->content)) ?></p>
        </div>
    </div>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($reply, 'content')->textarea(['rows' => 8]) ?>
    <div class="form-group">
        <?= Html::submitButton($isNewReply ? '创建回复' : '保存回复', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('取消', ['view', 'id' => $message->id], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
