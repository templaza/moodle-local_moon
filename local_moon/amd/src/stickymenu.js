// local/moon/amd/src/stickymenu.js
define([], function () {
    /**
     * Sticky Menu function
     * @param {HTMLElement} el The element to get the offset top for
     * @returns {number} The offset top of the element
     */
    function getOffsetTop(el) {
        return el.getBoundingClientRect().top + window.scrollY;
    }

    /**
     * Init header
     * @param {string} container The container element
     * @return {void}
     */
    function initHeader(container) {
        const header = document.getElementById('moon-header');
        if (!header) {
            return;
        }

        const wrap = header.querySelector('.header-wrap');
        const headerTop = getOffsetTop(header);
        const headerHeight = header.offsetHeight;
        const headerBottom = headerTop + headerHeight + 30;
        const winScroll = window.scrollY;

        if (winScroll > headerBottom) {
            header.classList.add('sticky-header-active');
            header.style.paddingTop = headerHeight + 'px';
            header.style.setProperty('--mf-sticky-header-mark-height', headerHeight + 'px');
            if (container) {
                wrap.classList.add(container);
            }
        } else {
            header.classList.remove('sticky-header-active');
            header.style.paddingTop = '';
            header.style.removeProperty('--mf-sticky-header-mark-height');
            if (container) {
                wrap.classList.remove(container);
            }
        }
    }
    return {
        /**
         * Initialize the sticky menu
         * @param {string} container The container element
         * @returns {void}
         */
        init: function(container) {
            document.addEventListener('DOMContentLoaded', function() {
                initHeader(container);
            });
            window.addEventListener('resize', function() {
                initHeader(container);
            });
            window.addEventListener('scroll', function() {
                initHeader(container);
            });
            window.addEventListener('orientationchange', function() {
                initHeader(container);
            });
        }
    };
});