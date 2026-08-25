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
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;
$icons     = new SubForm($params->get('icons', ''));
if (!count($icons->data)) {
    return false;
}
$gutter         =   $params->get('gutter', 'lg');
$border_radius  =   $params->get('btn_border_radius', '');
$bd_radius      =   $border_radius ? ' ' . $border_radius : '';
$button_size    =   $params->get('button_size', '');

$button_size    =   $button_size ? ' '. $button_size : '';
echo '<div class="icons-group d-flex" role="group">';
foreach ($icons->data as $key => $icon) {

    $title = $icon->params->get('title', '');
    if ($icon->params->get('icon', '')) {
        $title      = '<i class="'.$icon->params->get('icon', '').'"></i>' . $title . '';
    }
    $btn_element_size = $button_size;

    // Button Custom Style

    $color          =   Style::getColor($icon->params->get('color', ''));
    $color_hover    =   Style::getColor($icon->params->get('color_hover', ''));
    $bgcolor        =   Style::getColor($icon->params->get('bgcolor', ''));
    $bgcolor_hover  =   Style::getColor($icon->params->get('bgcolor_hover', ''));

    // Color style
    $element->style->child('#icon-'.$icon->id)->addCss('color', $color['light']);
    $element->style_dark->child('#icon-'.$icon->id)->addCss('color', $color['dark']);
    $element->style->child('#icon-'.$icon->id)->hover()->addCss('color', $color_hover['light']);
    $element->style_dark->child('#icon-'.$icon->id)->hover()->addCss('color', $color_hover['dark']);

    // Background color style
    $element->style->child('#icon-'.$icon->id)->addCss('background-color', $bgcolor['light']);
    $element->style_dark->child('#icon-'.$icon->id)->addCss('background-color', $bgcolor['dark']);
    $element->style->child('#icon-'.$icon->id)->hover()->addCss('background-color', $bgcolor_hover['light']);
    $element->style_dark->child('#icon-'.$icon->id)->hover()->addCss('background-color', $bgcolor_hover['dark']);

    if ($icon->params->get('link', '')) {
        $link_target    =   !empty($icon->params->get('link_target', '')) ? ' target="'.$icon->params->get('link_target', '').'"' : '';
        echo '<a id="icon-'.$icon->id.'" href="' .$icon->params->get('link', ''). '" class="moon-icon d-flex align-items-center justify-content-center" '.$link_target.'>'.$title.'</a>';
    } else {
        echo '<div id="icon-'.$icon->id.'" class="moon-icon d-flex align-items-center justify-content-center">'.$title.'</div>';
    }

    $title_font_style =   $icon->params->get('title_font_style');
    if (!empty($title_font_style)) {
        Style::renderTypography('#'.$element->id.' #icon-' . $icon->id , $title_font_style, null, $element->isRoot);
    }
}
echo '</div>';

// Item Padding
if (trim($button_size) == 'custom') {
    $item_padding   =   $params->get('btn_padding', '');
    if (!empty($item_padding)) {
        Style::setSpacingStyle($element->style->child('.btn'), $item_padding);
    }
    $button_font_style =   $params->get('button_font_style');
    if (!empty($button_font_style)) {
        Style::renderTypography('#'.$element->id.' .btn', $button_font_style, null, $element->isRoot);
    }
}
$icon_size        =   $params->get('icon_size', '30');
$icon_size = json_decode($icon_size, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $element->style->child('.moon-icon')->addResponsiveCSS('font-size', $icon_size, $icon_size['postfix']);
}
$icon_height      =   $params->get('icon_height', '');
$icon_height = json_decode($icon_height, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_height)) {
    $element->style->child('.moon-icon')->addResponsiveCSS('height', $icon_height, $icon_height['postfix']);
}
$icon_width      =   $params->get('icon_width', '');
$icon_width = json_decode($icon_width, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_width)) {
    $element->style->child('.moon-icon')->addResponsiveCSS('width', $icon_width, $icon_width['postfix']);
}
$icon_padding   =   $params->get('icon_padding', '');
if (!empty($icon_padding)) {
    Style::setSpacingStyle($element->style->child('.moon-icon'), $icon_padding);
}
$icon_margin   =   $params->get('icon_margin', '');
if (!empty($icon_margin)) {
    Style::setSpacingStyle($element->style->child('.moon-icon'), $icon_margin,'margin');
}
$icon_radius  =   $params->get('icon_radius', '');
if (!empty($icon_radius)) {
    Style::setSpacingStyle($element->style->child('.moon-icon'), $icon_radius,'radius');
}
$icon_border    =   json_decode($params->get('icon_border', ''), true);
if (!empty($icon_border)) {
    Style::addBorderStyle('#'. $element->id . ' .moon-icon', $icon_border, 'global', $element->isRoot);
}
$icon_border_hover    =   json_decode($params->get('icon_border_hover', ''), true);
if (!empty($icon_border_hover)) {
    Style::addBorderStyle('#'. $element->id . ' .moon-icon:hover', $icon_border_hover, 'global', $element->isRoot);
}
$icons_color     = Style::getColor($params->get('icons_color', ''));
$element->style->child('.moon-icon')->addCss('color', $icons_color['light']);
$element->style_dark->child('.moon-icon')->addCss('color', $icons_color['dark']);