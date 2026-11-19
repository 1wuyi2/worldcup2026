<?php

/**
 * 模块 A 团队资料后台修改页。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PortalTeam */

$this->title = '修改团队资料：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '团队资料管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="portal-team-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
