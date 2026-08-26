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
use local_moon\library\helper\sub_form;
use local_moon\library\framework;
$params = $this->params;
$element = $this;
$testimonials     = new sub_form($params->get('testimonials', ''));
if (!count($testimonials->get_data())) {
    return false;
}
$style = $element->style;
$style_dark = $element->style_dark;

$enable_slider      =   $params->get('enable_slider', 0);
$slider_autoplay    =   $params->get('slider_autoplay', 0);
$slider_nav         =   $params->get('slider_nav', 1);
$slider_scrollbar   =   $params->get('slider_scrollbar', 0);
$nav_position       =   $params->get('nav_position', '');
$nav_position       =   $nav_position !== '' ? ' ' . $nav_position : $nav_position;
$slider_dotnav      =   $params->get('slider_dotnav', 0);
$interval           =   $params->get('interval', 3);
$slide_settings     =   array();
$slide_responsive   =   array();
$row_column_cls     =   'row';

$responsive_key     =   [
    'xs'    => '',
    'sm'    => '576',
    'md'    => '768',
    'lg'    => '992',
    'xl'    => '1200',
    'xxl'   => '1400',
];
foreach ($responsive_key as $key => $min_width) {
    $column         =   $params->get($key . '_column', '');
    $slidesPerGroup =   $params->get($key . '_slidesPerGroup', '');
    $gutter         =   $params->get('column_gutter_' . $key, '10');
    if ($enable_slider && !empty($column)) {
        if (!count($slide_settings)) {
            $slide_settings[]       =   'slidesPerView: ' . $column;
            if ($slidesPerGroup == '') {
                $slide_settings[]       =   'slidesPerGroup: ' . $slidesPerGroup;
            }
            $slide_settings[]       =   'spaceBetween: ' . $gutter;
        } elseif (!empty($min_width)) {
            $slide_responsive[]     =   $min_width . ': {slidesPerView: '.$column.($slidesPerGroup ? ',slidesPerGroup: '.$slidesPerGroup : '').',spaceBetween: '.$gutter.'}';
        }
    } else {
        if (!empty($column)) {
            $row_column_cls .=  ' row-cols' . ($key !== 'xs' ? '-' . $key : '') . '-' . $column;
        }
    }
}

if ($slider_autoplay) {
    $slide_settings[]       =   'autoplay: {delay: '.($interval * 1000).'}';
}

if ($slider_dotnav) {
    $slide_settings[]       =   'pagination: {el: ".swiper-pagination",clickable: true,}';
}

if ($slider_nav) {
    $slide_settings[]       =   'navigation: {nextEl: ".swiper-button-next",prevEl: ".swiper-button-prev",}';
}
$speed              =   $params->get('speed', 0);
if (!empty($speed)) {
    $slide_settings[]   =   'speed:' . ($speed * 1000);
}
$loop               =   $params->get('loop', 0);
if (!empty($loop)) {
    $slide_settings[]   =   'loop:true';
}
$freemode           =   $params->get('freemode', 0);
if (!empty($freemode)) {
    $slide_settings[]   =   'freeMode: true';
}
$dir                =   $params->get('direction', '');
//$slide_settings[]   =   'autoHeight: true';
if (count($slide_responsive)) {
    $slide_settings[]       =   'breakpoints: {'.implode(',', $slide_responsive).'}';
}

$responsive_key     =   ['xxl', 'xl', 'lg', 'md', 'sm', 'xs'];
$gutter_cls         =   '';
foreach ($responsive_key as $key) {
    if ($key !== 'xs') {
        $row_gutter         =   $params->get('row_gutter_'.$key, '');
        $column_gutter      =   $params->get('column_gutter_'. $key, '');
        if ($row_gutter) {
            $gutter_cls     .=  ' gy-' . $key . '-' . $row_gutter;
        }
        if ($column_gutter) {
            $gutter_cls     .=  ' gx-' . $key . '-' . $column_gutter;
        }
    } else {
        $row_gutter         =   $params->get('row_gutter', 3);
        $column_gutter      =   $params->get('column_gutter', 3);
        $gutter_cls         .=  ' gy-' . $row_gutter;
        $gutter_cls         .=  ' gx-' . $column_gutter;
    }
}

