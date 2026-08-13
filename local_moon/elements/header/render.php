<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Header;
use local_moon\library\Helper\Style;
global $OUTPUT, $PAGE, $SITE, $CFG;

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

$theme = Framework::getTheme();
$params = $theme->getParams();

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
    $style = new Style('.moon-logo', '', true);

    if ($default_logo_width !== '' && preg_match('/^\d+(\.\d+)?(px|rem|em|%|vw|vh)?$/', $default_logo_width)) {
        $style->child('.moon-logo-image > .moon-logo-default')->addCss('max-width', $default_logo_width);
    }
    if ($default_logo_height !== '' && preg_match('/^\d+(\.\d+)?(px|rem|em|%|vw|vh)?$/', $default_logo_height)) {
        $style->child('.moon-logo-image > .moon-logo-default')->addCss('max-height', $default_logo_height);
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

$header_options = new Header($mode);
$templatecontext = [
    'output' => $OUTPUT,
    'primarymoremenu' => $primarymenu['moremenu'],
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'header_mode' => $header_mode,
    'header' => $header_options->getOptions(),
    'logo' => $logo,
];

echo $OUTPUT->render_from_template('local_moon/header', $templatecontext);