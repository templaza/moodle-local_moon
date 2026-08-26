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

defined('MOODLE_INTERNAL') || die;
use local_moon\library\helper\style;
use local_moon\library\blocks\course_handler;
use local_moon\library\framework;

$params         = $this->params;
$style = $this->style;
$style_dark = $this->style_dark;

global $CFG, $PAGE, $DB;

require_once($CFG->dirroot. '/course/renderer.php');

$course_limit       = $params->get('course_limit', 5);
$title_style        = $params->get('title_font_style', null);
$autoplay           = $params->get('autoplay', 0);
$navigation         = $params->get('navigation', 0);
$dot                = $params->get('dot', 1);
$category           = $params->get('course_category', '');
$slider_column      = $params->get('slider_column', 'col-lg-4');
$layout             = $params->get('layout', 'style1');
$tab_margin             = $params->get('tab_margin', '');

$nav_color     = style::get_color($params->get('navigation_color', ''));
$nav_hover_color     = style::get_color($params->get('navigation_color_hover', ''));
$nav_bg_color     = style::get_color($params->get('navigation_bg_color', ''));
$nav_bg_hover_color     = style::get_color($params->get('navigation_bg_color_hover', ''));

$dot_margin =  $params->get('dot_margin', '');

$attrs_slider[] = '';
$attrs_slider[] = (  $autoplay  ) ? 'autoplay: 1' : '';
$attrs_slider   = ' data-uk-slider="' . implode( '; ', array_filter( $attrs_slider ) ) . '"';

$document = framework::get_document();
$document->load_ui_kit();

$categories = json_decode($category, true);

$moonCourseHandler = new course_handler();

$courses = [];

foreach ($categories as $cat) {
    $category = core_course_category::get($cat['value']);
    $courses += $category->get_courses();
}
$layout_cl  = '';
$text = '';
$text .= '
        <div class="education-courses-area ptb-140 '.$layout_cl.'">
            <div class="container">               
                <div class="education-courses-tabs" data-cues="slideInUp" data-duration="1000">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">';
$i = 0;
foreach ($categories as $cat) {
    $catkey = 'cat-'.$cat['value'];
    if($i == 0) {
        $activeClass = 'active';
        $ariaSelected = 'true';
    } else {
        $activeClass = '';
        $ariaSelected = 'false';
    }
    $text .='
                                <li class="nav-item">
                                    <a class="nav-link '.$activeClass.'"
                                    id="'.$catkey.'-tab"
                                    data-toggle="tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#'.$catkey.'"
                                    role="tab"
                                    aria-controls="'.$catkey.'"
                                    aria-selected="'.$ariaSelected.'">
                                        '.$cat['text'].'
                                    </a>
                                </li>
                            ';
    $i++;
}
$text .='
                    </ul>
                    <div class="tab-content" id="myTabContent">';