$card_style         =   $params->get('card_style', '');
$card_style         =   $card_style ? ' text-bg-' . $card_style : '';

$card_size          =   $params->get('card_size', '');
$card_size          =   $card_size ? ' card-size-' . $card_size : '';

$card_rounded_size  =   $params->get('card_rounded_size', '3');
$border_radius      =   $params->get('card_border_radius', '');
$bd_radius          =   $border_radius != '' ? ' rounded-' . $border_radius : ' rounded-' . $card_rounded_size;

$avatar_width_cls    =   '';
$avatar_position    =   $params->get('avatar_position', 'top');
if ($avatar_position == 'right') {
    $avatar_width_cls.=  'order-2';
} else {
    $avatar_width_cls.=  'order-0';
}
$xxl_column_avatar   =   $params->get('xxl_column_avatar', '');
$avatar_width_cls    .=  $xxl_column_avatar ? ' col-xxl-' . $xxl_column_avatar : '';
$xl_column_avatar    =   $params->get('xl_column_avatar', '');
$avatar_width_cls    .=  $xl_column_avatar ? ' col-xl-' . $xl_column_avatar : '';
$lg_column_avatar    =   $params->get('lg_column_avatar', 4);
$avatar_width_cls    .=  $lg_column_avatar ? ' col-lg-' . $lg_column_avatar : '';
$md_column_avatar    =   $params->get('md_column_avatar', 12);
$avatar_width_cls    .=  $md_column_avatar ? ' col-md-' . $md_column_avatar : '';
$sm_column_avatar    =   $params->get('sm_column_avatar', 12);
$avatar_width_cls    .=  $sm_column_avatar ? ' col-sm-' . $sm_column_avatar : '';
$xs_column_avatar    =   $params->get('xs_column_avatar', 12);
$avatar_width_cls    .=  $xs_column_avatar ? ' col-' . $xs_column_avatar : '';

$enable_grid_match  =   $params->get('enable_grid_match', 0);

$box_shadow         =   $params->get('card_box_shadow', '');
$box_shadow         =   $box_shadow ? ' ' . $box_shadow : '';
$box_shadow_hover   =   $params->get('card_box_shadow_hover', '');
$box_shadow_hover   =   $box_shadow_hover ? ' ' . $box_shadow_hover : '';

$title_html_element =   $params->get('title_html_element', 'h3');
$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    style::render_typography('#'.$element->id.' .as-author-name', $title_font_style, null, $element->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$designation_font_style    =   $params->get('designation_font_style');
if (!empty($designation_font_style)) {
    style::render_typography('#'.$element->id.' .as-author-designation', $designation_font_style, null, $element->isRoot);
}

$designation_heading_margin=   $params->get('designation_heading_margin', '');
$designation_position      =   $params->get('designation_position', 'after');

$content_margin     =   $params->get('content_margin', '');
$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    style::render_typography('#'.$element->id.' .as-author-message', $content_font_style, null, $element->isRoot);
}

$image_max_width        =   $params->get('image_max_width', '200');
$image_rounded_size     =   $params->get('image_rounded_size', '3');
$image_border_radius    =   $params->get('image_border_radius', '0');
$image_border_radius    =   $image_border_radius != 'rounded' ? ' rounded-' . $image_border_radius : ' rounded-' . $image_rounded_size;

$image_border           =   json_decode($params->get('image_border', ''), true);

$hover_effect   = $params->get('hover_effect', '');
$hover_effect   = $hover_effect !== '' ? ' as-effect-' . $hover_effect : '';
$transition     = $params->get('hover_transition', '');
$transition     = $transition !== '' ? ' as-transition-' . $transition : '';

$card_hover_transition     = $params->get('card_hover_transition', '');
$card_hover_transition     = $card_hover_transition !== '' ? ' as-transition-' . $card_hover_transition : '';

