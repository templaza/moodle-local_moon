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
 * @module    local_moon/transform
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
define([], function() {
    class AstroidTransform {
        scenes = [];
        scrollConfig = {};
        timelineConfig = {};
        el = null;
        constructor(el) {
            this.el = el.dataset.transformTrigger
                ? el.querySelectorAll(el.dataset.transformTrigger)
                : el;
            this.scenes = JSON.parse(el.dataset.transformScenes);
            this.scrollConfig = el.dataset.transformScroll
                ? JSON.parse(el.dataset.transformScroll)
                : {};
            this.timelineConfig = el.dataset.transformTimeline
                ? JSON.parse(el.dataset.transformTimeline)
                : {};
            this.timelineConfig.paused = true;
            if (this.timelineConfig.repeat) {
                this.timelineConfig.repeat = this.timelineConfig.repeat === 'true' ? -1 : parseInt(this.timelineConfig.repeat);
            }
            if (this.scrollConfig.scrub) {
                this.scrollConfig.scrub = this.scrollConfig.scrub === 'true' ? true : parseFloat(this.scrollConfig.scrub);
            }
        }

        init() {
            // Create timeline
            const tl = window.gsap.timeline(this.timelineConfig);
            // Build scenes
            this.scenes.forEach(scene => {
                if (scene.from && scene.to) {
                    tl.fromTo(this.el, scene.from, scene.to);
                } else if (scene.from) {
                    tl.from(this.el, scene.from);
                } else {
                    tl.to(this.el, scene.to || {});
                }
            });

            // Attach ScrollTrigger
            window.ScrollTrigger.create({
                ...{
                    trigger: this.el,
                    animation: tl
                },
                ...this.scrollConfig
            });
        }
    }

    return {
        /**
         * Initialize the video background
         */
        init: function() {
            /**
             * Run the transform initialization
             */
            function run() {
                window.gsap.registerPlugin(window.ScrollTrigger);
                document.querySelectorAll('[data-transform-scenes]').forEach(el => {
                    const transform = new AstroidTransform(el);
                    transform.init();
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function () { run(); });
            } else {
                run();
            }
        }
    };
});