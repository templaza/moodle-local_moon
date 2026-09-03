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
 * @package   local_moon
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
$grids     = new sub_form($params->get('grids', ''));
if (!count($grids->data)) {
    return false;
}
global $OUTPUT;
$template_context = [];
$document = framework::get_document();
$style = $element->style;
$style_dark = $element->style_dark;
$row_column_cls     =   '';
$document->load_ui_kit();
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
        if ($row_gutter || $row_gutter==0) {
            $row_column_cls .=  ' gy-' . $key . '-' . $row_gutter;
        }
        if ($column_gutter || $column_gutter==0) {
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

$card_style         =   $params->get('card_style', '');
$card_style         =   $card_style ? ' text-bg-' . $card_style : '';

$card_size          =   $params->get('card_size', '');
$card_size          =   $card_size ? ' card-size-' . $card_size : '';

$card_rounded_size  =   $params->get('card_rounded_size', '3');
$border_radius      =   $params->get('card_border_radius', '');
$bd_radius          =   $border_radius != '' ? ' rounded-' . $border_radius : ' rounded-' . $card_rounded_size;
$card_custom_radius      =   $params->get('card_custom_radius', '');

$media_width_cls    =   '';
$media_position     =   $params->get('media_position', 'top');
$content_position     =   $params->get('content_position', 'uk-position-bottom');
if ($media_position == 'right') {
    $media_width_cls.=  'order-2';
} else {
    $media_width_cls.=  'order-0';
}

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

$icon_size          =   $params->get('icon_size', 60);

$icon_color         =   style::get_color($params->get('icon_color', ''));
$style->child('.moon-icon')->add_css('color', $icon_color['light']);
$style_dark->child('.moon-icon')->add_css('color', $icon_color['dark']);

$icon_color_hover   =   style::get_color($params->get('icon_color_hover', ''));
$style->child('.moon-icon')->hover()->add_css('color', $icon_color_hover['light']);
$style_dark->child('.moon-icon')->hover()->add_css('color', $icon_color_hover['dark']);

$icon_bg_color         =   style::get_color($params->get('icon_bg_color', ''));
$style->child('.moon-icon')->add_css('background-color', $icon_bg_color['light']);
$style_dark->child('.moon-icon')->add_css('background-color', $icon_bg_color['dark']);

$icon_bgcolor_hover   =   style::get_color($params->get('icon_bgcolor_hover', ''));
$style->child('.moon-icon')->hover()->add_css('background-color', $icon_bgcolor_hover['light']);
$style_dark->child('.moon-icon')->hover()->add_css('background-color', $icon_bgcolor_hover['dark']);

$layout             =   $params->get('layout', 'classic');
$enable_image_cover =   $params->get('enable_image_cover', 0);
$template_context['enable_image_cover'] = $enable_image_cover;
$image_fullwidth    =   $enable_image_cover ? 1 : $params->get('image_fullwidth', 1);
$min_height         =   $params->get('min_height', 0);
$overlay_type       =   $params->get('overlay_type', '');
$enable_grid_match  =   $params->get('enable_grid_match', 0);
$vertical_middle    =   $params->get('vertical_middle', 0);

$box_shadow         =   $params->get('card_box_shadow', '');
$box_shadow         =   $box_shadow ? ' ' . $box_shadow : '';
$box_shadow_hover   =   $params->get('card_box_shadow_hover', '');
$box_shadow_hover   =   $box_shadow_hover ? ' ' . $box_shadow_hover : '';
$cover_toggle = '';
if($enable_image_cover){
    $cover_toggle = ' uk-transition-toggle ';
}
$content_hover_transition     = $params->get('media_hover_transition', '');
$template_context['content_hover_transition'] = $content_hover_transition;

$title_html_element =   $params->get('title_html_element', 'h3');
$template_context['title_html_element'] = $title_html_element;
$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    style::render_typography('#'.$element->id.' .moon-heading', $title_font_style, null, $element->is_root);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$meta_font_style    =   $params->get('meta_font_style');
if (!empty($meta_font_style)) {
    style::render_typography('#'.$element->id.' .moon-meta', $meta_font_style, null, $element->is_root);
}

$meta_heading_margin=   $params->get('meta_heading_margin', '');
$meta_heading_padding=   $params->get('meta_heading_padding', '');
$meta_heading_radius=   $params->get('meta_heading_radius', '');
$meta_position      =   $params->get('meta_position', 'before');
$template_context['meta_position'] = $meta_position;
$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    style::render_typography('#'.$element->id.' .moon-text', $content_font_style, null, $element->is_root);
}

