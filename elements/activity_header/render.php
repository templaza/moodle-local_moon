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

defined('MOODLE_INTERNAL') || die;
global $OUTPUT, $PAGE;
$renderer = $PAGE->get_renderer('core');
$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);
if (empty($headercontent)) {
    return;
}
$enable_title = (int) $this->params->get('enable_heading_title', 0);
if (!$enable_title) {
    $headercontent['title'] = null;
}

echo $OUTPUT->render_from_template('core/activity_header', $headercontent);