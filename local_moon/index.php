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

require_once(__DIR__ . '/../../config.php');
defined('MOODLE_INTERNAL') || die();
use local_moon\library\Framework;
use local_moon\library\Helper\Settings;
use local_moon\library\Helper\Constants;

require_login();
if (!is_siteadmin()) {
    throw new required_capability_exception(
        context_system::instance(),
        'moodle/site:config',
        'nopermissions',
        ''
    );
}
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moon/index.php'));
$PAGE->set_title('Moon Framework Settings');
$PAGE->set_heading('Moon Framework Settings');
$theme_name = optional_param('theme', $PAGE->theme->name, PARAM_ALPHANUMEXT);
Framework::init($theme_name);
$theme = Framework::getTheme();
if (!$theme->isMoon()) {
    throw new \moodle_exception('themenotfound', 'error', $PAGE->url, $theme_name);
}
$document = Framework::getDocument();
Settings::loadOptions($CFG->dirroot . '/local/moon/options');
$theme->loadSettings();
$config = Constants::manager_configs();
$document->addScriptOptions('astroid_lib', $config);
$document->addScriptOptions('astroid_content', Settings::prepareManagerForm($theme->getFields()));
// Get Language
$document->addScriptOptions('astroid_lang', Settings::loadLanguage());

echo $OUTPUT->render_from_template('local_moon/manage', [
    'title' => get_string('pluginname', $theme->getName()) . ' - Moon Framework',
    'favicon' => $OUTPUT->image_url('favicon', 'theme'),
    'content' => 'This demo page proves the framework assets, template and classes load correctly.',
    'color_mode_theme' => 'light',
    'script_options' => json_encode($document->getScriptOptions()),
    'stylesheets' => '<link href="' . parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/manage/index.css' . '" rel="stylesheet" type="text/css" /><link href="' . parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/fontawesome/css/all.min.css' . '" rel="stylesheet" type="text/css" /><link href="' . parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/linearicons/font.min.css' . '" rel="stylesheet" type="text/css" />',
    'head_scripts' => '<script src="'. parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/bootstrap/js/bootstrap.bundle.min.js' .'"></script><script src="'. parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/tinymce/tinymce.min.js' .'"></script>',
    'body_scripts' => '<script src="'. parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/manage/index.js' .'"></script>',
]);