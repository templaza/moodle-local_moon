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

namespace local_moon\library\helper;

use local_moon\library\framework;

defined('MOODLE_INTERNAL') or die;

class style
{
    public $_selector, $_css = ['mobile' => [], 'landscape_mobile' => [], 'tablet' => [], 'desktop' => [], 'large_desktop' => [], 'larger_desktop' => [], 'global' => []], $_styles = ['mobile' => [], 'landscape_mobile' => [], 'tablet' => [], 'desktop' => [], 'large_desktop' => [], 'larger_desktop' => [], 'global' => []], $_child = [];
    public static array $_devices = ['mobile', 'landscape_mobile', 'tablet', 'desktop', 'large_desktop', 'larger_desktop', 'global'];

    protected $_hover = null, $_focus = null, $_active = null, $_link = null;
    public bool $_onFile = false;
    public string $_mode = '';
    public function __construct($selectors, $mode = '', $on_file = false, $parent_element = '')
    {
        if (is_array($selectors)) {
            for ($key = 0; $key < count($selectors); $key ++) {
                $selector = !empty($parent_element) ? $parent_element .' '. $selectors[$key] : $selectors[$key];
                $selectors[$key]    =   $mode ? '[data-bs-theme='.$mode.'] '. $selector : $selector;
            }
            $this->_selector    =   implode(', ', $selectors);
        } else {
            $selector = !empty($parent_element) ? $parent_element .' '. $selectors : $selectors;
            $this->_selector    =   $mode ? '[data-bs-theme='.$mode.'] '. $selector : $selector;
        }
        $this->_mode = $mode;
        $this->_onFile = $on_file;
    }

    protected function _selectorize($postfix = null, $prefix = null): string
    {
        $return = [];
        $selectors = explode(',', $this->_selector);
        if ($postfix !== null) {
            $postfixes = explode(',', $postfix);
            foreach ($selectors as $selector) {
                foreach ($postfixes as $postfix) {
                    $return[] = trim($selector) . $postfix;
                }
            }
        }
        if ($prefix !== null) {
            $prefixes = explode(',', $prefix);
            foreach ($selectors as $selector) {
                foreach ($prefixes as $prefix) {
                    $return[] = $prefix . trim($selector);
                }
            }
        }
        return implode(', ', $return);
    }

    public function hover($class = ''): style
    {
        if ($this->_hover === null) {
            $this->_hover = new style($this->_selectorize(':hover' . (empty($class) ? '' : ',' . $class)), '', $this->_onFile);
        }
        return $this->_hover;
    }

    public function focus($class = ''): style
    {
        if ($this->_focus === null) {
            $this->_focus = new style($this->_selectorize(':focus' . (empty($class) ? '' : ',' . $class)), '', $this->_onFile);
        }
        return $this->_focus;
    }

    public function active($class = ''): style
    {
        if ($this->_active === null) {
            $this->_active = new style($this->_selectorize(':active' . (empty($class) ? '' : ',' . $class)), '', $this->_onFile);
        }
        return $this->_active;
    }

    public function link($ref = 'child', $subfix = ''): style
    {
        if ($this->_link === null) {
            if ($ref == 'child') {
                $this->_link = new style($this->_selectorize(' a'. $subfix), '', $this->_onFile);
            } else if ($ref == 'self') {
                $this->_link = new style($this->_selectorize(null, 'a'. $subfix), '', $this->_onFile);
            } else {
                $this->_link = new style($this->_selectorize(null, 'a'.$subfix.' '), '', $this->_onFile);
            }
        }
        return $this->_link;
    }

    public function child($selector)
    {
        if ($this->_has_child($selector)) {
            return $this->_get_child($selector);
        } else {
            return $this->add_child($selector);
        }
    }

    protected function _has_child($selector): bool
    {
        $selector = $this->_child_selector($selector);
        return isset($this->_child[text::slugify($selector)]);
    }

