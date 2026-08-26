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
use local_moon\library\helper\constants;
use local_moon\library\helper\text;
framework::get_theme()->add_fields(
    'theming',
    [
        'label' => 'theming',
        'icon' => 'as-icon as-icon-pencil-ruler2',
        'order' => 7,
        'fields' => [
            'colors' => [
                'type' => 'group',
                'label' => 'colors',
                'help' => 'https://docs.astroidframe.work/styling/theming',
            ],
            'sassoverrides' => [
                'type' => 'group',
                'label' => 'sass_overrides',
                'help' => 'https://docs.astroidframe.work/styling/theming#-custom-colors',
            ],

            'theme_colors_heading' => [
                'group' => 'colors',
                'type' => 'heading',
                'attributes' => [
                    'title' => text::_('all_colors'),
                    'description' => text::_('all_colors_desc'),
                ],
            ],

            'theme_blue' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'blue',
                'default' => '#007bff',
            ],
            'theme_indigo' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'indigo',
                'default' => '#6610f2',
            ],
            'theme_purple' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'purple',
                'default' => '#6f42c1',
            ],
            'theme_pink' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'pink',
                'default' => '#f36',
            ],
            'theme_red' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'red',
                'default' => '#dc3545',
            ],
            'theme_orange' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'orange',
                'default' => '#fd7e14',
            ],
            'theme_yellow' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'yellow',
                'default' => '#ffc107',
            ],
            'theme_green' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'green',
                'default' => '#28a745',
            ],
            'theme_teal' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'teal',
                'default' => '#20c997',
            ],
            'theme_cyan' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'cyan',
                'default' => '#17a2b8',
            ],
            'theme_white' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'white',
                'default' => '#fff',
            ],
            'theme_gray100' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'light_gray',
                'default' => '#f8f9fa',
            ],
            'theme_gray600' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'gray',
                'default' => '#6c757d',
            ],
            'theme_gray800' => [
                'group' => 'colors',
                'type' => 'color',
                'label' => 'gray_dark',
                'default' => '#2c2e36',
            ],

            'theme_scheme_heading' => [
                'group' => 'colors',
                'type' => 'heading',
                'attributes' => [
                    'title' => text::_('theme_colors'),
                    'description' => text::_('theme_colors_desc'),
                ],
            ],

            'theme_primary' => [
                'group' => 'colors',
                'type' => 'list',
                'class' => 'form-select',
                'default' => '',
                'label' => 'Primary',
                "options" => constants::$bootstrap_colors,
            ],
            'theme_primary_custom' => [
                'group' => 'colors',
                'type' => 'color',
                'default' => '',
                'label' => 'Custom Primary',
                'conditions' => "[theme_primary]=='custom'",
            ],

            'theme_secondary' => [
                'group' => 'colors',
                'type' => 'list',
                'class' => 'form-select',
                'default' => '',
                'label' => 'Secondary',
                "options" => constants::$bootstrap_colors,
            ],
            'theme_secondary_custom' => [
                'group' => 'colors',
                'type' => 'color',
                'default' => '',
                'label' => 'Custom Secondary',
                'conditions' => "[theme_secondary]=='custom'",
            ],

            'theme_success' => [
                'group' => 'colors',
                'type' => 'list',
                'class' => 'form-select',
                'default' => '',
                'label' => 'Success',
                "options" => constants::$bootstrap_colors,
            ],
            'theme_success_custom' => [
                'group' => 'colors',
                'type' => 'color',
                'default' => '',
                'label' => 'Custom Success',
                'conditions' => "[theme_success]=='custom'",
            ],

            'theme_info' => [
                'group' => 'colors',
                'type' => 'list',
                'class' => 'form-select',
                'default' => '',
                'label' => 'Info',
                "options" => constants::$bootstrap_colors,
            ],
            'theme_info_custom' => [
                'group' => 'colors',
                'type' => 'color',
                'default' => '',
                'label' => 'Custom Info',
                'conditions' => "[theme_info]=='custom'",
            ],

            'theme_warning' => [
                'group' => 'colors',
                'type' => 'list',
                'class' => 'form-select',
                'default' => '',
                'label' => 'Warning',
                "options" => constants::$bootstrap_colors,
            ],
            'theme_warning_custom' => [
                'group' => 'colors',
                'type' => 'color',
                'default' => '',
                'label' => 'Custom Warning',
                'conditions' => "[theme_warning]=='custom'",
            ],

            'theme_danger' => [
                'group' => 'colors',
                'type' => 'list',
                'class' => 'form-select',
                'default' => '',
                'label' => 'Danger',
                "options" => constants::$bootstrap_colors,
            ],
            'theme_danger_custom' => [
                'group' => 'colors',
                'type' => 'color',
                'default' => '',
                'label' => 'Custom Danger',
                'conditions' => "[theme_danger]=='custom'",
            ],

            'theme_light' => [
                'group' => 'colors',
                'type' => 'list',
                'class' => 'form-select',
                'default' => '',
                'label' => 'Light',
                "options" => constants::$bootstrap_colors,
            ],
            'theme_light_custom' => [
                'group' => 'colors',
                'type' => 'color',
                'default' => '',
                'label' => 'Custom Light',
                'conditions' => "[theme_light]=='custom'",
            ],

            'theme_dark' => [
                'group' => 'colors',
                'type' => 'list',
                'class' => 'form-select',
                'default' => '',
                'label' => 'Dark',
                "options" => constants::$bootstrap_colors,
            ],
            'theme_dark_custom' => [
                'group' => 'colors',
                'type' => 'color',
                'default' => '',
                'label' => 'Custom Dark',
                'conditions' => "[theme_dark]=='custom'",
            ],

            'sass_overrides' => [
                'group' => 'sassoverrides',
                'type' => 'sassoverrides',
            ],
        ]
    ]
);