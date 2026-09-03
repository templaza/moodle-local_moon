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

namespace local_moon\external;

defined('MOODLE_INTERNAL') || die();

use external_function_parameters;
use external_value;
use external_single_structure;

class upload_media_api extends api {
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'theme' => new external_value(
                PARAM_ALPHANUMEXT,
                'Theme Name'
            ),

            'task' => new external_value(
                PARAM_ALPHANUMEXT,
                'Task Name'
            ),

            'filearea' => new external_value(
                PARAM_ALPHANUMEXT,
                'File Area',
                VALUE_DEFAULT,
                ''
            ),

            'itemid' => new external_value(
                PARAM_INT,
                'Item ID',
                VALUE_DEFAULT,
                0
            ),

            'folder' => new external_value(
                PARAM_PATH,
                'Current Folder',
                VALUE_DEFAULT,
                ''
            ),

            'fileInfo' => new external_single_structure([
                'contextid' => new external_value(PARAM_INT, 'File context ID'),
                'component' => new external_value(PARAM_ALPHANUMEXT, 'File component'),
                'filearea' => new external_value(PARAM_ALPHANUMEXT, 'File area'),
                'itemid' => new external_value(PARAM_INT, 'File item ID'),
                'filepath' => new external_value(PARAM_PATH, 'File path'),
                'filename' => new external_value(PARAM_FILE, 'File name'),
            ], 'File Info'),
        ]);
    }

    public static function execute($theme, $task, $filearea = '', $itemid = 0, $folder = '', $file_info = null) {
        $params = self::validate_parameters(self::execute_parameters(), ['theme' => $theme, 'task' => $task, 'filearea' => $filearea, 'itemid' => $itemid, 'folder' => $folder, 'fileInfo' => $file_info]);
        $exec = self::action($params);
        try {
            if (!method_exists($exec, $params['task'])) {
                throw new \Exception('Method not found');
            }
            return $exec->{$params['task']}();
        } catch (\Exception $e) {
            return self::response('', 'error', $e->getCode(), $e->getMessage());
        }
    }
}
