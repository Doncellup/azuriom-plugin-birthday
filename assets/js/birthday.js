/* Birthday Rewards - page helpers (no dependency) */
window.birthday = (function () {
    'use strict';

    // Small confetti burst, drawn on a temporary canvas above the page.
    function confetti() {
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        var colors = ['#e8590c', '#d4a017', '#0d6efd', '#198754', '#dc3545', '#6f42c1', '#20c997'];
        var pieces = [];
        var start = null;
        var duration = 2800;

        canvas.style.cssText = 'position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:2000';
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        document.body.appendChild(canvas);

        for (var i = 0; i < 160; i++) {
            pieces.push({
                x: canvas.width / 2 + (Math.random() - 0.5) * canvas.width * 0.3,
                y: canvas.height * 0.35,
                vx: (Math.random() - 0.5) * 14,
                vy: -Math.random() * 13 - 4,
                size: 5 + Math.random() * 6,
                rotation: Math.random() * Math.PI,
                spin: (Math.random() - 0.5) * 0.3,
                color: colors[i % colors.length]
            });
        }

        function frame(time) {
            if (start === null) {
                start = time;
            }

            var elapsed = time - start;
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.globalAlpha = Math.max(0, 1 - elapsed / duration);

            pieces.forEach(function (p) {
                p.vy += 0.35;
                p.vx *= 0.99;
                p.x += p.vx;
                p.y += p.vy;
                p.rotation += p.spin;

                context.save();
                context.translate(p.x, p.y);
                context.rotate(p.rotation);
                context.fillStyle = p.color;
                context.fillRect(-p.size / 2, -p.size / 4, p.size, p.size / 2);
                context.restore();
            });

            if (elapsed < duration) {
                window.requestAnimationFrame(frame);
            } else {
                canvas.remove();
            }
        }

        window.requestAnimationFrame(frame);
    }

    return {
        confetti: confetti,
        init: function (options) {
            if (options && options.celebrate) {
                confetti();
            }
        }
    };
})();
