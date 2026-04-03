<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\Style;
use local_moon\library\Blocks\CourseHandler;
use local_moon\library\Framework;

$params         = $this->params;
$style = $this->style;
$style_dark = $this->style_dark;
$element = $this;
global $CFG, $PAGE;

require_once($CFG->dirroot. '/course/renderer.php');

$course_limit       = $params->get('course_limit', 5);
$title_style        = $params->get('title_font_style', null);
$autoplay           = $params->get('autoplay', 0);
$navigation         = $params->get('navigation', 0);
$dot                = $params->get('dot', 1);
$slider_column      = $params->get('slider_column', 'col-lg-4');

$dot_margin =  $params->get('dot_margin', '');
$item_padding =  $params->get('item_padding', '');
$course_style =  $params->get('course_style', '');

$attrs_slider[] = '';
$attrs_slider[] = (  $autoplay  ) ? 'autoplay: 1' : '';
$attrs_slider   = ' data-uk-slider="' . implode( '; ', array_filter( $attrs_slider ) ) . '"';

$document = Framework::getDocument();
$document->loadUIKit();

$moonCourseHandler = new CourseHandler();

$courses = $moonCourseHandler->moonCoursePopular($course_limit);

if (empty($courses)) {
   echo  get_string('nocourses', 'local_moon');
}
$text = '';
$text .= '<div class="courses_item course_category_tree '.$course_style.'">';
$text .= '<div class="course-filter-style2 container p-0 uk-position-relative uk-visible-toggle" tabindex="-1" '.$attrs_slider.'>';
$text .= '<div class="uk-slider-items row flex-nowrap">';
$j=1;
if($course_style=='style2'){
    foreach ($courses as $course) {
        $moonCourse = $moonCourseHandler->moonGetCourseDetails($course->id);
        $courseDescriptionRaw = $moonCourseHandler->moonGetCourseDescription($course->id, 5000);
        $wordsArray = explode(' ', strip_tags($courseDescriptionRaw ?? ''));
        $first20Words = array_slice($wordsArray, 0, 20);
        $moonCourseDescription = implode(' ', $first20Words);

        // Rating Control
        $filepath = $CFG->dirroot . '/admin/tool/courserating/version.php';
        if (file_exists($filepath) && !empty($course->id) && is_int($course->id)) {
            $tool_courserating = $PAGE->get_renderer('tool_courserating');
            $rating_block = $tool_courserating->course_rating_block($course->id);
        } else {
            $rating_block = '';
        }
        $cp = $moonCourseHandler->moonGetCourseprice($course->id);
        $cp_badge = '';
        if ($cp->hascourseprice) {
            $cp_badge = '<span class="cp badge-price uk-position-bottom-left">'.$cp->courseprice->currency.' '.number_format($cp->courseprice->cost, 2, '.', '').'</span>';
        }else{
            $cp_badge = '<span class="cp badge-price uk-position-bottom-left">'.get_string('course-free', 'local_moon').'</span>';
        }

        $text .= '
                <div class="course-slider-item course'.$j.' '.$slider_column.' col-md-6">
                    <div class="coursebox grid">
                        <div class="education-courses-card content">
                            <div class="image uk-position-relative">
                                <a class="image_course_url" href="'. $moonCourse->url .'">
                                    '.$moonCourse->moonRender->coverImage.'
                                </a>
                                '.$cp_badge.'
                            </div>
                            <div class="summary">
                                <div class="coursecat">
                                <span class="categoryname text-truncate">'.$moonCourse->categoryName.'</span>
                                </div>
                                <h3 class="coursename text-left">
                                    <a href="'. $moonCourse->url .'">'.$moonCourse->fullName.'</a>
                                </h3>';
        $text .='<div class="course-description">'.$moonCourseDescription.'</div>';

        $studentcount = $moonCourseHandler->moonCountstudents($course->id);
        $sectioncount = $moonCourseHandler->moonCourseSections($course->id);
        $text .= '<div class="course-middle">';
        $text .= '<div class="course-learners course-middle-left"><i class="fa-solid fa-users"></i>'.$studentcount .' '. get_string('course-learners', 'local_moon').'</div>';
        $text .= '<div class="course-lectures"><i class="fa-solid fa-file-lines"></i>'.$sectioncount .' '. get_string('course-lectures', 'local_moon').'</div>';
        $text .= '</div>';


        if ($cp->hascourseprice || $moonCourse->teachers) {
            $text .= '<div class="course-info-bottom">';
            $text .= '<div class="teachers">';
            $current_role = '';
            $i = 0;
            $teachers = $moonCourse->teachers;
            foreach ($teachers as $teacher) {
                $userid = $teacher->userId;
                $user = \core_user::get_user($userid);
                $userpicture = new \user_picture($user);
                $userpicture->size = 100;
                $avatar_url = $userpicture->get_url($PAGE)->out();
                $avatar='';
                if ($i == 0) {
                    $current_role = $teacher->role;
                    $text .= '<span class="role_type">'.$current_role.': </span>';
                    $name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $teacher->name);
                    if($avatar_url){
                        $avatar = html_writer::tag('img', '', array(
                            'src' => $avatar_url,
                            'alt' => $teacher->name,
                            'class' => 'teacher-avatar rounded-full'
                        ));
                    }
                    $text .= $avatar.$name;
                }
                if (($i > 0) AND ($teacher->role == $current_role)) {
                    $text .= ', ';
                    $name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $teacher->name);
                    if($avatar_url){
                        $avatar = html_writer::tag('img', '', array(
                            'src' => $avatar_url,
                            'alt' => $teacher->name,
                            'class' => 'teacher-avatar rounded-full'
                        ));
                    }
                    $text .= $avatar.$name;
                }
                else if ($i > 0) {
                    $text .= '</div>';
                    $text .= '<div class="teachers">';
                    $current_role = $teacher->role;
                    $text .= '<span class="role_type">'.$current_role.': </span>';
                    $name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $teacher->name);
                    if($avatar_url){
                        $avatar = html_writer::tag('img', '', array(
                            'src' => $avatar_url,
                            'alt' => $teacher->name,
                            'class' => 'teacher-avatar rounded-full'
                        ));
                    }
                    $text .= $avatar.$name;
                }
                $i++;
            }


            $text .= '</div>'; // .teachers
            $text .= '</div>';
        }

        $text .= '                                                                    
                                                                    
                </div>
            </div>
            
        </div>
    </div>';
        $j++;
    }
}else{
    foreach ($courses as $course) {
        $moonCourse = $moonCourseHandler->moonGetCourseDetails($course->id);
        $courseDescriptionRaw = $moonCourseHandler->moonGetCourseDescription($course->id, 5000);
        $wordsArray = explode(' ', strip_tags($courseDescriptionRaw ?? ''));
        $first20Words = array_slice($wordsArray, 0, 15);
        $moonCourseDescription = implode(' ', $first20Words);

        // Rating Control
        $filepath = $CFG->dirroot . '/admin/tool/courserating/version.php';
        if (file_exists($filepath) && !empty($course->id) && is_int($course->id)) {
            $tool_courserating = $PAGE->get_renderer('tool_courserating');
            $rating_block = $tool_courserating->course_rating_block($course->id);
        } else {
            $rating_block = '';
        }

        $text .= '
                <div class="course-slider-item course'.$j.' '.$slider_column.' col-md-6">
                    <div class="coursebox grid">
                        <div class="education-courses-card content">
                            <div class="image">
                                <a class="image_course_url" href="'. $moonCourse->url .'">
                                    '.$moonCourse->moonRender->coverImage.'
                                </a>
                                
                            </div>
                            <div class="summary">
                                <div class="coursecat">
                                <span class="categoryname text-truncate">'.$moonCourse->categoryName.'</span>
                                </div>
                                <h3 class="coursename text-left">
                                    <a href="'. $moonCourse->url .'">'.$moonCourse->fullName.'</a>
                                </h3>';
        $text .='<div class="course-description">'.$moonCourseDescription.'</div>';

        $studentcount = $moonCourseHandler->moonCountstudents($course->id);
        $sectioncount = $moonCourseHandler->moonCourseSections($course->id);
        $text .= '<div class="course-middle">';
        $text .= '<div class="course-learners course-middle-left"><i class="fa-solid fa-users"></i>'.$studentcount .' '. get_string('course-learners', 'local_moon').'</div>';
        $text .= '<div class="course-lectures"><i class="fa-solid fa-file-lines"></i>'.$sectioncount .' '. get_string('course-lectures', 'local_moon').'</div>';
        $text .= '</div>';
        $cp = $moonCourseHandler->moonGetCourseprice($course->id);
        $cp_badge = '';
        if ($cp->hascourseprice) {
            $cp_badge = '<span class="cp badge-price">'.$cp->courseprice->currency.' '.number_format($cp->courseprice->cost, 2, '.', '').'</span>';
        }else{
            $cp_badge = '<span class="cp badge-price">'.get_string('course-free', 'local_moon').'</span>';
        }
        if ($cp->hascourseprice || $moonCourse->teachers) {
            $text .= '<div class="course-info-bottom">';
            $text .= '<div class="teachers">';
            $current_role = '';
            $i = 0;
            $teachers = $moonCourse->teachers;
            foreach ($teachers as $teacher) {
                $userid = $teacher->userId;
                $user = \core_user::get_user($userid);
                $userpicture = new \user_picture($user);
                $userpicture->size = 100;
                $avatar_url = $userpicture->get_url($PAGE)->out();
                $avatar='';
                if ($i == 0) {
                    $current_role = $teacher->role;
                    $text .= '<span class="role_type">'.$current_role.': </span>';
                    $name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $teacher->name);
                    if($avatar_url){
                        $avatar = html_writer::tag('img', '', array(
                            'src' => $avatar_url,
                            'alt' => $teacher->name,
                            'class' => 'teacher-avatar rounded-full'
                        ));
                    }
                    $text .= $avatar.$name;
                }
                if (($i > 0) AND ($teacher->role == $current_role)) {
                    $text .= ', ';
                    $name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $teacher->name);
                    if($avatar_url){
                        $avatar = html_writer::tag('img', '', array(
                            'src' => $avatar_url,
                            'alt' => $teacher->name,
                            'class' => 'teacher-avatar rounded-full'
                        ));
                    }
                    $text .= $avatar.$name;
                }
                else if ($i > 0) {
                    $text .= '</div>';
                    $text .= '<div class="teachers">';
                    $current_role = $teacher->role;
                    $text .= '<span class="role_type">'.$current_role.': </span>';
                    $name = html_writer::link(new moodle_url('/user/view.php', array('id' => $userid, 'course' => SITEID)), $teacher->name);
                    if($avatar_url){
                        $avatar = html_writer::tag('img', '', array(
                            'src' => $avatar_url,
                            'alt' => $teacher->name,
                            'class' => 'teacher-avatar rounded-full'
                        ));
                    }
                    $text .= $avatar.$name;
                }
                $i++;
            }


            $text .= '</div>'; // .teachers


            $text .= $cp_badge;

            $text .= '</div>';
        }

        $text .= '                                                                    
                                                                    
                </div>
            </div>
            
        </div>
    </div>';
        $j++;
    }
}


