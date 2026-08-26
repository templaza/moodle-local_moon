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
$params         = $this->params;
$element = $this;

$style = $this->style;
$style_dark = $this->style_dark;
$title          = $params->get('title', '');
$html_element   = $params->get('html_element', 'h2');
$font_style     = $params->get('font_style', null);
$use_link       = $params->get('use_link', 0);
$link           = $params->get('link', '');
$add_icon       = $params->get('add_icon', 0);
$icon           = $params->get('icon', '');
$icon_color     = style::get_color($params->get('icon_color', ''));
$style->child('.moon-icon')->add_css('color', $icon_color['light']);
$style_dark->child('.moon-icon')->add_css('color', $icon_color['dark']);

$title_heading_margin=  $params->get('title_heading_margin', '');

$title_clone       = $params->get('title_clone', 0);
$title_clone_txt           = $params->get('title_clone_txt', '');

// Meta
$meta = $params->get('meta_text', '');
$meta_font_style     = $params->get('meta_font_style', null);
$meta_heading_margin =  $params->get('meta_heading_margin', '');
$meta_heading_padding =  $params->get('meta_heading_padding', '');
$meta_position=  $params->get('meta_position', 'before');
$meta_border    =   json_decode($params->get('meta_border', ''), true);

if (!empty($meta_border)) {
    style::add_border_style('#'. $element->id . ' .heading-meta', $meta_border, 'global', $element->isRoot);
}
$meta_radius=  $params->get('meta_radius', '');
if (!empty($meta_radius)) {
    style::set_spacing_style($element->style->child(' .heading-meta'), $meta_radius, 'radius');
}
$meta_cls = '';
$meta_line       = $params->get('meta_line', 0);
if($meta_line==1){
    $meta_cls = ' meta-line';
    $line_height      =   $params->get('line_height', '');
    $line_width      =   $params->get('line_width', '');
    $line_height_data = json_decode($line_height, true);
    $line_width_data = json_decode($line_width, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($line_width_data)) {
        $style->child('.meta-line:before')->add_responsive_css('width', $line_width_data, $line_width_data['postfix']);
    }
    if (json_last_error() === JSON_ERROR_NONE && is_array($line_height_data)) {
        $style->child('.meta-line:before')->add_responsive_css('height', $line_height_data, $line_height_data['postfix']);
    }
    $line_color     = style::get_color($params->get('line_color', ''));
    $style->child('.meta-line:before')->add_css('background-color', $line_color['light']);
    $style_dark->child('.meta-line:before')->add_css('background-color', $line_color['dark']);
}

if (!empty($title)) {
    if (($meta !== '' || $meta_line === 1) && $meta_position === 'before') {
        echo '<div class="heading-meta '.$meta_cls.'">'.$meta.'</div>';
    }
    if ($use_link) {
        echo '<a href="'.$link.'" title="'.$title.'">';
    }
    echo '<'.$html_element.' class="heading">'. ($add_icon && $icon ? '<i class="'.$icon.' moon-icon me-2"></i>' : '') . $title . '</'.$html_element.'>';
    if ($use_link) {
        echo '</a>';
    }
    if($title_clone){
        echo '<div class="heading-clone position-absolute">'.$title_clone_txt.'</div>';
    }
    if (($meta !== '' || $meta_line === 1) && $meta_position === 'after') {
        echo '<div class="heading-meta '.$meta_cls.'">'.$meta.'</div>';
    }
}
if (!empty($font_style)) {
    style::render_typography('#'.$this->id.' .heading', $font_style, null, $this->isRoot);
}
if (!empty($title_heading_margin)) {
    style::set_spacing_style($this->style->child('.heading'), $title_heading_margin, 'margin');
}
if (!empty($meta_font_style)) {
    style::render_typography('#'.$this->id.' .heading-meta', $meta_font_style, null, $this->isRoot);
}
if (!empty($meta_heading_margin)) {
    style::set_spacing_style($this->style->child('.heading-meta'), $meta_heading_margin, 'margin');
}
if (!empty($meta_heading_padding)) {
    style::set_spacing_style($this->style->child('.heading-meta'), $meta_heading_padding);
}

$title_clone_margin=  $params->get('title_clone_margin', '');
if (!empty($title_clone_margin)) {
    style::set_spacing_style($this->style->child('.heading-clone'), $title_clone_margin, 'margin');
}

$clone_font_style     = $params->get('title_clone_font_style', null);
if (!empty($font_style)) {
    style::render_typography('#'.$this->id.' .heading-clone', $clone_font_style, null, $this->isRoot);
}