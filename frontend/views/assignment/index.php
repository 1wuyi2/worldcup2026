<?php

/**
 * 模块 A 前台课程资料中心页面。
 *
 * @author wuyi
 * @date 2026-11-20
 */

use common\models\PortalAssignment;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $teamAssignments common\models\PortalAssignment[] */
/* @var $personalAssignments common\models\PortalAssignment[] */
/* @var $category string */
/* @var $memberId string|int */
/* @var $memberOptions array */

$this->title = '课程资料中心';
$this->params['breadcrumbs'][] = $this->title;

$renderAssignments = function ($assignments, $emptyText) {
    if (empty($assignments)) {
        return Html::tag(
            'div',
            Html::encode($emptyText),
            ['class' => 'alert alert-info']
        );
    }

    $items = [];

    foreach ($assignments as $assignment) {

        // 个人作业的所属成员可以直接访问个人主页。
        if (
            $assignment->category === PortalAssignment::CATEGORY_PERSONAL
            && $assignment->member !== null
        ) {
            $ownerHtml = Html::a(
                Html::encode($assignment->member->name),
                [
                    '/team/member',
                    'id' => $assignment->member->id,
                ]
            );
        } else {
            $owner = $assignment->owner_label === null
                || $assignment->owner_label === ''
                ? '团队'
                : $assignment->owner_label;

            $ownerHtml = Html::encode($owner);
        }

        $description = $assignment->description === null
            || $assignment->description === ''
            ? '暂无说明。'
            : $assignment->description;

        $items[] = Html::tag(
            'div',

            Html::tag(
                'h3',
                Html::encode($assignment->title),
                ['class' => 'panel-title']
            )

            . Html::tag(
                'p',
                '所属：' . $ownerHtml,
                [
                    'class' => 'text-muted',
                    'style' => 'margin-top: 10px;',
                ]
            )

            . Html::tag(
                'p',
                nl2br(Html::encode($description))
            )

            . Html::a(
                '下载资料',
                [
                    'download',
                    'id' => $assignment->id,
                ],
                ['class' => 'btn btn-primary']
            ),

            ['class' => 'panel-body']
        );
    }

    return Html::tag(
        'div',
        implode('', $items),
        ['class' => 'panel panel-default']
    );
};

$this->registerJs(<<<'JS'
var assignmentCategory = $('#public-assignment-category');
var assignmentMember = $('#public-assignment-member');
var assignmentForm = $('#public-assignment-filter-form');

assignmentCategory.on('change', function () {
    if ($(this).val() !== 'personal') {
        assignmentMember.val('');
    }

    assignmentForm.trigger('submit');
});

assignmentMember.on('change', function () {
    if ($(this).val() !== '') {
        assignmentCategory.val('personal');
    }

    assignmentForm.trigger('submit');
});
JS
);
?>

<div class="assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="lead">
        这里提供已发布的团队作业和个人作业，下载由系统统一校验。
    </p>

    <?= Html::beginForm(
    ['index'],
    'get',
    [
        'id' => 'public-assignment-filter-form',
        'class' => 'form-inline',
        'style' => 'margin-bottom: 20px;',
    ]
) ?>

    <div class="form-group">

        <?= Html::label(
            '作业分类',
            'public-assignment-category',
            ['class' => 'control-label']
        ) ?>

        <?= Html::dropDownList(
            'category',
            $category,
            PortalAssignment::getCategoryOptions(),
            [
                'id' => 'public-assignment-category',
                'prompt' => '全部分类',
                'class' => 'form-control',
            ]
        ) ?>

    </div>

    <div class="form-group">

        <?= Html::label(
            '个人成员',
            'public-assignment-member',
            ['class' => 'control-label']
        ) ?>

        <?= Html::dropDownList(
            'member_id',
            $memberId,
            $memberOptions,
            [
                'id' => 'public-assignment-member',
                'prompt' => '全部成员',
                'class' => 'form-control',
            ]
        ) ?>

    </div>

    <?= Html::submitButton(
        '筛选',
        ['class' => 'btn btn-primary']
    ) ?>

    <?= Html::a(
        '重置',
        ['index'],
        ['class' => 'btn btn-default']
    ) ?>

    <?= Html::endForm() ?>

    <?php if (
        $memberId !== ''
        && isset($memberOptions[$memberId])
    ): ?>

        <p>
            当前成员：
            <strong>
                <?= Html::encode($memberOptions[$memberId]) ?>
            </strong>

            <?= Html::a(
                '查看个人主页',
                [
                    '/team/member',
                    'id' => $memberId,
                ]
            ) ?>
        </p>

    <?php endif; ?>

    <?php if (
        $category === ''
        || $category === PortalAssignment::CATEGORY_TEAM
    ): ?>

        <h2>团队作业</h2>

        <?= $renderAssignments(
            $teamAssignments,
            '团队作业正在整理中，暂无可下载资料。'
        ) ?>

    <?php endif; ?>

    <?php if (
        $category === ''
        || $category === PortalAssignment::CATEGORY_PERSONAL
    ): ?>

        <h2>个人作业</h2>

        <?= $renderAssignments(
            $personalAssignments,
            '暂无符合条件的个人作业。'
        ) ?>

    <?php endif; ?>

</div>