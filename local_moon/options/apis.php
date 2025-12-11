<?php
use local_moon\library\Framework;

Framework::getTheme()->addFields(
    'apis',
    [
        'label' => 'apis',
        'icon' => 'as-icon as-icon-palette',
        'order' => 8,
        'fields' => [
            'google_map' => ["type" => "group", "label" => "google_map", "description" => "google_map_desc"],

            'gmap_api' => [
                'group' => 'google_map',
                'type' => 'text',
                'label' => 'gmap_api',
                'description' => 'gmap_api_desc',
            ],
        ]
    ]
);