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
            'icon' => 'fa-solid fa-book-open',
            'category' => 'Blog',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('title_options',  [
            "type" => "group",
            "label" => "title_options",
        ]);
        $this->addField('meta_options',  [
            "type" => "group",
            "label" => "meta_options",
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
                5184000 => get_string('numdays', '', 60)
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
        $this->addField('meta_margin', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->addField('autoplay', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'autoplay',
        ]);
        $this->addField('slider_column', [
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "slider_column",
            "default"    => "col-lg-4",
            "options"    => [
                "col-lg-12" => "1 column",
                "col-lg-6" => "2 columns",
                "col-lg-4" => "3 columns",
                "col-lg-3" => "4 columns",
                "col-lg-2" => "6 columns",
            ],
        ]);

        $this->addField('navigation', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Navigation',
        ]);
        $this->addField('navigation_color', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[navigation]==1",
        ]);
        $this->addField('navigation_bg_color', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[navigation]==1",
        ]);
        $this->addField('navigation_color_hover', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[navigation]==1",
        ]);
        $this->addField('navigation_bg_color_hover', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "background_hover_color",
            "conditions" => "[navigation]==1",
        ]);
        $this->addField('navigation_padding', [
            'group'      => 'slider_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[navigation]==1",
        ]);

        $this->addField('dot', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => 1,
            'label'   => 'Dotnav',
        ]);
        $this->addField('dot_margin', [
            "group" => "slider_options",
            "type"  => "spacing",
            "label" => "margin",
            'conditions' => "[dot]==1",
        ]);

    }
}