    protected function _get_child($selector)
    {
        $selector = $this->_child_selector($selector);
        if (isset($this->_child[text::slugify($selector)])) {
            return $this->_child[text::slugify($selector)];
        } else {
            return null;
        }
    }

    public function add_css($property, $value, $device = 'global'): static
    {
        if (empty($value)) {
            return $this;
        }
        $this->_css[$device][$property] = $value;
        return $this;
    }

    public function add_responsive_css($property, $value, $unit = ''): static
    {
        if (empty($value)) {
            return $this;
        }
        if (is_string($value)) {
            $json = json_decode($value, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->_css['global'][$property] = $value . $unit;
                return $this;
            } else {
                $value = $json;
            }
        }
        if (is_array($value)) {
            foreach (self::$_devices as $device) {
                if (!empty($value[$device])) {
                    $this->_css[$device][$property] = $value[$device] . (is_array($unit) && isset($unit[$device]) ? $unit[$device] : (is_string($unit) ? $unit : ''));
                }
            }
        } elseif (is_object($value)) {
            foreach (self::$_devices as $device) {
                if (!empty($value->{$device})) {
                    $this->_css[$device][$property] = $value->{$device} . (is_array($unit) && isset($unit[$device]) ? $unit[$device] : (is_string($unit) ? $unit : ''));
                }
            }
        } else {
            $this->_css['global'][$property] = $value . $unit;
        }
        return $this;
    }

    public function add_border($value, $device = 'global', $on_file = false): void
    {
        self::add_border_style($this->_selector, $value, $device, $on_file);
    }

    public function add_style($css, $device = 'global'): void
    {
        if (empty($css)) {
            return;
        }
        $this->_styles[$device][] = $css;
    }

    public function add_child($selector): style
    {
        $selector = $this->_child_selector($selector);
        $this->_child[text::slugify($selector)] = new style($selector, '', $this->_onFile);
        return $this->_child[text::slugify($selector)];
    }

    protected function _child_selector($selector): string
    {
        $selector = explode(',', $selector);
        foreach ($selector as &$element) {
            if (strpos(trim($element), ':') === 0) {
                $element = $this->_selectorize($element);
            } else {
                $element = $this->_selectorize(' ' . trim($element));
            }
        }

        return implode(', ', $selector);
    }

    public function render(): void
    {
        $css = ['global' => '', 'larger_desktop' => '', 'large_desktop' => '', 'desktop' => '', 'tablet' => '', 'landscape_mobile' => '', 'mobile' => ''];
        foreach ($this->_css as $device => $styles) {
            foreach ($styles as $property => $value) {
                $css[$device] .= $property . ':' . $value . ';';
            }
        }

        foreach ($this->_styles as $device => $css_script) {
            if (!empty($css_script)) {
                $css[$device] .= implode(';', $css_script);
            }
        }

        if (!empty($css)) {
            $document = framework::get_document();
            foreach ($css as $device => $styles) {
                if (!empty($styles)) {
                    $document->add_style_declaration($this->_selector . '{' . $styles . '}', $device);
                }
            }
        }

        $this->_css = ['mobile' => [], 'landscape_mobile' => [], 'tablet' => [], 'desktop' => [], 'large_desktop' => [], 'larger_desktop' => [], 'global' => []];
        $this->_styles = ['mobile' => [], 'landscape_mobile' => [], 'tablet' => [], 'desktop' => [], 'large_desktop' => [], 'larger_desktop' => [], 'global' => []];

        if ($this->_hover !== null) {
            $this->_hover->render();
        }

        if ($this->_focus !== null) {
            $this->_focus->render();
        }

        if ($this->_active !== null) {
            $this->_active->render();
        }

        if ($this->_link !== null) {
            $this->_link->render();
        }

        foreach ($this->_child as $child) {
            $child->render();
        }
    }

