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

use local_moon\library\framework;

framework::get_theme()->add_fields(
    'miscellaneous',
    [
        'label' => 'miscellaneous',
        'icon' => 'as-icon as-icon-cog',
        'order' => 8,
        'fields' => [
            'contact_info' => ["type" => "group", "label" => "contact_info", "description" => "contact_info_desc"],
            'contact_details' => [
                'group' => 'contact_info',
                'type' => 'radio',
                'label' => 'contact_details',
                'description' => 'contact_details_desc',
                'default' => 1,
                "attributes" => [
                    "role" => "switch"
                ]
            ],

            'contact_module_position' => [
                'group' => 'contact_info',
                'type' => 'regions',
                'label' => 'select_region',
                'description' => 'select_region_desc',
                "attributes" => [
                    'astroid_content_layout' => 'contactinfo',
                ],
                'conditions' => "[contact_details]=='1'",
            ],

            'contact_feature_load_position' => [
                'group' => 'contact_info',
                'type' => 'list',
                'label' => 'feature_load_region',
                'description' => 'feature_load_region_desc',
                "attributes" => [
                    'astroid_content_layout_load' => 'contact_module_position',
                ],
                'default' => 'after',
                'conditions' => "[contact_details]=='1'",
                'options' => [
                    'after' => 'after_region',
                    'before' => 'before_region',
                ],
            ],

            'contact_address' => [
                'group' => 'contact_info',
                'type' => 'text',
                'label' => 'address',
                'description' => 'address_desc',
                "attributes" => [
                    'hint' => '15 Barnes Wallis Way, West Road, Chorley, USA',
                ],
                'conditions' => "[contact_details]=='1'",
            ],

            'contact_phone_number' => [
                'group' => 'contact_info',
                'type' => 'text',
                'label' => 'phone_number',
                'description' => 'phone_number_desc',
                "attributes" => [
                    'hint' => '+1 123 456 7890',
                ],
                'conditions' => "[contact_details]=='1'",
            ],

            'contact_mobile_number' => [
                'group' => 'contact_info',
                'type' => 'text',
                'label' => 'mobile_number',
                'description' => 'mobile_number_desc',
                "attributes" => [
                    'hint' => '+1 123 456 7890',
                ],
                'conditions' => "[contact_details]=='1'",
            ],

            'contact_email_address' => [
                'group' => 'contact_info',
                'type' => 'text',
                'label' => 'email',
                'description' => 'email_desc',
                "attributes" => [
                    'hint' => 'email@yourcompany.com',
                ],
                'conditions' => "[contact_details]=='1'",
            ],

            'contact_open_hours' => [
                'group' => 'contact_info',
                'type' => 'text',
                'label' => 'open_hours',
                'description' => 'open_hours_desc',
                "attributes" => [
                    'hint' => 'Mon-Fri : 9:00am - 6:00pm',
                ],
                'conditions' => "[contact_details]=='1'",
            ],

            'contact_display' => [
                'group' => 'contact_info',
                'type' => 'radio',
                'label' => 'display',
                'description' => 'display_type_desc',
                'default' => 'icons',
                'conditions' => "[contact_details]=='1'",
                'options' => [
                    'text' => 'text',
                    'icons' => 'icons',
                ],
            ],

            'icon_color' => [
                'group' => 'contact_info',
                'type' => 'color',
                'label' => 'icon_color',
                'description' => 'icon_color_desc',
                'conditions' => "[contact_details]=='1' AND [contact_display]=='icons'",
            ],
        ]
    ]
);