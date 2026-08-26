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
$params = $this->params;
$element = $this;

$slides     = new sub_form($params->get('slides', ''));
if (!count($slides->get_data())) {
    return false;
}

$document = framework::get_document();
$style = $element->style;
$style_dark = $element->style_dark;

$media_position     =   $params->get('media_position', 'top');

$min_height         =   $params->get('min_height', '');
$slider_height      =   $params->get('slider_height', '');
$slider_nav_position      =   $params->get('slider_nav_position', '');
$autoplay           =   $params->get('autoplay', 0);
$interval           =   $params->get('interval', 5);
$interval           =   $interval * 1000;
$center             =   $params->get('center', 0);
$overlay_position   =   $params->get('overlay_position', 'justify-content-center align-items-center');
$overlay_position   =   $overlay_position !== '' ? ' ' . $overlay_position : '';
$overlay_max_width  =   $params->get('overlay_max_width', '');
$overlay_max_width  =   $overlay_max_width !== '' ? ' as-width-'. $overlay_max_width : '';

$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    style::render_typography('#'.$element->id.' .moon-heading', $title_font_style, null, $element->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$meta_font_style    =   $params->get('meta_font_style');
if (!empty($meta_font_style)) {
    style::render_typography('#'.$element->id.' .moon-meta', $meta_font_style, null, $element->isRoot);
}
$meta_position      =   $params->get('meta_position', 'before');
$meta_heading_margin=   $params->get('meta_heading_margin', '');

$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    style::render_typography('#'.$element->id.' .moon-text', $content_font_style, null, $element->isRoot);
}
$button_size        =   $params->get('button_size', '');
$button_size        =   $button_size ? ' '. $button_size : '';

$btn_radius         =   $params->get('btn_border_radius', '');
$btn_radius         =   $btn_radius ? ' '. $btn_radius : '';

$image_height      =   $params->get('image_height', '');
$image_width      =   $params->get('image_width', '');

$image_height_data = json_decode($image_height, true);
$image_width_data = json_decode($image_width, true);

if (json_last_error() === JSON_ERROR_NONE && is_array($image_width_data)) {
    $style->child('.uk-slider-image')->add_responsive_css('width', $image_width_data, $image_width_data['postfix']);

}
if (json_last_error() === JSON_ERROR_NONE && is_array($image_height_data)) {
    $style->child('.uk-slider-image')->add_responsive_css('height', $image_height_data, $image_height_data['postfix']);

}
$image_radius=  $params->get('image_radius', '');
if (!empty($image_radius)) {
    style::set_spacing_style($element->style->child('.uk-slider-image'), $image_radius, 'radius');
}

$image_border    =   json_decode($params->get('image_border', ''), true);
if (!empty($image_border)) {
    style::add_border_style('#'. $element->id . ' .uk-slider-image', $image_border, 'global', $element->isRoot);
}


$overlay_positions = $params->get('overlay_positions','');
$overlay_pos_int   = ( $overlay_positions == 'top' || $overlay_positions == 'bottom' ) ? ' uk-flex-1' : '';
if ( ( $overlay_positions == 'top' ) || ( $overlay_positions == 'left' ) || ( $overlay_positions == 'bottom' ) || ( $overlay_positions == 'right' ) ) {
    $overlay_positions = ' uk-flex-' . $overlay_positions;
} elseif ( $overlay_positions == 'top-left' ) {
    $overlay_positions = ' uk-flex-top uk-flex-left';
} elseif ( $overlay_positions == 'top-right' ) {
    $overlay_positions = ' uk-flex-top uk-flex-right';
} elseif ( $overlay_positions == 'top-center' ) {
    $overlay_positions = ' uk-flex-top uk-flex-center';
} elseif ( $overlay_positions == 'center-left' ) {
    $overlay_positions = ' uk-flex-left uk-flex-middle';
} elseif ( $overlay_positions == 'center-right' ) {
    $overlay_positions = ' uk-flex-right uk-flex-middle';
} elseif ( $overlay_positions == 'center-center' ) {
    $overlay_positions = ' uk-flex-center uk-flex-middle';
} elseif ( $overlay_positions == 'bottom-left' ) {
    $overlay_positions = ' uk-flex-bottom uk-flex-left';
} elseif ( $overlay_positions == 'bottom-center' ) {
    $overlay_positions = ' uk-flex-bottom uk-flex-center';
} elseif ( $overlay_positions == 'bottom-right' ) {
    $overlay_positions = ' uk-flex-bottom uk-flex-right';
}