    public static function get_css($content, $device = 'global', $breakpoints = ['mobile' => '575.98px', 'landscape_mobile' => '767.98px', 'tablet' => '991.98px', 'desktop' => '1199.98px', 'large_desktop' => '1399.98px', 'larger_desktop' => '1600px']): string
    {
        return match ($device) {
            'mobile' => '@media (max-width: '.$breakpoints['mobile'].') {' . $content . '}',
            'landscape_mobile' => '@media (max-width: '.$breakpoints['landscape_mobile'].') {' . $content . '}',
            'tablet' => '@media (max-width: '.$breakpoints['tablet'].') {' . $content . '}',
            'desktop' => '@media (max-width: '.$breakpoints['desktop'].') {' . $content . '}',
            'large_desktop' => '@media (max-width: '.$breakpoints['large_desktop'].') {' . $content . '}',
            'larger_desktop' => '@media (max-width: '.$breakpoints['larger_desktop'].') {' . $content . '}',
            default => $content,
        };
    }

    public static function add_border_style($selector, $border, $device = 'global', $on_file = false): void
    {
        $style      = new style($selector, '', $on_file);
        $style_dark = new style($selector, 'dark', $on_file);
        if (isset($border['border_width'])) {
            if (utilities::is_json_string($border['border_width'])) {
                self::set_spacing_style($style, $border['border_width'], 'border');
            } else {
                $style->add_css('border-width', $border['border_width']. 'px', $device);
            }
        }
        if (isset($border['border_style'])) {
            $style->add_css('border-style', $border['border_style'], $device);
        }
        if (isset($border['border_color'])) {
            if (isset($border['border_color']['light'])) {
                $style->add_css('border-color', $border['border_color']['light'], $device);
            }
            if (isset($border['border_color']['dark'])) {
                $style_dark->add_css('border-color', $border['border_color']['dark'], $device);
            }
        }
        $style->render();
        $style_dark->render();
    }

    public static function add_css_by_selector($selector, $property, $value, $device = 'global', $mode = '', $on_file = false): style
    {
        $style = new style($selector, $mode, $on_file);
        $style->add_css($property, $value, $device);
        $style->render();
        return $style;
    }

