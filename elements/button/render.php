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
$buttons     = new sub_form($params->get('buttons', ''));
if (!count($buttons->data)) {
    return false;
}
$button_group   =   intval($params->get('button_group', 0));
$gutter         =   $params->get('gutter', 'lg');
$border_radius  =   $params->get('btn_border_radius', '');
$bd_radius      =   $border_radius ? ' ' . $border_radius : '';
$button_size    =   $params->get('button_size', '');

$button_size    =   $button_size ? ' '. $button_size : '';
$template_context['button_class'] = $button_group ? 'btn-group' : 'as-gutter-' . $gutter;
$buttons_data = [];
foreach ($buttons->data as $key => $button) {
    $button_data = new \stdClass();
    if ($button_group && $border_radius === 'rounded-pill') {
        if ($key === 0) {
            $bd_radius = ' rounded-start-pill';
        } elseif ($key === count($buttons->data) - 1) {
            $bd_radius = ' rounded-end-pill';
        } else {
            $bd_radius = '';
        }
    }
    $title = $button->params->get('title', '');
    $button_data->title = $title;
    $button_data->has_icon = !empty($button->params->get('icon', ''));
    $button_data->icon = $button->params->get('icon', '');
    $button_data->icon_position_first = $button->params->get('icon_position', '') === 'first';
    $btn_element_size = $button_size;
    if ($button->params->get('button_size', '')) {
        $btn_element_size = ' ' . $button->params->get('button_size', '');
        // Item Padding
        if (trim($button->params->get('button_size', '')) == 'custom') {
            $item_padding   =   $button->params->get('btn_padding', '');
            if (!empty($item_padding)) {
                style::set_spacing_style($element->style->child('#btn-'.$button->id), $item_padding);
            }
        }
    }

    // Button Custom Style
    $button_style   =   $button->params->get('button_style', '');
    if ($button_style === 'custom') {
        $color          =   style::get_color($button->params->get('color', ''));
        $color_hover    =   style::get_color($button->params->get('color_hover', ''));
        $bgcolor        =   style::get_color($button->params->get('bgcolor', ''));
        $bgcolor_hover  =   style::get_color($button->params->get('bgcolor_hover', ''));

        // Color style
        $element->style->child('#btn-'.$button->id)->add_css('color', $color['light']);
        $element->style_dark->child('#btn-'.$button->id)->add_css('color', $color['dark']);
        $element->style->child('#btn-'.$button->id)->hover()->add_css('color', $color_hover['light']);
        $element->style_dark->child('#btn-'.$button->id)->hover()->add_css('color', $color_hover['dark']);

        if (intval($button->params->get('button_outline', ''))) {
            // Background color style
            $element->style->child('#btn-'.$button->id)->add_css('border-color', $bgcolor['light']);
            $element->style_dark->child('#btn-'.$button->id)->add_css('border-color', $bgcolor['dark']);
            $element->style->child('#btn-'.$button->id)->hover()->add_css('border-color', $bgcolor_hover['light']);
            $element->style_dark->child('#btn-'.$button->id)->hover()->add_css('border-color', $bgcolor_hover['dark']);
        } else {
            // Background color style
            $element->style->child('#btn-'.$button->id)->add_css('background-color', $bgcolor['light']);
            $element->style_dark->child('#btn-'.$button->id)->add_css('background-color', $bgcolor['dark']);
            $element->style->child('#btn-'.$button->id)->hover()->add_css('background-color', $bgcolor_hover['light']);
            $element->style_dark->child('#btn-'.$button->id)->hover()->add_css('background-color', $bgcolor_hover['dark']);
        }
    }


    $link_target    =   !empty($button->params->get('link_target', '')) ? ' target="'.$button->params->get('link_target', '').'"' : '';
    $button_class   =   $button_style !== 'text' ? 'btn btn-' . (intval($button->params->get('button_outline', '')) ? 'outline-' : '') . $button_style . $btn_element_size. $bd_radius : 'as-btn-text text-uppercase text-reset';

    $button_data->class = $button_class;
    $button_data->is_text = $button_style == 'text';
    $button_data->id = 'btn-'.$button->id;
    $button_data->link = $button->params->get('link', '');
    $button_data->link_target = $link_target;
    $buttons_data[] = $button_data;

    $btn_font_style =   $button->params->get('btn_font_style');
    if (!empty($btn_font_style)) {
        style::render_typography('#'.$element->id.' #btn-' . $button->id , $btn_font_style, null, $element->isRoot);
    }
}
$template_context['buttons'] = $buttons_data;
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
echo $OUTPUT->render_from_template('local_moon/elements/button/default', $template_context);