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

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');
use local_moon\library\element\layout;
use local_moon\library\framework;
// Moon Asset Manager.
$document = framework::get_document();
$params = framework::get_theme()->get_params();

$header = $params->get('header', TRUE);
$header_mode = $params->get('header_mode', 'horizontal');
$layout_container_class = ['moon-layout-container']; // container class
$layout_content_class = ['moon-layout-content'];
$sidebar_content = '';
if ($header && !empty($header_mode) && $header_mode == 'sidebar') {
    $layout_content_class[] = 'has-sidebar';
    $header_sidebar_menu_mode = $params->get('header_sidebar_menu_mode', 'left');
    if ($header_sidebar_menu_mode == 'topbar') {
        $sidebar_position = $params->get('sidebar_position', 'left');
    } else {
        $sidebar_position = $header_sidebar_menu_mode;
    }
    $layout_content_class[] = 'sidebar-dir-' . $sidebar_position;
    array_push($layout_container_class, 'row', 'g-0');
    $layout_content_class[] = 'col';
    $sidebar_content = $document->include('sidebar', [], true);
}

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers', 'moon-layout'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
// In `local/moon/layout/default.php`
$forceblockdraweropen = method_exists($OUTPUT, 'firstview_fakeblocks') ? $OUTPUT->firstview_fakeblocks() : false;

$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$templatecontext = [
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'addblockbutton' => $addblockbutton,
    'document' => $document,
    'layoutbuilder' => layout::render('root'),
    'has_sidebar' => $header_mode == 'sidebar',
    'layout_container_class' => implode(' ', $layout_container_class),
    'layout_content_class' => implode(' ', $layout_content_class),
    'sidebar_content' => $sidebar_content,
];

echo $OUTPUT->render_from_template('local_moon/default', $templatecontext);