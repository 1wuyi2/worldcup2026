/**
 * WorldCup2026 - Home Interaction
 *
 * 首页动态交互：
 * 1. 滚动入场动画
 * 2. 内容错峰入场
 * 3. 图片揭示动画
 * 4. 数据统计数字增长
 */

(function () {
    'use strict';

    function initHomeInteractions() {

    // 章节导航属于基础浏览功能，
    // 不受减少动态效果设置影响。
    initSectionNavigation();

    const motionPreference = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    if (motionPreference.matches) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        return;
    }

    initScrollReveal();
    initCountUp();
    initHeroMotion();
}


    /* ==========================================
       1. 滚动入场动画
       ========================================== */

    function initScrollReveal() {

        const elements = new Set();

        /**
         * 注册需要播放入场动画的元素。
         */
        function register(selector, type, delay, stagger) {

            document.querySelectorAll(selector).forEach(
                function (element, index) {

                    if (elements.has(element)) {
                        return;
                    }

                    const rect = element.getBoundingClientRect();

                    // 已经处于视口内或视口上方的内容保持可见，
                    // 避免刷新页面时突然隐藏。
                    if (rect.top < window.innerHeight) {
                        return;
                    }

                    const animationDelay = Math.min(
                        (delay || 0) + index * (stagger || 0),
                        360
                    );

                    element.style.setProperty(
                        '--home-reveal-delay',
                        animationDelay + 'ms'
                    );

                    element.classList.add(
                        'home-reveal',
                        'home-reveal--' + type
                    );

                    elements.add(element);
                }
            );
        }

        /*
         * 赛事精选
         */

        register(
            '.home-match-feature',
            'up',
            0,
            0
        );

        /*
         * 探索世界杯
         */

        register(
            '.home-explore__header',
            'up',
            0,
            0
        );

        register(
            '.home-explore__stats',
            'up',
            60,
            0
        );

        register(
            '.home-explore__teams-header',
            'up',
            0,
            0
        );

        register(
            '.home-explore__team',
            'soft',
            0,
            55
        );

        /*
         * 世界杯资讯
         */

        register(
            '.home-news__header',
            'up',
            0,
            0
        );

        register(
            '.home-news__photo',
            'image',
            0,
            0
        );

        register(
            '.home-news__feed',
            'right',
            80,
            0
        );

        /*
         * 团队门户
         */

        register(
            '.home-portal__inner',
            'line',
            0,
            0
        );

        register(
            '.home-portal__intro',
            'up',
            0,
            0
        );

        register(
            '.home-portal__link',
            'soft',
            40,
            70
        );


        function reveal(element) {

            element.classList.add('is-revealed');

            // 只播放一次。
            observer.unobserve(element);
        }


        const observer = new IntersectionObserver(
            function (entries) {

                entries.forEach(function (entry) {

                    if (entry.isIntersecting) {
                        reveal(entry.target);
                    }

                });

            },
            {
                root: null,
                rootMargin: '0px 0px -24px 0px',
                threshold: 0
            }
        );


        elements.forEach(function (element) {

            observer.observe(element);

            // 键盘用户聚焦隐藏区域中的链接时，
            // 立即显示该区域。
            element.addEventListener('focusin', function () {

                element.style.setProperty(
                    '--home-reveal-delay',
                    '0ms'
                );

                reveal(element);

            });

        });
    }


    /* ==========================================
       2. 数据统计数字增长
       ========================================== */

    function initCountUp() {

        const numbers = document.querySelectorAll(
            '.home-explore__stat-number'
        );

        if (!numbers.length) {
            return;
        }

        const formatter = new Intl.NumberFormat('en-US');


        function animateNumber(element) {

            // 直接读取 PHP 已输出的真实数据库数据。
            const originalText = element.textContent.trim();

            const target = Number(
                originalText.replace(/,/g, '')
            );

            if (!Number.isFinite(target) || target <= 0) {
                return;
            }

            // 保留原始数字供辅助技术读取，
            // 避免屏幕阅读器连续朗读动画中的数字变化。
            const accessibleValue = document.createElement('span');
            accessibleValue.className = 'home-motion-sr-only';
            accessibleValue.textContent = originalText;

            const visualValue = document.createElement('span');
            visualValue.setAttribute('aria-hidden', 'true');
            visualValue.textContent = originalText;

            element.textContent = '';
            element.appendChild(accessibleValue);
            element.appendChild(visualValue);

            const duration = 1100;

            let startTime = null;


            function update(timestamp) {

                if (startTime === null) {
                    startTime = timestamp;
                }

                const elapsed = timestamp - startTime;

                const progress = Math.min(
                    elapsed / duration,
                    1
                );

                // Ease-out cubic：开始较快，结束较慢。
                const eased = 1 - Math.pow(
                    1 - progress,
                    3
                );

                const current = Math.round(
                    target * eased
                );

                visualValue.textContent = formatter.format(current);

                if (progress < 1) {

                    window.requestAnimationFrame(update);

                } else {

                    // 动画结束后恢复精确的原始数字。
                    element.textContent = originalText;

                }
            }

            window.requestAnimationFrame(update);
        }


        const observer = new IntersectionObserver(
            function (entries) {

                entries.forEach(function (entry) {

                    if (!entry.isIntersecting) {
                        return;
                    }

                    animateNumber(entry.target);

                    observer.unobserve(entry.target);

                });

            },
            {
                threshold: 0.5
            }
        );


        numbers.forEach(function (number) {
            observer.observe(number);
        });
    }
    /* ==========================================
   3. Hero 沉浸式动态交互
   ========================================== */

function initHeroMotion() {

    const hero = document.querySelector('.home-hero');

    if (!hero) {
        return;
    }

    const desktop = window.matchMedia(
        '(min-width: 992px)'
    );

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    let frameId = null;
    let heroVisible = true;


    // 启用 Hero 首屏入场样式。
    hero.classList.add('home-hero--motion');


    /*
     * 根据 Hero 当前的滚动进度更新 CSS 变量。
     */

    function updateHero() {

        frameId = null;

        if (!desktop.matches || reducedMotion.matches) {

            hero.style.setProperty(
                '--hero-parallax-y',
                '0px'
            );

            hero.style.setProperty(
                '--hero-content-y',
                '0px'
            );

            hero.style.setProperty(
                '--hero-content-opacity',
                '1'
            );

            return;
        }

        if (!heroVisible) {
            return;
        }

        const rect = hero.getBoundingClientRect();

        if (rect.height <= 0) {
            return;
        }

        // Hero 已经滚出屏幕的距离。
        const scrolled = Math.max(0, -rect.top);

        // 将滚动进度限制在 0 到 1 之间。
        const progress = Math.min(
            scrolled / rect.height,
            1
        );

        // 背景与内容采用不同的移动速度。
        const backgroundY = Math.min(
            scrolled * 0.18,
            110
        );

        const contentY = Math.min(
            scrolled * 0.07,
            45
        );

        // 滚动初期保持标题清晰，之后逐渐淡出。
        const fadeProgress = Math.max(
            0,
            (progress - 0.2) / 0.8
        );

        // 不完全隐藏内容，保证按钮仍然具有可读性。
        const contentOpacity = Math.max(
            0.4,
            1 - fadeProgress * 0.6
        );


        hero.style.setProperty(
            '--hero-parallax-y',
            backgroundY.toFixed(2) + 'px'
        );

        hero.style.setProperty(
            '--hero-content-y',
            contentY.toFixed(2) + 'px'
        );

        hero.style.setProperty(
            '--hero-content-opacity',
            contentOpacity.toFixed(3)
        );
    }


    /*
     * 使用 requestAnimationFrame 合并滚动更新。
     */

    function requestUpdate() {

        if (frameId !== null) {
            return;
        }

        frameId = window.requestAnimationFrame(
            updateHero
        );
    }


    /*
     * Hero 离开屏幕后停止更新其动画状态。
     */

    const heroObserver = new IntersectionObserver(

        function (entries) {

            entries.forEach(function (entry) {

                heroVisible = entry.isIntersecting;

                if (heroVisible) {
                    requestUpdate();
                }

            });

        },

        {
            threshold: 0
        }

    );

    heroObserver.observe(hero);


    /*
     * 注册滚动和窗口尺寸变化事件。
     */

    window.addEventListener(
        'scroll',
        function () {

            if (heroVisible && desktop.matches) {
                requestUpdate();
            }

        },
        {
            passive: true
        }
    );

    window.addEventListener(
        'resize',
        requestUpdate
    );


    // 用户改变减少动态效果设置时，重新计算状态。
    if (reducedMotion.addEventListener) {

        reducedMotion.addEventListener(
            'change',
            requestUpdate
        );

        desktop.addEventListener(
            'change',
            requestUpdate
        );
    }


    // 初始化当前滚动位置。
    requestUpdate();

}
/* ==========================================
   4. 首页章节导航与阅读进度
   ========================================== */

function initSectionNavigation() {

    const navigation = document.querySelector(
        '.home-section-nav'
    );

    if (!navigation) {
        return;
    }

    const progressBar = document.querySelector(
        '.home-reading-progress__bar'
    );

    const counter = navigation.querySelector(
        '.home-section-nav__counter'
    );

    const links = Array.from(
        navigation.querySelectorAll(
            '.home-section-nav__link'
        )
    );

    /*
     * 根据导航链接的 href 属性，
     * 找到对应的真实页面区域。
     */

    const sections = links.map(function (link) {

        const id = link.getAttribute('href').slice(1);

        return document.getElementById(id);

    });


    if (sections.some(function (section) {
        return section === null;
    })) {
        return;
    }


    let activeIndex = -1;

    let frameId = null;


    /*
     * 更新当前激活的章节。
     */

    function setActiveSection(index) {

        if (index === activeIndex) {
            return;
        }

        activeIndex = index;

        links.forEach(function (link, linkIndex) {

            const isActive = linkIndex === index;

            link.classList.toggle(
                'is-active',
                isActive
            );

            if (isActive) {

                link.setAttribute(
                    'aria-current',
                    'location'
                );

            } else {

                link.removeAttribute('aria-current');

            }

        });


        if (counter) {

            const current = String(index + 1)
                .padStart(2, '0');

            const total = String(sections.length)
                .padStart(2, '0');

            counter.textContent =
                current + ' / ' + total;

        }
    }


    /*
     * 根据滚动位置计算当前章节及阅读进度。
     */

    function updateNavigation() {

        frameId = null;

        const scrollTop = window.scrollY;

        const viewportHeight = window.innerHeight;

        const documentHeight =
            document.documentElement.scrollHeight;

        const maxScroll = Math.max(
            documentHeight - viewportHeight,
            0
        );


        /*
         * 阅读进度。
         */

        const progress = maxScroll > 0
            ? Math.min(
                Math.max(scrollTop / maxScroll, 0),
                1
            )
            : 0;

        if (progressBar) {

            progressBar.style.transform =
                'scaleX(' + progress + ')';

        }


        /*
         * 以视口高度的 35% 作为章节判断参考线。
         */

        const triggerLine = viewportHeight * 0.35;

        let currentIndex = 0;

        sections.forEach(function (section, index) {

            const rect = section.getBoundingClientRect();

            if (rect.top <= triggerLine) {

                currentIndex = index;

            }

        });


        // 到达页面底部时，确保最后一个章节激活。
        if (
            maxScroll > 0 &&
            scrollTop >= maxScroll - 4
        ) {

            currentIndex = sections.length - 1;

        }


        setActiveSection(currentIndex);
    }


    /*
     * 合并高频滚动事件。
     */

    function requestUpdate() {

        if (frameId !== null) {
            return;
        }

        frameId = window.requestAnimationFrame(
            updateNavigation
        );

    }


    /*
     * 监听页面滚动及尺寸变化。
     */

    window.addEventListener(
        'scroll',
        requestUpdate,
        {
            passive: true
        }
    );

    window.addEventListener(
        'resize',
        requestUpdate
    );

    window.addEventListener(
        'load',
        requestUpdate
    );

    window.addEventListener(
        'pageshow',
        requestUpdate
    );


    // 初始化当前章节与阅读进度。
    navigation.classList.add('is-ready');

    requestUpdate();

}

    /* ==========================================
       初始化
       ========================================== */

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            initHomeInteractions
        );

    } else {

        initHomeInteractions();

    }

})();