<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

defined('MOODLE_INTERNAL') || die();
use local_moon\library\Framework;
use local_moon\library\Helper\Utilities;

global $OUTPUT, $PAGE;

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

$document   = Framework::getDocument();
$params     = Framework::getTheme()->getParams();

$header = $params->get('header', TRUE);
$header_mode = $params->get('header_mode', 'horizontal');

if (!($header && !empty($header_mode) && $header_mode == 'sidebar')) {
    return;
}

$mode = $params->get('header_sidebar_menu_mode', 'left');
if ($mode == 'topbar') {
    $sidebar_position = $params->get('sidebar_position', 'left');
    $is_topbar = true;
} else {
    $sidebar_position = $mode;
    $is_topbar = false;
}
$header_breakpoint = $params->get('header_breakpoint', 'lg');
$block_1_type = $params->get('header_block_1_type', 'blank');
$block_1_position = $params->get('header_block_1_position', '');
$block_1_custom = $params->get('header_block_1_custom', '');
$block_2_type = $params->get('header_block_2_type', 'blank');
$block_2_position = $params->get('header_block_2_position', '');
$block_2_custom = $params->get('header_block_2_custom', '');
$block_3_type = $params->get('header_block_3_type', 'blank');
$block_3_position = $params->get('header_block_3_position', '');
$block_3_custom = $params->get('header_block_3_custom', '');
$position_count = 0;
// Block 1 Content
$block_1 = '';
$block_2 = '';
$block_3 = '';
if ($mode == 'topbar') {
    $position_count ++;
    if (${'block_'.$position_count.'_type'} != 'blank') {
        ${'block_'.$position_count} .= '<div class="moon-sidebar-block d-none d-lg-flex col-lg justify-content-end moon-sidebar-block-'.$position_count.'">';
        ${'block_'.$position_count} .= '<div class="header-block-item d-flex align-item-center as-gutter-lg">';
        if (${'block_'.$position_count.'_type'} == 'position') {
            $block_1 .= Utilities::loadRegion(${'block_'.$position_count.'_position'}, [], 'div');
        }
        if (${'block_'.$position_count.'_type'} == 'custom') {
            $block_1 .= ${'block_'.$position_count.'_custom'};
        }
        ${'block_'.$position_count} .= '</div>';
        ${'block_'.$position_count} .= '</div>';
    }
}
$position_count ++;
if (${'block_'.$position_count.'_type'} != 'blank') {
    ${'block_'.$position_count} .= '<div class="moon-sidebar-block moon-sidebar-block-'.$position_count.'">';
    ${'block_'.$position_count} .= '<div class="header-block-item">';
    if (${'block_'.$position_count.'_type'} == 'position') {
        $block_1 .= Utilities::loadRegion(${'block_'.$position_count.'_position'}, [], 'div');
    }
    if (${'block_'.$position_count.'_type'} == 'custom') {
        $block_1 .= ${'block_'.$position_count.'_custom'};
    }
    ${'block_'.$position_count} .= '</div>';
    ${'block_'.$position_count} .= '</div>';
}
$position_count ++;
if (${'block_'.$position_count.'_type'} != 'blank') {
    ${'block_'.$position_count} .= '<div class="moon-sidebar-block moon-sidebar-block-'.$position_count.'">';
    ${'block_'.$position_count} .= '<div class="header-block-item">';
    if (${'block_'.$position_count.'_type'} == 'position') {
        $block_1 .= Utilities::loadRegion(${'block_'.$position_count.'_position'}, [], 'div');
    }
    if (${'block_'.$position_count.'_type'} == 'custom') {
        $block_1 .= ${'block_'.$position_count.'_custom'};
    }
    ${'block_'.$position_count} .= '</div>';
    ${'block_'.$position_count} .= '</div>';
}

$header_menu = $params->get('header_menu', 'mainmenu');
$enable_offcanvas = $params->get('enable_offcanvas', FALSE);
$header_mobile_menu = $params->get('header_mobile_menu', '');
$offcanvas_animation = $params->get('offcanvas_animation', 'st-effect-1');
$offcanvas_togglevisibility = $params->get('offcanvas_togglevisibility', 'd-block');
$class = ['moon-header', 'moon-sidebar-header', 'col-12', 'col-xl-auto', 'moon-sidebar-' . $mode, 'sidebar-dir-' . $sidebar_position, 'has-sidebar'];
if ($sidebar_position == 'right') {
    $class[] = 'order-xl-1';
}
$navClass = ['nav', 'moon-nav', 'd-none', 'd-lg-flex'];
$navWrapperClass = ['align-self-center', 'px-2', 'd-none', 'd-lg-block'];

$templatecontext = [
    'output' => $OUTPUT,
    'primarymoremenu' => $primarymenu['moremenu'],
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'class' => implode(' ', $class),
    'navClass' => implode(' ', $navClass),
    'navWrapperClass' => implode(' ', $navWrapperClass),
    'is_topbar' => $is_topbar,
    'block_1' => $block_1,
    'block_2' => $block_2,
    'block_3' => $block_3,
];

echo $OUTPUT->render_from_template('local_moon/header/sidebar', $templatecontext);