$button_style       =   $params->get('button_style', 'primary');
$button_outline     =   $params->get('button_outline', 0);

$button_size        =   $params->get('button_size', '');
$button_size        =   $button_size ? ' '. $button_size : '';

$button_radius      =   $params->get('btn_border_radius', '');
$button_bd_radius   =   $button_radius ? ' ' . $button_radius : '';

$image_rounded_size     =   $params->get('image_rounded_size', '3');
$image_border_radius    =   $params->get('image_border_radius', '0');
$image_border_radius    =   $image_border_radius != 'rounded' ? ' rounded-' . $image_border_radius : ' rounded-' . $image_rounded_size;
$image_radius      =   $params->get('image_radius', '');

$hover_effect   = $params->get('hover_effect', '');
$transition     = $params->get('hover_transition', '');
$transition     = $transition !== '' ? ' as-transition-' . $transition : '';

$card_hover_transition     = $params->get('card_hover_transition', '');

$card_hover_transition     = $card_hover_transition !== '' ? ' as-transition-' . $card_hover_transition : '';

$use_masonry        =   $params->get('use_masonry', 0);

$hover_tog_class = $img_tog_class =$img_eff='';
if (str_starts_with($hover_effect, 'uk-transition')) {
    $hover_tog_class = ' uk-transition-toggle ';
    $img_tog_class = ' uk-transition-opaque ';
}else{
    $img_eff = ' '.$hover_effect.' ';
}

$autoplay       = $params->get('autoplay', 0);
$navigation     = $params->get('navigation', 0);
$dot            = $params->get('dot', 1);
$dot_margin     =  $params->get('dot_margin', '');

$attrs_slider[] = '';
$attrs_slider[] = (  $autoplay  ) ? 'autoplay: 1' : '';
$attrs_slider   = ' data-uk-slider="' . implode( '; ', array_filter( $attrs_slider ) ) . '"';

$enable_slider        =   $params->get('enable_slider', 0);
$slider_wrap='';
$template_context['enable_slider'] = $enable_slider;
$template_context['attrs_slider'] = $attrs_slider;
if($enable_slider){
    $slider_wrap = ' uk-slider-items flex-nowrap ';
}
$template_context['row_class'] = 'row'.($use_masonry ? ' as-masonry as-loading' : '').$row_column_cls.$slider_wrap;

