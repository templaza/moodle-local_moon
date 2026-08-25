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
/**
 * Optional event hooks or callbacks for local_moon.
 * Keep this file lightweight; most logic should be in classes/.
 */
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once($autoload);
}
function local_moon_extend_navigation(global_navigation $nav) {
    // Find Home node (in Moodle 5.0 the id is still 'home').
    $homenode = $nav->find('home', navigation_node::TYPE_ROOTNODE);
    if ($homenode) {
        // Add Submenu 1.
        $homenode->add(
            'submenu1',
            new moodle_url('/local/home_moon/page1.php'),
            navigation_node::TYPE_CUSTOM,
            null,
            'subpage1'
        );

        // Add Submenu 2.
        $homenode->add(
            'submenu2',
            new moodle_url('/local/home_moon/page2.php'),
            navigation_node::TYPE_CUSTOM,
            null,
            'subpage2'
        );
    }
}
function local_moon_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    $fs = get_file_storage();
    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    $file = $fs->get_file($context->id, 'local_moon', $filearea, $itemid, $filepath, $filename);

    if (!$file || $file->is_directory()) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}

function local_moon_render_navbar_output() {
    $theme = framework::get_theme();
    $html = '';
    if (!$theme->is_moon()) {
        return $html;
    }
    $params = $theme->get_params();
    $color_mode_type = $params->get('astroid_color_mode_enable', 0);
    if ($color_mode_type != 1) {
        return $html;
    }
    $color_mode = $theme->get_color_mode();
    if ($color_mode) {
        $enable_color_mode_transform    =   $params->get('enable_color_mode_transform', 0);
        if (!$enable_color_mode_transform) {
            $attributes = [
                'class' => 'form-check-input switcher',
                'type' => 'checkbox',
                'role' => 'switch',
                'aria-label' => 'Color Mode',
                'name' => 'moon_color_mode',
            ];
            if ($color_mode == 'dark') {
                $attributes['checked'] = 'checked';
            }
            $html = html_writer::div(
                html_writer::div(html_writer::empty_tag('input', $attributes), 'form-check form-switch'),
                'd-flex align-items-center moon-color-mode px-2'
            );
        }
    }
    return $html;
}