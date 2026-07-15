/*
 * BitFlow · Globo holográfico de ubicación
 * Reemplaza el antiguo mapa de Leaflet por un planeta animado en canvas,
 * en sintonía con la estética neón de la sección de contacto.
 */
(function () {
    'use strict';

    var CYAN = '0, 229, 255';
    var BLUE = '0, 130, 220';

    // Ubicación real de BitFlow (Villa Lugano, CABA)
    var SITE_LAT = -34.679276;
    var SITE_LON = -58.489494;

    // Máscara equirectangular de tierra/océano (200x100, 1 bit por celda,
    // empaquetada LSB-first). Permite dibujar los continentes reales.
    var LAND_W = 200, LAND_H = 100;
    var LAND_B64 = 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAPjvMPsRAAAAAAAAAAAAAAAAAAAAAAAAAJD3j/3//wEAwAcAAAAAAAAAAAAAAAAAAABQ4PH///8AADABAAAAAAgAAAAAAAAAAACTvJPg//9/AgAAAAAAAwDgAwAAAAAAAAAAIAB4AOD//wAAAAAAEADwTwMAAAAAAAAAgDuxfQDA/78AAAAAAAQA3n//AAAAAAAAAAD8w/wfAP+vAAAACAAEbv///+9uDIAA/n/7o2ZkPAD/PwAAgDeAwKz3/////8X5B/7/v3+fd+LA/wEAAPD/l//v////////957///+e/xs4wT+ACgD4HPz///////////8A+P9/du9D8IAfgAIAbB7+//7v//////9/AP///89/gA4ADgAAgBsv///n3///////H4B+8///bwAeAAgAAIA/wv//+///////wwUAIAD+//kAvgEAAACCHf/9////////AzAAAAAA+v/vB/4BAAAAAhr//////////wE4AAAAAPD/aj/2BwAAgAaA/////7///n8BPAAAAADw/99y/x8AAICN////////f///AQgAAAAA4P/vf/MHAAAA3P//3////////wsAAAAAAMD/f/99EAAAAPD///////v///8LAAAAAACA//fh/yAAAAD4/3//////////AQAAAAAAgP//j/8BAAAA+Pcffs7//f///wAAAAAAAID//5edAAAAAPj0DzzP/////38IAAAAAACA//83DwAAAIA/zAd4/r////8PCAAAAAAAgP///wcAAACAD5Dw//7///9/BwQAAAAAAID///8BAAAAgA8AmXf8////HwYGAAAAAAAA////AQAAAACDA/H//P//938MAwAAAAAAAP7//wEAAAAA+gNE//////8/ZAEAAAAAAAD4//8AAAAAgP8DgP//////fxAAAAAAAAAA6P9/AAAAAID/n6f//////38AAAAAAAAAAPD/QgAAAADA////f//////vAAAAAAAAAACAP0AAAAAA4P//f3/+////fwAAAAAAAAAAgD8AAAAAAPD//3//8P///z8AAAAAAAAAAEA/AAAAAAD4/////gXyv/+fAAAAAAAAAAAAPqAAAAAA+P////wf+L//BwAAAAAAAAAAADwMAgAAAPj////9D/CPfwAAAAAAAAAAAAB8BjAAAAD4////+Q/wB38BAAAAAAAAAAAA8AcAAAAA+P////MD4AN/gAAAAAAAAAAAAAAfAAAAAPj////zAOAAvAAAAAAAAAAAAAAAHAAAAAD4//f/NwDgAPgBAQAAAAAAAAAAAAAAAAAA8P///wcAwADgAQIAAAAAAAAAAAAg/AMAAPD/////AMAAQAAAAAAAAAAAAAAAAP8GAADg/////QAAAQgAAwAAAAAAAAAAAAD+DwAAwJ///38AAAEQAAIAAAAAAAAAAAAA/n8AAACA/v9/AAAAEjgAAAAAAAAAAAAAAP7/AAAAAP7/PwAAACwcAAAAAAAAAAAAAAD//wAAAAD+/w8AAAAYnwEAAAAAAAAAAACA/38DAAAA/L8HAAAAEN6hAAAAAAAAAAAAAP/vBwAAAPz/AwAAAHBeQAMAAAAAAAAAAID//34AAAD4/wMAAABgwAAfAQAAAAAAAACA////AQAA+O8DAAAAgABAPgAAAAAAAAAAAP///wEAAPj/AwAAAAAEACwYAAAAAAAAAAD//+8AAAD4/wMAAAAAAABAAAAAAAAAAAAA/v9/AAAA8P8DAAAAAAAAAAAAAAAAAAAAAPz/PwAAAPj/gwAAAAAAcAgAAAAAAAAAAAD8/z8AAAD4/8cAAAAAAP4YAAAAAAAAAAAA8P8/AAAA/PdhAAAAAAD+HQAAAAAAAAAAAOD/PwAAAPj/YAAAAAAA/x8AAAAAAAAAAADg/x8AAAD4f3AAAAAA4P9/gAAAAAAAAAAA4P8fAAAA8P9wAAAAAPj//wAAAAAAAAAAAOD/AwAAAPD/MAAAAAD4//8AAAAAAAAAAADg/wEAAADwPwAAAAAA+P//AQAAAAAAAAAA8P8BAAAA4D8AAAAAAPj//wEAAAAAAAAAAPD/AAAAAOAfAAAAAADw//8BAAAAAAAAAADwfwAAAADADwAAAAAA8P//AQAAAAAAAAAA8DMAAAAAwAcAAAAAAPCB/gAAAAAAAAAAAPAPAAAAAAAAAAAAAAAAAP4AEAAAAAAAAAD4DwAAAAAAAAAAAAAAAAB8AAAAAAAAAAAA+AcAAAAAAAAAAAAAAAAAAABgAAAAAAAAAPgBAAAAAAAAAAAAAAAAAAAAIAAAAAAAAADwAQAAAAAAAAAAAAAAAAAgAAgAAAAAAAAA+AAAAAAAAAAAAAAAAAAAAAAGAAAAAAAAADwAAAAAAAAAAAAAAAAAAAAAAgAAAAAAAAB4AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAYAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAACAAAAAAAAAAAAAAAAIAAAAAAAAAAgL8A/P///x8AAAAAAAAAAACQAQAAAAAAgPz/g///////DwAAAAAAAAAAuAMAAEDh//////f///////8BAAAAAAAgBMQDAAD4////////////////AwAAAOFf8P//AQCA////////////////PwAAAPj/////HwAA8P///////////////z8AAOD/////PwAAB/////////////////9/AAAA/P////8ByAf6////////////////HwAAwP//////D4D//////////////////z8AAID/////////////////////////////B/8H/v///////////////////////////////////////////////////////////////////////////////////////////////w==';
    var landBytes = null;

    function decodeLand() {
        if (landBytes) return;
        try {
            var bin = atob(LAND_B64);
            landBytes = new Uint8Array(bin.length);
            for (var i = 0; i < bin.length; i++) landBytes[i] = bin.charCodeAt(i);
        } catch (e) { landBytes = new Uint8Array(0); }
    }

    function isLand(latDeg, lonDeg) {
        if (!landBytes || !landBytes.length) return false;
        var xi = Math.floor((lonDeg + 180) / 360 * LAND_W);
        var yi = Math.floor((90 - latDeg) / 180 * LAND_H);
        if (xi < 0) xi = 0; else if (xi >= LAND_W) xi = LAND_W - 1;
        if (yi < 0) yi = 0; else if (yi >= LAND_H) yi = LAND_H - 1;
        var idx = yi * LAND_W + xi;
        return ((landBytes[idx >> 3] >> (idx & 7)) & 1) === 1;
    }

    function initGlobe() {
        var canvas = document.getElementById('geo-globe');
        if (!canvas || !canvas.getContext) return;

        var ctx = canvas.getContext('2d');
        var reduceMotion = window.matchMedia &&
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var W = 0, H = 0, cx = 0, cy = 0, R = 0, dpr = 1;
        var rot = 0;            // rotación automática (rad)
        var pointerRot = 0;     // rotación extra por arrastre del usuario
        var spin = 0;           // inercia tras soltar el arrastre
        var raf = null;
        var visible = true;
        var stars = [];
        var landPoints = [];    // vectores unitarios de los continentes

        function buildLandPoints() {
            decodeLand();
            landPoints = [];
            var latStep = 2.5;
            for (var lat = -88; lat <= 88; lat += latStep) {
                var cosLat = Math.cos(lat * Math.PI / 180);
                var lonCount = Math.max(6, Math.round(108 * cosLat));
                var lonStep = 360 / lonCount;
                for (var k = 0; k < lonCount; k++) {
                    var lon = -180 + k * lonStep;
                    if (!isLand(lat, lon)) continue;
                    var la = lat * Math.PI / 180;
                    var lo = lon * Math.PI / 180;
                    var cl = Math.cos(la);
                    landPoints.push({
                        x: cl * Math.sin(lo),
                        y: Math.sin(la),
                        z: cl * Math.cos(lo)
                    });
                }
            }
        }

        function resize() {
            var rect = canvas.getBoundingClientRect();
            if (!rect.width || !rect.height) return;
            dpr = Math.min(window.devicePixelRatio || 1, 2);
            W = Math.round(rect.width);
            H = Math.round(rect.height);
            canvas.width = W * dpr;
            canvas.height = H * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            // En pantallas anchas, corremos el globo a la derecha para dejar
            // espacio a la tarjeta de info; en mobile lo ubicamos arriba
            // (la tarjeta queda debajo).
            var wide = W > 720;
            cx = wide ? W * 0.68 : W * 0.5;
            cy = wide ? H * 0.5 : H * 0.25;
            R = wide ? Math.min(H * 0.4, W * 0.32) : Math.min(H * 0.235, W * 0.30);
            R = Math.max(R, 50);
            buildStars();
            if (reduceMotion) draw(performance.now());
        }

        function buildStars() {
            stars = [];
            var count = Math.round((W * H) / 7000);
            count = Math.min(count, 160);
            for (var i = 0; i < count; i++) {
                stars.push({
                    x: Math.random() * W,
                    y: Math.random() * H,
                    r: Math.random() * 1.1 + 0.25,
                    a: Math.random() * 0.5 + 0.2,
                    tw: Math.random() * Math.PI * 2
                });
            }
        }

        // Proyección ortográfica de (lat, lon) sobre la esfera girada.
        function project(latDeg, lonDeg, rotation) {
            var lat = latDeg * Math.PI / 180;
            var lon = lonDeg * Math.PI / 180 + rotation;
            var cosLat = Math.cos(lat);
            var x = cosLat * Math.sin(lon);
            var y = Math.sin(lat);
            var z = cosLat * Math.cos(lon);
            return { sx: cx + x * R, sy: cy - y * R, z: z };
        }

        function drawStars(now) {
            for (var i = 0; i < stars.length; i++) {
                var s = stars[i];
                var tw = 0.55 + 0.45 * Math.sin(now * 0.002 + s.tw);
                ctx.beginPath();
                ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(190, 240, 255, ' + (s.a * tw).toFixed(3) + ')';
                ctx.fill();
            }
        }

        function drawOrbitRing(now) {
            var tilt = -0.42;
            var a = R * 1.32;
            var b = R * 0.42;
            ctx.save();
            ctx.translate(cx, cy);
            ctx.rotate(tilt);
            ctx.beginPath();
            ctx.ellipse(0, 0, a, b, 0, 0, Math.PI * 2);
            ctx.strokeStyle = 'rgba(' + CYAN + ', 0.22)';
            ctx.lineWidth = 1;
            ctx.setLineDash([3, 9]);
            ctx.stroke();
            ctx.restore();

            // Satélite recorriendo el anillo
            var ang = now * 0.0006;
            var ex = Math.cos(ang) * a;
            var ey = Math.sin(ang) * b;
            var sx = cx + ex * Math.cos(tilt) - ey * Math.sin(tilt);
            var sy = cy + ex * Math.sin(tilt) + ey * Math.cos(tilt);
            ctx.save();
            ctx.shadowColor = 'rgba(' + CYAN + ', 0.9)';
            ctx.shadowBlur = 12;
            ctx.beginPath();
            ctx.arc(sx, sy, 3, 0, Math.PI * 2);
            ctx.fillStyle = '#eafcff';
            ctx.fill();
            ctx.restore();
        }

        function drawSphere() {
            var g = ctx.createRadialGradient(
                cx - R * 0.35, cy - R * 0.35, R * 0.05,
                cx, cy, R
            );
            g.addColorStop(0, 'rgba(' + CYAN + ', 0.16)');
            g.addColorStop(0.55, 'rgba(' + BLUE + ', 0.08)');
            g.addColorStop(1, 'rgba(0, 20, 45, 0.02)');
            ctx.beginPath();
            ctx.arc(cx, cy, R, 0, Math.PI * 2);
            ctx.fillStyle = g;
            ctx.fill();

            // Halo exterior
            ctx.beginPath();
            ctx.arc(cx, cy, R * 1.02, 0, Math.PI * 2);
            ctx.strokeStyle = 'rgba(' + CYAN + ', 0.18)';
            ctx.lineWidth = 1.5;
            ctx.setLineDash([]);
            ctx.stroke();
        }

        function drawLandDots(rotation) {
            var cosR = Math.cos(rotation);
            var sinR = Math.sin(rotation);
            for (var i = 0; i < landPoints.length; i++) {
                var p = landPoints[i];
                var z = p.z * cosR - p.x * sinR;
                if (z < -0.06) continue; // cara oculta del planeta
                var x = p.x * cosR + p.z * sinR;
                var depth = (z + 1) / 2;
                var size = 0.55 + depth * 1.5;
                var alpha = 0.14 + depth * 0.66;
                ctx.beginPath();
                ctx.arc(cx + x * R, cy - p.y * R, size, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(' + CYAN + ', ' + alpha.toFixed(3) + ')';
                ctx.fill();
            }
        }

        function drawBeacon(rotation, now) {
            var p = project(SITE_LAT, SITE_LON, rotation);
            if (p.z <= 0.02) return; // en la cara oculta: no se dibuja
            var depth = (p.z + 1) / 2;
            var pulse = (Math.sin(now * 0.004) + 1) / 2;

            // Anillos que se expanden
            for (var r = 0; r < 2; r++) {
                var phase = (pulse + r * 0.5) % 1;
                ctx.beginPath();
                ctx.arc(p.sx, p.sy, 5 + phase * 18, 0, Math.PI * 2);
                ctx.strokeStyle = 'rgba(' + CYAN + ', ' + (0.45 * (1 - phase)).toFixed(3) + ')';
                ctx.lineWidth = 1.5;
                ctx.setLineDash([]);
                ctx.stroke();
            }

            // Núcleo brillante
            ctx.save();
            ctx.shadowColor = 'rgba(' + CYAN + ', 0.95)';
            ctx.shadowBlur = 20;
            ctx.beginPath();
            ctx.arc(p.sx, p.sy, 3.5 + depth * 1.5, 0, Math.PI * 2);
            ctx.fillStyle = '#f0fdff';
            ctx.fill();
            ctx.restore();
        }

        function draw(now) {
            ctx.clearRect(0, 0, W, H);
            drawStars(now);
            drawOrbitRing(now);
            drawSphere();
            var rotation = rot + pointerRot;
            drawLandDots(rotation);
            drawBeacon(rotation, now);
        }

        function frame(now) {
            if (!reduceMotion) {
                if (Math.abs(spin) > 0.00005) {
                    pointerRot += spin;
                    spin *= 0.94;
                }
                rot += 0.0022;
            }
            draw(now);
            raf = requestAnimationFrame(frame);
        }

        function start() {
            if (raf || reduceMotion) return;
            raf = requestAnimationFrame(frame);
        }

        function stop() {
            if (raf) { cancelAnimationFrame(raf); raf = null; }
        }

        // Interacción: arrastrar para girar el planeta
        var dragging = false, lastX = 0, lastT = 0;
        canvas.addEventListener('pointerdown', function (e) {
            dragging = true; lastX = e.clientX; lastT = e.timeStamp; spin = 0;
            if (canvas.setPointerCapture) { try { canvas.setPointerCapture(e.pointerId); } catch (err) {} }
        });
        canvas.addEventListener('pointermove', function (e) {
            if (!dragging) return;
            var dx = e.clientX - lastX;
            var dt = Math.max(1, e.timeStamp - lastT);
            pointerRot += dx * 0.006;
            spin = (dx * 0.006) / dt * 16;
            lastX = e.clientX; lastT = e.timeStamp;
        });
        function endDrag() { dragging = false; }
        canvas.addEventListener('pointerup', endDrag);
        canvas.addEventListener('pointercancel', endDrag);
        window.addEventListener('pointerup', endDrag);

        window.addEventListener('resize', resize);

        // Pausar la animación cuando la sección no está en pantalla
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                visible = entries[0].isIntersecting;
                if (visible) { resize(); start(); } else { stop(); }
            }, { threshold: 0.05 });
            io.observe(canvas);
        }

        buildLandPoints();
        resize();
        start();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGlobe);
    } else {
        initGlobe();
    }
})();