$template_context['is_overlay'] = $layout == 'overlay';
$grids_data = [];
foreach ($grids->data as $key => $grid) {
    $link_target    =   !empty($grid->params->get('link_target', '')) ? ' target="'.$grid->params->get('link_target', '').'"' : '';
    $item_bg_color  =   style::get_color($grid->params->get('item_background_color', ''));
    if($item_bg_color){
        $element->style->child('#grid-'. $grid -> id .' .card')->add_css('background-color', $item_bg_color['light']);
        $element->style_dark->child('#grid-'. $grid -> id .' .card')->add_css('background-color', $item_bg_color['dark']);
    }
    $item_background_overlay  =   style::get_color($grid->params->get('item_background_overlay', ''));
    if($item_background_overlay){
        $element->style->child('#grid-'. $grid -> id .' .card:before')->add_css('background-color', $item_background_overlay['light']);
        $element->style_dark->child('#grid-'. $grid -> id .' .card:before')->add_css('background-color', $item_background_overlay['dark']);
    }
    $item_background_overlay_hover  =   style::get_color($grid->params->get('item_background_overlay_hover', ''));
    if($item_background_overlay_hover){
        $element->style->child('#grid-'. $grid -> id .':hover .card:before')->add_css('background-color', $item_background_overlay_hover['light']);
        $element->style_dark->child('#grid-'. $grid -> id .':hover .card:before')->add_css('background-color', $item_background_overlay_hover['dark']);
    }
    $media          =   '';
    $grid_data = new \stdClass();
    $grid_data->is_image = $grid->params->get('type', '') == 'image';
    $grid_data->is_icon = $grid->params->get('type', '') == 'icon';
    $grid_data->image = $grid->params->get('image', '');
    $grid_data->title = $grid->params->get('title', '');
    $grid_data->has_link = !empty($grid->params->get('link', ''));
    $grid_data->link = $grid->params->get('link', '');
    $grid_data->link_target = $link_target;
    $grid_data->link_class = $media_position == 'bottom' ? 'order-2 ' : '';
    if ($grid->params->get('type', '') == 'image' && $grid->params->get('image', '')) {
        $grid_data->image_cover_class = 'as-image-cover grid-media position-relative overflow-hidden' . $image_border_radius . $hover_tog_class .$img_eff. $transition . ($media_position == 'bottom' ? ' order-2 ' : '');
        $grid_data->image_class = 'tz-img-grid ' .$hover_effect.$img_tog_class . ($image_fullwidth ? 'w-100' : ' uk-width-auto') . ($enable_image_cover || $media_position == 'left' || $media_position == 'right' ? ' object-fit-cover h-100' : '') . ($params->get('card_style', '') == 'none' ? '' : ' card-img-'. $media_position);
    } elseif ($grid->params->get('type', '') == 'icon') {
        switch ($grid->params->get('icon_type', '')) {
            case 'fontawesome':
                $grid_data->icon_class = 'moon-icon '. ($media_position == 'bottom' ? 'order-2 ' : '') .$grid->params->get('fa_icon', '');
                break;
            case 'astroid':
                $grid_data->icon_class = 'moon-icon '. ($media_position == 'bottom' ? 'order-2 ' : '') .$grid->params->get('as_icon', '');
                $document->load_as_icon();
                break;
            default:
                $grid_data->icon_class = 'moon-icon '. ($media_position == 'bottom' ? 'order-2 ' : '') .$grid->params->get('custom_icon', '');
                break;
        }
    }

    $grid_data->grid_id = 'grid-'. $grid -> id;
    $grid_data->grid_class = 'card overflow-hidden ' . $cover_toggle. $card_style . $box_shadow . $box_shadow_hover .$bd_radius . $card_hover_transition . ($enable_grid_match ? ' h-100' : '');
    $grid_data->is_left_right = $media_position == 'left' || $media_position == 'right';
    $grid_data->left_right_wrap_class = 'row g-0'.($vertical_middle ? ' align-items-center' : '');
    $grid_data->media_width_class = $media_width_cls;
    $grid_data->media_on_cover = ($media_position != 'inside' || $media_position == 'cover') && $media_position != 'left_title';

    $grid_data->is_media_position_cover = $media_position == 'cover' && $content_position;
    $grid_data->content_position = $content_position;
    $grid_data->content_hover_transition = $content_hover_transition;

    $grid_data->card_body_class = ($layout == 'overlay' && $enable_image_cover ? ' as-light' : 'order-1 card-body' ) . $card_size;

    $grid_data->is_media_inside = $media_position == 'inside';

    $grid_data->is_meta_before = !empty($grid->params->get('meta', '')) && $meta_position == 'before' || $meta_position !='after';
    $grid_data->meta = $grid->params->get('meta', '');

    $grid_data->has_title = !empty($grid->params->get('title', ''));
    $grid_data->is_left_title = $media_position == 'left_title';
    $grid_data->title = $grid->params->get('title', '');

    $grid_data->is_meta_after = !empty($grid->params->get('meta', '')) && $meta_position == 'after';

    $grid_data->has_description = !empty($grid->params->get('description', ''));
    $grid_data->description = format_text($grid->params->get('description', ''), FORMAT_HTML, ['context' => $this->context]);
    $grid_data->has_button = !empty(!empty($grid->params->get('link', '')) && !empty($grid->params->get('link_title', '')));
    $grid_data->button_class = $button_style !== 'text' ? 'btn btn-' . (intval($button_outline) ? 'outline-' : '') . $button_style . $button_size. $button_bd_radius : 'as-btn-text d-inline-block';
    $grid_data->button_title = $button_style == 'text' ? ''. $grid->params->get('link_title', '') . '' : $grid->params->get('link_title', '');

    if ($grid->params->get('enable_background_image', 0)) {
        $image = $grid->params->get('background_image', '');
        if (!empty($image)) {
            $element->style->child('#grid-' . $grid->id)->child('.card')->add_css('background-image', 'url(' . $image . ')');
            $element->style->child('#grid-' . $grid->id)->child('.card')->add_css('background-repeat', $grid->params->get('background_repeat', ''));
            $element->style->child('#grid-' . $grid->id)->child('.card')->add_css('background-size', $grid->params->get('background_size', ''));
            $element->style->child('#grid-' . $grid->id)->child('.card')->add_css('background-attachment', $grid->params->get('background_attachment', ''));
            $element->style->child('#grid-' . $grid->id)->child('.card')->add_css('background-position', $grid->params->get('background_position', ''));
        }
    }
    $grids_data[] = $grid_data;
}
$template_context['grids_data'] = $grids_data;
//echo '</div>';