    public static function render_typography($selector, $object, $default_object = null, $on_file = false, $parent_class = ''): void
    {
        if (is_string($object) && utilities::is_json_string($object)) {
            $object = json_decode($object);
        }
        $typography = new registry();
        $typography->load_object($object);

        $style = new style($selector, '', $on_file, $parent_class);
        $style_dark = new style($selector, 'dark', $on_file, $parent_class);

        // font color, weight and transfrom
        $font_color = style::get_color($typography->get('font_color', ''));
        $style->add_css('color', $font_color['light']);
        $style_dark->add_css('color', $font_color['dark']);
        $style->add_css('font-weight', $typography->get('font_weight', ''));
        $style->add_css('text-transform', $typography->get('text_transform', ''));

        // font size
        $font_size = $typography->get('font_size', '');
        $font_size_unit = $typography->get('font_size_unit', '');

        if (!empty($font_size)) {
            if (is_object($font_size)) {
                foreach (style::$_devices as $device) {
                    if (isset($font_size->{$device}) && $font_size->{$device}) {
                        $unit = $font_size_unit->{$device} ?? 'em';
                        $style->add_css('font-size', $font_size->{$device} . $unit, $device);
                    }
                }
            } else {
                $style->add_css('font-size', $font_size . $font_size_unit);
            }
        }

        // font styles
        $font_styles = $typography->get('font_style', []);
        if (is_array($font_styles) && count($font_styles)) {
            foreach ($font_styles as $font_style) {
                switch ($font_style) {
                    case 'bold':
                        $style->add_css('font-weight', 'bold');
                        break;
                    case 'italic':
                        $style->add_css('font-style', 'italic');
                        break;
                    case 'underline':
                        $style->add_css('text-decoration', 'underline');
                        break;
                }
            }
        }

        // letter spacing
        $letter_spacing = $typography->get('letter_spacing', '');
        $letter_spacing_unit = $typography->get('letter_spacing_unit', '');

        if (!empty($letter_spacing)) {
            if (is_object($letter_spacing)) {
                foreach (style::$_devices as $device) {
                    if (!empty($letter_spacing->{$device})) {
                        $letter_spacing_unit_value = $letter_spacing_unit->{$device} ?? 'em';
                        $style->add_css('letter-spacing', $letter_spacing->{$device} . $letter_spacing_unit_value, $device);
                    }
                }
            } else {
                $style->add_css('letter-spacing', $letter_spacing . $letter_spacing_unit);
            }
        }

        // line height
        $line_height = $typography->get('line_height', '');
        $line_height_unit = $typography->get('line_height_unit', '');

        if (!empty($line_height)) {
            if (is_object($line_height)) {
                $default_value = '';
                foreach (style::$_devices as $device) {
                    if (isset($line_height->{$device}) && $line_height->{$device}) {
                        $line_height_unit_value = $line_height_unit->{$device} ?? 'em';
                        $style->add_css('line-height', $line_height->{$device} . $line_height_unit_value, $device);
                    }
                }
            } else {
                $style->add_css('line-height', $line_height . $line_height_unit);
            }
        }

        // font family
        $font_face = $typography->get('font_face', '');
        $alt_font_face = $typography->get('alt_font_face', '');

        if ($default_object !== null) {
            $default_typography = new registry();
            $default_typography->load_object($default_object);
            $font_face = ($font_face == '__default' ? $default_typography->get('font_face', '') : $font_face);
            $alt_font_face = ($alt_font_face == '__default' ? $default_typography->get('alt_font_face', '') : $alt_font_face);
        }
        $style->add_css('font-family', self::get_font_family_value($font_face, $alt_font_face));
        $style->render();
        $style_dark->render();
    }

    public static function add_background_css ($obj, $obj_params, $prefix = '', $on_file = false): void
    {
        $background = $obj_params->get($prefix . 'background_setting', '');
        if (!empty($background)) {
            $style = new style($obj, '', $on_file);
            $style_dark = new style($obj, 'dark', $on_file);
            switch ($background) {
                case 'color': // if color background
                    $background_color   =   style::get_color($obj_params->get($prefix . 'background_color', ''));
                    $style->add_css('background-color', $background_color['light']);
                    $style_dark->add_css('background-color', $background_color['dark']);
                    break;
                case 'image': // if image background
                    $background_color   =   style::get_color($obj_params->get($prefix . 'img_background_color', ''));
                    $style->add_css('background-color', $background_color['light']);
                    $style_dark->add_css('background-color', $background_color['dark']);
                    $image = $obj_params->get($prefix . 'background_image', '');
                    if (!empty($image)) {
                        $style->add_css('background-image', 'url(' . $image . ')');
                        $style->add_css('background-repeat', $obj_params->get($prefix . 'background_repeat', ''));
                        $style->add_css('background-size', $obj_params->get($prefix . 'background_size', ''));
                        $style->add_css('background-attachment', $obj_params->get($prefix . 'background_attachment', ''));
                        $style->add_css('background-position', $obj_params->get($prefix . 'background_position', ''));
                        self::add_overlay_color($obj, $obj_params, $prefix);
                    }
                    break;
                case 'video': // if video background
                    $video = $obj_params->get($prefix . 'background_video', '');
                    if (!empty($video)) {
                        self::add_overlay_color($obj, $obj_params, $prefix);
                    }
                    break;
                case 'gradient': // if gradient background
                    $style->add_css('background-image', style::get_gradient_value($obj_params->get($prefix . 'background_gradient', '')));
                    break;
            }
            $style->render();
            $style_dark->render();
        }
    }

