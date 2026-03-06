<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementRuntext extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'runtext',
            'title' => 'Run Text',
            'description' => 'Run Text Widget of Moodle',
            'icon' => 'as-icon as-icon-toggle-on',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('widget_styles', [
            'type'  => 'group',
            'label' => 'widget_styles',
        ]);
        $this->addField('title_styles', [
            'type'  => 'group',
            'label' => 'title_options',
        ]);
        $this->addField('icon_styles', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'title' => [
                        'type'    => 'text',
                        'class'   => 'form-control',
                        'label'   => 'title',
                        'dynamic' => true,
                    ],
                    'title_color' => [
                        'type'   => 'color',
                        'label'  => 'color',
                        'conditions' => "[title] !=''",
                    ],
                    'icon' => [
                        'type'    => 'icons',
                        'label'   => 'icon',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'dynamic' => true,
                    ],
                    'icon_color' => [
                        'type'   => 'color',
                        'label'  => 'color',
                        'conditions' => "[icon] !=''",
                    ],

                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('texts',  [
            "group" => "general",
            "type" => "subform",
            "label" => "buttons",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);
        $this->addField('item_margin', [
            'group' => 'general',
            'type'  => 'spacing',
            'label' => 'item_margin',
        ]);

        $this->addField('text_font_style', [
            "group"      => "title_styles",
            "label"      => "font_style",
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
                'value' => Font::$get_default_font_value,
            ],
        ]);
        $this->addField('text_stroke', [
            'group'   => 'title_styles',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Text stroke',
        ]);
        $this->addField('text_stroke_color', [
            "group"      => "title_styles",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[text_stroke]==1",
        ]);
        $this->addField('text_stroke_width', [
            'group'   => 'title_styles',
            'type'    => 'range',
            'label'      => 'stroke width',
            "attributes" => [
                'min'        => 1,
                'max'        => 100,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px',
            ],
            'default' => 1,
            "conditions" => "[text_stroke]==1",
        ]);

        $this->addField('title_icon_size', [
            'group'      => 'icon_styles',
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
        $this->addField('icon_margin', [
            'group' => 'icon_styles',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

    }
}