<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);

$currentRoute = Yii::$app->controller->route;
$isRouteActive = static function (array $routes) use ($currentRoute) {
    return in_array($currentRoute, $routes, true);
};
$isFootballActive = strpos($currentRoute, 'football/') === 0;
$isPortalActive = in_array($currentRoute, [
    'team/index',
    'assignment/index',
    'assignment/download',
    'guestbook/index',
], true);
$isFullBleed = !empty($this->params['fullBleed']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="midnight-pitch">
<?php $this->beginBody() ?>

<a class="skip-link" href="#main-content">跳到主要内容</a>

<div class="site-shell">
    <header class="site-header" data-site-header>
        <div class="wc-container site-header__inner">
            <?= Html::a(
                '<span class="site-brand__mark" aria-hidden="true">26</span>'
                . '<span class="site-brand__copy"><strong>WORLD CUP</strong><small>THE GLOBAL PITCH</small></span>',
                ['/site/index'],
                ['class' => 'site-brand', 'aria-label' => 'WorldCup2026 首页']
            ) ?>

            <button class="site-nav-toggle" type="button" aria-expanded="false"
                    aria-controls="primary-navigation" data-nav-toggle>
                <span class="sr-only">展开主导航</span>
                <span class="site-nav-toggle__line"></span>
                <span class="site-nav-toggle__line"></span>
                <span class="site-nav-toggle__line"></span>
            </button>

            <nav class="site-nav" id="primary-navigation" aria-label="主导航" data-site-nav>
                <ul class="site-nav__list">
                    <li class="site-nav__item<?= $isRouteActive(['site/index']) ? ' is-active' : '' ?>">
                        <?= Html::a('首页', ['/site/index'], ['class' => 'site-nav__link']) ?>
                    </li>
                    <li class="site-nav__item<?= strpos($currentRoute, 'match/') === 0 ? ' is-active' : '' ?>">
                        <?= Html::a('赛事中心', ['/match/index'], ['class' => 'site-nav__link']) ?>
                    </li>
                    <li class="site-nav__item site-nav__item--has-menu<?= $isFootballActive ? ' is-active' : '' ?>">
                        <button class="site-nav__link site-nav__dropdown-toggle" type="button"
                                aria-expanded="false" aria-controls="football-navigation" data-nav-dropdown>
                            探索世界杯 <span aria-hidden="true">⌄</span>
                        </button>
                        <ul class="site-subnav" id="football-navigation">
                            <li><?= Html::a('国家队', ['/football/teams']) ?></li>
                            <li><?= Html::a('球员', ['/football/players']) ?></li>
                            <li><?= Html::a('场馆', ['/football/stadiums']) ?></li>
                        </ul>
                    </li>
                    <li class="site-nav__item<?= strpos($currentRoute, 'news/') === 0 ? ' is-active' : '' ?>">
                        <?= Html::a('新闻资讯', ['/news/index'], ['class' => 'site-nav__link']) ?>
                    </li>
                    <li class="site-nav__item site-nav__item--has-menu<?= $isPortalActive ? ' is-active' : '' ?>">
                        <button class="site-nav__link site-nav__dropdown-toggle" type="button"
                                aria-expanded="false" aria-controls="portal-navigation" data-nav-dropdown>
                            关于我们 <span aria-hidden="true">⌄</span>
                        </button>
                        <ul class="site-subnav" id="portal-navigation">
                            <li><?= Html::a('团队介绍', ['/team/index']) ?></li>
                            <li><?= Html::a('课程资料', ['/assignment/index']) ?></li>
                            <li><?= Html::a('留言互动', ['/guestbook/index']) ?></li>
                        </ul>
                    </li>
                </ul>

                <div class="site-account" aria-label="账户导航">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?= Html::a('登录', ['/site/login'], ['class' => 'site-account__link']) ?>
                        <?= Html::a('注册', ['/site/signup'], ['class' => 'btn btn-primary btn-sm site-account__signup']) ?>
                    <?php else: ?>
                        <span class="site-account__name"><?= Html::encode(Yii::$app->user->identity->username) ?></span>
                        <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'site-account__form']) ?>
                        <?= Html::submitButton('退出', ['class' => 'site-account__logout']) ?>
                        <?= Html::endForm() ?>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="site-main<?= $isFullBleed ? ' site-main--flush' : '' ?>"
          id="main-content" tabindex="-1">
        <?php if ($isFullBleed): ?>
            <?php if (empty($this->params['hideBreadcrumbs'])): ?>
                <div class="wc-container">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'homeLink' => ['label' => '首页', 'url' => ['/site/index']],
                        'options' => ['class' => 'breadcrumb site-breadcrumb'],
                    ]) ?>
                </div>
            <?php endif; ?>
            <div class="wc-container site-main__feedback">
                <?= Alert::widget() ?>
            </div>
            <?= $content ?>
        <?php else: ?>
            <div class="wc-container">
                <?php if (empty($this->params['hideBreadcrumbs'])): ?>
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'homeLink' => ['label' => '首页', 'url' => ['/site/index']],
                        'options' => ['class' => 'breadcrumb site-breadcrumb'],
                    ]) ?>
                <?php endif; ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<footer class="site-footer">
    <div class="wc-container">
        <div class="site-footer__grid">
            <section class="site-footer__brand" aria-label="WorldCup2026 品牌介绍">
                <p class="site-footer__eyebrow">MIDNIGHT PITCH</p>
                <h2>WORLD CUP <span>2026</span></h2>
                <p>聚焦赛事、球队与场外故事的世界杯主题课程门户。</p>
            </section>
            <nav class="site-footer__links" aria-label="赛事内容">
                <h3>赛事内容</h3>
                <?= Html::a('赛程与比分', ['/match/index']) ?>
                <?= Html::a('国家队', ['/football/teams']) ?>
                <?= Html::a('新闻资讯', ['/news/index']) ?>
            </nav>
            <nav class="site-footer__links" aria-label="团队项目">
                <h3>团队项目</h3>
                <?= Html::a('团队介绍', ['/team/index']) ?>
                <?= Html::a('课程资料', ['/assignment/index']) ?>
                <?= Html::a('留言互动', ['/guestbook/index']) ?>
            </nav>
            <section class="site-footer__about">
                <h3>课程说明</h3>
                <p>大学《互联网数据库开发》三人团队课程设计，内容由团队独立整理与维护。</p>
            </section>
        </div>
        <div class="site-footer__bottom">
            <span>&copy; <?= date('Y') ?> WorldCup2026 Team</span>
            <span>Built with Yii2 &amp; Bootstrap</span>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