if($enable_slider){
    $template_context['navigation'] = $navigation;
    $template_context['dot'] = $dot;
}

if ($use_masonry) {
    $document->load_masonry('#'. $element->id .' .as-masonry');
}
$style->child('.moon-icon')->add_css('font-size', $icon_size.'px');
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
if (!empty($meta_heading_padding)) {
    style::set_spacing_style($element->style->child('.moon-meta'), $meta_heading_padding);
}
if (!empty($meta_heading_radius)) {
    style::set_spacing_style($element->style->child('.moon-meta'), $meta_heading_radius,'radius');
}

$meta_bg       =   style::get_color($params->get('meta_heading_bg', ''));
$style->child('.moon-meta')->add_css('background-color', $meta_bg['light']);
$style_dark->child('.moon-meta')->add_css('background-color', $meta_bg['dark']);

if ($enable_image_cover) {
    $style->child('.as-image-cover')->add_css('height', $min_height . 'px');
}
if ($params->get('card_style', '') == 'custom') {
    $text_color     =   style::get_color($params->get('text_color', ''));
    $style->child('.moon-grid > .card')->add_css('color', $text_color['light']);
    $style_dark->child('.moon-grid > .card')->add_css('color', $text_color['dark']);

    $bg_color       =   style::get_color($params->get('bg_color', ''));
    $style->child('.moon-grid > .card')->add_css('background-color', $bg_color['light']);
    $style_dark->child('.moon-grid > .card')->add_css('background-color', $bg_color['dark']);

    $card_border    =   json_decode($params->get('card_border', ''), true);
    if (!empty($card_border)) {
        style::add_border_style('#'. $element->id . ' .moon-grid > .card', $card_border, 'global', $element->is_root);
    }
}
switch ($overlay_type) {
    case 'color':
        $overlay_color      =   style::get_color($params->get('overlay_color', ''));
        $style->child('.card-img-overlay')->add_css('background-color', $overlay_color['light']);
        $style_dark->child('.card-img-overlay')->add_css('background-color', $overlay_color['dark']);
        break;
    case 'background-color':
        $overlay_gradient   =   $params->get('overlay_gradient', '');
        if (!empty($overlay_gradient)) {
            $style->child('.card-img-overlay')->add_css('background-image', style::get_gradient_value($overlay_gradient));
        }
        break;
}
$media_margin =   $params->get('media_margin', '');

if (!empty($media_margin)) {
    style::set_spacing_style($element->style->child('.grid-media'), $media_margin,'margin');
}
if (!empty($image_radius) && $image_border_radius==' rounded-custom') {
    style::set_spacing_style($element->style->child('.grid-media, .tz-img-grid'), $image_radius,'radius');
}
if (!empty($card_custom_radius) && $border_radius=='custom') {
    style::set_spacing_style($element->style->child('.card'), $card_custom_radius,'radius');
}

$title_color_hover     = style::get_color($params->get('title_color_hover', ''));
$style->child('.card:hover .moon-heading')->add_css('color', $title_color_hover['light']);
$style_dark->child('.card:hover .moon-heading')->add_css('color', $title_color_hover['dark']);

