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
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

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