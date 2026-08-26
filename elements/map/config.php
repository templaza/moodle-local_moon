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
use local_moon\library\helper\moon_element;
use local_moon\library\helper\form;
use local_moon\library\helper\constants;
use local_moon\library\helper\font;
class moon_element_map extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'map',
            'title' => 'Map',
            'description' => 'Map Widget of Moodle',
            'icon' => 'as-icon as-icon-map',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('map_options', [
            'type'  => 'group',
            'label' => 'map_options',
        ]);

        $this->add_field('multiple_maps', [
            'type'  => 'group',
            'label' => 'multiple_maps',
        ]);

        $this->add_field('map_option', [
            'group'   => 'general',
            'type'    => 'list',
            'default' => 'basic',
            'options' => [
                'basic'    => 'basic',
                'advanced' => 'advanced',
            ],
        ]);

        $this->add_field('location', [
            'group'       => 'general',
            'conditions'  => "[map_option]=='basic'",
            'type'        => 'text',
            'label'       => 'location',
            'description' => 'location_desc',
            'attributes' => [
                'hint'        => 'Big Ben London, UK',
            ],
        ]);

        $this->add_field('map', [
            'group'       => 'general',
            'conditions'  => "[map_option]=='advanced'",
            'type'        => 'text',
            'label'       => 'address_location',
            'description' => 'address_location_desc',
            'attributes' => [
                'hint'        => '23.755349,90.375961',
            ],
        ]);

        $this->add_field('type', [
            'group'   => 'general',
            'conditions'=> "[map_option]=='advanced'",
            'type'    => 'list',
            'default' => 'roadmap',
            'label'   => 'map_type',
            'description' => 'map_type_desc',
            'options' => [
                'roadmap'   => 'ROADMAP',
                'satellite' => 'SATELLITE',
                'hybrid'    => 'HYBRID',
                'terrain'   => 'TERRAIN',
            ],
        ]);

        $this->add_field('height', [
            'group'      => 'general',
            'type'       => 'range',
            'attributes' => [
                'min'     => 1,
                'max'     => 2000,
                'step'    => 1,
                'postfix' => 'px',
            ],
            'default' => 300,
            'label'   => 'map_height',
            'description' => 'map_height_desc'
        ]);

        $this->add_field('infowindow', [
            'group'      => 'map_options',
            'conditions' => "[map_option]=='advanced'",
            'type'       => 'textarea',
            'label'      => 'infowindow',
            'description'=> 'infowindow_desc',
        ]);

        $this->add_field('zoom', [
            'group'      => 'map_options',
            'type'       => 'range',
            'attributes' => [
                'min'  => 0,
                'max'  => 25,
                'step' => 1,
            ],
            'default' => 15,
            'label'   => 'zoom',
        ]);

        $this->add_field('mousescroll', [
            'group'      => 'map_options',
            'conditions' => "[map_option]=='advanced'",
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'mousescroll',
            'description'=> 'mousescroll_desc',
        ]);

        $this->add_field('show_controllers', [
            'group'      => 'map_options',
            'conditions' => "[map_option]=='advanced'",
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '1',
            'label'      => 'show_controllers',
            'description'=> 'show_controllers_desc',
        ]);

        $this->add_field('multi_location', [
            'group'      => 'multiple_maps',
            'conditions' => "[map_option]=='advanced'",
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'multi_location',
            'description'=> 'multi_location_desc',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'location_item' => [
                        'type'        => 'text',
                        'label'       => 'address_location',
                        'description' => 'address_location_desc',
                        'attributes' => ['hint' => '23.755349,90.375961',],
                    ],
                    'location_popup_text' => [
                        'type'        => 'astroidtextarea',
                        'label'       => 'infowindow',
                        'description' => 'infowindow_desc',
                    ],
                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('multi_location_items',  [
            "group" => "multiple_maps",
            "type" => "subform",
            "label" => "multi_location_items",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
            'conditions' => "[multi_location]=='1' AND [map_option]=='advanced'",
        ]);
    }
}