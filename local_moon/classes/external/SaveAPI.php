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
use local_moon\library\Helper\Utilities;

class SaveAPI extends API {
    public static function execute_parameters(): external_function_parameters {
        global $PAGE;
        return new external_function_parameters([
            'params' => new external_value(
                PARAM_RAW,
                'Parameters'
            ),
            'theme' => new external_value(
                PARAM_TEXT,
                'Theme Name',
                VALUE_DEFAULT,
                $PAGE->theme->name
            ),
            'astroid_preset_name' => new external_value(
                PARAM_TEXT,
                'Preset Name',
                VALUE_DEFAULT,
                ''
            ),
            'astroid_preset_desc' => new external_value(
                PARAM_TEXT,
                'Preset Description',
                VALUE_DEFAULT,
                ''
            ),
            'astroid_preset' => new external_value(
                PARAM_INT,
                'Preset Toggle',
                VALUE_DEFAULT,
                0
            )
        ]);
    }

    public static function execute($params, $theme, $astroid_preset_name, $astroid_preset_desc, $astroid_preset) {
        $value = self::validate_parameters(self::execute_parameters(), ['params' => $params, 'theme' => $theme, 'astroid_preset_name' => $astroid_preset_name, 'astroid_preset_desc' => $astroid_preset_desc, 'astroid_preset' => $astroid_preset]);
        require_login();
        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/moon:view', $context);
        try {
            $data = \json_decode($value['params'], true);
            if (!is_array($data)) {
                throw new \Exception('Invalid JSON data');
            }
            if ($value['astroid_preset']) {
                $preset = [
                    'title' => $value['astroid_preset_name'],
                    'desc' => $value['astroid_preset_desc'],
                    'thumbnail' => '',
                    'preset' => $data
                ];
                $preset_name = uniqid('preset-');

                global $CFG;
                $presets_path = $CFG -> dirroot . "/theme/{$value['theme']}/moon/presets/";
                if (!is_dir($presets_path)) {
                    if (!mkdir($presets_path, 0755, true) && !is_dir($presets_path)) {
                        throw new \Exception('Failed to create presets directory: ' . $presets_path);
                    }
                }

                $file = $presets_path . $preset_name . '.json';
                if (file_put_contents($file, \json_encode($preset)) === false) {
                    throw new \Exception('Failed to write preset file: ' . $file);
                }

                // Save Main Layout Preset
                Utilities::saveLayoutPreset('main_layouts', 0, $value['theme']);

                // Save Sub-layouts Preset
                Utilities::saveLayoutPreset('layouts', 0, $value['theme']);
                return self::response($preset_name);
            } else {
                foreach ($data as $field => $val) {
                    Utilities::saveConfig($field, $val, 'theme_' . $value['theme']);
                }
                return self::response('Theme Saved');
            }
        } catch (\Exception $e) {
            return self::response('', 'error', $e->getCode(), $e->getMessage());
        }
    }
}