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
 * @module    local_moon/gmap
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
define([], function() {
    /**
     * Initialize the Google Map
     * @param {HTMLElement} el The map container element
     * @param {Object} gdata The map data
     */
    async function initMap(el, gdata) {
        if (!window.google || !window.google.maps || typeof window.google.maps.importLibrary !== 'function') {
            return;
        }

        const google = window.google;
        const { Map } = await google.maps.importLibrary('maps');
        const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');

        const position = { lat: parseFloat(gdata.lat), lng: parseFloat(gdata.lng) };
        el.innerHTML = '';
        el.classList.remove('d-none');

        const map = new Map(el, {
            center: position,
            zoom: parseInt(gdata.zoom, 10),
            mapTypeId: gdata.type,
            scrollwheel: parseInt(gdata.mousescroll, 10) !== 0,
            disableDefaultUI: parseInt(gdata.show_controllers, 10) !== 1,
            mapId: 'DEMO_MAP_ID',
        });

        const marker = new AdvancedMarkerElement({
            map,
            position,
            title: gdata.title,
        });

        if (gdata.infowindow && gdata.infowindow !== '') {
            const infowindow = new google.maps.InfoWindow({
                content: '<h5>' + gdata.title + '</h5>' + '<div>' + gdata.infowindow + '</div>',
                ariaLabel: gdata.title,
            });

            marker.addListener('click', () => {
                infowindow.open({ anchor: marker, map });
            });
        }

        if (Array.isArray(gdata.locations) && gdata.locations.length) {
            gdata.locations.forEach(location => {
                const _marker = new AdvancedMarkerElement({
                    map,
                    position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) },
                    title: location.address,
                });

                const _infowindow = new google.maps.InfoWindow({
                    content: location.address,
                    ariaLabel: location.address,
                });

                _marker.addListener('click', () => {
                    _infowindow.open({ anchor: _marker, map });
                });
            });
        }
    }

    return {
        /**
         * Initialize the video background
         */
        init: function() {
            /**
             * Run the Google Map initialization for all elements with the class 'moon-gmap'
             */
            function run() {
                document.querySelectorAll('.moon-gmap').forEach(el => {
                    const gdata = JSON.parse(el.textContent.trim());
                    initMap(el, gdata);
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