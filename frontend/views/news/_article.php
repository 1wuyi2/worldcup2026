<?php

/**
 * 模块 B 新闻列表中的文章摘要与筛选入口。
 * @author XPC
 * @date 2026-11-22
 */

use yii\helpers\Html;

$summary = $model->summary ?: mb_substr($model->body, 0, 160, 'UTF-8');
?>
<article class="panel panel-default">
    <div class="panel-body">
        <h2><?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id]) ?></h2>
        <p>
            <?= Html::a(Html::encode($model->category->name), ['index', 'category_id' => $model->category_id]) ?>
            · <?= Html::encode($model->publishedAtLabel) ?>
        </p>
        <p><?= nl2br(Html::encode($summary)) ?></p>
        <p>
            <?php foreach ($model->tags as $tag): ?>
                <?= Html::a(Html::encode($tag->name), ['index', 'tag_id' => $tag->id], ['class' => 'label label-default']) ?>
            <?php endforeach; ?>
        </p>
    </div>
</article>
