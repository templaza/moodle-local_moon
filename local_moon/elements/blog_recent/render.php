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
use local_moon\library\Helper\Style;
use local_moon\library\Blocks\BlogHandler;
use local_moon\library\Framework;

$params         = $this->params;
$style = $this->style;
$style_dark = $this->style_dark;
$element = $this;
global $CFG, $PAGE;

require_once($CFG->dirroot. '/course/renderer.php');

$title_style        = $params->get('title_font_style', null);
$meta_style        = $params->get('meta_font_style', null);

$enable_slider      = $params->get('enable_slider', 1);
$autoplay           = $params->get('autoplay', 0);
$navigation         = $params->get('navigation', 0);
$dot                = $params->get('dot', 1);
$category           = $params->get('course_category', '');
$slider_column      = $params->get('slider_column', 'col-lg-4');
$nav_color     = Style::getColor($params->get('navigation_color', ''));
$nav_hover_color     = Style::getColor($params->get('navigation_color_hover', ''));
$nav_bg_color     = Style::getColor($params->get('navigation_bg_color', ''));
$nav_bg_hover_color     = Style::getColor($params->get('navigation_bg_color_hover', ''));

$dot_margin =  $params->get('dot_margin', '');
$meta_margin =  $params->get('meta_margin', '');
$layout =  $params->get('blog_style', '');
$limit =  $params->get('blog_limit', 5);
$show_author =  $params->get('show_author', '');
$show_comment =  $params->get('show_comment', '');
$show_date_create =  $params->get('show_date_create', '');
$image_position =  $params->get('image_position', '');

$row_column_cls     =   '';
$responsive_key     =   ['xxl', 'xl', 'lg', 'md', 'sm', 'xs'];
foreach ($responsive_key as $key) {
    $default        =   match ($key) {
        'xxl', 'xl' =>  '',
        'lg'        =>  3,
        default     =>  1
    };
    $column         =   $params->get($key . '_column', $default);

    if ($key !== 'xs') {
        $row_column_cls     .=  $column ? ' row-cols-'. $key .'-' . $column : '';
        $row_gutter         =   $params->get('row_gutter_'.$key, '');
        $column_gutter      =   $params->get('column_gutter_'. $key, '');

        $row_column_cls .=  ' gy-' . $key . '-' . $row_gutter;
        $row_column_cls .=  ' gx-' . $key . '-' . $column_gutter;

    } else {
        $row_column_cls     .=  $column ? ' row-cols-' . $column : '';
        $row_gutter         =   $params->get('row_gutter', 3);
        $column_gutter      =   $params->get('column_gutter', 3);
        $row_column_cls     .=  ' gy-' . $row_gutter;
        $row_column_cls     .=  ' gx-' . $column_gutter;
    }
}
$media_width_cls    =   '';
$media_position     =   $params->get('image_position', '');
$vertical_middle    =   $params->get('vertical_middle', 0);
foreach ($responsive_key as $device) {
    $default        =   match ($device) {
        'xxl', 'xl' =>  '',
        'lg'        =>  4,
        default     =>  12
    };
    $column_media   =   $params->get($device . '_column_media', $default);
    if ($device === 'xs') {
        $media_width_cls.=  $column_media ? ' col-' . $column_media . ($media_position == 'right' && $column_media < 12 ? ' text-end' : '') : '';
    } else {
        $media_width_cls.=  $column_media ? ' col-' . $device . '-' . $column_media . ($media_position == 'right' && $column_media < 12 ? ' text-'.$device.'-end' : '') : '';
    }
}

$attrs_slider[] = '';
$attrs_slider[] = (  $autoplay  ) ? 'autoplay: 1' : '';
$attrs_slider   = ' data-uk-slider="' . implode( '; ', array_filter( $attrs_slider ) ) . '"';
$now_rap = ' flex-nowrap ';
if(!$enable_slider){
    $attrs_slider='';
    $now_rap = '';
}
$img_cover_container = 'uk-inline';
$img_cover = '';
if ($media_position == 'left') {
    $img_cover = 'data-uk-cover';
    $img_cover_container = ' uk-height-1-1 uk-cover-container';
}
$document = Framework::getDocument();
$document->loadUIKit();

$moonBlog = new BlogHandler();

$blog_since =  $params->get('blog_since', 604800);

$courses = [];
$filter = array();

