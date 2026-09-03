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
 * @package   local_moon
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

defined('MOODLE_INTERNAL') || die;
use local_moon\library\helper\moon_element;
use local_moon\library\helper\form;
use local_moon\library\helper\constants;
use local_moon\library\helper\font;
class moon_element_grid extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'grid',
            'title' => 'Grid',
            'description' => 'Grid Widget of Moodle',
            'icon' => 'as-icon as-icon-profile',
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

        $this->add_field('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);

        $this->add_field('image_options', [
            'type'  => 'group',
            'label' => 'image_options',
        ]);

        $this->add_field('slider_options', [
            'type'  => 'group',
            'label' => 'slider_options',
        ]);
        $this->add_field('title_options', [
            'type'  => 'group',
            'label' => 'title_options',
        ]);

        $this->add_field('meta_options', [
            'type'  => 'group',
            'label' => 'meta_options',
        ]);

        $this->add_field('content_options', [
            'type'  => 'group',
            'label' => 'content_options',
        ]);

        $this->add_field('readmore_options', [
            'type'  => 'group',
            'label' => 'readmore_options',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'type' => [
                        'type' => 'list',
                        'label' => 'media_type',
                        'default' => '',
                        'options' => [
                            ''      => 'none',
                            'icon'  => 'icon',
                            'image' => 'image',
                        ],
                    ],

                    'icon_type' => [
                        'conditions' => "[type]=='icon'",
                        'type' => 'list',
                        'label' => 'icon_type',
                        'default' => 'fontawesome',
                        'options' => [
                            'fontawesome' => 'fontawesome',
                            'astroid'     => 'astroid_icon',
                            'custom'      => 'custom',
                        ],
                    ],

                    'fa_icon' => [
                        'conditions' => "[type]=='icon' AND [icon_type]=='fontawesome'",
                        'type' => 'icons',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'label' => 'icon',
                    ],

                    'as_icon' => [
                        'conditions' => "[type]=='icon' AND [icon_type]=='astroid'",
                        'type' => 'icons',
                        "attributes" => [
                            'source' => 'astroid',
                        ],
                        'label' => 'icon',
                    ],

                    'custom_icon' => [
                        'conditions' => "[type]=='icon' AND [icon_type]=='custom'",
                        'type' => 'text',
                        'label' => 'icon_class',
                        'dynamic' => true,
                    ],

                    'image' => [
                        'conditions' => "[type]=='image'",
                        'type' => 'media',
                        'label' => 'TPL_ASTROID_SELECT_IMAGE',
                        'dynamic' => true,
                    ],

                    'title' => [
                        'type' => 'text',
                        'label' => 'title',
                        'dynamic' => true,
                    ],

                    'meta' => [
                        'type' => 'text',
                        'label' => 'meta',
                        'dynamic' => true,
                    ],

                    'description' => [
                        'type' => 'editor',
                        'label' => 'description',
                        'dynamic' => true,
                    ],

                    'link' => [
                        'type' => 'text',
                        'label' => 'link_url',
                        'hint' => 'https://astroidframe.work',
                        'dynamic' => true,
                    ],

                    'link_title' => [
                        'conditions' => "[link]!=''",
                        'type' => 'text',
                        'label' => 'link_text',
                        'hint' => 'View More',
                        'dynamic' => true,
                    ],

                    'link_target' => [
                        'conditions' => "[link]!=''",
                        'type' => 'list',
                        'label' => 'link_target',
                        'default' => '',
                        'options' => [
                            ''         => 'Default',
                            '_blank'   => 'New Window',
                            '_parent'  => 'Parent Frame',
                            '_top'     => 'Full body of the window',
                        ],
                    ],

                    'enable_background_image' => [
                        'type' => 'radio',
                        "attributes" => [
                            "role" => "switch"
                        ],
                        'default' => '0',
                        'label' => 'enable_background_image',
                    ],

                    'background_image' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'media',
                        'label' => 'background_image',
                    ],

                    'background_repeat' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'list',
                        'label' => 'background_repeat',
                        'options' => [
                            '' => 'inherit',
                            'no-repeat' => 'no_repeat',
                            'repeat-x'  => 'repeat_x',
                            'repeat-y'  => 'repeat_y',
                        ],
                    ],

                    'background_size' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'list',
                        'label' => 'background_size',
                        'options' => [
                            '' => 'inherit',
                            'cover' => 'cover',
                            'contain' => 'contain',
                        ],
                    ],

                    'background_attachment' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'list',
                        'label' => 'background_attachment',
                        'options' => [
                            '' => 'inherit',
                            'scroll' => 'scroll',
                            'fixed'  => 'fixed',
                        ],
                    ],

                    'background_position' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'list',
                        'label' => 'background_position',
                        'options' => [
                            '' => 'inherit',
                            "left top" => "left_top",
                            "left center" => "left_center",
                            "left bottom" => "left_bottom",
                            "right top" => "right_top",
                            "right center" => "right_center",
                            "right bottom" => "right_bottom",
                            "center top" => "center_top",
                            "center center" => "center_center",
                            "center bottom" => "center_bottom",
                        ],
                    ],
                    'item_background_color' => [
                        'type' => 'color',
                        'label' => 'background_color',
                    ],
                    'item_background_overlay' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'color',
                        'label' => 'overlay_bg_color',
                    ],
                    'item_background_overlay_hover' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'color',
                        'label' => 'overlay_bg_color_hover',
                    ],
                ]
            ],
        ];

        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->add_field('grids',  [
            "group" => "general",
            "type" => "subform",
            "label" => "grids",
            "attributes" => [
                'form'    =>  $repeater->render_json('subform')
            ],
        ]);

        $this->add_field('column_responsive', [
            "group"   => "grid_options",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "lg",
            "options" => [
                'xxl' => 'xxl_icon',
                'xl'  => 'xl_icon',
                'lg'  => 'lg_icon',
                'md'  => 'md_icon',
                'sm'  => 'sm_icon',
                'xs'  => 'xs_icon',
            ],
        ]);

        $this->add_field('xxl_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xxl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('xl_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('lg_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "lg_column",
            "default"    => "3",
            "conditions" => "[column_responsive]=='lg'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('md_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "md_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='md'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('sm_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "sm_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='sm'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('xs_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xs_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='xs'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->add_field('row_gutter_xxl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xxl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter_xl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter_lg', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_lg",
            "default"    => "4",
            "conditions" => "[column_responsive]=='lg'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter_md', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_md",
            "default"    => "3",
            "conditions" => "[column_responsive]=='md'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter_sm', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_sm",
            "default"    => "3",
            "conditions" => "[column_responsive]=='sm'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('row_gutter', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xs",
            "default"    => "3",
            "conditions" => "[column_responsive]=='xs'",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_xxl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xxl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_xl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_lg', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_lg",
            "default"    => "4",
            "conditions" => "[column_responsive]=='lg'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_md', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_md",
            "default"    => "3",
            "conditions" => "[column_responsive]=='md'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter_sm', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_sm",
            "default"    => "3",
            "conditions" => "[column_responsive]=='sm'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('column_gutter', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xs",
            "default"    => "3",
            "conditions" => "[column_responsive]=='xs'",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('use_masonry', [
            "group"   => "grid_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "use_masonry",
        ]);

        $this->add_field('card_style', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "card_style",
            "default" => "",
            "options" => [
                ""          => "default",
                "primary"   => "Primary",
                "secondary" => "Secondary",
                "success"   => "Success",
                "danger"    => "Danger",
                "warning"   => "Warning",
                "info"      => "Info",
                "light"     => "Light",
                "dark"      => "Dark",
                "none"      => "None",
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
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "card_size",
            "default" => "",
            "options" => [
                "none"   => "none",
                ""       => "default",
                "small"  => "small",
                "large"  => "large",
                "custom" => "custom",
            ],
        ]);

        $this->add_field('card_padding', [
            "group"      => "card_options",
            "type"       => "spacing",
            "label"      => "padding",
            "conditions" => "[card_size]=='custom'",
        ]);

        $this->add_field('card_border_radius', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "",
            "options" => [
                ""       => "rounded",
                "0"      => "squared",
                "circle" => "circle",
                "pill"   => "pill",
                "custom"   => "custom",
            ],
        ]);
        $this->add_field('card_custom_radius', [
            'group' => 'card_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
            "conditions" => "[card_border_radius]=='custom'",
        ]);

        $this->add_field('card_rounded_size', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "rounded_size",
            "default"    => "3",
            "conditions" => "[card_border_radius]==''",
            "options"    => [
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('media_position', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "media_position",
            "default" => "inside",
            "options" => [
                "top"    => "top",
                "left"   => "left",
                "bottom" => "bottom",
                "right"  => "right",
                "inside" => "inside",
                "left_title" => "Left Title",
                "cover" => "cover",
            ],
        ]);
        $this->add_field('content_position', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "content_position",
            "default" => "bottom_left",
            "options" => [
                "uk-position-top"  => "top",
                "uk-position-center"  => "center",
                "uk-position-bottom"  => "bottom",
            ],
            "conditions" => "[media_position]=='cover'",
        ]);
        $this->add_field('media_hover_transition', [
            "group"      => "card_options",
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
            "conditions" => "[media_position]=='cover'",
        ]);

        $this->add_field('media_margin', [
            "group" => "card_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->add_field('media_column_responsive', [
            "group"   => "card_options",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "lg",
            "conditions" => "[media_position]=='left' OR [media_position]=='right'",
            "options" => [
                "xxl" => "xxl_icon",
                "xl"  => "xl_icon",
                "lg"  => "lg_icon",
                "md"  => "md_icon",
                "sm"  => "sm_icon",
                "xs"  => "xs_icon",
            ],
        ]);

        // media columns (xxl/xl/lg/md/sm/xs) with conditions
        $this->add_field('xxl_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "xxl_column_media_width",
            "default"    => "",
            "conditions" => "[media_column_responsive]=='xxl' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('xl_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "xl_column_media_width",
            "default"    => "",
            "conditions" => "[media_column_responsive]=='xl' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('lg_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "lg_column_media_width",
            "default"    => "4",
            "conditions" => "[media_column_responsive]=='lg' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('md_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "md_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='md' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('sm_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "sm_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='sm' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('xs_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "xs_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='xs' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->add_field('vertical_middle', [
            "group"      => "card_options",
            "type"       => "radio",
            "default"    => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"      => "vertical_middle",
            "conditions" => "[media_position]=='left' OR [media_position]=='right'",
        ]);

        $this->add_field('enable_grid_match', [
            "group"   => "card_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "enable_grid_match",
        ]);

        $this->add_field('card_hover_transition', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "hover_transition",
            "default" => "",
            "options" => constants::$hover_transition,
        ]);

        $this->add_field('card_box_shadow', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "box_shadow",
            "default" => "",
            "options" => [
                ""             => "default",
                "shadow-none"  => "none",
                "shadow-sm"    => "sm",
                "shadow"       => "md",
                "shadow-lg"    => "lg",
            ],
        ]);

        $this->add_field('card_box_shadow_hover', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "box_shadow_hover",
            "default" => "",
            "options" => [
                ""                    => "default",
                "shadow-hover-none"   => "none",
                "shadow-hover-sm"     => "sm",
                "shadow-hover"        => "md",
                "shadow-hover-lg"     => "lg",
                "shadow-hover-popout" => "popout",
            ],
        ]);
        $this->add_field('title_color_hover', [
            "group" => "card_options",
            "type"  => "color",
            "label" => "card_hover_title_color",
        ]);
        $this->add_field('content_color_hover', [
            "group" => "card_options",
            "type"  => "color",
            "label" => "card_hover_content_color",
        ]);
        $this->add_field('card_button_color_hover', [
            "group" => "card_options",
            "type"  => "color",
            "label" => "card_hover_button_color",
        ]);

        $this->add_field('enable_slider', [
            "group"   => "slider_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "enable_slider",
        ]);
        $this->add_field('autoplay', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'autoplay',
            "conditions" => "[enable_slider]==1",
        ]);
        $this->add_field('navigation', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Navigation',
            "conditions" => "[enable_slider]==1",
        ]);

        $this->add_field('dot', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => 1,
            'label'   => 'Dotnav',
            "conditions" => "[enable_slider]==1",
        ]);
        $this->add_field('dot_margin', [
            "group" => "slider_options",
            "type"  => "spacing",
            "label" => "dot_margin",
            'conditions' => "[dot]==1",
        ]);
        $this->add_field('icon_size', [
            "group"   => "icon_options",
            "type"    => "range",
            "label"   => "icon_size",
            "attributes" => [
                "min"     => 1,
                "max"     => 300,
                "step"    => 1,
                "postfix"    => "px",
            ],
            "default" => 60,
        ]);
        $this->add_field('icon_box_width', [
            'group'   => 'icon_options',
            'type'    => 'range',
            'label'      => 'box_width',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->add_field('icon_box_height', [
            'group'   => 'icon_options',
            'type'    => 'range',
            'label'      => 'box_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->add_field('icon_box_radius', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'name'  => 'icon_box_radius',
            'label' => 'border_radius',
        ]);

        $this->add_field('icon_color', [
            "group" => "icon_options",
            "type"  => "color",
            "label" => "color",
        ]);

        $this->add_field('icon_bg_color', [
            "group" => "icon_options",
            "type"  => "color",
            "label" => "background_color",
        ]);

        $this->add_field('icon_color_hover', [
            "group" => "icon_options",
            "type"  => "color",
            "label" => "color_hover",
        ]);

        $this->add_field('icon_bgcolor_hover', [
            "group" => "icon_options",
            "type"  => "color",
            "label" => "background_color_hover",
        ]);

        $this->add_field('enable_icon_link', [
            "group"   => "icon_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "enable_icon_link",
        ]);

        $this->add_field('layout', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "choose_layout",
            "default" => "classic",
            "options" => [
                "classic" => "classic",
                "overlay" => "overlay",
            ],
        ]);

        $this->add_field('image_fullwidth', [
            "group"   => "image_options",
            "type"    => "radio",
            "default" => "1",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "image_fullwidth",
            "conditions" => "[layout]=='overlay'",
        ]);

        $this->add_field('enable_image_cover', [
            "group"   => "image_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "enable_image_cover",
            "conditions" => "[layout]=='overlay'",
        ]);
        $this->add_field('image_width', [
            'group'   => 'image_options',
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
        $this->add_field('image_height', [
            'group'   => 'image_options',
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

        $this->add_field('min_height', [
            "group"      => "image_options",
            "type"       => "range",
            "label"      => "min_height",
            "attributes" => [
                "min"        => 1,
                "max"        => 1000,
                "step"       => 1,
                "postfix"    => "px",
            ],
            "default"    => 200,
            "conditions" => "[enable_image_cover]==1",
        ]);

        $this->add_field('overlay_type', [
            "group"      => "image_options",
            "type"       => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default"    => "color",
            "label"      => "overlay_color",
            "conditions" => "[enable_image_cover]==1",
            "options"    => [
                ""         => "none",
                "color"    => "color",
                "gradient" => "gradient",
            ],
        ]);

        $this->add_field('overlay_color', [
            "group"      => "image_options",
            "type"       => "color",
            "label"      => "overlay_color",
            "conditions" => "[enable_image_cover]==1 AND [overlay_type]=='color'",
        ]);

        $this->add_field('overlay_gradient', [
            "group"      => "image_options",
            "type"       => "gradient",
            "label"      => "overlay_gradient",
            "conditions" => "[enable_image_cover]==1 AND [overlay_type]=='gradient'",
        ]);

        $this->add_field('image_border_radius', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "0",
            "options" => [
                "rounded" => "rounded",
                "0"       => "square",
                "circle"  => "circle",
                "pill"    => "pill",
                "custom"    => "custom",
            ],
        ]);
        $this->add_field('image_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
            "conditions" => "[image_border_radius]=='custom'",
        ]);

        $this->add_field('image_rounded_size', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "rounded_size",
            "default"    => "3",
            "conditions" => "[image_border_radius]=='rounded'",
            "options"    => [
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->add_field('hover_effect', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "hover_effect",
            "default" => "",
            "options" => [
                ""        => "default",
                "as-effect-light-up" => "light_up",
                "as-effect-flash"    => "flash",
                "as-effect-unveil"   => "unveil",
                "uk-transition-scale-up"   => "scale_up",
                "uk-transition-scale-down"   => "scale_down",

            ],
        ]);

        $this->add_field('hover_transition', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "hover_transition",
            "default" => "",
            "options" => constants::$hover_transition,
        ]);

        $this->add_field('title_html_element', [
            "group"   => "title_options",
            "type"    => "list",
            "label"   => "html_element",
            "default" => "h3",
            "options" => [
                "h1" => "h1",
                "h2" => "h2",
                "h3" => "h3",
                "h4" => "h4",
                "h5" => "h5",
                "h6" => "h6",
                "div" => "div",
            ],
        ]);

        $this->add_field('title_font_style', [
            "group"   => "title_options",
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
            "group" => "title_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->add_field('meta_font_style', [
            "group"   => "meta_options",
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
        $this->add_field('meta_heading_bg', [
            "group"      => "meta_options",
            "type"       => "color",
            "label"      => "background_color",
        ]);

        $this->add_field('meta_heading_margin', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->add_field('meta_heading_padding', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "padding",
        ]);

        $this->add_field('meta_heading_radius', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "radius",
        ]);

        $this->add_field('meta_position', [
            "group"   => "meta_options",
            "type"    => "list",
            "label"   => "meta_position",
            "default" => "before",
            "options" => [
                "before" => "before_title",
                "after" => "after_title",
                "uk-position-top-left"  => "top_left",
                "uk-position-top-center"  => "top_center",
                "uk-position-top-right"  => "top_right",
                "uk-position-center-left"  => "center_left",
                "uk-position-center"  => "center_center",
                "uk-position-center-right"  => "center_right",
                "uk-position-bottom-left"  => "bottom_left",
                "uk-position-bottom-center"  => "bottom_center",
                "uk-position-bottom-right"  => "bottom_right",
            ],
        ]);

        $this->add_field('content_font_style', [
            "group"   => "content_options",
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
        $this->add_field('button_font_style', [
            "group"   => "readmore_options",
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

        $this->add_field('button_style', [
            "group"   => "readmore_options",
            "type"    => "list",
            "label"   => "style",
            "name"    => "button_style",
            "default" => "primary",
            "options" => [
                "primary"   => "Primary",
                "secondary" => "Secondary",
                "success"   => "Success",
                "danger"    => "Danger",
                "warning"   => "Warning",
                "info"      => "Info",
                "light"     => "Light",
                "dark"      => "Dark",
                "link"      => "Link",
                "text"      => "Text",
                "custom"      => "custom",
            ],
        ]);
        $this->add_field('button_color', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[button_style]=='custom'",
        ]);
        $this->add_field('button_bg_color', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[button_style]=='custom'",
        ]);
        $this->add_field('button_color_hover', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[button_style]=='custom'",
        ]);
        $this->add_field('button_bg_color_hover', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "background_hover_color",
            "conditions" => "[button_style]=='custom'",
        ]);

        $this->add_field('button_outline', [
            "group"   => "readmore_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "button_outline",
        ]);

        $this->add_field('button_size', [
            "group"   => "readmore_options",
            "type"    => "list",
            "label"   => "button_size",
            "default" => "",
            "options" => [
                ""       => "Default",
                "btn-lg" => "Large",
                "btn-sm" => "Small",
            ],
        ]);
        $this->add_field('button_padding', [
            "group"      => "readmore_options",
            "type"       => "spacing",
            "label"      => "padding",
        ]);

        $this->add_field('btn_border_radius', [
            "group"   => "readmore_options",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "",
            "options" => [
                ""             => "Rounded",
                "rounded-0"    => "Square",
                "rounded-pill" => "Circle",
                "custom" => "custom",
            ],
        ]);
        $this->add_field('button_custom_radius', [
            'group' => 'readmore_options',
            'type'  => 'spacing',
            'label' => 'radius',
            "conditions" => "[btn_border_radius]=='custom'",
        ]);
        $this->add_field('button_custom_margin', [
            "group" => "readmore_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
    }
}