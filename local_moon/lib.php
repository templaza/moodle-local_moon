<?php
defined('MOODLE_INTERNAL') || die();
use local_moon\library\Framework;
/**
 * Optional event hooks or callbacks for local_moon.
 * Keep this file lightweight; most logic should be in classes/.
 */
$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once($autoload);
}
function local_moon_extend_navigation(global_navigation $nav) {
    $node = $nav->add(
        'Custom Layout',
        new moodle_url('/local/moon/index.php'),
        navigation_node::TYPE_CUSTOM,
        null,
        'customlayoutmenu'
    );
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
    $theme = Framework::getTheme();
    $html = '';
    if (!$theme->isMoon()) {
        return $html;
    }
    $params = $theme->getParams();
    $color_mode_type = $params->get('astroid_color_mode_enable', 0);
    if ($color_mode_type != 1) {
        return $html;
    }
    $color_mode = $theme->getColorMode();
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