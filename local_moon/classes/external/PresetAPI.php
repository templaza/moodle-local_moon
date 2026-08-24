<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\external;

defined('MOODLE_INTERNAL') || die();

use external_function_parameters;
use external_value;

class PresetAPI extends API {
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

            'name' => new external_value(
                PARAM_ALPHANUMEXT,
                'Preset Name',
                VALUE_DEFAULT,
                ''
            ),
        ]);
    }

    public static function execute($theme, $task, $name) {
        $params = self::validate_parameters(self::execute_parameters(), ['theme' => $theme, 'task' => $task, 'name' => $name]);
        $exec = self::action($params);
        try {
            if (!method_exists($exec, $params['task'])) {
                throw new \Exception('Method not found');
            }
            $return = $exec->{$params['task']}();
            return self::response(\json_encode($return));
        } catch (\Exception $e) {
            return self::response('', 'error', $e->getCode(), $e->getMessage());
        }
    }
}