<?php
defined('MOODLE_INTERNAL') || die;
global $OUTPUT;
$show_admin = (int) $this->params->get('only_admin', 1);
if (is_siteadmin() && $show_admin==1) {
    echo $OUTPUT->full_header();
}