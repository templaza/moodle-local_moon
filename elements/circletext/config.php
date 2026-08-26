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
use local_moon\library\helper\font;
class moon_element_circletext extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'circletext',
            'title' => 'Circle Text',
            'description' => 'Circle Text',
            'icon' => 'fa-solid fa-circle-h',
            'category' => 'typography',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

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

        $this->add_field('title_bg_color', [
            "group"      => "general",
            "type"       => "color",
            "label"      => "background_color",
        ]);

        $this->add_field('title_border', [
            "group"      => "general",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('title_radius', [
            'group' => 'general',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);
        $this->add_field('title_padding', [
            'group' => 'general',
            'type'  => 'spacing',
            'label' => 'padding',
        ]);
        $this->add_field('title_margin', [
            'group' => 'general',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->add_field('title_width', [
            'group'      => 'general',
            'type'       => 'range',
            'label'      => 'width',
            "attributes" => [
                'min'        => 1,
                'max'        => 1000,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 150,
        ]);
        $this->add_field('title_icon', [
            "type"       => "icons",
            'group' => 'general',
            "label"      => "icon",
        ]);
        $this->add_field('title_icon_size', [
            'group'      => 'general',
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
        $this->add_field('title_icon_color', [
            "group"      => "general",
            "type"       => "color",
            "label"      => "color",
        ]);
        $this->add_field('hover_rotate', [
            'group'      => 'general',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'hover_rotate',
            'attributes' => ["role" => "switch"],
        ]);
        $this->add_field('auto_rotate', [
            'group'      => 'general',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'auto_rotate',
            'attributes' => ["role" => "switch"],
        ]);
        $this->add_field('title_pos', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "position",
            "default"    => "",
            "options"    => [
                'uk-position-relative'   => 'Relative',
                'uk-position-absolute'   => 'Absolute',
            ],
        ]);
        $this->add_field('box_position', [
            'group'   => 'general',
            'type'    => 'list',
            'label'   => 'position',
            'options'         => array(
                '' =>  'None',
                'uk-position-top-left' => 'top_left',
                'uk-position-top-center' => 'top_center',
                'uk-position-top-right' => 'top_right',
                'uk-position-center-left' => 'center_left',
                'uk-position-center' => 'center_center',
                'uk-position-center-right' => 'center_right',
                'uk-position-bottom-left' => 'bottom_left',
                'uk-position-bottom-center' => 'bottom_center',
                'uk-position-bottom-right' => 'bottom_right',
            ),
            "conditions" => "[title_pos]=='uk-position-absolute'",
        ]);
    }
}