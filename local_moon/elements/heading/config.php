<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Font;
class MoonElementHeading extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'heading',
            'title' => 'Heading',
            'description' => 'Heading Widget of Moodle',
            'icon' => 'fa-solid fa-heading',
            'category' => 'typography',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('meta_options',  [
            "type" => "group",
            "label" => "meta_options",
        ]);

        $this->addField('title', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "title",
            "dynamic"     => true,
        ]);

        $this->addField('use_link', [
            "group"       => "general",
            "type"        => "radio",
            "label"       => "use_link",
            "description" => "use_link_desc",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => 0,
        ]);

        $this->addField('link', [
            "group"      => "general",
            "type"       => "text",
            "label"      => "link_url",
            "description"=> "link_url_desc",
            "name"       => "link",
            "hint"       => "https://astroidframe.work/",
            "conditions" => "[use_link]==1",
        ]);

        $this->addField('add_icon', [
            "group"       => "general",
            "type"        => "radio",
            "label"       => "add_icon",
            "description" => "add_icon_desc",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => 0,
        ]);

        $this->addField('icon', [
            "group"      => "general",
            "type"       => "icons",
            "label"      => "icon",
            "default"    => "fa-solid fa-heading",
            "conditions" => "[add_icon]==1",
        ]);

        $this->addField('icon_color', [
            "group"      => "general",
            "type"       => "color",
            "label"      => "icon_color",
            "conditions" => "[add_icon]==1",
        ]);

        $this->addField('html_element', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "html_element",
            "default"    => "h2",
            "conditions" => "[heading]!==''",
            "options"    => [
                "h1" => "h1",
                "h2" => "h2",
                "h3" => "h3",
                "h4" => "h4",
                "h5" => "h5",
                "h6" => "h6",
                "div" => "div",
            ],
        ]);

        $this->addField('font_style', [
            "group"      => "general",
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

        $this->addField('title_heading_margin', [
            "group" => "general",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->addField('meta_text', [
            "group"       => "meta_options",
            "type"        => "text",
            "label"       => "meta",
            "dynamic"     => true,
        ]);

        $this->addField('meta_font_style', [
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
                    'system_fonts' => Font::get_system_fonts(),
                    'text_transform_options' => Font::text_transform(),
                    'lang' => Font::font_properties(),
                ],
                'lang' => Font::font_properties(),
                'value' => Font::$get_default_font_value,
            ],
        ]);

        $this->addField('meta_heading_margin', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->addField('meta_border', [
            "group"      => "meta_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->addField('meta_radius', [
            'group' => 'meta_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
        ]);

        $this->addField('meta_position', [
            "group"   => "meta_options",
            "type"    => "list",
            "label"   => "meta_position",
            "default" => "before",
            "options" => [
                "before" => "before_title",
                "after"  => "after_title",
            ],
        ]);
        $this->addField('title_clone', [
            "group"       => "general",
            "type"        => "radio",
            "label"       => "title_clone",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => 0,
        ]);
        $this->addField('title_clone_txt', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "title_custom_clone",
            "dynamic"     => true,
            "conditions" => "[title_clone]==1",
        ]);
        $this->addField('title_clone_margin', [
            "group" => "general",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->addField('title_clone_font_style', [
            "group"   => "general",
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