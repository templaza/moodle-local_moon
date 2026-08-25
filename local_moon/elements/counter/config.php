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
class MoonElementCounter extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'counter',
            'title' => 'Counter',
            'description' => 'Counter Widget of Moodle',
            'icon' => 'as-icon as-icon-hourglass',
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

        $this->addField('content_options', [
            'type'  => 'group',
            'label' => 'content_options',
        ]);

        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'title' => [
                        "type"    => "text",
                        "label"   => "number",
                        "attributes" => [
                            'hint' => '100'
                        ]
                    ],
                    'duration' => [
                        "type"    => "text",
                        "label"   => "duration",
                        "attributes" => [
                            'hint' => '500'
                        ]
                    ],
                    'prefix' => [
                        "type"    => "text",
                        "label"   => "prefix",
                        "attributes" => [
                            'hint' => '+,K,$'
                        ]
                    ],
                    'prefix_position' => [
                        "type"    => "radio",
                        "label"   => "prefix_position",
                        "default" => "before",
                        "options" => [
                            "before" => "before",
                            "after" => "after",
                        ],
                    ],
                    'use_number_format' => [
                        "type"       => "radio",
                        "label"      => "use_number_format",
                        "default"    => "0",
                        "attributes" => [
                            'role' => 'switch'
                        ]
                    ],
                    'separator' => [
                        "type"    => "text",
                        "label"   => "separator",
                        "attributes" => [
                            'hint' => ','
                        ],
                        "conditions" => "[use_number_format]==1",
                    ],
                    'alignment' => [
                        "type"       => "radio",
                        "label"      => "alignment",
                        "default"    => "center",
                        "attributes" => [
                            'width' => 'full'
                        ],
                        "options" => [
                            "left" => "left",
                            "center" => "center",
                            "right" => "right",
                        ],
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);

        $this->addField('items', [
            "group" => "general",
            "type"  => "subform",
            "label" => "items",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);

        $this->addField('column_responsive', [
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

        $this->addField('xxl_column', [
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

        $this->addField('xl_column', [
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

        $this->addField('lg_column', [
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

        $this->addField('md_column', [
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

        $this->addField('sm_column', [
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

        $this->addField('xs_column', [
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

        $this->addField('row_gutter_xxl', [
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

        $this->addField('row_gutter_xl', [
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

        $this->addField('row_gutter_lg', [
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

        $this->addField('row_gutter_md', [
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

        $this->addField('row_gutter_sm', [
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

        $this->addField('row_gutter', [
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

        $this->addField('column_gutter_xxl', [
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

        $this->addField('column_gutter_xl', [
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

        $this->addField('column_gutter_lg', [
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

        $this->addField('column_gutter_md', [
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

        $this->addField('column_gutter_sm', [
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

        $this->addField('column_gutter', [
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

        $this->addField('use_masonry', [
            "group"   => "grid_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "use_masonry",
        ]);

        $this->addField('card_style', [
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

        $this->addField('card_padding', [
            "group"      => "card_options",
            "type"       => "spacing",
            "label"      => "padding",
            "conditions" => "[card_size]=='custom'",
        ]);

        $this->addField('card_border_radius', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "",
            "options" => [
                ""       => "rounded",
                "0"      => "squared",
                "circle" => "circle",
                "pill"   => "pill",
            ],
        ]);

        $this->addField('card_rounded_size', [
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

        $this->addField('enable_grid_match', [
            "group"   => "card_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "enable_grid_match",
        ]);

        $this->addField('card_hover_transition', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "hover_transition",
            "default" => "",
            "options" => Constants::$hover_transition,
        ]);

        $this->addField('card_box_shadow', [
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

        $this->addField('card_box_shadow_hover', [
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

        $this->addField('content_font_style', [
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
                    'system_fonts' => Font::get_system_fonts(),
                    'text_transform_options' => Font::text_transform(),
                    'lang' => Font::font_properties(),
                ],
                'lang' => Font::font_properties(),
                'value' => Font::$get_default_font_value,
            ],
        ]);
    }
}