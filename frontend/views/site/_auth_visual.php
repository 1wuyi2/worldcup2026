<?php

/**
 * WorldCup2026 用户认证页面共享视觉组件。
 *
 * @author wuyi
 * @date 2026-11-27
 */

/* @var $mode string */

$isSignup = $mode === 'signup';

?>

<aside class="auth-visual" aria-hidden="true">

    <!-- 顶部品牌标识 -->

    <div class="auth-visual__top">

        <span class="auth-visual__eyebrow">
            THE GLOBAL PITCH
        </span>

        <span class="auth-visual__edition">
            EST. 2026
        </span>

    </div>


    <!-- 主题图形 -->

    <div class="auth-visual__graphic">

        <?php if ($isSignup): ?>

            <!-- 注册页面：全球连接 -->

            <svg viewBox="0 0 480 380"
                 xmlns="http://www.w3.org/2000/svg">

                <!-- 背景参考线 -->

                <g fill="none"
                   stroke="#263D47"
                   stroke-width="0.8">

                    <path d="M20 190H460M240 15V365"/>

                    <rect x="20"
                          y="20"
                          width="440"
                          height="340"
                          stroke-dasharray="4 8"/>

                </g>


                <!-- 地球轮廓及经纬线 -->

                <g fill="none"
                   stroke="#547873"
                   stroke-width="1.2">

                    <circle cx="240" cy="190" r="150"/>

                    <ellipse cx="240" cy="190"
                             rx="70" ry="150"/>

                    <ellipse cx="240" cy="190"
                             rx="120" ry="150"/>

                    <ellipse cx="240" cy="190"
                             rx="150" ry="58"/>

                    <ellipse cx="240" cy="190"
                             rx="150" ry="112"/>

                    <path d="M90 190H390M240 40V340"/>

                </g>


                <!-- 全球连接路径 -->

                <g fill="none"
                   stroke="#5AD9CF"
                   stroke-width="1.5"
                   stroke-dasharray="5 6">

                    <path d="M143 125Q230 20 323 110"/>

                    <path d="M143 125Q110 240 269 290"/>

                    <path d="M323 110Q415 245 269 290"/>

                    <path d="M143 125Q290 110 269 290"/>

                </g>


                <!-- 动态连接节点 -->

                <?php foreach (
                    [[143, 125], [323, 110], [269, 290]]
                    as $index => $point
                ): ?>

                    <g transform="translate(<?= $point[0] ?>
                                            <?= $point[1] ?>)">

                        <circle
                            class="auth-visual__pulse
                                   auth-visual__pulse--<?= $index + 1 ?>"
                            r="18"
                            fill="none"
                            stroke="#C7F36B"
                            stroke-width="1.2"/>

                        <circle r="6" fill="#C7F36B"/>

                    </g>

                <?php endforeach; ?>


                <!-- 辅助图形标识 -->

                <g fill="#8296A6"
                   font-family="Consolas, monospace"
                   font-size="10"
                   letter-spacing="1">

                    <text x="20" y="12">
                        FIG. 02 / GLOBAL CONNECTION
                    </text>

                    <text x="460" y="375"
                          text-anchor="end">
                        CONNECTED BY FOOTBALL
                    </text>

                </g>

            </svg>

        <?php else: ?>

            <!-- 登录页面：足球战术板 -->

            <svg viewBox="0 0 480 380"
                 xmlns="http://www.w3.org/2000/svg">

                <defs>

                    <pattern id="auth-pitch-grid"
                             width="24"
                             height="24"
                             patternUnits="userSpaceOnUse">

                        <path d="M24 0H0V24"
                              stroke="#263D47"
                              stroke-width="0.7"
                              fill="none"/>

                    </pattern>

                </defs>


                <!-- 战术板背景网格 -->

                <rect x="20"
                      y="38"
                      width="440"
                      height="304"
                      fill="url(#auth-pitch-grid)"/>


                <!-- 足球场轮廓 -->

                <g stroke="#678C7C"
                   stroke-width="1.5"
                   fill="none">

                    <rect x="36"
                          y="60"
                          width="408"
                          height="260"/>

                    <!-- 中线 -->

                    <path d="M240 60V320"/>

                    <!-- 中圈 -->

                    <circle cx="240"
                            cy="190"
                            r="54"/>

                    <!-- 禁区 -->

                    <rect x="36"
                          y="112"
                          width="72"
                          height="156"/>

                    <rect x="372"
                          y="112"
                          width="72"
                          height="156"/>

                    <!-- 球门区 -->

                    <rect x="36"
                          y="150"
                          width="28"
                          height="80"/>

                    <rect x="416"
                          y="150"
                          width="28"
                          height="80"/>

                    <!-- 点球点 -->

                    <circle cx="86" cy="190" r="2"/>

                    <circle cx="394" cy="190" r="2"/>

                </g>


                <!-- 战术连接线路 -->

                <path d="M92 124
                         L165 258
                         L240 190
                         L357 110"

                      stroke="#5AD9CF"
                      stroke-width="1.8"
                      stroke-dasharray="6 7"
                      fill="none"/>


                <!-- 战术节点 -->

                <?php foreach (
                    [[92, 124], [165, 258], [357, 110]]
                    as $index => $point
                ): ?>

                    <g transform="translate(<?= $point[0] ?>
                                            <?= $point[1] ?>)">

                        <circle
                            class="auth-visual__pulse
                                   auth-visual__pulse--<?= $index + 1 ?>"
                            r="17"
                            fill="none"
                            stroke="#5AD9CF"
                            stroke-width="1.2"/>

                        <circle r="6" fill="#5AD9CF"/>

                    </g>

                <?php endforeach; ?>


                <!-- 中心强调节点 -->

                <g transform="translate(240 190)">

                    <circle
                        class="auth-visual__pulse"
                        r="25"
                        fill="none"
                        stroke="#C7F36B"
                        stroke-width="1.2"/>

                    <circle r="8" fill="#C7F36B"/>

                </g>


                <!-- 辅助图形标识 -->

                <g fill="#8296A6"
                   font-family="Consolas, monospace"
                   font-size="10"
                   letter-spacing="1">

                    <text x="20" y="22">
                        FIG. 01 / PITCH SYSTEM
                    </text>

                    <text x="460" y="22"
                          text-anchor="end">
                        WC / 26
                    </text>

                    <text x="460" y="365"
                          text-anchor="end">
                        TACTICAL VIEW
                    </text>

                </g>

            </svg>

        <?php endif; ?>

    </div>


    <!-- 品牌宣传文字 -->

    <div class="auth-visual__identity">

        <div class="auth-visual__kicker">

            <span></span>

            <?= $isSignup
                ? 'JOIN THE GLOBAL PITCH'
                : 'THE GLOBAL PITCH / 2026' ?>

        </div>

        <div class="auth-visual__headline">

            <?php if ($isSignup): ?>

                <span>THE WORLD</span>

                <span>IS OUR PITCH.</span>

            <?php else: ?>

                <span>ONE GAME.</span>

                <span>ONE WORLD.</span>

            <?php endif; ?>

        </div>

        <p class="auth-visual__description">

            <?= $isSignup
                ? '从这里开始，探索足球世界。'
                : '连接世界足球，探索每一场精彩。' ?>

        </p>

    </div>


    <!-- 底部辅助标识 -->

    <div class="auth-visual__footer">

        <span>WORLD CUP 2026</span>

        <span>MIDNIGHT PITCH</span>

    </div>

</aside>