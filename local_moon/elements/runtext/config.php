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
class moon_element_runtext extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'runtext',
            'title' => 'Run Text',
            'description' => 'Run Text Widget of Moodle',
            'icon' => 'as-icon as-icon-text-wrap',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('widget_styles', [
            'type'  => 'group',
            'label' => 'widget_styles',
        ]);
        $this->add_field('title_styles', [
            'type'  => 'group',
            'label' => 'title_options',
        ]);
        $this->add_field('icon_styles', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'title' => [
                        'type'    => 'text',
                        'class'   => 'form-control',
                        'label'   => 'title',
                        'dynamic' => true,
                    ],
                    'title_color' => [
                        'type'   => 'color',
                        'label'  => 'color',
                        'conditions' => "[title] !=''",
                    ],
                    'icon' => [
                        'type'    => 'icons',
                        'label'   => 'icon',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'dynamic' => true,
                    ],
                    'icon_color' => [
                        'type'   => 'color',
                        'label'  => 'color',
                        'conditions' => "[icon] !=''",
                    ],

                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('texts',  [
            "group" => "general",
            "type" => "subform",
            "label" => "buttons",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
        ]);
        $this->add_field('item_margin', [
            'group' => 'general',
            'type'  => 'spacing',
            'label' => 'item_margin',
        ]);

        $this->add_field('text_font_style', [
            "group"      => "title_styles",
            "label"      => "font_style",
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
                'value' => font::$get_default_font_value,
            ],
        ]);
        $this->add_field('text_stroke', [
            'group'   => 'title_styles',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Text stroke',
        ]);
        $this->add_field('text_stroke_color', [
            "group"      => "title_styles",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[text_stroke]==1",
        ]);
        $this->add_field('text_stroke_width', [
            'group'   => 'title_styles',
            'type'    => 'range',
            'label'      => 'stroke width',
            "attributes" => [
                'min'        => 1,
                'max'        => 100,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px',
            ],
            'default' => 1,
            "conditions" => "[text_stroke]==1",
        ]);

        $this->add_field('title_icon_size', [
            'group'      => 'icon_styles',
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
            'group' => 'icon_styles',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

    }
}