$content_color_hover     = style::get_color($params->get('content_color_hover', ''));
$style->child('.card:hover .moon-text')->add_css('color', $content_color_hover['light']);
$style_dark->child('.card:hover .moon-text')->add_css('color', $content_color_hover['dark']);

$card_button_color_hover     = style::get_color($params->get('card_button_color_hover', ''));
$style->child('.card:hover .btn')->add_css('color', $card_button_color_hover['light']);
$style_dark->child('.card:hover .btn')->add_css('color', $card_button_color_hover['dark']);


$button_font_style   =   $params->get('button_font_style');
if (!empty($button_font_style)) {
    style::render_typography('#'.$element->id.' .btn, #'.$element->id.' .as-btn-text', $button_font_style, null, $element->is_root);
}

$button_color     = style::get_color($params->get('button_color', ''));
$button_color_hover     = style::get_color($params->get('button_color_hover', ''));
$button_bg_color     = style::get_color($params->get('button_bg_color', ''));
$button_bg_color_hover     = style::get_color($params->get('button_bg_color_hover', ''));
$style->child('.btn')->add_css('color', $button_color['light']);
$style_dark->child('.btn')->add_css('color', $button_color['dark']);
$style->child('.btn:hover')->add_css('color', $button_color_hover['light']);
$style_dark->child('.btn:hover')->add_css('color', $button_color_hover['dark']);
$style->child('.btn')->add_css('background-color', $button_bg_color['light']);
$style_dark->child('.btn')->add_css('background-color', $button_bg_color['dark']);
$style->child('.btn:hover')->add_css('background-color', $button_bg_color_hover['light']);
$style_dark->child('.btn:hover')->add_css('background-color', $button_bg_color_hover['dark']);

$button_padding =   $params->get('button_padding', '');

if (!empty($button_padding)) {
    style::set_spacing_style($element->style->child('.btn'), $button_padding);
    style::set_spacing_style($element->style->child('.as-btn-text'), $button_padding);
}
$button_custom_radius      =   $params->get('button_custom_radius', '');
if (!empty($button_custom_radius) && $button_radius=='custom') {
    style::set_spacing_style($element->style->child('.btn'), $button_custom_radius,'radius');
}
$button_custom_margin =   $params->get('button_custom_margin', '');

if (!empty($button_custom_margin)) {
    style::set_spacing_style($element->style->child('.btn'), $button_custom_margin,'margin');
    style::set_spacing_style($element->style->child('.as-btn-text'), $button_custom_margin,'margin');
}
$image_height      =   $params->get('image_height', '');
$image_width      =   $params->get('image_width', '');

$image_height_data = json_decode($image_height, true);
$image_width_data = json_decode($image_width, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($image_width_data)) {
    $style->child('.grid-media')->add_responsive_css('width', $image_width_data, $image_width_data['postfix']);
}
if (json_last_error() === JSON_ERROR_NONE && is_array($image_height_data)) {
    $style->child('.grid-media')->add_responsive_css('height', $image_height_data, $image_height_data['postfix']);
}
if (!empty($dot_margin)) {
    style::set_spacing_style($this->style->child('.uk-dotnav'), $dot_margin, 'margin');
}

$icon_box_width      =   $params->get('icon_box_width', '');
$icon_box_height      =   $params->get('icon_box_height', '');

$icon_box_height_data = json_decode($icon_box_height, true);
$icon_box_width_data = json_decode($icon_box_width, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_box_width_data)) {
    $style->child('.moon-icon')->add_responsive_css('width', $icon_box_width_data, $icon_box_width_data['postfix']);
    $style->child('.moon-icon')->add_css('display', 'inline-flex');
    $style->child('.moon-icon')->add_css('align-items', 'center');
    $style->child('.moon-icon')->add_css('justify-content', 'center');
}
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_box_height_data)) {
    $style->child('.moon-icon')->add_responsive_css('height', $icon_box_height_data, $icon_box_height_data['postfix']);
}
$icon_box_radius      =   $params->get('icon_box_radius', '');
if (!empty($icon_box_radius)) {
    style::set_spacing_style($element->style->child('.moon-icon'), $icon_box_radius,'radius');
}

echo $OUTPUT->render_from_template('local_moon/elements/grid/default', $template_context);