$text .= '</div>';
if($navigation){
    $text .= '<a class="uk-position-center-left uk_slider_preview uk-position-small uk-hidden-hover" href data-uk-slidenav-previous data-uk-slider-item="previous"></a>
        <a class="uk-position-center-right uk_slider_next  uk-position-small uk-hidden-hover" href data-uk-slidenav-next data-uk-slider-item="next"></a>';
}
if($dot){
    $text .= '<ul class="uk-slider-nav uk-dotnav uk-flex-center"></ul>';
}
$text .= '</div>';
$text .= '</div>';

echo $text;

if (!empty($title_style)) {
    Style::renderTypography('#'.$this->id.' .coursename a', $title_style, null, $this->isRoot);
}
if (!empty($dot_margin)) {
    Style::setSpacingStyle($this->style->child('.uk-dotnav'), $dot_margin, 'margin');
}
if (!empty($item_padding)) {
    Style::setSpacingStyle($this->style->child('.uk-slider-items > *'), $item_padding, );
}
$item_card_padding   =   $params->get('item_card_padding', '');
if (!empty($item_card_padding)) {
    Style::setSpacingStyle($this->style->child('.education-courses-card'), $item_card_padding);
}
$content_padding   =   $params->get('content_padding', '');
if (!empty($content_padding)) {
    Style::setSpacingStyle($this->style->child('.summary'), $content_padding);
}
$content_margin   =   $params->get('content_margin', '');
if (!empty($content_margin)) {
    Style::setSpacingStyle($this->style->child('.summary'), $content_margin,'margin');
}
$item_bg_color     = Style::getColor($params->get('item_bg_color', ''));

$style->child('.education-courses-card')->addCss('background-color', $item_bg_color['light']);
$style_dark->child('.education-courses-card')->addCss('background-color', $item_bg_color['dark']);
$item_border    =   json_decode($params->get('item_border', ''), true);
if (!empty($item_border)) {
    Style::addBorderStyle('#'. $element->id . ' .education-courses-card', $item_border, 'global', $element->isRoot);
}
$item_radius=   $params->get('item_border_radius', '');
if (!empty($item_radius)) {
    Style::setSpacingStyle($element->style->child('.education-courses-card'), $item_radius,'radius');
}
$image_radius=   $params->get('image_radius', '');
if (!empty($image_radius)) {
    Style::setSpacingStyle($element->style->child('.image_course_url'), $image_radius,'radius');
    Style::setSpacingStyle($element->style->child('.image_course_url img'), $image_radius,'radius');
}