$filter['since'] = $blog_since;
$text = '';
//$bloglisting = new blog_listing($filter);
//$entries = $bloglisting->get_entries(0, 5);

global $DB;
$since = time() - $blog_since;

$entries = $DB->get_records_sql("
    SELECT *
    FROM {post}
    WHERE created >= ?
    ORDER BY created DESC
", [$since], 0, $limit);

if (!empty($entries)) {
    $viewblogurl = new moodle_url('/blog/index.php');
    $text = '';
    if($layout =='style2'){
        $text .='<div class="moon-blog-area moon-blog-area-style2 ">';
        $text .='<div class="container-blog">';
        $text .='<div class="row  g-4">';
        $i=1;
foreach ($entries as $entryid => $entry):
    $viewblogurl->param('entryid', $entryid);
    $img_url = $moonBlog->moon_get_blog_image_url($entryid);

    $summary = isset($entry->summary) ? strip_tags($entry->summary) : '';
    $words = $summary !== '' ? preg_split('/\s+/', trim($summary)) : [];
    $excerpttxt = !empty($words) ? implode(' ', array_slice($words, 0, 15)) : '';

    $author = core_user::get_user($entry->userid);

    $authorname = fullname($author);
    $authorurl = (new moodle_url('/user/profile.php', ['id' => $author->id]))->out(false);
    if($i==1){
        $text .='<div class="col-lg-6 col-md-12 blog-style2-left">';
        $text .='<div class="moon-blog-card wrap-style">';
        $text .='<div class="image">';
        $text .='<a href="'.$viewblogurl .'">';
        if ($img_url) {
            $text .='<img src="'.$img_url .'" alt="image">';
        }
        $text .='</a>';
        $text .='</div>';

        $text .='<div class="content">';
        $text .='<ul class="meta">';
                 $text .=' <li>
                            '. get_string('blog_date', 'local_moon').'<span> '.userdate($entry->created, '%B %e, %Y', 0).'</span>
                        </li>';
                $text .='    <li>
                    <a href="'. $authorurl.'">'.$authorname.'</a>
                </li>';
                $text .='</ul>';
                $text .='<h3 class="blog-title-left">
                    <a href="'.$viewblogurl.'">'.format_string($entry->subject).'</a>
                </h3>';

                    if ($excerpttxt) {
                        $text .='<div class="blog-description"> <p>'.format_text($excerpttxt, FORMAT_HTML).'</p></div>';
                    }
        $text .='</div>';
        $text .='</div>';
        $text .='</div>';
    }else{
        if($i==2){
            $text .='<div class="col-lg-6 col-md-12 blog-style2-right">';
        }
        $text .='<div class="blog-right-item">';
            $text .='<div class="moon-blog-card row">';
                $text .='<div class="blog-image-box col-md-5"><div class="image">
                    <a href="'. $viewblogurl.'">';
                        if ($img_url) {
                            $text .='<img src="'.$img_url.'" alt="image">';
                        }
                    $text .='</a>';
                $text .='</div></div>';
                $text .='<div class="content col-md-7">
                    <ul class="meta">';
                            if($show_date_create){
                                $text .='<li>
                                '.get_string('blog_date', 'local_moon').' <span> '.userdate($entry->created, '%B %e, %Y', 0).'</span>
                            </li>';
                            }
                            if($show_author){
                                $text .='<li>
                                    <a href="'.$authorurl.'">'.$authorname.'</a>
                                </li>';
                            }

                    $text .='</ul>';
                        $text .='<h3 class="blog-title">
                            <a href="'.$viewblogurl.'">'.format_string($entry->subject).'</a>
                        </h3>';

                    if ($excerpttxt) {
                        $text .='<div class="blog-description"> <p>'.format_text($excerpttxt, FORMAT_HTML).'</p></div>';
                    }
                $text .='</div>';
            $text .='</div>';
        $text .='</div>';
        if($i==count($entries)){
            $text .='</div>';
        }
    }
    $i++;
    endforeach;

    }elseif($layout=='style3'){
        $text .= '<div class="moon-blog-area moon-blog-area-style3">';
        $text .= '<div class=" container p-0 uk-position-relative uk-visible-toggle" tabindex="-1" '.$attrs_slider.'>';
        $text .= '<div class="uk-slider-items row  '.$now_rap.$row_column_cls.'">';
        foreach ($entries as $entryid => $entry) {
            $viewblogurl->param('entryid', $entryid);
            $img_url = $moonBlog->moon_get_blog_image_url($entryid);

            $summary = isset($entry->summary) ? strip_tags($entry->summary) : '';
            $words = $summary !== '' ? preg_split('/\s+/', trim($summary)) : [];
            $excerpttxt = !empty($words) ? implode(' ', array_slice($words, 0, 15)) : '';


            $meta_html = '';
            if($show_author || $show_comment){
                $author = core_user::get_user($entry->userid);

                $authorname = fullname($author);
                $authorurl = (new moodle_url('/user/profile.php', ['id' => $author->id]))->out(false);
                $blog_comment = '';
                $count = $moonBlog->moon_get_blog_comment_count($entryid);
                if ($count !== null) {
                    $blog_comment ='<li><i class="fa-solid fa-comments"></i> '.$count.'</li>';
                }
                $author_html = '<li><i class="fa-solid fa-user"></i><a href="'.$authorurl.'">'.$authorname.'</a></li>';
                $meta_html .= '
                    <ul class="meta">                                                   
                        '.$author_html.'
                        '.$blog_comment.'
                    </ul>
                ';
            }

            $text .='
                    <div class="blog-slider-item blog-item'.$entryid.'">
                        <div class="moon-blog-card">
                    ';
                    if ($media_position == 'left') {
                        $text .= '<div class="row g-0">';
                        $text .= '<div class="'.$media_width_cls.' ">';
                    }
                    if($img_url){
                        $text .='
                            <div class="image '.$img_cover_container.'">
                                <a href="'.$viewblogurl.'">
                                    <img src="'.$img_url.'" '.$img_cover.' alt="image">
                                </a>
                                <span class="uk-position-top-left blog-date uk-flex-center uk-flex-middle uk-flex uk-flex-column">                        
                                <span class="blog-day">'.userdate($entry->created, '%e', 0).'</span>
                                <span class="blog-month">'.userdate($entry->created, '%b', 0).'</span>
                                </span>
                            </div>
                            ';
                    }
                    if ($media_position == 'left') {
                        $text .= '</div>';
                        $text .= '<div class="col order-1 '.($vertical_middle ? ' align-items-center' : '').'">';
                    }

                    $text .='
                            <div class="content">                        
                                <h3 class="blog-title">
                                    <a href="'.$viewblogurl.'">'.format_string($entry->subject).'</a>
                                </h3>
                                <div class="blog-description"> <p>'.format_text($excerpttxt, FORMAT_HTML).'</p></div>                        
                                '.$meta_html.'
                            </div>
                        ';
                    if ($media_position == 'left') {
                        $text .= '</div></div>';
                    }
                    $text .='
                        </div>
                    </div>
                ';
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
    }else{
        $text .= '<div class="moon-blog-area">';
        $text .= '<div class=" container p-0 uk-position-relative uk-visible-toggle" tabindex="-1" '.$attrs_slider.'>';
        $text .= '<div class="uk-slider-items row '.$now_rap.$row_column_cls.'">';
        foreach ($entries as $entryid => $entry) {
            $viewblogurl->param('entryid', $entryid);
            $img_url = $moonBlog->moon_get_blog_image_url($entryid);

            $summary = isset($entry->summary) ? strip_tags($entry->summary) : '';
            $words = $summary !== '' ? preg_split('/\s+/', trim($summary)) : [];
            $excerpttxt = !empty($words) ? implode(' ', array_slice($words, 0, 15)) : '';

            $author = core_user::get_user($entry->userid);

            $authorname = fullname($author);
            $authorurl = (new moodle_url('/user/profile.php', ['id' => $author->id]))->out(false);
            $text .='
                    <div class="blog-slider-item ">
                        <div class="moon-blog-card">
                    ';
            if($img_url){
                $text .='
                    <div class="image">
                        <a href="'.$viewblogurl.'">
                            <img src="'.$img_url.'" alt="image">
                        </a>
                    </div>
                    ';
            }

            $text .='
                    <div class="content">
                        <ul class="meta">
                            <li>
                                '.get_string('blog_date', 'local_moon').' <span>'.userdate($entry->created, '%B %e, %Y', 0).'</span>
                            </li>                        
                            <li><a href="'.$authorurl.'">'.$authorname.'</a>
                            </li>
                        </ul>
                        <h3 class="blog-title">
                            <a href="'.$viewblogurl.'">'.format_string($entry->subject).'</a>
                        </h3>
                        <div class="blog-description"> <p>'.format_text($excerpttxt, FORMAT_HTML).'</p></div>
                    </div>
                ';
            $text .='
                        </div>
                    </div>
                ';
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
    }

} else {
    echo get_string('norecentblogentries', 'block_blog_recent');
}

echo $text;

if (!empty($title_style)) {
    Style::renderTypography('#'.$this->id.' .coursename a', $title_style, null, $this->isRoot);
}
if (!empty($dot_margin)) {
    Style::setSpacingStyle($this->style->child('.uk-dotnav'), $dot_margin, 'margin');
}
if (!empty($meta_style)) {
    Style::renderTypography('#'.$this->id.' .meta li span', $meta_style, null, $this->isRoot);
}
if (!empty($meta_margin)) {
    Style::setSpacingStyle($this->style->child('.meta'), $meta_margin, 'margin');
}
$style->child('.uk-slidenav')->addCss('color', $nav_color['light']);
$style_dark->child('.uk-slidenav')->addCss('color', $nav_color['dark']);
$style->child('.uk-slidenav:hover')->addCss('color', $nav_hover_color['light']);
$style_dark->child('.uk-slidenav:hover')->addCss('color', $nav_hover_color['dark']);

$style->child('.uk-slidenav')->addCss('background-color', $nav_bg_color['light']);
$style_dark->child('.uk-slidenav')->addCss('background-color', $nav_bg_color['dark']);
$style->child('.uk-slidenav:hover')->addCss('background-color', $nav_bg_hover_color['light']);
$style_dark->child('.uk-slidenav:hover')->addCss('background-color', $nav_bg_hover_color['dark']);

$nav_padding   =   $params->get('navigation_padding', '');
if (!empty($nav_padding)) {
    Style::setSpacingStyle($this->style->child('.uk-slidenav'), $nav_padding);
}
$item_content_padding   =   $params->get('item_content_padding', '');
if (!empty($item_content_padding)) {
    Style::setSpacingStyle($this->style->child('.content'), $item_content_padding);
}
$item_padding   =   $params->get('item_padding', '');
if (!empty($item_padding)) {
    Style::setSpacingStyle($this->style->child('.moon-blog-card'), $item_padding);
}
$title_margin   =   $params->get('title_margin', '');
if (!empty($title_margin)) {
    Style::setSpacingStyle($this->style->child('.blog-title'), $title_margin,'margin');
}
$image_margin   =   $params->get('image_margin', '');
if (!empty($image_margin)) {
    Style::setSpacingStyle($this->style->child('.image'), $image_margin,'margin');
}

$item_bg_color     = Style::getColor($params->get('item_bg_color', ''));

$style->child('.moon-blog-card')->addCss('background-color', $item_bg_color['light']);
$style_dark->child('.moon-blog-card')->addCss('background-color', $item_bg_color['dark']);
$item_border    =   json_decode($params->get('item_border', ''), true);
if (!empty($item_border)) {
    Style::addBorderStyle('#'. $element->id . ' .moon-blog-card', $item_border, 'global', $element->isRoot);
}
$item_radius=   $params->get('item_border_radius', '');
if (!empty($item_radius)) {
    Style::setSpacingStyle($element->style->child('.moon-blog-card'), $item_radius,'radius');
}
$image_border_radius=   $params->get('image_border_radius', '');
if (!empty($image_border_radius)) {
    Style::setSpacingStyle($element->style->child('.image'), $image_border_radius,'radius');
}
$show_content           = $params->get('show_content', 1);
if($show_content){
    $content_margin   =   $params->get('content_margin', '');
    if (!empty($content_margin)) {
        Style::setSpacingStyle($this->style->child('.blog-description'), $content_margin,'margin');
    }
    $content_style        = $params->get('content_font_style', null);
    if (!empty($content_style)) {
        Style::renderTypography('#'.$this->id.' .blog-description', $content_style, null, $this->isRoot);
    }
}
$min_height      =   $params->get('image_min_height', 300);
$min_height = json_decode($min_height, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($min_height)) {
    $element->style->child('.image')->addResponsiveCSS('height', $min_height, $min_height['postfix']);
}