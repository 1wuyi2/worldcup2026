<?php

/**
 * 模块 A 前台课程资料中心页面。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $teamAssignments common\models\PortalAssignment[] */
/* @var $personalAssignments common\models\PortalAssignment[] */

$this->title = '课程资料中心';
$this->params['breadcrumbs'][] = $this->title;

$renderAssignments = function ($assignments, $emptyText) {
    if (empty($assignments)) {
        return Html::tag('div', Html::encode($emptyText), ['class' => 'alert alert-info']);
    }

    $items = [];
    foreach ($assignments as $assignment) {
        $owner = $assignment->owner_label === null || $assignment->owner_label === ''
            ? '未注明'
            : $assignment->owner_label;
        $description = $assignment->description === null || $assignment->description === ''
            ? '暂无说明。'
            : $assignment->description;

        $items[] = Html::tag('div',
            Html::tag('h3', Html::encode($assignment->title), ['class' => 'panel-title'])
            . Html::tag('p', '所属：' . Html::encode($owner), ['class' => 'text-muted', 'style' => 'margin-top: 10px;'])
            . Html::tag('p', nl2br(Html::encode($description)))
            . Html::a('下载资料', ['download', 'id' => $assignment->id], ['class' => 'btn btn-primary']),
            ['class' => 'panel-body']
        );
    }

    return Html::tag('div', implode('', $items), ['class' => 'panel panel-default']);
};
?>
<div class="assignment-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="lead">这里提供已发布的团队作业和个人作业，下载由系统统一校验。</p>

    <h2>团队作业</h2>
    <?= $renderAssignments($teamAssignments, '团队作业正在整理中，暂无可下载资料。') ?>

    <h2>个人作业</h2>
    <?= $renderAssignments($personalAssignments, '个人作业正在整理中，暂无可下载资料。') ?>
</div>
