<?php

/**
 * 模块 A 课程资料后台修改页。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PortalAssignmentForm */
/* @var $assignment common\models\PortalAssignment */

$this->title = '修改课程资料：' . $assignment->title;
$this->params['breadcrumbs'][] = ['label' => '课程资料管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $assignment->title, 'url' => ['view', 'id' => $assignment->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="portal-assignment-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
