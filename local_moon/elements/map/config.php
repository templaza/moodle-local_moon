<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\MoonElement;
use local_moon\library\Helper\Form;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Font;
class MoonElementMap extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'map',
            'title' => 'Map',
            'description' => 'Map Widget of Moodle',
            'icon' => 'as-icon as-icon-map',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $this->setFieldSet('general-settings');

        $this->addField('map_options', [
            'type'  => 'group',
            'label' => 'map_options',
        ]);

        $this->addField('multiple_maps', [
            'type'  => 'group',
            'label' => 'multiple_maps',
        ]);

        $this->addField('map_option', [
            'group'   => 'general',
            'type'    => 'list',
            'default' => 'basic',
            'options' => [
                'basic'    => 'basic',
                'advanced' => 'advanced',
            ],
        ]);

        $this->addField('location', [
            'group'       => 'general',
            'conditions'  => "[map_option]=='basic'",
            'type'        => 'text',
            'label'       => 'location',
            'description' => 'location_desc',
            'attributes' => [
                'hint'        => 'Big Ben London, UK',
            ],
        ]);

        $this->addField('map', [
            'group'       => 'general',
            'conditions'  => "[map_option]=='advanced'",
            'type'        => 'text',
            'label'       => 'address_location',
            'description' => 'address_location_desc',
            'attributes' => [
                'hint'        => '23.755349,90.375961',
            ],
        ]);

        $this->addField('type', [
            'group'   => 'general',
            'conditions'=> "[map_option]=='advanced'",
            'type'    => 'list',
            'default' => 'roadmap',
            'label'   => 'map_type',
            'description' => 'map_type_desc',
            'options' => [
                'roadmap'   => 'ROADMAP',
                'satellite' => 'SATELLITE',
                'hybrid'    => 'HYBRID',
                'terrain'   => 'TERRAIN',
            ],
        ]);

        $this->addField('height', [
            'group'      => 'general',
            'type'       => 'range',
            'attributes' => [
                'min'     => 1,
                'max'     => 2000,
                'step'    => 1,
                'postfix' => 'px',
            ],
            'default' => 300,
            'label'   => 'map_height',
            'description' => 'map_height_desc'
        ]);

        $this->addField('infowindow', [
            'group'      => 'map_options',
            'conditions' => "[map_option]=='advanced'",
            'type'       => 'textarea',
            'label'      => 'infowindow',
            'description'=> 'infowindow_desc',
        ]);

        $this->addField('zoom', [
            'group'      => 'map_options',
            'type'       => 'range',
            'attributes' => [
                'min'  => 0,
                'max'  => 25,
                'step' => 1,
            ],
            'default' => 15,
            'label'   => 'zoom',
        ]);

        $this->addField('mousescroll', [
            'group'      => 'map_options',
            'conditions' => "[map_option]=='advanced'",
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'mousescroll',
            'description'=> 'mousescroll_desc',
        ]);

        $this->addField('show_controllers', [
            'group'      => 'map_options',
            'conditions' => "[map_option]=='advanced'",
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '1',
            'label'      => 'show_controllers',
            'description'=> 'show_controllers_desc',
        ]);

        $this->addField('multi_location', [
            'group'      => 'multiple_maps',
            'conditions' => "[map_option]=='advanced'",
            'type'       => 'radio',
            'attributes' => ['role' => 'switch'],
            'default'    => '0',
            'label'      => 'multi_location',
            'description'=> 'multi_location_desc',
        ]);
        $repeater_options = [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'location_item' => [
                        'type'        => 'text',
                        'label'       => 'address_location',
                        'description' => 'address_location_desc',
                        'attributes' => ['hint' => '23.755349,90.375961',],
                    ],
                    'location_popup_text' => [
                        'type'        => 'astroidtextarea',
                        'label'       => 'infowindow',
                        'description' => 'infowindow_desc',
                    ],
                ]
            ],
        ];
        $repeater   = new Form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $this->addField('multi_location_items',  [
            "group" => "multiple_maps",
            "type" => "subform",
            "label" => "multi_location_items",
            "attributes" => [
                'form'    =>  $repeater->renderJson('subform')
            ],
            'conditions' => "[multi_location]=='1' AND [map_option]=='advanced'",
        ]);
    }
}