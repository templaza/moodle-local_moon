<?php
require_once(__DIR__ . '/../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();
$filename = optional_param('id', '', PARAM_ALPHANUMEXT);
if (!$filename) {
    redirect(new moodle_url('/'));
}
$context = context_system::instance();
$PAGE->set_context($context);

use local_moon\library\Framework;
$theme = Framework::getTheme();
$layout = $theme->getLayout($filename);

$PAGE->set_url(new moodle_url('/local/moon/page.php?id=' . $filename));
$PAGE->set_pagelayout($filename);
$PAGE->set_title($layout['title']);
$PAGE->set_heading($layout['title']);
echo $OUTPUT->header();
echo "";
echo $OUTPUT->footer();