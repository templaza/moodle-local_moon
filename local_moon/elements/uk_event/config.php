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
use local_moon\library\blocks\event_handler;
class moon_element_uk_event extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'uk_event',
            'title' => 'UK Event',
            'description' => 'List Events of Moodle',
            'icon' => 'as-icon as-icon-grid3',
            'category' => 'utility,uikit',
            'element_type' => 'widget'
        ]);

    }
    public function set_fields(): void {
        $moonEventHandler = new event_handler();
        $events = $moonEventHandler->moon_get_moodle_events_options();

        $this->set_field_set('general-settings');
        $this->add_field('grid_options', [
            'type'  => 'group',
            'label' => 'grid_options',
        ]);
        $this->add_field('item_options', [
            "type"  => "group",
            "label" => "item_options",
        ]);
        $this->add_field('image_options', [
            "type"  => "group",
            "label" => "image_options",
        ]);

        $this->add_field('title_options', [
            "type"  => "group",
            "label" => "title_options",
        ]);

        $this->add_field('content_options', [
            "type"  => "group",
            "label" => "content_options",
        ]);
        $this->add_field('readmore_options', [
            "type"  => "group",
            "label" => "readmore_options",
        ]);

        $this->add_field('event_layout', [
            "group"   => "general",
            "type"    => "list",
            "label" => "layout",
            "default" => "",
            "options" => [
                '' => 'default',
                'list'  => 'list',
            ],
        ]);

        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'image' => [
                        'type'    => 'media',
                        'label'   => 'TPL_ASTROID_SELECT_IMAGE',
                    ],
                    'event' => [
                        "type"    => "list",
                        "label"   => "event",
                        "default" => "",
                        "options" => $events,
                    ],
                ]
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);

        $this->add_field('list_events', [
            "group" => "general",
            "type"  => "subform",
            "label" => "list_items",
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
            "conditions" => "[event_layout]=='' ",
        ]);

        $this->add_field('xxl_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xxl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl' AND [event_layout]=='' ",
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
            "conditions" => "[column_responsive]=='xl' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='lg' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='md' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='sm' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='xs' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='xxl' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='xl' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='lg' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='md' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='sm' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='xs' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='xxl' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='xl' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='lg' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='md' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='sm' AND [event_layout]==''",
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
            "conditions" => "[column_responsive]=='xs' AND [event_layout]==''",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);
        $this->add_field('item_bg_color', [
            "group"      => "item_options",
            "type"       => "color",
            "label"      => "background_color",
        ]);
        $this->add_field('item_border', [
            "group"      => "item_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->add_field('item_border_radius', [
            'group' => 'item_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);

        $this->add_field('item_card_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'card_padding',
        ]);

        $this->add_field('content_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'content_padding',
        ]);
        $this->add_field('image_layout', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "image_layout",
            "default"    => "",
            "options"    => [
                "" => "default",
                "overlay" => "content_overlay",
            ],
        ]);
        $this->add_field('content_position', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "content_position",
            "default" => "uk-position-bottom",
            "options" => [
                "uk-position-top"  => "top",
                "uk-position-center"  => "center",
                "uk-position-bottom"  => "bottom",
            ],
            "conditions" => "[image_layout]=='overlay'",
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
        $this->add_field('image_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'label' => 'border_radius',
        ]);
        $this->add_field('overlay_type', [
            "group"      => "image_options",
            "type"       => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default"    => "color",
            "label"      => "overlay_color",
            "conditions" => "[image_layout]=='overlay'",
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
            "conditions" => "[image_layout]=='overlay' AND [overlay_type]=='color'",
        ]);

        $this->add_field('overlay_gradient', [
            "group"      => "image_options",
            "type"       => "gradient",
            "label"      => "overlay_gradient",
            "conditions" => "[image_layout]=='overlay' AND [overlay_type]=='gradient'",
        ]);
        $this->add_field('image_hover_transition', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "hover_transition",
            "default"    => "",
            "options"    => [
                '' => 'default',
                'uk-transition-scale-up' => 'scale_up',
                'uk-transition-scale-down' => 'scale_down',
            ],
        ]);
        $this->add_field('title_font_style', [
            "group"      => "title_options",
            "type"       => "typography",
            "label"       => "font_style",
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
            "label"  => "margin",
        ]);

        $this->add_field('duration_font_style', [
            "group"      => "content_options",
            "type"       => "typography",
            "label"       => "font_style",
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
        $this->add_field('start_icon', [
            "group" => "content_options",
            "type"  => "icons",
            "label"  => "icon_start_date",
        ]);
        $this->add_field('end_icon', [
            "group" => "content_options",
            "type"  => "icons",
            "label"  => "icon_end_date",
        ]);
        $this->add_field('icon_size', [
            'group'      => 'content_options',
            'type'       => 'range',
            'label'      => 'icon_size',
            "attributes" => [
                'min'        => 1,
                'max'        => 300,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 12,
        ]);
        $this->add_field('icon_margin', [
            'group' => 'content_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->add_field('button_text',  [
            "group" => "readmore_options",
            "type" => "text",
            "label" => "title",
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_icon', [
            "group" => "readmore_options",
            "type"  => "icons",
            "label"  => "icon",
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_color', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_bg_color', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_color_hover', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_bg_color_hover', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "background_color_hover",
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_padding', [
            'group'      => 'readmore_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_margin', [
            "group" => "readmore_options",
            "type"  => "spacing",
            "label"  => "margin",
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_radius', [
            'group' => 'readmore_options',
            'type'  => 'spacing',
            'label' => 'border_radius',
            "conditions" => "[event_layout]=='list'",
        ]);
        $this->add_field('button_align', [
            'group'   => 'readmore_options',
            'type'    => 'list',
            'label'   => 'alignment',
            'default' => '',
            'options' => [
                ''          => 'Default',
                'text-md-center'   => 'center',
                'text-md-right'   => 'right',
            ],
            "conditions" => "[event_layout]=='list'",
        ]);

    }
}