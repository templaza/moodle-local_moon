<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementList extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'list',
            'title' => 'List',
            'description' => 'List Widget of Moodle',
            'icon' => 'as-icon as-icon-list2',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('misc_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "misc_options",
        ]);

        $this->addField('title_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "title_options",
        ]);
        $this->addField('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);

        $this->addField('content_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "content_options",
        ]);

        $this->addField('spacing_options', [
            "group" => "general",
            "type"  => "group",
            "label" => "spacing_options",
        ]);

        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'title' => [
                        "type"    => "text",
                        "label"   => "title",
                        "class"   => "form-control",
                        "dynamic" => true,
                    ],
                    'description' => [
                        "type"    => "editor",
                        "label"   => "description",
                        "dynamic" => true,
                    ],
                    'icon_type' => [
                        "type"    => "list",
                        "label"   => "icon_type",
                        "default" => "fontawesome",
                        "options" => [
                            "fontawesome" => "fontawesome",
                            "custom"      => "custom",
                        ],
                    ],
                    'fa_icon' => [
                        "type"       => "icons",
                        "label"      => "fa_icon",
                        "conditions" => "[icon_type]=='fontawesome'",
                    ],
                    'custom_icon' => [
                        "type"       => "text",
                        "label"      => "custom_icon",
                        "dynamic"    => true,
                        "conditions" => "[icon_type]=='custom'",
                    ],
                    'icon_color_item' => [
                        "type"       => "color",
                        "label"      => "color",
                    ],
                    'icon_bg_item' => [
                        "type"       => "color",
                        "label"      => "background_color",
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);

        $this->addField('list_items', [
            "group" => "general",
            "type"  => "subform",
            "label" => "list_items",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);
        $this->addField('title_heading', [
            "group" => "general",
            "type"  => "text",
            "label"  => "heading",
        ]);
        $this->addField('heading_font_style', [
            "group"      => "general",
            "type"       => "typography",
            "label"       => "font_style",
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
            "conditions" => "[title_heading] !=''",
        ]);
        $this->addField('heading_margin', [
            "group" => "general",
            "type"  => "spacing",
            "label"  => "heading_margin",
            "conditions" => "[title_heading] !=''",
        ]);

        $this->addField('list_style', [
            "group"   => "misc_options",
            "type"    => "list",
            "label"   => "list_style",
            "default" => "ul",
            "options" => [
                "ul"                      => "Unordered List",
                "ol"                      => "Ordered List",
                "list-unstyled"           => "Unstyled List",
                "list-inline"             => "Inline",
                "list-description"        => "Description List",
                "list-group"              => "List Group",
                "list-group-flush"        => "List Group Flush",
                "list-group-numbered"     => "List Group Numbered",
                "custom"     => "custom",
            ],
        ]);

        $this->addField('vertical_align', [
            "group"   => "misc_options",
            "type"    => "list",
            "label"   => "vertical_alignment",
            "default" => "uk-flex-top",
            "options" => [
                "uk-flex-top"             => "top",
                "uk-flex-middle"          => "middle",
                "uk-flex-bottom"          => "bottom",
            ],
            "conditions" => "[list_style]=='custom'",
        ]);

        $this->addField('title_width', [
            "group"      => "misc_options",
            "type"       => "range",
            "label"       => "title_width",
            "min"        => 1,
            "max"        => 12,
            "step"       => 1,
            "default"    => 3,
            "postfix"    => "cols",
            "conditions" => "[list_style]=='list-description'",
        ]);

        $this->addField('title_html_element', [
            "group"   => "title_options",
            "type"    => "list",
            "label"    => "title_html_element",
            "default" => "h6",
            "options" => [
                "h1" => "h1",
                "h2" => "h2",
                "h3" => "h3",
                "h4" => "h4",
                "h5" => "h5",
                "h6" => "h6",
                "div"=> "div",
            ],
        ]);

        $this->addField('title_font_style', [
            "group"      => "title_options",
            "type"       => "typography",
            "label"       => "title_font_style",
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

        $this->addField('title_heading_margin', [
            "group" => "title_options",
            "type"  => "spacing",
            "label"  => "title_heading_margin",
        ]);
        $this->addField('icon_color', [
            'group' => 'icon_options',
            'type'  => 'color',
            'label' => 'icon_color',
        ]);
        $this->addField('icon_bg_color', [
            'group' => 'icon_options',
            'type'  => 'color',
            'label' => 'background_color',
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
        $this->addField('icon_margin', [
            "group" => "icon_options",
            "type"  => "spacing",
            "label"  => "icon_margin",
        ]);
        $this->addField('icon_padding', [
            "group" => "icon_options",
            "type"  => "spacing",
            "label"  => "icon_padding",
        ]);
        $this->addField('icon_width', [
            'group'   => 'icon_options',
            'type'    => 'range',
            'label'      => 'width',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->addField('icon_height', [
            'group'   => 'icon_options',
            'type'    => 'range',
            'label'      => 'height',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->addField('icon_border', [
            "group"      => "icon_options",
            "type"       => "border",
            "label"      => "border",
        ]);
        $this->addField('icon_radius', [
            'group' => 'icon_options',
            'type'  => 'spacing',
            'label' => 'radius',
        ]);

        $this->addField('content_font_style', [
            "group"      => "content_options",
            "type"       => "typography",
            "label"       => "content_font_style",
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

        $this->addField('item_margin', [
            "group" => "spacing_options",
            "type"  => "spacing",
            "label"  => "margin",
        ]);

        $this->addField('item_padding', [
            "group" => "spacing_options",
            "type"  => "spacing",
            "label"  => "padding",
        ]);
    }
}