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
class moon_element_testimonials extends moon_element {
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
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('grid_options', [
            'type'  => 'group',
            'label' => 'grid_options',
        ]);

        $this->add_field('card_options', [
            'type'  => 'group',
            'label' => 'card_options',
        ]);

        $this->add_field('slider_options', [
            'type'  => 'group',
            'label' => 'slider_options',
        ]);

        $this->add_field('avatar_options', [
            'type'  => 'group',
            'label' => 'avatar_options',
        ]);

        $this->add_field('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);

        $this->add_field('name_options', [
            'type'  => 'group',
            'label' => 'name_options',
        ]);

        $this->add_field('designation_options', [
            'type'  => 'group',
            'label' => 'designation_options',
        ]);

        $this->add_field('content_options', [
            'type'  => 'group',
            'label' => 'content_options',
        ]);

        $this->add_field('rating_options', [
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
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('testimonials',  [
            "group" => "general",
            "type" => "subform",
            "label" => "testimonials",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
        ]);
        $this->add_field('overlay_text_color', [
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

        $this->add_field('column_responsive', [
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

        $this->add_field('xxl_column', [
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

        $this->add_field('xl_column', [
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

        $this->add_field('lg_column', [
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

        $this->add_field('md_column', [
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

        $this->add_field('sm_column', [
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

        $this->add_field('xs_column', [
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

        $this->add_field('row_gutter_xxl', [
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

        $this->add_field('row_gutter_xl', [
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

        $this->add_field('row_gutter_lg', [
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

        $this->add_field('row_gutter_md', [
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

        $this->add_field('row_gutter_sm', [
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

        $this->add_field('row_gutter', [
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

        $this->add_field('column_gutter_xxl', [
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

        $this->add_field('column_gutter_xl', [
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

        $this->add_field('column_gutter_lg', [
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

        $this->add_field('column_gutter_md', [
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

        $this->add_field('column_gutter_sm', [
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

        $this->add_field('column_gutter', [
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

        $this->add_field('use_masonry', [
            'group'      => 'grid_options',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'use_masonry',
            'attributes' => ["role" => "switch"],
        ]);

        $this->add_field('card_style', [
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
        $this->add_field('text_color', [
            "group"      => "card_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->add_field('bg_color', [
            "group"      => "card_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->add_field('card_border', [
            "group"      => "card_options",
            "type"       => "border",
            "label"      => "border",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->add_field('card_size', [
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

        $this->add_field('card_padding', [
            'group'      => 'card_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[card_size]=='custom'",
        ]);

        $this->add_field('card_margin', [
            'group'      => 'card_options',
            'type'       => 'spacing',
            'label'      => 'margin',
            'conditions' => "[card_size]=='custom'",
        ]);

        $this->add_field('card_border_radius', [
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
        $this->add_field('card_radius_custom', [
            'group' => 'card_options',
            'type'  => 'spacing',
            'label' => 'radius',
            'conditions' => "[card_border_radius]=='custom'",
        ]);

        $this->add_field('card_rounded_size', [
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



        $this->add_field('enable_grid_match', [
            'group'      => 'card_options',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'enable_grid_match',
            'attributes' => ["role" => "switch"],
        ]);

        $this->add_field('card_hover_transition', [
            'group'   => 'card_options',
            'type'    => 'list',
            'label'   => 'hover_transition',
            'default' => '',
            'options' => constants::$hover_transition,
        ]);

        $this->add_field('card_box_shadow', [
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

        $this->add_field('card_box_shadow_hover', [
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
        $this->add_field('card_vertical_align', [
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

        $this->add_field('enable_slider', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'enable_slider',
            'attributes' => ["role" => "switch"],
        ]);
        $this->add_field('slider_padding', [
            'group' => 'slider_options',
            'type'  => 'spacing',
            'label' => 'slider_padding',
        ]);

        $this->add_field('slider_autoplay', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'autoplay',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->add_field('interval', [
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

        $this->add_field('speed', [
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

        $this->add_field('freemode', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'freemode',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->add_field('loop', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'loop',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->add_field('slider_nav', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '1',
            'label'      => 'navigation',
            'conditions' => "[enable_slider]==1",
        ]);
        $this->add_field('slider_nav_position', [
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
        $this->add_field('slider_nav_height', [
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
        $this->add_field('slider_nav_width', [
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
        $this->add_field('slider_nav_border', [
            "group"      => "slider_options",
            "type"       => "border",
            "label"      => "border",
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);

        $this->add_field('slider_nav_radius', [
            'group' => 'slider_options',
            'type'  => 'spacing',
            'label' => 'radius',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->add_field('next_margin', [
            'group' => 'slider_options',
            'type'  => 'spacing',
            'label' => 'next_margin',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->add_field('preview_margin', [
            'group' => 'slider_options',
            'type'  => 'spacing',
            'label' => 'preview_margin',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);

        $this->add_field('nav_color', [
            'group' => 'slider_options',
            'type'  => 'color',
            'label' => 'color_hover',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->add_field('nav_bg_color', [
            'group' => 'slider_options',
            'type'  => 'color',
            'label' => 'background_color',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->add_field('nav_color_hover', [
            'group' => 'slider_options',
            'type'  => 'color',
            'label' => 'color_hover',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->add_field('nav_bg_color_hover', [
            'group' => 'slider_options',
            'type'  => 'color',
            'label' => 'background_color_hover',
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);
        $this->add_field('slider_nav_border_hover', [
            "group"      => "slider_options",
            "type"       => "border",
            "label"      => "border_hover",
            'conditions' => "[slider_nav]==1 AND [enable_slider]==1",
        ]);

        $this->add_field('slider_dotnav', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'dot_navigation',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->add_field('slider_scrollbar', [
            'group'      => 'slider_options',
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'scrollbar',
            'conditions' => "[enable_slider]==1",
        ]);

        $this->add_field('slidesPerGroup_responsive', [
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

        $this->add_field('xxl_slidesPerGroup', [
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

        $this->add_field('xl_slidesPerGroup', [
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

        $this->add_field('lg_slidesPerGroup', [
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

        $this->add_field('md_slidesPerGroup', [
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

        $this->add_field('sm_slidesPerGroup', [
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

        $this->add_field('xs_slidesPerGroup', [
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

        $this->add_field('avatar_position', [
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
        $this->add_field('avatar_column_responsive', [
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

        $this->add_field('xxl_column_avatar', [
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

        $this->add_field('xl_column_avatar', [
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

        $this->add_field('lg_column_avatar', [
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

        $this->add_field('md_column_avatar', [
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

        $this->add_field('sm_column_avatar', [
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

        $this->add_field('xs_column_avatar', [
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

        $this->add_field('image_max_width', [
            'group'      => 'avatar_options',
            'type'       => 'range',
            'label'      => 'max_width',
            'default'    => '200',
            'attributes' => ['min' => 1, 'max' => 1200, 'step' => 1, 'postfix' => 'px'],
        ]);
        $this->add_field('image_margin', [
            'group' => 'avatar_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->add_field('image_border', [
            'group' => 'avatar_options',
            'type'  => 'border',
            'label' => 'border',
        ]);

        $this->add_field('image_border_radius', [
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

        $this->add_field('image_rounded_size', [
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

        $this->add_field('hover_effect', [
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

        $this->add_field('hover_transition', [
            'group'   => 'avatar_options',
            'type'    => 'list',
            'label'   => 'hover_transition',
            'default' => '',
            'options' => constants::$hover_transition,
        ]);

        $this->add_field('title_html_element', [
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

        $this->add_field('title_font_style', [
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
                    'system_fonts' => font::get_system_fonts(),
                    'text_transform_options' => font::text_transform(),
                    'lang' => font::font_properties(),
                ],
                'lang' => font::font_properties(),
                'value' => font::$get_default_font_value,
            ],
        ]);

        $this->add_field('title_heading_margin', [
            'group' => 'name_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->add_field('designation_font_style', [
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
                    'system_fonts' => font::get_system_fonts(),
                    'text_transform_options' => font::text_transform(),
                    'lang' => font::font_properties(),
                ],
                'lang' => font::font_properties(),
                'value' => font::$get_default_font_value,
            ],
        ]);

        $this->add_field('designation_heading_margin', [
            'group' => 'designation_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->add_field('designation_position', [
            'group'   => 'designation_options',
            'type'    => 'list',
            'label'   => 'meta',
            'default' => 'after',
            'options' => [
                'before' => 'before_title',
                'after'  => 'after_title',
            ],
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

        $this->add_field('content_margin', [
            'group' => 'content_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->add_field('enable_rating', [
            'group'      => 'rating_options',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'enable_rating',
            'attributes' => ["role" => "switch"],
        ]);

        $this->add_field('rating_color', [
            'group' => 'rating_options',
            'type'  => 'color',
            'label' => 'rating_color',
        ]);
        $this->add_field('rating_margin', [
            'group' => 'rating_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->add_field('testimonial_icon', [
            "type"       => "icons",
            'group' => 'icon_options',
            "label"      => "fa_icon",
        ]);
        $this->add_field('testimonial_icon_size', [
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
        $this->add_field('icon_margin', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->add_field('testimonial_icon_color', [
            "group"      => "icon_options",
            "type"       => "color",
            "label"      => "color",
        ]);
    }
}