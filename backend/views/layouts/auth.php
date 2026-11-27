<?php

/**
 * WorldCup2026 后台独立认证布局。
 *
 * @author wuyi
 * @date 2026-11-27
 */

use backend\assets\AppAsset;
use yii\helpers\Html;
use common\widgets\Alert;

/* @var $this yii\web\View */
/* @var $content string */

AppAsset::register($this);

$this->registerCssFile('@web/css/auth.css', [
    'depends' => [AppAsset::className()],
]);

$this->registerJsFile('@web/js/auth.js', [
    'depends' => [AppAsset::className()],
]);

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

    <title><?= Html::encode($this->title) ?> | WorldCup2026</title>

    <?php $this->head() ?>

</head>

<body class="admin-auth-body">

<?php $this->beginBody() ?>

<main class="admin-auth-main">

    <?= Alert::widget() ?>

    <?= $content ?>

</main>

<?php $this->endBody() ?>

</body>

</html>

<?php $this->endPage() ?>