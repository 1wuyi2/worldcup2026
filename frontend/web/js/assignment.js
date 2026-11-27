/**
 * WorldCup2026 课程资料中心。
 *
 * 成员筛选自动提交。
 */

(function () {
    'use strict';

    function initArchiveFilters() {

        const form = document.getElementById(
            'tp-archive-filter-form'
        );

        const memberSelect = document.getElementById(
            'tp-archive-member'
        );

        if (!form || !memberSelect) {
            return;
        }

        /*
         * 成员选择只针对个人作业。
         *
         * 选择成员后，使用原有 GET 表单提交，
         * 由 Controller 执行实际数据库查询。
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
            initArchiveFilters,
            {
                once: true
            }
        );

    } else {

        initArchiveFilters();
    }

})();