    public static function add_overlay_color($obj, $obj_params, $prefix = '', $on_file = false): void
    {
        $overlay_type   =   $obj_params->get($prefix . 'background_image_overlay', '');
        if (!empty($overlay_type)) {
            $background = $obj_params->get($prefix . 'background_setting', '');
            $overlay_style_cls      =   '.moon-element-overlay';
            if ($background == 'video') {
                $overlay_style_cls  =   ' > ' . $overlay_style_cls;
            }

            switch ($overlay_type) {
                case 'color':
                    $background_image_overlay_color     =   style::get_color($obj_params->get($prefix . 'background_image_overlay_color', ''));
                    if (!empty($background_image_overlay_color)) {
                        $overlay_style   =   new style($obj . $overlay_style_cls . ':before', '', $on_file);
                        $overlay_style->add_css('background-color', $background_image_overlay_color['light']);
                        $overlay_style->render();

                        $overlay_style   =   new style($obj . $overlay_style_cls . ':before', 'dark', $on_file);
                        $overlay_style->add_css('background-color', $background_image_overlay_color['dark']);
                        $overlay_style->render();
                    }
                    break;
                case 'gradient':
                    $background_image_overlay_gradient  =   $obj_params->get($prefix . 'background_image_overlay_gradient', '');
                    if (!empty($background_image_overlay_gradient)) {
                        $overlay_style   =   new style($obj . $overlay_style_cls . ':before');
                        $overlay_style->add_css('background-image', style::get_gradient_value($background_image_overlay_gradient));
                        $overlay_style->render();
                    }
                    break;
                case 'pattern':
                    $background_image_overlay_pattern   =   $obj_params->get($prefix . 'background_image_overlay_pattern', '');
                    $background_image_overlay_color     =   style::get_color($obj_params->get($prefix . 'background_image_overlay_color', ''));
                    if (!empty($background_image_overlay_pattern)) {
                        $overlay_style   =   new style($obj . $overlay_style_cls . ':before', '', $on_file);
                        if ($background_image_overlay_color) {
                            $overlay_style_dark   =   new style($obj . $overlay_style_cls . ':before', 'dark', $on_file);
                            $overlay_style->add_css('background-color', $background_image_overlay_color['light']);
                            $overlay_style_dark->add_css('background-color', $background_image_overlay_color['dark']);
                            $overlay_style_dark->render();
                        }
                        $overlay_style->add_css('background-image', 'url(' . $background_image_overlay_pattern . ')');
                        $overlay_style->render();
                    }
                    break;
            }
        }
    }

    public static function get_font_family_value($value, $alt_font = ''): string
    {
        $value = ($value == '__default' ? '' : $value);
        if (empty($value) && empty($alt_font)) {
            return '';
        }

        $return = [];
        $font = font::get_font_family($value);
        if (!empty($font)) {
            $return[] = $font;
        }
        $alt_font = font::get_font_family($alt_font);
        if (!empty($alt_font)) {
            $return[] = $alt_font;
        }
        return implode(', ', $return);
    }

    public static function get_color($color): array
    {
        $result = json_decode($color);
        if (json_last_error() === JSON_ERROR_NONE) {
            return ['light'=>$result->light, 'dark'=>$result->dark];
        } else {
            return ['light'=>$color, 'dark'=>$color];
        }
    }

    public static function get_gradient_value($value): string
    {
        if (empty($value)) {
            return '';
        }
        $gradient = \json_decode($value, true);
        if (isset($gradient['type']) && $gradient['start'] && $gradient['stop']) {
            if ($gradient['type'] == 'linear') {
                return $gradient['type'] . '-gradient('. (isset($gradient['angle']) ? $gradient['angle'].'deg,' : '') . $gradient['start'] . (isset($gradient['start_pos']) ? ' ' . $gradient['start_pos'].'%' : '') . ',' . $gradient['stop'] . (isset($gradient['stop_pos']) ?  ' ' . $gradient['stop_pos'].'%' : '') . ')';
            } else {
                return $gradient['type'] . '-gradient('. (isset($gradient['position']) && $gradient['position'] ? $gradient['position'].',' : '') . $gradient['start'] . (isset($gradient['start_pos']) ? ' ' . $gradient['start_pos'].'%' : '') . ',' . $gradient['stop'] . (isset($gradient['stop_pos']) ? ' ' . $gradient['stop_pos'].'%' : '') . ')';
            }
        } else {
            return '';
        }
    }

