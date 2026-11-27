/**
 * WorldCup2026 后台认证页面交互。
 *
 * 只处理密码可见性切换。
 */

(function () {
    'use strict';

    function initPasswordToggles() {

        var buttons = document.querySelectorAll(
            '.admin-auth [data-password-toggle]'
        );

        buttons.forEach(function (button) {

            var input = document.getElementById(
                button.getAttribute('aria-controls')
            );

            if (!input) {
                return;
            }

            button.hidden = false;

            function setVisible(visible) {

                input.type = visible
                    ? 'text'
                    : 'password';

                button.setAttribute(
                    'aria-pressed',
                    visible ? 'true' : 'false'
                );

                button.setAttribute(
                    'aria-label',
                    visible ? '隐藏密码' : '显示密码'
                );

                button.setAttribute(
                    'title',
                    visible ? '隐藏密码' : '显示密码'
                );
            }

            button.addEventListener('click', function () {

                setVisible(input.type === 'password');
            });

            if (input.form) {

                input.form.addEventListener(
                    'submit',
                    function () {
                        setVisible(false);
                    }
                );
            }
        });
    }

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            initPasswordToggles,
            { once: true }
        );

    } else {

        initPasswordToggles();
    }

})();