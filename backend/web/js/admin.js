/**
 * WorldCup2026 后台公共导航交互
 */

(function () {
    'use strict';

    function initAdminNavigation() {

        var body = document.body;

        var sidebar = document.getElementById(
            'admin-sidebar'
        );

        var toggle = document.querySelector(
            '[data-admin-menu-toggle]'
        );

        if (!sidebar || !toggle) {
            return;
        }

        var mobileQuery = window.matchMedia(
            '(max-width: 991px)'
        );

        body.classList.add('admin-js');

        toggle.hidden = false;

        function setMenu(open) {

            body.classList.toggle(
                'admin-menu-open',
                open
            );

            toggle.setAttribute(
                'aria-expanded',
                open ? 'true' : 'false'
            );

            toggle.setAttribute(
                'aria-label',
                open
                    ? '收起后台导航'
                    : '展开后台导航'
            );
        }

        // 移动端默认关闭导航。
        setMenu(false);

        toggle.addEventListener('click', function () {

            var willOpen =
                toggle.getAttribute('aria-expanded')
                !== 'true';

            setMenu(willOpen);

            if (willOpen) {

                var firstLink =
                    sidebar.querySelector('a[href]');

                if (firstLink) {
                    firstLink.focus();
                }
            }
        });

        // Escape 关闭移动端导航并恢复焦点。
        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape'
                    && mobileQuery.matches
                    && body.classList.contains(
                        'admin-menu-open'
                    )
                ) {

                    setMenu(false);

                    toggle.focus();
                }
            }
        );

        // 点击导航区域外部时关闭。
        document.addEventListener(
            'click',
            function (event) {

                if (!mobileQuery.matches) {
                    return;
                }

                if (
                    sidebar.contains(event.target)
                    || toggle.contains(event.target)
                ) {
                    return;
                }

                setMenu(false);
            }
        );

        // 桌面和移动端切换时恢复状态。
        function resetNavigation() {
            setMenu(false);
        }

        if (mobileQuery.addEventListener) {

            mobileQuery.addEventListener(
                'change',
                resetNavigation
            );

        } else {

            mobileQuery.addListener(
                resetNavigation
            );
        }
    }

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            initAdminNavigation,
            { once: true }
        );

    } else {

        initAdminNavigation();
    }

})();