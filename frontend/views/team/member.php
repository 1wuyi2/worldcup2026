<?php

/**
 * 模块 A 团队成员个人主页。
 *
 * @author wuyi
 * @date 2026-11-26
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $member common\models\PortalMember */
/* @var $team common\models\PortalTeam|null */
/* @var $avatarUrl string|null */
/* @var $assignments common\models\PortalAssignment[] */
/* @var $messages common\models\PortalMessage[] */

$this->title = $member->name . '的个人主页';

$this->params['breadcrumbs'][] = [
    'label' => '团队门户',
    'url' => ['index'],
];

$this->params['breadcrumbs'][] = $member->name;
?>

<div class="team-member">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(
            '返回团队介绍',
            ['index'],
            ['class' => 'btn btn-default']
        ) ?>
    </p>

    <!-- 一、个人基本资料 -->
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">

                <div class="col-sm-3 text-center">

                    <?php if ($avatarUrl !== null): ?>

                        <?= Html::img($avatarUrl, [
                            'alt' => $member->name . ' 头像',
                            'class' => 'img-responsive img-circle center-block',
                            'style' => 'width: 160px; height: 160px; object-fit: cover;',
                        ]) ?>

                    <?php else: ?>

                        <div class="center-block text-center"
                             style="width: 160px;
                                    height: 160px;
                                    line-height: 160px;
                                    border: 1px solid #ddd;
                                    border-radius: 50%;
                                    font-size: 48px;">

                            <?= Html::encode(
                                mb_substr(
                                    $member->name,
                                    0,
                                    1,
                                    'UTF-8'
                                )
                            ) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="col-sm-9">

                    <h2><?= Html::encode($member->name) ?></h2>

                    <dl class="dl-horizontal">

                        <dt>所属团队</dt>
                        <dd>
                            <?= $team !== null
                                ? Html::encode($team->name)
                                : '暂无团队资料' ?>
                        </dd>

                        <dt>学号</dt>
                        <dd>
                            <?= $member->student_no
                                ? Html::encode($member->student_no)
                                : '未提供' ?>
                        </dd>

                        <dt>项目职责</dt>
                        <dd>
                            <?= $member->role
                                ? Html::encode($member->role)
                                : '暂无职责说明' ?>
                        </dd>

                    </dl>

                    <h3>个人介绍</h3>

                    <?php if (
                        $member->bio !== null
                        && $member->bio !== ''
                    ): ?>

                        <p>
                            <?= nl2br(
                                Html::encode($member->bio)
                            ) ?>
                        </p>

                    <?php else: ?>

                        <p class="text-muted">暂无个人介绍。</p>

                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>

    <!-- 二、个人课程作业 -->
    <section class="member-assignments">

        <h2>个人课程作业</h2>

        <p class="text-muted">
            已发布作业：<?= count($assignments) ?> 份
        </p>

        <?php if (empty($assignments)): ?>

            <div class="alert alert-info">
                该成员暂未发布个人课程作业。
            </div>

        <?php else: ?>

            <?php foreach ($assignments as $assignment): ?>

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?= Html::encode($assignment->title) ?>
                        </h3>
                    </div>

                    <div class="panel-body">

                        <?php if (
                            $assignment->description !== null
                            && $assignment->description !== ''
                        ): ?>

                            <p>
                                <?= nl2br(
                                    Html::encode(
                                        $assignment->description
                                    )
                                ) ?>
                            </p>

                        <?php else: ?>

                            <p class="text-muted">暂无作业说明。</p>

                        <?php endif; ?>

                        <?= Html::a(
                            '下载作业',
                            [
                                '/assignment/download',
                                'id' => $assignment->id,
                            ],
                            ['class' => 'btn btn-primary']
                        ) ?>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

        <p>
            <?= Html::a(
                '前往课程作业中心',
                [
                    '/assignment/index',
                    'category' => 'personal',
                    'member_id' => $member->id,
                ],
                ['class' => 'btn btn-default']
            ) ?>
        </p>

    </section>

    <!-- 三、成员公开留言 -->
    <section class="member-messages">

        <h2>个人留言</h2>

        <p class="text-muted">
            已发布留言：<?= count($messages) ?> 条
        </p>

        <?php if (empty($messages)): ?>

            <div class="alert alert-info">
                该成员暂未发布个人留言。
            </div>

        <?php else: ?>

            <?php foreach ($messages as $message): ?>

                <div class="panel panel-default">

                    <div class="panel-heading">

                        <strong>
                            <?= Html::encode($member->name) ?>
                        </strong>

                        <span class="text-muted pull-right">
                            <?= Html::encode(
                                Yii::$app->formatter->asDatetime(
                                    $message->created_at
                                )
                            ) ?>
                        </span>

                    </div>

                    <div class="panel-body">

                        <p>
                            <?= nl2br(
                                Html::encode($message->content)
                            ) ?>
                        </p>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

        <p>
            <?= Html::a(
                '前往留言板',
                [
                    '/guestbook/index',
                    'identity' => 'member',
                    'member_id' => $member->id,
                ],
                ['class' => 'btn btn-default']
            ) ?>
        </p>

    </section>

</div>