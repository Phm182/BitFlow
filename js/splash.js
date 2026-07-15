(function () {
    'use strict';

    function initSplash() {
        var hero = document.querySelector('.LetrasLogo');
        if (!hero) return;

        var mode = hero.getAttribute('data-mode') || 'full';
        var fromSplash = sessionStorage.getItem('bitflowFromSplash');
        var header = document.querySelector('.header--delayed');
        var container = document.querySelector('.contenedorHeader--inicio');
        var tagline = hero.querySelector('.splash-tagline');

        if (mode === 'full') {
            var leaveBtn = document.querySelector('.btn-content[href="inicio.php"]');
            if (leaveBtn && leaveBtn.dataset.leaveBound !== '1') {
                leaveBtn.dataset.leaveBound = '1';
                leaveBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var target = leaveBtn.getAttribute('href');
                    var lettersRow = hero.querySelector('.bitflow-letters-row');
                    try { sessionStorage.setItem('bitflowFromSplash', '1'); } catch (err) {}
                    if (lettersRow) {
                        lettersRow.style.transition = 'opacity 0.7s ease';
                    }
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () {
                            hero.classList.add('splash-leaving');
                        });
                    });
                    window.setTimeout(function () {
                        window.location.href = target;
                    }, 750);
                });
            }
        }

        if (mode === 'continue' && fromSplash) {
            hero.classList.add('splash-continue');
            sessionStorage.removeItem('bitflowFromSplash');

            if (container) {
                container.classList.add('is-blur-transition');

                requestAnimationFrame(function () {
                    container.classList.add('is-blurring');
                });

                window.setTimeout(function () {
                    container.classList.add('is-split-visible');
                    hero.classList.add('is-split-visible');
                    document.documentElement.classList.remove('splash-from-index');
                    if (tagline) {
                        tagline.setAttribute('aria-hidden', 'false');
                    }
                }, 800);
            }

            if (header) {
                window.setTimeout(function () {
                    header.classList.add('header-visible');
                }, 1000);
            }
        } else if (mode === 'continue') {
            hero.classList.add('splash-continue');
            if (container) {
                container.classList.add('is-blur-transition', 'is-split-visible');
            }
            if (tagline) {
                tagline.setAttribute('aria-hidden', 'false');
            }
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    hero.classList.add('is-split-visible');
                });
            });
            if (header) {
                window.setTimeout(function () {
                    header.classList.add('header-visible');
                }, 1000);
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
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSplash);
    } else {
        initSplash();
    }
})();
