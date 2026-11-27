<?php

/**
 * WorldCup2026 前台注册页面。
 *
 * @author wuyi
 * @date 2026-11-27
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\SignupForm */

$this->title = '注册';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

$this->registerCssFile('@web/css/auth.css', [
    'depends' => [AppAsset::className()],
]);

$this->registerJsFile('@web/js/auth.js', [
    'depends' => [AppAsset::className()],
]);

?>

<div class="auth-page auth-page--signup">

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
                'mode' => 'signup',
            ]) ?>


            <!-- 右侧注册表单 -->

            <section class="auth-panel"
                     aria-labelledby="auth-signup-title">

                <div class="auth-panel__inner">

                    <!-- 表单标题 -->

                    <div class="auth-panel__heading">

                        <span class="auth-panel__eyebrow">
                            ACCOUNT / 02
                        </span>

                        <h1 id="auth-signup-title"
                            class="auth-panel__title">

                            创建账号<span>。</span>

                        </h1>

                        <p class="auth-panel__subtitle">
                            开启你的世界杯探索之旅。
                        </p>

                    </div>


                    <?php $form = ActiveForm::begin([
                        'id' => 'form-signup',
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


                        <!-- 邮箱 -->

                        <?= $form->field($model, 'email', [
                            'template' =>
                                "{label}\n"
                                . "<div class=\"auth-input auth-input--mail\">"
                                . "{input}</div>\n{error}",
                        ])->input('email', [
                            'placeholder' => '请输入邮箱地址',
                            'autocomplete' => 'email',
                        ])->label('电子邮箱') ?>


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
                                           aria-controls="signupform-password"
                                           hidden>
                                        <span class="auth-password-toggle__icon"
                                              aria-hidden="true"></span>
                                    </button>'
                                . "</div>\n{hint}\n{error}",
                        ])->passwordInput([
                            'id' => 'signupform-password',
                            'placeholder' => '请设置密码',
                            'autocomplete' => 'new-password',
                        ])->label('密码')->hint(
                            '密码至少6个字符，且不超过72字节。'
                        ) ?>


                        <!-- 注册按钮 -->

                        <div class="auth-form__submit">

                            <?= Html::submitButton(
                                '<span>创建账号</span>'
                                . '<span class="auth-submit__arrow"
                                         aria-hidden="true">→</span>',
                                [
                                    'class' => 'auth-submit',
                                    'name' => 'signup-button',
                                ]
                            ) ?>

                        </div>

                    <?php ActiveForm::end(); ?>


                    <!-- 登录入口 -->

                    <div class="auth-panel__switch">

                        <span>已经拥有账号？</span>

                        <?= Html::a(
                            '立即登录 <span aria-hidden="true">↗</span>',
                            ['/site/login'],
                            [
                                'class' => 'auth-switch-link',
                            ]
                        ) ?>

                    </div>


                    <!-- 页面底部标识 -->

                    <div class="auth-panel__footer">

                        <span>WORLD CUP 2026</span>

                        <span>SIGN UP</span>

                    </div>

                </div>

            </section>

        </div>

    </div>

</div>