<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Font;
class MoonElementAccordion extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'accordion',
            'title' => 'Accordion',
            'description' => 'Accordion Widget of Moodle',
            'icon' => 'as-icon as-icon-menu3',
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

        $this->addField('title_options', [
            'type'  => 'group',
            'label' => 'title_options',
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
                        'type'        => 'text',
                        'label'       => 'title',
                    ],
                    'content' => [
                        'type'        => 'editor',
                        'label'       => 'content',
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('accordions',  [
            "group" => "general",
            "type" => "subform",
            "label" => "accordion_items",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);

        $this->addField('style', [
            'group'   => 'widget_styles',
            'type'    => 'list',
            'label'   => 'style',
            'default' => '',
            'options' => [
                ''               => 'Default',
                'accordion-flush' => 'Flush',
            ],
        ]);

        $this->addField('collapse', [
            'group'   => 'widget_styles',
            'type'    => 'list',
            'label'   => 'collapse',
            'default' => '',
            'options' => [
                ''          => 'open_first_item',
                'close-all' => 'close_all',
            ],
        ]);

        $this->addField('always_open', [
            'group'      => 'widget_styles',
            'type'       => 'radio',
            'label'      => 'always_open',
            'default'    => 0,
            'attributes' => ['role' => 'switch'],
        ]);

        $this->addField('color_settings', [
            'group'   => 'widget_styles',
            'type'    => 'radio',
            'attributes' => ['width'   => 'full',],
            'label'   => 'color_settings',
            'default' => 'color',
            'options' => [
                'color'  => 'color',
                'hover'  => 'color_hover',
                'active' => 'color_active',
            ],
        ]);

        $this->addField('color', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'color',
            'conditions' => "[color_settings]=='color'",
        ]);

        $this->addField('color_hover', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'color',
            'conditions' => "[color_settings]=='hover'",
        ]);

        $this->addField('color_active', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'color',
            'conditions' => "[color_settings]=='active'",
        ]);

        $this->addField('bgcolor', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'background_color',
            'conditions' => "[color_settings]=='color'",
        ]);

        $this->addField('bgcolor_hover', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'background_color',
            'conditions' => "[color_settings]=='hover'",
        ]);

        $this->addField('bgcolor_active', [
            'group'      => 'widget_styles',
            'type'       => 'color',
            'label'      => 'background_color',
            'conditions' => "[color_settings]=='active'",
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

        $this->addField('content_font_style', [
            'group'   => 'content_options',
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
    }
}