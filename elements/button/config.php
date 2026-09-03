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
use local_moon\library\helper\form;
use local_moon\library\helper\constants;
use local_moon\library\helper\font;
class moon_element_button extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'button',
            'title' => 'Button',
            'description' => 'Button Widget of Moodle',
            'icon' => 'as-icon as-icon-toggle-on',
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
                    'link' => [
                        'type'        => 'text',
                        'label'       => 'link_url',
                        'description' => 'link_url_desc',
                        'name'        => 'link',
                        "attributes" => [
                            'hint'        => 'https://astroidframe.work/',
                        ],
                        'dynamic'     => true,
                    ],
                    'link_target' => [
                        'conditions'  => "[link]!=''",
                        'type'    => 'list',
                        'label'   => 'link_target',
                        'default' => '',
                        'options' => [
                            ''        => 'Default',
                            '_blank'  => 'New Window',
                            '_parent' => 'Parent Frame',
                            '_top'    => 'Full body of the window',
                        ],
                    ],
                    'icon' => [
                        'type'    => 'icons',
                        'label'   => 'icon',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'dynamic' => true,
                    ],
                    'icon_position' => [
                        'conditions'  => "[icon]!=''",
                        'type'    => 'list',
                        'label'   => 'icon_position',
                        'default' => 'first',
                        'options' => [
                            'first' => 'first',
                            'last'  => 'last',
                        ],
                    ],
                    'button_style' => [
                        'type'    => 'list',
                        'label'   => 'style',
                        'default' => 'primary',
                        'options' => [
                            'primary'   => 'Primary',
                            'secondary' => 'Secondary',
                            'success'   => 'Success',
                            'danger'    => 'Danger',
                            'warning'   => 'Warning',
                            'info'      => 'Info',
                            'light'     => 'Light',
                            'dark'      => 'Dark',
                            'link'      => 'Link',
                            'text'      => 'Text',
                            'custom'    => 'Custom',
                        ],
                    ],
                    'color_settings' => [
                        'conditions'  => "[button_style]=='custom'",
                        'type'    => 'radio',
                        "attributes" => [
                            'width'   => 'full',
                        ],
                        'default' => 'color',
                        'options' => [
                            'color' => 'color',
                            'hover' => 'color_hover',
                        ],
                    ],
                    'color' => [
                        'conditions' => "[button_style]=='custom' AND [color_settings]=='color'",
                        'type'   => 'color',
                        'label'  => 'color',
                    ],
                    'color_hover' => [
                        'conditions' => "[button_style]=='custom' AND [color_settings]=='hover'",
                        'type'   => 'color',
                        'label'  => 'color_hover',
                    ],
                    'bgcolor' => [
                        'conditions' => "[button_style]=='custom' AND [color_settings]=='color'",
                        'type'   => 'color',
                        'label'  => 'background_color',
                    ],
                    'bgcolor_hover' => [
                        'conditions' => "[button_style]=='custom' AND [color_settings]=='hover'",
                        'type'   => 'color',
                        'label'  => 'background_color_hover',
                    ],
                    'button_outline' => [
                        'type'           => 'radio',
                        "attributes" => [
                            "role" => "switch"
                        ],
                        'default'        => '0',
                        'label'          => 'button_outline',
                    ],
                    'button_size' => [
                        'type'    => 'list',
                        'label'   => 'button_size',
                        'default' => '',
                        'options' => [
                            ''       => 'Default',
                            'btn-lg' => 'Large',
                            'btn-sm' => 'Small',
                            'custom' => 'Custom',
                        ],
                    ],
                    'btn_padding' => [
                        'conditions' => "[button_size]=='custom'",
                        'type'   => 'spacing',
                        'label'  => 'padding',
                    ],
                    'btn_font_style' => [
                        'label'   => 'font_style',
                        'type'    => 'typography',
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
                    ],
                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('buttons',  [
            "group" => "general",
            "type" => "subform",
            "label" => "buttons",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
        ]);
        $this->add_field('button_group', [
            "group"          => "widget_styles",
            "type"           => "radio",
            "attributes" => [
                "role" => "switch"
            ],
            "default"        => "0",
            "label"          => "button_group",
        ]);

        $this->add_field('button_size', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "button_size",
            "default" => "",
            "options" => [
                ""       => "default",
                "btn-lg" => "Large",
                "btn-sm" => "Small",
                "custom" => "Custom",
            ],
        ]);

        $this->add_field('button_font_style', [
            "group"      => "widget_styles",
            "conditions" => "[button_size]=='custom'",
            "label"      => "font_style",
            "type"       => "typography",
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

        $this->add_field('btn_padding', [
            "group"      => "widget_styles",
            "conditions" => "[button_size]=='custom'",
            "type"       => "spacing",
            "label"      => "padding",
        ]);

        $this->add_field('btn_border_radius', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "",
            "options" => [
                ""             => "Rounded",
                "rounded-0"    => "Square",
                "rounded-pill" => "Circle",
            ],
        ]);

        $this->add_field('gutter', [
            "conditions" => "[button_group]==0",
            "group"      => "widget_styles",
            "type"       => "list",
            "label"      => "gutter",
            "default"    => "lg",
            "options"    => [
                "sm"  => "sm",
                "md"  => "md",
                "lg"  => "lg",
                "xl"  => "xl",
                "xxl" => "xxl",
            ],
        ]);
    }
}