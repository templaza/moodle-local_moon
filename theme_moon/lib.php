<?php
defined('MOODLE_INTERNAL') || die();
use local_moon\library\Helper\Utilities;
use local_moon\library\Helper\Media;

/**
 * @throws \ScssPhp\ScssPhp\Exception\SassException
 */
function theme_moon_get_main_scss_content($theme) {
    global $CFG;
    $scss = Utilities::getMoonSCSSVariables();
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    $scss .= Utilities::getMoonCss();
    return $scss;
}

/**
 * Serve the files from the theme file areas.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context  $context
 * @param string   $filearea
 * @param array    $args
 * @param bool     $forcedownload
 * @param array    $options
 * @return bool
 */
function theme_moon_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    Media::pluginFile('theme_moon', $context, $filearea, $args, $forcedownload, $options);
    return true;
}