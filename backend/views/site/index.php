<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $overview array */
/* @var $modules array */

$this->title = '管理工作台';

$icons = [
    'users' =>
        '<circle cx="9" cy="8" r="3"/>
         <path d="M3 21v-3a6 6 0 0 1 12 0v3"/>
         <path d="M16 5a3 3 0 0 1 0 6M21 21v-3a5 5 0 0 0-4-5"/>',

    'folder' =>
        '<path d="M3 6h7l2 3h9v11H3Z"/>',

    'news' =>
        '<rect x="3" y="4" width="18" height="16" rx="2"/>
         <rect x="6" y="7" width="5" height="5"/>
         <path d="M14 7h4M14 11h4M6 15h12"/>',

    'trophy' =>
        '<path d="M8 3h8v8a4 4 0 0 1-8 0Z"/>
         <path d="M8 5H4v3a4 4 0 0 0 4 4M16 5h4v3a4 4 0 0 1-4 4"/>
         <path d="M12 15v5M8 21h8"/>',

    'shield' =>
        '<path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6Z"/>
         <path d="m8 12 3 3 5-6"/>',

    'arrow' =>
        '<path d="M5 12h14m-6-6 6 6-6 6"/>',
];

$icon = static function ($name) use ($icons) {
    return '<svg viewBox="0 0 24 24"
                 class="admin-icon"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.6"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 aria-hidden="true">'
        . ($icons[$name] ?? '')
        . '</svg>';
};

?>

<div class="admin-dashboard">

    <!-- =====================================
         页面标题
         ===================================== -->

    <header class="admin-dashboard__heading">

        <div>

            <h1>管理工作台</h1>

            <p>查看项目数据概况，管理各业务模块。</p>

        </div>

        <div class="admin-dashboard__role">

            <?= $icon('shield') ?>

            <span>管理账号</span>

        </div>

    </header>


    <!-- =====================================
         数据概览
         ===================================== -->

    <?php if (!empty($overview)): ?>

        <section class="admin-dashboard__section"
                 aria-label="数据概览">

            <div class="admin-dashboard__section-heading">

                <h2>数据概览</h2>

                <span>全部管理记录</span>

            </div>

            <div class="admin-dashboard__overview">

                <?php foreach ($overview as $item): ?>

                    <a class="admin-stat-card"
                       href="<?= Html::encode(
                           \yii\helpers\Url::to($item['url'])
                       ) ?>">

                        <div class="admin-stat-card__top">

                            <span class="admin-stat-card__label">

                                <?= Html::encode($item['label']) ?>

                            </span>

                            <span class="admin-stat-card__icon">

                                <?= $icon($item['icon']) ?>

                            </span>

                        </div>

                        <strong class="admin-stat-card__value">

                            <?= number_format($item['value']) ?>

                        </strong>

                        <div class="admin-stat-card__footer">

                            <span>

                                <?= Html::encode($item['module']) ?>

                            </span>

                            <?= $icon('arrow') ?>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        </section>

    <?php endif; ?>


    <!-- =====================================
         业务模块管理
         ===================================== -->

    <section class="admin-dashboard__section"
             aria-label="业务模块">

        <div class="admin-dashboard__section-heading">

            <h2>业务模块</h2>

            <span>

                已授权 <?= count($modules) ?> 个模块

            </span>

        </div>

        <div class="admin-dashboard__modules">

            <?php foreach ($modules as $module): ?>

                <article class="admin-module-card">

                    <header class="admin-module-card__heading">

                        <span class="admin-module-card__code">

                            <?= Html::encode($module['code']) ?>

                        </span>

                        <div class="admin-module-card__identity">

                            <h3>

                                <?= Html::encode($module['title']) ?>

                            </h3>

                            <p>

                                <?= Html::encode(
                                    $module['description']
                                ) ?>

                            </p>

                        </div>

                        <?= Html::a(
                            '<span>进入模块</span>'
                            . $icon('arrow'),
                            $module['url'],
                            [
                                'class' => 'admin-module-card__enter',
                                'aria-label' =>
                                    '进入' . $module['title'],
                            ]
                        ) ?>

                    </header>


                    <div class="admin-module-card__items">

                        <?php foreach ($module['items'] as $item): ?>

                            <a class="admin-module-item"
                               href="<?= Html::encode(
                                   \yii\helpers\Url::to($item['url'])
                               ) ?>">

                                <span class="admin-module-item__label">

                                    <?= Html::encode($item['label']) ?>

                                </span>

                                <strong class="admin-module-item__count">

                                    <?= number_format($item['count']) ?>

                                </strong>

                                <span class="admin-module-item__action">

                                    查看记录

                                    <?= $icon('arrow') ?>

                                </span>

                            </a>

                        <?php endforeach; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </section>

    <?php if (empty($modules)): ?>

        <div class="alert alert-warning">

            当前账号没有可用的业务管理权限。

        </div>

    <?php endif; ?>

</div>