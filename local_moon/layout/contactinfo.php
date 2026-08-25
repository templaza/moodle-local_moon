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
use local_moon\library\framework;
global $OUTPUT;
$params = framework::get_theme()->get_params();
$contact_details = $params->get('contact_details', 1);
if (!$contact_details) {
    return;
}
$phone = $params->get('contact_phone_number', '');
$mobile = $params->get('contact_mobile_number', '');
$email = $params->get('contact_email_address', '');
$openhours = $params->get('contact_open_hours', '');
$address = $params->get('contact_address', '');
$contact_display = $params->get('contact_display', 'icons');
$output = '';
if (!empty($address)) {
    $output .= '<span class="moon-contact-address">';
    if ($contact_display === 'icons') $output .= '<i class="fas fa-map-marker-alt me-2"></i>';
    if ($contact_display === 'text') $output .= Text::_('TPL_ASTROID_ADDRESS_LABEL') . ':';
    $output .= htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
    $output .= '</span>';
}
if (!empty($phone)) {
    $output .= '<span class="moon-contact-phone">';
    if ($contact_display === 'icons') $output .= '<i class="fas fa-phone-alt me-2"></i>';
    if ($contact_display === 'text') $output .= Text::_('TPL_ASTROID_PHONE_LABEL') . ':';
    $telHref = 'tel:' . preg_replace('/\s+/', '', $phone);
    $output .= '<a href="' . htmlspecialchars($telHref, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . '</a>';
    $output .= '</span>';
}
if (!empty($mobile)) {
    $output .= '<span class="moon-contact-mobile">';
    if ($contact_display === 'icons') $output .= '<i class="fas fa-mobile-alt me-2"></i>';
    if ($contact_display === 'text') $output .= Text::_('TPL_ASTROID_MOBILE_LABEL') . ':';
    $mobileHref = 'tel:' . preg_replace('/\s+/', '', $mobile);
    $output .= '<a href="' . htmlspecialchars($mobileHref, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($mobile, ENT_QUOTES, 'UTF-8') . '</a>';
    $output .= '</span>';
}
if (!empty($email)) {
    $output .= '<span class="moon-contact-email">';
    if ($contact_display === 'icons') $output .= '<i class="far fa-envelope me-2"></i>';
    if ($contact_display === 'text') $output .= Text::_('JGLOBAL_EMAIL') . ':';
    $output .= '<a href="mailto:' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</a>';
    $output .= '</span>';
}
if (!empty($openhours)) {
    $output .= '<span class="moon-contact-openhours">';
    if ($contact_display === 'icons') $output .= '<i class="far fa-clock me-2"></i>';
    if ($contact_display === 'text') $output .= Text::_('TPL_ASTROID_OPENHOURS_LABEL');
    $output .= htmlspecialchars($openhours, ENT_QUOTES, 'UTF-8');
    $output .= '</span>';
}
$templatecontext = [
    'content' => $output,
    'has_content' => !empty($output),
];

echo $OUTPUT->render_from_template('local_moon/includes/contactinfo', $templatecontext);