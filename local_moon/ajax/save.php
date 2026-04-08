<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
define('AJAX_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');

require_login();
if (!is_siteadmin()) {
    throw new required_capability_exception(
        context_system::instance(),
        'moodle/site:config',
        'nopermissions',
        ''
    );
}
require_sesskey();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moon/ajax/save.php'));
use local_moon\library\Helper\Utilities;
use local_moon\library\Helper\Client;

header('Content-Type: application/json; charset=utf-8');

$jsondata = required_param('params', PARAM_RAW);
$theme_name = optional_param('theme', $PAGE->theme->name, PARAM_ALPHANUMEXT);
$client = new Client();
$data = \json_decode($jsondata, true);

try {
    if (!is_array($data)) {
        throw new Exception('Invalid JSON data');
    }
    $astroid_preset = optional_param('astroid-preset', 0, PARAM_INT);
    if ($astroid_preset) {
        $preset = [
            'title' => optional_param('astroid-preset-name', '', PARAM_RAW),
            'desc' => optional_param('astroid-preset-desc', '', PARAM_RAW),
            'thumbnail' => '',
            'preset' => $data
        ];
        $preset_name = uniqid('preset-');

        global $CFG;
        $presets_path = $CFG -> dirroot . "/theme/{$theme_name}/moon/presets/";
        if (!is_dir($presets_path)) {
            if (!mkdir($presets_path, 0755, true) && !is_dir($presets_path)) {
                throw new Exception('Failed to create presets directory: ' . $presets_path);
            }
        }

        $file = $presets_path . $preset_name . '.json';
        if (file_put_contents($file, \json_encode($preset)) === false) {
            throw new Exception('Failed to write preset file: ' . $file);
        }

        // Save Main Layout Preset
        Utilities::saveLayoutPreset('main_layouts', 0, $theme_name);

        // Save Sub-layouts Preset
        Utilities::saveLayoutPreset('layouts', 0, $theme_name);

        $client->response($preset_name);
    } else {
        foreach ($data as $field => $value) {
            Utilities::saveConfig($field, $value, 'theme_' . $theme_name);
        }
        $client->response('Theme Saved');
    }

} catch (Exception $e) {
    $client->errorResponse($e);
}