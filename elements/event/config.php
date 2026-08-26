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
use local_moon\library\blocks\event_handler;
class moon_element_event extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'event',
            'title' => 'Event',
            'description' => 'List Event of Moodle',
            'icon' => 'as-icon as-icon-list2',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);

    }
    public function set_fields(): void {
        $moonEventHandler = new event_handler();
        $events = $moonEventHandler->moon_get_moodle_events_options();

        $this->set_field_set('general-settings');

        $this->add_field('title_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "title_options",
        ]);

        $this->add_field('content_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "content_options",
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

                    'event' => [
                        "type"    => "list",
                        "label"   => "event",
                        "default" => "",
                        "options" => $events,
                    ],

                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);

        $this->add_field('list_events', [
            "group" => "general",
            "type"  => "subform",
            "label" => "list_items",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
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
            "label"  => "title_heading_margin",
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