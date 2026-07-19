(function () {
    'use strict';

    var canvas = document.getElementById('qs-constellation');
    if (!canvas) return;

    var ctx = canvas.getContext('2d');
    var nodes = [];
    var pulses = [];
    var mouse = { x: -9999, y: -9999 };
    var raf = 0;
    var running = true;

    function resize() {
        var parent = canvas.parentElement;
        if (!parent) return;
        var rect = parent.getBoundingClientRect();
        var dpr = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = Math.max(1, Math.floor(rect.width * dpr));
        canvas.height = Math.max(1, Math.floor(rect.height * dpr));
        canvas.style.width = rect.width + 'px';
        canvas.style.height = rect.height + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        seed(rect.width, rect.height);
    }

    function seed(w, h) {
        var count = Math.max(28, Math.min(52, Math.floor((w * h) / 18000)));
        nodes = [];
        for (var i = 0; i < count; i++) {
            nodes.push({
                x: Math.random() * w,
                y: Math.random() * h,
                vx: (Math.random() - 0.5) * 0.28,
                vy: (Math.random() - 0.5) * 0.28,
                r: 1.2 + Math.random() * 2.2
            });
        }
        pulses = [];
    }

    function spawnPulse() {
        if (nodes.length < 2) return;
        var a = nodes[Math.floor(Math.random() * nodes.length)];
        var b = nodes[Math.floor(Math.random() * nodes.length)];
        if (a === b) return;
        pulses.push({ a: a, b: b, t: 0, speed: 0.008 + Math.random() * 0.012 });
    }

    function step() {
        if (!running) return;
        var w = canvas.clientWidth;
        var h = canvas.clientHeight;
        ctx.clearRect(0, 0, w, h);

        var i;
        var j;
        for (i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            n.x += n.vx;
            n.y += n.vy;
            if (n.x < 0 || n.x > w) n.vx *= -1;
            if (n.y < 0 || n.y > h) n.vy *= -1;
            n.x = Math.max(0, Math.min(w, n.x));
            n.y = Math.max(0, Math.min(h, n.y));

            var dx = mouse.x - n.x;
            var dy = mouse.y - n.y;
            var dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < 120 && dist > 1) {
                n.vx += (dx / dist) * 0.01;
                n.vy += (dy / dist) * 0.01;
            }
        }

        for (i = 0; i < nodes.length; i++) {
            for (j = i + 1; j < nodes.length; j++) {
                var a = nodes[i];
                var b = nodes[j];
                var ddx = a.x - b.x;
                var ddy = a.y - b.y;
                var d = Math.sqrt(ddx * ddx + ddy * ddy);
                if (d > 130) continue;
                var alpha = (1 - d / 130) * 0.35;
                ctx.beginPath();
                ctx.moveTo(a.x, a.y);
                ctx.lineTo(b.x, b.y);
                ctx.strokeStyle = 'rgba(0, 229, 255,' + alpha + ')';
                ctx.lineWidth = 1;
                ctx.stroke();
            }
        }

        if (Math.random() < 0.04) spawnPulse();
        for (i = pulses.length - 1; i >= 0; i--) {
            var p = pulses[i];
            p.t += p.speed;
            if (p.t >= 1) {
                pulses.splice(i, 1);
                continue;
            }
            var px = p.a.x + (p.b.x - p.a.x) * p.t;
            var py = p.a.y + (p.b.y - p.a.y) * p.t;
            var glow = ctx.createRadialGradient(px, py, 0, px, py, 10);
            glow.addColorStop(0, 'rgba(0, 229, 255, 0.95)');
            glow.addColorStop(1, 'rgba(0, 229, 255, 0)');
            ctx.fillStyle = glow;
            ctx.beginPath();
            ctx.arc(px, py, 10, 0, Math.PI * 2);
            ctx.fill();
        }

        for (i = 0; i < nodes.length; i++) {
            var node = nodes[i];
            ctx.beginPath();
            ctx.arc(node.x, node.y, node.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(0, 229, 255, 0.75)';
            ctx.fill();
        }

        // Core hex pulse behind gallery area
        var cx = w * 0.5;
        var cy = h * 0.58;
        var t = Date.now() * 0.001;
        var radius = 70 + Math.sin(t * 1.4) * 8;
        ctx.beginPath();
        for (i = 0; i < 6; i++) {
            var ang = (Math.PI / 3) * i - Math.PI / 6 + t * 0.15;
            var hx = cx + Math.cos(ang) * radius;
            var hy = cy + Math.sin(ang) * radius;
            if (i === 0) ctx.moveTo(hx, hy);
            else ctx.lineTo(hx, hy);
        }
        ctx.closePath();
        ctx.strokeStyle = 'rgba(0, 229, 255,' + (0.18 + Math.sin(t * 2) * 0.08) + ')';
        ctx.lineWidth = 1.5;
        ctx.stroke();

        raf = window.requestAnimationFrame(step);
    }

    function onMove(e) {
        var rect = canvas.getBoundingClientRect();
        mouse.x = e.clientX - rect.left;
        mouse.y = e.clientY - rect.top;
    }

    function onLeave() {
        mouse.x = -9999;
        mouse.y = -9999;
    }

    function onVisibility() {
        running = !document.hidden;
        if (running && !raf) step();
    }

    resize();
    window.addEventListener('resize', resize);
    canvas.parentElement.addEventListener('mousemove', onMove);
    canvas.parentElement.addEventListener('mouseleave', onLeave);
    document.addEventListener('visibilitychange', onVisibility);
    step();
})();
