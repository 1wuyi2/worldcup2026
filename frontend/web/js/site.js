/**
 * WorldCup2026 公共导航交互。
 *
 * 主导航、业务下拉菜单及账户菜单。
 */

(function () {
    'use strict';

    var header = document.querySelector(
        '[data-site-header]'
    );

    var nav = document.querySelector(
        '[data-site-nav]'
    );

    var navToggle = document.querySelector(
        '[data-nav-toggle]'
    );

    if (!header || !nav || !navToggle) {
        return;
    }

    var dropdownButtons = Array.prototype.slice.call(
        nav.querySelectorAll('[data-nav-dropdown]')
    );


    /* ========================================
       01. 下拉菜单状态管理
       ======================================== */

    function setDropdown(button, open) {

        var item = button.parentNode;

        button.setAttribute(
            'aria-expanded',
            open ? 'true' : 'false'
        );

        item.classList.toggle('is-open', open);
    }


    function closeDropdowns(exceptButton) {

        dropdownButtons.forEach(function (button) {

            if (button !== exceptButton) {
                setDropdown(button, false);
            }
        });
    }


    /* ========================================
       02. 移动端主导航状态
       ======================================== */

    function setNavigation(open) {

        navToggle.setAttribute(
            'aria-expanded',
            open ? 'true' : 'false'
        );

        navToggle.querySelector('.sr-only').textContent =
            open ? '收起主导航' : '展开主导航';

        nav.classList.toggle('is-open', open);

        document.body.classList.toggle(
            'nav-open',
            open
        );

        if (!open) {
            closeDropdowns();
        }
    }


    navToggle.addEventListener('click', function () {

        setNavigation(
            navToggle.getAttribute('aria-expanded')
            !== 'true'
        );
    });


    /* ========================================
       03. 下拉菜单点击操作
       ======================================== */

    dropdownButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            var willOpen =
                button.getAttribute('aria-expanded')
                !== 'true';

            // 展开当前菜单时关闭其他已展开菜单。
            closeDropdowns(button);

            setDropdown(button, willOpen);
        });


        /* ====================================
           ArrowDown 键展开菜单并聚焦首项
           ==================================== */

        button.addEventListener(
            'keydown',
            function (event) {

                if (event.key !== 'ArrowDown') {
                    return;
                }

                event.preventDefault();

                closeDropdowns(button);

                setDropdown(button, true);

                /*
                 * 原有业务菜单首项是链接；
                 * 普通账户菜单首项可能是退出按钮。
                 */

                var firstAction =
                    button.parentNode.querySelector(
                        '.site-subnav a, '
                        + '.site-account__menu a[href], '
                        + '.site-account__menu button[type="submit"]'
                    );

                if (firstAction) {
                    firstAction.focus();
                }
            }
        );
    });


    /* ========================================
       04. Escape 键关闭菜单
       ======================================== */

    nav.addEventListener('keydown', function (event) {

        if (event.key !== 'Escape') {
            return;
        }

        var openButton = nav.querySelector(
            '[data-nav-dropdown][aria-expanded="true"]'
        );

        if (openButton) {

            event.preventDefault();

            closeDropdowns();

            openButton.focus();

            return;
        }

        if (
            navToggle.getAttribute('aria-expanded')
            === 'true'
        ) {

            event.preventDefault();

            setNavigation(false);

            navToggle.focus();
        }
    });


    /* ========================================
       05. 点击菜单外部关闭
       ======================================== */

    document.addEventListener(
        'click',
        function (event) {

            // 保留原有点击 Header 外部关闭菜单机制。
            if (!header.contains(event.target)) {

                closeDropdowns();

                return;
            }

            /*
             * 账户菜单单独处理点击外部关闭。
             * 即使点击的是 Header 内其他区域，
             * 账户菜单也应该关闭。
             */

            dropdownButtons.forEach(function (button) {

                if (
                    !button.classList.contains(
                        'site-account__trigger'
                    )
                ) {
                    return;
                }

                if (
                    !button.parentNode.contains(
                        event.target
                    )
                ) {
                    setDropdown(button, false);
                }
            });
        }
    );


    /* ========================================
       06. 响应式状态恢复
       ======================================== */

    window.addEventListener('resize', function () {

        if (
            window.innerWidth >= 992
            && navToggle.getAttribute('aria-expanded')
                === 'true'
        ) {

            setNavigation(false);
        }
    });

}());