<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Font;
class MoonElementVideo_Button extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'video_button',
            'title' => 'Video Button',
            'description' => 'Video Button Widget of Moodle',
            'icon' => 'as-icon as-icon-play',
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
        $this->addField('url',  [
            "group" => "general",
            "type" => "text",
            "label" => "link_url",
            "attributes" => [
                'hint' => 'https://www.youtube.com/watch?v=xxxxx'
            ],
            "dynamic" => true,
        ]);
        $this->addField('button_size', [
            "group"      => "widget_styles",
            "type"       => "range",
            "attributes" => [
                "min"     => 1,
                "max"     => 300,
                "step"    => 1,
                "postfix" => "px",
            ],
            "default" => 24,
            "label"   => "button_size",
        ]);

        $this->addField('ripple_color', [
            "group" => "widget_styles",
            "type"  => "color",
            "label" => "ripple_color",
        ]);

        $this->addField('width', [
            "group"      => "widget_styles",
            "type"       => "range",
            "attributes" => [
                "min"     => 10,
                "max"     => 500,
                "step"    => 1,
                "postfix" => "px",
                'responsive' => true,
            ],
            "default" => 150,
            "label"   => "width",
        ]);

        $this->addField('height', [
            "group"      => "widget_styles",
            "type"       => "range",
            "attributes" => [
                "min"     => 10,
                "max"     => 500,
                "step"    => 1,
                "postfix" => "px",
                'responsive' => true,
            ],
            "default" => 150,
            "label"   => "height",
        ]);

        $this->addField('color_hover_toggle', [
            "group"      => "widget_styles",
            "type"       => "radio",
            "attributes" => [
                "width" => "full",
            ],
            "default" => "color",
            "options" => [
                "color" => "color",
                "hover" => "color_hover",
            ],
        ]);

        $this->addField('color', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "color",
            "conditions" => "[color_hover_toggle]=='color'",
        ]);

        $this->addField('color_hover', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "color_hover",
            "conditions" => "[color_hover_toggle]=='hover'",
        ]);

        $this->addField('background_color', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "background_color",
            "conditions" => "[color_hover_toggle]=='color'",
        ]);

        $this->addField('background_color_hover', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "background_color_hover",
            "conditions" => "[color_hover_toggle]=='hover'",
        ]);

        $this->addField('use_border', [
            "group"      => "widget_styles",
            "type"       => "radio",
            "attributes" => [
                "role" => "switch"
            ],
            "default" => "0",
            "label"   => "use_border",
        ]);

        $this->addField('border_width', [
            "group"      => "widget_styles",
            "type"       => "range",
            "attributes" => [
                "min"     => 1,
                "max"     => 50,
                "step"    => 1,
                "postfix" => "px",
            ],
            "default"    => 1,
            "label"      => "border_width",
            "conditions" => "[use_border]==1",
        ]);

        $this->addField('border_color', [
            "group"      => "widget_styles",
            "type"       => "color",
            "label"      => "border_color",
            "conditions" => "[use_border]==1",
        ]);
    }
}