$overlay_align = $params->get('overlay_align','');

$height = $params->get('slider_height', '');

$attrs_slideshow[] = '';
$attrs_slideshow[] = (  $autoplay  ) ? 'autoplay: true' : '';
$attrs_slideshow[] = (  $center  ) ? 'center: true' : '';
$attrs_slideshow   = ' uk-slider="' . implode( '; ', array_filter( $attrs_slideshow ) ) . '"';

$kenburns = $params->get('kenburns_transition','');

$kenburns_transition = ( isset( $kenburns ) && $kenburns ) ? ' uk-transform-origin-' . $kenburns : '';

$kenburns_duration = $params->get('kenburns_duration','');

if ( $kenburns_duration ) {
    $kenburns_duration = ' style="-webkit-animation-duration: ' . $kenburns_duration . 's; animation-duration: ' . $kenburns_duration . 's;"';
}

$navigation = $params->get('navigation', '');
$nav_color     = style::get_color($params->get('navigation_color', ''));
$nav_hover_color     = style::get_color($params->get('navigation_color_hover', ''));
$nav_bg_color     = style::get_color($params->get('navigation_bg_color', ''));
$nav_bg_hover_color     = style::get_color($params->get('navigation_bg_color_hover', ''));

$dot_options = $params->get('dot_style', '');
$dot_below = $params->get('dot_below', '');
$dot_position = $params->get('dot_position', 'uk-position-bottom-center');
$dot_margin = $params->get('dot_margin', '');
$dot_border_color     = style::get_color($params->get('dot_border_color', ''));
$dot_color     = style::get_color($params->get('dot_color', ''));
$dot_hover_color     = style::get_color($params->get('dot_hover_color', ''));

$height_cls = '';
if ( $height == 'full' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . '"';
} elseif ( $height == 'percent' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . 'offset-bottom: 20"';
} elseif ( $height == 'section' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . 'offset-bottom: !.elementor-section +"';
}
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
        if ($row_gutter) {
            $row_column_cls .=  ' gy-' . $key . '-' . $row_gutter;
        }
        if ($column_gutter) {
            $row_column_cls .=  ' gx-' . $key . '-' . $column_gutter;
        }
    } else {
        $row_column_cls     .=  $column ? ' row-cols-' . $column : '';
        $row_gutter         =   $params->get('row_gutter', 3);
        $column_gutter      =   $params->get('column_gutter', 3);
        $row_column_cls     .=  ' gy-' . $row_gutter;
        $row_column_cls     .=  ' gx-' . $column_gutter;
    }
}

