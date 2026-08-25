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
use local_moon\library\helper\video;
$params         = $this->params;
$title          = $params->get('heading', '');
$html_element   = $params->get('html_element', 'h2');
$font_style     = $params->get('font_style');
$heading_margin = $params->get('heading_margin', '');
$content        = video::get_video_from_content($params->get('content', ''));
$content        = format_text($content, FORMAT_HTML, ['context' => $this->context]);

$content_font_style= $params->get('content_font_style');

$text_column_cls        =   '';
$xxl_column             =   $params->get('text_column_xxl', '');
$text_column_cls        .=  $xxl_column ? ' as-column-xxl-' . $xxl_column : '';
$xl_column              =   $params->get('text_column_xl', '');
$text_column_cls        .=  $xl_column ? ' as-column-xl-' . $xl_column : '';
$lg_column              =   $params->get('text_column_lg', '');
$text_column_cls        .=  $lg_column ? ' as-column-lg-' . $lg_column : '';
$md_column              =   $params->get('text_column_md', '');
$text_column_cls        .=  $md_column ? ' as-column-md-' . $md_column : '';
$sm_column              =   $params->get('text_column_sm', '');
$text_column_cls        .=  $sm_column ? ' as-column-sm-' . $sm_column : '';
$xs_column              =   $params->get('text_column_xs', '');
$text_column_cls        .=  $xs_column ? ' as-column-' . $xs_column : '';

if (!empty($title)) {
    echo '<'.$html_element.' class="moon-content-heading">'. $title . '</'.$html_element.'>';
}
if (!empty($content)) {
    echo '<div class="moon-content-text'.$text_column_cls.'">'. $content . '</div>';
}

if (!empty($font_style)) {
    style::render_typography('#'.$this->id.' .moon-content-heading', $font_style, null, $this->isRoot);
}
if (!empty($heading_margin)) {
    $heading_style = $this->style->child('.moon-content-heading');
    style::set_spacing_style($heading_style, $heading_margin, 'margin');
}

if (!empty($content_font_style)) {
    style::render_typography('#'.$this->id.' .moon-content-text', $content_font_style, null, $this->isRoot);
    style::render_typography('#'.$this->id.' .moon-content-text *', $content_font_style, null, $this->isRoot);
}