<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_moon_preset' => [
        'classname'   => 'local_moon\external\PresetAPI',
        'methodname'  => 'execute',
        'description' => 'Presets for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_import_preset' => [
        'classname'   => 'local_moon\external\ImportPresetAPI',
        'methodname'  => 'execute',
        'description' => 'Import Preset for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_action' => [
        'classname'   => 'local_moon\external\ActionAPI',
        'methodname'  => 'execute',
        'description' => 'Perform an action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_save' => [
        'classname'   => 'local_moon\external\SaveAPI',
        'methodname'  => 'execute',
        'description' => 'Perform a save action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_layout' => [
        'classname'   => 'local_moon\external\LayoutAPI',
        'methodname'  => 'execute',
        'description' => 'Perform a layout action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_save_layout' => [
        'classname'   => 'local_moon\external\SaveLayoutAPI',
        'methodname'  => 'execute',
        'description' => 'Perform a save layout action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_delete_layout' => [
        'classname'   => 'local_moon\external\DeleteLayoutAPI',
        'methodname'  => 'execute',
        'description' => 'Perform a delete layout action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_media' => [
        'classname'   => 'local_moon\external\MediaAPI',
        'methodname'  => 'execute',
        'description' => 'Perform a media action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_upload_media' => [
        'classname'   => 'local_moon\external\UploadMediaAPI',
        'methodname'  => 'execute',
        'description' => 'Perform a media action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
    'local_moon_icon' => [
        'classname'   => 'local_moon\external\IconAPI',
        'methodname'  => 'execute',
        'description' => 'Perform a icon action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:view',
    ],
];

$services = [
    'Moon Web Service' => [
        'functions' => [
            'local_moon_action',
            'local_moon_import_preset',
            'local_moon_layout',
            'local_moon_save_layout',
            'local_moon_upload_media'
        ],
        'restrictedusers' => 0,
        'enabled'         => 1,
        'shortname'       => 'local_moon',
        'uploadfiles' => 1,
        'downloadfiles' => 1,
    ],
];