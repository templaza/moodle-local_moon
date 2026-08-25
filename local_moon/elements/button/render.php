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
$buttons     = new SubForm($params->get('buttons', ''));
if (!count($buttons->data)) {
    return false;
}
$button_group   =   intval($params->get('button_group', 0));
$gutter         =   $params->get('gutter', 'lg');
$border_radius  =   $params->get('btn_border_radius', '');
$bd_radius      =   $border_radius ? ' ' . $border_radius : '';
$button_size    =   $params->get('button_size', '');

$button_size    =   $button_size ? ' '. $button_size : '';
echo '<div class="'.($button_group ? 'btn-group' : 'as-gutter-' . $gutter).'" role="group">';
foreach ($buttons->data as $key => $button) {
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
    if ($button->params->get('icon', '')) {
        $title      =   $button->params->get('icon_position', '') === 'first' ? '<i class="'.$button->params->get('icon', '').' me-2"></i>' . $title : $title . '<i class="'.$button->params->get('icon', '').' ms-2"></i>';
    }
    $btn_element_size = $button_size;
    if ($button->params->get('button_size', '')) {
        $btn_element_size = ' ' . $button->params->get('button_size', '');
        // Item Padding
        if (trim($button->params->get('button_size', '')) == 'custom') {
            $item_padding   =   $button->params->get('btn_padding', '');
            if (!empty($item_padding)) {
                Style::setSpacingStyle($element->style->child('#btn-'.$button->id), $item_padding);
            }
        }
    }

    // Button Custom Style
    $button_style   =   $button->params->get('button_style', '');
    if ($button_style === 'custom') {
        $color          =   Style::getColor($button->params->get('color', ''));
        $color_hover    =   Style::getColor($button->params->get('color_hover', ''));
        $bgcolor        =   Style::getColor($button->params->get('bgcolor', ''));
        $bgcolor_hover  =   Style::getColor($button->params->get('bgcolor_hover', ''));

        // Color style
        $element->style->child('#btn-'.$button->id)->addCss('color', $color['light']);
        $element->style_dark->child('#btn-'.$button->id)->addCss('color', $color['dark']);
        $element->style->child('#btn-'.$button->id)->hover()->addCss('color', $color_hover['light']);
        $element->style_dark->child('#btn-'.$button->id)->hover()->addCss('color', $color_hover['dark']);

        if (intval($button->params->get('button_outline', ''))) {
            // Background color style
            $element->style->child('#btn-'.$button->id)->addCss('border-color', $bgcolor['light']);
            $element->style_dark->child('#btn-'.$button->id)->addCss('border-color', $bgcolor['dark']);
            $element->style->child('#btn-'.$button->id)->hover()->addCss('border-color', $bgcolor_hover['light']);
            $element->style_dark->child('#btn-'.$button->id)->hover()->addCss('border-color', $bgcolor_hover['dark']);
        } else {
            // Background color style
            $element->style->child('#btn-'.$button->id)->addCss('background-color', $bgcolor['light']);
            $element->style_dark->child('#btn-'.$button->id)->addCss('background-color', $bgcolor['dark']);
            $element->style->child('#btn-'.$button->id)->hover()->addCss('background-color', $bgcolor_hover['light']);
            $element->style_dark->child('#btn-'.$button->id)->hover()->addCss('background-color', $bgcolor_hover['dark']);
        }
    }

    $link_target    =   !empty($button->params->get('link_target', '')) ? ' target="'.$button->params->get('link_target', '').'"' : '';
    $button_class   =   $button_style !== 'text' ? 'btn btn-' . (intval($button->params->get('button_outline', '')) ? 'outline-' : '') . $button_style . $btn_element_size. $bd_radius : 'as-btn-text text-uppercase text-reset';
    $btn_title      =   $button_style == 'text' ? '<small>'. $title . '</small>' : $title;
    echo '<a id="btn-'.$button->id.'" href="' .$button->params->get('link', ''). '" class="' .$button_class . '"'.$link_target.'>'.$btn_title.'</a>';
    $btn_font_style =   $button->params->get('btn_font_style');
    if (!empty($btn_font_style)) {
        Style::renderTypography('#'.$element->id.' #btn-' . $button->id , $btn_font_style, null, $element->isRoot);
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