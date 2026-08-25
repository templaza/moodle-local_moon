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
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Constants;
class MoonElementImage extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'image',
            'title' => 'Image',
            'description' => 'Image Widget of Moodle',
            'icon' => 'as-icon as-icon-picture',
            'category' => 'media',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('widget_styles',  [
            "type" => "group",
            "label" => "widget_styles",
        ]);
        $this->addField('mask_styles',  [
            "type" => "group",
            "label" => "mask_styles",
        ]);

        $this->addField('image_color_mode', [
            "group"   => "general",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "light",
            "options" => [
                "light" => "color_mode_light",
                "dark"  => "color_mode_dark",
            ],
        ]);

        $this->addField('image', [
            "group"      => "general",
            "type"       => "media",
            "label"      => "TPL_ASTROID_SELECT_IMAGE_LIGHT",
            "dynamic"    => true,
            "conditions" => "[image_color_mode]=='light'",
        ]);

        $this->addField('image_dark', [
            "group"      => "general",
            "type"       => "media",
            "label"      => "TPL_ASTROID_SELECT_IMAGE_DARK",
            "dynamic"    => true,
            "conditions" => "[image_color_mode]=='dark'",
        ]);

        $this->addField('figure_caption', [
            "group"   => "general",
            "type"    => "text",
            "label"   => "figure_caption",
            "dynamic" => true,
        ]);

        $this->addField('use_link', [
            "group"       => "general",
            "type"        => "radio",
            "label"       => "use_link",
            "description" => "use_link_desc",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => "0",
        ]);

        $this->addField('link', [
            "group"      => "general",
            "type"       => "text",
            "label"      => "link_url",
            "description"=> "link_url_desc",
            "hint"       => "https://astroidframe.work/",
            "dynamic"    => true,
            "conditions" => "[use_link]==1",
        ]);

        $this->addField('target', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "link_target",
            "default"    => "",
            "options"    => [
                ""        => "Default",
                "_blank"  => "New Window",
                "_parent" => "Parent Frame",
                "_top"    => "Full body of the window",
            ],
            "conditions" => "[use_link]==1",
        ]);

        $this->addField('display', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "display",
            "default" => "",
            "options" => [
                ""           => "Block",
                "d-inline-block" => "Inline Block",
                "d-inline"   => "Inline",
                "d-flex"    => "Flex",
                "d-inline-flex" => "Inline Flex",
            ],
        ]);
        $this->addField('image_width', [
            'group'   => 'widget_styles',
            'type'    => 'range',
            'label'      => 'image_width',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->addField('image_height', [
            'group'   => 'widget_styles',
            'type'    => 'range',
            'label'      => 'image_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->addField('image_border', [
            "group"      => "widget_styles",
            "type"       => "border",
            "label"      => "border",
        ]);

        $this->addField('img_border_radius', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "",
            "options" => [
                ""               => "none",
                "rounded"        => "rounded",
                "rounded-circle" => "circle",
                "rounded-pill"   => "pill",
                "custom"   => "custom",
            ],
        ]);
        $this->addField('image_radius', [
            'group' => 'widget_styles',
            'type'  => 'spacing',
            'label' => 'radius',
            "conditions" => "[img_border_radius]=='custom'",
        ]);

        $this->addField('image_rounded_size', [
            "group"      => "widget_styles",
            "type"       => "list",
            "label"      => "rounded_size",
            "default"    => "3",
            "options"    => [
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
            "conditions" => "[img_border_radius]=='rounded'",
        ]);

        $this->addField('box_shadow', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "box_shadow",
            "default" => "",
            "options" => [
                ""          => "default",
                "shadow-none" => "none",
                "shadow-sm"   => "small",
                "shadow"      => "regular",
                "shadow-lg"   => "large",
            ],
        ]);
        $this->addField('image_rotate', [
            'group'      => 'widget_styles',
            'type'       => 'range',
            'label'      => 'rotate',
            "attributes" => [
                'min'        => -360,
                'max'        => 360,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'deg',
            ],
            'default'    => 0,
        ]);

        $this->addField('hover_effect', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "hover_effect",
            "default" => "",
            "options" => [
                ""         => "default",
                "light-up" => "light_up",
                "flash"    => "flash",
                "unveil"   => "unveil",
            ],
        ]);

        $this->addField('hover_transition', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "hover_transition",
            "default" => "",
            "options" => Constants::$hover_transition,
        ]);
        $this->addField('img_mask', [
            "group"   => "mask_styles",
            "type"    => "list",
            "label"   => "mask",
            "default" => "",
            "options" => [
                ""               => "none",
                "style1"        => "style1",
                "custom"   => "custom",
            ],
        ]);
        $this->addField('mask_scale', [
            'group'   => 'mask_styles',
            'type'    => 'range',
            'label'      => 'mask_scale',
            "attributes" => [
                'min'        => 1,
                'max'        => 200,
                'step'       => 1,
                'responsive' => true,
                'postfix' => '%',
            ],
            'default' => 100,
        ]);
        $this->addField('mask_position', [
            "group"   => "mask_styles",
            "type"    => "list",
            "label"   => "position",
            "default" => "",
            "options" => [
                ""         => "default",
                "center center" => "center_center",
                "center left"    => "center_left",
                "center right"   => "center_right",
                "top center"   => "top_center",
                "top left"   => "top_left",
                "top right"   => "top_right",
                "bottom center"   => "bottom_center",
                "bottom left"   => "bottom_left",
                "bottom right"   => "bottom_right",
            ],
        ]);
        $this->addField('mask_repeat', [
            "group"   => "mask_styles",
            "type"    => "list",
            "label"   => "repeat",
            "default" => "",
            "options" => [
                "no-repeat"  => "no_repeat",
                "repeat" => "repeat_all",
                "repeat-x"    => "repeat_x",
                "repeat-Y"   => "repeat_y",
            ],
        ]);
    }
}