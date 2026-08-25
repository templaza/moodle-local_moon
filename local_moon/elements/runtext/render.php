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
$style = $this->style;
$texts     = new SubForm($params->get('texts', ''));
if (!count($texts->data)) {
    return false;
}
$text_stroke          = $params->get('text_stroke', '');
$stroke = '';
if($text_stroke=='1'){
    $stroke = ' text-stroke';
}
echo '<div class="ui-text">
      <div class="runtext-container">
      <div class="main-runtext">
      <div class="tz-marquee" >
      <div class="tz-marquee-content uk-flex uk-flex-middle">
';
foreach ($texts->data as $key => $text) {

    echo '<div id="runtext-'.$text->id.'" class="holder">';
    echo '<div class="uk-flex uk-flex-middle" data-uk-height-match>';
    echo '<div class="text-inner '.$stroke.'">'.$text->params->get('title', '').'</div>';
    if ($text->params->get('icon', '')) {
    echo '<div class="text-icon uk-flex uk-flex-middle"><i class="'.$text->params->get('icon', '').'"></i></div>';
    }
    echo '</div>';
    echo '</div>';

    $title_color          =   Style::getColor($text->params->get('title_color', ''));
    $icon_color          =   Style::getColor($text->params->get('icon_color', ''));
    if($title_color){
        $element->style->child('#runtext-'.$text->id.' .text-inner')->addCss('color', $title_color['light']);
        $element->style_dark->child('#runtext-'.$text->id.' .text-inner')->addCss('color', $title_color['dark']);
    }
    if($icon_color){
        $element->style->child('#runtext-'.$text->id.' i')->addCss('color', $icon_color['light']);
        $element->style_dark->child('#runtext-'.$text->id.' i')->addCss('color', $icon_color['dark']);
    }
}
echo '</div></div></div></div></div>';

$text_font_style =   $params->get('text_font_style');
if (!empty($text_font_style)) {
    Style::renderTypography('#'.$element->id.' .text-inner', $text_font_style, null, $element->isRoot);
}

$title_icon_size        =   $params->get('title_icon_size', '30');
$icon_size = json_decode($title_icon_size, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $element->style->child('.text-icon i')->addResponsiveCSS('font-size', $icon_size, $icon_size['postfix']);
}

$icon_margin   =   $params->get('icon_margin', '');
if (!empty($icon_margin)) {
    Style::setSpacingStyle($element->style->child('.text-icon'), $icon_margin, 'margin');
}

$item_margin   =   $params->get('item_margin', '');
if (!empty($item_margin)) {
    Style::setSpacingStyle($element->style->child('.holder'), $item_margin, 'margin');
}