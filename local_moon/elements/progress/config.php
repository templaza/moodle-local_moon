<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementProgress extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'progress',
            'title' => 'Progress',
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
                    'percent' => [
                        'type'    => 'range',
                        'class'   => 'form-control',
                        'label'   => 'percent',
                        "attributes" => [
                            'min'        => 1,
                            'max'        => 100,
                            'step'       => 1,
                            'responsive' => false,
                            'postfix' => '%',
                        ],
                        'default' => 50,
                    ],
                    'color' => [
                        'type'   => 'color',
                        'label'  => 'color',
                        'conditions' => "[title] !=''",
                    ],

                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('progress',  [
            "group" => "general",
            "type" => "subform",
            "label" => "items",
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
        $this->addField('title_margin', [
            'group' => 'title_styles',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
    }
}