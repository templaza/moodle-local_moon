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
class MoonElementArt_Slider extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'art_slider',
            'title' => 'Art Slider',
            'description' => 'Art Slider Widget of Moodle',
            'icon' => 'as-icon as-icon-spotlights',
            'category' => 'media,utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('slideshow_options', [
            'type'  => 'group',
            'label' => 'slideshow',
        ]);

        $this->addField('overlay_options', [
            'type'  => 'group',
            'label' => 'overlay',
        ]);

        $this->addField('title_options', [
            'type'  => 'group',
            'label' => 'title',
        ]);

        $this->addField('meta_options', [
            'type'  => 'group',
            'label' => 'meta',
        ]);

        $this->addField('content_options', [
            'type'  => 'group',
            'label' => 'content',
        ]);

        $this->addField('readmore_options', [
            'type'  => 'group',
            'label' => 'readmore',
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
                    'meta' => [
                        'type'    => 'text',
                        'label'   => 'meta',
                        'dynamic' => true,
                    ],
                    'description' => [
                        'type'    => 'editor',
                        'label'   => 'description',
                        'dynamic' => true,
                    ],
                    'link' => [
                        'type'    => 'text',
                        'label'   => 'link_url',
                        "attributes" => [
                            'hint'    => 'https://moonframe.work',
                            'dynamic' => true,
                        ],
                    ],
                    'link_title' => [
                        'type'       => 'text',
                        'label'      => 'link_text',
                        "attributes" => [
                            'hint'       => 'View More',
                            'dynamic' => true,
                        ],
                        'conditions' => "[link]!==''",
                    ],
                    'link_target' => [
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
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('slides',  [
            "group" => "general",
            "type" => "subform",
            "label" => "slides",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);

        $this->addField('slider_height', [
            'group'      => 'slideshow_options',
            'type'       => 'range',
            'label'      => 'height',
            "attributes" => [
                'min'        => 1,
                'max'        => 1200,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'vh|px',
            ],
            'default'    => 80,
        ]);

        $this->addField('min_height', [
            'group'   => 'slideshow_options',
            'type'    => 'range',
            'label'      => 'min_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 1200,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px',
            ],
            'default' => 600,
        ]);

        $this->addField('effect_type', [
            'group'   => 'slideshow_options',
            'type'    => 'list',
            'name'    => 'effect_type',
            'label'   => 'effect_type',
            'default' => 'theater',
            'options' => [
                'theater'               => 'Theater',
                'drape'                 => 'Drape',
                'slide_vertical'        => 'Vertical Slider',
                'slide_horizontal'      => 'Horizontal Slider',
                'simple_slide_vertical' => 'Vertical Slider Simple',
                'slide_cross'           => 'Cross Slider',
                'fashion'               => 'Fashion',
                'shutters'              => 'Shutters Slider',
                'shutters_vertical'     => 'Vertical Shutters Slider',
                'expo'                  => 'Expo Slider',
                'gallery'               => 'Gallery',
                'tv_channel'            => 'TV Channel',
                'flip'                  => 'Flip Slider',
                'stone_throwing'        => 'Stone Throwing',
                'spring'                => 'Spring Slider',
                'page_flip'             => 'Page Flip',
            ],
        ]);

        $this->addField('main_color', [
            'group' => 'slideshow_options',
            'type'  => 'color',
            'label' => 'color',
        ]);

        $this->addField('autoplay', [
            'group'   => 'slideshow_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'autoplay',
        ]);

        $this->addField('interval', [
            'group'      => 'slideshow_options',
            'type'       => 'range',
            'label'      => 'interval',
            "attributes" => [
                'min'        => 1,
                'max'        => 10,
                'step'       => 1,
                'postfix'    => 'seconds',
            ],
            'default'    => 3,
            'conditions' => "[autoplay]==1",
        ]);

        $this->addField('controls', [
            'group'   => 'slideshow_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'controls',
        ]);

        $this->addField('indicators', [
            'group'   => 'slideshow_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'indicators',
        ]);

        $this->addField('image_effect', [
            'group'   => 'slideshow_options',
            'type'    => 'list',
            'name'    => 'image_effect',
            'label'   => 'image_effect',
            'default' => '',
            'options' => [
                ''           => 'none',
                'zoom_image' => 'zoom_in',
            ],
        ]);

        $this->addField('slide_border_radius', [
            'group'   => 'slideshow_options',
            'type'    => 'list',
            'name'    => 'slide_border_radius',
            'label'   => 'border_radius',
            'default' => '',
            'options' => [
                ''       => 'rounded',
                '0'      => 'square',
                'circle' => 'circle',
                'pill'   => 'pill',
            ],
        ]);

        $this->addField('slide_rounded_size', [
            'group'      => 'slideshow_options',
            'type'       => 'list',
            'name'       => 'slide_rounded_size',
            'label'      => 'rounded_size',
            'default'    => '3',
            'conditions' => "[slide_border_radius]==''",
            'options'    => [
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);

        $this->addField('box_shadow', [
            'group'   => 'slideshow_options',
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

        $this->addField('box_shadow_hover', [
            'group'   => 'slideshow_options',
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

        $this->addField('overlay_max_width', [
            'group'       => 'overlay_options',
            'type'        => 'list',
            'name'        => 'overlay_max_width',
            'label'       => 'max_width',
            'description' => 'max_width_desc',
            'default'     => '',
            'options'     => [
                ''        => 'inherit',
                'xxsmall' => 'xxsmall',
                'xsmall'  => 'xsmall',
                'small'   => 'small',
                'medium'  => 'medium',
                'large'   => 'large',
                'xlarge'  => 'xlarge',
                'xxlarge' => 'xxlarge',
            ],
        ]);

        $this->addField('overlay_position', [
            'group'   => 'overlay_options',
            'type'    => 'list',
            'name'    => 'overlay_position',
            'label'   => 'overlay_position',
            'default' => 'justify-content-center align-items-center',
            'options' => [
                'justify-content-start align-items-start'   => 'left_top',
                'justify-content-center align-items-start'  => 'center_top',
                'justify-content-end align-items-start'     => 'right_top',
                'justify-content-start align-items-center'  => 'left_center',
                'justify-content-center align-items-center' => 'center_center',
                'justify-content-end align-items-center'    => 'right_center',
                'justify-content-start align-items-end'     => 'left_bottom',
                'justify-content-center align-items-end'    => 'center_bottom',
                'justify-content-end align-items-end'       => 'right_bottom',
            ],
        ]);

        $this->addField('overlay_text_color', [
            'group'   => 'overlay_options',
            'type'    => 'list',
            'name'    => 'overlay_text_color',
            'label'   => 'overlay_text_color',
            'default' => '',
            'options' => [
                ''         => 'inherit',
                'as-light' => 'color_mode_light',
                'as-dark'  => 'color_mode_dark',
            ],
        ]);

        $this->addField('overlay_type', [
            'group'   => 'overlay_options',
            'type'    => 'radio',
            "attributes" => [
                'width'   => 'full',
            ],
            'name'    => 'overlay_type',
            'default' => 'color',
            'options' => [
                ''                 => 'none',
                'color'            => 'color',
                'background-color' => 'background_color',
            ],
        ]);

        $this->addField('overlay_color', [
            'group'      => 'overlay_options',
            'type'       => 'color',
            'name'       => 'overlay_color',
            'label'      => 'overlay_color',
            'conditions' => "[overlay_type]=='color'",
        ]);

        $this->addField('overlay_gradient', [
            'group'      => 'overlay_options',
            'type'       => 'gradient',
            'name'       => 'overlay_gradient',
            'label'      => 'overlay_gradient',
            'conditions' => "[overlay_type]=='background-color'",
        ]);

        $this->addField('overlay_padding', [
            'group'   => 'overlay_options',
            'type'    => 'list',
            'name'    => 'overlay_padding',
            'label'   => 'overlay_padding',
            'default' => '',
            'options' => [
                'none'   => 'none',
                ''       => 'default',
                'small'  => 'small',
                'large'  => 'large',
                'custom' => 'custom',
            ],
        ]);

        $this->addField('overlay_custom_padding', [
            'group'      => 'overlay_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[overlay_padding]=='custom'",
        ]);

        $this->addField('title_html_element', [
            'group'   => 'title_options',
            'type'    => 'list',
            'label'   => 'html_element',
            'default' => 'h3',
            'options' => [
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6',
                'div'=> 'div',
            ],
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

        $this->addField('meta_font_style', [
            'group'   => 'meta_options',
            'type'    => 'typography',
            'name'    => 'meta_font_style',
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

        $this->addField('meta_heading_margin', [
            'group' => 'meta_options',
            'type'  => 'spacing',
            'name'  => 'meta_heading_margin',
            'label' => 'margin',
        ]);

        $this->addField('meta_position', [
            'group'   => 'meta_options',
            'type'    => 'list',
            'name'    => 'meta_position',
            'label'   => 'meta_position',
            'default' => 'before',
            'options' => [
                'before' => 'before_title',
                'after'  => 'after_title',
            ],
        ]);

        $this->addField('content_font_style', [
            'group'   => 'content_options',
            'type'    => 'typography',
            'name'    => 'content_font_style',
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

        $this->addField('button_style', [
            'group'   => 'readmore_options',
            'type'    => 'list',
            'name'    => 'button_style',
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
            ],
        ]);

        $this->addField('button_outline', [
            'group'   => 'readmore_options',
            'type'    => 'radio',
            'name'    => 'button_outline',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'button_outline',
        ]);

        $this->addField('button_size', [
            'group'   => 'readmore_options',
            'type'    => 'list',
            'name'    => 'button_size',
            'label'   => 'style',
            'default' => '',
            'options' => [
                ''       => 'Default',
                'btn-lg' => 'Large',
                'btn-sm' => 'Small',
            ],
        ]);

        $this->addField('btn_border_radius', [
            'group'   => 'readmore_options',
            'type'    => 'list',
            'name'    => 'btn_border_radius',
            'label'   => 'border_radius',
            'default' => '',
            'options' => [
                ''             => 'Rounded',
                'rounded-0'    => 'Square',
                'rounded-pill' => 'Circle',
            ],
        ]);
    }
}