// local/moon/amd/src/preloader.js
define([], function() {
    return {
        /**
         * Initialize the preloader fade out
         * @param {number|string} duration Duration in milliseconds (number) or a CSS time string (e.g. "400ms" or "0.4s")
         */
        init: function(duration) {
            /**
             * Normalize the duration parameter to a CSS time string and a number of milliseconds.
             * @param {number|string} d
             * @returns {{css: string, ms: number}|{css: string, ms: number}}
             */
            function normalizeDuration(d) {
                let defaultMs = 400;
                if (typeof d === 'number' && isFinite(d)) {
                    return { css: d + 'ms', ms: d };
                }
                if (typeof d === 'string') {
                    let s = d.trim();
                    if (/^\d+$/.test(s)) {
                        let n = parseInt(s, 10);
                        return { css: n + 'ms', ms: n };
                    }
                    if (/^\d+ms$/.test(s)) {
                        return { css: s, ms: parseInt(s, 10) };
                    }
                    if (/^\d+(\.\d+)?s$/.test(s)) {
                        let sec = parseFloat(s);
                        return { css: s, ms: Math.round(sec * 1000) };
                    }
                }
                return { css: defaultMs + 'ms', ms: defaultMs };
            }

            let dur = normalizeDuration(duration);

            /**
             * Run the preloader fade out
             */
            function run() {
                const preloader = document.getElementById('moon-preloader');
                if (!preloader) {
                    return;
                }

                // ensure visible and reset any previous inline styles
                preloader.classList.remove('d-none');
                preloader.classList.add('d-flex');
                preloader.style.opacity = '1';
                preloader.style.transition = 'opacity ' + dur.css + ' ease';

                // trigger fade out on next frame
                requestAnimationFrame(function() {
                    preloader.style.opacity = '0';
                });

                // when transition ends, fully hide and clean up
                const onEnd = function() {
                    preloader.classList.remove('d-flex');
                    preloader.classList.add('d-none');
                    preloader.style.opacity = '';
                    preloader.style.transition = '';
                };

                // use { once: true } to auto-remove the listener
                preloader.addEventListener('transitionend', onEnd, { once: true });

                // fallback: ensure it's hidden even if transitionend doesn't fire
                setTimeout(function() {
                    if (preloader && getComputedStyle(preloader).opacity === '0') {
                        onEnd();
                    }
                }, dur.ms + 200);
            }

            if (document.readyState === 'complete') {
                run();
            } else {
                window.addEventListener('load', run);
            }
        }
    };
});
