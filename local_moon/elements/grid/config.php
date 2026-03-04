<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementGrid extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'grid',
            'title' => 'Grid',
            'description' => 'Grid Widget of Moodle',
            'icon' => 'as-icon as-icon-profile',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('grid_options', [
            'type'  => 'group',
            'label' => 'grid_options',
        ]);

        $this->addField('card_options', [
            'type'  => 'group',
            'label' => 'card_options',
        ]);

        $this->addField('icon_options', [
            'type'  => 'group',
            'label' => 'icon_options',
        ]);

        $this->addField('image_options', [
            'type'  => 'group',
            'label' => 'image_options',
        ]);

        $this->addField('title_options', [
            'type'  => 'group',
            'label' => 'title_options',
        ]);

        $this->addField('meta_options', [
            'type'  => 'group',
            'label' => 'meta_options',
        ]);

        $this->addField('content_options', [
            'type'  => 'group',
            'label' => 'content_options',
        ]);

        $this->addField('readmore_options', [
            'type'  => 'group',
            'label' => 'readmore_options',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'type' => [
                        'type' => 'list',
                        'label' => 'media_type',
                        'default' => '',
                        'options' => [
                            ''      => 'none',
                            'icon'  => 'icon',
                            'image' => 'image',
                        ],
                    ],

                    'icon_type' => [
                        'conditions' => "[type]=='icon'",
                        'type' => 'list',
                        'label' => 'icon_type',
                        'default' => 'fontawesome',
                        'options' => [
                            'fontawesome' => 'fontawesome',
                            'astroid'     => 'astroid_icon',
                            'custom'      => 'custom',
                        ],
                    ],

                    'fa_icon' => [
                        'conditions' => "[type]=='icon' AND [icon_type]=='fontawesome'",
                        'type' => 'icons',
                        "attributes" => [
                            'source' => 'fontawesome',
                        ],
                        'label' => 'icon',
                    ],

                    'as_icon' => [
                        'conditions' => "[type]=='icon' AND [icon_type]=='astroid'",
                        'type' => 'icons',
                        "attributes" => [
                            'source' => 'astroid',
                        ],
                        'label' => 'icon',
                    ],

                    'custom_icon' => [
                        'conditions' => "[type]=='icon' AND [icon_type]=='custom'",
                        'type' => 'text',
                        'label' => 'icon_class',
                        'dynamic' => true,
                    ],

                    'image' => [
                        'conditions' => "[type]=='image'",
                        'type' => 'media',
                        'label' => 'TPL_ASTROID_SELECT_IMAGE',
                        'dynamic' => true,
                    ],

                    'title' => [
                        'type' => 'text',
                        'label' => 'title',
                        'dynamic' => true,
                    ],

                    'meta' => [
                        'type' => 'text',
                        'label' => 'meta',
                        'dynamic' => true,
                    ],

                    'description' => [
                        'type' => 'editor',
                        'label' => 'description',
                        'dynamic' => true,
                    ],

                    'link' => [
                        'type' => 'text',
                        'label' => 'link_url',
                        'hint' => 'https://astroidframe.work',
                        'dynamic' => true,
                    ],

                    'link_title' => [
                        'conditions' => "[link]!=''",
                        'type' => 'text',
                        'label' => 'link_text',
                        'hint' => 'View More',
                        'dynamic' => true,
                    ],

                    'link_target' => [
                        'conditions' => "[link]!=''",
                        'type' => 'list',
                        'label' => 'link_target',
                        'default' => '',
                        'options' => [
                            ''         => 'Default',
                            '_blank'   => 'New Window',
                            '_parent'  => 'Parent Frame',
                            '_top'     => 'Full body of the window',
                        ],
                    ],

                    'enable_background_image' => [
                        'type' => 'radio',
                        "attributes" => [
                            "role" => "switch"
                        ],
                        'default' => '0',
                        'label' => 'enable_background_image',
                    ],

                    'background_image' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'media',
                        'label' => 'background_image',
                    ],

                    'background_repeat' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'list',
                        'label' => 'background_repeat',
                        'options' => [
                            '' => 'inherit',
                            'no-repeat' => 'no_repeat',
                            'repeat-x'  => 'repeat_x',
                            'repeat-y'  => 'repeat_y',
                        ],
                    ],

                    'background_size' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'list',
                        'label' => 'background_size',
                        'options' => [
                            '' => 'inherit',
                            'cover' => 'cover',
                            'contain' => 'contain',
                        ],
                    ],

                    'background_attachment' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'list',
                        'label' => 'background_attachment',
                        'options' => [
                            '' => 'inherit',
                            'scroll' => 'scroll',
                            'fixed'  => 'fixed',
                        ],
                    ],

                    'background_position' => [
                        'conditions' => "[enable_background_image]==1",
                        'type' => 'list',
                        'label' => 'background_position',
                        'options' => [
                            '' => 'inherit',
                            "left top" => "left_top",
                            "left center" => "left_center",
                            "left bottom" => "left_bottom",
                            "right top" => "right_top",
                            "right center" => "right_center",
                            "right bottom" => "right_bottom",
                            "center top" => "center_top",
                            "center center" => "center_center",
                            "center bottom" => "center_bottom",
                        ],
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('grids',  [
            "group" => "general",
            "type" => "subform",
            "label" => "grids",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
        ]);

        $this->addField('column_responsive', [
            "group"   => "grid_options",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "lg",
            "options" => [
                'xxl' => 'xxl_icon',
                'xl'  => 'xl_icon',
                'lg'  => 'lg_icon',
                'md'  => 'md_icon',
                'sm'  => 'sm_icon',
                'xs'  => 'xs_icon',
            ],
        ]);

        $this->addField('xxl_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xxl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('xl_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xl_column",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('lg_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "lg_column",
            "default"    => "3",
            "conditions" => "[column_responsive]=='lg'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('md_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "md_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='md'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('sm_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "sm_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='sm'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('xs_column', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "xs_column",
            "default"    => "1",
            "conditions" => "[column_responsive]=='xs'",
            "options"    => [
                ""  => "inherit",
                '1' => 'one_column',
                '2' => 'two_columns',
                '3' => 'three_columns',
                '4' => 'four_columns',
                '5' => 'five_columns',
                '6' => 'six_columns',
            ],
        ]);

        $this->addField('row_gutter_xxl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xxl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('row_gutter_xl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('row_gutter_lg', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_lg",
            "default"    => "4",
            "conditions" => "[column_responsive]=='lg'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('row_gutter_md', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_md",
            "default"    => "3",
            "conditions" => "[column_responsive]=='md'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('row_gutter_sm', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_sm",
            "default"    => "3",
            "conditions" => "[column_responsive]=='sm'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('row_gutter', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "row_gutter_xs",
            "default"    => "3",
            "conditions" => "[column_responsive]=='xs'",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('column_gutter_xxl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xxl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xxl'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('column_gutter_xl', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xl",
            "default"    => "",
            "conditions" => "[column_responsive]=='xl'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('column_gutter_lg', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_lg",
            "default"    => "4",
            "conditions" => "[column_responsive]=='lg'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('column_gutter_md', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_md",
            "default"    => "3",
            "conditions" => "[column_responsive]=='md'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('column_gutter_sm', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_sm",
            "default"    => "3",
            "conditions" => "[column_responsive]=='sm'",
            "options"    => [
                ""  => "inherit",
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('column_gutter', [
            "group"      => "grid_options",
            "type"       => "list",
            "label"      => "column_gutter_xs",
            "default"    => "3",
            "conditions" => "[column_responsive]=='xs'",
            "options"    => [
                "0" => "Collapse",
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('use_masonry', [
            "group"   => "grid_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "use_masonry",
        ]);

        $this->addField('card_style', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "card_style",
            "default" => "",
            "options" => [
                ""          => "default",
                "primary"   => "Primary",
                "secondary" => "Secondary",
                "success"   => "Success",
                "danger"    => "Danger",
                "warning"   => "Warning",
                "info"      => "Info",
                "light"     => "Light",
                "dark"      => "Dark",
                "none"      => "None",
                "custom"    => "custom",
            ],
        ]);

        $this->addField('text_color', [
            "group"      => "card_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->addField('bg_color', [
            "group"      => "card_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->addField('card_border', [
            "group"      => "card_options",
            "type"       => "border",
            "label"      => "border",
            "conditions" => "[card_style]=='custom'",
        ]);

        $this->addField('card_size', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "card_size",
            "default" => "",
            "options" => [
                "none"   => "none",
                ""       => "default",
                "small"  => "small",
                "large"  => "large",
                "custom" => "custom",
            ],
        ]);

        $this->addField('card_padding', [
            "group"      => "card_options",
            "type"       => "spacing",
            "label"      => "padding",
            "conditions" => "[card_size]=='custom'",
        ]);

        $this->addField('card_border_radius', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "",
            "options" => [
                ""       => "rounded",
                "0"      => "squared",
                "circle" => "circle",
                "pill"   => "pill",
                "custom"   => "custom",
            ],
        ]);
        $this->addField('card_custom_radius', [
            'group' => 'card_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
            "conditions" => "[card_border_radius]=='custom'",
        ]);

        $this->addField('card_rounded_size', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "rounded_size",
            "default"    => "3",
            "conditions" => "[card_border_radius]==''",
            "options"    => [
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('media_position', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "media_position",
            "default" => "inside",
            "options" => [
                "top"    => "top",
                "left"   => "left",
                "bottom" => "bottom",
                "right"  => "right",
                "inside" => "inside",
                "cover" => "cover",
            ],
        ]);
        $this->addField('content_position', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "content_position",
            "default" => "bottom_left",
            "options" => [
                "uk-position-top"  => "top",
                "uk-position-center"  => "center",
                "uk-position-bottom"  => "bottom",
            ],
            "conditions" => "[media_position]=='cover'",
        ]);

        $this->addField('media_margin', [
            "group" => "card_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->addField('media_column_responsive', [
            "group"   => "card_options",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "lg",
            "conditions" => "[media_position]=='left' OR [media_position]=='right'",
            "options" => [
                "xxl" => "xxl_icon",
                "xl"  => "xl_icon",
                "lg"  => "lg_icon",
                "md"  => "md_icon",
                "sm"  => "sm_icon",
                "xs"  => "xs_icon",
            ],
        ]);

        // media columns (xxl/xl/lg/md/sm/xs) with conditions
        $this->addField('xxl_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "xxl_column_media_width",
            "default"    => "",
            "conditions" => "[media_column_responsive]=='xxl' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->addField('xl_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "xl_column_media_width",
            "default"    => "",
            "conditions" => "[media_column_responsive]=='xl' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->addField('lg_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "lg_column_media_width",
            "default"    => "4",
            "conditions" => "[media_column_responsive]=='lg' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->addField('md_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "md_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='md' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->addField('sm_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "sm_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='sm' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->addField('xs_column_media', [
            "group"      => "card_options",
            "type"       => "list",
            "label"      => "xs_column_media_width",
            "default"    => "12",
            "conditions" => "[media_column_responsive]=='xs' AND ([media_position]=='left' OR [media_position]=='right')",
            "options"    => [
                ""     => "inherit",
                "12"   => "1/1",
                "6"    => "1/2",
                "4"    => "1/3",
                "8"    => "2/3",
                "3"    => "1/4",
                "9"    => "3/4",
                "2"    => "1/6",
                "5"    => "5/12",
                "7"    => "7/12",
                "1"    => "1/12",
                "auto" => "auto",
            ],
        ]);

        $this->addField('vertical_middle', [
            "group"      => "card_options",
            "type"       => "radio",
            "default"    => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"      => "vertical_middle",
            "conditions" => "[media_position]=='left' OR [media_position]=='right'",
        ]);

        $this->addField('enable_grid_match', [
            "group"   => "card_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "enable_grid_match",
        ]);

        $this->addField('card_hover_transition', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "hover_transition",
            "default" => "",
            "options" => Constants::$hover_transition,
        ]);

        $this->addField('card_box_shadow', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "box_shadow",
            "default" => "",
            "options" => [
                ""             => "default",
                "shadow-none"  => "none",
                "shadow-sm"    => "sm",
                "shadow"       => "md",
                "shadow-lg"    => "lg",
            ],
        ]);

        $this->addField('card_box_shadow_hover', [
            "group"   => "card_options",
            "type"    => "list",
            "label"   => "box_shadow_hover",
            "default" => "",
            "options" => [
                ""                    => "default",
                "shadow-hover-none"   => "none",
                "shadow-hover-sm"     => "sm",
                "shadow-hover"        => "md",
                "shadow-hover-lg"     => "lg",
                "shadow-hover-popout" => "popout",
            ],
        ]);

        $this->addField('icon_size', [
            "group"   => "icon_options",
            "type"    => "range",
            "label"   => "icon_size",
            "attributes" => [
                "min"     => 1,
                "max"     => 300,
                "step"    => 1,
                "postfix"    => "px",
            ],
            "default" => 60,
        ]);

        $this->addField('icon_color', [
            "group" => "icon_options",
            "type"  => "color",
            "label" => "color",
        ]);

        $this->addField('icon_color_hover', [
            "group" => "icon_options",
            "type"  => "color",
            "label" => "color_hover",
        ]);

        $this->addField('enable_icon_link', [
            "group"   => "icon_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "enable_icon_link",
        ]);

        $this->addField('layout', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "choose_layout",
            "default" => "classic",
            "options" => [
                "classic" => "classic",
                "overlay" => "overlay",
            ],
        ]);

        $this->addField('image_fullwidth', [
            "group"   => "image_options",
            "type"    => "radio",
            "default" => "1",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "image_fullwidth",
        ]);

        $this->addField('enable_image_cover', [
            "group"   => "image_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "enable_image_cover",
        ]);

        $this->addField('min_height', [
            "group"      => "image_options",
            "type"       => "range",
            "label"      => "min_height",
            "attributes" => [
                "min"        => 1,
                "max"        => 600,
                "step"       => 1,
                "postfix"    => "px",
            ],
            "default"    => 200,
            "conditions" => "[enable_image_cover]==1",
        ]);

        $this->addField('overlay_type', [
            "group"      => "image_options",
            "type"       => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default"    => "color",
            "label"      => "overlay_color",
            "conditions" => "[enable_image_cover]==1",
            "options"    => [
                ""         => "none",
                "color"    => "color",
                "gradient" => "gradient",
            ],
        ]);

        $this->addField('overlay_color', [
            "group"      => "image_options",
            "type"       => "color",
            "label"      => "overlay_color",
            "conditions" => "[enable_image_cover]==1 AND [overlay_type]=='color'",
        ]);

        $this->addField('overlay_gradient', [
            "group"      => "image_options",
            "type"       => "gradient",
            "label"      => "overlay_gradient",
            "conditions" => "[enable_image_cover]==1 AND [overlay_type]=='gradient'",
        ]);

        $this->addField('image_border_radius', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "0",
            "options" => [
                "rounded" => "rounded",
                "0"       => "square",
                "circle"  => "circle",
                "pill"    => "pill",
                "custom"    => "custom",
            ],
        ]);
        $this->addField('image_radius', [
            'group' => 'image_options',
            'type'  => 'spacing',
            'name'  => 'image_radius',
            'label' => 'radius',
            "conditions" => "[image_border_radius]=='custom'",
        ]);

        $this->addField('image_rounded_size', [
            "group"      => "image_options",
            "type"       => "list",
            "label"      => "rounded_size",
            "default"    => "3",
            "conditions" => "[image_border_radius]=='rounded'",
            "options"    => [
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
        ]);

        $this->addField('hover_effect', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "hover_effect",
            "default" => "",
            "options" => [
                ""        => "default",
                "as-effect-light-up" => "light_up",
                "as-effect-flash"    => "flash",
                "as-effect-unveil"   => "unveil",
                "uk-transition-scale-up"   => "scale_up",
                "uk-transition-scale-down"   => "scale_down",

            ],
        ]);

        $this->addField('hover_transition', [
            "group"   => "image_options",
            "type"    => "list",
            "label"   => "hover_transition",
            "default" => "",
            "options" => Constants::$hover_transition,
        ]);

        $this->addField('title_html_element', [
            "group"   => "title_options",
            "type"    => "list",
            "label"   => "html_element",
            "default" => "h3",
            "options" => [
                "h1" => "h1",
                "h2" => "h2",
                "h3" => "h3",
                "h4" => "h4",
                "h5" => "h5",
                "h6" => "h6",
                "div" => "div",
            ],
        ]);

        $this->addField('title_font_style', [
            "group"   => "title_options",
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

        $this->addField('title_heading_margin', [
            "group" => "title_options",
            "type"  => "spacing",
            "label" => "margin",
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
        $this->addField('meta_heading_bg', [
            "group"      => "meta_options",
            "type"       => "color",
            "label"      => "background_color",
        ]);

        $this->addField('meta_heading_margin', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->addField('meta_heading_padding', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "padding",
        ]);

        $this->addField('meta_heading_radius', [
            "group" => "meta_options",
            "type"  => "spacing",
            "label" => "radius",
        ]);

        $this->addField('meta_position', [
            "group"   => "meta_options",
            "type"    => "list",
            "label"   => "meta_position",
            "default" => "before",
            "options" => [
                "before" => "before_title",
                "after" => "after_title",
                "uk-position-top-left"  => "top_left",
                "uk-position-top-center"  => "top_center",
                "uk-position-top-right"  => "top_right",
                "uk-position-center-left"  => "center_left",
                "uk-position-center"  => "center_center",
                "uk-position-center-right"  => "center_right",
                "uk-position-bottom-left"  => "bottom_left",
                "uk-position-bottom-center"  => "bottom_center",
                "uk-position-bottom-right"  => "bottom_right",
            ],
        ]);

        $this->addField('content_font_style', [
            "group"   => "content_options",
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
        $this->addField('button_font_style', [
            "group"   => "readmore_options",
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

        $this->addField('button_style', [
            "group"   => "readmore_options",
            "type"    => "list",
            "label"   => "style",
            "name"    => "button_style",
            "default" => "primary",
            "options" => [
                "primary"   => "Primary",
                "secondary" => "Secondary",
                "success"   => "Success",
                "danger"    => "Danger",
                "warning"   => "Warning",
                "info"      => "Info",
                "light"     => "Light",
                "dark"      => "Dark",
                "link"      => "Link",
                "text"      => "Text",
                "custom"      => "custom",
            ],
        ]);
        $this->addField('button_color', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[button_style]=='custom'",
        ]);
        $this->addField('button_bg_color', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[button_style]=='custom'",
        ]);
        $this->addField('button_color_hover', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[button_style]=='custom'",
        ]);
        $this->addField('button_bg_color_hover', [
            "group"      => "readmore_options",
            "type"       => "color",
            "label"      => "background_hover_color",
            "conditions" => "[button_style]=='custom'",
        ]);

        $this->addField('button_outline', [
            "group"   => "readmore_options",
            "type"    => "radio",
            "default" => "0",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "button_outline",
        ]);

        $this->addField('button_size', [
            "group"   => "readmore_options",
            "type"    => "list",
            "label"   => "button_size",
            "default" => "",
            "options" => [
                ""       => "Default",
                "btn-lg" => "Large",
                "btn-sm" => "Small",
            ],
        ]);
        $this->addField('button_padding', [
            "group"      => "readmore_options",
            "type"       => "spacing",
            "label"      => "padding",
        ]);

        $this->addField('btn_border_radius', [
            "group"   => "readmore_options",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "",
            "options" => [
                ""             => "Rounded",
                "rounded-0"    => "Square",
                "rounded-pill" => "Circle",
                "custom" => "custom",
            ],
        ]);
        $this->addField('button_custom_radius', [
            'group' => 'readmore_options',
            'type'  => 'spacing',
            'label' => 'radius',
            "conditions" => "[btn_border_radius]=='custom'",
        ]);
        $this->addField('button_custom_margin', [
            "group" => "readmore_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
    }
}