// local/moon/amd/src/colormode.js
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
            date.setTime(date.getTime() + (days*24*60*60*1000));
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
            let color_mode = 'light';
            const cmCookieName = 'moon-color-mode-' + templatehash;
            const acm = ('; ' + document.cookie).split(`; ` + cmCookieName + `=`).pop().split(';')[0];

            if (acm === 'light') {
                switchers.forEach(s => { s.checked = false; });
                color_mode = 'light';
            } else if (acm === 'dark') {
                switchers.forEach(s => { s.checked = true; });
                color_mode = 'dark';
            } else if (mode === 'auto') {
                const cur_hour = new Date().getHours();
                if ((24 - cur_hour < 7) || (cur_hour < 6)) {
                    color_mode = 'dark';
                }
                const checked = (color_mode === 'dark');
                switchers.forEach(s => { s.checked = checked; });
            } else {
                color_mode = mode;
            }

            document.documentElement.setAttribute('data-bs-theme', color_mode);

            switchers.forEach(s => {
                s.addEventListener('change', (e) => {
                    const checked = e.target.checked;
                    switchers.forEach(s => { s.checked = checked; });
                    const mode = checked ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-bs-theme', mode);
                    setCookie('moon-color-mode-' + templatehash, mode, 3);
                });
            });
        }
    };
});