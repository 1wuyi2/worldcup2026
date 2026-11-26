<?php

/**
 * 模块 A 后台创建团队或个人留言页面。
 *
 * @author wuyi
 * @date 2026-11-26
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PortalMessageForm */
/* @var $memberOptions array */

$this->title = '发布新留言';
$this->params['breadcrumbs'][] = ['label' => '留言管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-guestbook-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model, 'memberOptions' => $memberOptions]) ?>
</div>
