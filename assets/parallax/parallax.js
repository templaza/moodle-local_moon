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
 * @package   Astroid Framework
 * @author    Astroid Framework Team https://astroidframe.work
 * @copyright Copyright (C) 2026 AstroidFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

class Parallax {
    config = {};
    element = null;
    type = null;
    speed = 0.3;
    startPercent = -70;
    endPercent = (20 * this.speed)-50;
    startTrigger = 'top bottom';
    endTrigger = 'bottom top';
    scrub = true;
    _scrollTriggerRegistered = false;

    constructor(element, config = {}) {
        this.element = element;
        this.config = config;
        this.type = this.config.type || 'image';
        this.speed = Number(this.config.speed) || 0.3;
        this.startPercent = -70;
        this.endPercent = (20 * this.speed)-50;
        this.startTrigger = this.config.start || 'top bottom';
        this.endTrigger = this.config.end || 'bottom top';
        this.scrub = this.config.scrub;
    }

    init() {
        // create pseudo background layer for image
        const _this = this;
        const bgUrl = getComputedStyle(_this.element).backgroundImage;

        if (!bgUrl || bgUrl === "none") return;

        const bgElement = document.createElement("div");
        bgElement.classList.add('position-absolute', 'top-50', 'start-50', 'object-fit-cover', 'pe-none', 'z-0');

        bgElement.style.backgroundImage = bgUrl;
        bgElement.style.backgroundSize = "cover";
        bgElement.style.backgroundPosition = "center";
        bgElement.style.minWidth = '100%';
        // bgElement.style.minHeight = '125%';
        bgElement.style.minHeight = `${120 + (this.speed * 50)}%`;

        _this.element.style.backgroundImage = "none";
        _this.element.style.position = "relative";
        _this.element.style.overflow = "hidden";

        _this.element.prepend(bgElement);

        if (!bgElement) return;

        // determine scrub: allow boolean or numeric value
        let scrub = true;
        if (typeof _this.config.scrub !== 'undefined') {
            if (_this.config.scrub === false || _this.config.scrub === 'false') scrub = false;
            else if (_this.config.scrub === true || _this.config.scrub === 'true') scrub = true;
            else scrub = Number(_this.config.scrub) || true;
        }

        // Use will-change for smoother animations
        gsap.set(bgElement, { xPercent: -50, yPercent: _this.startPercent, y: 0, willChange: 'transform' });

        gsap.to(bgElement, {
            yPercent: _this.endPercent,
            ease: 'none',
            scrollTrigger: {
                trigger: _this.element,
                start: _this.startTrigger,
                end: _this.endTrigger,
                scrub: scrub,
                invalidateOnRefresh: true
            }
        });
    }
}

(function () {
    document.addEventListener('DOMContentLoaded', () => {
        const elements = document.querySelectorAll("[data-parallax]");
        if (!elements.length) return;
        gsap.registerPlugin(ScrollTrigger);
        elements.forEach((el) => {
            let config = {};

            try {
                config = JSON.parse(el.dataset.parallax || "{}");
            } catch (e) {
                console.warn("Invalid JSON in data-parallax", el);
            }

            const parallax = new Parallax(el, config);
            if (parallax.type === 'image') {
                parallax.init();
            }
        });
    });
})();
