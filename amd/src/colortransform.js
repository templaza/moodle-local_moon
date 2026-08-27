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
    /**
     * Color transform function
     * @param {string} from The from color mode
     * @param {string} to The to color mode
     * @param {number} offset The scroll offset percentage
     * @returns {void}
     */
    function colorTransform(from, to, offset) {
        const offsetFactor = Math.max(0, Math.min(100, Number(offset || 100))) / 100;
        const reached = (window.innerHeight + window.scrollY) >= (document.body.scrollHeight * offsetFactor);
        const html = document.documentElement;
        const theme = html.getAttribute('data-bs-theme');

        if (reached) {
            if (theme === from) {
                html.setAttribute('data-bs-theme', to);
            }
        } else {
            if (theme === to) {
                html.setAttribute('data-bs-theme', from);
            }
        }
    }
    return {
        /**
         * Initialize the color mode switcher
         * @param {string} from The from color mode
         * @param {string} to The to color mode
         * @param {number} offset The scroll offset percentage
         * @returns {void}
         */
        init: function(from, to, offset) {
            const normalizedOffset = Math.max(0, Math.min(100, Number(offset || 100)));

            /**
             * Ensure the body has the transition class
             * @returns {void}
             */
            function ensureBodyClass() {
                if (document.body) {
                    document.body.classList.add('as-transition-body');
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    ensureBodyClass();
                    colorTransform(from, to, normalizedOffset);
                });
            } else {
                ensureBodyClass();
                colorTransform(from, to, normalizedOffset);
            }

            // Attach a single passive scroll listener and also listen to resize
            const handler = () => colorTransform(from, to, normalizedOffset);
            window.addEventListener('scroll', handler, { passive: true });
            window.addEventListener('resize', handler, { passive: true });
        }
    };
});