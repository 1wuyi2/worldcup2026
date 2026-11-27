/**
 * WorldCup2026 用户认证页面交互。
 *
 * 负责密码显示与隐藏。
 * 不参与认证请求、密码校验或数据存储。
 */

(function () {
    'use strict';

    function initPasswordToggles() {

        const buttons = document.querySelectorAll(
            '.auth-page [data-password-toggle]'
        );

        buttons.forEach(function (button) {

            const inputId = button.getAttribute(
                'aria-controls'
            );

            const passwordInput = document.getElementById(
                inputId
            );

            if (!passwordInput) {
                return;
            }

            // JavaScript 正常加载后再显示交互按钮。
            button.hidden = false;

            button.addEventListener('click', function () {

                const isHidden =
                    passwordInput.type === 'password';

                passwordInput.type = isHidden
                    ? 'text'
                    : 'password';

                button.setAttribute(
                    'aria-pressed',
                    isHidden ? 'true' : 'false'
                );

                button.setAttribute(
                    'aria-label',
                    isHidden ? '隐藏密码' : '显示密码'
                );

                button.setAttribute(
                    'title',
                    isHidden ? '隐藏密码' : '显示密码'
                );
            });

            /*
             * 提交表单时恢复密码隐藏状态。
             * 不修改输入框的值、name 或表单提交逻辑。
             */

            const form = passwordInput.form;

            if (form) {

                form.addEventListener('submit', function () {

                    passwordInput.type = 'password';

                    button.setAttribute(
                        'aria-pressed',
                        'false'
                    );

                    button.setAttribute(
                        'aria-label',
                        '显示密码'
                    );

                    button.setAttribute(
                        'title',
                        '显示密码'
                    );
                });
            }
        });
    }

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            initPasswordToggles,
            {
                once: true
            }
        );

    } else {

        initPasswordToggles();
    }

})();