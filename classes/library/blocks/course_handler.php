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
* COURSE HANDLER
*/
namespace local_moon\library\blocks;
defined('MOODLE_INTERNAL') || die();
use core\url;
use local_moon\library\framework;

class course_handler {
    public function moon_get_course_details($course_id) {
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;


        $course_id = (int)$course_id;
        if ($DB->record_exists('course', array('id' => $course_id))) {
        $moon_course = new \stdClass();
        $cathelper = new \coursecat_helper();
        $course_context = \context_course::instance($course_id);

        $course_record = $DB->get_record('course', array('id' => $course_id));
        $course_element = new \core_course_list_element($course_record);

        $course_id = $course_record->id;
        $course_short_name = $course_record->shortname;
        $course_full_name = $course_record->fullname;
        $course_summary = $cathelper->get_course_formatted_summary($course_element, array('noclean' => true, 'para' => false));
        $course_format = $course_record->format;
        $course_announcements = $course_record->newsitems;
        $course_start_date = $course_record->startdate;
        $course_end_date = $course_record->enddate;
        $course_visible = $course_record->visible;
        $course_created = $course_record->timecreated;
        $course_updated = $course_record->timemodified;
        $course_requested = $course_record->requested;
        $course_enrolment_count = count_enrolled_users($course_context);
        $course_is_enrolled = is_enrolled($course_context, $USER->id, '', true);

        $category_id = $course_record->category;

        try {
            $course_category = \core_course_category::get($category_id);
            $category_name = $course_category->get_formatted_name();
            $category_url = $CFG->wwwroot . '/course/index.php?categoryid='.$category_id;
        } catch (\Exception $e) {
            $course_category = "";
            $category_name = "";
            $category_url = "";
        }

        $enrolment_link = $CFG->wwwroot . '/enrol/index.php?id=' . $course_id;
        $course_url = new \moodle_url('/course/view.php', array('id' => $course_id));
        $enrol_instances = enrol_get_instances($course_id, true);

        $course_price = '';
        $course_currency = '';
        foreach($enrol_instances as $singleenrol_instances){
            if($singleenrol_instances->enrol == 'paypal'){
                $course_price = $singleenrol_instances->cost;
                $course_currency = $singleenrol_instances->currency;
            }elseif($singleenrol_instances->enrol == 'stripe'){
                $course_price = $singleenrol_instances->cost;
                $course_currency = $singleenrol_instances->currency;
            }elseif($singleenrol_instances->enrol == 'payfast'){
                $course_price = $singleenrol_instances->cost;
                $course_currency = $singleenrol_instances->currency;
            }elseif($singleenrol_instances->enrol == 'paymob'){
                $course_price = $singleenrol_instances->cost;
                $course_currency = $singleenrol_instances->currency;
            }else{
                $course_price = $singleenrol_instances->cost;
                $course_currency = $singleenrol_instances->currency;
            }
        }
        

        $moon_array_of_costs = array();
            $moon_course_contacts = array();
            if ($course_element->has_course_contacts()) {
                foreach ($course_element->get_course_contacts() as $key => $course_contact) {
                $moon_course_contacts[$key] = new \stdClass();
                $moon_course_contacts[$key]->userId = $course_contact['user']->id;
                $moon_course_contacts[$key]->username = $course_contact['user']->username;
                $moon_course_contacts[$key]->name = $course_contact['user']->firstname . ' ' . $course_contact['user']->lastname;
                $moon_course_contacts[$key]->role = $course_contact['role']->displayname;
                $moon_course_contacts[$key]->profileUrl = new \moodle_url('/user/view.php', array('id' => $course_contact['user']->id, 'course' => SITEID));
                }
            }


            $theme = framework::get_theme();
            $contentimages = $CFG->wwwroot . '/theme/'.$theme->name.'/pix/category.jpg';
            foreach ($course_element->get_course_overviewfiles() as $file) {
                if ($file->is_valid_image()) {
                    $url = url::make_pluginfile_url(
                        $file->get_contextid(),
                        $file->get_component(),
                        $file->get_filearea(),
                        $file->get_filepath(),
                        $file->get_filename(),
                        false
                    );

                    $contentimages = $url->out();
                    break;
                }
            }

        $moon_course->courseId = $course_id;
        $moon_course->enrolments = $course_enrolment_count;
        $moon_course->categoryId = $category_id;
        $moon_course->categoryName = $category_name;
        $moon_course->categoryUrl = $category_url;
        $moon_course->shortName = $course_short_name;
        $moon_course->fullName = format_text($course_full_name, FORMAT_HTML, array('filter' => true));
        $moon_course->summary = $course_summary;
        $moon_course->format = $course_format;
        $moon_course->announcements = $course_announcements;
        $moon_course->startDate = userdate($course_start_date, get_string('strftimedatefullshort', 'langconfig'));
        $moon_course->endDate = userdate($course_end_date, get_string('strftimedatefullshort', 'langconfig'));
        $moon_course->visible = $course_visible;
        $moon_course->created = userdate($course_created, get_string('strftimedatefullshort', 'langconfig'));
        $moon_course->updated = userdate($course_updated, get_string('strftimedatefullshort', 'langconfig'));
        $moon_course->requested = $course_requested;
        $moon_course->enrolmentLink = $enrolment_link;
        $moon_course->url = $course_url;
        $moon_course->teachers = $moon_course_contacts;
        $moon_course->course_price = $course_price;
        $moon_course->course_currency = $course_currency;
        $moon_course->course_is_enrolled = $course_is_enrolled;

        $moon_render = new \stdClass();
        $moon_render->enrolmentIcon = '';
        $moon_render->enrolmentIcon1 = '';
        $moon_render->announcementsIcon     =     '';
        $moon_render->announcementsIcon1     =     '';
        $moon_render->updatedDate           =     '';
        $moon_render->updatedDate         =     userdate($course_updated, get_string('strftimedatefullshort', 'langconfig'));
        $moon_render->title             =     '<h3><a href="'. $moon_course->url .'">'. $moon_course->fullName .'</a></h3>';
        $moon_render->coverImage = '<img class="img-whp" src="'. $contentimages .'" alt="'.strip_tags($moon_course->fullName).'">';
        $moon_render->ImageUrl = $contentimages;
        $moon_course->moonRender = $moon_render;
        return $moon_course;
        }
        return null;
    }

