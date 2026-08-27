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

namespace local_moon\library\helper;
defined('MOODLE_INTERNAL') || die;

use local_moon\library\framework;
use local_moon\library\helper\utilities;

class header {
    public string $mode = '';
    public function __construct($mode = 'horizontal') {
        $this->mode = $mode;
    }
    public function get_options(): array
    {
        if (!method_exists($this, $this->mode)) return self::horizontal();
        return self::{$this->mode}();
    }
    public static function horizontal(): array
    {
        $template = framework::get_theme();
        $params = $template->get_params();
        $mode = $params->get('header_horizontal_menu_mode', 'left');
        $header_breakpoint = $params->get('header_breakpoint', 'lg');
        // Block options
        $block_1_type = $params->get('header_block_1_type', 'blank');
        $block_1_position = $params->get('header_block_1_position', '');
        $block_1_custom = $params->get('header_block_1_custom', '');
        $block_2_type = $params->get('header_block_2_type', 'blank');
        $block_2_position = $params->get('header_block_2_position', '');
        $block_2_custom = $params->get('header_block_2_custom', '');
        $block_1 = '';
        if ($block_1_type != 'blank') {
            $block_1 .= '<div class="header-right-block d-none d-'.$header_breakpoint.'-block align-self-center">';
            if ($block_1_type == 'position') {
                $block_1 .= '<div class="header-block-item d-flex justify-content-end align-items-center">'. utilities::load_region($block_1_position, [], 'div') .'</div>';
            }
            if ($block_1_type == 'custom') {
                $block_1 .= '<div class="header-block-item d-flex justify-content-end align-items-center">'. $block_1_custom .'</div>';
            }
            $block_1 .= '</div>';
        }

        // Block 2 options
        $block_2 = '';
        if ($block_2_type != 'blank') {
            $block_2 .= '<div class="header-left-block d-none d-'.$header_breakpoint.'-block align-self-center ms-4">';
            if ($block_2_type == 'position') {
                $block_2 .= '<div class="header-block-item d-flex justify-content-start align-items-center">'. utilities::load_region($block_2_position, [], 'div') .'</div>';
            }
            if ($block_2_type == 'custom') {
                $block_2 .= '<div class="header-block-item d-flex justify-content-start align-items-center">'. $block_2_custom .'</div>';
            }
            $block_2 .= '</div>';
        }

        $class = ['moon-header', 'moon-horizontal-header', 'moon-horizontal-' . $mode . '-header'];
        $nav_class = ['nav', 'moon-nav', 'd-none', 'd-'.$header_breakpoint.'-flex'];
        $nav_wrapper_class = ['align-self-center', 'd-none', 'd-'.$header_breakpoint.'-block'];
        $head_attrs = ' data-megamenu data-megamenu-class=".has-megamenu" data-megamenu-content-class=".megamenu-container" data-dropdown-arrow="'.($params->get('dropdown_arrow', 0) ? 'true' : 'false').'" data-header-offset="true" data-transition-speed="'.$params->get('dropdown_animation_speed', 300).'" data-megamenu-animation="'.$params->get('dropdown_animation_type', 'fade').'" data-easing="'.$params->get('dropdown_animation_ease', 'linear').'" data-moon-trigger="'.$params->get('dropdown_trigger', 'hover').'" data-megamenu-submenu-class=".nav-submenu,.nav-submenu-static"';
        $burger_class = ['d-flex d-'.$header_breakpoint.'-none justify-content-start'];
        return [
            'class' => implode(' ', $class),
            'nav_class' => implode(' ', $nav_class),
            'navWrapperClass' => implode(' ', $nav_wrapper_class),
            'headAttrs' => $head_attrs,
            'burgerClass' => implode(' ', $burger_class),
            'header_breakpoint' => $header_breakpoint,
            'block_1' => $block_1,
            'block_2' => $block_2,
            'is_left' => $mode === 'left',
            'is_right' => $mode === 'right',
            'is_center' => $mode === 'center',
        ];
    }

