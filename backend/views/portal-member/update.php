<?php

/**
 * 模块 A 团队成员后台修改页。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PortalMember */

$this->title = '修改成员：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '团队成员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="portal-member-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
