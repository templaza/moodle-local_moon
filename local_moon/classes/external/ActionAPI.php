<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\external;

defined('MOODLE_INTERNAL') || die();

class ActionAPI extends API {
    public static function execute($theme, $task, $filearea, $itemid) {
        $params = self::validate_parameters(self::execute_parameters(), ['theme' => $theme, 'task' => $task, 'filearea' => $filearea, 'itemid' => $itemid]);
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