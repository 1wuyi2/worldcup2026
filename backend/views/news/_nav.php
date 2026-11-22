<?php

/**
 * 模块 B 后台内部导航。
 * @author XPC
 * @date 2026-11-22
 */

use yii\helpers\Html;
?>
<p class="btn-group">
    <?= Html::a('文章管理', ['/news/index'], ['class' => 'btn btn-default']) ?>
    <?= Html::a('分类管理', ['/news-category/index'], ['class' => 'btn btn-default']) ?>
    <?= Html::a('标签管理', ['/news-tag/index'], ['class' => 'btn btn-default']) ?>
</p>
