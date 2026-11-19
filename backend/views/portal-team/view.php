<?php

/**
 * 模块 A 团队资料后台详情页。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PortalTeam */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '团队资料管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-team-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('管理成员', ['/portal-member/index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('返回', ['index'], ['class' => 'btn btn-default']) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            ['attribute' => 'description', 'format' => 'ntext'],
            'logo_path',
            ['attribute' => 'created_at', 'format' => ['datetime']],
            ['attribute' => 'updated_at', 'format' => ['datetime']],
        ],
    ]) ?>
</div>
