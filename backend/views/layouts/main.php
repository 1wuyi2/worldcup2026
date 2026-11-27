<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

/* @var $this yii\web\View */
/* @var $content string */

AppAsset::register($this);

$this->registerCssFile('@web/css/admin.css', [
    'depends' => [AppAsset::className()],
]);

$this->registerJsFile('@web/js/admin.js', [
    'depends' => [AppAsset::className()],
]);

$isGuest = Yii::$app->user->isGuest;

$currentUser = $isGuest
    ? null
    : Yii::$app->user->identity;

$currentController = Yii::$app->controller->id;
$currentAction = Yii::$app->controller->action->id;

$frontendUrl = Yii::$app->params['frontendBaseUrl'];

// 后台模块与实际 RBAC 权限的对应关系。
$groups = [
    [
        'label' => '团队门户',
        'permission' => 'managePortal',
        'items' => [
            [
                'label' => '团队资料',
                'controller' => 'portal-team',
                'icon' => 'building',
            ],
            [
                'label' => '成员管理',
                'controller' => 'portal-member',
                'icon' => 'users',
            ],
            [
                'label' => '课程作业',
                'controller' => 'portal-assignment',
                'icon' => 'folder',
            ],
            [
                'label' => '留言管理',
                'controller' => 'portal-guestbook',
                'icon' => 'message',
            ],
        ],
    ],
    [
        'label' => '新闻资讯',
        'permission' => 'manageNews',
        'items' => [
            [
                'label' => '新闻文章',
                'controller' => 'news',
                'icon' => 'news',
            ],
            [
                'label' => '新闻分类',
                'controller' => 'news-category',
                'icon' => 'folder',
            ],
            [
                'label' => '新闻标签',
                'controller' => 'news-tag',
                'icon' => 'tag',
            ],
        ],
    ],
    [
        'label' => '足球数据',
        'permission' => 'manageFootball',
        'items' => [
            [
                'label' => '比赛管理',
                'controller' => 'match',
                'icon' => 'calendar',
            ],
            [
                'label' => '球队管理',
                'controller' => 'football-team',
                'icon' => 'flag',
            ],
            [
                'label' => '球员管理',
                'controller' => 'football-player',
                'icon' => 'users',
            ],
            [
                'label' => '场馆管理',
                'controller' => 'stadium',
                'icon' => 'stadium',
            ],
            [
                'label' => '进球记录',
                'controller' => 'match-goal',
                'icon' => 'ball',
            ],
        ],
    ],
];

// 本地 SVG 图标，不依赖外部图标库。
$icons = [
    'dashboard' =>
        '<rect x="3" y="3" width="7" height="7" rx="1"/>
         <rect x="14" y="3" width="7" height="7" rx="1"/>
         <rect x="3" y="14" width="7" height="7" rx="1"/>
         <rect x="14" y="14" width="7" height="7" rx="1"/>',

    'building' =>
        '<path d="M4 21V5h12v16M16 10h4v11M2 21h20"/>
         <path d="M8 9h4M8 13h4M8 17h4"/>',

    'users' =>
        '<circle cx="9" cy="8" r="3"/>
         <path d="M3 21v-3a6 6 0 0 1 12 0v3"/>
         <path d="M16 5a3 3 0 0 1 0 6M21 21v-3a5 5 0 0 0-4-5"/>',

    'folder' =>
        '<path d="M3 6h7l2 3h9v11H3Z"/>',

    'message' =>
        '<path d="M3 4h18v13H9l-6 4Z"/>
         <path d="M7 9h10M7 13h7"/>',

    'news' =>
        '<rect x="3" y="4" width="18" height="16" rx="2"/>
         <rect x="6" y="7" width="5" height="5"/>
         <path d="M14 7h4M14 11h4M6 15h12"/>',

    'tag' =>
        '<path d="M3 3h8l10 10-8 8L3 11Z"/>
         <circle cx="7.5" cy="7.5" r="1"/>',

    'calendar' =>
        '<rect x="3" y="5" width="18" height="16" rx="2"/>
         <path d="M7 3v4M17 3v4M3 10h18M7 14h3M14 14h3"/>',

    'flag' =>
        '<path d="M5 21V3M5 4h15l-3 5 3 5H5"/>',

    'stadium' =>
        '<ellipse cx="12" cy="9" rx="9" ry="5"/>
         <path d="M3 9v6c0 3 4 5 9 5s9-2 9-5V9"/>
         <ellipse cx="12" cy="9" rx="4" ry="2"/>',

    'ball' =>
        '<circle cx="12" cy="12" r="9"/>
         <path d="m12 8 4 3-2 5h-4l-2-5Z"/>
         <path d="M12 8V3M16 11l5-2M14 16l3 4M10 16l-3 4M8 11 3 9"/>',

    'external' =>
        '<path d="M13 5h6v6M19 5 10 14"/>
         <path d="M19 14v5H5V5h5"/>',

    'shield' =>
        '<path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6Z"/>
         <path d="m8 12 3 3 5-6"/>',

    'logout' =>
        '<path d="M10 4H4v16h6M10 12h11m-4-4 4 4-4 4"/>',

    'menu' =>
        '<path d="M4 6h16M4 12h16M4 18h16"/>',
];

$icon = static function ($name) use ($icons) {
    return '<svg class="admin-icon"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.6"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 aria-hidden="true">'
        . ($icons[$name] ?? '')
        . '</svg>';
};

$isDashboard = $currentController === 'site'
    && $currentAction === 'index';

$currentModule = '管理后台';

$currentSection = $isDashboard
    ? '管理工作台'
    : '';

// 只保留当前用户实际拥有管理权限的模块。
$visibleGroups = [];

