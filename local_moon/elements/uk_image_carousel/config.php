<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementUk_Image_Carousel extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'uk_image_carousel',
            'title' => 'Uk Image Carousel',
            'description' => 'Uk Image Carousel Widget of Moodle',
            'icon' => 'as-icon as-icon-pictures',
            'category' => 'media,utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('slideshow_options', [
            'type'  => 'group',
            'label' => 'Slider',
        ]);
        $this->addField('navigation_options', [
            'type'  => 'group',
            'label' => 'Navigation',
        ]);
        $this->addField('dot_options', [
            'type'  => 'group',
            'label' => 'dot_options',
        ]);

        $this->addField('title_options', [
            'type'  => 'group',
            'label' => 'title',
        ]);
        $this->addField('image_options', [
            'type'  => 'group',
            'label' => 'Image',
        ]);
        $this->addField('overlay_options', [
            'type'  => 'group',
            'label' => 'overlay',
        ]);

        $this->addField('meta_options', [
            'type'  => 'group',
            'label' => 'meta',
        ]);

        $this->addField('content_options', [
            'type'  => 'group',
            'label' => 'content',
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
        $this->addField('column_responsive', [
            "group"   => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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
            "group"      => "slideshow_options",
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


        $this->addField('slideshow_padding', [
            'group'      => 'slideshow_options',
            'type'       => 'spacing',
            'label'      => 'padding',
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
        $this->addField('autoplay_interval',  [
            "group" => "slideshow_options",
            "type" => "text",
            "label" => "Interval",
            "description" => "interval_desc",
            "conditions" => "[autoplay]==1",
        ]);
        $this->addField('center', [
            'group'   => 'slideshow_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Center',
        ]);
        $this->addField('parallax', [
            'group'   => 'slideshow_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Parallax',
        ]);


        $this->addField('navigation', [
            'group'   => 'navigation_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Nav Enable',
        ]);
        $this->addField('slider_nav_position', [
            'group'   => 'navigation_options',
            'type'    => 'list',
            'label'   => 'nav_position',
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
            'conditions' => "[navigation]==1",
        ]);
        $this->addField('navigation_wrap_margin', [
            'group'      => 'navigation_options',
            'type'       => 'spacing',
            'label'      => 'nav_margin',
            'conditions' => "[navigation]==1 AND [slider_nav_position] !=''",
        ]);
        $this->addField('navigation_padding', [
            'group'      => 'navigation_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[navigation]==1",
        ]);
        $this->addField('navigation_next_margin', [
            'group'      => 'navigation_options',
            'type'       => 'spacing',
            'label'      => 'next_margin',
            'conditions' => "[navigation]==1",
        ]);
        $this->addField('navigation_pre_margin', [
            'group'      => 'navigation_options',
            'type'       => 'spacing',
            'label'      => 'preview_margin',
            'conditions' => "[navigation]==1",
        ]);
        $this->addField('navigation_radius', [
            'group' => 'navigation_options',
            'type'  => 'spacing',
            'label' => 'radius',
            'conditions' => "[navigation]==1",
        ]);

        $this->addField('navigation_color', [
            "group"      => "navigation_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[navigation]==1",
        ]);
        $this->addField('navigation_bg_color', [
            "group"      => "navigation_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[navigation]==1",
        ]);
        $this->addField('navigation_color_hover', [
            "group"      => "navigation_options",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[navigation]==1",
        ]);
        $this->addField('navigation_bg_color_hover', [
            "group"      => "navigation_options",
            "type"       => "color",
            "label"      => "background_hover_color",
            "conditions" => "[navigation]==1",
        ]);

        $this->addField('dot_style', [
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
        $this->addField('dot_position', [
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
        $this->addField('dot_margin', [
            'group' => 'dot_options',
            'type'  => 'spacing',
            'name'  => 'dot_margin',
            'label' => 'margin',
        ]);
        $this->addField('dot_border_color', [
            'group'      => 'dot_options',
            'type'       => 'color',
            'label'      => 'border_color',
        ]);
        $this->addField('dot_color', [
            'group'      => 'dot_options',
            'type'       => 'color',
            'label'      => 'color',
        ]);
        $this->addField('dot_hover_color', [
            'group'      => 'dot_options',
            'type'       => 'color',
            'label'      => 'color_hover',
        ]);
        $this->addField('dot_below', [
            'group'   => 'dot_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'dot_below',
            'conditions' => "[dot_positions]=='dotnav' OR [dot_positions]=='thumbnav'",
        ]);
        $this->addField('dot_vertical', [
            'group'   => 'dot_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'dot_vertical',
            'conditions' => "[dot_positions]=='dotnav' OR [dot_positions]=='thumbnav' AND [dot_below]=='0'",
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

        $this->addField('overlay_padding', [
            'group' => 'overlay_options',
            'type'  => 'spacing',
            'name'  => 'overlay_padding',
            'label' => 'padding',
        ]);
        $this->addField('overlay_align', [
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
        $this->addField('overlay_positions', [
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
        $this->addField('overlay_bg_color', [
            'group'      => 'overlay_options',
            'type'       => 'color',
            'label'      => 'overlay_bg_color',
        ]);

        $this->addField('title_font_style', [
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
        $this->addField('meta_bg_color', [
            "group"      => "meta_options",
            "type"       => "color",
            "label"      => "background_color",
        ]);

        $this->addField('meta_heading_margin', [
            'group' => 'meta_options',
            'type'  => 'spacing',
            'name'  => 'meta_heading_margin',
            'label' => 'margin',
        ]);
        $this->addField('meta_heading_padding', [
            'group'      => 'meta_options',
            'type'       => 'spacing',
            'label'      => 'padding',
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
        $this->addField('meta_radius', [
            'group' => 'meta_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);

        $this->addField('image_width', [
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
        $this->addField('image_height', [
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
        $this->addField('image_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
        ]);
        $this->addField('image_border', [
            "group"      => "image_options",
            "type"       => "border",
            "label"      => "border",
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
        $this->addField('content_padding', [
            'group'      => 'content_options',
            'type'       => 'spacing',
            'label'      => 'padding',
        ]);

    }
}