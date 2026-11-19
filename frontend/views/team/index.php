<?php

/**
 * 模块 A 团队门户前台页面。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $team common\models\PortalTeam|null */
/* @var $members common\models\PortalMember[] */
/* @var $teamLogoUrl string|null */
/* @var $memberAvatarUrls array */

$this->title = '团队门户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($team === null): ?>
        <div class="alert alert-info">团队资料正在整理中，请稍后再来查看。</div>
    <?php else: ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <?php if ($teamLogoUrl !== null): ?>
                        <div class="col-sm-3 text-center">
                            <?= Html::img($teamLogoUrl, [
                                'alt' => $team->name . ' Logo',
                                'class' => 'img-responsive center-block',
                                'style' => 'max-height: 220px;',
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <div class="<?= $teamLogoUrl !== null ? 'col-sm-9' : 'col-sm-12' ?>">
                        <h2><?= Html::encode($team->name) ?></h2>
                        <?php if ($team->description !== null && $team->description !== ''): ?>
                            <p><?= nl2br(Html::encode($team->description)) ?></p>
                        <?php else: ?>
                            <p class="text-muted">暂无团队介绍。</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <h2>团队成员</h2>
        <?php if (empty($members)): ?>
            <div class="alert alert-info">成员资料正在整理中。</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($members as $member): ?>
                    <?php $avatarUrl = isset($memberAvatarUrls[(string) $member->id])
                        ? $memberAvatarUrls[(string) $member->id]
                        : null; ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php if ($avatarUrl !== null): ?>
                                    <?= Html::img($avatarUrl, [
                                        'alt' => $member->name . ' 头像',
                                        'class' => 'img-responsive img-circle center-block',
                                        'style' => 'width: 140px; height: 140px; object-fit: cover;',
                                    ]) ?>
                                <?php endif; ?>
                                <h3 class="text-center"><?= Html::encode($member->name) ?></h3>
                                <dl>
                                    <dt>学号</dt>
                                    <dd><?= $member->student_no !== null && $member->student_no !== ''
                                            ? Html::encode($member->student_no)
                                            : '<span class="text-muted">未提供</span>' ?></dd>
                                    <dt>职责</dt>
                                    <dd><?= $member->role !== null && $member->role !== ''
                                            ? Html::encode($member->role)
                                            : '<span class="text-muted">未提供</span>' ?></dd>
                                </dl>
                                <?php if ($member->bio !== null && $member->bio !== ''): ?>
                                    <p><?= nl2br(Html::encode($member->bio)) ?></p>
                                <?php else: ?>
                                    <p class="text-muted">暂无个人介绍。</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
