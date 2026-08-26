// local/moon/amd/src/colortransform.js
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