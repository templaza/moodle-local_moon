<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Font;
class MoonElementRawHTML extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'rawhtml',
            'title' => 'Raw HTML',
            'description' => 'HTML Widget of Moodle',
            'icon' => 'fa-brands fa-html5',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('content', [
            "group"   => "general",
            "type"    => "textarea",
            "label"   => "content",
            "attributes" => [
                'code' => 'html'
            ],
            "dynamic" => true,
        ]);

    }
}