/**
 * WorldCup2026 前台留言板。
 *
 * 成员筛选自动提交。
 */

(function () {
    'use strict';

    function initGuestbookFilters() {

        const form = document.getElementById(
            'tp-voices-filter-form'
        );

        const memberSelect = document.getElementById(
            'tp-voices-member'
        );

        if (!form || !memberSelect) {
            return;
        }

        /*
         * 切换发布成员后自动提交 GET 表单。
         * 实际筛选由 GuestbookController 完成。
         */

        memberSelect.addEventListener(
            'change',
            function () {

                if (
                    typeof form.requestSubmit === 'function'
                ) {

                    form.requestSubmit();

                } else {

                    form.submit();
                }
            }
        );
    }

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            initGuestbookFilters,
            {
                once: true
            }
        );

    } else {

        initGuestbookFilters();
    }

})();