<?php

/**
 * 模块 A 团队成员后台详情页。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PortalMember */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '团队成员管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portal-member-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定要删除该成员吗？',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('返回列表', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'team_id',
                'value' => $model->team !== null ? $model->team->name : '未知团队',
            ],
            'name',
            'student_no',
            'role',
            ['attribute' => 'bio', 'format' => 'ntext'],
            'avatar_path',
            'sort_order',
            ['attribute' => 'created_at', 'format' => ['datetime']],
            ['attribute' => 'updated_at', 'format' => ['datetime']],
        ],
    ]) ?>
</div>
