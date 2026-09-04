<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * @package   local_moon
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_moon_preset' => [
        'classname'   => 'local_moon\external\preset_api',
        'methodname'  => 'execute',
        'description' => 'Presets for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_import_preset' => [
        'classname'   => 'local_moon\external\import_preset_api',
        'methodname'  => 'execute',
        'description' => 'Import Preset for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_action' => [
        'classname'   => 'local_moon\external\action_api',
        'methodname'  => 'execute',
        'description' => 'Perform an action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_save' => [
        'classname'   => 'local_moon\external\save_api',
        'methodname'  => 'execute',
        'description' => 'Perform a save action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_layout' => [
        'classname'   => 'local_moon\external\layout_api',
        'methodname'  => 'execute',
        'description' => 'Perform a layout action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_save_layout' => [
        'classname'   => 'local_moon\external\save_layout_api',
        'methodname'  => 'execute',
        'description' => 'Perform a save layout action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_delete_layout' => [
        'classname'   => 'local_moon\external\delete_layout_api',
        'methodname'  => 'execute',
        'description' => 'Perform a delete layout action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_media' => [
        'classname'   => 'local_moon\external\media_api',
        'methodname'  => 'execute',
        'description' => 'Perform a media action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_upload_media' => [
        'classname'   => 'local_moon\external\upload_media_api',
        'methodname'  => 'execute',
        'description' => 'Perform a media action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
    ],
    'local_moon_icon' => [
        'classname'   => 'local_moon\external\icon_api',
        'methodname'  => 'execute',
        'description' => 'Perform a icon action for AJAX',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/moon:manage',
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