$overlay_text_color =   $params->get('overlay_text_color', '');
$overlay_text_color =   $overlay_text_color !== '' ? ' ' . $overlay_text_color : '';

$enable_rating      =   $params->get('enable_rating', 0);

// Alignment
$text_alignment             =   $params->get('text_alignment','');
$text_alignment_breakpoint  =   $params->get('text_alignment_breakpoint','');
$text_alignment_fallback    =   $params->get('text_alignment_fallback','');

$card_vertical_align    =   $params->get('card_vertical_align','');

$slider_nav_position    =   $params->get('slider_nav_position','');

$testimonial_icon    =   $params->get('testimonial_icon','');

if ($text_alignment) {
    $alignment              =   ' justify-content' . ($text_alignment_breakpoint ? '-' . $text_alignment_breakpoint : '') . '-' . $text_alignment . ($text_alignment_fallback ? ' justify-content-' . $text_alignment_fallback : '');
} else {
    $alignment              =   '';
}
$item_cl = ' testimonial-item';
$use_masonry        =   $params->get('use_masonry', 0);
if ($enable_slider) {
    echo '<div class="swiper"'.(!empty($dir) ? ' dir="'.$dir.'"' : '').'>';
    $item_cl = 'swiper-slide';
}
echo '<div class="moon-grid '.($enable_slider ? 'swiper-wrapper' : $row_column_cls.$gutter_cls . ($use_masonry ? ' as-masonry as-loading' : '')).$overlay_text_color.'">';
foreach ($testimonials->get_data() as $key => $testimonial) {
    $item_bg_color   =   style::get_color($testimonial->params->get('item_bg_color', ''));
    $element->style->child('#testimonial-'. $testimonial -> id .' .card-body')->add_css('background-color', $item_bg_color['light']);
    $element->style_dark->child('#testimonial-'. $testimonial -> id .' .card-body')->add_css('background-color', $item_bg_color['dark']);

    $avatar =   $testimonial->params->get('avatar', '');
    $rating =   $testimonial->params->get('rating', 5);
    $media  =   '';
    if ($avatar) {
        $media      =   '<div class="as-author-avatar d-inline-block position-relative overflow-hidden' . $image_border_radius . $box_shadow . $hover_effect . $transition . '">';
        $media      .=  '<img class="' . ($avatar_position == 'left' || $avatar_position == 'right' ? 'object-fit-cover w-100 h-100 ' : '') .'" src="'. $avatar .'" alt="'.$testimonial->params->get('title', '').'">';
        $media      .=  '</div>';
    }

    echo '<div id="testimonial-'. $testimonial -> id .'" class="'.$item_cl.'"><div class="card' . $card_style . $box_shadow . $box_shadow_hover .$bd_radius . $card_hover_transition . ($enable_grid_match ? ' h-100' : '') . '">';
    if ($avatar_position == 'left' || $avatar_position == 'right') {
        echo '<div class="row g-0 '.$card_vertical_align.'">';
        echo '<div class="'.$avatar_width_cls.'">';
    }
    if ($avatar_position == 'left' || $avatar_position == 'right') {
        echo $media;
    }
    if ($avatar_position == 'left' || $avatar_position == 'right') {
        echo '</div>';
        echo '<div class="col order-1">';
    }

    echo '<div class="order-1 card-body'.$card_size.'">'; // Start Card-Body
    if ($avatar_position == 'left' || $avatar_position == 'right' || $avatar_position == 'bottom') {
        if($testimonial_icon){
            echo '<div class="testimonial_icon"><i class="'.$testimonial_icon.'"></i></div>';
        }
    }


    if ($avatar_position == 'top') {
        echo '<div class="d-flex align-items-start">';
        echo $media;
        echo '<div class="top-name">';
            if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'before') {
                echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
            }
            if (!empty($testimonial->params->get('title', ''))) {
                echo '<'.$title_html_element.' class="as-author-name">'. $testimonial->params->get('title', '') . '</'.$title_html_element.'>';
            }
            if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'after') {
                echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
            }
            if (!empty($testimonial->params->get('link', '')) && !empty($testimonial->params->get('link_title', ''))) {
                echo '<a class="as-author-url" href="'.$testimonial->params->get('link', '').'" target="_blank">' . $testimonial->params->get('link_title', '') . '</a>';
            }
        echo '</div>';
        echo '</div>';
        if (!empty($enable_rating)) {
            echo '<div class="as-rating-block row row-cols-auto gx-2'.$alignment.'">';
            for ($i = 0; $i < 5 ; $i++) {
                if ($i < $rating) {
                    if ($rating - $i >= 1) {
                        echo '<div class="as-star"><i class="fa-solid fa-star"></i></div>';
                    } else {
                        echo '<div class="as-star"><i class="fa-solid fa-star-half-stroke"></i></div>';
                    }
                } else {
                    echo '<div class="as-star"><i class="fa-regular fa-star"></i></div>';
                }
            }
            echo '</div>';
        }
    }
    if (!empty($testimonial->params->get('message', ''))) {
        echo '<div class="as-author-message">' . $testimonial->params->get('message', '') . '</div>';
    }
    if ($avatar_position == 'top') {
        if($testimonial_icon){
            echo '<div class="testimonial_icon text-right"><i class="'.$testimonial_icon.'"></i></div>';
        }
    }
    if ($avatar_position == 'bottom') {
        echo $media;

        if (!empty($enable_rating)) {
            echo '<div class="as-rating-block row row-cols-auto gx-2'.$alignment.'">';
            for ($i = 0; $i < 5 ; $i++) {
                if ($i < $rating) {
                    if ($rating - $i >= 1) {
                        echo '<div class="as-star"><i class="fa-solid fa-star"></i></div>';
                    } else {
                        echo '<div class="as-star"><i class="fa-solid fa-star-half-stroke"></i></div>';
                    }
                } else {
                    echo '<div class="as-star"><i class="fa-regular fa-star"></i></div>';
                }
            }
            echo '</div>';
        }
        if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'before') {
            echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
        }
        if (!empty($testimonial->params->get('title', ''))) {
            echo '<'.$title_html_element.' class="as-author-name">'. $testimonial->params->get('title', '') . '</'.$title_html_element.'>';
        }
        if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'after') {
            echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
        }
        if (!empty($testimonial->params->get('link', '')) && !empty($testimonial->params->get('link_title', ''))) {
            echo '<a class="as-author-url" href="'.$testimonial->params->get('link', '').'" target="_blank">' . $testimonial->params->get('link_title', '') . '</a>';
        }
    }
    if ($avatar_position == 'left' || $avatar_position == 'right') {

        if (!empty($enable_rating)) {
            echo '<div class="as-rating-block row row-cols-auto gx-2'.$alignment.'">';
            for ($i = 0; $i < 5 ; $i++) {
                if ($i < $rating) {
                    if ($rating - $i >= 1) {
                        echo '<div class="as-star"><i class="fa-solid fa-star"></i></div>';
                    } else {
                        echo '<div class="as-star"><i class="fa-solid fa-star-half-stroke"></i></div>';
                    }
                } else {
                    echo '<div class="as-star"><i class="fa-regular fa-star"></i></div>';
                }
            }
            echo '</div>';
        }
        if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'before') {
            echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
        }
        if (!empty($testimonial->params->get('title', ''))) {
            echo '<'.$title_html_element.' class="as-author-name">'. $testimonial->params->get('title', '') . '</'.$title_html_element.'>';
        }
        if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'after') {
            echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
        }
        if (!empty($testimonial->params->get('link', '')) && !empty($testimonial->params->get('link_title', ''))) {
            echo '<a class="as-author-url" href="'.$testimonial->params->get('link', '').'" target="_blank">' . $testimonial->params->get('link_title', '') . '</a>';
        }
    }
    echo '</div>'; // End Card-Body

    if ($avatar_position == 'left' || $avatar_position == 'right') {

        echo '</div>';
        echo '</div>';
    }

    echo '</div></div>';
}
echo '</div>';
if ($enable_slider) {
    if ($slider_dotnav) {
        echo '<div class="swiper-pagination"></div>';
    }
    if ($slider_nav) {
        if($slider_nav_position){
            echo '<div class="swiper_nav uk-flex '.$slider_nav_position.'"><div class="swiper-button-prev swiper-nav-button uk-position-relative"></div><div class="swiper-button-next swiper-nav-button uk-position-relative"></div></div> ';
        }else{
            echo '<div class="swiper_nav uk-flex '.$slider_nav_position.'"><div class="swiper-button-prev swiper-nav-button"></div><div class="swiper-button-next swiper-nav-button"></div></div> ';
        }

    }
    if ($slider_scrollbar) {
        echo '<div class="swiper-scrollbar"></div>';
    }
    echo '</div>';
}
$document = framework::get_document();

