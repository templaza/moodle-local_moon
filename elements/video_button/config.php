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
class moon_element_video_button extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'video_button',
            'title' => 'Video Button',
            'description' => 'Video Button Widget of Moodle',
            'icon' => 'as-icon as-icon-play',
            'category' => 'media',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('widget_styles',  [
            "type" => "group",
            "label" => "widget_styles",
        ]);
        $this->add_field('url',  [
            "group" => "general",
            "type" => "text",
            "label" => "link_url",
            "attributes" => [
                'hint' => 'https://www.youtube.com/watch?v=xxxxx'
            ],
            "dynamic" => true,
        ]);
        $this->add_field('button_size', [
            "group"      => "widget_styles",
            "type"       => "range",
            "attributes" => [
                "min"     => 1,
                "max"     => 300,
                "step"    => 1,
                "postfix" => "px",
            ],
            "default" => 24,
            "label"   => "button_size",
        ]);

        $this->add_field('ripple_color', [
            "group" => "widget_styles",
            "type"  => "color",
            "label" => "ripple_color",
        ]);

        $this->add_field('width', [
            "group"      => "widget_styles",
            "type"       => "range",
            "attributes" => [
                "min"     => 10,
                "max"     => 500,
                "step"    => 1,
                "postfix" => "px",
                'responsive' => true,
            ],
            "default" => 150,
            "label"   => "width",
        ]);

        $this->add_field('height', [
            "group"      => "widget_styles",
            "type"       => "range",
            "attributes" => [
                "min"     => 10,
                "max"     => 500,
                "step"    => 1,
                "postfix" => "px",
                'responsive' => true,
            ],
            "default" => 150,
            "label"   => "height",
        ]);

        $this->add_field('color_hover_toggle', [
            "group"      => "widget_styles",
            "type"       => "radio",
            "attributes" => [
                "width" => "full",
            ],
            "default" => "color",
            "options" => [
                "color" => "color",
                "hover" => "color_hover",
            ],
        ]);

        $this->add_field('color', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[color_hover_toggle]=='color'",
        ]);

        $this->add_field('color_hover', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[color_hover_toggle]=='hover'",
        ]);

        $this->add_field('background_color', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[color_hover_toggle]=='color'",
        ]);

        $this->add_field('background_color_hover', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "background_color_hover",
            "conditions" => "[color_hover_toggle]=='hover'",
        ]);

        $this->add_field('use_border', [
            "group"      => "widget_styles",
            "type"       => "radio",
            "attributes" => [
                "role" => "switch"
            ],
            "default" => "0",
            "label"   => "use_border",
        ]);

        $this->add_field('border_width', [
            "group"      => "widget_styles",
            "type"       => "range",
            "attributes" => [
                "min"     => 1,
                "max"     => 50,
                "step"    => 1,
                "postfix" => "px",
            ],
            "default"    => 1,
            "label"      => "border_width",
            "conditions" => "[use_border]==1",
        ]);

        $this->add_field('border_color', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "border_color",
            "conditions" => "[use_border]==1",
        ]);
    }
}