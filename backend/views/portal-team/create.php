<?php

/**
 * 模块 A 团队资料后台创建页。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PortalTeam */

$this->title = '初始化团队资料';
$this->params['breadcrumbs'][] = ['label' => '团队资料管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-team-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
