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
global $PAGE;
$show_admin = (int) $this->params->get('show_admin', 1);
if (is_siteadmin() && $show_admin==0) {
    return;
}
$heading = '';
$breadcrumbs = $PAGE->navbar->get_items();
$show_heading = (int) $this->params->get('show_heading', 1);
$show_page_button = (int) $this->params->get('show_page_button', 1);
$lastitem = end($breadcrumbs);
if($lastitem){
    $heading = $lastitem->text;
}

if($heading==''){
    $heading =  $PAGE->heading;
}

$heading_content = $show_heading ? '<h2 class="breadcrumb-heading">' . $heading . '</h2>' : '';
$heading_content .= $show_page_button ? $PAGE->button : '';
if (!empty($heading_content)) {
    $heading_content = '<div class="pagetitle d-flex justify-content-between">' . $heading_content . '</div>';
}

$breadcrumb_html = '<nav aria-label="breadcrumb">';
$breadcrumb_html .= '<ol class="breadcrumb">';
foreach ($breadcrumbs as $key => $item) {
    $url = $item->action instanceof moodle_url ? $item->action->out() : '';
    $text = !empty($url) && $key < count($breadcrumbs) - 1 ? '<a href="' . $url . '">' . $item->text . '</a>' : '<span>' . $item->text . '</span>';
    if ($key == count($breadcrumbs) - 1) {
        $breadcrumb_html .= '<li class="breadcrumb-item active" aria-current="page">' . $text . '</li>';
    } else {
        $breadcrumb_html .= '<li class="breadcrumb-item">' . $text . '</li>';
    }
}
$breadcrumb_html .= '</ol>';
$breadcrumb_html .= '</nav>';
echo "<div class='pageinfo-block'>{$heading_content}{$breadcrumb_html}</div>";

$heading_font_style   =   $this->params->get('heading_font_style');
if (!empty($heading_font_style)) {
    style::render_typography('#'.$this->id.' .breadcrumb-heading', $heading_font_style, null, $this->isRoot);
}

$content_font_style =   $this->params->get('content_font_style');
if (!empty($content_font_style)) {
    style::render_typography('#'.$this->id.' .breadcrumb-item > *', $content_font_style, null, $this->isRoot);
}