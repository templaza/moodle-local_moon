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
$accordions     = new sub_form($params->get('accordions', ''));
if (!count($accordions->data)) {
    return false;
}
$document = framework::get_document();
$document->load_ui_kit();
$box_shadow         =   $params->get('box_shadow', '');
$box_shadow         =   $box_shadow ? ' ' . $box_shadow : '';
$box_shadow_hover   =   $params->get('box_shadow_hover', '');
$box_shadow_hover   =   $box_shadow_hover ? ' ' . $box_shadow_hover : '';

$style          = $params->get('style', '');
$style          = $params->get('style', '');
$style          = $style !== '' ? ' '. $style : '';

$collapse       = $params->get('collapse', '');
$always_open    = $params->get('always_open', 0);

$icon_type = $params->get('icon_type', '');
$fa_icon = $params->get('fa_icon', '');
$icon =$icon_cl= '';

if($icon_type){
    if($fa_icon){
        $icon = '<i class="'.$fa_icon.'"></i>';
        $icon_cl = 'custom_icon';
    }
}

echo '<div class="accordion'.$style.' '.$icon_cl.'" id="accordion-'.$element->id.'">';
foreach ($accordions->data as $key => $accordion) {
    $title_color  =   style::get_color($accordion->params->get('title_color', ''));
    $title_bg_color  =   style::get_color($accordion->params->get('title_bg_color', ''));
    if($title_bg_color){
        $element->style->child('#accordion-'. $accordion -> id .' .accordion-header button')->add_css('background-color', $title_bg_color['light']);
        $element->style_dark->child('#accordion-'. $accordion -> id .' .accordion-header button')->add_css('background-color', $title_bg_color['dark']);
    }
    if($title_color){
        $element->style->child('#accordion-'. $accordion -> id .' .accordion-header button')->add_css('color', $title_color['light']);
        $element->style_dark->child('#accordion-'. $accordion -> id .' .accordion-header button')->add_css('color', $title_color['dark']);
    }

    echo '<div id="accordion-'. $accordion -> id .'" class="accordion-item '.$box_shadow . $box_shadow_hover.'">';

    echo '<h2 class="accordion-header ">';
    echo '<button class="uk-flex uk-flex-between accordion-button'.($key != 0 || $collapse === 'close-all' ? ' collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'.$element->id.$key.'" aria-expanded="true" aria-controls="collapse'.$element->id.$key.'">'.$accordion->params->get('title', '').' '.$icon.'</button>';
    echo '</h2>';

    echo '<div id="collapse'.$element->id.$key.'" class="accordion-collapse collapse'.($key == 0 && $collapse === '' ? ' show' : '').'"'.(!$always_open ? ' data-bs-parent="#accordion-'.$element->id.'"' : '').'>';
    echo '<div class="accordion-body">'. $accordion->params->get('content', '') . '</div>';
    echo '</div>';

    echo '</div>';
}
echo '</div>';

$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    style::render_typography('#'.$element->id.' .accordion-button', $title_font_style, null, $element->isRoot);
}

$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    style::render_typography('#'.$element->id.' .accordion-body', $content_font_style, null, $element->isRoot);
}

$color          = style::get_color($params->get('color', ''));
$color_hover    = style::get_color($params->get('color_hover', ''));
$color_active   = style::get_color($params->get('color_active', ''));
$bgcolor        = style::get_color($params->get('bgcolor', ''));
$bgcolor_hover  = style::get_color($params->get('bgcolor_hover', ''));
$bgcolor_active = style::get_color($params->get('bgcolor_active', ''));
$icon_color = style::get_color($params->get('icon_color', ''));

$bgcolor_content        = style::get_color($params->get('bgcolor_content', ''));

// Color style
$element->style->child('.accordion-button')->add_css('color', $color['light']);
$element->style_dark->child('.accordion-button')->add_css('color', $color['dark']);
$element->style->child('.accordion-button')->hover()->add_css('color', $color_hover['light']);
$element->style_dark->child('.accordion-button')->hover()->add_css('color', $color_hover['dark']);
$element->style->child('.accordion-button:not(.collapsed)')->add_css('color', $color_active['light']);
$element->style_dark->child('.accordion-button:not(.collapsed)')->add_css('color', $color_active['dark']);

$element->style->child('.accordion-button::after')->add_css('color', $icon_color['light']);
$element->style_dark->child('.accordion-button::after')->add_css('color', $icon_color['dark']);

// Background color style
$element->style->child('.accordion-button')->add_css('background-color', $bgcolor['light']);
$element->style_dark->child('.accordion-button')->add_css('background-color', $bgcolor['dark']);
$element->style->child('.accordion-button')->hover()->add_css('background-color', $bgcolor_hover['light']);
$element->style_dark->child('.accordion-button')->hover()->add_css('background-color', $bgcolor_hover['dark']);
$element->style->child('.accordion-button:not(.collapsed)')->add_css('background-color', $bgcolor_active['light']);
$element->style_dark->child('.accordion-button:not(.collapsed)')->add_css('background-color', $bgcolor_active['dark']);

//content background

$element->style->child('.accordion-body')->add_css('background-color', $bgcolor_content['light']);
$element->style_dark->child('.accordion-body')->add_css('background-color', $bgcolor_content['dark']);

$title_padding   =   $params->get('title_padding', '');
if (!empty($title_padding)) {
    style::set_spacing_style($element->style->child('.accordion-button'), $title_padding);
}
$content_padding   =   $params->get('content_padding', '');
if (!empty($content_padding)) {
    style::set_spacing_style($element->style->child('.accordion-body'), $content_padding);
}
$title_border    =   json_decode($params->get('title_border', ''), true);
if (!empty($title_border)) {
    style::add_border_style('#'. $element->id . ' .accordion-button', $title_border, 'global', $element->isRoot);
}
$item_radius  =   $params->get('item_radius', '');
if (!empty($item_radius)) {
    style::set_spacing_style($element->style->child('.accordion-item'), $item_radius,'radius');
}
$title_radius  =   $params->get('title_radius', '');
if (!empty($title_radius)) {
    style::set_spacing_style($element->style->child('.accordion-button'), $title_radius,'radius');
}
$item_margin=  $params->get('item_margin', '');
if (!empty($item_margin)) {
    style::set_spacing_style($element->style->child('.accordion-item'), $item_margin, 'margin');
}