$i = 0;
foreach ($categories as $cat) {
    $catkey = 'cat-' . $cat['value'];
    if($i == 0){
        $activeClass = 'show active';
    } else {
        $activeClass = '';
    }
    $text .= '
                            <div class="tab-pane fade '.$activeClass.'" id="' . $catkey . '" role="tabpanel" aria-labelledby="'.$catkey.'-tab">
                                <div class="courses_tab_content">
                                    <div class="courses_item course_category_tree">';
    if($layout == 'style2'){
        $text .= '<div class="course-filter-style2 uk-position-relative uk-visible-toggle" tabindex="-1" '.$attrs_slider.'>';
        $text .= '<div class="uk-slider-items uk-child-width-1-2@s uk-child-width-1-3@l uk-grid uk-grid-medium">';
        $i = 1;
        foreach ($courses as $course) {
            if ($course->category == $cat['value'] && $DB->record_exists('course', array('id' => $course->id))) {
                $moonCourseHandler = new course_handler();
                $moonCourse = $moonCourseHandler->moon_get_course_details($course->id);
                $courseDescriptionRaw = $moonCourseHandler->moon_get_course_description($course->id, 5000);
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
                    <div class="course-slider-item">
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
                $studentcount = $moonCourseHandler->moon_countstudents($course->id);
                $sectioncount = $moonCourseHandler->moon_course_sections($course->id);
                $text .= '<div class="course-middle">';
                $text .= '<div class="course-learners course-middle-left"><i class="fa-solid fa-users"></i>'.$studentcount .' '. get_string('course-learners', 'local_moon').'</div>';
                $text .= '<div class="course-lectures"><i class="fa-solid fa-file-lines"></i>'.$sectioncount .' '. get_string('course-lectures', 'local_moon').'</div>';
                $text .= '</div>';
                $cp = $moonCourseHandler->moon_get_courseprice($course->id);
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
            }
            $i++;
        }
        $text .= '</div>';
        if($navigation){
            $text .= '<a class="uk-position-center-left uk_slider_preview uk-position-small uk-hidden-hover" href data-uk-slidenav-previous data-uk-slider-item="previous"></a>
        <a class="uk-position-center-right uk_slider_next  uk-position-small uk-hidden-hover" href data-uk-slidenav-next data-uk-slider-item="next"></a>';
        }
        if($dot){
            $text .= '<ul class="uk-slider-nav uk-dotnav uk-flex-center"></ul>';
        }

        $text .='</div>';
    }else{
        $text .='<div class="row justify-content-center g-4">';
        $i = 1;
        foreach ($courses as $course) {
            if ($course->category == $cat['value'] && $DB->record_exists('course', array('id' => $course->id))) {
                $moonCourseHandler = new course_handler();
                $moonCourse = $moonCourseHandler->moon_get_course_details($course->id);
                $courseDescriptionRaw = $moonCourseHandler->moon_get_course_description($course->id, 5000);
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
                                                    <div class="col-xl-4 col-md-6">
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
                $studentcount = $moonCourseHandler->moon_countstudents($course->id);
                $sectioncount = $moonCourseHandler->moon_course_sections($course->id);
                $text .= '<div class="course-middle">';
                $text .= '<div class="course-learners course-middle-left"><i class="fa-solid fa-users"></i>'.$studentcount .' '. get_string('course-learners', 'local_moon').'</div>';
                $text .= '<div class="course-lectures"><i class="fa-solid fa-file-lines"></i>'.$sectioncount .' '. get_string('course-lectures', 'local_moon').'</div>';
                $text .= '</div>';
                $cp = $moonCourseHandler->moon_get_courseprice($course->id);
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
            }
            $i++;
        }
        $text .='</div>';
    }

    $text .= '

                                    </div>
                                </div>
                            </div>';
    $i++;
}
$text .= '
                    </div>
                </div>                
            </div>
        </div>';

echo $text;

if (!empty($title_style)) {
    style::render_typography('#'.$this->id.' .coursename a', $title_style, null, $this->isRoot);
}
if (!empty($dot_margin)) {
    style::set_spacing_style($this->style->child('.uk-dotnav'), $dot_margin, 'margin');
}
$style->child('.uk-slidenav')->add_css('color', $nav_color['light']);
$style_dark->child('.uk-slidenav')->add_css('color', $nav_color['dark']);
$style->child('.uk-slidenav:hover')->add_css('color', $nav_hover_color['light']);
$style_dark->child('.uk-slidenav:hover')->add_css('color', $nav_hover_color['dark']);

$style->child('.uk-slidenav')->add_css('background-color', $nav_bg_color['light']);
$style_dark->child('.uk-slidenav')->add_css('background-color', $nav_bg_color['dark']);
$style->child('.uk-slidenav:hover')->add_css('background-color', $nav_bg_hover_color['light']);
$style_dark->child('.uk-slidenav:hover')->add_css('background-color', $nav_bg_hover_color['dark']);

$nav_padding   =   $params->get('navigation_padding', '');
if (!empty($nav_padding)) {
    style::set_spacing_style($this->style->child('.uk-slidenav'), $nav_padding);
}

if (!empty($tab_margin)) {
    style::set_spacing_style($this->style->child('.nav-tabs'), $tab_margin, 'margin');
}