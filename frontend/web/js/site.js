(function () {
    'use strict';

    var header = document.querySelector('[data-site-header]');
    var nav = document.querySelector('[data-site-nav]');
    var navToggle = document.querySelector('[data-nav-toggle]');

    if (!header || !nav || !navToggle) {
        return;
    }

    var dropdownButtons = Array.prototype.slice.call(
        nav.querySelectorAll('[data-nav-dropdown]')
    );

    function setDropdown(button, open) {
        var item = button.parentNode;

        button.setAttribute('aria-expanded', open ? 'true' : 'false');
        item.classList.toggle('is-open', open);
    }

    function closeDropdowns(exceptButton) {
        dropdownButtons.forEach(function (button) {
            if (button !== exceptButton) {
                setDropdown(button, false);
            }
        });
    }

    function setNavigation(open) {
        navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        navToggle.querySelector('.sr-only').textContent = open ? '收起主导航' : '展开主导航';
        nav.classList.toggle('is-open', open);
        document.body.classList.toggle('nav-open', open);

        if (!open) {
            closeDropdowns();
        }
    }

    navToggle.addEventListener('click', function () {
        setNavigation(navToggle.getAttribute('aria-expanded') !== 'true');
    });

    dropdownButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var willOpen = button.getAttribute('aria-expanded') !== 'true';

            closeDropdowns(button);
            setDropdown(button, willOpen);
        });

        button.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                closeDropdowns(button);
                setDropdown(button, true);

                var firstLink = button.parentNode.querySelector('.site-subnav a');
                if (firstLink) {
                    firstLink.focus();
                }
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                setDropdown(button, false);
                button.focus();
            }
        });
    });

    nav.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        var openButton = nav.querySelector('[data-nav-dropdown][aria-expanded="true"]');
        if (openButton) {
            setDropdown(openButton, false);
            openButton.focus();
            return;
        }

        if (navToggle.getAttribute('aria-expanded') === 'true') {
            setNavigation(false);
            navToggle.focus();
        }
    });

    document.addEventListener('click', function (event) {
        if (!header.contains(event.target)) {
            closeDropdowns();
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992 && navToggle.getAttribute('aria-expanded') === 'true') {
            setNavigation(false);
        }
    });
}());
