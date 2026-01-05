<?php
/*
* COURSE HANDLER
*/
namespace local_moon\library\Blocks;
defined('MOODLE_INTERNAL') || die();
use core\url;
use local_moon\library\Framework;
require_once($CFG->dirroot. '/course/renderer.php');
include_once($CFG->dirroot . '/course/lib.php');

class CourseHandler {
    public function moonGetCourseDetails($courseId) {
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;


        $courseId = (int)$courseId;
        if ($DB->record_exists('course', array('id' => $courseId))) {
        // @moonComm: Initiate
        $moonCourse = new \stdClass();
        $chelper = new \coursecat_helper();
        $courseContext = \context_course::instance($courseId);

        $courseRecord = $DB->get_record('course', array('id' => $courseId));
        $courseElement = new \core_course_list_element($courseRecord);

        /* @moonBreak */
        $courseId = $courseRecord->id;
        $courseShortName = $courseRecord->shortname;
        $courseFullName = $courseRecord->fullname;
        $courseSummary = $chelper->get_course_formatted_summary($courseElement, array('noclean' => true, 'para' => false));
        $courseFormat = $courseRecord->format;
        $courseAnnouncements = $courseRecord->newsitems;
        $courseStartDate = $courseRecord->startdate;
        $courseEndDate = $courseRecord->enddate;
        $courseVisible = $courseRecord->visible;
        $courseCreated = $courseRecord->timecreated;
        $courseUpdated = $courseRecord->timemodified;
        $courseRequested = $courseRecord->requested;
        $courseEnrolmentCount = count_enrolled_users($courseContext);
        $course_is_enrolled = is_enrolled($courseContext, $USER->id, '', true);

        /* @moonBreak */
        $categoryId = $courseRecord->category;

        try {
            $courseCategory = \core_course_category::get($categoryId);
            $categoryName = $courseCategory->get_formatted_name();
            $categoryUrl = $CFG->wwwroot . '/course/index.php?categoryid='.$categoryId;
        } catch (Exception $e) {
            $courseCategory = "";
            $categoryName = "";
            $categoryUrl = "";
        }

        /* @moonBreak */
        $enrolmentLink = $CFG->wwwroot . '/enrol/index.php?id=' . $courseId;
        $courseUrl = new \moodle_url('/course/view.php', array('id' => $courseId));
        // @moonComm: Start Payment
        $enrolInstances = enrol_get_instances($courseId, true);

        $course_price = '';
        $course_currency = '';
        foreach($enrolInstances as $singleenrolInstances){
            if($singleenrolInstances->enrol == 'paypal'){
                $course_price = $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }elseif($singleenrolInstances->enrol == 'stripe'){
                $course_price = $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }elseif($singleenrolInstances->enrol == 'payfast'){
                $course_price = $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }elseif($singleenrolInstances->enrol == 'paymob'){
                $course_price = $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }else{
                $course_price = $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }
        }
        

        $moonArrayOfCosts = array();
            $moonCourseContacts = array();
            if ($courseElement->has_course_contacts()) {
                foreach ($courseElement->get_course_contacts() as $key => $courseContact) {
                $moonCourseContacts[$key] = new \stdClass();
                $moonCourseContacts[$key]->userId = $courseContact['user']->id;
                $moonCourseContacts[$key]->username = $courseContact['user']->username;
                $moonCourseContacts[$key]->name = $courseContact['user']->firstname . ' ' . $courseContact['user']->lastname;
                $moonCourseContacts[$key]->role = $courseContact['role']->displayname;
                $moonCourseContacts[$key]->profileUrl = new \moodle_url('/user/view.php', array('id' => $courseContact['user']->id, 'course' => SITEID));
                }
            }


        // @moonComm: Process first image
            $theme = Framework::getTheme();
            $contentimages = $CFG->wwwroot . '/theme/'.$theme->name.'/pix/category.jpg';
            foreach ($courseElement->get_course_overviewfiles() as $file) {
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

        /* Map data */
        $moonCourse->courseId = $courseId;
        $moonCourse->enrolments = $courseEnrolmentCount;
        $moonCourse->categoryId = $categoryId;
        $moonCourse->categoryName = $categoryName;
        $moonCourse->categoryUrl = $categoryUrl;
        $moonCourse->shortName = $courseShortName;
        $moonCourse->fullName = format_text($courseFullName, FORMAT_HTML, array('filter' => true));
        $moonCourse->summary = $courseSummary;
        $moonCourse->format = $courseFormat;
        $moonCourse->announcements = $courseAnnouncements;
        $moonCourse->startDate = userdate($courseStartDate, get_string('strftimedatefullshort', 'langconfig'));
        $moonCourse->endDate = userdate($courseEndDate, get_string('strftimedatefullshort', 'langconfig'));
        $moonCourse->visible = $courseVisible;
        $moonCourse->created = userdate($courseCreated, get_string('strftimedatefullshort', 'langconfig'));
        $moonCourse->updated = userdate($courseUpdated, get_string('strftimedatefullshort', 'langconfig'));
        $moonCourse->requested = $courseRequested;
        $moonCourse->enrolmentLink = $enrolmentLink;
        $moonCourse->url = $courseUrl;
        $moonCourse->teachers = $moonCourseContacts;
        $moonCourse->course_price = $course_price;
        $moonCourse->course_currency = $course_currency;
        $moonCourse->course_is_enrolled = $course_is_enrolled;

        /* Render object */
        $moonRender = new \stdClass();
        $moonRender->enrolmentIcon = '';
        $moonRender->enrolmentIcon1 = '';
        $moonRender->announcementsIcon     =     '';
        $moonRender->announcementsIcon1     =     '';
        $moonRender->updatedDate           =     '';
        $moonRender->updatedDate         =     userdate($courseUpdated, get_string('strftimedatefullshort', 'langconfig'));
        $moonRender->title             =     '<h3><a href="'. $moonCourse->url .'">'. $moonCourse->fullName .'</a></h3>';
        $moonRender->coverImage = '<img class="img-whp" src="'. $contentimages .'" alt="'.strip_tags($moonCourse->fullName).'">';
        $moonRender->ImageUrl = $contentimages;
        /* @moonBreak */
        $moonCourse->moonRender = $moonRender;
        return $moonCourse;
        }
        return null;
    }

    public function moonGetCourseDescription($courseId, $maxLength){
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;
    
        if ($DB->record_exists('course', array('id' => $courseId))) {
        $chelper = new \coursecat_helper();
        $courseContext = \context_course::instance($courseId);
    
        $courseRecord = $DB->get_record('course', array('id' => $courseId));
        $courseElement = new \core_course_list_element($courseRecord);
    
        if ($courseElement->has_summary()) {
            $courseSummary = $chelper->get_course_formatted_summary($courseElement, array('noclean' => false, 'para' => false));
            if($maxLength != null) {
            if (strlen($courseSummary) > $maxLength) {
                $courseSummary = wordwrap($courseSummary, $maxLength);
                $courseSummary = substr($courseSummary, 0, strpos($courseSummary, "\n")) . '...';
            }
            }
            return $courseSummary;
        }
    
        }
        return null;
    }

    public function moonListCategories(){
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

    public function moonGetCategoryDetails($categoryId){
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;
    
        if ($DB->record_exists('course_categories', array('id' => $categoryId))) {
    
        $categoryRecord = $DB->get_record('course_categories', array('id' => $categoryId));
    
        $chelper = new \coursecat_helper();
        $categoryObject = \core_course_category::get($categoryId);
    
        $moonCategory = new \stdClass();
    
        $categoryId = $categoryRecord->id;
        $categoryName = format_text($categoryRecord->name, FORMAT_HTML, array('filter' => true));
        $categoryDescription = $chelper->get_category_formatted_description($categoryObject);
    
        $categorySummary = format_string($categoryRecord->description, $striplinks = true,$options = null);
        $isVisible = $categoryRecord->visible;
        $categoryUrl = $CFG->wwwroot . '/course/index.php?categoryid=' . $categoryId;
        $categoryCourses = $categoryObject->get_courses();
        $categoryCoursesCount = count($categoryCourses);
    
        $categoryGetSubcategories = [];
        $categorySubcategories = [];
        if (!$chelper->get_categories_display_option('nodisplay')) {
            $categoryGetSubcategories = $categoryObject->get_children($chelper->get_categories_display_options());
        }
        foreach($categoryGetSubcategories as $k=>$moonSubcategory) {
            $moonSubcat = new \stdClass();
            $moonSubcat->id = $moonSubcategory->id;
            $moonSubcat->name = $moonSubcategory->name;
            $moonSubcat->description = $moonSubcategory->description;
            $moonSubcat->depth = $moonSubcategory->depth;
            $moonSubcat->coursecount = $moonSubcategory->coursecount;
            $categorySubcategories[$moonSubcategory->id] = $moonSubcat;
        }
    
        $categorySubcategoriesCount = count($categorySubcategories);
    
        /* Do image */
        $outputimage = '';
        //moonComm: Fetching the image manually added to the coursecat description via the editor.
        $description = $chelper->get_category_formatted_description($categoryObject);
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
            foreach($categoryCourses as $child_course) {
            if ($child_course === reset($categoryCourses)) {
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
    
        /* Map data */
        $moonCategory->categoryId = $categoryId;
        $moonCategory->categoryName = $categoryName;
        $moonCategory->categoryDescription = $categoryDescription;
        $moonCategory->categorySummary = $categorySummary;
        $moonCategory->isVisible = $isVisible;
        $moonCategory->categoryUrl = $categoryUrl;
        $moonCategory->coverImage = $outputimage;
        $moonCategory->ImageUrl = $outputimage;
        $moonCategory->courses = $categoryCourses;
        $moonCategory->coursesCount = $categoryCoursesCount;
        $moonCategory->subcategories = $categorySubcategories;
        $moonCategory->subcategoriesCount = $categorySubcategoriesCount;
        return $moonCategory;
    
        }
    }

    public function moonGetExampleCategories($maxNum) {
        global $CFG, $DB;
    
        $moonCategories = $DB->get_records('course_categories', array(), $sort='', $fields='*', $limitfrom=0, $limitnum=$maxNum);
    
        $moonReturn = array();
        foreach ($moonCategories as $moonCategory) {
        $moonReturn[] = $this->moonGetCategoryDetails($moonCategory->id);
        }
        return $moonReturn;
    }

    public function moonGetExampleCategoriesIds($maxNum) {
        global $CFG, $DB;
    
        $moonCategories = $this->moonGetExampleCategories($maxNum);
    
        $moonReturn = array();
        foreach ($moonCategories as $key => $moonCategory) {
        $moonReturn[] = $moonCategory->categoryId;
        }
        return $moonReturn;
    }
    public function moonGetCourseprice($courseid) {
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
    public function moonCountstudents($courseid) {
        global $DB;

        $sql = "SELECT COUNT(*)
              FROM {role_assignments} ra
              JOIN {context} ctx ON ra.contextid = ctx.id
             WHERE ctx.contextlevel = 50
               AND ctx.instanceid = :courseid
               AND ra.roleid = 5"; // 5 = student

        return $DB->count_records_sql($sql, ['courseid' => $courseid]);
    }

    function moonCourseSections(int $courseid): int {
        global $DB;

        if ($courseid <= 0) {
            throw new coding_exception('Invalid course id');
        }
        $count = $DB->count_records('course_sections', ['course' => $courseid]);

        return $count;
    }
}
