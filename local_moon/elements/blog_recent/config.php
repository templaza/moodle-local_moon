<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Font;
use local_moon\library\Blocks\CourseHandler;
class MoonElementBlog_Recent extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'blog_recent',
            'title' => 'Blog Recent',
            'description' => 'get latest blog',
            'icon' => 'as-icon as-icon-list4',
            'category' => 'Blog',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('grid_options',  [
            "type" => "group",
            "label" => "grid_options",
        ]);
        $this->addField('item_options',  [
            "type" => "group",
            "label" => "item_options",
        ]);
        $this->addField('title_options',  [
            "type" => "group",
            "label" => "title_options",
        ]);
        $this->addField('meta_options',  [
            "type" => "group",
            "label" => "meta_options",
        ]);
        $this->addField('content_options',  [
            "type" => "group",
            "label" => "content_options",
        ]);
        $this->addField('image_options',  [
            "type" => "group",
            "label" => "image_options",
        ]);

        $this->addField('slider_options',  [
            "type" => "group",
            "label" => "slider_options",
        ]);
        $this->addField('blog_style', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "style",
            "default"    => "style1",
            "options"    => [
                "style1" => "style1",
                "style2" => "style2",
                "style3" => "style3",
            ],
        ]);
        $this->addField('blog_since', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "blog_recent_time",
            "options"    => [
                7200 => get_string('numhours', '', 2),
                14400 => get_string('numhours', '', 4),
                21600 => get_string('numhours', '', 6),
                43200 => get_string('numhours', '', 12),
                86400 => get_string('numhours', '', 24),
                172800 => get_string('numdays', '', 2),
                604800 => get_string('numdays', '', 7),
                1209600 => get_string('numdays', '', 14),
                2592000 => get_string('numdays', '', 30),
                5184000 => get_string('numdays', '', 60),
                31104000 => '1_year',
                155520000 => '5_years'
            ],
        ]);

        $this->addField('blog_limit', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "limit",
            "dynamic"     => true,
        ]);
        $this->addField('title_font_style', [
            "group"      => "title_options",
            "type"       => "typography",
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
                'value' => Font::$get_default_font_value
            ],
        ]);
        $this->addField('title_margin', [
            "group" => "title_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->addField('show_content', [
            'group'   => 'content_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'show_content',
        ]);
        $this->addField('content_font_style', [
            "group"      => "content_options",
            "type"       => "typography",
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
                'value' => Font::$get_default_font_value
            ],
        ]);
        $this->addField('content_margin', [
            "group" => "content_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->addField('meta_font_style', [
            "group"      => "meta_options",
            "type"       => "typography",
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
                'value' => Font::$get_default_font_value
            ],
        ]);
        $this->addField('show_author', [
            'group'   => 'meta_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'show_author',
        ]);
        $this->addField('show_comment', [
            'group'   => 'meta_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'show_comment',
        ]);

        $this->addField('meta_margin', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->addField('image_position', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "image_position",
            "default" => "",
            "options" => [
                ""    => "top",
                "left"   => "left",
            ],
        ]);
        $this->addField('media_column_responsive', [
            "group"   => "image_options",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "lg",
            "conditions" => "[image_position]=='left'",
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
        $this->addField('xxl_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "xxl_column_media_width",
            "default"    => "",
            "conditions" => "[media_column_responsive]=='xxl' AND [image_position]=='left'",
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

        $this->addField('xl_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "xl_column_media_width",
            "default"    => "",
            "conditions" => "[media_column_responsive]=='xl' AND [image_position]=='left'",
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

        $this->addField('lg_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "lg_column_media_width",
            "default"    => "4",
            "conditions" => "[media_column_responsive]=='lg' AND [image_position]=='left'",
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

        $this->addField('md_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "md_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='md' AND [image_position]=='left'",
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

        $this->addField('sm_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "sm_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='sm' AND [image_position]=='left'",
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

        $this->addField('xs_column_media', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "xs_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='xs' AND [image_position]=='left'",
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

        $this->addField('vertical_middle', [
            "group"      => "image_options",
            "type"       => "radio",
            "default"    => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"      => "vertical_middle",
            "conditions" => "[image_position]=='left'",
        ]);
        $this->addField('image_border_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'label' => 'border_radius',
        ]);
        $this->addField('image_min_height', [
            'group'      => 'image_options',
            'type'       => 'range',
            'label'      => 'min_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 1000,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 300,
        ]);
        $this->addField('image_margin', [
            'group'      => 'image_options',
            'type'       => 'spacing',
            'label'      => 'margin',
        ]);
        $this->addField('item_bg_color', [
            "group"      => "item_options",
            "type"       => "color",
            "label"      => "background_color",
        ]);
        $this->addField('item_border', [
            "group"      => "item_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->addField('item_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'padding',
        ]);
        $this->addField('item_border_radius', [
            'group' => 'item_options',
            'type'  => 'spacing',
            'label' => 'border_radius',
        ]);
        $this->addField('item_content_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'content_padding',
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
            "conditions" => "[blog_style]!='style2'",
        ]);

        $this->addField('xxl_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xxl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='xl' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='lg' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='md' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='sm' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='xs' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='xxl' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='xl' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='lg' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='md' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='sm' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='xs' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='xxl' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='xl' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='lg' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='md' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='sm' AND [blog_style]!='style2'",
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
            "conditions" => "[column_responsive]=='xs' AND [blog_style]!='style2'",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);
        $this->addField('enable_slider', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '1',
            'label'   => 'enable_slider',
            "conditions" => "[blog_style]!='style2'",
        ]);
        $this->addField('autoplay', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'autoplay',
            "conditions" => "[blog_style]!='style2' AND [enable_slider]==1",
        ]);

        $this->addField('navigation', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Navigation',
            "conditions" => "[blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->addField('navigation_color', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->addField('navigation_bg_color', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->addField('navigation_color_hover', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->addField('navigation_bg_color_hover', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "background_hover_color",
            "conditions" => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->addField('navigation_padding', [
            'group'      => 'slider_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[navigation]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);

        $this->addField('dot', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => 1,
            'label'   => 'Dotnav',
            "conditions" => "[blog_style]!='style2' AND [enable_slider]==1",
        ]);
        $this->addField('dot_margin', [
            "group" => "slider_options",
            "type"  => "spacing",
            "label" => "margin",
            'conditions' => "[dot]==1 AND [blog_style]!='style2' AND [enable_slider]==1",
        ]);

    }
}