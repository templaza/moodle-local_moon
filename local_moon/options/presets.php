<?php
use local_moon\library\Framework;

Framework::getTheme()->addFields(
    'presets',
    [
        'label' => 'presets',
        'icon' => 'as-icon as-icon-rocket',
        'order' => 10,
        'fields' => [
            'presets' => ["type" => "group", "label" => "presets", "description" => "presets_desc"],

            'presets_style' => [
                'group' => 'presets',
                'type' => 'preset',
            ],
        ]
    ]
);