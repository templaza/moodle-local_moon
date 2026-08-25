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

defined('MOODLE_INTERNAL') || die();
use local_moon\library\Helper\Style;
use local_moon\library\Framework;
$document   = Framework::getDocument();
$params     = Framework::getTheme()->getParams();

$enable_social_profiler     = $params->get('enable_social_profiler', 1);
$social_profiles            = $params->get('social_profiles', []);
$style                      = $params->get('social_profiles_style', 1);
$gutter                     = $params->get('social_profiles_gutter', '');
$fontsize                   = $params->get('social_profiles_fontsize', '16px');
$social_icon_color          = Style::getColor($params->get('social_icon_color', ''));
$social_icon_color_hover    = Style::getColor($params->get('social_icon_color_hover', ''));

if (!$enable_social_profiler) return false;

if (!empty($social_profiles)) {
    $social_profiles = json_decode($social_profiles);
}
$class              = $gutter ? 'gx-'.$gutter : '';
$styles             = '';
$social_style       =   new Style('.moon-social-icons', '', true);
$social_style_dark  =   new Style('.moon-social-icons', 'dark', true);
if (!empty($fontsize)) {
    $social_style->addCss('font-size', $fontsize);
}
if (!empty($social_icon_color) && $style == 1) {
    $social_style->link()->addCss('color', $social_icon_color['light']. '!important');
    $social_style_dark->link()->addCss('color', $social_icon_color['dark']. '!important');
}
if (!empty($social_icon_color_hover) && $style == 1) {
    $social_style->link()->hover()->addCss('color', $social_icon_color_hover['light'] . '!important');
    $social_style_dark->link()->hover()->addCss('color', $social_icon_color_hover['dark'] . '!important');
}
$social_style->render();
$social_style_dark->render();
$output = '';
foreach ($social_profiles as $social_profile) {
    switch ($social_profile->title) {
        case 'WhatsApp':
            $social_profile_link = 'https://wa.me/' . $social_profile->link;
            break;
        case 'Telegram':
            $social_profile_link = 'https://t.me/' . $social_profile->link;
            break;
        default:
            $social_profile_link = $social_profile->link;
            break;
    }
    $output .= '<a title="' . ($social_profile->title ? $social_profile->title : 'Social Icon') . '" ' . ($style != 1 ? ' aria-label="' . $social_profile->title . '" style="color:' . $social_profile->color . '"' : '') . ' href="' . $social_profile_link . '" target="_blank" rel="noopener"><i class="' . $social_profile->icon . '"></i></a>';
}
if (!empty($output)) {
    $output = '<div class="moon-social-icons as-gutter-x-lg as-gutter-x-xl@lg'.(!empty($class) ? ' ' . $class : '').'">' . $output . '</div>';
}
echo $output;