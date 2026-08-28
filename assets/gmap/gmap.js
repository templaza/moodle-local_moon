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
 * @copyright Copyright (C) 2024 AstroidFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        async function initMap(el, gdata) {
            if (!window.google || !google.maps || typeof google.maps.importLibrary !== 'function') {
                return;
            }

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

        document.querySelectorAll('.moon-gmap').forEach(el => {
            try {
                const gdata = JSON.parse(el.textContent.trim());
                initMap(el, gdata);
            } catch (err) {
                // invalid JSON or other error — ignore element
            }
        });
    });
})();