if ($enable_slider) {
    $document->load_swiper('#'.$element->id.' .swiper', implode(',', $slide_settings));
} elseif ($use_masonry) {
    $document->load_masonry('#'. $element->id .' .as-masonry');
}
$document->load_ui_kit();
if ($params->get('card_size', '') == 'custom') {
    $card_padding   =   $params->get('card_padding', '');
    if (!empty($card_padding)) {
        style::set_spacing_style($element->style->child('.card-size-custom'), $card_padding);
    }
}
if ($params->get('card_size', '') == 'custom') {
    $card_margin   =   $params->get('card_margin', '');
    if (!empty($card_margin)) {
        style::set_spacing_style($element->style->child('.card-size-custom'), $card_margin, 'margin');
    }
}
if (!empty($image_max_width)) {
    $element->style->child('.as-author-avatar > img')->add_css('max-width', $image_max_width . 'px');
}
if (!empty($title_heading_margin)) {
    style::set_spacing_style($element->style->child('.as-author-name'), $title_heading_margin, 'margin');
}
if (!empty($designation_heading_margin)) {
    style::set_spacing_style($element->style->child('.as-author-designation'), $designation_heading_margin, 'margin');
}
if (!empty($content_margin)) {
    style::set_spacing_style($element->style->child('.as-author-message'), $content_margin, 'margin');
}
if (!empty($image_border)) {
    style::add_border_style('#'. $element->id . ' .as-author-avatar', $image_border);
}
if (!empty($enable_rating)) {
    $rating_color   =   style::get_color($params->get('rating_color', ''));
    $rating_margin=   $params->get('rating_margin', '');
    $element->style->child('.as-rating-block')->add_css('color', $rating_color['light']);
    $element->style_dark->child('.as-rating-block')->add_css('color', $rating_color['dark']);
    if (!empty($rating_margin)) {
        style::set_spacing_style($element->style->child('.as-rating-block'), $rating_margin, 'margin');
    }
}
if ($params->get('card_style', '') == 'custom') {
    $text_color     =   style::get_color($params->get('text_color', ''));
    $style->child('.card')->add_css('color', $text_color['light']);
    $style_dark->child('.card')->add_css('color', $text_color['dark']);

    $bg_color       =   style::get_color($params->get('bg_color', ''));
    if ($avatar_position == 'left' || $avatar_position == 'right') {
        $style->child('.card-body')->add_css('background-color', $bg_color['light']);
        $style_dark->child('.card-body')->add_css('background-color', $bg_color['dark']);
    }else{
        $style->child('.card')->add_css('background-color', $bg_color['light']);
        $style_dark->child('.card')->add_css('background-color', $bg_color['dark']);
    }

    $card_border    =   json_decode($params->get('card_border', ''), true);
    if (!empty($card_border)) {
        style::add_border_style('#'. $element->id . ' .card', $card_border, 'global', $element->isRoot);
    }

}
$slider_padding   =   $params->get('slider_padding', '');
if (!empty($slider_padding)) {
    style::set_spacing_style($element->style->child('.swiper'), $slider_padding);
}

