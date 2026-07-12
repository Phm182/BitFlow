(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-scroll-next').forEach(function (btn) {
            btn.addEventListener('click', function (event) {
                event.preventDefault();
                var selector = btn.getAttribute('href');
                if (!selector || selector.charAt(0) !== '#') return;

                var target = document.querySelector(selector);
                if (!target) return;

                var header = document.querySelector('.header');
                var headerHeight = header ? header.getBoundingClientRect().height : 0;
                var targetTop = target.getBoundingClientRect().top + window.pageYOffset;
                window.scrollTo({
                    top: Math.max(0, targetTop - headerHeight),
                    behavior: 'smooth'
                });
            });
        });
    });
})();
