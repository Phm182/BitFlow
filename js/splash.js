(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var hero = document.querySelector('.LetrasLogo');
        if (!hero) return;

        var mode = hero.getAttribute('data-mode') || 'full';
        var fromSplash = sessionStorage.getItem('bitflowFromSplash');
        var header = document.querySelector('.header--delayed');
        var container = document.querySelector('.contenedorHeader--inicio');
        var splitPanel = hero.querySelector('.splash-hero-split');

        if (mode === 'continue' && fromSplash) {
            hero.classList.add('splash-continue');
            sessionStorage.removeItem('bitflowFromSplash');

            if (container && splitPanel) {
                container.classList.add('is-blur-transition');

                requestAnimationFrame(function () {
                    container.classList.add('is-blurring');
                });

                window.setTimeout(function () {
                    container.classList.add('is-split-visible');
                    hero.classList.add('is-split-visible');
                    splitPanel.setAttribute('aria-hidden', 'false');
                }, 750);

                window.setTimeout(function () {
                    container.classList.remove('is-blurring');
                }, 1400);
            }

            if (header) {
                window.setTimeout(function () {
                    header.classList.add('header-visible');
                }, 900);
            }
        } else if (header) {
            header.classList.add('header-visible');
        }

        var sections = document.querySelectorAll('.reveal-section');
        if (!sections.length) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.12 });

        sections.forEach(function (section, index) {
            section.style.transitionDelay = (index * 0.08) + 's';
            observer.observe(section);
        });
    });
})();
