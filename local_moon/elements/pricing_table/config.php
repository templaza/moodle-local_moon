<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementPricing_Table extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'pricing_table',
            'title' => 'Pricing Table',
            'description' => 'Pricing Widget of Moodle',
            'icon' => 'as-icon as-icon-tablet',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('title_options', [
            'type'  => 'group',
            'label' => 'title',
        ]);

        $this->addField('meta_options', [
            'type'  => 'group',
            'label' => 'meta_options',
        ]);

        $this->addField('pricing_options', [
            'type'  => 'group',
            'label' => 'pricing_options',
        ]);

        $this->addField('symbol_options', [
            'type'  => 'group',
            'label' => 'symbol_options',
        ]);

        $this->addField('description_options', [
            'type'  => 'group',
            'label' => 'description_options',
        ]);

        $this->addField('listing_options', [
            'type'  => 'group',
            'label' => 'listing_options',
        ]);

        $this->addField('button_options', [
            'type'  => 'group',
            'label' => 'button_options',
        ]);

        $this->addField('title', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'title',
        ]);

        $this->addField('meta', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'meta',
        ]);
        $this->addField('description', [
            "group"   => "general",
            "type"    => "editor",
            "label"   => "content",
            "dynamic" => true,
        ]);
        $this->addField('price', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'price',
        ]);
        $this->addField('price_symbol', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'price_symbol',
        ]);
        $this->addField('label_text', [
            'group' => 'general',
            'type'  => 'text',
            'label' => 'highlight',
        ]);


        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'item_title' => [
                        'type'    => 'text',
                        'label'   => 'title',
                        'dynamic' => true,
                    ],
                    'item_title_color' => [
                        'type'    => 'color',
                        'label'   => 'title_color',
                        'dynamic' => true,
                    ],
                    'item_icon' => [
                        'type'    => 'icons',
                        'label'   => 'icon',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'dynamic' => true,
                    ],
                    'item_icon_color' => [
                        'type'    => 'color',
                        'label'   => 'icon_color',
                        'dynamic' => true,
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('pricing_items',  [
            "group" => "general",
            "type" => "subform",
            "label" => "Pricing Items",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);

        $this->addField('button_url', [
            "group"      => "general",
            'type'    => 'text',
            'label'   => 'link_url',
            "attributes" => [
                'hint'    => 'https://moonframe.work',
                'dynamic' => true,
            ],
        ]);

        $this->addField('button_text', [
            "group"      => "general",
            'type'       => 'text',
            'label'      => 'link_text',
            "attributes" => [
                'hint'       => 'View More',
                'dynamic' => true,
            ],
            'conditions' => "[button_url]!==''",
        ]);

        $this->addField('button_target', [
            "group"      => "general",
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
        ]);

        $this->addField('meta_alignment', [
            "group"      => "meta_options",
            "type"       => "list",
            "label"      => "meta_alignment",
            "default"    => "",
            "options"    => [
                'top' => 'Above',
                '' => 'Below',
                'inline' => 'Inline',
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
        $this->addField('title_heading_padding', [
            'group' => 'title_options',
            'type'  => 'spacing',
            'name'  => 'title_heading_padding',
            'label' => 'padding',
        ]);
        $this->addField('title_border', [
            "group"      => "title_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->addField('title_radius', [
            'group' => 'title_options',
            'type'  => 'spacing',
            'name'  => 'title_radius',
            'label' => 'radius',
        ]);


        $this->addField('pricing_font_style', [
            'group'   => 'pricing_options',
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
        $this->addField('price_color', [
            'group' => 'pricing_options',
            'type'  => 'color',
            'label' => 'color',
        ]);
        $this->addField('price_icon', [
            'group' => 'pricing_options',
            'type'  => 'icons',
            "attributes" => [
                'source' => 'fontawesome',
            ],
            'label' => 'icon',
        ]);
        $this->addField('price_icon_size', [
            'group'      => 'pricing_options',
            'type'       => 'range',
            'label'      => 'icon_size',
            "attributes" => [
                'min'        => 1,
                'max'        => 1200,
                'step'       => 1,
                'responsive' => true,
                'postfix'    => 'px',
            ],
            'default'    => 30,
        ]);
        $this->addField('price_icon_color', [
            'group' => 'pricing_options',
            'type'  => 'color',
            'label' => 'icon_color',
        ]);
        $this->addField('price_margin', [
            'group' => 'pricing_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);

        $this->addField('symbol_font_style', [
            'group'   => 'symbol_options',
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

        $this->addField('symbol_pos', [
            "group"      => "symbol_options",
            "type"       => "list",
            "label"      => "position",
            "default"    => "",
            "options"    => [
                ''        => 'Default',
                'right'   => 'right',
            ],
        ]);
        $this->addField('symbol_margin', [
            'group' => 'symbol_options',
            'type'  => 'spacing',
            'name'  => 'symbol_margin',
            'label' => 'margin',
        ]);

        $this->addField('description_font_style', [
            'group'   => 'description_options',
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

        $this->addField('listing_border', [
            "group"      => "listing_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->addField('listing_margin', [
            'group' => 'listing_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->addField('listing_padding', [
            'group' => 'listing_options',
            'type'  => 'spacing',
            'label' => 'padding',
        ]);

        $this->addField('button_font_style', [
            'group'   => 'button_options',
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
        $this->addField('button_margin', [
            'group' => 'button_options',
            'type'  => 'spacing',
            'label' => 'margin',
        ]);
        $this->addField('button_padding', [
            'group' => 'button_options',
            'type'  => 'spacing',
            'label' => 'padding',
        ]);
        $this->addField('button_border', [
            "group"      => "button_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->addField('button_radius', [
            'group' => 'button_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);
        $this->addField('button_bg_color', [
            'group' => 'button_options',
            'type'  => 'color',
            'label' => 'background_color',
        ]);
        $this->addField('button_color_hover', [
            'group' => 'button_options',
            'type'  => 'color',
            'label' => 'color_hover',
        ]);
        $this->addField('button_bg_color_hover', [
            'group' => 'button_options',
            'type'  => 'color',
            'label' => 'background_color_hover',
        ]);



    }
}