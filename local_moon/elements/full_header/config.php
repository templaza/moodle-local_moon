<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
class MoonElementFull_Header extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'full_header',
            'title' => 'Full Header',
            'description' => 'Full Header of Moodle',
            'icon' => 'fas fa-header',
            'category' => 'system',
            'element_type' => 'system',
            'multiple' => false,
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('content_options', [
            'type'  => 'group',
            'label' => 'content_options',
        ]);
        $this->addField('only_admin', [
            "group"   => "general",
            "type"    => "radio",
            "default" => "1",
            "attributes" => [
                "role" => "switch"
            ],
            "label"   => "only_admin",
        ]);

    }
}