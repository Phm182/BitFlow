(function () {
    'use strict';

    function initProyectosCarousel() {
        var track = document.getElementById('proyectos-track');
        var dotsWrap = document.getElementById('proyectos-dots');
        if (!track || !dotsWrap) return;
        if (track.dataset.carouselBound === '1') return;
        track.dataset.carouselBound = '1';

        var cards = Array.prototype.slice.call(track.querySelectorAll('.Proy'));
        if (!cards.length) return;

        var carousel = track.closest('.proyectos-carousel');
        var prevBtn = carousel ? carousel.querySelector('.proyectos-carousel__arrow--prev') : null;
        var nextBtn = carousel ? carousel.querySelector('.proyectos-carousel__arrow--next') : null;
        var activeIndex = 0;

        cards.forEach(function (_, index) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'proyectos-dot' + (index === 0 ? ' is-active' : '');
            dot.setAttribute('aria-label', 'Ir al proyecto ' + (index + 1));
            dot.setAttribute('role', 'tab');
            dot.addEventListener('click', function () {
                scrollToIndex(index);
            });
            dotsWrap.appendChild(dot);
        });

        var dots = Array.prototype.slice.call(dotsWrap.querySelectorAll('.proyectos-dot'));

        function scrollToIndex(index) {
            var card = cards[index];
            if (!card) return;
            var left = card.offsetLeft - (track.clientWidth - card.clientWidth) / 2;
            track.scrollTo({ left: Math.max(0, left), behavior: 'smooth' });
        }

        function updateActive() {
            var trackCenter = track.scrollLeft + track.clientWidth / 2;
            var nearest = 0;
            var nearestDist = Infinity;

            cards.forEach(function (card, index) {
                var cardCenter = card.offsetLeft + card.clientWidth / 2;
                var dist = Math.abs(cardCenter - trackCenter);
                if (dist < nearestDist) {
                    nearestDist = dist;
                    nearest = index;
                }
            });

            activeIndex = nearest;
            dots.forEach(function (dot, index) {
                dot.classList.toggle('is-active', index === nearest);
            });

            if (prevBtn) prevBtn.disabled = nearest === 0;
            if (nextBtn) nextBtn.disabled = nearest === cards.length - 1;
        }

        track.addEventListener('scroll', function () {
            window.requestAnimationFrame(updateActive);
        }, { passive: true });

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                scrollToIndex(Math.max(0, activeIndex - 1));
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                scrollToIndex(Math.min(cards.length - 1, activeIndex + 1));
            });
        }

        updateActive();
        window.addEventListener('resize', updateActive);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProyectosCarousel);
    } else {
        initProyectosCarousel();
    }
})();
