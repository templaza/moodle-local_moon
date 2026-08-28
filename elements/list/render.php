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
global $OUTPUT;
$template_context = [];
$params = $this->params;
$element = $this;
$style = $element->style;

$document = framework::get_document();
$document->load_ui_kit();
$list_items     = new sub_form($params->get('list_items', ''));
if (!count($list_items->get_data())) {
    return false;
}
$title_html_element =   $params->get('title_html_element', 'h3');
$template_context['title_html_element'] = $title_html_element;
$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    style::render_typography('#'.$element->id.' .as-list-title', $title_font_style, null, $element->is_root);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    style::render_typography('#'.$element->id.' .as-list-desc', $content_font_style, null, $element->is_root);
}

$item_margin    =   $params->get('item_margin', '');
$item_padding   =   $params->get('item_padding', '');
$vertical_align     =   $params->get('vertical_align', 'uk-flex-top');
$template_context['vertical_align'] = $vertical_align;
$list_style     =   $params->get('list_style', 'ul');
$template_context['list_style'] = $list_style;
$title_width    =   intval($params->get('title_width', 3));
$heading    =   $params->get('title_heading', '');

$tag = match ($list_style) {
    'ol', 'list-group-numbered' => 'ol',
    'list-description' => 'dl',
    default => 'ul',
};

$class = match ($list_style) {
    'list-unstyled', 'list-inline', 'list-group' => $list_style,
    'list-group-flush', 'list-group-numbered' => 'list-group '. $list_style,
    'list-description' => 'row',
    default => ''
};

$class_item = match ($list_style) {
    'list-group', 'list-group-flush', 'list-group-numbered' => 'list-item list-group-item d-flex align-items-start',
    'list-inline' => 'list-item list-inline-item',
    default => 'list-item'
};

$class_item_inner = match ($list_style) {
    'list-group-numbered' => ' ms-2',
    default => ''
};
if($list_style=='custom'){
    $class = 'list-unstyled';
}
$template_context['has_heading'] = !empty($heading);
$template_context['heading'] = $heading;

$template_context['tag'] = $tag;
$template_context['class'] = $class . ' style-'.$list_style;
$template_context['is_list_description'] = $list_style == 'list-description';
$template_context['is_list_custom'] = $list_style == 'custom';
$template_context['is_list_inline'] = $list_style == 'list-inline';
$template_context['is_list_others'] = !$template_context['is_list_inline'] && !$template_context['is_list_custom'] && !$template_context['is_list_description'];
$template_context['title_width'] = $title_width;
$template_context['description_width'] = $title_width < 12 ? 12-$title_width : 12;
$template_context['class_item'] = $class_item;
$template_context['class_item_inner'] = $class_item_inner;
$lists_data = [];
foreach ($list_items->get_data() as $list) {
    $list_item = new \stdClass();
    $icon_type      =   $list->params->get('icon_type', 'fontawesome');
    $icon_color          =   style::get_color($list->params->get('icon_color_item', ''));
    $icon_bg_color          =   style::get_color($list->params->get('icon_bg_item', ''));
    if($icon_color){
        $element->style->child('#tzlist-'.$list->id.' i')->add_css('color', $icon_color['light']);
        $element->style_dark->child('#tzlist-'.$list->id.' i')->add_css('color', $icon_color['dark']);
    }
    if($icon_bg_color){
        $element->style->child('#tzlist-'.$list->id.' .as-list-icon')->add_css('background-color', $icon_bg_color['light']);
        $element->style_dark->child('#tzlist-'.$list->id.' .as-list-icon')->add_css('background-color', $icon_bg_color['dark']);
    }

    if ($icon_type === 'fontawesome') {
        $icon       =   $list->params->get('fa_icon', '');
    } else {
        $icon       =   $list->params->get('custom_icon', '');
    }

    $list_item->id = $list->id;
    $list_item->has_icon = !empty($icon);
    $list_item->icon = $icon;
    $list_item->title = $list->params->get('title', '');
    $list_item->has_title = !empty($list->params->get('title', ''));
    $list_item->has_description = !empty($list->params->get('description', ''));

    $list_item->description = $list->params->get('description', '');
    $lists_data[] = $list_item;
}
$template_context['lists'] = $lists_data;

if (!empty($title_heading_margin)) {
    style::set_spacing_style($element->style->child('.as-list-title'), $title_heading_margin, 'margin');
}

// Item Margin
if (!empty($item_margin)) {
    style::set_spacing_style($element->style->child('.list-item'), $item_margin, 'margin');
}
// Item Padding
if (!empty($item_padding)) {
    style::set_spacing_style($element->style->child('.list-item'), $item_padding);
}
$icon_color   =   style::get_color($params->get('icon_color', ''));
$element->style->child('.as-list-icon i')->add_css('color', $icon_color['light']);
$element->style_dark->child('.as-list-icon i')->add_css('color', $icon_color['dark']);

$icon_padding=  $params->get('icon_padding', '');
if (!empty($icon_padding)) {
    if($list_style === 'custom') {
        style::set_spacing_style($element->style->child('.as-list-icon'), $icon_padding, 'padding');

    }else{
        style::set_spacing_style($element->style->child('.as-list-icon i'), $icon_padding, 'padding');
    }
}
$icon_margin=  $params->get('icon_margin', '');
if (!empty($icon_margin)) {
    style::set_spacing_style($element->style->child('.as-list-icon'), $icon_margin, 'margin');
}
$icon_listsize        =   $params->get('icon_size', '');
$icon_size = json_decode($icon_listsize, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $style->child('.as-list-icon i')->add_responsive_css('font-size', $icon_size, $icon_size['postfix']);
}
$icon_height      =   $params->get('icon_height', '');
$icon_width      =   $params->get('icon_width', '');

$icon_height_data = json_decode($icon_height, true);
$icon_width_data = json_decode($icon_width, true);

if (json_last_error() === JSON_ERROR_NONE && is_array($icon_width_data)) {
    $style->child('.as-list-icon')->add_responsive_css('width', $icon_width_data, $icon_width_data['postfix']);
}
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_height_data)) {
    $style->child('.as-list-icon')->add_responsive_css('height', $icon_height_data, $icon_height_data['postfix']);
}
$icon_border    =   json_decode($params->get('icon_border', ''), true);
if (!empty($icon_border)) {
    style::add_border_style('#'. $element->id . ' .as-list-icon', $icon_border, 'global', $element->is_root);
}
$icon_radius=  $params->get('icon_radius', '');
if (!empty($icon_radius)) {
    style::set_spacing_style($element->style->child('.as-list-icon'), $icon_radius, 'radius');
}
$heading_font_style   =   $params->get('heading_font_style');
if (!empty($heading_font_style)) {
    style::render_typography('#'.$element->id.' .list-heading', $heading_font_style, null, $element->is_root);
}
$heading_margin=  $params->get('heading_margin', '');
if (!empty($heading_margin)) {
    style::set_spacing_style($element->style->child('.list-heading'), $heading_margin, 'margin');
}
echo $OUTPUT->render_from_template('local_moon/elements/list/default', $template_context);