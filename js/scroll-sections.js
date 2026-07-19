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
        var candidates = section.querySelectorAll('.nombre, h1, h2, h3, .page-hero__title, .page-story__heading');
        for (var i = 0; i < candidates.length; i++) {
            if (isVisible(candidates[i])) {
                return candidates[i];
            }
        }
        return section;
    }

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

        if (document.body.classList.contains('has-scroll-sections')) {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }

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

    function getSectionNextHref(section) {
        if (!section) return '';
        var btn = section.querySelector('.section-scroll-hint .btn-scroll-next, .Boton-flechas--down .btn-scroll-next');
        return btn ? (btn.getAttribute('href') || '') : '';
    }

    function initFixedScrollButton() {
        if (
            !document.body.classList.contains('page-inicio') &&
            !document.body.classList.contains('has-scroll-sections')
        ) {
            return;
        }

        var fixedWrap = document.getElementById('scroll-next-fixed');
        var fixedBtn = fixedWrap ? fixedWrap.querySelector('.btn-scroll-next') : null;
        if (!fixedWrap || !fixedBtn) return;

        var sections = Array.prototype.slice.call(document.querySelectorAll('.page-section'));
        var items = sections.map(function (section) {
            return {
                section: section,
                href: getSectionNextHref(section)
            };
        });

        if (!items.length) return;

        // Asegurar que el botón viva en <body> (nunca dentro de un ancestro con transform)
        if (fixedWrap.parentNode !== document.body) {
            document.body.appendChild(fixedWrap);
        }

        function setVisible(show) {
            if (show) {
                fixedWrap.removeAttribute('hidden');
                fixedWrap.classList.add('is-visible');
            } else {
                fixedWrap.setAttribute('hidden', '');
                fixedWrap.classList.remove('is-visible');
            }
        }

        function updateActive() {
            var probe = window.innerHeight * 0.42;
            var best = null;
            var bestScore = -1;

            items.forEach(function (item) {
                var rect = item.section.getBoundingClientRect();
                var visibleTop = Math.max(rect.top, 0);
                var visibleBottom = Math.min(rect.bottom, window.innerHeight);
                var visible = Math.max(0, visibleBottom - visibleTop);
                if (visible <= 0) return;

                var containsProbe = rect.top <= probe && rect.bottom >= probe;
                var score = visible + (containsProbe ? window.innerHeight : 0);

                if (score > bestScore) {
                    bestScore = score;
                    best = item;
                }
            });

            // Sin sección activa, o última sección (mapa) sin siguiente → ocultar
            if (!best || !best.href) {
                setVisible(false);
                return;
            }

            // Hero: esperar el split antes de mostrar el botón
            if (best.section.id === 'section-hero') {
                var hero = best.section.querySelector('.LetrasLogo[data-mode="continue"]');
                if (hero && !hero.classList.contains('is-split-visible')) {
                    setVisible(false);
                    return;
                }
            }

            fixedBtn.setAttribute('href', best.href);
            setVisible(true);
        }

        var ticking = false;
        function onScrollOrResize() {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(function () {
                ticking = false;
                updateActive();
            });
        }

        window.addEventListener('scroll', onScrollOrResize, { passive: true });
        window.addEventListener('resize', onScrollOrResize);
        updateActive();

        var hero = document.querySelector('.LetrasLogo[data-mode="continue"]');
        if (hero && typeof MutationObserver !== 'undefined') {
            var mo = new MutationObserver(updateActive);
            mo.observe(hero, { attributes: true, attributeFilter: ['class'] });
        }
    }

    function initScrollSections() {
        document.addEventListener('click', onScrollClick);
        initFixedScrollButton();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollSections);
    } else {
        initScrollSections();
    }
})();
