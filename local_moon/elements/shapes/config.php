<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementShapes extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'shapes',
            'title' => 'Shape',
            'description' => 'Add Shape for block',
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
        $this->addField('shape_style', [
            'group'   => 'general',
            'type'    => 'list',
            'label'   => 'Shape',
            'default' => '',
            'options' => [
                'wave'         => 'wave style1',
                'wave2' => 'wave style2',
                'wave3' => 'background-title',
            ],
        ]);

        $this->addField('shape_color', [
            "group"      => "general",
            "type"       => "color",
            "label"      => "color",
        ]);


    }
}