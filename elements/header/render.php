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
use local_moon\library\framework;
use local_moon\library\helper\header;
use local_moon\library\helper\style;
global $OUTPUT, $PAGE, $SITE, $CFG;

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

$theme = framework::get_theme();
$params = $theme->get_params();

$header = $params->get('header', TRUE);
$mode = $params->get('header_mode', 'horizontal');
$header_mode = [
    'is_horizontal' => $header && $mode === 'horizontal',
    'is_stacked' => $header && $mode === 'stacked',
    'is_sidebar' => $header && $mode === 'sidebar',
];

// Logo options
$logo = new \stdClass();
$logo_type = $params->get('logo_type', 'image'); // Logo Type
if (!$OUTPUT->should_display_navbar_logo()) {
    $logo_type = 'text';
}
$logo->class = 'moon-logo-' . $logo_type;
$logo->text = $params->get('logo_text', \format_string($SITE->shortname, true, ['context' => \context_course::instance(SITEID), "escape" => false]));
if ($logo_type == 'text') {
    $logo->is_text = true;
    $logo->tag_line = $params->get('tag_line', ''); // Logo Tagline
    if ($logo->tag_line) {
        $logo->has_tagline = true;
    } else {
        $logo->has_tagline = false;
    }
} else {
    // Logo file
    $logo->is_text = false;
    $logo->class .= ' d-flex align-items-center';
    $system_logo = $OUTPUT->get_compact_logo_url();
    $logo->default = $params->get('default_logo', $system_logo);
    $logo->default_dark = $params->get('default_logo_dark', $system_logo);
    $logo->mobile = $params->get('mobile_logo', $system_logo);
    $logo->mobile_dark = $params->get('mobile_logo_dark', $system_logo);
    $logo->sticky_header = $params->get('sticky_header_logo', $system_logo);
    $logo->sticky_header_dark = $params->get('sticky_header_logo_dark', $system_logo);

    $default_logo_width  = trim((string) $params->get('default_logo_width', ''));
    $default_logo_height = trim((string) $params->get('default_logo_height', ''));
    $style = new style('.moon-logo', '', true);

    if ($default_logo_width !== '' && preg_match('/^\d+(\.\d+)?(px|rem|em|%|vw|vh)?$/', $default_logo_width)) {
        $style->child('.moon-logo-image > .moon-logo-default')->add_css('max-width', $default_logo_width);
    }
    if ($default_logo_height !== '' && preg_match('/^\d+(\.\d+)?(px|rem|em|%|vw|vh)?$/', $default_logo_height)) {
        $style->child('.moon-logo-image > .moon-logo-default')->add_css('max-height', $default_logo_height);
    }
    $style->render();
}

$logo->link_type = $params->get('logo_link_type', 'default');
$logo->is_link = $logo->link_type !== 'none';

$logo->link = $CFG->wwwroot;
$logo->link_target = '_self';
$logo->link_rel = '';

if ($logo->link_type === 'custom') {
    $url = trim((string) $params->get('logo_link_custom', ''));
    // Allow only well-formed absolute URLs; fall back to site root if empty/invalid.
    $logo->link = clean_param($url, PARAM_URL) ?: $CFG->wwwroot;

    if (!empty($params->get('logo_link_target_blank', 0))) {
        $logo->link_target = '_blank';
        $logo->link_rel = 'noopener noreferrer';
    }
} elseif ($logo->link_type === 'none') {
    $logo->link = '';
}

$header_options = new header($mode);
$templatecontext = [
    'output' => $OUTPUT,
    'primarymoremenu' => $primarymenu['moremenu'],
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'header_mode' => $header_mode,
    'header' => $header_options->get_options(),
    'logo' => $logo,
];

echo $OUTPUT->render_from_template('local_moon/header', $templatecontext);