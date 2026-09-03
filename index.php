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

require_once(__DIR__ . '/../../config.php');
defined('MOODLE_INTERNAL') || die();
use local_moon\library\framework;
use local_moon\library\helper\settings;
use local_moon\library\helper\constants;

require_login();
$context = context_system::instance();
require_capability('local/moon:manage', $context);
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moon/index.php'));
$PAGE->set_title(get_string('moon_framework_settings', 'local_moon'));
$PAGE->set_heading(get_string('moon_framework_settings', 'local_moon'));
$theme_name = optional_param('theme', $PAGE->theme->name, PARAM_ALPHANUMEXT);
framework::init($theme_name);
$theme = framework::get_theme();
if (!$theme->is_moon()) {
    throw new \moodle_exception('themenotfound', 'error', $PAGE->url, $theme_name);
}
$document = framework::get_document();
settings::load_options($CFG->dirroot . '/local/moon/options');
$theme->load_settings();
$config = constants::manager_configs();
$document->add_script_options('astroid_lib', $config);
$document->add_script_options('astroid_content', settings::prepare_manager_form($theme->get_fields()));
// Get Language
$document->add_script_options('astroid_lang', settings::load_language());

echo $OUTPUT->render_from_template('local_moon/manage', [
    'title' => get_string('pluginname', $theme->get_name()) . get_string('pluginname_subfix', 'local_moon'),
    'favicon' => $OUTPUT->image_url('favicon', 'theme'),
    'color_mode_theme' => 'light',
    'script_options' => json_encode($document->get_script_options()),
    'stylesheets' => '<link href="' . parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/manage/index.css' . '" rel="stylesheet" type="text/css" /><link href="' . parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/fontawesome/css/all.min.css' . '" rel="stylesheet" type="text/css" /><link href="' . parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/linearicons/font.min.css' . '" rel="stylesheet" type="text/css" />',
    'head_scripts' => '<script src="'. parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/bootstrap/js/bootstrap.bundle.min.js' .'"></script><script src="'. parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/tinymce/tinymce.min.js' .'"></script>',
    'body_scripts' => '<script src="'. parse_url($CFG->wwwroot, PHP_URL_PATH) . '/local/moon/assets/manage/index.js' .'"></script>',
]);