foreach ($groups as $group) {
    if (
        $isGuest
        || !Yii::$app->user->can($group['permission'])
    ) {
        continue;
    }

    $visibleGroups[] = $group;

    foreach ($group['items'] as $item) {
        if ($currentController === $item['controller']) {
            $currentModule = $group['label'];
            $currentSection = $item['label'];
        }
    }
}

?>

<?php $this->beginPage() ?>

<!DOCTYPE html>

<html lang="zh-CN">

<head>

    <meta charset="<?= Yii::$app->charset ?>">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <?php $this->registerCsrfMetaTags() ?>

    <title>
        <?= Html::encode($this->title ?: '管理后台') ?>
        | WorldCup2026
    </title>

    <?php $this->head() ?>

</head>

<body class="admin-body">

<?php $this->beginBody() ?>

<div class="admin-shell">


    <!-- =====================================
         左侧公共导航
         ===================================== -->

    <aside class="admin-sidebar"
           id="admin-sidebar">

        <a class="admin-brand"
           href="<?= Html::encode(Yii::$app->homeUrl) ?>">

            <span class="admin-brand__mark">
                26
            </span>

            <span class="admin-brand__text">

                <strong>WORLD CUP</strong>

                <small>管理系统</small>

            </span>

        </a>


        <nav class="admin-navigation"
             aria-label="后台管理导航">

            <?php if (!$isGuest): ?>

                <!-- 管理工作台 -->

                <?= Html::a(
                    $icon('dashboard')
                    . '<span>管理工作台</span>',
                    ['/site/index'],
                    [
                        'class' => 'admin-nav-link'
                            . ($isDashboard ? ' is-active' : ''),
                        'aria-current' => $isDashboard
                            ? 'page'
                            : null,
                    ]
                ) ?>


                <!-- ABC 业务模块 -->

                <?php foreach ($visibleGroups as $group): ?>

                    <div class="admin-nav-group">

                        <h2 class="admin-nav-group__title">

                            <?= Html::encode($group['label']) ?>

                        </h2>

                        <?php foreach ($group['items'] as $item): ?>

                            <?php
                            $isActive = $currentController
                                === $item['controller'];
                            ?>

                            <?= Html::a(
                                $icon($item['icon'])
                                . '<span>'
                                . Html::encode($item['label'])
                                . '</span>',
                                [
                                    '/' . $item['controller'] . '/index',
                                ],
                                [
                                    'class' => 'admin-nav-link'
                                        . ($isActive
                                            ? ' is-active'
                                            : ''),
                                    'aria-current' => $isActive
                                        ? 'location'
                                        : null,
                                ]
                            ) ?>

                        <?php endforeach; ?>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <?= Html::a(
                    '后台登录',
                    ['/site/login'],
                    ['class' => 'admin-nav-link']
                ) ?>

            <?php endif; ?>

        </nav>


        <!-- 侧边栏底部 -->

        <div class="admin-sidebar__footer">

            <?= Html::a(
                $icon('external')
                . '<span>返回前台</span>',
                $frontendUrl,
                ['class' => 'admin-nav-link']
            ) ?>

        </div>

    </aside>


    <!-- =====================================
         右侧工作区
         ===================================== -->

    <div class="admin-workspace">

        <!-- 顶部工具栏 -->

        <header class="admin-topbar">

            <div class="admin-topbar__left">

                <button type="button"
                        class="admin-menu-toggle"
                        data-admin-menu-toggle
                        aria-label="展开后台导航"
                        aria-expanded="false"
                        aria-controls="admin-sidebar"
                        hidden>

                    <?= $icon('menu') ?>

                </button>

                <div class="admin-location">

                    <span>

                        <?= Html::encode($currentModule) ?>

                    </span>

                    <?php if ($currentSection !== ''): ?>

                        <span class="admin-location__separator">
                            /
                        </span>

                        <strong>

                            <?= Html::encode($currentSection) ?>

                        </strong>

                    <?php endif; ?>

                </div>

            </div>


            <div class="admin-topbar__right">

                <?php if (!$isGuest): ?>

                    <div class="admin-account">

                        <span class="admin-account__avatar">

                            <?= $icon('shield') ?>

                        </span>

                        <span class="admin-account__name">

                            <?= Html::encode($currentUser->username) ?>

                        </span>

                    </div>

                    <div class="admin-account__divider"></div>

                    <?= Html::beginForm(
                        ['/site/logout'],
                        'post',
                        ['class' => 'admin-logout-form']
                    ) ?>

                        <?= Html::submitButton(
                            $icon('logout')
                            . '<span>退出登录</span>',
                            [
                                'class' => 'admin-logout',
                                'title' => '退出登录',
                            ]
                        ) ?>

                    <?= Html::endForm() ?>

                <?php else: ?>

                    <?= Html::a(
                        '登录',
                        ['/site/login']
                    ) ?>

                <?php endif; ?>

            </div>

        </header>


        <!-- 页面内容 -->

        <main class="admin-main"
              id="admin-main">

            <?php if (!empty($this->params['breadcrumbs'])): ?>

                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => '管理工作台',
                        'url' => ['/site/index'],
                    ],
                    'links' => $this->params['breadcrumbs'],
                ]) ?>

            <?php endif; ?>

            <?= Alert::widget() ?>

            <?= $content ?>

        </main>


        <!-- 公共页脚 -->

        <footer class="admin-footer">

            <span>© 2026 WorldCup2026</span>

            <span>世界杯内容与数据管理平台</span>

        </footer>

    </div>

</div>

<?php $this->endBody() ?>

</body>

</html>

<?php $this->endPage() ?>