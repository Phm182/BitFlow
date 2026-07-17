(function () {
    'use strict';

    function readProjects() {
        var cards = document.querySelectorAll('#proyectos-track .Proy');
        var list = [];

        for (var i = 0; i < cards.length; i++) {
            var card = cards[i];
            var titleEl = card.querySelector('h3');
            var iconEl = card.querySelector('.proy-icon i');
            var name = titleEl ? titleEl.textContent.replace(/\s+/g, ' ').trim() : ('Proyecto ' + (i + 1));
            var iconClass = 'fas fa-cube';

            if (iconEl && iconEl.className) {
                iconClass = iconEl.className;
            }

            list.push({
                name: name,
                shortName: name.split(' ')[0],
                iconClass: iconClass,
                card: card
            });
        }

        return list;
    }

    function targetX(index, total) {
        if (total <= 1) return 0.78;
        var start = 0.56;
        var end = 0.9;
        return start + ((end - start) * index) / (total - 1);
    }

    function initProyectosForge() {
        var root = document.getElementById('proyectos-forge');
        var canvas = document.getElementById('proyectos-forge-canvas');
        var outputs = document.getElementById('proyectos-forge-outputs');
        var track = document.getElementById('proyectos-track');
        if (!root || !canvas || !outputs) return;

        var projects = readProjects();
        if (!projects.length) {
            root.style.display = 'none';
            return;
        }

        outputs.innerHTML = '';
        projects.forEach(function (project, index) {
            var chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'proyectos-forge__chip' + (index === 0 ? ' is-on' : '');
            chip.setAttribute('data-forge', String(index));
            chip.setAttribute('aria-label', project.name);
            chip.innerHTML = '<i class="' + project.iconClass + '" aria-hidden="true"></i> <span>' + project.shortName + '</span>';
            outputs.appendChild(chip);
        });

        var ctx = canvas.getContext('2d');
        if (!ctx) return;

        var chips = outputs.querySelectorAll('.proyectos-forge__chip');
        var dpr = Math.min(window.devicePixelRatio || 1, 2);
        var W = 0;
        var H = 0;
        var raf = 0;
        var running = false;
        var sparks = [];
        var active = 0;
        var nextSwitch = 0;
        var total = projects.length;

        function resize() {
            var rect = root.getBoundingClientRect();
            W = Math.max(1, Math.floor(rect.width));
            H = Math.max(1, Math.floor(rect.height));
            canvas.width = Math.floor(W * dpr);
            canvas.height = Math.floor(H * dpr);
            canvas.style.width = W + 'px';
            canvas.style.height = H + 'px';
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }

        function scrollCardIntoView(index) {
            if (!track || !projects[index] || !projects[index].card) return;
            if (!(window.matchMedia && window.matchMedia('(max-width: 770px)').matches)) return;
            var card = projects[index].card;
            var left = card.offsetLeft - (track.clientWidth - card.clientWidth) / 2;
            track.scrollTo({ left: Math.max(0, left), behavior: 'smooth' });
        }

        function setActive(i) {
            if (i < 0 || i >= total) return;
            active = i;
            for (var c = 0; c < chips.length; c++) {
                chips[c].classList.toggle('is-on', c === i);
            }
        }

        function spawnSpark(now) {
            var x0 = W * 0.26;
            var y0 = H * 0.55;
            var tx = W * targetX(active, total);
            var ty = H * 0.42;
            sparks.push({
                x: x0,
                y: y0,
                tx: tx,
                ty: ty,
                t: 0,
                speed: 0.9 + Math.random() * 0.5,
                born: now
            });
            if (sparks.length > 18) sparks.shift();
        }

        function drawIsoGrid(now) {
            ctx.save();
            ctx.strokeStyle = 'rgba(0,229,255,0.06)';
            ctx.lineWidth = 1;
            var offset = (now * 0.02) % 28;
            for (var i = -6; i < 18; i++) {
                var x = i * 28 + offset;
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x + H * 0.55, H);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(W - x, 0);
                ctx.lineTo(W - x - H * 0.55, H);
                ctx.stroke();
            }
            ctx.restore();
        }

        function drawBeam(now) {
            var x0 = W * 0.32;
            var y0 = H * 0.58;
            var x1 = W * targetX(active, total);
            var y1 = H * 0.4;
            var pulse = 0.45 + 0.55 * (0.5 + 0.5 * Math.sin(now * 0.006));

            var grad = ctx.createLinearGradient(x0, y0, x1, y1);
            grad.addColorStop(0, 'rgba(0,229,255,0)');
            grad.addColorStop(0.35, 'rgba(0,229,255,' + (0.35 * pulse) + ')');
            grad.addColorStop(1, 'rgba(124,255,178,' + (0.7 * pulse) + ')');

            ctx.beginPath();
            ctx.strokeStyle = grad;
            ctx.lineWidth = 2;
            ctx.moveTo(x0, y0);
            ctx.quadraticCurveTo((x0 + x1) / 2, Math.min(y0, y1) - 18, x1, y1);
            ctx.stroke();

            ctx.beginPath();
            ctx.fillStyle = 'rgba(124,255,178,0.9)';
            ctx.shadowColor = 'rgba(0,229,255,1)';
            ctx.shadowBlur = 12;
            ctx.arc(x1, y1, 3.5, 0, Math.PI * 2);
            ctx.fill();
            ctx.shadowBlur = 0;
        }

        function drawSparks(dt, now) {
            if (Math.random() < 0.12) spawnSpark(now);

            for (var i = sparks.length - 1; i >= 0; i--) {
                var s = sparks[i];
                s.t += s.speed * dt;
                if (s.t >= 1) {
                    sparks.splice(i, 1);
                    continue;
                }
                var t = s.t;
                var cx = (s.x + s.tx) / 2;
                var cy = Math.min(s.y, s.ty) - 18;
                var x = (1 - t) * (1 - t) * s.x + 2 * (1 - t) * t * cx + t * t * s.tx;
                var y = (1 - t) * (1 - t) * s.y + 2 * (1 - t) * t * cy + t * t * s.ty;

                ctx.beginPath();
                ctx.fillStyle = 'rgba(0,229,255,' + (0.9 - t * 0.6) + ')';
                ctx.arc(x, y, 2, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        var last = performance.now();
        function frame(now) {
            if (!running) return;
            var dt = Math.min(0.05, (now - last) / 1000);
            last = now;

            if (now > nextSwitch) {
                setActive((active + 1) % total);
                nextSwitch = now + 2600;
            }

            ctx.clearRect(0, 0, W, H);
            drawIsoGrid(now);
            drawBeam(now);
            drawSparks(dt, now);
            raf = requestAnimationFrame(frame);
        }

        function start() {
            if (running) return;
            running = true;
            nextSwitch = performance.now() + 2600;
            last = performance.now();
            raf = requestAnimationFrame(frame);
        }

        function stop() {
            running = false;
            if (raf) cancelAnimationFrame(raf);
            raf = 0;
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                var i = parseInt(chip.getAttribute('data-forge'), 10);
                if (!isNaN(i)) {
                    setActive(i);
                    scrollCardIntoView(i);
                    nextSwitch = performance.now() + 3200;
                }
            });
        });

        window.addEventListener('resize', function () {
            resize();
            if (!running) start();
        });

        if (typeof IntersectionObserver !== 'undefined') {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) start();
                    else stop();
                });
            }, { threshold: 0.15 });
            io.observe(root);
        }

        setActive(0);
        resize();
        start();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProyectosForge);
    } else {
        initProyectosForge();
    }
})();
