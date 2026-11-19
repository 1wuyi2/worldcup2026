<?php

/**
 * 模块 A 团队资料后台首页。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PortalTeam|null */

$this->title = '团队资料管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-team-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model === null): ?>
        <div class="alert alert-info">尚未创建团队资料。系统按单团队模式管理，请先初始化一条团队记录。</div>
        <p><?= Html::a('初始化团队资料', ['create'], ['class' => 'btn btn-success']) ?></p>
    <?php else: ?>
        <p>
            <?= Html::a('查看详情', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
            <?= Html::a('修改团队资料', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('管理成员', ['/portal-member/index'], ['class' => 'btn btn-info']) ?>
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
    <?php endif; ?>
</div>
