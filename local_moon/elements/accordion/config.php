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
use local_moon\library\helper\font;
class moon_element_accordion extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'accordion',
            'title' => 'Accordion',
            'description' => 'Accordion Widget of Moodle',
            'icon' => 'as-icon as-icon-menu3',
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

        $this->add_field('title_options', [
            'type'  => 'group',
            'label' => 'title_options',
        ]);

        $this->add_field('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);

        $this->add_field('content_options', [
            'type'  => 'group',
            'label' => 'content_options',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'title' => [
                        'type'        => 'text',
                        'label'       => 'title',
                    ],
                    'content' => [
                        'type'        => 'editor',
                        'label'       => 'content',
                    ],
                    'title_color' => [
                        'type' => 'color',
                        'label' => 'color',
                    ],
                    'title_bg_color' => [
                        'type' => 'color',
                        'label' => 'background_color',
                    ],
                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('accordions',  [
            "group" => "general",
            "type" => "subform",
            "label" => "accordion_items",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
        ]);

        $this->add_field('style', [
            'group'   => 'widget_styles',
            'type'    => 'list',
            'label'   => 'style',
            'default' => '',
            'options' => [
                ''               => 'Default',
                'accordion-flush' => 'Flush',
            ],
        ]);

        $this->add_field('collapse', [
            'group'   => 'widget_styles',
            'type'    => 'list',
            'label'   => 'collapse',
            'default' => '',
            'options' => [
                ''          => 'open_first_item',
                'close-all' => 'close_all',
            ],
        ]);

        $this->add_field('always_open', [
            'group'      => 'widget_styles',
            'type'       => 'radio',
            'label'      => 'always_open',
            'default'    => 0,
            'attributes' => ['role' => 'switch'],
        ]);
        $this->add_field('item_radius', [
            'group' => 'widget_styles',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);
        $this->add_field('item_margin', [
            'group' => 'widget_styles',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->add_field('color_settings', [
            'group'   => 'widget_styles',
            'type'    => 'radio',
            'attributes' => ['width'   => 'full',],
            'label'   => 'color_settings',
            'default' => 'color',
            'options' => [
                'color'  => 'color',
                'hover'  => 'color_hover',
                'active' => 'color_active',
            ],
        ]);

        $this->add_field('color', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'color',
            'conditions' => "[color_settings]=='color'",
        ]);

        $this->add_field('color_hover', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'color',
            'conditions' => "[color_settings]=='hover'",
        ]);

        $this->add_field('color_active', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'color',
            'conditions' => "[color_settings]=='active'",
        ]);

        $this->add_field('bgcolor', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'background_color',
            'conditions' => "[color_settings]=='color'",
        ]);

        $this->add_field('bgcolor_hover', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'background_color',
            'conditions' => "[color_settings]=='hover'",
        ]);

        $this->add_field('bgcolor_active', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'background_color',
            'conditions' => "[color_settings]=='active'",
        ]);
        $this->add_field('box_shadow', [
            'group'   => 'widget_styles',
            'type'    => 'list',
            'name'    => 'box_shadow',
            'label'   => 'box_shadow',
            'description' => 'box_shadow_desc',
            'default' => '',
            'options' => [
                ''            => 'default',
                'shadow-none' => 'none',
                'shadow-sm'   => 'small',
                'shadow'      => 'regular',
                'shadow-lg'   => 'large',
            ],
        ]);

        $this->add_field('box_shadow_hover', [
            'group'   => 'widget_styles',
            'type'    => 'list',
            'name'    => 'box_shadow_hover',
            'label'   => 'box_shadow_hover',
            'description' => 'box_shadow_hover_desc',
            'default' => '',
            'options' => [
                ''                   => 'default',
                'shadow-hover-none'  => 'none',
                'shadow-hover-sm'    => 'small',
                'shadow-hover'       => 'regular',
                'shadow-hover-lg'    => 'large',
            ],
        ]);

        $this->add_field('title_font_style', [
            'group'   => 'title_options',
            'type'    => 'typography',
            'label'   => 'font_style',
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
        $this->add_field('title_padding', [
            "group"      => "title_options",
            "type"       => "spacing",
            "label"      => "padding",
        ]);
        $this->add_field('title_border', [
            "group"      => "title_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('title_radius', [
            'group' => 'title_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);
        $this->add_field('icon_type', [
            'group'   => 'icon_options',
            'type'    => 'list',
            'label'   => 'icon_type',
            'default' => '',
            'options' => [
                ''                   => 'default',
                'fontawesome'  => 'fontawesome',
            ],
        ]);
        $this->add_field('fa_icon', [
            'group'      => 'icon_options',
            'type'       => 'icons',
            'label'      => 'fa_icon',
            "conditions" => "[icon_type]=='fontawesome'",
        ]);
        $this->add_field('icon_color', [
            'group'      => 'icon_options',
            'type'       => 'color',
            'label'      => 'icon_color',
        ]);
        $this->add_field('content_font_style', [
            'group'   => 'content_options',
            'type'    => 'typography',
            'label'   => 'font_style',
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
        $this->add_field('content_padding', [
            "group"      => "content_options",
            "type"       => "spacing",
            "label"      => "padding",
        ]);
        $this->add_field('bgcolor_content', [
            'group'      => 'content_options',
            'type'       => 'color',
            'label'      => 'background_color',
        ]);
    }
}