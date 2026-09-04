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

defined('MOODLE_INTERNAL') || die();
use local_moon\library\helper\style;
use local_moon\library\framework;
global $OUTPUT;
$document   = framework::get_document();
$params     = framework::get_theme()->get_params();

$enable_social_profiler     = $params->get('enable_social_profiler', 1);
$social_profiles            = $params->get('social_profiles', []);
$style                      = $params->get('social_profiles_style', 1);
$gutter                     = $params->get('social_profiles_gutter', '');
$fontsize                   = $params->get('social_profiles_fontsize', '16px');
$social_icon_color          = style::get_color($params->get('social_icon_color', ''));
$social_icon_color_hover    = style::get_color($params->get('social_icon_color_hover', ''));

if (!$enable_social_profiler) return false;

if (!empty($social_profiles)) {
    $social_profiles = json_decode($social_profiles);
}
$class              = $gutter ? 'gx-'.$gutter : '';
$social_style       =   new style('.moon-social-icons', '', true);
$social_style_dark  =   new style('.moon-social-icons', 'dark', true);
if (!empty($fontsize)) {
    $social_style->add_css('font-size', $fontsize);
}
if (!empty($social_icon_color) && $style == 1) {
    $social_style->link()->add_css('color', $social_icon_color['light']. '!important');
    $social_style_dark->link()->add_css('color', $social_icon_color['dark']. '!important');
}
if (!empty($social_icon_color_hover) && $style == 1) {
    $social_style->link()->hover()->add_css('color', $social_icon_color_hover['light'] . '!important');
    $social_style_dark->link()->hover()->add_css('color', $social_icon_color_hover['dark'] . '!important');
}
$social_style->render();
$social_style_dark->render();
$template_context = [];
if (!empty($social_profiles)) {
    $template_context['has_content'] = true;
} else {
    $template_context['has_content'] = false;
}
$template_context['wrapper_classes'] = 'moon-social-icons as-gutter-x-lg as-gutter-x-xl@lg'.(!empty($class) ? ' ' . $class : '');
$template_context['is_color'] = $style != 1;
$output = '';
foreach ($social_profiles as $social_profile) {
    switch ($social_profile->title) {
        case 'WhatsApp':
            $social_profile->link = 'https://wa.me/' . $social_profile->link;
            break;
        case 'Telegram':
            $social_profile->link = 'https://t.me/' . $social_profile->link;
            break;
    }
}
$template_context['social_profiles'] = $social_profiles;
echo $OUTPUT->render_from_template('local_moon/layout/social', $template_context);