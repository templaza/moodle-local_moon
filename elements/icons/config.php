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
class moon_element_icons extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'icons',
            'title' => 'Icons',
            'description' => 'Icon Widget of Moodle',
            'icon' => 'as-icon as-icon-3d-rotate',
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

        $this->add_field('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'icon' => [
                        'type'    => 'icons',
                        'label'   => 'icon',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'dynamic' => true,
                    ],
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
                        'type'   => 'color',
                        'label'  => 'color',
                    ],
                    'color_hover' => [
                        'type'   => 'color',
                        'label'  => 'color_hover',
                    ],
                    'bgcolor' => [
                        'type'   => 'color',
                        'label'  => 'background_color',
                    ],
                    'bgcolor_hover' => [
                        'type'   => 'color',
                        'label'  => 'background_color_hover',
                    ],
                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('icons',  [
            "group" => "general",
            "type" => "subform",
            "label" => "icons",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
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
        $this->add_field('icons_color', [
            'group' => 'icon_options',
            'type'  => 'color',
            'label' => 'color',
        ]);
        $this->add_field('icon_width', [
            'group'      => 'icon_options',
            'type'       => 'range',
            'label'      => 'width',
            "attributes" => [
                'min'        => 1,
                'max'        => 300,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 50,
        ]);
        $this->add_field('icon_height', [
            'group'      => 'icon_options',
            'type'       => 'range',
            'label'      => 'height',
            "attributes" => [
                'min'        => 1,
                'max'        => 300,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 50,
        ]);
        $this->add_field('icon_radius', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'label' => 'border_radius',
        ]);
        $this->add_field('icon_border', [
            "group"      => "icon_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('icon_border_hover', [
            "group"      => "icon_options",
            "type"       => "border",
            "label"      => "border_hover",
        ]);

        $this->add_field('icon_padding', [
            "group"      => "icon_options",
            "type"       => "spacing",
            "label"      => "padding",
        ]);
        $this->add_field('icon_margin', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
    }
}