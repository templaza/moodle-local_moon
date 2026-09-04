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
use local_moon\library\helper\style;
use local_moon\library\framework;
global $OUTPUT;
$template_context = [];
$params         = $this->params;
$title                  = $params->get('title', '');
$url                    = $params->get('url', '');
$button_size            = $params->get('button_size', 24);
$width                  = $params->get('width', '');
$height                 = $params->get('height', '');
$use_border             = $params->get('use_border', '');
$border_width           = $params->get('border_width', '');
$ripple_color           = style::get_color($params->get('ripple_color', ''));
$color                  = style::get_color($params->get('color', ''));
$color_hover            = style::get_color($params->get('color_hover', ''));
$background_color       = style::get_color($params->get('background_color', ''));
$background_color_hover = style::get_color($params->get('background_color_hover', ''));
$border_color           = style::get_color($params->get('border_color', ''));
$template_context['has_video'] = !empty($url);
$template_context['video_url'] = $url;
$template_context['title'] = $title;
$template_context['data_fancybox'] = 'moon-'.$this->id;
if (!empty($url)) {
    $document = framework::get_document();
    $document->load_fancy_box();
    $document->add_script_declaration("Fancybox.bind('[data-fancybox=\"moon-{$this->id}\"]');");
    $style = $this->style;
    $style_dark = $this->style_dark;

    $style->child('.video-button')->add_css('font-size', $button_size . 'px');
    $style->child('.video-button i')->add_css('width', $button_size . 'px');
    $style->child('.video-button i')->add_css('height', $button_size . 'px');

    if ($ripple_color) {
        $style->child('.button-ripple:before')->add_css('box-shadow', '0 0 0 0 '.$ripple_color['light']);
        $style->child('.button-ripple:after')->add_css('box-shadow', '0 0 0 0 '.$ripple_color['light']);
        $style_dark->child('.button-ripple:before')->add_css('box-shadow', '0 0 0 0 '.$ripple_color['dark']);
        $style_dark->child('.button-ripple:after')->add_css('box-shadow', '0 0 0 0 '.$ripple_color['dark']);
    }

    $style->child('.video-button')->add_css('color', $color['light']);
    $style_dark->child('.video-button')->add_css('color', $color['dark']);
    $style->child('.video-button')->add_css('background-color', $background_color['light']);
    $style_dark->child('.video-button')->add_css('background-color', $background_color['dark']);

    $style->child('.video-button')->hover()->add_css('color', $color_hover['light']);
    $style_dark->child('.video-button')->hover()->add_css('color', $color_hover['dark']);
    $style->child('.video-button')->hover()->add_css('background-color', $background_color_hover['light']);
    $style_dark->child('.video-button')->hover()->add_css('background-color', $background_color_hover['dark']);


    $width = json_decode($width, true);
    $height = json_decode($height, true);
    if (is_int($width)) {
        $style->child('.video-button')->add_css('width', $width. 'px');
        $style->child('.video-button:before')->add_css('width', $width. 'px');
        $style->child('.video-button:after')->add_css('width', $width. 'px');
    } else {
        if (json_last_error() === JSON_ERROR_NONE && is_array($width)) {
            $this->style->child('.video-button, .video-button:before, .video-button:after')->add_responsive_css('width', $width, $width['postfix']);
        }
    }
    if (is_int($height)) {
        $style->child('.video-button')->add_css('height', $height. 'px');
        $style->child('.video-button:before')->add_css('height', $height. 'px');
        $style->child('.video-button:after')->add_css('height', $height. 'px');

    } else {
        if (json_last_error() === JSON_ERROR_NONE && is_array($height)) {
            $this->style->child('.video-button, .video-button:before, .video-button:after')->add_responsive_css('height', $height, $height['postfix']);
        }
    }

    if ($use_border) {
        $style->child('.video-button')->add_css('border-style', 'solid');
        $style->child('.video-button')->add_css('border-color', $border_color['light']);
        $style_dark->child('.video-button')->add_css('border-color', $border_color['dark']);
        $style->child('.video-button')->add_css('border-width', $border_width . 'px');
    }
}
echo $OUTPUT->render_from_template('local_moon/elements/video_button/default', $template_context);