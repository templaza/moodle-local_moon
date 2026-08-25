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
class moon_element_uk_slider extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'uk_slider',
            'title' => 'Uk Slider',
            'description' => 'Uk Slider Widget of Moodle',
            'icon' => 'as-icon as-icon-camera-flip',
            'category' => 'media,utility',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('slideshow_options', [
            'type'  => 'group',
            'label' => 'slideshow',
        ]);
        $this->add_field('navigation_options', [
            'type'  => 'group',
            'label' => 'Navigation',
        ]);
        $this->add_field('dot_options', [
            'type'  => 'group',
            'label' => 'dot_options',
        ]);

        $this->add_field('overlay_options', [
            'type'  => 'group',
            'label' => 'overlay',
        ]);

        $this->add_field('title_options', [
            'type'  => 'group',
            'label' => 'title',
        ]);
        $this->add_field('image_options', [
            'type'  => 'group',
            'label' => 'Image',
        ]);

        $this->add_field('meta_options', [
            'type'  => 'group',
            'label' => 'meta',
        ]);

        $this->add_field('content_options', [
            'type'  => 'group',
            'label' => 'content',
        ]);

        $this->add_field('readmore_options', [
            'type'  => 'group',
            'label' => 'readmore',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'image_type' => [
                        'type'       => 'list',
                        'label'      => 'image_type',
                        'default'    => '',
                        'options'    => [
                            ''       => 'Image',
                            'video' => 'Video',
                        ],
                    ],
                    'image' => [
                        'type'    => 'media',
                        'label'   => 'TPL_ASTROID_SELECT_IMAGE',
                        'dynamic' => true,
                        "conditions" => "[image_type]==''",
                    ],
                    'video' => [
                        'type'    => 'text',
                        'label'   => 'video_url',
                        "attributes" => [
                            'hint'    => 'https://www.youtube.com/watch?v=gEbbIlMXE1Y',
                            'dynamic' => true,
                        ],
                        "conditions" => "[image_type]=='video'",
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
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('slides',  [
            "group" => "general",
            "type" => "subform",
            "label" => "slides",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
        ]);


        $this->add_field('slider_style', [
            'group'      => 'slideshow_options',
            'type'    => 'list',
            'label'      => 'Style',
            'options'         => array(
                'style1' => 'Style1',
                'style2' => 'Style2',
            ),
            'default'    => 'style1',
        ]);

        $this->add_field('slider_height', [
            'group'      => 'slideshow_options',
            'type'    => 'list',
            'label'      => 'height',
            "description" => "uk_slider_height",
            'options'         => array(
                '' => 'Auto',
                'full' => 'Viewport',
                'percent' => 'Viewport (Minus 20%)',
                'section' => 'Viewport (Minus the following section)',
            ),
            'default'    => '',
        ]);

        $this->add_field('min_height', [
            'group'   => 'slideshow_options',
            'type'    => 'range',
            'label'      => 'min_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 1200,
                'step'       => 1,
                'responsive' => false,
                'postfix' => 'px',
            ],
            'default' => 600,
        ]);

        $this->add_field('max_height', [
            'group'   => 'slideshow_options',
            'type'    => 'range',
            'label'      => 'max_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => false,
                'postfix' => 'px',
            ],
            'default' => 800,
        ]);
        $this->add_field('slideshow_padding', [
            'group'      => 'slideshow_options',
            'type'       => 'spacing',
            'label'      => 'padding',
        ]);
        $this->add_field('slideshow_radius', [
            'group' => 'slideshow_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);

        $this->add_field('slideshow_transition', [
            'group'   => 'slideshow_options',
            'type'    => 'list',
            'name'    => 'effect_type',
            'label'   => 'effect_type',
            'default' => 'fade',
            'options' => [
                'slide'      => 'Slide',
                'fade'       => 'Fade',
                'scale'      => 'Scale',
                'pull'       => 'Pull',
                'push'       => 'Push',
            ],
        ]);

        $this->add_field('autoplay', [
            'group'   => 'slideshow_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'autoplay',
        ]);
        $this->add_field('autoplay_interval',  [
            "group" => "slideshow_options",
            "type" => "text",
            "label" => "Interval",
            "description" => "interval_desc",
            "conditions" => "[autoplay]==1",
        ]);
        $this->add_field('kenburns_transition', [
            'group'   => 'slideshow_options',
            'type'    => 'list',
            'label'   => 'kenburns_label',
            'options'         => array(
                '' =>  'None',
                'top-left' => 'top_left',
                'top-center' => 'top_center',
                'top-right' => 'top_right',
                'center-left' => 'center_left',
                'center-center' => 'center_center',
                'center-right' => 'center_right',
                'bottom-left' => 'bottom_left',
                'bottom-center' => 'bottom_center',
                'bottom-right' => 'bottom_right',
            ),
        ]);
        $this->add_field('kenburns_duration', [
            'group'      => 'slideshow_options',
            'type'       => 'range',
            'label'      => 'kenburns_duration',
            'description'      => 'kenburns_duration_desc',
            "attributes" => [
                'min'        => 1,
                'max'        => 30,
                'step'       => 1,
                'responsive' => false,
                'postfix'    => '',
            ],
            'default'    => 9,
            "conditions" => "[kenburns_transition] !==''",
        ]);

        $this->add_field('navigation', [
            'group'   => 'navigation_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Nav Enable',
        ]);
        $this->add_field('slidenav_next_text', [
            "group"       => "navigation_options",
            "type"        => "text",
            "label"       => "next_text",
            "conditions" => "[navigation]==1",
        ]);
        $this->add_field('slidenav_preview_text', [
            "group"       => "navigation_options",
            "type"        => "text",
            "label"       => "preview_text",
            "conditions" => "[navigation]==1",
        ]);
        $this->add_field('navigation_color', [
            "group"      => "navigation_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[navigation]==1",
        ]);
        $this->add_field('navigation_bg_color', [
            "group"      => "navigation_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[navigation]==1",
        ]);
        $this->add_field('navigation_color_hover', [
            "group"      => "navigation_options",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[navigation]==1",
        ]);
        $this->add_field('navigation_bg_color_hover', [
            "group"      => "navigation_options",
            "type"       => "color",
            "label"      => "background_hover_color",
            "conditions" => "[navigation]==1",
        ]);
        $this->add_field('navigation_padding', [
            'group'      => 'navigation_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[navigation]==1",
        ]);
        $this->add_field('dot_style', [
            'group'   => 'dot_options',
            'type'    => 'list',
            'label'   => 'dot_option',
            'options'         => array(
                '' => 'None',
                'dotnav' => 'Dotnav',
                'thumbnav' => 'Thumbnav',
                'title' => 'Title'
            ),
        ]);
        $this->add_field('dot_position', [
            'group'   => 'dot_options',
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
        ]);
        $this->add_field('dot_margin', [
            'group' => 'dot_options',
            'type'  => 'spacing',
            'name'  => 'dot_margin',
            'label' => 'margin',
        ]);
        $this->add_field('dot_border_color', [
            'group'      => 'dot_options',
            'type'       => 'color',
            'label'      => 'border_color',
        ]);
        $this->add_field('dot_color', [
            'group'      => 'dot_options',
            'type'       => 'color',
            'label'      => 'color',
        ]);
        $this->add_field('dot_hover_color', [
            'group'      => 'dot_options',
            'type'       => 'color',
            'label'      => 'color_hover',
        ]);
        $this->add_field('dot_below', [
            'group'   => 'dot_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'dot_below',
            'conditions' => "[dot_positions]=='dotnav' OR [dot_positions]=='thumbnav'",
        ]);
        $this->add_field('dot_vertical', [
            'group'   => 'dot_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'dot_vertical',
            'conditions' => "[dot_positions]=='dotnav' OR [dot_positions]=='thumbnav' AND [dot_below]=='0'",
        ]);
        $this->add_field('overlay_max_width', [
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

        $this->add_field('overlay_padding', [
            'group' => 'overlay_options',
            'type'  => 'spacing',
            'name'  => 'overlay_padding',
            'label' => 'padding',
        ]);
        $this->add_field('overlay_align', [
            'group'   => 'overlay_options',
            'type'    => 'list',
            'label'   => 'text_alignment',
            'options'         => array(
                '' =>  'Left',
                'uk-text-center' => 'Center',
                'uk-text-right' => 'Right',
                'uk-text-justify' => 'Justifies',
            ),
        ]);
        $this->add_field('overlay_positions', [
            'group'   => 'overlay_options',
            'type'    => 'list',
            'label'   => 'overlay_position',
            'options'         => array(
                '' =>  'None',
                'top-left' => 'top_left',
                'top-center' => 'top_center',
                'top-right' => 'top_right',
                'center-left' => 'center_left',
                'center-center' => 'center_center',
                'center-right' => 'center_right',
                'bottom-left' => 'bottom_left',
                'bottom-center' => 'bottom_center',
                'bottom-right' => 'bottom_right',
            ),
        ]);
        $this->add_field('overlay_bg_color', [
            'group'      => 'overlay_options',
            'type'       => 'color',
            'label'      => 'overlay_bg_color',
        ]);

        $this->add_field('title_html_element', [
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

        $this->add_field('title_heading_margin', [
            'group' => 'title_options',
            'type'  => 'spacing',
            'name'  => 'title_heading_margin',
            'label' => 'margin',
        ]);

        $this->add_field('meta_font_style', [
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
                    'system_fonts' => font::get_system_fonts(),
                    'text_transform_options' => font::text_transform(),
                    'lang' => font::font_properties(),
                ],
                'lang' => font::font_properties(),
                'value' => font::$get_default_font_value,
            ],
        ]);
        $this->add_field('meta_bg_color', [
            "group"      => "meta_options",
            "type"       => "color",
            "label"      => "background_color",
        ]);

        $this->add_field('meta_heading_margin', [
            'group' => 'meta_options',
            'type'  => 'spacing',
            'name'  => 'meta_heading_margin',
            'label' => 'margin',
        ]);
        $this->add_field('meta_heading_padding', [
            'group'      => 'meta_options',
            'type'       => 'spacing',
            'label'      => 'padding',
        ]);

        $this->add_field('meta_position', [
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
        $this->add_field('meta_radius', [
            'group' => 'meta_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);

        $this->add_field('content_font_style', [
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
                    'system_fonts' => font::get_system_fonts(),
                    'text_transform_options' => font::text_transform(),
                    'lang' => font::font_properties(),
                ],
                'lang' => font::font_properties(),
                'value' => font::$get_default_font_value,
            ],
        ]);
        $this->add_field('content_padding', [
            'group'      => 'content_options',
            'type'       => 'spacing',
            'label'      => 'padding',
        ]);

        $this->add_field('button_font_style', [
            'group'   => 'readmore_options',
            'type'    => 'typography',
            'name'    => 'button_font_style',
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

        $this->add_field('button_style', [
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
                'custom'      => 'custom',
            ],
        ]);
        $this->add_field('button_margin', [
            'group' => 'readmore_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->add_field('button_padding', [
            'group' => 'readmore_options',
            'type'  => 'spacing',
            'label' => 'padding',
            'conditions' => "[button_style]=='custom'",
        ]);
        $this->add_field('button_border', [
            "group"      => "readmore_options",
            "type"       => "border",
            "label"      => "border",
            'conditions' => "[button_style]=='custom'",
        ]);
        $this->add_field('button_bg_color', [
            'group' => 'readmore_options',
            'type'  => 'color',
            'label' => 'background_color',
            'conditions' => "[button_style]=='custom'",
        ]);
        $this->add_field('button_color_hover', [
            'group' => 'readmore_options',
            'type'  => 'color',
            'label' => 'color_hover',
            'conditions' => "[button_style]=='custom'",
        ]);
        $this->add_field('button_bg_color_hover', [
            'group' => 'readmore_options',
            'type'  => 'color',
            'label' => 'background_color_hover',
            'conditions' => "[button_style]=='custom'",
        ]);

        $this->add_field('button_outline', [
            'group'   => 'readmore_options',
            'type'    => 'radio',
            'name'    => 'button_outline',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'button_outline',
        ]);

        $this->add_field('button_size', [
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

        $this->add_field('btn_border_radius', [
            'group'   => 'readmore_options',
            'type'    => 'list',
            'name'    => 'btn_border_radius',
            'label'   => 'border_radius',
            'default' => '',
            'options' => [
                ''             => 'Rounded',
                'rounded-0'    => 'Square',
                'rounded-pill' => 'Circle',
                'custom' => 'custom',
            ],
        ]);
        $this->add_field('button_radius', [
            'group'   => 'readmore_options',
            'type'  => 'spacing',
            'label' => 'radius',
            'conditions' => "[btn_border_radius]=='custom'",
        ]);
        $this->add_field('image_width', [
            'group'   => 'image_options',
            'type'    => 'range',
            'label'      => 'image_width',
            "attributes" => [
                'min'        => 1,
                'max'        => 1200,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px',
            ],
            'default' => 550,
        ]);
        $this->add_field('image_height', [
            'group'   => 'image_options',
            'type'    => 'range',
            'label'      => 'image_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 1200,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px',
            ],
            'default' => 700,
        ]);
        $this->add_field('image_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
        ]);
        $this->add_field('image_border', [
            "group"      => "image_options",
            "type"       => "border",
            "label"      => "border",
        ]);

    }
}