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
use local_moon\library\helper\text;
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
$templatecontext = [];
$templatecontext['has_content'] = false;
$templatecontext['is_icon'] = $contact_display == 'icons';
$templatecontext['has_address'] = !empty($address);
if ($templatecontext['has_address']) {
    $templatecontext['has_content'] = true;
    $item = new \stdClass();
    $item->prefix = $contact_display === 'icons' ? 'fas fa-map-marker-alt me-2' : text::_('TPL_ASTROID_ADDRESS_LABEL') . ':';
    $item->value = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
    $templatecontext['address'] = $item;
}
$templatecontext['has_phone'] = !empty($phone);
if ($templatecontext['has_phone']) {
    $templatecontext['has_content'] = true;
    $item = new \stdClass();
    $item->prefix = $contact_display === 'icons' ? 'fas fa-phone-alt me-2' : text::_('TPL_ASTROID_PHONE_LABEL') . ':';
    $item->link = htmlspecialchars('tel:' . preg_replace('/\s+/', '', $phone), ENT_QUOTES, 'UTF-8') ;
    $item->value = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
    $templatecontext['phone'] = $item;
}
$templatecontext['has_mobile'] = !empty($mobile);
if ($templatecontext['has_mobile']) {
    $templatecontext['has_content'] = true;
    $item = new \stdClass();
    $item->prefix = $contact_display === 'icons' ? 'fas fa-mobile-alt me-2' : text::_('TPL_ASTROID_MOBILE_LABEL') . ':';
    $item->link = htmlspecialchars('tel:' . preg_replace('/\s+/', '', $mobile), ENT_QUOTES, 'UTF-8') ;
    $item->value = htmlspecialchars($mobile, ENT_QUOTES, 'UTF-8');
    $templatecontext['mobile'] = $item;
}
$templatecontext['has_email'] = !empty($email);
if ($templatecontext['has_email']) {
    $templatecontext['has_content'] = true;
    $item = new \stdClass();
    $item->prefix = $contact_display === 'icons' ? 'far fa-envelope me-2' : text::_('JGLOBAL_EMAIL') . ':';
    $item->link = htmlspecialchars('mailto:' . $email, ENT_QUOTES, 'UTF-8');
    $item->value = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $templatecontext['email'] = $item;
}
$templatecontext['has_openhours'] = !empty($openhours);
if ($templatecontext['has_openhours']) {
    $templatecontext['has_content'] = true;
    $item = new \stdClass();
    $item->prefix = $contact_display === 'icons' ? 'far fa-clock me-2' : text::_('TPL_ASTROID_OPENHOURS_LABEL') . ':';
    $item->value = htmlspecialchars($openhours, ENT_QUOTES, 'UTF-8');
    $templatecontext['openhours'] = $item;
}
echo $OUTPUT->render_from_template('local_moon/layout/contactinfo', $templatecontext);