(function () {
    'use strict';

    var canvas = document.getElementById('contacto-particles');
    var typedEl = document.getElementById('contacto-typed');
    if (!canvas || !typedEl) return;

    var ctx = canvas.getContext('2d');
    var particles = [];
    var mouse = { x: -9999, y: -9999 };
    var lines = [
        '$ npm run create-magic',
        '→ Analizando tu idea...',
        '→ Diseñando arquitectura...',
        '→ Desplegando solución BitFlow ✓',
        'Tu producto digital está listo para despegar.'
    ];
    var lineIndex = 0;
    var charIndex = 0;
    var typingPause = 0;

    function resizeCanvas() {
        var rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * window.devicePixelRatio;
        canvas.height = rect.height * window.devicePixelRatio;
        ctx.setTransform(window.devicePixelRatio, 0, 0, window.devicePixelRatio, 0, 0);
        initParticles();
    }

    function initParticles() {
        var rect = canvas.getBoundingClientRect();
        var count = Math.min(55, Math.floor(rect.width / 14));
        particles = [];

        for (var i = 0; i < count; i++) {
            particles.push({
                x: Math.random() * rect.width,
                y: Math.random() * rect.height,
                vx: (Math.random() - 0.5) * 0.35,
                vy: (Math.random() - 0.5) * 0.35,
                r: Math.random() * 2 + 0.6
            });
        }
    }

    function drawParticles() {
        var rect = canvas.getBoundingClientRect();
        ctx.clearRect(0, 0, rect.width, rect.height);

        for (var i = 0; i < particles.length; i++) {
            var p = particles[i];
            p.x += p.vx;
            p.y += p.vy;

            if (p.x < 0 || p.x > rect.width) p.vx *= -1;
            if (p.y < 0 || p.y > rect.height) p.vy *= -1;

            var dx = mouse.x - p.x;
            var dy = mouse.y - p.y;
            var dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < 120) {
                p.x -= dx * 0.015;
                p.y -= dy * 0.015;
            }

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(0, 229, 255, 0.75)';
            ctx.fill();

            for (var j = i + 1; j < particles.length; j++) {
                var q = particles[j];
                var lx = p.x - q.x;
                var ly = p.y - q.y;
                var ld = Math.sqrt(lx * lx + ly * ly);
                if (ld < 110) {
                    ctx.strokeStyle = 'rgba(0, 229, 255, ' + (0.22 - ld / 500) + ')';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(q.x, q.y);
                    ctx.stroke();
                }
            }
        }

        requestAnimationFrame(drawParticles);
    }

    function typeLoop() {
        if (typingPause > 0) {
            typingPause--;
            return requestAnimationFrame(typeLoop);
        }

        var current = lines[lineIndex];
        typedEl.textContent += current.charAt(charIndex);
        charIndex++;

        if (charIndex >= current.length) {
            typedEl.textContent += '\n';
            charIndex = 0;
            lineIndex = (lineIndex + 1) % lines.length;
            typingPause = lineIndex === 0 ? 55 : 18;
            if (lineIndex === 0) typedEl.textContent = '';
        }

        setTimeout(function () {
            requestAnimationFrame(typeLoop);
        }, 38);
    }

    function animateStats() {
        document.querySelectorAll('.contacto-stat strong[data-count]').forEach(function (el) {
            var target = parseInt(el.getAttribute('data-count'), 10);
            var current = 0;
            var step = Math.max(1, Math.floor(target / 30));

            var tick = function () {
                current += step;
                if (current >= target) {
                    el.textContent = target;
                    return;
                }
                el.textContent = current;
                requestAnimationFrame(tick);
            };

            tick();
        });
    }

    canvas.closest('.contacto-showcase').addEventListener('mousemove', function (event) {
        var rect = canvas.getBoundingClientRect();
        mouse.x = event.clientX - rect.left;
        mouse.y = event.clientY - rect.top;
    });

    canvas.closest('.contacto-showcase').addEventListener('mouseleave', function () {
        mouse.x = -9999;
        mouse.y = -9999;
    });

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                animateStats();
                observer.disconnect();
            }
        });
    }, { threshold: 0.35 });

    observer.observe(canvas.closest('.contacto-showcase'));

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    drawParticles();
    typeLoop();
})();
