<?php

/**
 * WorldCup2026 前台登录页面。
 *
 * @author wuyi
 * @date 2026-11-27
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LoginForm */

$this->title = '登录';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/auth.css', [
    'depends' => [AppAsset::className()],
]);

$this->registerJsFile('@web/js/auth.js', [
    'depends' => [AppAsset::className()],
]);

?>

<div class="auth-page auth-page--login">

    <div class="wc-container">

        <!-- 页面顶部导航 -->

        <div class="auth-page__meta">

            <span>
                WORLD CUP 2026 / ACCOUNT
            </span>

            <?= Html::a(
                '返回首页 <span aria-hidden="true">↗</span>',
                ['/site/index'],
                [
                    'class' => 'auth-page__home-link',
                ]
            ) ?>

        </div>


        <!-- 认证双栏布局 -->

        <div class="auth-layout">

            <!-- 左侧共享品牌视觉 -->

            <?= $this->render('_auth_visual', [
                'mode' => 'login',
            ]) ?>


            <!-- 右侧登录表单 -->

            <section class="auth-panel"
                     aria-labelledby="auth-login-title">

                <div class="auth-panel__inner">

                    <!-- 表单标题 -->

                    <div class="auth-panel__heading">

                        <span class="auth-panel__eyebrow">
                            ACCOUNT / 01
                        </span>

                        <h1 id="auth-login-title"
                            class="auth-panel__title">

                            欢迎回来<span>。</span>

                        </h1>

                        <p class="auth-panel__subtitle">
                            登录你的 WorldCup2026 账号。
                        </p>

                    </div>


                    <!-- Yii ActiveForm -->

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'options' => [
                            'class' => 'auth-form',
                        ],
                    ]); ?>


                        <!-- 用户名 -->

                        <?= $form->field($model, 'username', [
                            'template' =>
                                "{label}\n"
                                . "<div class=\"auth-input auth-input--user\">"
                                . "{input}</div>\n{error}",
                        ])->textInput([
                            'placeholder' => '请输入用户名',
                            'autocomplete' => 'username',
                            'autofocus' => true,
                        ])->label('用户名') ?>


                        <!-- 密码 -->

                        <?= $form->field($model, 'password', [
                            'template' =>
                                "{label}\n"
                                . "<div class=\"auth-input auth-input--password\">"
                                . "{input}"
                                . '<button type="button"
                                           class="auth-password-toggle"
                                           data-password-toggle
                                           aria-label="显示密码"
                                           aria-pressed="false"
                                           aria-controls="loginform-password"
                                           hidden>
                                        <span class="auth-password-toggle__icon"
                                              aria-hidden="true"></span>
                                    </button>'
                                . "</div>\n{error}",
                        ])->passwordInput([
                            'id' => 'loginform-password',
                            'placeholder' => '请输入密码',
                            'autocomplete' => 'current-password',
                        ])->label('密码') ?>


                        <!-- 记住登录状态 -->

                        <div class="auth-form__options">

                            <?= $form->field(
                                $model,
                                'rememberMe'
                            )->checkbox([
                                'label' => '记住登录状态',
                                'class' => 'auth-remember',
                            ]) ?>

                        </div>


                        <!-- 登录按钮 -->

                        <div class="auth-form__submit">

                            <?= Html::submitButton(
                                '<span>登录</span>'
                                . '<span class="auth-submit__arrow"
                                         aria-hidden="true">→</span>',
                                [
                                    'class' => 'auth-submit',
                                    'name' => 'login-button',
                                ]
                            ) ?>

                        </div>

                    <?php ActiveForm::end(); ?>


                    <!-- 注册入口 -->

                    <div class="auth-panel__switch">

                        <span>还没有账号？</span>

                        <?= Html::a(
                            '立即注册 <span aria-hidden="true">↗</span>',
                            ['/site/signup'],
                            [
                                'class' => 'auth-switch-link',
                            ]
                        ) ?>

                    </div>


                    <!-- 页面底部标识 -->

                    <div class="auth-panel__footer">

                        <span>WORLD CUP 2026</span>

                        <span>SIGN IN</span>

                    </div>

                </div>

            </section>

        </div>

    </div>

</div>