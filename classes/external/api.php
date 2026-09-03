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

global $CFG;

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use local_moon\library\framework;
use local_moon\library\helper\action;

class api extends external_api {
    public static function execute_parameters(): external_function_parameters {
        global $PAGE;
        return new external_function_parameters([
            'theme' => new external_value(
                PARAM_ALPHANUMEXT,
                'Theme Name',
                VALUE_DEFAULT,
                $PAGE->theme->name
            ),

            'task' => new external_value(
                PARAM_ALPHANUMEXT,
                'Task Name',
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
        ]);
    }

    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(
                PARAM_TEXT,
                'Status'
            ),

            'data' => new external_value(
                PARAM_RAW,
                'JSON encoded response data'
            ),

            'code' => new external_value(
                PARAM_INT,
                'Error code',
                VALUE_OPTIONAL,
                0
            ),

            'message' => new external_value(
                PARAM_TEXT,
                'Error message',
                VALUE_OPTIONAL,
                ''
            ),
        ]);
    }

    /**
     * @param array $params
     * @return action
     * @throws \moodle_exception
     */
    public static function action($params): action
    {
        \require_login();
        $context = \context_system::instance();
        self::validate_context($context);
        $task = $params['task'] ?? '';
        $readonlytasks = [
            'list',
            'get_layouts',
            'get_layout',
            'get_fonts',
            'get_icons',
            'get_presets',
            'load_preset',
        ];
        $requiredcapability = in_array($task, $readonlytasks, true) ? 'local/moon:view' : 'local/moon:manage';
        \require_capability($requiredcapability, $context);
        framework::init($params['theme'] ?? null);
        if (!framework::get_theme()->is_moon()) {
            throw new \moodle_exception('themenotmoon', 'local_moon');
        }
        return new action($params);
    }

    public static function response($data, $status = 'success', $code = 200, $message = 'OK'): array
    {
        return ['status' => $status, 'code' => $code, 'data' => $data, 'message' => $message];
    }
}