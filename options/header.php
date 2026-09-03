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
 * @package   local_moon
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

defined('MOODLE_INTERNAL') || die;

use local_moon\library\framework;
use local_moon\library\helper\font;

framework::get_theme()->add_fields(
    'header',
    [
        'label' => 'header',
        'icon' => 'as-icon as-icon-menu3',
        'order' => 2,
        'fields' => [
            'header_element' => ["type" => "group", "label" => "header", "description" => ""],
            'header_logo_options_element' => ["type" => "group", "label" => "logo_options", "description" => "logo_options_desc"],
            'header_sticky_options_element' => ["type" => "group", "label" => "sticky_header", "description" => "sticky_header_desc"],

            'header' => [
                "group" => "header_element",
                "type" => "radio",
                "label" => "enable_header",
                "description" => "",
                "default" => 1,
                "attributes" => [
                    "role" => "switch"
                ]
            ],

            'header_mode' => [
                "group" => "header_element",
                "type" => "radio",
                "label" => "header_mode",
                "description" => "header_mode_desc",
                "default" => 'horizontal',
                "attributes" => [
                    "role" => "image"
                ],
                "options" => [
                    'horizontal' => '/local/moon/assets/images/header/horizontal-left.svg',
                    'stacked' => '/local/moon/assets/images/header/stacked_style1.svg',
                    'sidebar' => '/local/moon/assets/images/header/sidebar-1.svg',
                ],
                "conditions" => "[header]==true"
            ],

            'header_horizontal_menu_mode' => [
                "group" => "header_element",
                "type" => "radio",
                "label" => "horizontal_menu_mode",
                "description" => "horizontal_menu_mode_desc",
                "default" => "left",
                "attributes" => [
                    "role" => "image"
                ],
                "options" => [
                    'left'   => '/local/moon/assets/images/header/horizontal-left.svg',
                    'center' => '/local/moon/assets/images/header/horizontal-center.svg',
                    'right'  => '/local/moon/assets/images/header/horizontal-right.svg',
                ],
                "conditions" => "[header]==true AND [header_mode]=='horizontal'",
            ],

            'header_sidebar_menu_mode' => [
                "group" => "header_element",
                "type" => "radio",
                "label" => "sidebar_menu_mode",
                "description" => "sidebar_menu_mode_desc",
                "default" => "left",
                "attributes" => [
                    "role" => "image"
                ],
                "options" => [
                    'left'   => '/local/moon/assets/images/header/sidebar-1.svg',
                    'right'  => '/local/moon/assets/images/header/sidebar-2.svg',
                    'topbar' => '/local/moon/assets/images/header/sidebar-topbar.svg',
                ],
                "conditions" => "[header]==true AND [header_mode]=='sidebar'",
            ],

            'header_stacked_menu_mode' => [
                "group" => "header_element",
                "type" => "radio",
                "label" => "stacked_menu_mode",
                "description" => "stacked_menu_mode_desc",
                "default" => "seperated",
                "attributes" => [
                    "role" => "image"
                ],
                "options" => [
                    'center-balance'     => '/local/moon/assets/images/header/stacked_style0.svg',
                    'center'             => '/local/moon/assets/images/header/stacked_style1.svg',
                    'seperated'          => '/local/moon/assets/images/header/stacked_style2.svg',
                    'divided'            => '/local/moon/assets/images/header/stacked_style3.svg',
                    'divided-logo-left'  => '/local/moon/assets/images/header/stacked_style4.svg',
                ],
                "conditions" => "[header]==true AND [header_mode]=='stacked'",
            ],

            'sidebar_position' => [
                "group" => "header_element",
                "type" => "radio",
                "label" => "sidebar_position",
                "description" => "sidebar_position_desc",
                "default" => "left",
                "options" => [
                    'left'  => 'left',
                    'right' => 'right',
                ],
                "conditions" => "[header]==true AND [header_mode]=='sidebar' AND [header_sidebar_menu_mode]=='topbar'",
            ],

            'divided_logo_width' => [
                "group" => "header_element",
                "type" => "range",
                "label" => "divided_logo_width",
                "description" => "divided_logo_width_desc",
                "default" => 200,
                "attributes" => [
                    "min" => 20,
                    "max" => 600,
                    "step" => 1,
                    "postfix" => "px",
                ],
                "conditions" => "[header]==true AND [header_mode]=='stacked' AND [header_stacked_menu_mode]=='divided-logo-left'",
            ],

            'odd_menu_items' => [
                "group" => "header_element",
                "type" => "radio",
                "label" => "odd_menu_items",
                "description" => "odd_menu_items_desc",
                "default" => "left",
                "options" => [
                    'left'  => 'left',
                    'right' => 'right',
                ],
                "conditions" => "[header]==true AND [header_mode]=='stacked' AND [header_stacked_menu_mode]=='seperated'",
            ],

            'header_block_1_type' => [
                'group' => 'header_element',
                'type' => 'list',
                'label' => 'header_block_1',
                'description' => 'header_block_1_desc',
                'default' => 'blank',
                'conditions' => "[header]==true",
                'options' => [
                    'blank'   => 'blank',
                    'position'=> 'region',
                    'custom'  => 'custom',
                ],
            ],

            'header_block_1_position' => [
                'group' => 'header_element',
                'type' => 'regions',
                'label' => 'block_1_position',
                'description' => 'block_1_position_desc',
                'conditions' => "[header]==true AND [header_block_1_type]=='position'",
            ],

            'header_block_1_custom' => [
                'group' => 'header_element',
                'type' => 'textarea',
                'label' => 'block_1_custom',
                'description' => 'block_1_custom_desc',
                "attributes" => [
                    'filter' => 'raw',
                ],
                'conditions' => "[header]==true AND [header_block_1_type]=='custom'",
            ],

            'header_block_2_type' => [
                'group' => 'header_element',
                'type' => 'list',
                'label' => 'header_block_2',
                'description' => 'header_block_2_desc',
                'default' => 'blank',
                'conditions' => "[header]==true AND (([header_mode]=='stacked' AND [header_stacked_menu_mode]!='center') OR ([header_mode]=='horizontal') OR ([header_mode]=='sidebar'))",
                'options' => [
                    'blank'    => 'blank',
                    'position' => 'region',
                    'custom'   => 'custom',
                ],
            ],

            'header_block_2_position' => [
                'group' => 'header_element',
                'type' => 'regions',
                'label' => 'block_2_position',
                'description' => 'block_2_position_desc',
                'conditions' => "[header]==true AND (([header_mode]=='stacked' AND [header_stacked_menu_mode]!='center') OR ([header_mode]=='horizontal') OR ([header_mode]=='sidebar')) AND [header_block_2_type]=='position'",
            ],

            'header_block_2_custom' => [
                'group' => 'header_element',
                'type' => 'textarea',
                'label' => 'block_2_custom',
                'description' => 'block_2_custom_desc',
                "attributes" => [
                    'filter' => 'raw',
                ],
                'conditions' => "[header]==true AND (([header_mode]=='stacked' AND [header_stacked_menu_mode]!='center') OR ([header_mode]=='horizontal') OR ([header_mode]=='sidebar')) AND [header_block_2_type]=='custom'",
            ],

            'header_block_3_type' => [
                'group' => 'header_element',
                'type' => 'list',
                'label' => 'header_block_3',
                'description' => 'header_block_3_desc',
                'default' => 'blank',
                'conditions' => "[header]==true AND (([header_mode]=='stacked' AND [header_stacked_menu_mode]=='divided-logo-left') OR ([header_mode]=='sidebar' AND [header_sidebar_menu_mode]=='topbar'))",
                'options' => [
                    'blank'    => 'blank',
                    'position' => 'region',
                    'custom'   => 'custom',
                ],
            ],

            'header_block_3_position' => [
                'group' => 'header_element',
                'type' => 'regions',
                'label' => 'block_3_position',
                'description' => 'block_3_position_desc',
                'conditions' => "[header]==true AND (([header_mode]=='stacked' AND [header_stacked_menu_mode]=='divided-logo-left') OR ([header_mode]=='sidebar' AND [header_sidebar_menu_mode]=='topbar')) AND [header_block_3_type]=='position'",
            ],

            'header_block_3_custom' => [
                'group' => 'header_element',
                'type' => 'textarea',
                'label' => 'block_3_custom',
                'description' => 'block_3_custom_desc',
                "attributes" => [
                    'filter' => 'raw',
                ],
                'conditions' => "[header]==true AND (([header_mode]=='stacked' AND [header_stacked_menu_mode]=='divided-logo-left') OR ([header_mode]=='sidebar' AND [header_sidebar_menu_mode]=='topbar')) AND [header_block_3_type]=='custom'",
            ],

            // Head Breakpoints
            'header_breakpoint' => [
                'group'       => 'header_element',
                'type'        => 'list',
                'label'       => 'header_breakpoint',
                'description' => 'header_breakpoint_desc',
                'default'     => 'lg',
                'conditions'  => "[header]==true AND [header_mode]!='sidebar'",
                'options'     => [
                    'sm'     => 'small',
                    'md'     => 'medium',
                    'lg'     => 'large',
                    'xl'     => 'xlarge',
                    'xxl'    => 'xxlarge',
                    'always' => 'always',
                ],
            ],

            // Logo options
            'logo_type' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'radio',
                'label'       => 'TPL_ASTROID_BASIC_LOGO_TYPE_LABEL',
                'description' => 'TPL_ASTROID_BASIC_LOGO_TYPE_DESC',
                'default'     => 'image',
                'conditions'  => "[header]==true",
                'options'     => [
                    'text'  => 'TPL_ASTROID_BASIC_LOGO_TYPE_OPTIONS_TEXT',
                    'image' => 'TPL_ASTROID_BASIC_LOGO_TYPE_OPTIONS_IMAGE',
                ],
            ],

            'logo_text' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'text',
                'label'       => 'TPL_ASTROID_BASIC_LOGO_TEXT_LABEL',
                'description' => 'TPL_ASTROID_BASIC_LOGO_TEXT_DESC',
                'default'     => 'Astroid',
                'conditions'  => "[header]==true AND [logo_type]=='text'",
            ],

            'tag_line' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'text',
                'label'       => 'TPL_ASTROID_BASIC_TAG_LINE_LABEL',
                'description' => 'TPL_ASTROID_BASIC_TAG_LINE_DESC',
                'conditions'  => "[header]==true AND [logo_type]=='text'",
            ],

            // Logo Typography
            'logo_typography' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'radio',
                'label'       => 'TPL_ASTROID_TYPOGRAPHY_LOGO',
                'description' => 'typography_properties_desc',
                'default'     => 'inherit',
                'conditions'  => "[header]==1 AND [logo_type]=='text'",
                'options'     => [
                    'inherit' => 'inherit',
                    'custom'  => 'custom',
                ],
            ],

            'logo_typography_options' => [
                'group'             => 'header_logo_options_element',
                'type'              => 'typography',
                'conditions'        => "[logo_typography]=='custom' AND [header]==true AND [logo_type]=='text'",
                "attributes" => [
                    'options' => [
                        "colorpicker" => true,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => false,
                        'collapse' => true,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value,
                ],
            ],

            'logo_tag_line_typography' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'radio',
                'label'       => 'TPL_ASTROID_TYPOGRAPHY_LOGO_TAG_LINE',
                'description' => 'typography_properties_desc',
                'default'     => 'inherit',
                'conditions'  => "[header]==true AND [logo_type]=='text'",
                'options'     => [
                    'inherit' => 'inherit',
                    'custom'  => 'custom',
                ],
            ],

            'logo_tag_line_typography_options' => [
                'group'             => 'header_logo_options_element',
                'type'              => 'typography',
                'conditions'        => "[logo_tag_line_typography]=='custom' AND [header]==true AND [logo_type]=='text'",
                "attributes" => [
                    'options' => [
                        "colorpicker" => true,
                        'stylepicker' => false,
                        'fontpicker' => true,
                        'sizepicker' => true,
                        'letterspacingpicker' => true,
                        'lineheightpicker' => true,
                        'weightpicker' => true,
                        'transformpicker' => true,
                        'columns' => 3,
                        'preview' => false,
                        'collapse' => true,
                        'system_fonts' => font::get_system_fonts(),
                        'text_transform_options' => font::text_transform(),
                        'lang' => font::font_properties(),
                    ],
                    'lang' => font::font_properties(),
                    'value' => font::$get_default_font_value,
                ],
            ],

            'logo_link_type' => [
                'group'      => 'header_logo_options_element',
                'type'       => 'radio',
                'label'      => 'Logo Link',
                'default'    => 'default',
                'conditions' => "[header]==true AND [logo_type]!='none'",
                'options'    => [
                    'default' => 'default',
                    'custom'  => 'custom',
                    'none'    => 'none',
                ],
            ],

            'logo_link_custom' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'text',
                'label'       => 'Logo Link Url',
                'default'     => '#',
                'description' => '',
                'conditions'  => "[header]==true AND [logo_type]!='none' AND [logo_link_type]=='custom'",
            ],

            'logo_link_target_blank' => [
                'group'         => 'header_logo_options_element',
                'type'          => 'radio',
                'label'         => 'Open in new window',
                "attributes" => [
                    "role" => "switch"
                ],
                'default'       => '0',
                'conditions'    => "[header]==true AND [logo_type]!='none' AND [logo_link_type]=='custom'",
                'description'   => '',
            ],

            'default_logo' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'media',
                'label'       => 'TPL_ASTROID_BASIC_DEFULT_LOGO_LABEL',
                'description' => 'TPL_ASTROID_BASIC_DEFULT_LOGO_DESC',
                'conditions'  => "[header]==true AND [logo_type]=='image'",
            ],

            'default_logo_dark' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'media',
                'name'        => 'default_logo_dark',
                'label'       => 'TPL_ASTROID_BASIC_DEFAULT_LOGO_DARK_LABEL',
                'description' => 'TPL_ASTROID_BASIC_DEFAULT_LOGO_DARK_DESC',
                'conditions'  => "[header]==true AND [logo_type]=='image' AND [astroid_color_mode_enable]=='1'",
            ],

            'default_logo_width' => [
                'group'      => 'header_logo_options_element',
                'type'       => 'text',
                'name'       => 'default_logo_width',
                'label'      => 'TPL_ASTROID_BASIC_DEFAULT_LOGO_WIDTH_LABEL',
                'description'=> 'TPL_ASTROID_BASIC_DEFAULT_LOGO_WIDTH_DESC',
                "attributes" => [
                    'hint'       => '200px',
                ],
                'conditions' => "[header]==true AND [logo_type]=='image'",
            ],

            'default_logo_height' => [
                'group'      => 'header_logo_options_element',
                'type'       => 'text',
                'name'       => 'default_logo_height',
                'label'      => 'TPL_ASTROID_BASIC_DEFAULT_LOGO_HEIGHT_LABEL',
                'description'=> 'TPL_ASTROID_BASIC_DEFAULT_LOGO_HEIGHT_DESC',
                "attributes" => [
                    'hint'       => '60px',
                ],
                'conditions' => "[header]==true AND [logo_type]=='image'",
            ],

            'mobile_logo' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'media',
                'name'        => 'mobile_logo',
                'label'       => 'TPL_ASTROID_BASIC_MOBILE_LOGO_LABEL',
                'description' => 'TPL_ASTROID_BASIC_MOBILE_LOGO_DESC',
                'conditions'  => "[header]==true AND [logo_type]=='image'",
            ],

            'mobile_logo_dark' => [
                'group'       => 'header_logo_options_element',
                'type'        => 'media',
                'name'        => 'mobile_logo_dark',
                'label'       => 'TPL_ASTROID_BASIC_MOBILE_LOGO_DARK_LABEL',
                'description' => 'TPL_ASTROID_BASIC_MOBILE_LOGO_DARK_DESC',
                'conditions'  => "[header]==true AND [logo_type]=='image' AND [astroid_color_mode_enable]=='1'",
            ],

            'mobile_logo_width' => [
                'group'      => 'header_logo_options_element',
                'type'       => 'text',
                'name'       => 'mobile_logo_width',
                'label'      => 'TPL_ASTROID_BASIC_MOBILE_LOGO_WIDTH_LABEL',
                'description'=> 'TPL_ASTROID_BASIC_MOBILE_LOGO_WIDTH_DESC',
                "attributes" => [
                    'hint'       => '200px',
                ],
                'conditions' => "[header]==true AND [logo_type]=='image'",
            ],

            'mobile_logo_height' => [
                'group'      => 'header_logo_options_element',
                'type'       => 'text',
                'name'       => 'mobile_logo_height',
                'label'      => 'TPL_ASTROID_BASIC_MOBILE_LOGO_HEIGHT_LABEL',
                'description'=> 'TPL_ASTROID_BASIC_MOBILE_LOGO_HEIGHT_DESC',
                "attributes" => [
                    'hint'       => '60px',
                ],
                'conditions' => "[header]==true AND [logo_type]=='image'",
            ],

            'enable_sticky_menu' => [
                'group' => 'header_sticky_options_element',
                'type' => 'radio',
                'label' => 'enable_sticky_menu',
                'description' => 'enable_sticky_menu_desc',
                'attributes' => [
                    "role" => "switch"
                ],
                'default' => '1',
                'conditions' => "[header]==true AND [header_mode]!='sidebar'",
            ],

            'header_sticky_container_type' => [
                'group' => 'header_sticky_options_element',
                'type' => 'list',
                'label' => 'container_type',
                'description' => 'container_type_desc',
                'default' => '',
                'options' => [
                    '' => 'default',
                    'container' => 'ASTROID_CONTAINER',
                    'container-fluid' => 'ASTROID_CONTAINER_FLUID',
                    'container-with-no-gutters' => 'ASTROID_CONTAINER_WITH_NO_GUTTERS',
                    'container-fluid-with-no-gutters' => 'ASTROID_CONTAINER_FLUID_WITH_NO_GUTTERS',
                    'no-container' => 'ASTROID_ELEMENT_LAYOUT_SECTION_LAYOUT_OPTIONS_WITHOUT_CONTAINER',
                    'custom-container' => 'ASTROID_ELEMENT_LAYOUT_SECTION_LAYOUT_OPTIONS_CUSTOM',
                ],
                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [header_mode]!='sidebar'",
            ],

