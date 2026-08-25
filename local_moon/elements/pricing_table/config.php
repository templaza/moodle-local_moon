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
class moon_element_pricing_table extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'pricing_table',
            'title' => 'Pricing Table',
            'description' => 'Pricing Widget of Moodle',
            'icon' => 'as-icon as-icon-tablet',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('title_options', [
            'type'  => 'group',
            'label' => 'title',
        ]);

        $this->add_field('meta_options', [
            'type'  => 'group',
            'label' => 'meta_options',
        ]);

        $this->add_field('pricing_options', [
            'type'  => 'group',
            'label' => 'pricing_options',
        ]);

        $this->add_field('symbol_options', [
            'type'  => 'group',
            'label' => 'symbol_options',
        ]);

        $this->add_field('description_options', [
            'type'  => 'group',
            'label' => 'description_options',
        ]);

        $this->add_field('listing_options', [
            'type'  => 'group',
            'label' => 'listing_options',
        ]);

        $this->add_field('button_options', [
            'type'  => 'group',
            'label' => 'button_options',
        ]);

        $this->add_field('title', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'title',
        ]);

        $this->add_field('meta', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'meta',
        ]);
        $this->add_field('description', [
            "group"   => "general",
            "type"    => "editor",
            "label"   => "content",
            "dynamic" => true,
        ]);
        $this->add_field('price', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'price',
        ]);
        $this->add_field('price_symbol', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'price_symbol',
        ]);
        $this->add_field('label_text', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'highlight',
        ]);


        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'item_title' => [
                        'type'    => 'text',
                        'label'   => 'title',
                        'dynamic' => true,
                    ],
                    'item_title_color' => [
                        'type'    => 'color',
                        'label'   => 'title_color',
                        'dynamic' => true,
                    ],
                    'item_icon' => [
                        'type'    => 'icons',
                        'label'   => 'icon',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'dynamic' => true,
                    ],
                    'item_icon_color' => [
                        'type'    => 'color',
                        'label'   => 'icon_color',
                        'dynamic' => true,
                    ],
                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('pricing_items',  [
            "group" => "general",
            "type" => "subform",
            "label" => "Pricing Items",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
        ]);

        $this->add_field('button_url', [
            "group"      => "general",
            'type'    => 'text',
            'label'   => 'link_url',
            "attributes" => [
                'hint'    => 'https://moonframe.work',
                'dynamic' => true,
            ],
        ]);

        $this->add_field('button_text', [
            "group"      => "general",
            'type'       => 'text',
            'label'      => 'link_text',
            "attributes" => [
                'hint'       => 'View More',
                'dynamic' => true,
            ],
            'conditions' => "[button_url]!==''",
        ]);

        $this->add_field('button_target', [
            "group"      => "general",
            'type'       => 'list',
            'label'      => 'link_target',
            'default'    => '',
            'conditions' => "[link]!==''",
            'options'    => [
                ''       => 'Default',
                '_blank' => 'New Window',
                '_parent'=> 'Parent Frame',
                '_top'   => 'Full body of the window',
            ],
        ]);

        $this->add_field('meta_alignment', [
            "group"      => "meta_options",
            "type"       => "list",
            "label"      => "meta_alignment",
            "default"    => "",
            "options"    => [
                'top' => 'Above',
                '' => 'Below',
                'inline' => 'Inline',
            ],
        ]);


        $this->add_field('title_font_style', [
            'group'   => 'title_options',
            'type'    => 'typography',
            'label'   => 'font_style',
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

        $this->add_field('title_heading_margin', [
            'group' => 'title_options',
            'type'  => 'spacing',
            'name'  => 'title_heading_margin',
            'label' => 'margin',
        ]);
        $this->add_field('title_heading_padding', [
            'group' => 'title_options',
            'type'  => 'spacing',
            'name'  => 'title_heading_padding',
            'label' => 'padding',
        ]);
        $this->add_field('title_border', [
            "group"      => "title_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('title_radius', [
            'group' => 'title_options',
            'type'  => 'spacing',
            'name'  => 'title_radius',
            'label' => 'radius',
        ]);


        $this->add_field('pricing_font_style', [
            'group'   => 'pricing_options',
            'type'    => 'typography',
            'label'   => 'font_style',
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
        $this->add_field('price_color', [
            'group' => 'pricing_options',
            'type'  => 'color',
            'label' => 'color',
        ]);
        $this->add_field('price_icon', [
            'group' => 'pricing_options',
            'type'  => 'icons',
            "attributes" => [
                'source' => 'fontawesome',
            ],
            'label' => 'icon',
        ]);
        $this->add_field('price_icon_size', [
            'group'      => 'pricing_options',
            'type'       => 'range',
            'label'      => 'icon_size',
            "attributes" => [
                'min'        => 1,
                'max'        => 1200,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 30,
        ]);
        $this->add_field('price_icon_color', [
            'group' => 'pricing_options',
            'type'  => 'color',
            'label' => 'icon_color',
        ]);
        $this->add_field('price_margin', [
            'group' => 'pricing_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->add_field('symbol_font_style', [
            'group'   => 'symbol_options',
            'type'    => 'typography',
            'label'   => 'font_style',
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

        $this->add_field('symbol_pos', [
            "group"      => "symbol_options",
            "type"       => "list",
            "label"      => "position",
            "default"    => "",
            "options"    => [
                ''        => 'Default',
                'right'   => 'right',
            ],
        ]);
        $this->add_field('symbol_margin', [
            'group' => 'symbol_options',
            'type'  => 'spacing',
            'name'  => 'symbol_margin',
            'label' => 'margin',
        ]);

        $this->add_field('description_font_style', [
            'group'   => 'description_options',
            'type'    => 'typography',
            'label'   => 'font_style',
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

        $this->add_field('listing_border', [
            "group"      => "listing_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('listing_margin', [
            'group' => 'listing_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->add_field('listing_padding', [
            'group' => 'listing_options',
            'type'  => 'spacing',
            'label' => 'padding',
        ]);

        $this->add_field('button_font_style', [
            'group'   => 'button_options',
            'type'    => 'typography',
            'label'   => 'font_style',
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
        $this->add_field('button_margin', [
            'group' => 'button_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->add_field('button_padding', [
            'group' => 'button_options',
            'type'  => 'spacing',
            'label' => 'padding',
        ]);
        $this->add_field('button_border', [
            "group"      => "button_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('button_radius', [
            'group' => 'button_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);
        $this->add_field('button_bg_color', [
            'group' => 'button_options',
            'type'  => 'color',
            'label' => 'background_color',
        ]);
        $this->add_field('button_color_hover', [
            'group' => 'button_options',
            'type'  => 'color',
            'label' => 'color_hover',
        ]);
        $this->add_field('button_bg_color_hover', [
            'group' => 'button_options',
            'type'  => 'color',
            'label' => 'background_color_hover',
        ]);



    }
}