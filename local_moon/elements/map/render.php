<?php
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

defined('MOODLE_INTERNAL') || die;
use local_moon\library\helper\style;
use local_moon\library\framework;
$document = framework::get_document();

$params = $this->params;
$element = $this;
$map_option = $params->get('map_option', '');
$gmap_data  =   new stdClass();
$gmap_data->title = $params->get('title', '');
$gmap_data->height = $params->get('height', '');
$gmap_data->zoom = $params->get('zoom', 0);
switch ($map_option) {
    case 'basic':
        $gmap_data->location = $params->get('location', '');
        $slug = \core_text::strtolower($gmap_data->location);
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
        $slug = trim($slug, '-');
        echo '<div class="d-flex">';
        echo '<iframe loading="lazy" class="moon-gmap w-100" src="https://maps.google.com/maps?q='.$slug.'&amp;t=m&amp;z='.$gmap_data->zoom.'&amp;output=embed&amp;iwloc=near" title="'.$gmap_data->title.'" aria-label="'.$gmap_data->title.'"></iframe>';
        echo '</div>';
        break;
    case 'advanced':
        $map = $params->get('map', '');
        if (!$map) return false;
        $map = explode(',', $map);
        $gmap_data->lat = trim($map[0]);
        $gmap_data->lng = trim($map[1]);
        $gmap_data->infowindow  = $params->get('infowindow', '');
        $gmap_data->type = $params->get('type', 'roadmap');
        $gmap_data->mousescroll = $params->get('mousescroll', 1);
        $gmap_data->show_controllers = $params->get('show_controllers', 1);

        $multi_location         = $params->get('multi_location', 0);
        $multi_location_items   = json_decode($params->get('multi_location_items', ''));
        $location_addr = [];
        if ($multi_location && !empty($multi_location_items) && count($multi_location_items)) {
            foreach ($multi_location_items as $key => $location_item)
            {
                $item    =   style::get_sub_form_params($location_item->params);
                $lat_long = explode(',', $item['location_item']);
                $location_addr[] = array('address' => $item['location_popup_text'], 'latitude' => trim($lat_long[0]), 'longitude' => trim($lat_long[1]));
            }
        }
        $gmap_data->locations = $location_addr;
        $template = framework::get_theme();
        $gmap_api = $template->get_params()->get('gmap_api', '');
        echo '<div id="moon-widget-map-' . $element->id . '" class="moon-gmap d-none">'.json_encode($gmap_data).'</div>';
        $document->add_script("https://maps.googleapis.com/maps/api/js", ['key' => $gmap_api, 'libraries' => 'places'], true);
        $document->add_script('/local/moon/assets/gmap/gmap.min.js');
        break;
}
style::add_css_by_selector('#'. $element->id . ' .moon-gmap', 'height', $gmap_data->height.'px');