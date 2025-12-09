<?php
require_once(__DIR__ . '/../../config.php');
defined('MOODLE_INTERNAL') || die();
use local_moon\library\Framework;
use local_moon\library\Helper\Settings;
use local_moon\library\Helper\Constants;
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moon/page.php'));
$PAGE->set_pagelayout('Default-6901cd885c9c1');
$PAGE->set_title('My Custom Page');
$PAGE->set_heading('Custom Layout Page');
echo $OUTPUT->header();
echo "";
echo $OUTPUT->footer();