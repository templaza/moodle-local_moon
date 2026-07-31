<?php
/*
* EVENT HANDLER
*/
namespace local_moon\library\Blocks;
defined('MOODLE_INTERNAL') || die();
use core\url;
use local_moon\library\Framework;
class EventHandler {

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
