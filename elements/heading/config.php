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
class moon_element_heading extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'heading',
            'title' => 'Heading',
            'description' => 'Heading Widget of Moodle',
            'icon' => 'fa-solid fa-heading',
            'category' => 'typography',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');


        $this->add_field('title_options', [
            'type'  => 'group',
            'label' => 'title_options',
        ]);

        $this->add_field('meta_options',  [
            "type" => "group",
            "label" => "meta_options",
        ]);

        $this->add_field('title', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "title",
            "dynamic"     => true,
        ]);

        $this->add_field('use_link', [
            "group"       => "general",
            "type"        => "radio",
            "label"       => "use_link",
            "description" => "use_link_desc",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => 0,
        ]);

        $this->add_field('link', [
            "group"      => "general",
            "type"       => "text",
            "label"      => "link_url",
            "description"=> "link_url_desc",
            "name"       => "link",
            "hint"       => "https://astroidframe.work/",
            "conditions" => "[use_link]==1",
        ]);

        $this->add_field('add_icon', [
            "group"       => "general",
            "type"        => "radio",
            "label"       => "add_icon",
            "description" => "add_icon_desc",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => 0,
        ]);

        $this->add_field('icon', [
            "group"      => "general",
            "type"       => "icons",
            "label"      => "icon",
            "default"    => "fa-solid fa-heading",
            "conditions" => "[add_icon]==1",
        ]);

        $this->add_field('icon_color', [
            "group"      => "general",
            "type"       => "color",
            "label"      => "icon_color",
            "conditions" => "[add_icon]==1",
        ]);

        $this->add_field('html_element', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "html_element",
            "default"    => "h2",
            "conditions" => "[heading]!==''",
            "options"    => [
                "h1" => "h1",
                "h2" => "h2",
                "h3" => "h3",
                "h4" => "h4",
                "h5" => "h5",
                "h6" => "h6",
                "div" => "div",
            ],
        ]);

        $this->add_field('font_style', [
            "group"      => "general",
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

        $this->add_field('title_heading_margin', [
            "group" => "general",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->add_field('title_clone', [
            "group"       => "general",
            "type"        => "radio",
            "label"       => "title_clone",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => 0,
        ]);
        $this->add_field('title_clone_txt', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "title_custom_clone",
            "dynamic"     => true,
            "conditions" => "[title_clone]==1",
        ]);
        $this->add_field('title_clone_margin', [
            "group" => "general",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->add_field('title_clone_font_style', [
            "group"   => "general",
            "type"    => "typography",
            "label"   => "font_style",
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

        $this->add_field('meta_text', [
            "group"       => "meta_options",
            "type"        => "text",
            "label"       => "meta",
            "dynamic"     => true,
        ]);

        $this->add_field('meta_font_style', [
            "group"   => "meta_options",
            "type"    => "typography",
            "label"   => "font_style",
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

        $this->add_field('meta_heading_margin', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->add_field('meta_heading_padding', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "padding",
        ]);
        $this->add_field('meta_border', [
            "group"      => "meta_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('meta_radius', [
            'group' => 'meta_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
        ]);
        $this->add_field('meta_line', [
            "group"       => "meta_options",
            "type"        => "radio",
            "label"       => "meta_line",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => 0,
        ]);
        $this->add_field('line_width', [
            'group'   => 'meta_options',
            'type'    => 'range',
            'label'      => 'width',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
            "conditions" => "[meta_line]==1",
        ]);
        $this->add_field('line_height', [
            'group'   => 'meta_options',
            'type'    => 'range',
            'label'      => 'height',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
            "conditions" => "[meta_line]==1",
        ]);
        $this->add_field('line_color', [
            "group"      => "meta_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[meta_line]==1",
        ]);

        $this->add_field('meta_position', [
            "group"   => "meta_options",
            "type"    => "list",
            "label"   => "meta_position",
            "default" => "before",
            "options" => [
                "before" => "before_title",
                "after"  => "after_title",
            ],
        ]);

    }
}