    public static function stacked(): array
    {
        $document = framework::get_document();
        $template = framework::get_theme();
        $params = $template->get_params();
        $mode = $params->get('header_stacked_menu_mode', 'center');
        $block_1_type = $params->get('header_block_1_type', 'blank');
        $block_1_position = $params->get('header_block_1_position', '');
        $block_1_custom = $params->get('header_block_1_custom', '');
        $block_2_type = $params->get('header_block_2_type', 'blank');
        $block_2_position = $params->get('header_block_2_position', '');
        $block_2_custom = $params->get('header_block_2_custom', '');
        $block_3_type = $params->get('header_block_3_type', 'blank');
        $block_3_position = $params->get('header_block_3_position', '');
        $block_3_custom = $params->get('header_block_3_custom', '');
        $header_breakpoint = $params->get('header_breakpoint', 'lg');
        $odd_menu_items = $params->get('odd_menu_items', 'left');
        $divided_logo_width = $params->get('divided_logo_width', 200);
        $class = ['moon-header', 'moon-stacked-header', 'moon-stacked-' . $mode . '-header'];
        $nav_class = ['nav', 'moon-nav', 'justify-content-center', 'd-flex', 'align-items-center'];
        $nav_class_left = ['nav', 'moon-nav', 'justify-content-left', 'd-flex', 'align-items-left'];
        $nav_class_divided = ['nav', 'moon-nav'];
        $head_attrs = ' data-megamenu data-megamenu-class=".has-megamenu" data-megamenu-content-class=".megamenu-container" data-dropdown-arrow="'.($params->get('dropdown_arrow', 0) ? 'true' : 'false').'" data-header-offset="true" data-transition-speed="'.$params->get('dropdown_animation_speed', 300).'" data-megamenu-animation="'.$params->get('dropdown_animation_type', 'fade').'" data-easing="'.$params->get('dropdown_animation_ease', 'linear').'" data-moon-trigger="'.$params->get('dropdown_trigger', 'hover').'" data-megamenu-submenu-class=".nav-submenu,.nav-submenu-static"';

        $burger_class = match($mode) {
            'center-balance' => ['w-100 d-flex d-'.$header_breakpoint.'-none justify-content-start'],
            default => ['d-flex d-'.$header_breakpoint.'-none justify-content-center']
        };
        if ($mode == 'divided-logo-left') {
            $nav_wrapper_class = ['moon-nav-wraper', 'moon-nav-' . $mode, 'align-self-center', 'd-none', 'd-'.$header_breakpoint.'-block', 'w-100'];
        } else {
            $nav_wrapper_class = ['moon-nav-wraper', 'moon-nav-' . $mode, 'align-self-center', 'px-2', 'd-none', 'd-'.$header_breakpoint.'-block', 'w-100'];
        }
        if ($mode == 'divided-logo-left') {
            $device = match($header_breakpoint) {
                'sm'  => 'landscape_mobile',
                'md'  => 'tablet',
                'lg'  => 'desktop',
                'xl'  => 'large_desktop',
                'xxl' => 'larger_desktop',
                default => 'global',
            };
            $document->add_style_declaration('.col-divided-logo{width: '.$divided_logo_width.'px;}');
            $document->add_style_declaration('.col-divided-logo{width: 100%;}', $device);
        }

        // Block 1 Content
        $block_1 = '';
        if ($block_1_type != 'blank') {
            $block_align = match($mode) {
                'center' => 'center',
                'divided' => 'end',
                default => 'start',
            };
            $block_1 .= '<div class="header-block-item d-flex justify-content-'.$block_align.' align-items-center'.($mode == 'divided-logo-left' ? '' : ' w-100').'">';
            if ($block_1_type == 'position') {
                $block_1 .= utilities::load_region($block_1_position, [], 'div');
            }
            if ($block_1_type == 'custom') {
                $block_1 .= $block_1_custom;
            }
            $block_1 .= '</div>';
        }

        // Block 2 options
        $block_2 = '';
        if ($block_2_type != 'blank') {
            $block_2 .= '<div class="header-block-item d-none d-'.$header_breakpoint.'-flex justify-content-end align-items-center">';
            if ($block_2_type == 'position') {
                $block_2 .= utilities::load_region($block_2_position, [], 'div');
            }
            if ($block_2_type == 'custom') {
                $block_2 .= $block_2_custom;
            }
            $block_2 .= '</div>';
        }

        // Block 3 options
        $block_3 = '';
        if ($block_3_type != 'blank') {
            $block_3 .= '<div class="header-left-block d-none d-'.$header_breakpoint.'-block align-self-center ms-4">';
            if ($block_3_type == 'position') {
                $block_3 .= '<div class="header-block-item d-flex justify-content-start align-items-center">'. utilities::load_region($block_3_position, [], 'div') .'</div>';
            }
            if ($block_3_type == 'custom') {
                $block_3 .= '<div class="header-block-item d-flex justify-content-start align-items-center">'. $block_3_custom .'</div>';
            }
            $block_3 .= '</div>';
        }
        return [
            'class' => implode(' ', $class),
            'nav_class' => implode(' ', $nav_class),
            'navWrapperClass' => implode(' ', $nav_wrapper_class),
            'headAttrs' => $head_attrs,
            'burgerClass' => implode(' ', $burger_class),
            'header_breakpoint' => $header_breakpoint,
            'odd_menu_items' => $odd_menu_items,
            'nav_class_left' => $nav_class_left,
            'nav_class_divided' => $nav_class_divided,
            'is_center_balance' => $mode == 'center-balance',
            'is_seperated' => $mode == 'seperated',
            'is_center' => $mode == 'center',
            'is_divided' => $mode == 'divided',
            'is_divided_logo_left' => $mode == 'divided-logo-left',
            'block_1' => $block_1,
            'block_2' => $block_2,
            'block_3' => $block_3,
        ];
    }
}