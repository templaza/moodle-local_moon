<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\library\Helper;
defined('MOODLE_INTERNAL') || die;

use local_moon\library\Framework;
use local_moon\library\Helper\Utilities;

class Header {
    public string $mode = '';
    public function __construct($mode = 'horizontal') {
        $this->mode = $mode;
    }
    public function getOptions(): array
    {
        if (!method_exists($this, $this->mode)) return self::horizontal();
        return self::{$this->mode}();
    }
    public static function horizontal(): array
    {
        $template = Framework::getTheme();
        $params = $template->getParams();
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
                $block_1 .= '<div class="header-block-item d-flex justify-content-end align-items-center">'. Utilities::loadRegion($block_1_position, [], 'div') .'</div>';
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
                $block_2 .= '<div class="header-block-item d-flex justify-content-start align-items-center">'. Utilities::loadRegion($block_2_position, [], 'div') .'</div>';
            }
            if ($block_2_type == 'custom') {
                $block_2 .= '<div class="header-block-item d-flex justify-content-start align-items-center">'. $block_2_custom .'</div>';
            }
            $block_2 .= '</div>';
        }

        $class = ['moon-header', 'moon-horizontal-header', 'moon-horizontal-' . $mode . '-header'];
        $navClass = ['nav', 'moon-nav', 'd-none', 'd-'.$header_breakpoint.'-flex'];
        $navWrapperClass = ['align-self-center', 'd-none', 'd-'.$header_breakpoint.'-block'];
        $headAttrs = ' data-megamenu data-megamenu-class=".has-megamenu" data-megamenu-content-class=".megamenu-container" data-dropdown-arrow="'.($params->get('dropdown_arrow', 0) ? 'true' : 'false').'" data-header-offset="true" data-transition-speed="'.$params->get('dropdown_animation_speed', 300).'" data-megamenu-animation="'.$params->get('dropdown_animation_type', 'fade').'" data-easing="'.$params->get('dropdown_animation_ease', 'linear').'" data-moon-trigger="'.$params->get('dropdown_trigger', 'hover').'" data-megamenu-submenu-class=".nav-submenu,.nav-submenu-static"';
        $burgerClass = ['d-flex d-'.$header_breakpoint.'-none justify-content-start'];
        return [
            'class' => implode(' ', $class),
            'navClass' => implode(' ', $navClass),
            'navWrapperClass' => implode(' ', $navWrapperClass),
            'headAttrs' => $headAttrs,
            'burgerClass' => implode(' ', $burgerClass),
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
        $document = Framework::getDocument();
        $template = Framework::getTheme();
        $params = $template->getParams();
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
        $navClass = ['nav', 'moon-nav', 'justify-content-center', 'd-flex', 'align-items-center'];
        $navClassLeft = ['nav', 'moon-nav', 'justify-content-left', 'd-flex', 'align-items-left'];
        $navClassDivided = ['nav', 'moon-nav'];
        $headAttrs = ' data-megamenu data-megamenu-class=".has-megamenu" data-megamenu-content-class=".megamenu-container" data-dropdown-arrow="'.($params->get('dropdown_arrow', 0) ? 'true' : 'false').'" data-header-offset="true" data-transition-speed="'.$params->get('dropdown_animation_speed', 300).'" data-megamenu-animation="'.$params->get('dropdown_animation_type', 'fade').'" data-easing="'.$params->get('dropdown_animation_ease', 'linear').'" data-moon-trigger="'.$params->get('dropdown_trigger', 'hover').'" data-megamenu-submenu-class=".nav-submenu,.nav-submenu-static"';

        $burgerClass = match($mode) {
            'center-balance' => ['w-100 d-flex d-'.$header_breakpoint.'-none justify-content-start'],
            default => ['d-flex d-'.$header_breakpoint.'-none justify-content-center']
        };
        if ($mode == 'divided-logo-left') {
            $navWrapperClass = ['moon-nav-wraper', 'moon-nav-' . $mode, 'align-self-center', 'd-none', 'd-'.$header_breakpoint.'-block', 'w-100'];
        } else {
            $navWrapperClass = ['moon-nav-wraper', 'moon-nav-' . $mode, 'align-self-center', 'px-2', 'd-none', 'd-'.$header_breakpoint.'-block', 'w-100'];
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
            $document->addStyleDeclaration('.col-divided-logo{width: '.$divided_logo_width.'px;', $device ?? 'global');
        }

        // Block 1 Content
        $block_1 = '';
        if ($block_1_type != 'blank') {
            $block_align = $mode == 'center' ? 'center' : 'start';
            $block_1 .= '<div class="w-100 header-block-item d-flex justify-content-'.$block_align.' align-items-center">';
            if ($block_1_type == 'position') {
                $block_1 .= Utilities::loadRegion($block_1_position, [], 'div');
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
                $block_2 .= Utilities::loadRegion($block_2_position, [], 'div');
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
                $block_3 .= '<div class="header-block-item d-flex justify-content-start align-items-center">'. Utilities::loadRegion($block_3_position, [], 'div') .'</div>';
            }
            if ($block_3_type == 'custom') {
                $block_3 .= '<div class="header-block-item d-flex justify-content-start align-items-center">'. $block_3_custom .'</div>';
            }
            $block_3 .= '</div>';
        }
        return [
            'class' => implode(' ', $class),
            'navClass' => implode(' ', $navClass),
            'navWrapperClass' => implode(' ', $navWrapperClass),
            'headAttrs' => $headAttrs,
            'burgerClass' => implode(' ', $burgerClass),
            'header_breakpoint' => $header_breakpoint,
            'odd_menu_items' => $odd_menu_items,
            'navClassLeft' => $navClassLeft,
            'navClassDivided' => $navClassDivided,
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