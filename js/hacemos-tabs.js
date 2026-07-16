(function () {
    'use strict';

    function initHacemosTabs() {
        var root = document.getElementById('section-hacemos');
        if (!root) return;

        var tabs = root.querySelectorAll('.hacemos-tab');
        var panels = root.querySelectorAll('.hacemos-panel');
        if (!tabs.length || !panels.length) return;

        function activate(tabId) {
            for (var i = 0; i < tabs.length; i++) {
                var tab = tabs[i];
                var isActive = tab.getAttribute('data-tab') === tabId;
                tab.classList.toggle('is-active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.tabIndex = isActive ? 0 : -1;
            }

            for (var j = 0; j < panels.length; j++) {
                var panel = panels[j];
                var match = panel.id === 'panel-' + tabId;
                panel.classList.toggle('is-active', match);
                if (match) {
                    panel.removeAttribute('hidden');
                } else {
                    panel.setAttribute('hidden', '');
                }
            }
        }

        root.addEventListener('click', function (event) {
            var tab = event.target.closest('.hacemos-tab');
            if (!tab || !root.contains(tab)) return;
            activate(tab.getAttribute('data-tab'));
        });

        root.addEventListener('keydown', function (event) {
            var current = event.target.closest('.hacemos-tab');
            if (!current || !root.contains(current)) return;

            var keys = ['ArrowLeft', 'ArrowRight', 'Home', 'End'];
            if (keys.indexOf(event.key) === -1) return;

            event.preventDefault();
            var list = Array.prototype.slice.call(tabs);
            var index = list.indexOf(current);
            var next = index;

            if (event.key === 'ArrowRight') next = (index + 1) % list.length;
            if (event.key === 'ArrowLeft') next = (index - 1 + list.length) % list.length;
            if (event.key === 'Home') next = 0;
            if (event.key === 'End') next = list.length - 1;

            list[next].focus();
            activate(list[next].getAttribute('data-tab'));
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHacemosTabs);
    } else {
        initHacemosTabs();
    }
})();
