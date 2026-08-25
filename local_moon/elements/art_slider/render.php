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
$document->load_gsap('Observer');

$style = $element->style;
$style_dark = $element->style_dark;

$card_size          =   $params->get('overlay_padding', '');
$card_size          =   $card_size ? ' card-size-' . $card_size : '';

$media_position     =   $params->get('media_position', 'top');

$slide_rounded_size =   $params->get('slide_rounded_size', '3');
$border_radius      =   $params->get('slide_border_radius', '');
$bd_radius          =   $border_radius != '' ? ' rounded-' . $border_radius : ' rounded-' . $slide_rounded_size;

$overlay_text_color =   $params->get('overlay_text_color', '');
$overlay_text_color =   $overlay_text_color !== '' ? ' ' . $overlay_text_color : '';
$min_height         =   $params->get('min_height', '');
$slider_height      =   $params->get('slider_height', '');
$overlay_max_width  =   $params->get('overlay_max_width', '');
$overlay_max_width  =   $overlay_max_width !== '' ? ' as-width-'. $overlay_max_width : '';
$autoplay           =   $params->get('autoplay', 0);
$interval           =   $params->get('interval', 5);
$interval           =   $interval * 1000;
$overlay_type       =   $params->get('overlay_type', '');
$overlay_color      =   $params->get('overlay_color', '');
$effect_type        =   $params->get('effect_type', 'theater');
$main_color         =   style::get_color($params->get('main_color', ''));
$overlay_position   =   $params->get('overlay_position', 'justify-content-center align-items-center');
$overlay_position   =   $overlay_position !== '' ? ' ' . $overlay_position : '';
$image_effect       =   $params->get('image_effect', '');

$box_shadow         =   $params->get('box_shadow', '');
$box_shadow         =   $box_shadow ? ' ' . $box_shadow : '';
$box_shadow_hover   =   $params->get('box_shadow_hover', '');
$box_shadow_hover   =   $box_shadow_hover ? ' ' . $box_shadow_hover : '';

$title_html_element =   $params->get('title_html_element', 'h3');
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
echo '<div id="slide-'.$element->id.'" class="as-art-slides as-art-slide-container position-relative'. $overlay_text_color . ' ' . $effect_type . $box_shadow . $box_shadow_hover .'" data-type="'.$effect_type.'" data-image-effect="'.$image_effect.'" data-autoplay="'.$autoplay.'" data-interval="'.$interval.'" data-controls="'.($params->get('controls', 1) ? 'true' : 'false').'" data-indicators="'.($params->get('indicators', 1) ? 'true' : 'false').'">';
echo '<div class="as-art-slides-inner overflow-hidden'.$bd_radius.'">';
foreach ($slides->get_data() as $key => $slide) {
    echo '<div class="as-art-slide">';
    echo '<div class="as-art-slide__img"><div class="as-art-slide-img-inner position-absolute w-100 h-100"><img src="'. $slide->params->get('image') .'" class="object-fit-cover w-100 h-100" alt="'.$slide->params->get('title').'"></div></div>';
    echo '<div class="d-flex card-img-overlay'.$overlay_position.'"><div class="moon-text-container overlay-inner'.$overlay_max_width.'">';
    if (!empty($slide->params->get('meta')) && $meta_position == 'before') {
        echo '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
    }
    if (!empty($slide->params->get('title'))) {
        echo '<'.$title_html_element.' class="moon-heading">'. $slide->params->get('title') . '</'.$title_html_element.'>';
    }
    if (!empty($slide->params->get('meta')) && $meta_position == 'after') {
        echo '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
    }
    if (!empty($slide->params->get('description'))) {
        $content        = format_text($slide->params->get('description', ''), FORMAT_HTML, ['context' => $this->context]);
        echo '<div class="moon-text">' . $content . '</div>';
    }
    $target = !empty($slide->params->get('link_target')) ? ' target="'.$slide->params->get('link_target').'"' : '';
    if (!empty($slide->params->get('link'))) {
        echo '<div class="moon-button mt-5"><a class="btn btn-' .(intval($params->get('button_outline', 0)) ? 'outline-' : ''). $params->get('button_style', '') . $button_size . $btn_radius . '" href="' . $slide->params->get('link') . '"'.$target.'>' . $slide->params->get('link_title') . '</a></div>';
    }
    echo '</div></div>';
    echo '</div>';
}
echo match($effect_type) {
    'theater' => '<div class="deco deco--1"></div><div class="deco deco--1"></div><div class="deco deco--2"></div><div class="deco deco--2"></div><div class="deco deco--3"></div><div class="deco deco--3"></div>',
    'slide_vertical' => '<div class="deco deco--3"></div>',
    'slide_horizontal' => '<div class="deco deco--1"></div><div class="deco deco--2"></div><div class="deco deco--3"></div>',
    'tv_channel' => '<div class="deco deco--3"></div><div class="deco deco--3"></div>',
    default => ''
};
echo '</div>';
if (!empty($params->get('indicators', 1))) {
    echo '<div class="as-art-slide-indicators d-none d-md-block z-1">';
    foreach ($slides->get_data() as $key => $slide) {
        echo '<button type="button" aria-label="'.$slide->params->get('title').'"'.($key == 0 ? ' class="active"' : '').'></button>';
    }
    echo '</div>';
}
if (!empty($params->get('controls', 1))) {
    echo '<nav class="as-art-slides-nav z-1">';
    echo '<button class="as-art-slides-nav__item slides-nav__item--prev">&larr;</button>';
    echo '<button class="as-art-slides-nav__item slides-nav__item--next">&rarr;</button>';
    echo '</nav>';
}
echo '</div>';
$document->load_art_slider();

$height_data = json_decode($slider_height, true);

if (json_last_error() === JSON_ERROR_NONE && is_array($height_data)) {
    $style->child('.as-art-slides-inner')->add_responsive_css('height', $height_data, $height_data['postfix']);
    if ($min_height) {
        $style->child('.as-art-slides-inner')->add_css('min-height', $min_height . 'px');
    }
} else {
    $style->child('.as-art-slides-inner')->add_css('height', $min_height . 'px');
}

if ($params->get('card_size', '') == 'custom') {
    $card_padding   =   $params->get('card_padding', '');
    if (!empty($card_padding)) {
        style::set_spacing_style($element->style->child('.card-size-custom'), $card_padding);
    }
}
if (!empty($title_heading_margin)) {
    style::set_spacing_style($element->style->child('.moon-heading'), $title_heading_margin, 'margin');
}
if (!empty($meta_heading_margin)) {
    style::set_spacing_style($element->style->child('.moon-meta'), $meta_heading_margin, 'margin');
}
$style->child('.deco--3')->add_css('background-color', $main_color['light']);
$style_dark->child('.deco--3')->add_css('background-color', $main_color['dark']);
switch ($overlay_type) {
    case 'color':
        $overlay_color      =   style::get_color($params->get('overlay_color', ''));
        $style->child('.as-art-slide__img:after')->add_css('background-color', $overlay_color['light']);
        $style_dark->child('.as-art-slide__img:after')->add_css('background-color', $overlay_color['dark']);
        break;
    case 'background-color':
        $overlay_gradient   =   $params->get('overlay_gradient', '');
        if (!empty($overlay_gradient)) {
            $style->child('.as-art-slide__img:after')->add_css('background-image', style::get_gradient_value($overlay_gradient));
        }
        break;
}