// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
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
