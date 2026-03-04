<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Constants;
class MoonElementImage extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'image',
            'title' => 'Image',
            'description' => 'Image Widget of Moodle',
            'icon' => 'as-icon as-icon-picture',
            'category' => 'media',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('widget_styles',  [
            "type" => "group",
            "label" => "widget_styles",
        ]);

        $this->addField('image_color_mode', [
            "group"   => "general",
            "type"    => "radio",
            "attributes" => [
                "width"   => "full",
            ],
            "default" => "light",
            "options" => [
                "light" => "color_mode_light",
                "dark"  => "color_mode_dark",
            ],
        ]);

        $this->addField('image', [
            "group"      => "general",
            "type"       => "media",
            "label"      => "TPL_ASTROID_SELECT_IMAGE_LIGHT",
            "dynamic"    => true,
            "conditions" => "[image_color_mode]=='light'",
        ]);

        $this->addField('image_dark', [
            "group"      => "general",
            "type"       => "media",
            "label"      => "TPL_ASTROID_SELECT_IMAGE_DARK",
            "dynamic"    => true,
            "conditions" => "[image_color_mode]=='dark'",
        ]);

        $this->addField('figure_caption', [
            "group"   => "general",
            "type"    => "text",
            "label"   => "figure_caption",
            "dynamic" => true,
        ]);

        $this->addField('use_link', [
            "group"       => "general",
            "type"        => "radio",
            "label"       => "use_link",
            "description" => "use_link_desc",
            "attributes" => [
                "role" => "switch"
            ],
            "default"     => "0",
        ]);

        $this->addField('link', [
            "group"      => "general",
            "type"       => "text",
            "label"      => "link_url",
            "description"=> "link_url_desc",
            "hint"       => "https://astroidframe.work/",
            "dynamic"    => true,
            "conditions" => "[use_link]==1",
        ]);

        $this->addField('target', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "link_target",
            "default"    => "",
            "options"    => [
                ""        => "Default",
                "_blank"  => "New Window",
                "_parent" => "Parent Frame",
                "_top"    => "Full body of the window",
            ],
            "conditions" => "[use_link]==1",
        ]);

        $this->addField('display', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "display",
            "default" => "",
            "options" => [
                ""           => "Block",
                "d-inline-block" => "Inline Block",
                "d-inline"   => "Inline",
                "d-flex"    => "Flex",
                "d-inline-flex" => "Inline Flex",
            ],
        ]);
        $this->addField('image_width', [
            'group'   => 'widget_styles',
            'type'    => 'range',
            'label'      => 'image_width',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);
        $this->addField('image_height', [
            'group'   => 'widget_styles',
            'type'    => 'range',
            'label'      => 'image_height',
            "attributes" => [
                'min'        => 1,
                'max'        => 2000,
                'step'       => 1,
                'responsive' => true,
                'postfix' => 'px|%',
            ],
        ]);

        $this->addField('img_border_radius', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "border_radius",
            "default" => "",
            "options" => [
                ""               => "none",
                "rounded"        => "rounded",
                "rounded-circle" => "circle",
                "rounded-pill"   => "pill",
                "custom"   => "custom",
            ],
        ]);
        $this->addField('image_radius', [
            'group' => 'widget_styles',
            'type'  => 'spacing',
            'label' => 'radius',
            "conditions" => "[img_border_radius]=='custom'",
        ]);

        $this->addField('image_rounded_size', [
            "group"      => "widget_styles",
            "type"       => "list",
            "label"      => "rounded_size",
            "default"    => "3",
            "options"    => [
                "1" => "X-Small",
                "2" => "Small",
                "3" => "Medium",
                "4" => "Large",
                "5" => "X-Large",
            ],
            "conditions" => "[img_border_radius]=='rounded'",
        ]);

        $this->addField('box_shadow', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "box_shadow",
            "default" => "",
            "options" => [
                ""          => "default",
                "shadow-none" => "none",
                "shadow-sm"   => "small",
                "shadow"      => "regular",
                "shadow-lg"   => "large",
            ],
        ]);

        $this->addField('hover_effect', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "hover_effect",
            "default" => "",
            "options" => [
                ""         => "default",
                "light-up" => "light_up",
                "flash"    => "flash",
                "unveil"   => "unveil",
            ],
        ]);

        $this->addField('hover_transition', [
            "group"   => "widget_styles",
            "type"    => "list",
            "label"   => "hover_transition",
            "default" => "",
            "options" => Constants::$hover_transition,
        ]);
    }
}