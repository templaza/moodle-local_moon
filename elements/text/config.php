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
class moon_element_text extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'text',
            'title' => 'Text',
            'description' => 'Text Widget of Moodle',
            'icon' => 'as-icon as-icon-text-size',
            'category' => 'typography',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('content_options',  [
            "type" => "group",
            "label" => "content_options",
        ]);

        $this->add_field('heading',  [
            "group" => "general",
            "type" => "text",
            "label" => "heading",
            "description" => "heading_desc",
            "dynamic" => true,
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
            "label"      => "font_style",
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
            "conditions" => "[heading]!==''",
        ]);

        $this->add_field('heading_margin', [
            "group"      => "general",
            "type"       => "spacing",
            "label"      => "margin",
            "conditions" => "[heading]!==''",
        ]);

        $this->add_field('content', [
            "group"   => "content_options",
            "type"    => "editor",
            "label"   => "content",
            "dynamic" => true,
        ]);

        $this->add_field('text_column_responsive', [
            "group"      => "content_options",
            "type"       => "radio",
            "width"      => "full",
            "default"    => "lg",
            "conditions" => "[content]!==''",
            "options"    => [
                "xxl" => "xxl_icon",
                "xl"  => "xl_icon",
                "lg"  => "lg_icon",
                "md"  => "md_icon",
                "sm"  => "sm_icon",
                "xs"  => "xs_icon",
            ],
        ]);

        $this->add_field('text_column_xxl', [
            "group"      => "content_options",
            "type"       => "list",
            "label"      => "xxl_column",
            "default"    => "",
            "conditions" => "[content]!=='' AND [text_column_responsive]=='xxl'",
            "options"    => [
                ""     => "inherit",
                "1-2"  => "1/2",
                "1-3"  => "1/3",
                "1-4"  => "1/4",
                "1-5"  => "1/5",
                "1-6"  => "1/6",
            ],
        ]);

        $this->add_field('text_column_xl', [
            "group"      => "content_options",
            "type"       => "list",
            "label"      => "xl_column",
            "default"    => "",
            "conditions" => "[content]!=='' AND [text_column_responsive]=='xl'",
            "options"    => [
                ""     => "inherit",
                "1-2"  => "1/2",
                "1-3"  => "1/3",
                "1-4"  => "1/4",
                "1-5"  => "1/5",
                "1-6"  => "1/6",
            ],
        ]);

        $this->add_field('text_column_lg', [
            "group"      => "content_options",
            "type"       => "list",
            "label"      => "lg_column",
            "default"    => "",
            "conditions" => "[content]!=='' AND [text_column_responsive]=='lg'",
            "options"    => [
                ""     => "inherit",
                "1-2"  => "1/2",
                "1-3"  => "1/3",
                "1-4"  => "1/4",
                "1-5"  => "1/5",
                "1-6"  => "1/6",
            ],
        ]);

        $this->add_field('text_column_md', [
            "group"      => "content_options",
            "type"       => "list",
            "label"      => "md_column",
            "default"    => "",
            "conditions" => "[content]!=='' AND [text_column_responsive]=='md'",
            "options"    => [
                ""     => "inherit",
                "1-2"  => "1/2",
                "1-3"  => "1/3",
                "1-4"  => "1/4",
                "1-5"  => "1/5",
                "1-6"  => "1/6",
            ],
        ]);

        $this->add_field('text_column_sm', [
            "group"      => "content_options",
            "type"       => "list",
            "label"      => "sm_column",
            "default"    => "",
            "conditions" => "[content]!=='' AND [text_column_responsive]=='sm'",
            "options"    => [
                ""     => "inherit",
                "1-2"  => "1/2",
                "1-3"  => "1/3",
                "1-4"  => "1/4",
                "1-5"  => "1/5",
                "1-6"  => "1/6",
            ],
        ]);

        $this->add_field('text_column_xs', [
            "group"      => "content_options",
            "type"       => "list",
            "label"      => "xs_column",
            "default"    => "",
            "conditions" => "[content]!=='' AND [text_column_responsive]=='xs'",
            "options"    => [
                ""     => "inherit",
                "1-2"  => "1/2",
                "1-3"  => "1/3",
                "1-4"  => "1/4",
                "1-5"  => "1/5",
                "1-6"  => "1/6",
            ],
        ]);

        $this->add_field('content_divider', [
            "group" => "content_options",
            "type"  => "divider",
            "name"  => "content_divider",
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
            "label"      => "font_style",
            "conditions" => "[content]!==''",
        ]);
    }
}