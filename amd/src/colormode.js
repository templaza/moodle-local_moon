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
 * @module    local_moon/colormode
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
define([], function() {
    /**
     * Set a cookie
     * @param {string} name The cookie name
     * @param {string} value The cookie value
     * @param {number} days Expiry in days
     */
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + encodeURIComponent(value || "") + expires + "; path=/";
    }

    return {
        /**
         * Initialize the color mode switcher
         * @param {string} mode The cookie name
         * @param {string} templatehash The template hash
         */
        init: function(mode, templatehash) {
            const switchers = Array.from(document.querySelectorAll('.moon-color-mode .switcher'));
            let colorMode = 'light';
            const cmCookieName = 'moon-color-mode-' + templatehash;
            const acm = ('; ' + document.cookie).split(`; ` + cmCookieName + `=`).pop().split(';')[0];

            if (acm === 'light') {
                switchers.forEach(s => {
                    s.checked = false;
                });
                colorMode = 'light';
            } else if (acm === 'dark') {
                switchers.forEach(s => {
                    s.checked = true;
                });
                colorMode = 'dark';
            } else if (mode === 'auto') {
                const curHour = new Date().getHours();
                if ((24 - curHour < 7) || (curHour < 6)) {
                    colorMode = 'dark';
                }
                const checked = (colorMode === 'dark');
                switchers.forEach(s => {
                    s.checked = checked;
                });
            } else {
                colorMode = mode;
            }

            document.documentElement.setAttribute('data-bs-theme', colorMode);

            switchers.forEach(s => {
                s.addEventListener('change', (e) => {
                    const checked = e.target.checked;
                    switchers.forEach(s => {
                        s.checked = checked;
                    });
                    const mode = checked ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-bs-theme', mode);
                    setCookie('moon-color-mode-' + templatehash, mode, 3);
                });
            });
        }
    };
});