$next_margin   =   $params->get('next_margin', '');
if (!empty($next_margin)) {
    style::set_spacing_style($element->style->child('.swiper-button-next'), $next_margin, 'margin');
}
$preview_margin   =   $params->get('preview_margin', '');
if (!empty($preview_margin)) {
    style::set_spacing_style($element->style->child('.swiper-button-prev'), $preview_margin, 'margin');
}
$slider_nav_height      =   $params->get('slider_nav_height', '');
$nav_height = json_decode($slider_nav_height, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($nav_height)) {
    $style->child('.swiper-nav-button')->add_responsive_css('height', $nav_height, $nav_height['postfix']);
}
$slider_nav_width      =   $params->get('slider_nav_width', '');
$nav_width = json_decode($slider_nav_width, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($nav_width)) {
    $style->child('.swiper-nav-button')->add_responsive_css('width', $nav_width, $nav_width['postfix']);
}
$nav_border    =   json_decode($params->get('slider_nav_border', ''), true);
if (!empty($nav_border)) {
    style::add_border_style('#'. $element->id . ' .swiper-nav-button', $nav_border, 'global', $element->isRoot);
}
$nav_radius  =   $params->get('slider_nav_radius', '');
if (!empty($nav_radius)) {
    style::set_spacing_style($element->style->child('.swiper-nav-button'), $nav_radius,'radius');
}

