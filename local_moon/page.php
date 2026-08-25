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
require_login();
$filename = optional_param('id', '', PARAM_ALPHANUMEXT);
if (!$filename) {
    redirect(new moodle_url('/'));
}
$context = context_system::instance();
$PAGE->set_context($context);

use local_moon\library\framework;
$theme = framework::get_theme();
$layout = $theme->get_layout($filename);

$PAGE->set_url(new moodle_url('/local/moon/page.php?id=' . $filename));
$PAGE->set_pagelayout($filename);
$PAGE->set_title($layout['title']);
$PAGE->set_heading($layout['title']);
echo $OUTPUT->header();
echo "";
echo $OUTPUT->footer();