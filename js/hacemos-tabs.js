(function () {
    'use strict';

    var MODES = {
        software: {
            caption: 'Pipeline digital BitFlow · en vivo',
            labels: ['Idea', 'Arquitectura', 'Build', 'Deploy', 'Escala'],
            hue: 185
        },
        hardware: {
            caption: 'Línea de armado BitFlow · precisión',
            labels: ['Brief', 'Componentes', 'Armado', 'Stress test', 'Entrega'],
            hue: 195
        },
        soporte: {
            caption: 'Centro de operaciones BitFlow · 24/7',
            labels: ['Monitor', 'Alerta', 'Diagnóstico', 'Fix', 'Uptime'],
            hue: 170
        }
    };

    function initHacemosTabs() {
        var root = document.getElementById('section-hacemos');
        if (!root) return;

        var tabs = root.querySelectorAll('.hacemos-tab');
        var panelsTrack = document.getElementById('hacemos-panels');
        var panels = panelsTrack
            ? Array.prototype.slice.call(panelsTrack.querySelectorAll('.hacemos-panel'))
            : Array.prototype.slice.call(root.querySelectorAll('.hacemos-panel'));
        var viz = document.getElementById('hacemos-viz');
        var canvas = document.getElementById('hacemos-canvas');
        var captionEl = document.getElementById('hacemos-viz-caption');
        var hudChips = viz ? viz.querySelectorAll('.hacemos-viz__chip') : [];
        var dotsWrap = document.getElementById('hacemos-dots');
        if (!tabs.length || !panels.length) return;

        var tabIds = panels.map(function (panel) {
            return panel.getAttribute('data-tab');
        });
        var activeIndex = 0;
        var syncingScroll = false;
        var mode = 'software';
        var ctx = canvas ? canvas.getContext('2d') : null;
        var raf = 0;
        var running = false;
        var dpr = Math.min(window.devicePixelRatio || 1, 2);
        var W = 0;
        var H = 0;
        var packets = [];
        var nodes = [];
        var dots = [];

        if (dotsWrap) {
            panels.forEach(function (_, index) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'hacemos-dot' + (index === 0 ? ' is-active' : '');
                dot.setAttribute('aria-label', 'Ir al servicio ' + (index + 1));
                dot.addEventListener('click', function () {
                    scrollToIndex(index);
                });
                dotsWrap.appendChild(dot);
            });
            dots = Array.prototype.slice.call(dotsWrap.querySelectorAll('.hacemos-dot'));
        }

        function setHud(tabId) {
            var conf = MODES[tabId] || MODES.software;
            mode = tabId;
            if (viz) viz.setAttribute('data-mode', tabId);
            if (captionEl) captionEl.textContent = conf.caption;
            for (var i = 0; i < hudChips.length; i++) {
                hudChips[i].textContent = conf.labels[i] || '';
                hudChips[i].classList.toggle('is-pulse', false);
            }
        }

        function setActiveUI(index) {
            if (index < 0 || index >= panels.length) return;
            activeIndex = index;
            var tabId = tabIds[index];

            for (var i = 0; i < tabs.length; i++) {
                var tab = tabs[i];
                var isActive = tab.getAttribute('data-tab') === tabId;
                tab.classList.toggle('is-active', isActive);
                tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                tab.tabIndex = isActive ? 0 : -1;
            }

            for (var j = 0; j < panels.length; j++) {
                panels[j].classList.toggle('is-active', j === index);
                panels[j].setAttribute('aria-hidden', j === index ? 'false' : 'true');
            }

            for (var d = 0; d < dots.length; d++) {
                dots[d].classList.toggle('is-active', d === index);
            }

            setHud(tabId);
            rebuildNodes();
            burstPackets();
        }

        function scrollToIndex(index, instant) {
            if (!panelsTrack || !panels[index]) return;
            syncingScroll = true;
            setActiveUI(index);
            panelsTrack.scrollTo({
                left: panels[index].offsetLeft,
                behavior: instant ? 'auto' : 'smooth'
            });
            window.setTimeout(function () {
                syncingScroll = false;
            }, instant ? 50 : 420);
        }

        function updateFromScroll() {
            if (!panelsTrack || syncingScroll) return;
            var center = panelsTrack.scrollLeft + panelsTrack.clientWidth / 2;
            var nearest = 0;
            var nearestDist = Infinity;

            for (var i = 0; i < panels.length; i++) {
                var panelCenter = panels[i].offsetLeft + panels[i].clientWidth / 2;
                var dist = Math.abs(panelCenter - center);
                if (dist < nearestDist) {
                    nearestDist = dist;
                    nearest = i;
                }
            }

            if (nearest !== activeIndex) {
                setActiveUI(nearest);
            }
        }

        function activate(tabId) {
            var index = tabIds.indexOf(tabId);
            if (index === -1) index = 0;
            scrollToIndex(index);
        }

        function resize() {
            if (!canvas || !viz) return;
            var rect = viz.getBoundingClientRect();
            W = Math.max(1, Math.floor(rect.width));
            H = Math.max(1, Math.floor(rect.height));
            canvas.width = Math.floor(W * dpr);
            canvas.height = Math.floor(H * dpr);
            canvas.style.width = W + 'px';
            canvas.style.height = H + 'px';
            if (ctx) ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            rebuildNodes();
        }

        function rebuildNodes() {
            nodes = [];
            var n = 5;
            var padX = Math.max(36, W * 0.08);
            var midY = H * 0.52;
            for (var i = 0; i < n; i++) {
                var x = padX + ((W - padX * 2) * i) / (n - 1);
                var wave = Math.sin((i / (n - 1)) * Math.PI) * Math.min(28, H * 0.12);
                nodes.push({
                    x: x,
                    y: midY - wave,
                    r: Math.max(7, Math.min(12, W * 0.014)),
                    phase: i * 0.7
                });
            }
        }

        function burstPackets() {
            packets = [];
            for (var i = 0; i < 8; i++) {
                packets.push({
                    seg: i % Math.max(1, nodes.length - 1),
                    t: Math.random(),
                    speed: 0.18 + Math.random() * 0.22,
                    size: 2 + Math.random() * 2.5
                });
            }
        }

        function drawGrid(now) {
            ctx.save();
            ctx.strokeStyle = 'rgba(0, 229, 255, 0.06)';
            ctx.lineWidth = 1;
            var step = 28;
            var offset = (now * 0.02) % step;
            for (var x = -step + offset; x < W + step; x += step) {
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x, H);
                ctx.stroke();
            }
            for (var y = -step + offset * 0.6; y < H + step; y += step) {
                ctx.beginPath();
                ctx.moveTo(0, y);
                ctx.lineTo(W, y);
                ctx.stroke();
            }
            ctx.restore();
        }

        function drawLinks(now) {
            if (nodes.length < 2) return;
            var conf = MODES[mode] || MODES.software;
            for (var i = 0; i < nodes.length - 1; i++) {
                var a = nodes[i];
                var b = nodes[i + 1];
                var pulse = 0.35 + 0.65 * (0.5 + 0.5 * Math.sin(now * 0.003 + i));
                var grad = ctx.createLinearGradient(a.x, a.y, b.x, b.y);
                grad.addColorStop(0, 'hsla(' + conf.hue + ',100%,60%,' + (0.15 * pulse) + ')');
                grad.addColorStop(0.5, 'hsla(' + conf.hue + ',100%,70%,' + (0.55 * pulse) + ')');
                grad.addColorStop(1, 'hsla(' + conf.hue + ',100%,60%,' + (0.15 * pulse) + ')');
                ctx.beginPath();
                ctx.strokeStyle = grad;
                ctx.lineWidth = 2;
                ctx.moveTo(a.x, a.y);
                var cx = (a.x + b.x) / 2;
                var cy = Math.min(a.y, b.y) - 18;
                ctx.quadraticCurveTo(cx, cy, b.x, b.y);
                ctx.stroke();
            }
        }

        function drawNodes(now) {
            var conf = MODES[mode] || MODES.software;
            var activeIndex = Math.floor((now * 0.0012) % nodes.length);
            for (var i = 0; i < nodes.length; i++) {
                var n = nodes[i];
                var glow = 0.55 + 0.45 * Math.sin(now * 0.004 + n.phase);
                if (i === activeIndex) {
                    if (hudChips[i]) {
                        for (var h = 0; h < hudChips.length; h++) {
                            hudChips[h].classList.toggle('is-pulse', h === i);
                        }
                    }
                }
                ctx.beginPath();
                ctx.fillStyle = 'hsla(' + conf.hue + ',100%,60%,' + (0.12 + 0.18 * glow) + ')';
                ctx.arc(n.x, n.y, n.r * (2.2 + glow * 0.6), 0, Math.PI * 2);
                ctx.fill();

                ctx.beginPath();
                ctx.fillStyle = 'hsla(' + conf.hue + ',100%,70%,0.95)';
                ctx.shadowColor = 'hsla(' + conf.hue + ',100%,60%,0.9)';
                ctx.shadowBlur = 14;
                ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
                ctx.fill();
                ctx.shadowBlur = 0;

                ctx.beginPath();
                ctx.strokeStyle = 'rgba(255,255,255,0.85)';
                ctx.lineWidth = 1.5;
                ctx.arc(n.x, n.y, n.r * 0.45, 0, Math.PI * 2);
                ctx.stroke();
            }
        }

        function drawPackets(dt) {
            if (nodes.length < 2) return;
            var conf = MODES[mode] || MODES.software;
            for (var i = 0; i < packets.length; i++) {
                var p = packets[i];
                p.t += p.speed * dt;
                if (p.t >= 1) {
                    p.t = 0;
                    p.seg = (p.seg + 1) % (nodes.length - 1);
                }
                var a = nodes[p.seg];
                var b = nodes[p.seg + 1];
                var t = p.t;
                var cx = (a.x + b.x) / 2;
                var cy = Math.min(a.y, b.y) - 18;
                var x = (1 - t) * (1 - t) * a.x + 2 * (1 - t) * t * cx + t * t * b.x;
                var y = (1 - t) * (1 - t) * a.y + 2 * (1 - t) * t * cy + t * t * b.y;

                ctx.beginPath();
                ctx.fillStyle = 'hsla(' + conf.hue + ',100%,80%,0.95)';
                ctx.shadowColor = 'hsla(' + conf.hue + ',100%,60%,1)';
                ctx.shadowBlur = 10;
                ctx.arc(x, y, p.size, 0, Math.PI * 2);
                ctx.fill();
                ctx.shadowBlur = 0;
            }
        }

        function drawScan(now) {
            var y = ((now * 0.04) % (H + 40)) - 20;
            var grad = ctx.createLinearGradient(0, y - 18, 0, y + 18);
            grad.addColorStop(0, 'rgba(0,229,255,0)');
            grad.addColorStop(0.5, 'rgba(0,229,255,0.12)');
            grad.addColorStop(1, 'rgba(0,229,255,0)');
            ctx.fillStyle = grad;
            ctx.fillRect(0, y - 18, W, 36);
        }

        var last = performance.now();
        function frame(now) {
            if (!running || !ctx) return;
            var dt = Math.min(0.05, (now - last) / 1000);
            last = now;
            ctx.clearRect(0, 0, W, H);
            drawGrid(now);
            drawScan(now);
            drawLinks(now);
            drawPackets(dt);
            drawNodes(now);
            raf = requestAnimationFrame(frame);
        }

        function start() {
            if (running || !ctx) return;
            running = true;
            last = performance.now();
            raf = requestAnimationFrame(frame);
        }

        function stop() {
            running = false;
            if (raf) cancelAnimationFrame(raf);
            raf = 0;
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

        if (panelsTrack) {
            panelsTrack.addEventListener('scroll', function () {
                window.requestAnimationFrame(updateFromScroll);
            }, { passive: true });

            var swipeStartX = 0;
            var swipeStartY = 0;
            var swipeAxis = null;
            var swipeActive = false;

            panelsTrack.addEventListener('touchstart', function (event) {
                if (!event.touches || !event.touches[0]) return;
                swipeStartX = event.touches[0].clientX;
                swipeStartY = event.touches[0].clientY;
                swipeAxis = null;
                swipeActive = true;
            }, { passive: true });

            panelsTrack.addEventListener('touchmove', function (event) {
                if (!swipeActive || !event.touches || !event.touches[0]) return;
                var dx = event.touches[0].clientX - swipeStartX;
                var dy = event.touches[0].clientY - swipeStartY;

                if (swipeAxis === null) {
                    if (Math.abs(dx) < 10 && Math.abs(dy) < 10) return;
                    swipeAxis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
                }

                // Vertical: dejar que la página scrollee (no interferir)
                if (swipeAxis === 'y') return;
            }, { passive: true });

            panelsTrack.addEventListener('touchend', function (event) {
                if (!swipeActive) return;
                swipeActive = false;

                if (swipeAxis !== 'x') {
                    swipeAxis = null;
                    return;
                }

                var touch = event.changedTouches && event.changedTouches[0];
                if (!touch) {
                    swipeAxis = null;
                    return;
                }

                var dx = touch.clientX - swipeStartX;
                var threshold = Math.min(56, panelsTrack.clientWidth * 0.18);

                if (Math.abs(dx) >= threshold) {
                    if (dx < 0 && activeIndex < panels.length - 1) {
                        scrollToIndex(activeIndex + 1);
                    } else if (dx > 0 && activeIndex > 0) {
                        scrollToIndex(activeIndex - 1);
                    }
                }

                swipeAxis = null;
            }, { passive: true });

            panelsTrack.addEventListener('touchcancel', function () {
                swipeActive = false;
                swipeAxis = null;
            }, { passive: true });
        }

        window.addEventListener('resize', function () {
            resize();
            if (panelsTrack && panels[activeIndex]) {
                syncingScroll = true;
                panelsTrack.scrollTo({
                    left: panels[activeIndex].offsetLeft,
                    behavior: 'auto'
                });
                window.setTimeout(function () {
                    syncingScroll = false;
                }, 50);
            }
        });

        if (typeof IntersectionObserver !== 'undefined' && viz) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) start();
                    else stop();
                });
            }, { threshold: 0.15 });
            io.observe(root);
        } else {
            start();
        }

        setActiveUI(0);
        resize();
        burstPackets();
        start();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHacemosTabs);
    } else {
        initHacemosTabs();
    }
})();
