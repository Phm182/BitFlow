(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var picker = document.getElementById('wa-picker');
        if (!picker) return;

        var backdrop = picker.querySelector('.wa-picker__backdrop');
        var closeBtn = picker.querySelector('.wa-picker__close');
        var triggers = document.querySelectorAll('.wa-picker-trigger');

        function openPicker(event) {
            if (event) {
                event.preventDefault();
            }
            picker.classList.add('wa-picker--open');
            picker.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closePicker() {
            picker.classList.remove('wa-picker--open');
            picker.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', openPicker);
        });

        if (backdrop) backdrop.addEventListener('click', closePicker);
        if (closeBtn) closeBtn.addEventListener('click', closePicker);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && picker.classList.contains('wa-picker--open')) {
                closePicker();
            }
        });
    });
})();
