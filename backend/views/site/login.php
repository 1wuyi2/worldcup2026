<?php

/**
 * WorldCup2026 后台管理登录页面。
 *
 * @author wuyi
 * @date 2026-11-27
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BackendLoginForm */

$this->title = '管理后台登录';

$frontendUrl = Yii::$app->params['frontendBaseUrl'];

?>

<div class="admin-auth">

    <!-- ======================================
         左侧：管理系统品牌视觉
         ====================================== -->

    <aside class="admin-auth-visual">

        <div class="admin-auth-visual__inner">

            <!-- 品牌标识 -->

            <a class="admin-auth-brand"
               href="<?= Html::encode($frontendUrl) ?>">

                <span class="admin-auth-brand__mark">
                    26
                </span>

                <span class="admin-auth-brand__text">

                    <strong>WORLD CUP</strong>

                    <small>MANAGEMENT CONSOLE</small>

                </span>

            </a>


            <div class="admin-auth-visual__content">

                <!-- 品牌标题 -->

                <div class="admin-auth-visual__eyebrow">

                    <span></span>

                    ADMINISTRATION / 2026

                </div>

                <div class="admin-auth-visual__headline">

                    <span>CONTROL</span>

                    <span>THE PITCH.</span>

                </div>

                <p class="admin-auth-visual__description">

                    WorldCup2026 内容与数据管理平台。

                </p>


                <!-- 管理控制台示意图 -->

                <div class="admin-auth-graphic"
                     aria-hidden="true">

                    <svg viewBox="0 0 480 320"
                         xmlns="http://www.w3.org/2000/svg">

                        <defs>

                            <pattern id="admin-auth-grid"
                                     width="24"
                                     height="24"
                                     patternUnits="userSpaceOnUse">

                                <path d="M24 0H0V24"
                                      stroke="#263B49"
                                      stroke-width=".6"
                                      fill="none"/>

                            </pattern>

                        </defs>

                        <rect width="480"
                              height="320"
                              fill="url(#admin-auth-grid)"/>


                        <!-- 控制台外框 -->

                        <g fill="#131D2B"
                           stroke="#4A625D"
                           stroke-width="1.2">

                            <rect x="26" y="24"
                                  width="428" height="272"
                                  rx="4"/>

                            <rect x="26" y="24"
                                  width="428" height="32"
                                  rx="3"/>

                            <!-- 左侧菜单 -->

                            <rect x="38" y="70"
                                  width="106" height="212"/>

                            <!-- 三个数据区域 -->

                            <rect x="158" y="70"
                                  width="88" height="66"/>

                            <rect x="256" y="70"
                                  width="88" height="66"/>

                            <rect x="354" y="70"
                                  width="88" height="66"/>

                            <!-- 数据展示区域 -->

                            <rect x="158" y="150"
                                  width="284" height="132"/>

                        </g>


                        <!-- 窗口标识 -->

                        <g fill="#8296A6">

                            <circle cx="42" cy="40" r="3"/>

                            <circle cx="54" cy="40" r="3"/>

                            <circle cx="66" cy="40" r="3"/>

                        </g>


                        <!-- 三个模块 -->

                        <rect x="46" y="85"
                              width="90" height="36"
                              fill="#26392C"/>

                        <g font-family="Consolas, monospace"
                           font-size="14">

                            <text x="56" y="108"
                                  fill="#C7F36B">A</text>

                            <text x="56" y="152"
                                  fill="#91A1B6">B</text>

                            <text x="56" y="196"
                                  fill="#91A1B6">C</text>

                        </g>

                        <g stroke="#536D64">

                            <path d="M78 103H124"/>

                            <path d="M78 147H124"/>

                            <path d="M78 191H124"/>

                        </g>


                        <!-- 抽象数据指标 -->

                        <g fill="#C7F36B">

                            <rect x="174" y="96"
                                  width="32" height="6"/>

                            <rect x="272" y="96"
                                  width="32" height="6"/>

                            <rect x="370" y="96"
                                  width="32" height="6"/>

                        </g>

                        <g stroke="#536D64">

                            <path d="M174 115H229"/>

                            <path d="M272 115H327"/>

                            <path d="M370 115H425"/>

                        </g>


                        <!-- 抽象数据曲线 -->

                        <g stroke="#30434A"
                           stroke-width="1">

                            <path d="M174 177H426"/>

                            <path d="M174 204H426"/>

                            <path d="M174 231H426"/>

                            <path d="M174 258H426"/>

                        </g>

                        <path d="M174 252
                                 L211 224
                                 L253 234
                                 L295 191
                                 L336 209
                                 L379 181
                                 L426 166"

                              fill="none"
                              stroke="#5AD9CF"
                              stroke-width="2"/>

                        <circle class="admin-auth-graphic__pulse"
                                cx="379"
                                cy="181"
                                r="14"
                                stroke="#C7F36B"
                                fill="none"/>

                        <circle cx="379"
                                cy="181"
                                r="5"
                                fill="#C7F36B"/>

                        <text x="454" y="313"
                              text-anchor="end"
                              fill="#8296A6"
                              font-size="9"
                              font-family="Consolas, monospace">

                            FIG. 01 / CONSOLE SYSTEM

                        </text>

                    </svg>

                </div>


                <!-- 业务模块说明 -->

                <div class="admin-auth-modules">

                    <div class="admin-auth-module">

                        <span>A</span>

                        <strong>团队门户</strong>

                        <small>TEAM PORTAL</small>

                    </div>

                    <div class="admin-auth-module">

                        <span>B</span>

                        <strong>新闻资讯</strong>

                        <small>NEWSROOM</small>

                    </div>

                    <div class="admin-auth-module">

                        <span>C</span>

                        <strong>足球数据</strong>

                        <small>FOOTBALL DATA</small>

                    </div>

                </div>

            </div>


            <div class="admin-auth-visual__footer">

                <span>MIDNIGHT PITCH</span>

                <span>NKU / 2026</span>

            </div>

        </div>

    </aside>


    <!-- ======================================
         右侧：后台认证表单
         ====================================== -->

    <section class="admin-auth-panel"
             aria-labelledby="admin-auth-title">

        <header class="admin-auth-panel__top">

            <span>WC26 / ADMIN</span>

            <?= Html::a(
                '返回前台 <span aria-hidden="true">↗</span>',
                $frontendUrl,
                [
                    'class' => 'admin-auth-return',
                ]
            ) ?>

        </header>


        <div class="admin-auth-panel__content">

            <!-- 管理身份图标 -->

            <div class="admin-auth-shield"
                 aria-hidden="true">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.6"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6l-8-3Z"/>

                    <path d="m8.5 12 2.5 2.5 4.5-5"/>

                </svg>

            </div>


            <div class="admin-auth-panel__eyebrow">

                AUTHORIZED ACCESS

            </div>

            <h1 id="admin-auth-title">

                管理后台登录<span>。</span>

            </h1>

            <p class="admin-auth-panel__description">

                请使用具有管理权限的账号登录系统。

            </p>


            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => [
                    'class' => 'admin-auth-form',
                ],
            ]); ?>


                <!-- 用户名 -->

                <?= $form->field($model, 'username', [
                    'template' =>
                        "{label}\n"
                        . '<div class="admin-auth-input admin-auth-input--user">'
                        . "{input}</div>\n{error}",
                ])->textInput([
                    'placeholder' => '请输入管理账号',
                    'autocomplete' => 'username',
                    'autofocus' => true,
                ])->label('用户名') ?>


                <!-- 密码 -->

                <?= $form->field($model, 'password', [
                    'template' =>
                        "{label}\n"
                        . '<div class="admin-auth-input admin-auth-input--password">'
                        . '{input}'
                        . '<button type="button"
                                   class="admin-auth-password-toggle"
                                   data-password-toggle
                                   aria-controls="admin-login-password"
                                   aria-label="显示密码"
                                   aria-pressed="false"
                                   hidden>
                                <span class="admin-auth-eye"
                                      aria-hidden="true"></span>
                           </button>'
                        . "</div>\n{error}",
                ])->passwordInput([
                    'id' => 'admin-login-password',
                    'placeholder' => '请输入密码',
                    'autocomplete' => 'current-password',
                ])->label('密码') ?>


                <!-- 记住登录状态 -->

                <div class="admin-auth-options">

                    <?= $form->field(
                        $model,
                        'rememberMe'
                    )->checkbox([
                        'label' => '记住登录状态',
                    ]) ?>

                </div>


                <!-- 登录按钮 -->

                <?= Html::submitButton(
                    '<span>进入管理系统</span>'
                    . '<span class="admin-auth-submit__arrow"
                             aria-hidden="true">→</span>',
                    [
                        'class' => 'admin-auth-submit',
                        'name' => 'login-button',
                    ]
                ) ?>

            <?php ActiveForm::end(); ?>


            <!-- 管理权限说明 -->

            <div class="admin-auth-notice">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.6"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     aria-hidden="true">

                    <path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6l-8-3Z"/>

                    <path d="M12 8v5M12 16h.01"/>

                </svg>

                <p>

                    后台仅向具有相应业务管理权限的账号开放。

                </p>

            </div>

        </div>


        <footer class="admin-auth-panel__footer">

            <span>WORLD CUP 2026</span>

            <span>MANAGEMENT CONSOLE</span>

        </footer>

    </section>

</div>