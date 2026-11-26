<?php

/**
 * 模块 A 后台修改主动发布留言页面。
 *
 * @author wuyi
 * @date 2026-11-26
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PortalMessageForm */
/* @var $message common\models\PortalMessage */
/* @var $memberOptions array */

$this->title = '修改留言 #' . $message->id;
$this->params['breadcrumbs'][] = ['label' => '留言管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '留言 #' . $message->id, 'url' => ['view', 'id' => $message->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="portal-guestbook-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model, 'memberOptions' => $memberOptions]) ?>
</div>