$document->load_ui_kit();
echo '<div class="uk-position-relative " tabindex="-1" '.$attrs_slideshow.'>';
echo '<div class="uk-slider-items row flex-nowrap '.$row_column_cls.'">';
foreach ($slides->get_data() as $key => $slide) {
    echo '<div class="">';
    echo '<div class="uk-card">';
        echo '<div class="uk-card-media-top">';
        echo '<img src="'. $slide->params->get('image') .'" class="w-100 h-100" alt="'.$slide->params->get('title').'">';
        echo '</div>';
        echo '<div class="uk-card-body">';
            if (!empty($slide->params->get('meta')) && $meta_position == 'before') {
                echo '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
            }
            if (!empty($slide->params->get('title'))) {
                echo '<h3 class="moon-heading">'. $slide->params->get('title') . '</h3>';
            }
            if (!empty($slide->params->get('meta')) && $meta_position == 'after') {
                echo '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
            }
            if (!empty($slide->params->get('description'))) {
                $content        = format_text($slide->params->get('description', ''), FORMAT_HTML, ['context' => $this->context]);
                echo '<div class="moon-text">' . $content . '</div>';
            }
        echo '</div>';

    echo '</div>';
    echo '</div>';
}
echo '</div>';
    if($navigation){
        if($slider_nav_position){
            echo '
            <div class="navigation-wrap '.$slider_nav_position.'">
            <a class="" href data-uk-slidenav-previous data-uk-slider-item="previous"></a>
            <a class="" href data-uk-slidenav-next data-uk-slider-item="next"></a>
            </div>
        ';
        }else{
            echo '
        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href data-uk-slidenav-previous data-uk-slider-item="previous"></a>
        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href data-uk-slidenav-next data-uk-slider-item="next"></a>
        ';
        }
    }
echo '</div>';
    echo '<ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>';

if (!empty($title_heading_margin)) {
    style::set_spacing_style($element->style->child('.moon-heading'), $title_heading_margin, 'margin');
}
if (!empty($meta_heading_margin)) {
    style::set_spacing_style($element->style->child('.moon-meta'), $meta_heading_margin, 'margin');
}
$meta_radius=  $params->get('meta_radius', '');
if (!empty($meta_radius)) {
    style::set_spacing_style($element->style->child('.moon-meta'), $meta_radius, 'radius');
}
$meta_padding   =   $params->get('meta_heading_padding', '');
if (!empty($meta_padding)) {
    style::set_spacing_style($this->style->child('.moon-meta'), $meta_padding);
}
$meta_bg_color     = style::get_color($params->get('meta_bg_color', ''));
$style->child('.moon-meta')->add_css('background-color', $meta_bg_color['light']);
$style_dark->child('.moon-meta')->add_css('background-color', $meta_bg_color['dark']);

$overlay_bg_color     = style::get_color($params->get('overlay_bg_color', ''));
$style->child('.ui-media::before')->add_css('background-color', $overlay_bg_color['light']);
$style_dark->child('.ui-media::before')->add_css('background-color', $overlay_bg_color['dark']);

$style->child('.uk-slidenav')->add_css('color', $nav_color['light']);
$style_dark->child('.uk-slidenav')->add_css('color', $nav_color['dark']);
$style->child('.uk-slidenav:hover')->add_css('color', $nav_hover_color['light']);
$style_dark->child('.uk-slidenav:hover')->add_css('color', $nav_hover_color['dark']);

$style->child('.uk-slidenav::before')->add_css('background-color', $nav_color['light']);
$style_dark->child('.uk-slidenav::before')->add_css('background-color', $nav_color['dark']);
$style->child('.uk-slidenav:hover::before')->add_css('background-color', $nav_hover_color['light']);
$style_dark->child('.uk-slidenav:hover::before')->add_css('background-color', $nav_hover_color['dark']);

$style->child('.uk-slidenav')->add_css('background-color', $nav_bg_color['light']);
$style_dark->child('.uk-slidenav')->add_css('background-color', $nav_bg_color['dark']);
$style->child('.uk-slidenav:hover')->add_css('background-color', $nav_bg_hover_color['light']);
$style_dark->child('.uk-slidenav:hover')->add_css('background-color', $nav_bg_hover_color['dark']);

