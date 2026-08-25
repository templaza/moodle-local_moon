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
use local_moon\library\Framework;
$params         = $this->params;
$title                  = $params->get('title', '');
$url                    = $params->get('url', '');
$button_size            = $params->get('button_size', 24);
$width                  = $params->get('width', '');
$height                 = $params->get('height', '');
$use_border             = $params->get('use_border', '');
$border_width           = $params->get('border_width', '');
$ripple_color           = Style::getColor($params->get('ripple_color', ''));
$color                  = Style::getColor($params->get('color', ''));
$color_hover            = Style::getColor($params->get('color_hover', ''));
$background_color       = Style::getColor($params->get('background_color', ''));
$background_color_hover = Style::getColor($params->get('background_color_hover', ''));
$border_color           = Style::getColor($params->get('border_color', ''));

if (!empty($url)) {
    echo '<a class="video-button button-ripple d-inline-flex align-items-center justify-content-center rounded-pill" href="'.$url.'" title="'.$title.'" data-fancybox="moon-'.$this->id.'"><span class="d-inline-flex justify-content-center align-items-center"><i class="fas fa-play"></i></span></a>';
    $document = Framework::getDocument();
    $document->loadFancyBox();
    $document->addScriptDeclaration("Fancybox.bind('[data-fancybox=\"moon-{$this->id}\"]');");
    $style = $this->style;
    $style_dark = $this->style_dark;

    $style->child('.video-button')->addCss('font-size', $button_size . 'px');
    $style->child('.video-button i')->addCss('width', $button_size . 'px');
    $style->child('.video-button i')->addCss('height', $button_size . 'px');

    if ($ripple_color) {
        $style->child('.button-ripple:before')->addCss('box-shadow', '0 0 0 0 '.$ripple_color['light']);
        $style->child('.button-ripple:after')->addCss('box-shadow', '0 0 0 0 '.$ripple_color['light']);
        $style_dark->child('.button-ripple:before')->addCss('box-shadow', '0 0 0 0 '.$ripple_color['dark']);
        $style_dark->child('.button-ripple:after')->addCss('box-shadow', '0 0 0 0 '.$ripple_color['dark']);
    }

    $style->child('.video-button')->addCss('color', $color['light']);
    $style_dark->child('.video-button')->addCss('color', $color['dark']);
    $style->child('.video-button')->addCss('background-color', $background_color['light']);
    $style_dark->child('.video-button')->addCss('background-color', $background_color['dark']);

    $style->child('.video-button')->hover()->addCss('color', $color_hover['light']);
    $style_dark->child('.video-button')->hover()->addCss('color', $color_hover['dark']);
    $style->child('.video-button')->hover()->addCss('background-color', $background_color_hover['light']);
    $style_dark->child('.video-button')->hover()->addCss('background-color', $background_color_hover['dark']);


    $width = json_decode($width, true);
    $height = json_decode($height, true);
    if (is_int($width)) {
        $style->child('.video-button')->addCss('width', $width. 'px');
        $style->child('.video-button:before')->addCss('width', $width. 'px');
        $style->child('.video-button:after')->addCss('width', $width. 'px');
    } else {
        if (json_last_error() === JSON_ERROR_NONE && is_array($width)) {
            $this->style->child('.video-button, .video-button:before, .video-button:after')->addResponsiveCSS('width', $width, $width['postfix']);
        }
    }
    if (is_int($height)) {
        $style->child('.video-button')->addCss('height', $height. 'px');
        $style->child('.video-button:before')->addCss('height', $height. 'px');
        $style->child('.video-button:after')->addCss('height', $height. 'px');

    } else {
        if (json_last_error() === JSON_ERROR_NONE && is_array($height)) {
            $this->style->child('.video-button, .video-button:before, .video-button:after')->addResponsiveCSS('height', $height, $height['postfix']);
        }
    }




    if ($use_border) {
        $style->child('.video-button')->addCss('border-style', 'solid');
        $style->child('.video-button')->addCss('border-color', $border_color['light']);
        $style_dark->child('.video-button')->addCss('border-color', $border_color['dark']);
        $style->child('.video-button')->addCss('border-width', $border_width . 'px');
    }
}