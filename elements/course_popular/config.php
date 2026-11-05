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
use local_moon\library\helper\moon_element;
use local_moon\library\helper\font;
class moon_element_course_popular extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'course_popular',
            'title' => 'Course Popular',
            'description' => 'Course Popular',
            'icon' => 'fa-solid fa-sliders',
            'category' => 'Course',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('title_options',  [
            "type" => "group",
            "label" => "title_options",
        ]);

        $this->add_field('description_options',  [
            "type" => "group",
            "label" => "description_options",
        ]);
        $this->add_field('item_options',  [
            "type" => "group",
            "label" => "item_options",
        ]);
        $this->add_field('image_options',  [
            "type" => "group",
            "label" => "image_options",
        ]);
        $this->add_field('slider_options',  [
            "type" => "group",
            "label" => "slider_options",
        ]);
        $this->add_field('course_style', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "style",
            "default"    => "style1",
            "options"    => [
                "style1" => "style1",
                "style2" => "style2",
            ],
        ]);
        $this->add_field('course_limit', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "course_limit",
            "dynamic"     => true,
        ]);
        $this->add_field('title', [
            "group"       => "title_options",
            "type"        => "text",
            "label"       => "title",
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
        $this->add_field('show_description', [
            'group'   => 'description_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'show_description',
        ]);
        $this->add_field('description_limit', [
            'group'   => 'description_options',
            'type'    => 'range',
            'label'      => 'limit_words',
            "attributes" => [
                'min'        => 1,
                'max'        => 1000,
                'step'       => 1,
            ],
            'default' => 20,
            'conditions' => "[show_description]==1",
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
        $this->add_field('item_border_radius', [
            'group' => 'item_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);

        $this->add_field('item_card_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'card_padding',
        ]);

        $this->add_field('content_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'content_padding',
        ]);
        $this->add_field('image_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);
        $this->add_field('column_responsive', [
            "group"   => "slider_options",
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
        ]);

        $this->add_field('xxl_column', [
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "xxl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "xl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "lg_column",
            "default"    => "3",
            "conditions" => "[column_responsive]=='lg'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "md_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='md'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "sm_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='sm'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "xs_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='xs'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "row_gutter_xxl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "row_gutter_xl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "row_gutter_lg",
            "default"    => "4",
            "conditions" => "[column_responsive]=='lg'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "row_gutter_md",
            "default"    => "3",
            "conditions" => "[column_responsive]=='md'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "row_gutter_sm",
            "default"    => "3",
            "conditions" => "[column_responsive]=='sm'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "row_gutter_xs",
            "default"    => "3",
            "conditions" => "[column_responsive]=='xs'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "column_gutter_xxl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "column_gutter_xl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "column_gutter_lg",
            "default"    => "4",
            "conditions" => "[column_responsive]=='lg'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "column_gutter_md",
            "default"    => "3",
            "conditions" => "[column_responsive]=='md'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "column_gutter_sm",
            "default"    => "3",
            "conditions" => "[column_responsive]=='sm'",
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
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "column_gutter_xs",
            "default"    => "3",
            "conditions" => "[column_responsive]=='xs'",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('autoplay', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'autoplay',
        ]);
        $this->add_field('slider_visible', [
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "slider_visible",
            "default"    => "",
            "options"    => [
                "" => "default",
                "moon-visible-left" => "visible_left",
                "moon-visible-right" => "visible_right",
                "moon-visible-all" => "visible_all",
            ],
        ]);
        $this->add_field('slider_padding', [
            'group'      => 'slider_options',
            'type'       => 'spacing',
            'label'      => 'slider_padding',
        ]);
        $this->add_field('item_padding', [
            'group'      => 'slider_options',
            'type'       => 'spacing',
            'label'      => 'item_padding',
        ]);
        $this->add_field('navigation', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Navigation',
        ]);

        $this->add_field('dot', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => 1,
            'label'   => 'Dotnav',
        ]);
        $this->add_field('dot_margin', [
            "group" => "slider_options",
            "type"  => "spacing",
            "label" => "margin",
            'conditions' => "[dot]==1",
        ]);

    }
}