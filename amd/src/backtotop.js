// javascript
define([], function() {
    let initialized = false;
    let _cleanup = null;

    return {
        /**
         * Initialize the back to top button
         * @param {number} enable 0 to disable, 1 to enable
         */
        init: function(enable = 0) {
            if (!enable || initialized) {
                return;
            }
            initialized = true;

            /**
             * Set up the back to top button
             */
            function setup() {
                const btn = document.getElementById('moon-backtotop');
                if (!btn) {
                    return;
                }

                const fadeDuration = 200; // ms
                btn.style.transition = `opacity ${fadeDuration}ms`;
                btn.style.opacity = '0';
                btn.style.display = 'none';
                btn.style.pointerEvents = 'none';

                /**
                 * Show the button
                 */
                function show() {
                    if (btn.style.display !== 'block') {
                        btn.style.display = 'block';
                        // force reflow so transition always runs
                        // eslint-disable-next-line @babel/no-unused-expressions
                        btn.offsetHeight;
                    }
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                }

                /**
                 * Hide the button
                 */
                function hide() {
                    btn.style.opacity = '0';
                    btn.style.pointerEvents = 'none';
                    setTimeout(() => {
                        if (btn.style.opacity === '0') {
                            btn.style.display = 'none';
                        }
                    }, fadeDuration);
                }

                let ticking = false;

                /**
                 * Handle scroll events
                 */
                function onScroll() {
                    if (ticking) {
                        return;
                    }
                    ticking = true;
                    requestAnimationFrame(() => {
                        if (window.scrollY >= 200) {
                            show();
                        } else {
                            hide();
                        }
                        ticking = false;
                    });
                }

                /**
                 * Handle button click
                 * @param {Event} e The click event
                 */
                function onClick(e) {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                window.addEventListener('scroll', onScroll, { passive: true });
                btn.addEventListener('click', onClick);

                // initialize based on current scroll position
                onScroll();

                _cleanup = function() {
                    window.removeEventListener('scroll', onScroll);
                    btn.removeEventListener('click', onClick);
                    _cleanup = null;
                    initialized = false;
                };
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setup, { once: true });
            } else {
                setup();
            }
        },

        /**
         * Remove listeners and reset initialization state
         */
        destroy: function() {
            if (typeof _cleanup === 'function') {
                _cleanup();
            }
        }
    };
});
