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
use external_single_structure;

class UploadMediaAPI extends API {
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

    public static function execute($theme, $task, $filearea = '', $itemid = 0, $folder = '', $fileInfo = null) {
        $params = self::validate_parameters(self::execute_parameters(), ['theme' => $theme, 'task' => $task, 'filearea' => $filearea, 'itemid' => $itemid, 'folder' => $folder, 'fileInfo' => $fileInfo]);
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
