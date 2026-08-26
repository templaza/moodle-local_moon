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
use local_moon\library\framework;
use local_moon\library\helper\style;
use local_moon\library\helper\sub_form;
use local_moon\library\blocks\event_handler;

$params = $this->params;
$style = $this->style;
$style_dark = $this->style_dark;
$document = framework::get_document();
$document->load_ui_kit();
$list_events     = new sub_form($params->get('list_events', ''));

if (!count($list_events->get_data())) {
    return false;
}
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
$content_hover_transition     = $params->get('media_hover_transition', '');

$enable_image_cover =   $params->get('enable_image_cover', 0);

$event_layout   =   $params->get('event_layout','');
$button_text   =   $params->get('button_text','');
$button_icon   =   $params->get('button_icon','');
$button_align   =   $params->get('button_align','');

$image_layout   =   $params->get('image_layout');
$image_height      =   $params->get('image_height', '');
$image_height_data = json_decode($image_height, true);

$overlay_html = $img_attr= $content_position= $start_icon = $end_icon=$img_transition=$toggle='';
if($image_layout=='overlay'){
    $overlay_html = '<div class="moon-bg-overlay uk-position-cover"></div>';
    $content_position     = 'uk-overlay '. $params->get('content_position', '').'';
}
if($image_height_data['global'] !='' || $image_height_data['larger_desktop'] !=''  || $image_height_data['desktop'] !=''  || $image_height_data['tablet'] !=''  || $image_height_data['mobile'] !='' ){
    $img_attr = 'data-uk-cover';
}
if ($params->get('start_icon', '')) {
    $start_icon      = '<i class="'.$params->get('start_icon', '').'"></i>';
}
if ($params->get('end_icon', '')) {
    $end_icon      = '<i class="'.$params->get('end_icon', '').'"></i>';
}
$image_hover_transition      =   $params->get('image_hover_transition', '');
if($image_hover_transition !=''){
    $toggle = ' uk-transition-toggle';
    $img_transition = ' uk-transition-scale-up uk-transition-opaque';
}
$event_cls = 'row '.$row_column_cls.'';
if($event_layout=='list'){
    $event_cls = ' event-element';
}
echo '<div class="'.$event_cls.'">';
foreach ($list_events->data as $key => $grid) {
    $media          =   '';
    global $DB;
    $eventid = $grid->params->get('event', '');
    if($eventid){
        $event = $DB->get_record('event', ['id' => $eventid]);
        $moonEventHandler = new event_handler();
        $url = $moonEventHandler->moon_get_event_link($eventid);
    }
    if ($grid->params->get('image', '')) {
        $media      = '<div class="event-media  uk-position-relative uk-overflow-hidden">';
        $media     .= '<a href="'.$url.'"><img '.$img_attr.' class="tz-img-grid '.$img_transition.'" src="'. $grid->params->get('image', '') .'" alt="'.$event->name.'"></a>';
        $media     .= '</div>';
    }
    if($event_layout=='list'){
        echo '<div id="event-'. $grid -> id .'" class="moon-event">
        <div class="event-item row uk-flex uk-flex-middle">';
        if($eventid){
            $date = userdate($event->timestart);
            $timestamp = strtotime($date);
            $day = date('d', $timestamp);

            echo '<div class="col-md-2 text-md-center">';
            echo '<div class="start-day">' .$day. '</div>';
            echo '<div class="start-month">' .date('F, Y', $timestamp). '</div>';
            echo '</div>';
            echo '<div class="col-md-8 event-summary">';
            echo '<h3 class="event-title"><a href="'.$url.'">' .$event->name.'</a></h3>';
            echo '<div class="event-end event-duration uk-flex uk-flex-middle">'.$end_icon.' ' . userdate($event->timestart + $event->timeduration).'</div>';
            echo '</div>';
            if($button_text || $button_icon){
                echo '<div class="col-md-2 '.$button_align.' ">';
                echo '<div class="event-btn"><a class="event-readmore uk-inline-block" href="'.$url.'">' .$button_text.' <i class="'.$button_icon.'"></i></a></div>';
                echo '</div>';
            }

            echo '</div></div>';
        }
    }else{
        echo '<div id="event-'. $grid -> id .'" class="moon-event "><div class="event-item uk-overflow-hidden uk-position-relative '.$toggle.'">';
        echo $media;
        if($eventid){
            echo $overlay_html;
            echo '<div class="event-summary '.$content_position.'"> <h3 class="event-title"><a href="'.$url.'">' .$event->name.'</a></h3>';
            echo '<div class="event-duration"><div class="event-start uk-flex uk-flex-middle">'.$start_icon.' ' . userdate($event->timestart).'</div>';
            echo '<div class="event-end uk-flex uk-flex-middle">'.$end_icon.' ' . userdate($event->timestart + $event->timeduration).'</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div></div>';
    }

}
echo '</div>';

$item_bg_color     = style::get_color($params->get('item_bg_color', ''));
$style->child('.event-item')->add_css('background-color', $item_bg_color['light']);
$style_dark->child('.event-item')->add_css('background-color', $item_bg_color['dark']);
$item_border    =   json_decode($params->get('item_border', ''), true);
if (!empty($item_border)) {
    style::add_border_style('#'. $this->id . ' .event-item', $item_border, 'global', $this->isRoot);
}
$item_border_radius      =   $params->get('item_border_radius', '');
if (!empty($item_border_radius)) {
    style::set_spacing_style($this->style->child('.event-item'), $item_border_radius,'radius');
}
$item_card_padding   =   $params->get('item_card_padding', '');
if (!empty($item_card_padding)) {
    style::set_spacing_style($this->style->child('.event-item'), $item_card_padding);
}
$content_padding   =   $params->get('content_padding', '');
if (!empty($content_padding)) {
    style::set_spacing_style($this->style->child('.event-summary'), $content_padding);
}
$overlay_type       =   $params->get('overlay_type', '');
switch ($overlay_type) {
    case 'color':
        $overlay_color      =   style::get_color($params->get('overlay_color', ''));
        $style->child('.moon-bg-overlay')->add_css('background-color', $overlay_color['light']);
        $style_dark->child('.moon-bg-overlay')->add_css('background-color', $overlay_color['dark']);
        break;
    case 'gradient':
        $overlay_gradient   =   $params->get('overlay_gradient', '');
        if (!empty($overlay_gradient)) {
            $style->child('.moon-bg-overlay')->add_css('background-image', style::get_gradient_value($overlay_gradient));
        }
        break;
}

$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    style::render_typography('#'.$this->id.' .event-title a', $title_font_style, null, $this->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');
if (!empty($title_heading_margin)) {
    style::set_spacing_style($this->style->child('.event-title'), $title_heading_margin, 'margin');
}
$duration_font_style   =   $params->get('duration_font_style');
if (!empty($duration_font_style)) {
    style::render_typography('#'.$this->id.' .event-duration', $duration_font_style, null, $this->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');
if (!empty($title_heading_margin)) {
    style::set_spacing_style($this->style->child('.event-title'), $title_heading_margin, 'margin');
}
$image_radius      =   $params->get('image_radius', '');
if (!empty($image_radius)) {
    style::set_spacing_style($this->style->child('.event-media'), $image_radius,'radius');
}
if (json_last_error() === JSON_ERROR_NONE && is_array($image_height_data)) {
    $style->child('.event-media')->add_responsive_css('height', $image_height_data, $image_height_data['postfix']);
}
$icon_size        =   $params->get('icon_size', '12');
$icon_size = json_decode($icon_size, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $style->child('.event-duration i')->add_responsive_css('font-size', $icon_size, $icon_size['postfix']);
}
$icon_margin   =   $params->get('icon_margin', '');
if (!empty($icon_margin)) {
    style::set_spacing_style($style->child('.event-duration i'), $icon_margin,'margin');
}

$button_color     = style::get_color($params->get('button_color', ''));
$button_hover_color     = style::get_color($params->get('button_color_hover', ''));
$button_bg_color     = style::get_color($params->get('button_bg_color', ''));
$button_bg_hover_color     = style::get_color($params->get('button_bg_color_hover', ''));

$style->child('.event-readmore')->add_css('color', $button_color['light']);
$style_dark->child('.event-readmore')->add_css('color', $button_color['dark']);
$style->child('.event-readmore:hover')->add_css('color', $button_hover_color['light']);
$style_dark->child('.event-readmore:hover')->add_css('color', $button_hover_color['dark']);

$style->child('.event-readmore')->add_css('background-color', $button_bg_color['light']);
$style_dark->child('.event-readmore')->add_css('background-color', $button_bg_color['dark']);
$style->child('.event-readmore:hover')->add_css('background-color', $button_bg_hover_color['light']);
$style_dark->child('.event-readmore:hover')->add_css('background-color', $button_bg_hover_color['dark']);

$button_padding   =   $params->get('button_padding', '');
if (!empty($button_padding)) {
    style::set_spacing_style($this->style->child('.event-readmore'), $button_padding);
}
$button_margin   =   $params->get('button_margin', '');
if (!empty($button_margin)) {
    style::set_spacing_style($this->style->child('.event-readmore'), $button_margin,'margin');
}

$button_radius=  $params->get('button_radius', '');
if (!empty($button_radius)) {
    style::set_spacing_style($this->style->child('.event-readmore'), $button_radius, 'radius');
}