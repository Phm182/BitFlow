(function () {
    'use strict';

    var nodes = document.querySelectorAll('.reveal-on-scroll');
    if (!nodes.length) return;

    if (!('IntersectionObserver' in window)) {
        for (var i = 0; i < nodes.length; i++) {
            nodes[i].classList.add('is-visible');
        }
        return;
    }

    var observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
    );

    for (var j = 0; j < nodes.length; j++) {
        observer.observe(nodes[j]);
    }
})();
