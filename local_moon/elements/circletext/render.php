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
$params         = $this->params;
$element = $this;

$style = $this->style;
$style_dark = $this->style_dark;
$title          = $params->get('title', '');
$font_style     = $params->get('font_style', null);
$use_link       = $params->get('use_link', 0);
$link           = $params->get('link', '');

// Meta
$title_border    =   json_decode($params->get('title_border', ''), true);
if (!empty($title_border)) {
    Style::addBorderStyle('#'. $element->id . ' .heading-meta', $title_border, 'global', $element->isRoot);
}
$hover_rotate         = $params->get('hover_rotate', 0);
$auto_rotate         = $params->get('auto_rotate', 0);
$title    =   $params->get('title','');

$title_icon    =   $params->get('title_icon','');
$title_pos    =   $params->get('title_pos','uk-position-relative');
$box_pos    =   $params->get('box_position','');

if (!empty($title)) {
    $cl  = $auto_rot ='';
    if($hover_rotate){
        $cl = ' rotate';
    }
    if($auto_rotate){
        $auto_rot = ' auto_rotate ';
    }
    $media = '';
    if ($title_icon) {
        $media .= '<i class="' . $title_icon . '" aria-hidden="true"></i>';
    }

    echo '<div class="ui-text '.$cl.' '.$title_pos.' '.$box_pos.' ">';
    ?>
    <svg class="circletext <?php echo $auto_rot;?>" viewBox="0 0 100 100" >
        <defs>
            <path id="circle"
                  d="
    M 50, 50
    m -37, 0
    a 37,37 0 1,1 74,0
    a 37,37 0 1,1 -74,0"/>
        </defs>
        <text>
            <textPath xlink:href="#circle">
                <?php
                echo $title;
                ?>
            </textPath>
        </text>

    </svg>
    <div class="circletext-icon uk-position-cover">
        <div class="uk-position-center"><?php echo $media; ?></div>
    </div>
    <?php
    echo '</div>';

}
if (!empty($font_style)) {
    Style::renderTypography('#'.$this->id.' .circletext textPath', $font_style, null, $this->isRoot);
}
$title_bg_color     = Style::getColor($params->get('title_bg_color', ''));
$style->child('.circletext')->addCss('background-color', $title_bg_color['light']);
$style_dark->child('.circletext')->addCss('background-color', $title_bg_color['dark']);

$title_border    =   json_decode($params->get('title_border', ''), true);
if (!empty($title_border)) {
    Style::addBorderStyle('#'. $element->id . ' .circletext', $title_border, 'global', $element->isRoot);
}

$title_radius  =   $params->get('title_radius', '');
if (!empty($title_radius)) {
    Style::setSpacingStyle($element->style->child('svg.circletext'), $title_radius,'radius');
}

$title_padding   =   $params->get('title_padding', '');
if (!empty($title_padding)) {
    Style::setSpacingStyle($element->style->child('svg.circletext'), $title_padding);
}
$title_margin   =   $params->get('title_margin', '');
if (!empty($title_margin)) {
    Style::setSpacingStyle($element->style->child('.ui-text'), $title_margin,'margin');
}

$title_width      =   $params->get('title_width', '');
$box_width = json_decode($title_width, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($box_width)) {
    $style->child('svg.circletext')->addResponsiveCSS('max-width', $box_width, $box_width['postfix']);
    $style->child('.circletext-icon')->addResponsiveCSS('max-width', $box_width, $box_width['postfix']);
}

$title_icon_size        =   $params->get('title_icon_size', '30');
$icon_size = json_decode($title_icon_size, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $style->child('.circletext-icon i')->addResponsiveCSS('font-size', $icon_size, $icon_size['postfix']);
}

$title_icon_color     = Style::getColor($params->get('title_icon_color', ''));
$style->child('.circletext-icon i')->addCss('color', $title_icon_color['light']);
$style_dark->child('.circletext-icon i')->addCss('color', $title_icon_color['dark']);