    public function moon_get_course_description($course_id, $max_length){
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;
    
        if ($DB->record_exists('course', array('id' => $course_id))) {
        $cathelper = new \coursecat_helper();
        $course_context = \context_course::instance($course_id);
    
        $course_record = $DB->get_record('course', array('id' => $course_id));
        $course_element = new \core_course_list_element($course_record);
    
        if ($course_element->has_summary()) {
            $course_summary = $cathelper->get_course_formatted_summary($course_element, array('noclean' => false, 'para' => false));
            if($max_length != null) {
            if (strlen($course_summary) > $max_length) {
                $course_summary = wordwrap($course_summary, $max_length);
                $course_summary = substr($course_summary, 0, strpos($course_summary, "\n")) . '...';
            }
            }
            return $course_summary;
        }
    
        }
        return null;
    }

    public function moon_list_categories(){
        global $DB, $CFG;
        $topcategory = \core_course_category::top();
        $topcategorykids = $topcategory->get_children();
        $areanames = array();
        foreach ($topcategorykids as $areaid => $topcategorykids) {
            $areanames[$areaid] = $topcategorykids->get_formatted_name();
            foreach($topcategorykids->get_children() as $k=>$child){
                $areanames[$k] = $child->get_formatted_name();
            }
        }
        return $areanames;
    }

    public function moon_get_category_details($category_id){
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;
    
        if ($DB->record_exists('course_categories', array('id' => $category_id))) {
    
        $category_record = $DB->get_record('course_categories', array('id' => $category_id));
    
        $cathelper = new \coursecat_helper();
        $category_object = \core_course_category::get($category_id);
    
        $moon_category = new \stdClass();
    
        $category_id = $category_record->id;
        $category_name = format_text($category_record->name, FORMAT_HTML, array('filter' => true));
        $category_description = $cathelper->get_category_formatted_description($category_object);
    
        $category_summary = format_string($category_record->description, $striplinks = true,$options = null);
        $is_visible = $category_record->visible;
        $category_url = $CFG->wwwroot . '/course/index.php?categoryid=' . $category_id;
        $category_courses = $category_object->get_courses();
        $category_courses_count = count($category_courses);
    
        $category_get_subcategories = [];
        $category_subcategories = [];
        if (!$cathelper->get_categories_display_option('nodisplay')) {
            $category_get_subcategories = $category_object->get_children($cathelper->get_categories_display_options());
        }
        foreach($category_get_subcategories as $k=>$moon_subcategory) {
            $moon_subcat = new \stdClass();
            $moon_subcat->id = $moon_subcategory->id;
            $moon_subcat->name = $moon_subcategory->name;
            $moon_subcat->description = $moon_subcategory->description;
            $moon_subcat->depth = $moon_subcategory->depth;
            $moon_subcat->coursecount = $moon_subcategory->coursecount;
            $category_subcategories[$moon_subcategory->id] = $moon_subcat;
        }
    
        $category_subcategories_count = count($category_subcategories);
    
        /* Do image */
        $outputimage = '';
        //moonComm: Fetching the image manually added to the coursecat description via the editor.
        $description = $cathelper->get_category_formatted_description($category_object);
        $src = "";
        if ($description) {
            $dom = new DOMDocument();
            $dom->loadHTML($description);
            $xpath = new DOMXPath($dom);
            $src = $xpath->evaluate("string(//img/@src)");
        }
        if ($src && $description){
            $outputimage = $src;
        } else {
            foreach($category_courses as $child_course) {
            if ($child_course === reset($category_courses)) {
                foreach ($child_course->get_course_overviewfiles() as $file) {
                    if ($file->is_valid_image()) {
                        $imagepath = '/' . $file->get_contextid() . '/' . $file->get_component() . '/' . $file->get_filearea() . $file->get_filepath() . $file->get_filename();
                        $imageurl = file_encode_url($CFG->wwwroot . '/pluginfile.php', $imagepath, false);
                        $outputimage  =  $imageurl;
                        // Use the first image found.
                        break;
                    }
                }
            }
            }
        }
    
        $moon_category->categoryId = $category_id;
        $moon_category->categoryName = $category_name;
        $moon_category->categoryDescription = $category_description;
        $moon_category->categorySummary = $category_summary;
        $moon_category->isVisible = $is_visible;
        $moon_category->categoryUrl = $category_url;
        $moon_category->coverImage = $outputimage;
        $moon_category->ImageUrl = $outputimage;
        $moon_category->courses = $category_courses;
        $moon_category->coursesCount = $category_courses_count;
        $moon_category->subcategories = $category_subcategories;
        $moon_category->subcategoriesCount = $category_subcategories_count;
        return $moon_category;
    
        }
    }

