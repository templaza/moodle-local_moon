<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Font;
class MoonElementCircletext extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'circletext',
            'title' => 'Circle Text',
            'description' => 'Circle Text',
            'icon' => 'fa-solid fa-circle-h',
            'category' => 'typography',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

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

        $this->addField('title_bg_color', [
            "group"      => "general",
            "type"       => "color",
            "label"      => "background_color",
        ]);

        $this->addField('title_border', [
            "group"      => "general",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->addField('title_radius', [
            'group' => 'general',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);
        $this->addField('title_padding', [
            'group' => 'general',
            'type'  => 'spacing',
            'label' => 'padding',
        ]);
        $this->addField('title_margin', [
            'group' => 'general',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->addField('title_width', [
            'group'      => 'general',
            'type'       => 'range',
            'label'      => 'width',
            "attributes" => [
                'min'        => 1,
                'max'        => 1000,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 150,
        ]);
        $this->addField('title_icon', [
            "type"       => "icons",
            'group' => 'general',
            "label"      => "icon",
        ]);
        $this->addField('title_icon_size', [
            'group'      => 'general',
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
        $this->addField('title_icon_color', [
            "group"      => "general",
            "type"       => "color",
            "label"      => "color",
        ]);
        $this->addField('hover_rotate', [
            'group'      => 'general',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'hover_rotate',
            'attributes' => ["role" => "switch"],
        ]);
        $this->addField('auto_rotate', [
            'group'      => 'general',
            'type'       => 'radio',
            'default'    => '0',
            'label'      => 'auto_rotate',
            'attributes' => ["role" => "switch"],
        ]);
        $this->addField('title_pos', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "position",
            "default"    => "",
            "options"    => [
                'uk-position-relative'   => 'Relative',
                'uk-position-absolute'   => 'Absolute',
            ],
        ]);
        $this->addField('box_position', [
            'group'   => 'general',
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
            "conditions" => "[title_pos]=='uk-position-absolute'",
        ]);
    }
}