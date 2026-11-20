<?php

/**
 * 模块 A 课程资料后台创建页。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PortalAssignmentForm */

$this->title = '新增课程资料';
$this->params['breadcrumbs'][] = ['label' => '课程资料管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-assignment-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
