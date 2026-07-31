<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementIcons extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'icons',
            'title' => 'Icons',
            'description' => 'Icon Widget of Moodle',
            'icon' => 'as-icon as-icon-3d-rotate',
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

        $this->addField('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'icon' => [
                        'type'    => 'icons',
                        'label'   => 'icon',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'dynamic' => true,
                    ],
                    'title' => [
                        'type'    => 'text',
                        'class'   => 'form-control',
                        'label'   => 'title',
                        'dynamic' => true,
                    ],
                    'link' => [
                        'type'        => 'text',
                        'label'       => 'link_url',
                        'description' => 'link_url_desc',
                        'name'        => 'link',
                        "attributes" => [
                            'hint'        => 'https://astroidframe.work/',
                        ],
                        'dynamic'     => true,
                    ],
                    'link_target' => [
                        'conditions'  => "[link]!=''",
                        'type'    => 'list',
                        'label'   => 'link_target',
                        'default' => '',
                        'options' => [
                            ''        => 'Default',
                            '_blank'  => 'New Window',
                            '_parent' => 'Parent Frame',
                            '_top'    => 'Full body of the window',
                        ],
                    ],
                    'color_settings' => [
                        'conditions'  => "[button_style]=='custom'",
                        'type'    => 'radio',
                        "attributes" => [
                            'width'   => 'full',
                        ],
                        'default' => 'color',
                        'options' => [
                            'color' => 'color',
                            'hover' => 'color_hover',
                        ],
                    ],
                    'color' => [
                        'type'   => 'color',
                        'label'  => 'color',
                    ],
                    'color_hover' => [
                        'type'   => 'color',
                        'label'  => 'color_hover',
                    ],
                    'bgcolor' => [
                        'type'   => 'color',
                        'label'  => 'background_color',
                    ],
                    'bgcolor_hover' => [
                        'type'   => 'color',
                        'label'  => 'background_color_hover',
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('icons',  [
            "group" => "general",
            "type" => "subform",
            "label" => "icons",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);

        $this->addField('icon_size', [
            'group'      => 'icon_options',
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
        $this->addField('icons_color', [
            'group' => 'icon_options',
            'type'  => 'color',
            'label' => 'color',
        ]);
        $this->addField('icon_width', [
            'group'      => 'icon_options',
            'type'       => 'range',
            'label'      => 'width',
            "attributes" => [
                'min'        => 1,
                'max'        => 300,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 50,
        ]);
        $this->addField('icon_height', [
            'group'      => 'icon_options',
            'type'       => 'range',
            'label'      => 'height',
            "attributes" => [
                'min'        => 1,
                'max'        => 300,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 50,
        ]);
        $this->addField('icon_radius', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'label' => 'border_radius',
        ]);
        $this->addField('icon_border', [
            "group"      => "icon_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->addField('icon_border_hover', [
            "group"      => "icon_options",
            "type"       => "border",
            "label"      => "border_hover",
        ]);

        $this->addField('icon_padding', [
            "group"      => "icon_options",
            "type"       => "spacing",
            "label"      => "padding",
        ]);
        $this->addField('icon_margin', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
    }
}