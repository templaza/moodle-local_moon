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
class moon_element_list extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'list',
            'title' => 'List',
            'description' => 'List Widget of Moodle',
            'icon' => 'as-icon as-icon-list2',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('misc_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "misc_options",
        ]);

        $this->add_field('title_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "title_options",
        ]);
        $this->add_field('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);

        $this->add_field('content_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "content_options",
        ]);

        $this->add_field('spacing_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "spacing_options",
        ]);

        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'title' => [
                        "type"    => "text",
                        "label"   => "title",
                        "class"   => "form-control",
                        "dynamic" => true,
                    ],
                    'description' => [
                        "type"    => "editor",
                        "label"   => "description",
                        "dynamic" => true,
                    ],
                    'icon_type' => [
                        "type"    => "list",
                        "label"   => "icon_type",
                        "default" => "fontawesome",
                        "options" => [
                            "fontawesome" => "fontawesome",
                            "custom"      => "custom",
                        ],
                    ],
                    'fa_icon' => [
                        "type"       => "icons",
                        "label"      => "fa_icon",
                        "conditions" => "[icon_type]=='fontawesome'",
                    ],
                    'custom_icon' => [
                        "type"       => "text",
                        "label"      => "custom_icon",
                        "dynamic"    => true,
                        "conditions" => "[icon_type]=='custom'",
                    ],
                    'icon_color_item' => [
                        "type"       => "color",
                        "label"      => "color",
                    ],
                    'icon_bg_item' => [
                        "type"       => "color",
                        "label"      => "background_color",
                    ],
                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);

        $this->add_field('list_items', [
            "group" => "general",
            "type"  => "subform",
            "label" => "list_items",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
        ]);
        $this->add_field('title_heading', [
            "group" => "general",
            "type"  => "text",
            "label"  => "heading",
        ]);
        $this->add_field('heading_font_style', [
            "group"      => "general",
            "type"       => "typography",
            "label"       => "font_style",
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
                'value' => font::$get_default_font_value,
            ],
            "conditions" => "[title_heading] !=''",
        ]);
        $this->add_field('heading_margin', [
            "group" => "general",
            "type"  => "spacing",
            "label"  => "heading_margin",
            "conditions" => "[title_heading] !=''",
        ]);

        $this->add_field('list_style', [
            "group"   => "misc_options",
            "type"    => "list",
            "label"   => "list_style",
            "default" => "ul",
            "options" => [
                "ul"                      => "Unordered List",
                "ol"                      => "Ordered List",
                "list-unstyled"           => "Unstyled List",
                "list-inline"             => "Inline",
                "list-description"        => "Description List",
                "list-group"              => "List Group",
                "list-group-flush"        => "List Group Flush",
                "list-group-numbered"     => "List Group Numbered",
                "custom"     => "custom",
            ],
        ]);

        $this->add_field('vertical_align', [
            "group"   => "misc_options",
            "type"    => "list",
            "label"   => "vertical_alignment",
            "default" => "uk-flex-top",
            "options" => [
                "uk-flex-top"             => "top",
                "uk-flex-middle"          => "middle",
                "uk-flex-bottom"          => "bottom",
            ],
            "conditions" => "[list_style]=='custom'",
        ]);

        $this->add_field('title_width', [
            "group"      => "misc_options",
            "type"       => "range",
            "label"       => "title_width",
            "min"        => 1,
            "max"        => 12,
            "step"       => 1,
            "default"    => 3,
            "postfix"    => "cols",
            "conditions" => "[list_style]=='list-description'",
        ]);

        $this->add_field('title_html_element', [
            "group"   => "title_options",
            "type"    => "list",
            "label"    => "title_html_element",
            "default" => "h6",
            "options" => [
                "h1" => "h1",
                "h2" => "h2",
                "h3" => "h3",
                "h4" => "h4",
                "h5" => "h5",
                "h6" => "h6",
                "div"=> "div",
            ],
        ]);

        $this->add_field('title_font_style', [
            "group"      => "title_options",
            "type"       => "typography",
            "label"       => "title_font_style",
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
                'value' => font::$get_default_font_value,
            ],
        ]);

        $this->add_field('title_heading_margin', [
            "group" => "title_options",
            "type"  => "spacing",
            "label"  => "margin",
        ]);
        $this->add_field('icon_color', [
            'group' => 'icon_options',
            'type'  => 'color',
            'label' => 'icon_color',
        ]);
        $this->add_field('icon_bg_color', [
            'group' => 'icon_options',
            'type'  => 'color',
            'label' => 'background_color',
        ]);
        $this->add_field('icon_size', [
            'group'      => 'icon_options',
            'type'       => 'range',
            'label'      => 'icon_size',
            "attributes" => [
                'min'        => 1,
                'max'        => 300,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 30,
        ]);
        $this->add_field('icon_margin', [
            "group" => "icon_options",
            "type"  => "spacing",
            "label"  => "icon_margin",
        ]);
        $this->add_field('icon_padding', [
            "group" => "icon_options",
            "type"  => "spacing",
            "label"  => "icon_padding",
        ]);
        $this->add_field('icon_width', [
            'group'   => 'icon_options',
            'type'    => 'range',
            'label'      => 'width',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->add_field('icon_height', [
            'group'   => 'icon_options',
            'type'    => 'range',
            'label'      => 'height',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->add_field('icon_border', [
            "group"      => "icon_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('icon_radius', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);

        $this->add_field('content_font_style', [
            "group"      => "content_options",
            "type"       => "typography",
            "label"       => "content_font_style",
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
                'value' => font::$get_default_font_value,
            ],
        ]);

        $this->add_field('item_margin', [
            "group" => "spacing_options",
            "type"  => "spacing",
            "label"  => "margin",
        ]);

        $this->add_field('item_padding', [
            "group" => "spacing_options",
            "type"  => "spacing",
            "label"  => "padding",
        ]);
    }
}