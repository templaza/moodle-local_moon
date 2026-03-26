<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Font;
class MoonElementCourse_Popular extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'course_popular',
            'title' => 'Course Popular',
            'description' => 'Course Popular',
            'icon' => 'fa-solid fa-sliders',
            'category' => 'Course',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('title_options',  [
            "type" => "group",
            "label" => "title_options",
        ]);
        $this->addField('item_options',  [
            "type" => "group",
            "label" => "item_options",
        ]);
        $this->addField('image_options',  [
            "type" => "group",
            "label" => "image_options",
        ]);
        $this->addField('slider_options',  [
            "type" => "group",
            "label" => "slider_options",
        ]);
        $this->addField('course_style', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "style",
            "default"    => "style1",
            "options"    => [
                "style1" => "style1",
                "style2" => "style2",
            ],
        ]);
        $this->addField('course_limit', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "course_limit",
            "dynamic"     => true,
        ]);
        $this->addField('title', [
            "group"       => "title_options",
            "type"        => "text",
            "label"       => "title",
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
        $this->addField('item_border_radius', [
            'group' => 'item_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);

        $this->addField('item_card_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'card_padding',
        ]);

        $this->addField('content_padding', [
            'group'      => 'item_options',
            'type'       => 'spacing',
            'label'      => 'content_padding',
        ]);
        $this->addField('image_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'label' => 'radius',
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
        $this->addField('item_padding', [
            'group'      => 'slider_options',
            'type'       => 'spacing',
            'label'      => 'item_padding',
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