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

use local_moon\library\framework;
use local_moon\library\helper\font;

framework::get_theme()->add_fields(
    'typography',
    [
        'label' => 'typography',
        'icon' => 'as-icon as-icon-text-size',
        'order' => 4,
        'fields' => [
            'body_typography_group' => ["type" => "group", "label" => "body_typography", "description" => "body_typography_desc"],
            'menu_typography_group' => ["type" => "group", "label" => "menu_typography", "description" => "menu_typography_desc"],
            'submenu_typography_group' => ["type" => "group", "label" => "submenu_typography", "description" => "submenu_typography_desc"],
            'secondmenu_typography_group' => ["type" => "group", "label" => "secondmenu_typography", "description" => "secondmenu_typography_desc"],
            'h1_typography_group' => ["type" => "group", "label" => "h1_typography", "description" => "h1_typography_desc"],
            'h2_typography_group' => ["type" => "group", "label" => "h2_typography", "description" => "h2_typography_desc"],
            'h3_typography_group' => ["type" => "group", "label" => "h3_typography", "description" => "h3_typography_desc"],
            'h4_typography_group' => ["type" => "group", "label" => "h4_typography", "description" => "h4_typography_desc"],
            'h5_typography_group' => ["type" => "group", "label" => "h5_typography", "description" => "h5_typography_desc"],
            'h6_typography_group' => ["type" => "group", "label" => "h6_typography", "description" => "h6_typography_desc"],
            'custom_typography_group' => ["type" => "group", "label" => "custom_typography", "description" => "custom_typography_desc"],
            // Body
            'body_typography' => [
                "group" => "body_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'body_typography_options' => [
                "group" => "body_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[body_typography]=='custom'"
            ],
            // Menu
            'menu_typography' => [
                "group" => "menu_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'menu_typography_options' => [
                "group" => "menu_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[menu_typography]=='custom'"
            ],
            // Submenu
            'submenu_typography' => [
                "group" => "submenu_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'submenu_typography_options' => [
                "group" => "submenu_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[submenu_typography]=='custom'"
            ],
            // Second Menu
            'secondmenu_typography' => [
                "group" => "secondmenu_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'secondmenu_typography_options' => [
                "group" => "secondmenu_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[secondmenu_typography]=='custom'"
            ],
            // H1 Typography
            'h1_typography' => [
                "group" => "h1_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'h1_typography_options' => [
                "group" => "h1_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[h1_typography]=='custom'"
            ],
            // H2 Typography
            'h2_typography' => [
                "group" => "h2_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'h2_typography_options' => [
                "group" => "h2_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[h2_typography]=='custom'"
            ],
            // H3 Typography
            'h3_typography' => [
                "group" => "h3_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'h3_typography_options' => [
                "group" => "h3_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[h3_typography]=='custom'"
            ],

            // H4 Typography
            'h4_typography' => [
                "group" => "h4_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'h4_typography_options' => [
                "group" => "h4_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[h4_typography]=='custom'"
            ],

            // H5 Typography
            'h5_typography' => [
                "group" => "h5_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'h5_typography_options' => [
                "group" => "h5_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[h5_typography]=='custom'"
            ],

            // H6 Typography
            'h6_typography' => [
                "group" => "h6_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'h6_typography_options' => [
                "group" => "h6_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[h6_typography]=='custom'"
            ],

            // Custom Typography
            'custom_typography' => [
                "group" => "custom_typography_group",
                "type" => "radio",
                "label" => "typography_properties",
                "description" => "typography_properties_desc",
                "default" => "default",
                "options" => [
                    "default" => "default",
                    "custom" => "custom"
                ]
            ],
            'custom_typography_selectors' => [
                "group" => "custom_typography_group",
                "type" => "text",
                "label" => "css_selectors",
                "description" => "css_selectors_desc",
                "conditions" => "[custom_typography]=='custom'"
            ],
            'custom_typography_options' => [
                "group" => "custom_typography_group",
                "type" => "typography",
                "attributes" => [
                    'options' => [
                        "colorpicker" => false,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => true,
                        'collapse' => false,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value
                ],
                "conditions" => "[custom_typography]=='custom'"
            ],
        ]
    ]
);