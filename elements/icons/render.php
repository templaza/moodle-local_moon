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
global $OUTPUT;
$template_context = [];
$params = $this->params;
$element = $this;
$icons     = new sub_form($params->get('icons', ''));
if (!count($icons->data)) {
    return false;
}
$gutter         =   $params->get('gutter', 'lg');
$border_radius  =   $params->get('btn_border_radius', '');
$bd_radius      =   $border_radius ? ' ' . $border_radius : '';
$button_size    =   $params->get('button_size', '');

$button_size    =   $button_size ? ' '. $button_size : '';
$icons_data = [];
foreach ($icons->data as $key => $icon) {
    $icon_data = new \stdClass();
    $icon_data->title = $icon->params->get('title', '');
    $icon_data->has_icon = $icon->params->get('icon', '') !== '';
    $icon_data->icon = $icon->params->get('icon', '');

    $btn_element_size = $button_size;

    // Button Custom Style

    $color          =   style::get_color($icon->params->get('color', ''));
    $color_hover    =   style::get_color($icon->params->get('color_hover', ''));
    $bgcolor        =   style::get_color($icon->params->get('bgcolor', ''));
    $bgcolor_hover  =   style::get_color($icon->params->get('bgcolor_hover', ''));

    // Color style
    $element->style->child('#icon-'.$icon->id)->add_css('color', $color['light']);
    $element->style_dark->child('#icon-'.$icon->id)->add_css('color', $color['dark']);
    $element->style->child('#icon-'.$icon->id)->hover()->add_css('color', $color_hover['light']);
    $element->style_dark->child('#icon-'.$icon->id)->hover()->add_css('color', $color_hover['dark']);

    // Background color style
    $element->style->child('#icon-'.$icon->id)->add_css('background-color', $bgcolor['light']);
    $element->style_dark->child('#icon-'.$icon->id)->add_css('background-color', $bgcolor['dark']);
    $element->style->child('#icon-'.$icon->id)->hover()->add_css('background-color', $bgcolor_hover['light']);
    $element->style_dark->child('#icon-'.$icon->id)->hover()->add_css('background-color', $bgcolor_hover['dark']);

    $icon_data->has_link = $icon->params->get('link', '') !== '';
    $icon_data->link = $icon->params->get('link', '');
    $icon_data->link_target = !empty($icon->params->get('link_target', '')) ? ' target="'.$icon->params->get('link_target', '').'"' : '';
    $icon_data->id = 'icon-'.$icon->id;

    $title_font_style =   $icon->params->get('title_font_style');
    if (!empty($title_font_style)) {
        style::render_typography('#'.$element->id.' #icon-' . $icon->id , $title_font_style, null, $element->isRoot);
    }
    $icons_data[] = $icon_data;
}
$template_context['icons'] = $icons_data;
// Item Padding
if (trim($button_size) == 'custom') {
    $item_padding   =   $params->get('btn_padding', '');
    if (!empty($item_padding)) {
        style::set_spacing_style($element->style->child('.btn'), $item_padding);
    }
    $button_font_style =   $params->get('button_font_style');
    if (!empty($button_font_style)) {
        style::render_typography('#'.$element->id.' .btn', $button_font_style, null, $element->isRoot);
    }
}
$icon_size        =   $params->get('icon_size', '30');
$icon_size = json_decode($icon_size, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $element->style->child('.moon-icon')->add_responsive_css('font-size', $icon_size, $icon_size['postfix']);
}
$icon_height      =   $params->get('icon_height', '');
$icon_height = json_decode($icon_height, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_height)) {
    $element->style->child('.moon-icon')->add_responsive_css('height', $icon_height, $icon_height['postfix']);
}
$icon_width      =   $params->get('icon_width', '');
$icon_width = json_decode($icon_width, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_width)) {
    $element->style->child('.moon-icon')->add_responsive_css('width', $icon_width, $icon_width['postfix']);
}
$icon_padding   =   $params->get('icon_padding', '');
if (!empty($icon_padding)) {
    style::set_spacing_style($element->style->child('.moon-icon'), $icon_padding);
}
$icon_margin   =   $params->get('icon_margin', '');
if (!empty($icon_margin)) {
    style::set_spacing_style($element->style->child('.moon-icon'), $icon_margin,'margin');
}
$icon_radius  =   $params->get('icon_radius', '');
if (!empty($icon_radius)) {
    style::set_spacing_style($element->style->child('.moon-icon'), $icon_radius,'radius');
}
$icon_border    =   json_decode($params->get('icon_border', ''), true);
if (!empty($icon_border)) {
    style::add_border_style('#'. $element->id . ' .moon-icon', $icon_border, 'global', $element->isRoot);
}
$icon_border_hover    =   json_decode($params->get('icon_border_hover', ''), true);
if (!empty($icon_border_hover)) {
    style::add_border_style('#'. $element->id . ' .moon-icon:hover', $icon_border_hover, 'global', $element->isRoot);
}
$icons_color     = style::get_color($params->get('icons_color', ''));
$element->style->child('.moon-icon')->add_css('color', $icons_color['light']);
$element->style_dark->child('.moon-icon')->add_css('color', $icons_color['dark']);

echo $OUTPUT->render_from_template('local_moon/elements/icons/default', $template_context);