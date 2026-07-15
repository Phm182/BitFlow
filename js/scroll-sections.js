(function () {
    'use strict';

    function isVisible(el) {
        if (!el) return false;
        var style = window.getComputedStyle(el);
        if (style.display === 'none' || style.visibility === 'hidden' || style.opacity === '0') {
            return false;
        }
        return el.getClientRects().length > 0;
    }

    function getSectionTitle(section) {
        var candidates = section.querySelectorAll('.nombre, h3');
        for (var i = 0; i < candidates.length; i++) {
            if (isVisible(candidates[i])) {
                return candidates[i];
            }
        }
        return section;
    }

    // offsetTop acumulado: NO se ve afectado por transforms (a diferencia de getBoundingClientRect)
    function getAbsoluteTop(el) {
        var top = 0;
        while (el) {
            top += el.offsetTop;
            el = el.offsetParent;
        }
        return top;
    }

    function getHeaderOffset() {
        var header = document.querySelector('.header');
        if (!header) return 16;
        return Math.ceil(header.getBoundingClientRect().height) + 14;
    }

    function scrollToSection(selector) {
        var section = document.querySelector(selector);
        if (!section) return;

        var anchor = getSectionTitle(section);
        var offset = getHeaderOffset();
        var top = getAbsoluteTop(anchor) - offset;

        window.scrollTo({
            top: Math.max(0, Math.round(top)),
            behavior: 'smooth'
        });
    }

    function onScrollClick(event) {
        var btn = event.target.closest('.btn-scroll-next');
        if (!btn) return;

        var selector = btn.getAttribute('href');
        if (!selector || selector.charAt(0) !== '#') return;

        event.preventDefault();
        scrollToSection(selector);
    }

    function initScrollSections() {
        document.addEventListener('click', onScrollClick);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollSections);
    } else {
        initScrollSections();
    }
})();
