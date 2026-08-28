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
define([], function () {
    /**
     * Sticky Menu function
     * @param {HTMLElement} el The element to get the offset top for
     * @returns {number} The offset top of the element
     */
    function getOffsetTop(el) {
        return el.getBoundingClientRect().top + window.scrollY;
    }

    const header = document.getElementById('moon-header');
    const headerHeight = header.offsetHeight;
    const wrap = header.querySelector('.header-wrap');
    const headerTop = getOffsetTop(header);
    const headerBottom = headerTop + headerHeight + 30;
    let container = '';
    let stickyheader = '';
    let stickyheadertablet = '';
    let stickyheadermobile = '';


    let lastScrollTop = 0;

    let initLastScrollTop = function () {
        lastScrollTop = window.scrollY;
    };

    let isScrollDown = () => window.scrollY > lastScrollTop;

    let deviceBreakpoint = function () {
        const _sizes = ['xs', 'sm', 'md', 'lg', 'xl'];
        let _device = 'undefined';
        _sizes.forEach(function (_size) {
            const el = document.querySelector('.moon-breakpoints .device-' + _size);
            if (el && getComputedStyle(el).display === 'block') {
                _device = _size;
            }
        });
        return _device;
    };

    /**
     * Toggle sticky header
     * @param {string} status The status of the sticky header
     * @param {string} stickyHeaderType The sticky header type
     * @return {void}
     */
    let toggleStickyHeader = function (status, stickyHeaderType) {
        if (status === 'active') {
            header.classList.add('sticky-header-active');
            if (stickyHeaderType === 'stickyonscroll' && header.classList.contains('inactive')) {
                header.classList.remove('inactive');
            }
            header.style.paddingTop = headerHeight + 'px';
            header.style.setProperty('--mf-sticky-header-mark-height', headerHeight + 'px');
            if (container) {
                wrap.classList.add(container);
            }
        } else if (status === 'inactive') {
            header.classList.add('inactive');
        } else {
            header.classList.remove('sticky-header-active');
            header.style.paddingTop = '';
            header.style.removeProperty('--mf-sticky-header-mark-height');
            if (container) {
                wrap.classList.remove(container);
            }
        }
    };

    /**
     * Init header
     * @return {void}
     */
    function initHeader() {
        if (!header) {
            return;
        }
        const winScroll = window.scrollY;
        const _breakpoint = deviceBreakpoint();

        if (_breakpoint === 'xl' || _breakpoint === 'lg') {
            if (winScroll > headerBottom) {
                if (stickyheader === 'sticky' || (stickyheader === 'stickyonscroll' && !isScrollDown())) {
                    toggleStickyHeader('active', stickyheader);
                } else if (stickyheader === 'stickyonscroll' && header.classList.contains('sticky-header-active')) {
                    toggleStickyHeader('inactive', stickyheader);
                }
            } else if (winScroll <= headerTop) {
                toggleStickyHeader('default', stickyheader);
            }
        } else if (_breakpoint === 'sm' || _breakpoint === 'md') {
            if (stickyheadertablet === 'static') {
                if (header.classList.contains('sticky-header-active')) {
                    toggleStickyHeader('default', stickyheadertablet);
                }
                return;
            }
            if (winScroll > headerBottom) {
                if (stickyheadertablet === 'sticky' || (stickyheadertablet === 'stickyonscroll' && !isScrollDown())) {
                    toggleStickyHeader('active', stickyheadertablet);
                } else if (stickyheadertablet === 'stickyonscroll' && header.classList.contains('sticky-header-active')) {
                    toggleStickyHeader('inactive', stickyheadertablet);
                }
            } else if (winScroll <= headerTop) {
                toggleStickyHeader('default', stickyheadertablet);
            }
        } else {
            if (stickyheadermobile === 'static') {
                if (header.classList.contains('sticky-header-active')) {
                    toggleStickyHeader('default', stickyheadermobile);
                }
                return;
            }
            if (winScroll > headerBottom) {
                if (stickyheadermobile === 'sticky' || (stickyheadermobile === 'stickyonscroll' && !isScrollDown())) {
                    toggleStickyHeader('active', stickyheadermobile);
                } else if (stickyheadermobile === 'stickyonscroll' && header.classList.contains('sticky-header-active')) {
                    toggleStickyHeader('inactive', stickyheadermobile);
                }
            } else if (winScroll <= headerTop) {
                toggleStickyHeader('default', stickyheadermobile);
            }
        }
    }
    return {
        /**
         * Initialize the sticky menu
         * @param {string} containerParam The container element
         * @param {string} stickyheaderParam The sticky header type
         * @param {string} stickyheadertabletParam The sticky header type for tablet
         * @param {string} stickyheadermobileParam The sticky header type for mobile
         * @returns {void}
         */
        init: function(containerParam, stickyheaderParam, stickyheadertabletParam, stickyheadermobileParam) {
            container = containerParam;
            stickyheader = stickyheaderParam;
            stickyheadertablet = stickyheadertabletParam;
            stickyheadermobile = stickyheadermobileParam;

            document.addEventListener('DOMContentLoaded', function() {
                initHeader();
            });
            window.addEventListener('resize', function() {
                initHeader();
            });
            window.addEventListener('scroll', function() {
                initHeader();
                initLastScrollTop();
            });
            window.addEventListener('orientationchange', function() {
                initHeader();
            });
        }
    };
});