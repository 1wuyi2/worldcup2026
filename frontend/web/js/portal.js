/**
 * WorldCup2026 团队门户动态交互。
 *
 * TEAM 404 / Interactive Identity
 *
 * 功能：
 * 1. 404 数字鼠标视差。
 * 2. 几何图形反向视差。
 * 3. 鼠标跟随环境光。
 * 4. 离屏动画暂停。
 * 5. 触屏与减少动态效果适配。
 */

(function () {
    'use strict';

    function initTeamHero() {

        const hero = document.querySelector(
            '.portal-team .tp-hero'
        );

        if (!hero) {
            return;
        }

        const interactionArea = hero.querySelector(
            '.tp-hero__main'
        );

        if (!interactionArea) {
            return;
        }


        /* ========================================
           01. 设备及动态效果设置
           ======================================== */

        const finePointer = window.matchMedia(
            '(hover: hover) and (pointer: fine)'
        );

        const reducedMotion = window.matchMedia(
            '(prefers-reduced-motion: reduce)'
        );


        /* ========================================
           02. 视差参数
           ======================================== */

        const settings = {

            // 左侧数字最大位移。
            numberMaxX: 12,
            numberMaxY: 8,

            // 右侧图形反向最大位移。
            graphicMaxX: 6,
            graphicMaxY: 4
        };


        let animationFrame = null;
        let latestPointer = null;

        let heroInViewport = true;


        function clamp(value, min, max) {
            return Math.max(
                min,
                Math.min(max, value)
            );
        }


        function canInteract() {
            return finePointer.matches
                && !reducedMotion.matches
                && heroInViewport
                && !document.hidden;
        }


        /* ========================================
           03. 更新鼠标视差
           ======================================== */

        function updatePointerEffect() {

            animationFrame = null;

            if (!latestPointer || !canInteract()) {
                return;
            }

            const areaRect =
                interactionArea.getBoundingClientRect();

            const heroRect =
                hero.getBoundingClientRect();

            if (
                areaRect.width <= 0
                || areaRect.height <= 0
                || heroRect.width <= 0
                || heroRect.height <= 0
            ) {
                return;
            }


            // 将鼠标位置归一化为 -1 到 1。
            const normalizedX = clamp(
                (
                    (
                        latestPointer.x - areaRect.left
                    ) / areaRect.width
                ) * 2 - 1,
                -1,
                1
            );

            const normalizedY = clamp(
                (
                    (
                        latestPointer.y - areaRect.top
                    ) / areaRect.height
                ) * 2 - 1,
                -1,
                1
            );


            // 左侧数字随鼠标方向轻微移动。
            const numberX =
                normalizedX * settings.numberMaxX;

            const numberY =
                normalizedY * settings.numberMaxY;


            // 右侧几何图形向相反方向移动。
            const graphicX =
                -normalizedX * settings.graphicMaxX;

            const graphicY =
                -normalizedY * settings.graphicMaxY;


            // 环境光跟随真实鼠标位置。
            const lightX = clamp(
                (
                    latestPointer.x - heroRect.left
                ) / heroRect.width * 100,
                0,
                100
            );

            const lightY = clamp(
                (
                    latestPointer.y - heroRect.top
                ) / heroRect.height * 100,
                0,
                100
            );


            // 通过 CSS 变量更新显示效果。
            hero.style.setProperty(
                '--tp-number-x',
                numberX.toFixed(2) + 'px'
            );

            hero.style.setProperty(
                '--tp-number-y',
                numberY.toFixed(2) + 'px'
            );

            hero.style.setProperty(
                '--tp-graphic-x',
                graphicX.toFixed(2) + 'px'
            );

            hero.style.setProperty(
                '--tp-graphic-y',
                graphicY.toFixed(2) + 'px'
            );

            hero.style.setProperty(
                '--tp-light-x',
                lightX.toFixed(2) + '%'
            );

            hero.style.setProperty(
                '--tp-light-y',
                lightY.toFixed(2) + '%'
            );

            hero.style.setProperty(
                '--tp-light-opacity',
                '1'
            );
        }


        /* ========================================
           04. 鼠标离开时恢复
           ======================================== */

        function resetPointerEffect() {

            latestPointer = null;

            if (animationFrame !== null) {

                window.cancelAnimationFrame(
                    animationFrame
                );

                animationFrame = null;
            }

            hero.style.setProperty(
                '--tp-number-x',
                '0px'
            );

            hero.style.setProperty(
                '--tp-number-y',
                '0px'
            );

            hero.style.setProperty(
                '--tp-graphic-x',
                '0px'
            );

            hero.style.setProperty(
                '--tp-graphic-y',
                '0px'
            );

            hero.style.setProperty(
                '--tp-light-opacity',
                '0'
            );
        }


        /* ========================================
           05. 注册鼠标交互事件
           ======================================== */

        interactionArea.addEventListener(
            'pointermove',
            function (event) {

                // 不对触摸操作启用视差。
                if (
                    event.pointerType === 'touch'
                    || !canInteract()
                ) {
                    return;
                }

                latestPointer = {
                    x: event.clientX,
                    y: event.clientY
                };

                /*
                 * 每一帧最多更新一次。
                 *
                 * 避免鼠标高频事件不断重复
                 * 修改页面样式。
                 */
                if (animationFrame === null) {

                    animationFrame =
                        window.requestAnimationFrame(
                            updatePointerEffect
                        );
                }
            },
            {
                passive: true
            }
        );


        interactionArea.addEventListener(
            'pointerleave',
            resetPointerEffect
        );

        interactionArea.addEventListener(
            'pointercancel',
            resetPointerEffect
        );

        window.addEventListener(
            'blur',
            resetPointerEffect
        );

        window.addEventListener(
            'resize',
            resetPointerEffect,
            {
                passive: true
            }
        );


        /* ========================================
           06. 动画运行状态管理
           ======================================== */

        function syncMotionState() {

            const shouldPause =
                !heroInViewport
                || document.hidden
                || reducedMotion.matches;

            hero.classList.toggle(
                'tp-motion-paused',
                shouldPause
            );

            if (
                shouldPause
                || !finePointer.matches
            ) {
                resetPointerEffect();
            }
        }


        /* ========================================
           07. 离屏动画暂停
           ======================================== */

        if ('IntersectionObserver' in window) {

            const observer =
                new IntersectionObserver(

                    function (entries) {

                        entries.forEach(function (entry) {

                            heroInViewport =
                                entry.isIntersecting;

                            syncMotionState();
                        });
                    },

                    {
                        threshold: 0
                    }
                );

            observer.observe(hero);
        }


        /* ========================================
           08. 浏览器标签页切换
           ======================================== */

        document.addEventListener(
            'visibilitychange',
            syncMotionState
        );


        /* ========================================
           09. 系统动态效果设置变化
           ======================================== */

        function observeMediaQuery(mediaQuery) {

            if (
                typeof mediaQuery.addEventListener
                === 'function'
            ) {
                mediaQuery.addEventListener(
                    'change',
                    syncMotionState
                );
            } else if (
                typeof mediaQuery.addListener
                === 'function'
            ) {
                mediaQuery.addListener(
                    syncMotionState
                );
            }
        }

        observeMediaQuery(finePointer);
        observeMediaQuery(reducedMotion);

        syncMotionState();
    }


    /* ========================================
       10. 页面初始化
       ======================================== */

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            initTeamHero,
            {
                once: true
            }
        );

    } else {

        initTeamHero();
    }

})();