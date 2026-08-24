<?php
/**
 * @package   Moon Framework
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
use local_moon\library\Framework;
use local_moon\library\Helper\Action;

class API extends external_api {
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

    public static function action($params): Action
    {
        \require_login();
        $context = \context_system::instance();
        self::validate_context($context);
        \require_capability('local/moon:view', $context);
        Framework::init($params['theme'] ?? null);
        return new Action($params);
    }

    public static function response($data, $status = 'success', $code = 200, $message = 'OK'): array
    {
        return ['status' => $status, 'code' => $code, 'data' => $data, 'message' => $message];
    }
}