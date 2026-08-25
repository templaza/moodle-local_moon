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
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementGallery extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'gallery',
            'title' => 'Gallery',
            'description' => 'Gallery Widget of Moodle',
            'icon' => 'as-icon as-icon-folder-picture',
            'category' => 'media,utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('title_options', [
            'type'  => 'group',
            'label' => 'title',
        ]);

        $this->addField('image_options', [
            'type'  => 'group',
            'label' => 'image_options',
        ]);

        $this->addField('overlay_options', [
            'type'  => 'group',
            'label' => 'overlay_options',
        ]);

        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'image' => [
                        'type'    => 'media',
                        'label'   => 'TPL_ASTROID_SELECT_IMAGE',
                        'dynamic' => true,
                    ],
                    'title' => [
                        'type'    => 'text',
                        'label'   => 'title',
                        'dynamic' => true,
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('galleries',  [
            "group" => "general",
            "type" => "subform",
            "label" => "Galleries",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);
        $this->addField('masonry', [
            'group'   => 'general',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'masonry',
        ]);
        $this->addField('gallery_column', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "column",
            "default"    => "col-lg-4",
            "options"    => [
                "col-lg-12" => "1 column",
                "col-lg-6" => "2 columns",
                "col-lg-4" => "3 columns",
                "col-lg-3" => "4 columns",
                "col-lg-2" => "6 columns",
            ],
        ]);
        $this->addField('column_padding', [
            'group'      => 'general',
            'type'       => 'spacing',
            'label'      => 'column_padding',
        ]);
        $this->addField('row_margin', [
            'group'      => 'general',
            'type'       => 'spacing',
            'label'      => 'row_margin',
        ]);
        $this->addField('thumbnail_hover', [
            'group'   => 'image_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'thumbnail_hover',
        ]);
        $this->addField('thumbnail_hover_transition', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "hover_transition",
            "default"    => "fade",
            "options"    => [
                'uk-transition-fade' => 'Fade',
                'uk-transition-scale-up' => 'scale_up',
                'uk-transition-scale-down' => 'scale_down',
                'uk-transition-slide-top' => 'slide_top',
                'uk-transition-slide-bottom' => 'slide_bottom',
                'uk-transition-slide-left' => 'slide_left',
                'uk-transition-slide-right' => 'slide_right',
                'uk-transition-slide-top-small' => 'slide_top_small',
                'uk-transition-slide-bottom-small' => 'slide_bottom_small',
                'uk-transition-slide-left-small' => 'slide_left_small',
                'uk-transition-slide-right-small' => 'slide_right_small',
                'uk-transition-slide-top-medium' => 'slide_top_medium',
                'uk-transition-slide-bottom-medium' => 'slide_bottom_medium',
                'uk-transition-slide-left-medium' => 'slide_left_medium',
                'uk-transition-slide-right-medium' => 'slide_right_medium',
            ],
            "conditions" => "[thumbnail_hover]==1",
        ]);
        $this->addField('image_lightbox', [
            'group'   => 'image_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'enable_lightbox',
        ]);
        $this->addField('image_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
        ]);
        $this->addField('overlay_padding', [
            'group'      => 'overlay_options',
            'type'       => 'spacing',
            'label'      => 'padding',
        ]);
        $this->addField('overlay_bg_color', [
            "group"      => "overlay_options",
            "type"       => "color",
            "label"      => "background_color",
        ]);

        $this->addField('title_font_style', [
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
                    'system_fonts' => Font::get_system_fonts(),
                    'text_transform_options' => Font::text_transform(),
                    'lang' => Font::font_properties(),
                ],
                'lang' => Font::font_properties(),
                'value' => Font::$get_default_font_value,
            ],
        ]);

        $this->addField('title_heading_margin', [
            'group' => 'title_options',
            'type'  => 'spacing',
            'name'  => 'title_heading_margin',
            'label' => 'margin',
        ]);

    }
}