$navigation_wrap_margin   =   $params->get('navigation_wrap_margin', '');
if (!empty($navigation_wrap_margin)) {
    style::set_spacing_style($this->style->child('.navigation-wrap'), $navigation_wrap_margin,'margin');
}
$navigation_next_margin   =   $params->get('navigation_next_margin', '');
if (!empty($navigation_next_margin)) {
    style::set_spacing_style($this->style->child('.uk-slidenav-next'), $navigation_next_margin,'margin');
}
$navigation_pre_margin   =   $params->get('navigation_pre_margin', '');
if (!empty($navigation_pre_margin)) {
    style::set_spacing_style($this->style->child('.uk-slidenav-previous'), $navigation_pre_margin,'margin');
}
$nav_padding   =   $params->get('navigation_padding', '');
if (!empty($nav_padding)) {
    style::set_spacing_style($this->style->child('.uk-slidenav'), $nav_padding);
}
$navigation_radius=  $params->get('navigation_radius', '');
if (!empty($navigation_radius)) {
    style::set_spacing_style($element->style->child('.uk-slidenav'), $navigation_radius, 'radius');
}
$slideshow_padding   =   $params->get('slideshow_padding', '');
if (!empty($slideshow_padding)) {
    style::set_spacing_style($this->style->child('.uk-slider'), $slideshow_padding);
}
$overlay_padding   =   $params->get('overlay_padding', '');
if (!empty($overlay_padding)) {
    style::set_spacing_style($this->style->child('.ui-content-wrap'), $overlay_padding);
}
if (!empty($dot_margin)) {
    style::set_spacing_style($element->style->child('.ui-nav-control'), $dot_margin, 'margin');
}

$style->child('.uk-dotnav > * > *')->add_css('border-color', $dot_border_color['light']);
$style_dark->child('.uk-dotnav > * > *')->add_css('border-color', $dot_border_color['dark']);
$style->child('.uk-dotnav > .uk-active > *')->add_css('background-color', $dot_color['light']);
$style_dark->child('.uk-dotnav > .uk-active > *')->add_css('background-color', $dot_color['dark']);

$style->child('.uk-dotnav > * > :hover')->add_css('background-color', $dot_hover_color['light']);
$style_dark->child('.uk-dotnav > * > :hover')->add_css('background-color', $dot_hover_color['dark']);

$button_font_style   =   $params->get('button_font_style');
if (!empty($button_font_style)) {
    style::render_typography('#'.$element->id.' .btn', $button_font_style, null, $element->isRoot);
}
$button_margin   =   $params->get('button_margin', '');
if (!empty($button_margin)) {
    style::set_spacing_style($element->style->child('.moon-button'), $button_margin, 'margin');
}
$button_padding   =   $params->get('button_padding', '');
if (!empty($button_padding)) {
    style::set_spacing_style($element->style->child('.btn'), $button_padding);
}
$button_radius  =   $params->get('button_radius', '');
if (!empty($button_radius)) {
    style::set_spacing_style($element->style->child('.btn'), $button_radius,'radius');
}
$button_border    =   json_decode($params->get('button_border', ''), true);
if (!empty($button_border)) {
    style::add_border_style('#'. $element->id . ' .btn', $button_border, 'global', $element->isRoot);
}
$button_bg_color     = style::get_color($params->get('button_bg_color', ''));
$style->child('.btn')->add_css('background-color', $button_bg_color['light']);
$style_dark->child('.btn')->add_css('background-color', $button_bg_color['dark']);

$button_bg_color_hover     = style::get_color($params->get('button_bg_color_hover', ''));
$style->child('.btn:hover')->add_css('background-color', $button_bg_color_hover['light']);
$style_dark->child('.btn:hover')->add_css('background-color', $button_bg_color_hover['dark']);

$button_color_hover     = style::get_color($params->get('button_color_hover', ''));
$style->child('.btn:hover')->add_css('color', $button_color_hover['light']);
$style_dark->child('.btn:hover')->add_css('color', $button_color_hover['dark']);

$content_padding   =   $params->get('content_padding', '');
if (!empty($content_padding)) {
    style::set_spacing_style($element->style->child('.uk-card-body'), $content_padding);
}