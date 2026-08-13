<?php
defined('MOODLE_INTERNAL') || die();

// Redirect to the Moon theme settings page if the 'section' parameter is set to 'themesettingmoon'.
if (!empty($_GET['section']) && $_GET['section'] === 'themesettingmoon') {
    redirect(new moodle_url('/local/moon/index.php?theme=moon'));
}