<?php

/**
 * 比赛详情及时间顺序排列的进球。
 * @author lzs
 * @date 2026-11-20
 */

use yii\helpers\Html;

$this->title = $match->fixtureLabel;
$this->params['breadcrumbs'][] = ['label' => '赛程与比分', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$goals = $match->orderedGoals;
?>
<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('/football/_nav') ?>

<dl>
    <dt>比分</dt>
    <dd><?= Html::encode($match->scoreLabel) ?></dd>
    <dt>比赛阶段</dt>
    <dd><?= Html::encode($match->stage) ?></dd>
    <dt>状态</dt>
    <dd><?= Html::encode($match->statusLabel) ?></dd>
    <dt>开球时间（北京时间）</dt>
    <dd><?= Html::encode($match->shanghaiKickoff) ?></dd>
    <dt>场馆</dt>
    <dd><?= Html::encode($match->stadium === null ? '暂无场馆资料' : $match->stadium->city . ' · ' . $match->stadium->name) ?></dd>
    <?php if ($match->result_note): ?>
        <dt>赛果说明</dt>
        <dd><?= Html::encode($match->result_note) ?></dd>
    <?php endif; ?>
    <?php if ($match->source_url): ?>
        <dt>比赛来源</dt>
        <dd><?= Html::a('查看来源', $match->source_url, ['rel' => 'noopener noreferrer']) ?></dd>
    <?php endif; ?>
</dl>
<h2>进球记录</h2>
<?php if ($goals === []): ?>
    <p>暂无进球记录。</p>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr><th>分钟</th><th>球员</th><th>记分归属</th><th>类型</th><th>来源</th></tr>
        </thead>
        <tbody>
            <?php foreach ($goals as $goal): ?>
                <tr>
                    <td><?= Html::encode($goal->minute_text ?? '—') ?></td>
                    <td><?= Html::encode($goal->scorer_name) ?></td>
                    <td><?= Html::encode($goal->team->name) ?></td>
                    <td><?= Html::encode($goal->typeLabel) ?></td>
                    <td><?= $goal->source_url ? Html::a('查看来源', $goal->source_url, ['rel' => 'noopener noreferrer']) : '—' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<p><?= Html::a('返回赛程', ['index']) ?></p>
