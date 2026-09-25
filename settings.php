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

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_moon', get_string('pluginname', 'local_moon'));
    $settings->add(new admin_setting_heading('local_moon_heading', '', get_string('settings_desc', 'local_moon')));
    $settings->add(new admin_setting_configselect('local_moon/hide_preview_font', get_string('hide_preview_font', 'local_moon') , get_string('hide_preview_font_desc', 'local_moon') , null, array(
        '0' => 'Visible',
        '1' => 'Hidden'
    )));
    $ADMIN->add('localplugins', $settings);
}
