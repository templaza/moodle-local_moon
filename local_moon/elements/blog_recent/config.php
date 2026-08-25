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
use local_moon\library\helper\font;
use local_moon\library\blocks\course_handler;
class moon_element_blog_recent extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'blog_recent',
            'title' => 'Blog Recent',
            'description' => 'get latest blog',
            'icon' => 'as-icon as-icon-list4',
            'category' => 'Blog',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('grid_options',  [
            "type" => "group",
            "label" => "grid_options",
        ]);
        $this->add_field('item_options',  [
            "type" => "group",
            "label" => "item_options",
        ]);
        $this->add_field('title_options',  [
            "type" => "group",
            "label" => "title_options",
        ]);
        $this->add_field('meta_options',  [
            "type" => "group",
            "label" => "meta_options",
        ]);
        $this->add_field('content_options',  [
            "type" => "group",
            "label" => "content_options",
        ]);
        $this->add_field('image_options',  [
            "type" => "group",
            "label" => "image_options",
        ]);

        $this->add_field('slider_options',  [
            "type" => "group",
            "label" => "slider_options",
        ]);
        $this->add_field('blog_style', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "style",
            "default"    => "style1",
            "options"    => [
                "style1" => "style1",
                "style2" => "style2",
                "style3" => "style3",
            ],
        ]);
        $this->add_field('blog_since', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "blog_recent_time",
            "options"    => [
                7200 => get_string('numhours', '', 2),
                14400 => get_string('numhours', '', 4),
                21600 => get_string('numhours', '', 6),
                43200 => get_string('numhours', '', 12),
                86400 => get_string('numhours', '', 24),
                172800 => get_string('numdays', '', 2),
                604800 => get_string('numdays', '', 7),
                1209600 => get_string('numdays', '', 14),
                2592000 => get_string('numdays', '', 30),
                5184000 => get_string('numdays', '', 60),
                31104000 => '1_year',
                155520000 => '5_years'
            ],
        ]);

        $this->add_field('blog_limit', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "limit",
            "dynamic"     => true,
        ]);
        $this->add_field('title_font_style', [
            "group"      => "title_options",
            "type"       => "typography",
            "attributes" => [
                'options' => [
                    "colorpicker" => true,
                    'stylepicker' => true,
                    'fontpicker' => true,
                    'sizepicker' => true,
                    'letterspacingpicker' => true,
                    'lineheightpicker' => true,
                    'weightpicker' => true,
                    'transformpicker' => true,
                    'columns' => 1,
                    'preview' => false,
                    'collapse' => true,
                    'system_fonts' => font::get_system_fonts(),
                    'text_transform_options' => font::text_transform(),
                    'lang' => font::font_properties(),
                ],
                'lang' => font::font_properties(),
                'value' => font::$get_default_font_value
            ],
        ]);
        $this->add_field('title_margin', [
            "group" => "title_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->add_field('show_content', [
            'group'   => 'content_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'show_content',
        ]);
        $this->add_field('content_font_style', [
            "group"      => "content_options",
            "type"       => "typography",
            "attributes" => [
                'options' => [
                    "colorpicker" => true,
                    'stylepicker' => true,
                    'fontpicker' => true,
                    'sizepicker' => true,
                    'letterspacingpicker' => true,
                    'lineheightpicker' => true,
                    'weightpicker' => true,
                    'transformpicker' => true,
                    'columns' => 1,
                    'preview' => false,
                    'collapse' => true,
                    'system_fonts' => font::get_system_fonts(),
                    'text_transform_options' => font::text_transform(),
                    'lang' => font::font_properties(),
                ],
                'lang' => font::font_properties(),
                'value' => font::$get_default_font_value
            ],
        ]);
        $this->add_field('content_margin', [
            "group" => "content_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->add_field('meta_font_style', [
            "group"      => "meta_options",
            "type"       => "typography",
            "attributes" => [
                'options' => [
                    "colorpicker" => true,
                    'stylepicker' => true,
                    'fontpicker' => true,
                    'sizepicker' => true,
                    'letterspacingpicker' => true,
                    'lineheightpicker' => true,
                    'weightpicker' => true,
                    'transformpicker' => true,
                    'columns' => 1,
                    'preview' => false,
                    'collapse' => true,
                    'system_fonts' => font::get_system_fonts(),
                    'text_transform_options' => font::text_transform(),
                    'lang' => font::font_properties(),
                ],
                'lang' => font::font_properties(),
                'value' => font::$get_default_font_value
            ],
        ]);
        $this->add_field('show_author', [
            'group'   => 'meta_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'show_author',
        ]);
        $this->add_field('show_comment', [
            'group'   => 'meta_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'show_comment',
        ]);

        $this->add_field('meta_margin', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->add_field('image_position', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "image_position",
            "default" => "",
            "options" => [
                ""    => "top",
                "left"   => "left",
            ],
        ]);
        $this->add_field('media_column_responsive', [
            "group"   => "image_options",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "lg",
            "conditions" => "[image_position]=='left'",
            "options" => [
                "xxl" => "xxl_icon",
                "xl"  => "xl_icon",
                "lg"  => "lg_icon",
                "md"  => "md_icon",
                "sm"  => "sm_icon",
                "xs"  => "xs_icon",
            ],
        ]);

        // media columns (xxl/xl/lg/md/sm/xs) with conditions
        $this->add_field('xxl_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "xxl_column_media_width",
            "default"    => "",
            "conditions" => "[media_column_responsive]=='xxl' AND [image_position]=='left'",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('xl_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "xl_column_media_width",
            "default"    => "",
            "conditions" => "[media_column_responsive]=='xl' AND [image_position]=='left'",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('lg_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "lg_column_media_width",
            "default"    => "4",
            "conditions" => "[media_column_responsive]=='lg' AND [image_position]=='left'",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('md_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "md_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='md' AND [image_position]=='left'",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('sm_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "sm_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='sm' AND [image_position]=='left'",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('xs_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "xs_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='xs' AND [image_position]=='left'",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('vertical_middle', [
            "group"      => "image_options",
            "type"       => "radio",
            "default"    => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"      => "vertical_middle",
            "conditions" => "[image_position]=='left'",
        ]);
        $this->add_field('image_border_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'label' => 'border_radius',
        ]);
        $this->add_field('image_min_height', [
            'group'      => 'image_options',
            'type'       => 'range',
            'label'      => 'min_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 1000,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 300,
        ]);
        $this->add_field('image_margin', [
            'group'      => 'image_options',
            'type'       => 'spacing',
            'label'      => 'margin',
        ]);
        $this->add_field('item_bg_color', [
            "group"      => "item_options",
            "type"       => "color",
            "label"      => "background_color",
        ]);
        $this->add_field('item_border', [
            "group"      => "item_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('item_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'padding',
        ]);
        $this->add_field('item_border_radius', [
            'group' => 'item_options',
            'type'  => 'spacing',
            'label' => 'border_radius',
        ]);
        $this->add_field('item_content_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'content_padding',
        ]);
        $this->add_field('column_responsive', [
            "group"   => "grid_options",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "lg",
            "options" => [
                'xxl' => 'xxl_icon',
                'xl'  => 'xl_icon',
                'lg'  => 'lg_icon',
                'md'  => 'md_icon',
                'sm'  => 'sm_icon',
                'xs'  => 'xs_icon',
            ],
            "conditions" => "[blog_style]!='style2'",
        ]);

        $this->add_field('xxl_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xxl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('xl_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('lg_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "lg_column",
            "default"    => "3",
            "conditions" => "[column_responsive]=='lg' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('md_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "md_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='md' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('sm_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "sm_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='sm' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('xs_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xs_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='xs' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('row_gutter_xxl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xxl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter_xl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter_lg', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_lg",
            "default"    => "4",
            "conditions" => "[column_responsive]=='lg' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter_md', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_md",
            "default"    => "3",
            "conditions" => "[column_responsive]=='md' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter_sm', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_sm",
            "default"    => "3",
            "conditions" => "[column_responsive]=='sm' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xs",
            "default"    => "3",
            "conditions" => "[column_responsive]=='xs' AND [blog_style]!='style2'",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_xxl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xxl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_xl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_lg', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_lg",
            "default"    => "4",
            "conditions" => "[column_responsive]=='lg' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_md', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_md",
            "default"    => "3",
            "conditions" => "[column_responsive]=='md' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_sm', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_sm",
            "default"    => "3",
            "conditions" => "[column_responsive]=='sm' AND [blog_style]!='style2'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xs",
            "default"    => "3",
            "conditions" => "[column_responsive]=='xs' AND [blog_style]!='style2'",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);
        $this->add_field('enable_slider', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'enable_slider',
            "conditions" => "[blog_style]!='style2'",
        ]);
        $this->add_field('autoplay', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'autoplay',
            "conditions" => "[blog_style]!='style2' AND [enable_slider]==1",
        ]);

        $this->add_field('navigation', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Navigation',
            "conditions" => "[blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->add_field('navigation_color', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->add_field('navigation_bg_color', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->add_field('navigation_color_hover', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->add_field('navigation_bg_color_hover', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "background_hover_color",
            "conditions" => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->add_field('navigation_padding', [
            'group'      => 'slider_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);

        $this->add_field('dot', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => 1,
            'label'   => 'Dotnav',
            "conditions" => "[blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->add_field('dot_margin', [
            "group" => "slider_options",
            "type"  => "spacing",
            "label" => "margin",
            'conditions' => "[dot]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);

    }
}