    public static function set_spacing_style($style, $value, $type = 'padding'): void
    {
        if (!empty($value)) {
            $object = \json_decode($value, false);

            foreach (self::$_devices as $device) {
                if (!empty($object->{$device})) {
                    $props = $object->{$device};
                } else {
                    $props = new \stdClass();
                    $props->top = '';
                    $props->right = '';
                    $props->bottom = '';
                    $props->left = '';
                    $props->lock = false;
                    $props->unit = 'px';
                }
                $style->add_style(style::spacing_value($props, $type), $device);
            }
        }
    }

    public static function spacing_value($value = null, $property = "padding", $default = []): string
    {
        $return = [];
        $values = [];
        if (!empty($value) && isset($value->unit)) {
            $unit = $value->unit == 'Custom' ? '' : $value->unit;
            if ( $value->lock && (($value->unit == 'Custom' && isset($value->top)) || is_numeric($value->top)) ) {
                foreach (['top', 'right', 'bottom', 'left'] as $position) {
                    $return[$position] = self::get_property_subset($property, $position) . ":{$value->top}{$unit}";
                    $values[$position] = "{$value->top}{$unit}";
                }
            } else {
                foreach (['top', 'right', 'bottom', 'left'] as $position) {
                    $pvalue = $value->{$position};
                    if (($value->unit == 'Custom' && isset($pvalue) && $pvalue !== '') || is_numeric($pvalue)) {
                        $return[$position] = self::get_property_subset($property, $position) . ":{$pvalue}{$unit}";
                        $values[$position] = "{$pvalue}{$unit}";
                    }
                }
            }
        }

        if (!isset($default['unit'])) {
            $default['unit'] = 'px';
        }
        if ($default['unit'] == 'Custom') {
            $default['unit'] = '';
        }

        foreach (array_keys($default) as $position) {
            if ($position == "unit") {
                continue;
            }
            if (!isset($return[$position]) && $default[$position] !== '') {
                $return[$position] = self::get_property_subset($property, $position) . ":{$default[$position]}{$default['unit']}";
                $values[$position] = "{$default[$position]}{$default['unit']}";
            }
        }

        if (count(array_keys($values)) === 4) {
            $return = [];
            $return[] = self::get_property_set($property) . ':' . implode(' ', $values);
        } elseif (count(array_keys($values)) && $property == 'border') {
            array_unshift($return, 'border-width:0');
        }

        return implode(";", $return);
    }

    public static function get_sub_form_params($params)
    {
        $return_array = array();
        if (is_array($params) && count($params)) {
            foreach ($params as $param) {
                $return_array[$param->name] = $param->value;
            }
        }
        return $return_array;
    }

    public static function get_property_subset($property, $position)
    {
        switch ($property) {
            case "radius":
                switch ($position) {
                    case "top":
                        return 'border-top-left-radius';
                        break;
                    case "left":
                        return 'border-bottom-left-radius';
                        break;
                    case "right":
                        return 'border-top-right-radius';
                        break;
                    case "bottom":
                        return 'border-bottom-right-radius';
                        break;
                }
                break;
            case "border":
                return $property . '-' . $position . '-width';
                break;
            default:
                return $property . '-' . $position;
                break;
        }
    }

    public static function get_property_set($property)
    {
        switch ($property) {
            case "radius":
                return "border-radius";
                break;
            case "border":
                return "border-width";
                break;
            default:
                return $property;
                break;
        }
    }
}