    public function moon_get_example_categories($max_num) {
        global $CFG, $DB;
    
        $moon_categories = $DB->get_records('course_categories', array(), $sort='', $fields='*', $limitfrom=0, $limitnum=$max_num);
    
        $moon_return = array();
        foreach ($moon_categories as $moon_category) {
        $moon_return[] = $this->moon_get_category_details($moon_category->id);
        }
        return $moon_return;
    }

    public function moon_get_example_categories_ids($max_num) {
        global $CFG, $DB;
    
        $moon_categories = $this->moon_get_example_categories($max_num);
    
        $moon_return = array();
        foreach ($moon_categories as $key => $moon_category) {
        $moon_return[] = $moon_category->categoryId;
        }
        return $moon_return;
    }
    public function moon_get_courseprice($courseid) {
        global $DB;
        $usedpayment = '';
        $result = new \stdClass;
        $result->hascourseprice = FALSE;
        $result->courseprice = 0;

        $enrol_methods = $DB->get_records( 'enrol', array( 'courseid' => $courseid, 'status' => ENROL_INSTANCE_ENABLED ), '', 'id, enrol, name, sortorder' );
        foreach ($enrol_methods as $method) {
            if (in_array($method->enrol, array('paypal', 'fee', 'stripepayment'))) {
                $result->hascourseprice = TRUE;
                $usedpayment = $method->enrol;
            }
        }
        if ($result->hascourseprice) {
            $result->courseprice = $DB->get_record_sql(
                'SELECT cost, currency FROM {enrol} WHERE courseid = ? AND enrol = ?',
                array($courseid, $usedpayment),
                IGNORE_MULTIPLE
            );
        }
        return $result;
    }
    public function moon_countstudents($courseid) {
        global $DB;

        $sql = "SELECT COUNT(*)
              FROM {role_assignments} ra
              JOIN {context} ctx ON ra.contextid = ctx.id
             WHERE ctx.contextlevel = 50
               AND ctx.instanceid = :courseid
               AND ra.roleid = 5"; // 5 = student

        return $DB->count_records_sql($sql, ['courseid' => $courseid]);
    }

    function moon_course_sections(int $courseid): int {
        global $DB;

        if ($courseid <= 0) {
            throw new coding_exception('Invalid course id');
        }
        $count = $DB->count_records('course_sections', ['course' => $courseid]);

        return $count;
    }
    function moon_course_popular(int $limit = 10): array {
        global $DB;

        $sql = "
        SELECT
            c.id,
            c.fullname,
            c.shortname,
            c.summary,
            COUNT(ue.id) AS enrolcount
        FROM {course} c
        JOIN {enrol} e ON e.courseid = c.id
        JOIN {user_enrolments} ue ON ue.enrolid = e.id
        WHERE c.visible = 1
          AND c.id <> 1
        GROUP BY c.id, c.fullname, c.shortname, c.summary
        ORDER BY enrolcount DESC
    ";

        return $DB->get_records_sql($sql, null, 0, $limit);
    }
    function moon_course_all_category(): array {
        $categories = \core_course_category::get_all();
        $cat_arr = array();
        foreach ($categories as $category) {
            $cat_arr[$category->id] = $category->name;
        }
        return $cat_arr;
    }
    function moon_get_moodle_events_options(): array {
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
