<?php

/**
 * 模块 A 团队成员后台创建页。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PortalMember */

$this->title = '新增团队成员';
$this->params['breadcrumbs'][] = ['label' => '团队成员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-member-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