//            'stickey_horizontal_menu_mode' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'radio',
//                'name' => 'stickey_horizontal_menu_mode',
//                "label" => "horizontal_menu_mode",
//                "description" => "horizontal_menu_mode_desc",
//                "default" => "left",
//                "attributes" => [
//                    "role" => "image"
//                ],
//                "options" => [
//                    'left'   => '/local/moon/assets/images/header/horizontal-left.svg',
//                    'center' => '/local/moon/assets/images/header/horizontal-center.svg',
//                    'right'  => '/local/moon/assets/images/header/horizontal-right.svg',
//                ],
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [header_mode]!='sidebar'",
//            ],
//
//            'stickey_header_logo' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'media',
//                'name' => 'stickey_header_logo',
//                'label' => 'TPL_ASTROID_BASIC_STICKY_HEADER_LOGO_LABEL',
//                'description' => 'TPL_ASTROID_BASIC_STICKY_HEADER_LOGO_DESC',
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [logo_type]=='image' AND [header_mode]!='sidebar' AND ([astroid_color_mode_enable]=='1' OR [astroid_color_mode_enable]=='0')",
//            ],
//
//            'stickey_header_logo_dark' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'media',
//                'name' => 'stickey_header_logo_dark',
//                'label' => 'TPL_ASTROID_BASIC_STICKY_HEADER_LOGO_DARK_LABEL',
//                'description' => 'TPL_ASTROID_BASIC_STICKY_HEADER_LOGO_DARK_DESC',
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [logo_type]=='image' AND [header_mode]!='sidebar' AND ([astroid_color_mode_enable]=='1' OR [astroid_color_mode_enable]=='2')",
//            ],
//
//            'sticky_logo_width' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'text',
//                'name' => 'sticky_logo_width',
//                'label' => 'TPL_ASTROID_BASIC_STICKY_LOGO_WIDTH_LABEL',
//                'description' => 'TPL_ASTROID_BASIC_STICKY_LOGO_WIDTH_DESC',
//                'attributes' => [
//                    'hint' => '200px',
//                ],
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [logo_type]=='image' AND [header_mode]!='sidebar'",
//            ],
//
//            'sticky_logo_height' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'text',
//                'name' => 'sticky_logo_height',
//                'label' => 'TPL_ASTROID_BASIC_STICKY_LOGO_HEIGHT_LABEL',
//                'description' => 'TPL_ASTROID_BASIC_STICKY_LOGO_HEIGHT_DESC',
//                'attributes' => [
//                    'hint' => '60px',
//                ],
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [logo_type]=='image' AND [header_mode]!='sidebar'",
//            ],
//
//            'stickey_block_1_type' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'list',
//                'label' => 'header_block_1',
//                'description' => 'header_block_1_desc',
//                'default' => 'blank',
//                'options' => [
//                    'blank'   => 'blank',
//                    'position'=> 'region',
//                    'custom'  => 'custom',
//                ],
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [header_mode]!='sidebar'",
//            ],
//
//            'stickey_block_1_position' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'regions',
//                'label' => 'block_1_position',
//                'description' => 'block_1_position_desc',
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [stickey_block_1_type] == 'position' AND [header_mode]!='sidebar'",
//            ],
//
//            'stickey_block_1_custom' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'textarea',
//                'label' => 'block_1_custom',
//                'description' => 'block_1_custom_desc',
//                "attributes" => [
//                    'filter' => 'raw',
//                ],
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [stickey_block_1_type] == 'custom' AND [header_mode]!='sidebar'",
//            ],
//
//            'enable_sticky_badge' => [
//                'group' => 'header_sticky_options_element',
//                'type' => 'radio',
//                'label' => 'TPL_ASTROID_HEADER_STICKY_HEADER_ENABLE_BADGE_LABEL',
//                'description' => 'TPL_ASTROID_HEADER_STICKY_HEADER_ENABLE_BADGE_DESC',
//                'attributes' => [
//                    'role' => 'switch',
//                ],
//                'default' => '0',
//                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [header_mode]!='sidebar'",
//            ],

            'stickyheader' => [
                'group' => 'header_sticky_options_element',
                'type' => 'list',
                'label' => 'sticky_on_desktop',
                'description' => 'sticky_on_desktop_desc',
                'default' => 'sticky',
                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [header_mode]!='sidebar'",
                'options' => [
                    'sticky' => 'sticky',
                    'stickyonscroll' => 'sticky_on_scrollup',
                ],
            ],

            'stickyheadertablet' => [
                'group' => 'header_sticky_options_element',
                'type' => 'list',
                'label' => 'sticky_on_tablet',
                'description' => 'sticky_on_tablet_desc',
                'default' => 'static',
                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [header_mode]!='sidebar'",
                'options' => [
                    'static' => 'static',
                    'sticky' => 'sticky',
                    'stickyonscroll' => 'sticky_on_scrollup',
                ],
            ],

            'stickyheadermobile' => [
                'group' => 'header_sticky_options_element',
                'type' => 'list',
                'name' => 'stickyheadermobile',
                'label' => 'sticky_on_mobile',
                'description' => 'sticky_on_mobile_desc',
                'default' => 'static',
                'conditions' => "[header]==true AND [enable_sticky_menu]==true AND [header_mode]!='sidebar'",
                'options' => [
                    'static' => 'static',
                    'sticky' => 'sticky',
                    'stickyonscroll' => 'sticky_on_scrollup',
                ],
            ],
        ]
    ]
);