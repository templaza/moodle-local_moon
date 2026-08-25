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

/*
* EVENT HANDLER
*/
namespace local_moon\library\blocks;
defined('MOODLE_INTERNAL') || die();
use core\url;
use local_moon\library\framework;
class event_handler {

    public function moon_get_moodle_events_options(): array {
        global $DB;
        $opts = [0 => '-- Select event --'];
        // Fetch a reasonable number of upcoming and recent events for selection.
        $now = time();
        $records = $DB->get_records_sql('SELECT id, name, timestart FROM {event} WHERE timestart >= :now ORDER BY timestart ASC', ['now' => $now], 0, 200);
        foreach ($records as $e) {
            $label = userdate($e->timestart, get_string('strftimedatetime', 'langconfig')) . ' — ' . format_string($e->name);
            $opts[$e->id] = $label;
        }
        // If none upcoming, include some recent past events as fallback.
        if (count($opts) === 1) {
            $records = $DB->get_records_sql('SELECT id, name, timestart FROM {event} ORDER BY timestart DESC', [], 0, 100);
            foreach ($records as $e) {
                $label = userdate($e->timestart, get_string('strftimedatetime', 'langconfig')) . ' — ' . format_string($e->name);
                $opts[$e->id] = $label;
            }
        }
        return $opts;
    }
}
