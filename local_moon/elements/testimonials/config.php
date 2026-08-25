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
class MoonElementTestimonials extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'testimonials',
            'title' => 'Testimonials',
            'description' => 'Testimonials Widget of Moodle',
            'icon' => 'as-icon as-icon-quote-close',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('grid_options', [
            'type'  => 'group',
            'label' => 'grid_options',
        ]);

        $this->addField('card_options', [
            'type'  => 'group',
            'label' => 'card_options',
        ]);

        $this->addField('slider_options', [
            'type'  => 'group',
            'label' => 'slider_options',
        ]);

        $this->addField('avatar_options', [
            'type'  => 'group',
            'label' => 'avatar_options',
        ]);

        $this->addField('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);

        $this->addField('name_options', [
            'type'  => 'group',
            'label' => 'name_options',
        ]);

        $this->addField('designation_options', [
            'type'  => 'group',
            'label' => 'designation_options',
        ]);

        $this->addField('content_options', [
            'type'  => 'group',
            'label' => 'content_options',
        ]);

        $this->addField('rating_options', [
            'type'  => 'group',
            'label' => 'rating_options',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'title' => [
                        'type'    => 'text',
                        'label'   => 'name',
                        'dynamic' => true,
                    ],

                    'designation' => [
                        'type'    => 'text',
                        'label'   => 'designation',
                        'dynamic' => true,
                    ],

                    'link' => [
                        'type'    => 'text',
                        'label'   => 'link_url',
                        'hint'    => 'https://astroidframe.work',
                        'dynamic' => true,
                    ],

                    'link_title' => [
                        'type'       => 'text',
                        'label'      => 'link_text',
                        'hint'       => 'astroidframe.work',
                        'dynamic'    => true,
                        'conditions' => "[link]!=''",
                    ],

                    'avatar' => [
                        'type'    => 'media',
                        'label'   => 'avatar',
                        'dynamic' => true,
                    ],

                    'message' => [
                        'type'    => 'editor',
                        'label'    => 'message',
                        'dynamic' => true,
                    ],

                    'rating' => [
                        'type'       => 'range',
                        'label'       => 'rating',
                        'attributes' => [
                            'min'     => 0,
                            'max'     => 5,
                            'step'    => 0.5,
                            'postfix' => 'stars',
                        ],
                        'default' => 5,
                        'dynamic' => true,
                    ],
                    'item_bg_color' => [
                        'type'       => 'color',
                        'label'      => 'background_color',
                    ],

                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('testimonials',  [
            "group" => "general",
            "type" => "subform",
            "label" => "testimonials",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);
        $this->addField('overlay_text_color', [
            'group'   => 'general',
            'type'    => 'list',
            'label'   => 'text_color',
            'default' => '',
            'options' => [
                ''         => 'inherit',
                'as-light' => 'color_mode_light',
                'as-dark'  => 'color_mode_dark',
            ],
        ]);

        $this->addField('column_responsive', [
            'group'   => 'grid_options',
            'type'    => 'radio',
            "attributes" => [
                'width'   => 'full',
            ],
            'default' => 'lg',
            'options' => [
                'xxl' => 'xxl_icon',
                'xl'  => 'xl_icon',
                'lg'  => 'lg_icon',
                'md'  => 'md_icon',
                'sm'  => 'sm_icon',
                'xs'  => 'xs_icon',
            ],
        ]);

        $this->addField('xxl_column', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'xxl_column',
            'default'    => '',
            'conditions' => "[column_responsive]=='xxl'",
            'options'    => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('xl_column', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'xl_column',
            'default'    => '',
            'conditions' => "[column_responsive]=='xl'",
            'options'    => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('lg_column', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'lg_column',
            'default'    => '1',
            'conditions' => "[column_responsive]=='lg'",
            'options'    => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('md_column', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'md_column',
            'default'    => '1',
            'conditions' => "[column_responsive]=='md'",
            'options'    => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('sm_column', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'sm_column',
            'default'    => '1',
            'conditions' => "[column_responsive]=='sm'",
            'options'    => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('xs_column', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'xs_column',
            'default'    => '1',
            'conditions' => "[column_responsive]=='xs'",
            'options'    => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('row_gutter_xxl', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'row_gutter_xxl',
            'default'    => '',
            'conditions' => "[column_responsive]=='xxl'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);

        $this->addField('row_gutter_xl', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'row_gutter_xl',
            'default'    => '',
            'conditions' => "[column_responsive]=='xl'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);

        $this->addField('row_gutter_lg', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'row_gutter_lg',
            'default'    => '4',
            'conditions' => "[column_responsive]=='lg'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);

        $this->addField('row_gutter_md', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'row_gutter_md',
            'default'    => '3',
            'conditions' => "[column_responsive]=='md'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);

        $this->addField('row_gutter_sm', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'row_gutter_sm',
            'default'    => '3',
            'conditions' => "[column_responsive]=='sm'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);

        $this->addField('row_gutter', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'row_gutter_xs',
            'default'    => '3',
            'conditions' => "[column_responsive]=='xs'",
            'options'    => [
                '0' => 'Collapse',
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);

        $this->addField('column_gutter_xxl', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'column_gutter_xxl',
            'default'    => '',
            'conditions' => "[column_responsive]=='xxl'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '10' => 'X-Small',
                '20' => 'Small',
                '30' => 'Medium',
                '40' => 'Large',
                '50' => 'X-Large',
            ],
        ]);

        $this->addField('column_gutter_xl', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'column_gutter_xl',
            'default'    => '',
            'conditions' => "[column_responsive]=='xl'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '10' => 'X-Small',
                '20' => 'Small',
                '30' => 'Medium',
                '40' => 'Large',
                '50' => 'X-Large',
            ],
        ]);

        $this->addField('column_gutter_lg', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'column_gutter_lg',
            'default'    => '4',
            'conditions' => "[column_responsive]=='lg'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '10' => 'X-Small',
                '20' => 'Small',
                '30' => 'Medium',
                '40' => 'Large',
                '50' => 'X-Large',
            ],
        ]);

        $this->addField('column_gutter_md', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'column_gutter_md',
            'default'    => '3',
            'conditions' => "[column_responsive]=='md'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '10' => 'X-Small',
                '20' => 'Small',
                '30' => 'Medium',
                '40' => 'Large',
                '50' => 'X-Large',
            ],
        ]);

        $this->addField('column_gutter_sm', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'column_gutter_sm',
            'default'    => '3',
            'conditions' => "[column_responsive]=='sm'",
            'options'    => [
                ''  => 'inherit',
                '0' => 'Collapse',
                '10' => 'X-Small',
                '20' => 'Small',
                '30' => 'Medium',
                '40' => 'Large',
                '50' => 'X-Large',
            ],
        ]);

        $this->addField('column_gutter', [
            'group'      => 'grid_options',
            'type'       => 'list',
            'label'      => 'column_gutter',
            'default'    => '3',
            'conditions' => "[column_responsive]=='xs'",
            'options'    => [
                '0' => 'Collapse',
                '10' => 'X-Small',
                '20' => 'Small',
                '30' => 'Medium',
                '40' => 'Large',
                '50' => 'X-Large',
            ],
        ]);

        $this->addField('use_masonry', [
            'group'      => 'grid_options',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'use_masonry',
            'attributes' => ["role" => "switch"],
        ]);

        $this->addField('card_style', [
            'group'   => 'card_options',
            'type'    => 'list',
            'label'   => 'card_style',
            'default' => '',
            'options' => [
                ''          => 'Default',
                'primary'   => 'Primary',
                'secondary' => 'Secondary',
                'success'   => 'Success',
                'danger'    => 'Danger',
                'warning'   => 'Warning',
                'info'      => 'Info',
                'light'     => 'Light',
                'dark'      => 'Dark',
                'none'      => 'None',
                "custom"    => "custom",
            ],
        ]);
        $this->addField('text_color', [
            "group"      => "card_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->addField('bg_color', [
            "group"      => "card_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->addField('card_border', [
            "group"      => "card_options",
            "type"       => "border",
            "label"      => "border",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->addField('card_size', [
            'group'   => 'card_options',
            'type'    => 'list',
            'label'   => 'card_size',
            'default' => '',
            'options' => [
                'none'   => 'none',
                ''       => 'default',
                'small'  => 'sm',
                'large'  => 'lg',
                'custom' => 'custom',
            ],
        ]);

        $this->addField('card_padding', [
            'group'      => 'card_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[card_size]=='custom'",
        ]);

        $this->addField('card_margin', [
            'group'      => 'card_options',
            'type'       => 'spacing',
            'label'      => 'margin',
            'conditions' => "[card_size]=='custom'",
        ]);

        $this->addField('card_border_radius', [
            'group'   => 'card_options',
            'type'    => 'list',
            'label'   => 'border_radius',
            'default' => '',
            'options' => [
                ''       => 'rounded',
                '0'      => 'square',
                'circle' => 'circle',
                'pill'   => 'pill',
                'custom'   => 'custom',
            ],
        ]);
        $this->addField('card_radius_custom', [
            'group' => 'card_options',
            'type'  => 'spacing',
            'label' => 'radius',
            'conditions' => "[card_border_radius]=='custom'",
        ]);

        $this->addField('card_rounded_size', [
            'group'      => 'card_options',
            'type'       => 'list',
            'label'      => 'rounded_size',
            'default'    => '3',
            'conditions' => "[card_border_radius]==''",
            'options'    => [
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);



        $this->addField('enable_grid_match', [
            'group'      => 'card_options',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'enable_grid_match',
            'attributes' => ["role" => "switch"],
        ]);

        $this->addField('card_hover_transition', [
            'group'   => 'card_options',
            'type'    => 'list',
            'label'   => 'hover_transition',
            'default' => '',
            'options' => Constants::$hover_transition,
        ]);

        $this->addField('card_box_shadow', [
            'group'   => 'card_options',
            'type'    => 'list',
            'label'   => 'box_shadow',
            'default' => '',
            'options' => [
                ''            => 'default',
                'shadow-none' => 'none',
                'shadow-sm'   => 'sm',
                'shadow'      => 'md',
                'shadow-lg'   => 'lg',
            ],
        ]);

        $this->addField('card_box_shadow_hover', [
            'group'   => 'card_options',
            'type'    => 'list',
            'label'   => 'box_shadow_hover',
            'default' => '',
            'options' => [
                ''            => 'default',
                'shadow-none' => 'none',
                'shadow-sm'   => 'sm',
                'shadow'      => 'md',
                'shadow-lg'   => 'lg',
            ],
        ]);
        $this->addField('card_vertical_align', [
            'group'   => 'card_options',
            'type'    => 'list',
            'label'   => 'vertical_alignment',
            'default' => '',
            'options' => [
                ''          => 'Default',
                'align-items-center'   => 'center',
                'align-items-end'   => 'bottom',

            ],
        ]);

        $this->addField('enable_slider', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'enable_slider',
            'attributes' => ["role" => "switch"],
        ]);
        $this->addField('slider_padding', [
            'group' => 'slider_options',
            'type'  => 'spacing',
            'label' => 'slider_padding',
        ]);

        $this->addField('slider_autoplay', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'autoplay',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->addField('interval', [
            'group'      => 'slider_options',
            'type'       => 'range',
            'conditions' => "[enable_slider]==1 AND [slider_autoplay]==1",
            'attributes' => [
                'min'        => 0,
                'max'        => 10,
                'step'       => 1,
                'postfix'    => 'seconds',
            ],
            'default'    => 3,
            'label'      => 'interval',
        ]);

        $this->addField('speed', [
            'group'      => 'slider_options',
            'type'       => 'range',
            'attributes' => [
                'min'        => 0,
                'max'        => 10,
                'step'       => 0.5,
                'postfix'    => 'seconds',
            ],
            'default'    => 1,
            'label'      => 'speed',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->addField('freemode', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'freemode',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->addField('loop', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'loop',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->addField('slider_nav', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '1',
            'label'      => 'navigation',
            'conditions' => "[enable_slider]==1",
        ]);
        $this->addField('slider_nav_position', [
            'group'   => 'slider_options',
            'type'    => 'list',
            'label'   => 'position',
            'options'         => array(
                '' =>  'Default',
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
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->addField('slider_nav_height', [
            'group'      => 'slider_options',
            'type'       => 'range',
            'label'      => 'nav_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 300,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 50,
        ]);
        $this->addField('slider_nav_width', [
            'group'      => 'slider_options',
            'type'       => 'range',
            'label'      => 'nav_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 300,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 50,
        ]);
        $this->addField('slider_nav_border', [
            "group"      => "slider_options",
            "type"       => "border",
            "label"      => "border",
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);

        $this->addField('slider_nav_radius', [
            'group' => 'slider_options',
            'type'  => 'spacing',
            'label' => 'radius',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->addField('next_margin', [
            'group' => 'slider_options',
            'type'  => 'spacing',
            'label' => 'next_margin',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->addField('preview_margin', [
            'group' => 'slider_options',
            'type'  => 'spacing',
            'label' => 'preview_margin',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);

        $this->addField('nav_color', [
            'group' => 'slider_options',
            'type'  => 'color',
            'label' => 'color_hover',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->addField('nav_bg_color', [
            'group' => 'slider_options',
            'type'  => 'color',
            'label' => 'background_color',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->addField('nav_color_hover', [
            'group' => 'slider_options',
            'type'  => 'color',
            'label' => 'color_hover',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->addField('nav_bg_color_hover', [
            'group' => 'slider_options',
            'type'  => 'color',
            'label' => 'background_color_hover',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->addField('slider_nav_border_hover', [
            "group"      => "slider_options",
            "type"       => "border",
            "label"      => "border_hover",
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);

        $this->addField('slider_dotnav', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'dot_navigation',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->addField('slider_scrollbar', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'scrollbar',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->addField('slidesPerGroup_responsive', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                'width'   => 'full',
            ],
            'default' => 'lg',
            'options' => [
                'xxl' => 'xxl_icon',
                'xl'  => 'xl_icon',
                'lg'  => 'lg_icon',
                'md'  => 'md_icon',
                'sm'  => 'sm_icon',
                'xs'  => 'xs_icon',
            ],
            'conditions'  => "[enable_slider]==1",
        ]);

        $this->addField('xxl_slidesPerGroup', [
            'group'   => 'slider_options',
            'type'    => 'list',
            'label'   => 'slides_per_group',
            'default' => '',
            'conditions'  => "[enable_slider]==1 AND [slidesPerGroup_responsive]=='xxl'",
            'options' => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
                'auto' => 'auto',
            ],
        ]);

        $this->addField('xl_slidesPerGroup', [
            'group'   => 'slider_options',
            'type'    => 'list',
            'label'   => 'slides_per_group',
            'default' => '',
            'conditions'  => "[enable_slider]==1 AND [slidesPerGroup_responsive]=='xl'",
            'options' => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
                'auto' => 'auto',
            ],
        ]);

        $this->addField('lg_slidesPerGroup', [
            'group'   => 'slider_options',
            'type'    => 'list',
            'label'   => 'slides_per_group',
            'default' => '3',
            'conditions'  => "[enable_slider]==1 AND [slidesPerGroup_responsive]=='lg'",
            'options' => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
                'auto' => 'auto',
            ],
        ]);

        $this->addField('md_slidesPerGroup', [
            'group'   => 'slider_options',
            'type'    => 'list',
            'label'   => 'slides_per_group',
            'default' => '1',
            'conditions'  => "[enable_slider]==1 AND [slidesPerGroup_responsive]=='md'",
            'options' => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
                'auto' => 'auto',
            ],
        ]);

        $this->addField('sm_slidesPerGroup', [
            'group'   => 'slider_options',
            'type'    => 'list',
            'label'   => 'slides_per_group',
            'default' => '1',
            'conditions'  => "[enable_slider]==1 AND [slidesPerGroup_responsive]=='sm'",
            'options' => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
                'auto' => 'auto',
            ],
        ]);

        $this->addField('xs_slidesPerGroup', [
            'group'   => 'slider_options',
            'type'    => 'list',
            'label'   => 'slides_per_group',
            'default' => '1',
            'conditions'  => "[enable_slider]==1 AND [slidesPerGroup_responsive]=='xs'",
            'options' => [
                ''  => 'inherit',
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
                'auto' => 'auto',
            ],
        ]);

        $this->addField('avatar_position', [
            'group'   => 'avatar_options',
            'type'    => 'list',
            'label'   => 'avatar_position',
            'default' => 'top',
            'options' => [
                'top'    => 'top',
                'left'   => 'left',
                'bottom' => 'bottom',
                'right'  => 'right',
            ],
        ]);
        $this->addField('avatar_column_responsive', [
            'group'      => 'card_options',
            'type'       => 'radio',
            'attributes' => ['width'      => 'full'],
            'default'    => 'lg',
            'conditions' => "[avatar_position]=='left' OR [avatar_position]=='right'",
            'options'    => [
                'xxl' => 'xxl_icon',
                'xl'  => 'xl_icon',
                'lg'  => 'lg_icon',
                'md'  => 'md_icon',
                'sm'  => 'sm_icon',
                'xs'  => 'xs_icon',
            ],
        ]);

        $this->addField('xxl_column_avatar', [
            'group'      => 'card_options',
            'type'       => 'list',
            'label'      => 'xxl_column_avatar_width',
            'default'    => '',
            'conditions' => "[avatar_column_responsive]=='xxl' AND ([avatar_position]=='left' OR [avatar_position]=='right')",
            'options'    => [
                ''  => 'inherit',
                '12' => '1/1',
                '6'  => '1/2',
                '4'  => '1/3',
                '8'  => '2/3',
                '3'  => '1/4',
                '9'  => '3/4',
                '2'  => '1/6',
                '5'  => '5/12',
                '7'  => '7/12',
            ],
        ]);

        $this->addField('xl_column_avatar', [
            'group'      => 'card_options',
            'type'       => 'list',
            'label'      => 'xl_column_avatar_width',
            'default'    => '',
            'conditions' => "[avatar_column_responsive]=='xl' AND ([avatar_position]=='left' OR [avatar_position]=='right')",
            'options'    => [
                ''  => 'inherit',
                '12' => '1/1',
                '6'  => '1/2',
                '4'  => '1/3',
                '8'  => '2/3',
                '3'  => '1/4',
                '9'  => '3/4',
                '2'  => '1/6',
                '5'  => '5/12',
                '7'  => '7/12',
            ],
        ]);

        $this->addField('lg_column_avatar', [
            'group'      => 'card_options',
            'type'       => 'list',
            'label'      => 'lg_column_avatar_width',
            'default'    => '4',
            'conditions' => "[avatar_column_responsive]=='lg' AND ([avatar_position]=='left' OR [avatar_position]=='right')",
            'options'    => [
                ''  => 'inherit',
                '12' => '1/1',
                '6'  => '1/2',
                '4'  => '1/3',
                '8'  => '2/3',
                '3'  => '1/4',
                '9'  => '3/4',
                '2'  => '1/6',
                '5'  => '5/12',
                '7'  => '7/12',
            ],
        ]);

        $this->addField('md_column_avatar', [
            'group'      => 'card_options',
            'type'       => 'list',
            'label'      => 'md_column_avatar_width',
            'default'    => '12',
            'conditions' => "[avatar_column_responsive]=='md' AND ([avatar_position]=='left' OR [avatar_position]=='right')",
            'options'    => [
                ''  => 'inherit',
                '12' => '1/1',
                '6'  => '1/2',
                '4'  => '1/3',
                '8'  => '2/3',
                '3'  => '1/4',
                '9'  => '3/4',
                '2'  => '1/6',
                '5'  => '5/12',
                '7'  => '7/12',
            ],
        ]);

        $this->addField('sm_column_avatar', [
            'group'      => 'card_options',
            'type'       => 'list',
            'label'      => 'sm_column_avatar_width',
            'default'    => '12',
            'conditions' => "[avatar_column_responsive]=='sm' AND ([avatar_position]=='left' OR [avatar_position]=='right')",
            'options'    => [
                ''  => 'inherit',
                '12' => '1/1',
                '6'  => '1/2',
                '4'  => '1/3',
                '8'  => '2/3',
                '3'  => '1/4',
                '9'  => '3/4',
                '2'  => '1/6',
                '5'  => '5/12',
                '7'  => '7/12',
            ],
        ]);

        $this->addField('xs_column_avatar', [
            'group'      => 'card_options',
            'type'       => 'list',
            'label'      => 'xs_column_avatar_width',
            'default'    => '12',
            'conditions' => "[avatar_column_responsive]=='xs' AND ([avatar_position]=='left' OR [avatar_position]=='right')",
            'options'    => [
                ''  => 'inherit',
                '12' => '1/1',
                '6'  => '1/2',
                '4'  => '1/3',
                '8'  => '2/3',
                '3'  => '1/4',
                '9'  => '3/4',
                '2'  => '1/6',
                '5'  => '5/12',
                '7'  => '7/12',
            ],
        ]);

        $this->addField('image_max_width', [
            'group'      => 'avatar_options',
            'type'       => 'range',
            'label'      => 'max_width',
            'default'    => '200',
            'attributes' => ['min' => 1, 'max' => 1200, 'step' => 1, 'postfix' => 'px'],
        ]);
        $this->addField('image_margin', [
            'group' => 'avatar_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->addField('image_border', [
            'group' => 'avatar_options',
            'type'  => 'border',
            'label' => 'border',
        ]);

        $this->addField('image_border_radius', [
            'group'   => 'avatar_options',
            'type'    => 'list',
            'label'   => 'border_radius',
            'default' => '0',
            'options' => [
                'rounded' => 'rounded',
                '0'       => 'square',
                'circle'  => 'circle',
                'pill'    => 'pill',
            ],
        ]);

        $this->addField('image_rounded_size', [
            'group'      => 'avatar_options',
            'type'       => 'list',
            'label'      => 'rounded_size',
            'default'    => '3',
            'conditions' => "[image_border_radius]=='rounded'",
            'options'    => [
                '1' => 'X-Small',
                '2' => 'Small',
                '3' => 'Medium',
                '4' => 'Large',
                '5' => 'X-Large',
            ],
        ]);

        $this->addField('hover_effect', [
            'group'   => 'avatar_options',
            'type'    => 'list',
            'label'   => 'hover_effect',
            'default' => '',
            'options' => [
                ''        => 'default',
                'light-up'=> 'light_up',
                'flash'   => 'flash',
                'unveil'  => 'unveil',
            ],
        ]);

        $this->addField('hover_transition', [
            'group'   => 'avatar_options',
            'type'    => 'list',
            'label'   => 'hover_transition',
            'default' => '',
            'options' => Constants::$hover_transition,
        ]);

        $this->addField('title_html_element', [
            'group'   => 'name_options',
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
            "group"   => "name_options",
            "type"    => "typography",
            "label"   => "font_style",
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
                    'system_fonts' => Font::get_system_fonts(),
                    'text_transform_options' => Font::text_transform(),
                    'lang' => Font::font_properties(),
                ],
                'lang' => Font::font_properties(),
                'value' => Font::$get_default_font_value,
            ],
        ]);

        $this->addField('title_heading_margin', [
            'group' => 'name_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->addField('designation_font_style', [
            'group'   => 'designation_options',
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
                    'system_fonts' => Font::get_system_fonts(),
                    'text_transform_options' => Font::text_transform(),
                    'lang' => Font::font_properties(),
                ],
                'lang' => Font::font_properties(),
                'value' => Font::$get_default_font_value,
            ],
        ]);

        $this->addField('designation_heading_margin', [
            'group' => 'designation_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->addField('designation_position', [
            'group'   => 'designation_options',
            'type'    => 'list',
            'label'   => 'meta',
            'default' => 'after',
            'options' => [
                'before' => 'before_title',
                'after'  => 'after_title',
            ],
        ]);

        $this->addField('content_font_style', [
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
                    'system_fonts' => Font::get_system_fonts(),
                    'text_transform_options' => Font::text_transform(),
                    'lang' => Font::font_properties(),
                ],
                'lang' => Font::font_properties(),
                'value' => Font::$get_default_font_value,
            ],
        ]);

        $this->addField('content_margin', [
            'group' => 'content_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->addField('enable_rating', [
            'group'      => 'rating_options',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'enable_rating',
            'attributes' => ["role" => "switch"],
        ]);

        $this->addField('rating_color', [
            'group' => 'rating_options',
            'type'  => 'color',
            'label' => 'rating_color',
        ]);
        $this->addField('rating_margin', [
            'group' => 'rating_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->addField('testimonial_icon', [
            "type"       => "icons",
            'group' => 'icon_options',
            "label"      => "fa_icon",
        ]);
        $this->addField('testimonial_icon_size', [
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
        $this->addField('icon_margin', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->addField('testimonial_icon_color', [
            "group"      => "icon_options",
            "type"       => "color",
            "label"      => "color",
        ]);
    }
}