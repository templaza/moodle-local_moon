<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementUk_Slider extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'uk_slider',
            'title' => 'Uk Slider',
            'description' => 'Uk Slider Widget of Moodle',
            'icon' => 'as-icon as-icon-dice',
            'category' => 'media,utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('slideshow_options', [
            'type'  => 'group',
            'label' => 'slideshow',
        ]);

        $this->addField('overlay_options', [
            'type'  => 'group',
            'label' => 'overlay',
        ]);

        $this->addField('title_options', [
            'type'  => 'group',
            'label' => 'title',
        ]);
        $this->addField('image_options', [
            'type'  => 'group',
            'label' => 'Image',
        ]);

        $this->addField('meta_options', [
            'type'  => 'group',
            'label' => 'meta',
        ]);

        $this->addField('content_options', [
            'type'  => 'group',
            'label' => 'content',
        ]);

        $this->addField('readmore_options', [
            'type'  => 'group',
            'label' => 'readmore',
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

        $this->addField('slider_height', [
            'group'      => 'slideshow_options',
            'type'    => 'list',
            'label'      => 'height',
            "description" => "uk_slider_height",
            'options'         => array(
                '' => 'Auto',
                'full' => 'Viewport',
                'percent' => 'Viewport (Minus 20%)',
                'section' => 'Viewport (Minus the following section)',
            ),
            'default'    => '',
        ]);

        $this->addField('min_height', [
            'group'   => 'slideshow_options',
            'type'    => 'range',
            'label'      => 'min_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 1200,
                'step'       => 1,
                'responsive' => false,
                'postfix' => 'px',
            ],
            'default' => 600,
        ]);

        $this->addField('max_height', [
            'group'   => 'slideshow_options',
            'type'    => 'range',
            'label'      => 'max_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => false,
                'postfix' => 'px',
            ],
            'default' => 800,
        ]);

        $this->addField('slideshow_transition', [
            'group'   => 'slideshow_options',
            'type'    => 'list',
            'name'    => 'effect_type',
            'label'   => 'effect_type',
            'default' => 'fade',
            'options' => [
                'slide'      => 'Slide',
                'fade'       => 'Fade',
                'scale'      => 'Scale',
                'pull'       => 'Pull',
                'push'       => 'Push',
            ],
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

        $this->addField('title_html_element', [
            'group'   => 'title_options',
            'type'    => 'list',
            'label'   => 'html_element',
            'default' => 'h3',
            'options' => [
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6',
                'div'=> 'div',
            ],
        ]);

        $this->addField('title_font_style', [
            'group'   => 'title_options',
            'type'    => 'typography',
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

        $this->addField('meta_heading_margin', [
            'group' => 'meta_options',
            'type'  => 'spacing',
            'name'  => 'meta_heading_margin',
            'label' => 'margin',
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

        $this->addField('button_style', [
            'group'   => 'readmore_options',
            'type'    => 'list',
            'name'    => 'button_style',
            'label'   => 'style',
            'default' => 'primary',
            'options' => [
                'primary'   => 'Primary',
                'secondary' => 'Secondary',
                'success'   => 'Success',
                'danger'    => 'Danger',
                'warning'   => 'Warning',
                'info'      => 'Info',
                'light'     => 'Light',
                'dark'      => 'Dark',
                'link'      => 'Link',
            ],
        ]);

        $this->addField('button_outline', [
            'group'   => 'readmore_options',
            'type'    => 'radio',
            'name'    => 'button_outline',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'button_outline',
        ]);

        $this->addField('button_size', [
            'group'   => 'readmore_options',
            'type'    => 'list',
            'name'    => 'button_size',
            'label'   => 'style',
            'default' => '',
            'options' => [
                ''       => 'Default',
                'btn-lg' => 'Large',
                'btn-sm' => 'Small',
            ],
        ]);

        $this->addField('btn_border_radius', [
            'group'   => 'readmore_options',
            'type'    => 'list',
            'name'    => 'btn_border_radius',
            'label'   => 'border_radius',
            'default' => '',
            'options' => [
                ''             => 'Rounded',
                'rounded-0'    => 'Square',
                'rounded-pill' => 'Circle',
            ],
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

    }
}