$nav_color     = style::get_color($params->get('nav_color', ''));
$style->child('.swiper-nav-button')->add_css('color', $nav_color['light']);
$style_dark->child('.swiper-nav-button')->add_css('color', $nav_color['dark']);

$nav_bg_color     = style::get_color($params->get('nav_bg_color', ''));
$style->child('.swiper-nav-button')->add_css('background-color', $nav_bg_color['light']);
$style_dark->child('.swiper-nav-button')->add_css('background-color', $nav_bg_color['dark']);

$nav_bg_color_hover     = style::get_color($params->get('nav_bg_color_hover', ''));
$style->child('.swiper-nav-button:hover')->add_css('background-color', $nav_bg_color_hover['light']);
$style_dark->child('.swiper-nav-button:hover')->add_css('background-color', $nav_bg_color_hover['dark']);

$nav_color_hover     = style::get_color($params->get('nav_color_hover', ''));
$style->child('.swiper-nav-button:hover')->add_css('color', $nav_color_hover['light']);
$style_dark->child('.swiper-nav-button:hover')->add_css('color', $nav_color_hover['dark']);

$nav_border_hover    =   json_decode($params->get('slider_nav_border_hover', ''), true);
if (!empty($nav_border_hover)) {
    style::add_border_style('#'. $element->id . ' .swiper-nav-button:hover', $nav_border_hover, 'global', $element->isRoot);
}
$testimonial_icon_size        =   $params->get('testimonial_icon_size', '30');
$icon_size = json_decode($testimonial_icon_size, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $style->child('.testimonial_icon')->add_responsive_css('font-size', $icon_size, $icon_size['postfix']);
}

$testimonial_icon_color     = style::get_color($params->get('testimonial_icon_color', ''));
$style->child('.testimonial_icon')->add_css('color', $testimonial_icon_color['light']);
$style_dark->child('.testimonial_icon')->add_css('color', $testimonial_icon_color['dark']);

if($border_radius  == 'custom'){
    $card_radius_custom  =   $params->get('card_radius_custom', '');
    if ($avatar_position == 'left' || $avatar_position == 'right') {
        if (!empty($card_radius_custom)) {
            style::set_spacing_style($element->style->child('.card-body'), $card_radius_custom,'radius');
        }
    }else{
        if (!empty($card_radius_custom)) {
            style::set_spacing_style($element->style->child('.card'), $card_radius_custom,'radius');
        }
    }
}
$image_margin   =   $params->get('image_margin', '');
if (!empty($image_margin)) {
    style::set_spacing_style($element->style->child('.as-author-avatar'), $image_margin, 'margin');
}
$icon_margin   =   $params->get('icon_margin', '');
if (!empty($image_margin)) {
    style::set_spacing_style($element->style->child('.testimonial_icon'), $icon_margin, 'margin');
}