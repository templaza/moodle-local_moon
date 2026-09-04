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

namespace local_moon\library\element;
use context_system;
use local_moon\library\framework;
use local_moon\library\helper\registry;
use local_moon\library\helper\sub_form;
use local_moon\library\helper\text;
use local_moon\library\helper\style;
use local_moon\library\helper\utilities;

defined('MOODLE_INTERNAL') || die;

class base_element
{
    protected $_data, $_tag = 'div', $_classes = [], $_attributes = [];
    public $id, $unqid, $params, $type, $style, $style_dark, $content = '';
    public int $state = 1;
    public bool $is_assigned = true;
    public array $devices = [];
    public array $options = [];
    public string $role = '';
    public bool $is_root = false;
    public bool $has_maxwidth = false;
    public mixed $context = '';
    public bool $transform_loaded = false;
    public function __construct($data, $devices, $options = array(), $role = '')
    {
        $this->_data    = $data;
        $this->devices  = $devices;
        $this->options  = $options;
        $this->context  = context_system::instance();
        $this->role     = $role;
        $this->id       = $data['id'];
        $this->unqid    = $data['id'];
        $this->type     = isset($data['type']) ? $data['type'] : 'element';
        $this->state    = isset($data['state']) ? intval($data['state']) : 1;
        $this->params   = new registry();
        if (isset($data['params']) && !empty($data['params'])) {
            $params = [];
            foreach ($data['params'] as $param) {
                $params[$param['name']] = $param['value'];
            }
            $this->params->load_array($params);
        }

        $this->add_class('moon-' . text::slugify($this->type));
        $this->_id();
        $this->_tag         =   $this->params->get('moon_element_tag', 'div');
        $this->is_root       =   $this->role === 'root';
        $this->style        =   new style('#' . $this->get_attribute('id'), '', $this->is_root);
        $this->style_dark   =   new style('#' . $this->get_attribute('id'), 'dark', $this->is_root);

    }

    protected function wrap(): string
    {
        if (empty($this->content) || !$this->state || !$this->is_assigned) {
            return '';
        }
//        $assignment_type =   $this->params->get('assignment_type', 1);
//        if ($assignment_type == 0) {
//            return '';
//        }
//        $app = Factory::getApplication();
//        $jinput = $app->input;
//        $menuId = $jinput->get('Itemid', 0, 'INT');
//
//        $assignment =   $this->params->get('assignment', "");
//        if ($assignment_type == 2 && $assignment) {
//            $assignment =   \json_decode($assignment, true);
//            if ((isset($assignment[$menuId]) && !$assignment[$menuId]) || !isset($assignment[$menuId])) {
//                return '';
//            }
//        }
//        $moon_element_visibility =   $this->params->get('moon_element_visibility', "allPage");
//        if ($moon_element_visibility == "currentPage") {
//            $menu = $app->getMenu();
//            $item = $menu->getItem($menuId);
//            if (empty($item)) {
//                return '';
//            }
//            if ((isset($item->query['option']) && $item->query['option'] != $jinput->get('option', '')) || (isset($item->query['view']) && $item->query['view'] != $jinput->get('view', '')) || (isset($item->query['layout']) && $item->query['layout'] != $jinput->get('layout', ''))) {
//                return '';
//            }
//        }
        $max_width                  =   $this->params->get('max_width','');
        $max_width_breakpoint       =   $this->params->get('max_width_breakpoint','');
        if ($max_width) {
            $class_maxwidth         =   'as-width' . ($max_width_breakpoint ? '-' . $max_width_breakpoint : '') . '-' . $max_width;
        } else {
            $class_maxwidth         =   '';
        }
//        Helper::triggerEvent('onMoonPrepareContent', [&$this, 'layout.element.content']);
        $this->_transform();
        $this->_animation();
        $this->_background();
        $content    =   '';
        if ($class_maxwidth) {
            if ($this->type === 'row') {
                $block_align                =   $this->params->get('block_align','');
                $block_align_breakpoint     =   $this->params->get('block_align_breakpoint','');
                $block_align_fallback       =   $this->params->get('block_align_fallback','');

                $block_align_class          =   '';
                if ($max_width && $block_align) {
                    $block_align_class      =   'w-100 d-flex justify-content' . ($block_align_breakpoint ? '-' . $block_align_breakpoint : '') . '-' . $block_align . ($block_align_fallback ? ' justify-content-' . $block_align_fallback : '');
                }
                $content            .=  '<div class="'.$block_align_class.'"><div class="'.$class_maxwidth.'">' . "<{$this->_tag}{$this->_attrbs()}>" . $this->content . "</{$this->_tag}>" . '</div></div>';
            } else {
                $content            .=  "<{$this->_tag}{$this->_attrbs()}>".'<div class="'.$class_maxwidth.'">'. $this->content .'</div>'."</{$this->_tag}>";
                $this->has_maxwidth = true;
            }
        } else {
            $content                .=  "<{$this->_tag}{$this->_attrbs()}>" . $this->content . "</{$this->_tag}>";
        }
        $this->_styles();
        return $content;
    }

    protected function _check_assignments(): bool
    {
//        $assignment_type =   $this->params->get('assignment_type', 1);
//        if ($assignment_type == 0) {
//            return false;
//        }
//        $app = Factory::getApplication();
//        $jinput = $app->input;
//        $menuId = $jinput->get('Itemid', 0, 'INT');
//
//        $assignment =   $this->params->get('assignment', "");
//        if ($assignment_type == 2 && $assignment) {
//            $assignment =   \json_decode($assignment, true);
//            if ((isset($assignment[$menuId]) && !$assignment[$menuId]) || !isset($assignment[$menuId])) {
//                return false;
//            }
//        }

        return true;
    }

    protected function _attrbs(): string
    {
        $this->_getclasses();
        $attributes = [];
        if (!empty($this->_classes)) {
            $classes = array_unique($this->_classes);
            $attributes[] = 'class="' . implode(' ', $classes) . '"';
        }
        if (!empty($this->_attributes)) {
            foreach ($this->_attributes as $prop => $value) {
                $attributes[] = $prop . '="' . $value . '"';
            }
        }
        return !empty($attributes) ? ' ' . implode(' ', $attributes) : '';
    }

    protected function _id(): void
    {
        $customid = $this->params->get('customid', '');
        if (!empty($customid)) {
            $this->id = $customid;
        } else {
            $prefix = !empty($this->params->get('title')) ? $this->params->get('title') : 'moon-' . $this->type;
            $this->id = text::shortify($prefix) . '-' . $this->id;
        }
        if (!empty($this->id)) {
            $this->add_attribute('id', $this->id);
        }
    }

    public function add_class($class): void
    {
        if (!empty($class)) {
            $this->_classes[] = $class;
        }
    }

    protected function add_attribute($prop, $value): void
    {
        $this->_attributes[$prop] = $value;
    }

    protected function get_attribute($prop)
    {
        if (isset($this->_attributes[$prop])) {
            return $this->_attributes[$prop];
        }
        return null;
    }

    protected function _getclasses(): void
    {
        $max_width                  =   $this->params->get('max_width','');
        $block_align                =   $this->params->get('block_align','');
        $block_align_breakpoint     =   $this->params->get('block_align_breakpoint','');
        $block_align_fallback       =   $this->params->get('block_align_fallback','');

        if ($max_width && $block_align && $this->type !== 'row') {
            if ($this->type !== 'column') {
                $this->add_class('w-100');
            }
            $this->add_class('d-flex justify-content' . ($block_align_breakpoint ? '-' . $block_align_breakpoint : '') . '-' . $block_align . ($block_align_fallback ? ' justify-content-' . $block_align_fallback : ''));
        }

        $text_alignment             =   $this->params->get('text_alignment','');
        $text_alignment_breakpoint  =   $this->params->get('text_alignment_breakpoint','');
        $text_alignment_fallback    =   $this->params->get('text_alignment_fallback','');

        if ($text_alignment) {
            $this->add_class('text' . ($text_alignment_breakpoint ? '-' . $text_alignment_breakpoint : '') . '-' . $text_alignment . ($text_alignment_fallback ? ' text-' . $text_alignment_fallback : ''));
        }

        $this->add_class($this->params->get('customclass', ''));
        $this->add_class($this->params->get('hideonxs', 0) ? 'hideonxs' : '');
        $this->add_class($this->params->get('hideonsm', 0) ? 'hideonsm' : '');
        $this->add_class($this->params->get('hideonmd', 0) ? 'hideonmd' : '');
        $this->add_class($this->params->get('hideonlg', 0) ? 'hideonlg' : '');
        $this->add_class($this->params->get('hideonxl', 0) ? 'hideonxl' : '');
        $this->add_class($this->params->get('hideonxxl', 0) ? 'hideonxxl' : '');
    }

    protected function _sticky(): void
    {
        $sticky_effect          =   $this->params->get('moon_element_sticky_effect','');
        if (!empty($sticky_effect)) {
            $sticky_effect_breakpoint   =   $this->params->get('moon_element_sticky_effect_breakpoint','');
            $sticky_effect_offset       =   $this->params->get('moon_element_sticky_effect_offset', '');
            $this->add_class('sticky' . ($sticky_effect_breakpoint ? '-' . $sticky_effect_breakpoint : '') . '-' . $sticky_effect);
            if (!empty($sticky_effect_offset)) {
                $sticky_effect_offset   =   json_decode($sticky_effect_offset, true);
                $this->style->add_responsive_css($sticky_effect, $sticky_effect_offset, $sticky_effect_offset['postfix']);
            }
        }
    }

    protected function _styles(): void
    {
        $this->_border();
        $this->_margin_padding();
        $this->_sticky();
        $this->_typography();
        $this->_custom_css();
        $this->style->render();
        $this->style_dark->render();
    }

    protected function _border(): void
    {
        $border = json_decode($this->params->get('border_style', ''), true);
        if (!empty($border)) {
            if ($this->has_maxwidth) {
                $this->style->child('>[class*=as-width]')->add_border($border, 'global', $this->is_root);
            } else {
                $this->style->add_border($border, 'global', $this->is_root);
            }

        }
        $border_radius = $this->params->get('border_radius', '');
        if ($this->has_maxwidth) {
            $this->style->child('>[class*=as-width]')->add_responsive_css('border-radius', $border_radius, 'px');
        } else {
            $this->style->add_responsive_css('border-radius', $border_radius, 'px');
        }
    }

    protected function _background(): void
    {
        $background = $this->params->get('background_setting', '');
        if (empty($background)) {
            return;
        }
        $enable_background_parallax = $this->params->get('enable_background_parallax', 0);
        switch ($background) {
            case 'color': // if color background
                $background_color   =   style::get_color($this->params->get('background_color', ''));
                $this->style->add_css('background-color', $background_color['light']);
                $this->style_dark->add_css('background-color', $background_color['dark']);
                break;
            case 'image': // if image background
                $background_color   =   style::get_color($this->params->get('img_background_color', ''));
                $this->style->add_css('background-color', $background_color['light']);
                $this->style_dark->add_css('background-color', $background_color['dark']);
                $image = $this->params->get('background_image', '');
                if (!empty($image)) {
                    $this->style->add_css('background-image', 'url(' . $image . ')');
                    $this->style->add_css('background-repeat', $this->params->get('background_repeat', ''));
                    $this->style->add_css('background-size', $this->params->get('background_size', ''));
                    $this->style->add_css('background-attachment', $this->params->get('background_attachment', ''));
                    $this->style->add_css('background-position', $this->params->get('background_position', ''));
                    if ($enable_background_parallax) {
                        $this->add_parallax('image');
                    }
                    $this->add_overlay_color();
                }
                break;
            case 'video': // if video background
                $video = $this->params->get('background_video', '');
                $poster = $this->params->get('background_image', '');
                if (!empty($video)) {
                    $this->add_attribute('data-as-video-bg', $video);
                    $this->add_attribute('data-as-video-poster', $poster);
                    if ($enable_background_parallax) {
                        $this->add_parallax('video');
                    }
                    framework::get_document()->load_video_bg();
                    $this->add_overlay_color();
                }
                break;
            case 'gradient': // if gradient background
                $this->style->add_css('background-image', style::get_gradient_value($this->params->get('background_gradient', '')));
                break;
        }
    }

    protected function add_parallax($type): void
    {
        $parallax_speed = $this->params->get('background_parallax_speed', 0.3);
        $parallax_scrub = $this->params->get('background_parallax_scrub', 2);
        $parallax = [];
        $parallax['type'] = $type;
        $parallax['speed'] = $parallax_speed;
        $parallax['scrub'] = $parallax_scrub;
        $this->add_attribute('data-parallax', htmlspecialchars(json_encode($parallax), ENT_QUOTES, 'UTF-8'));
        $document = framework::get_document();
        $document->load_gsap('ScrollTrigger');
        if ($type == 'image') {
            $document->load_parallax();
        }
    }

    protected function add_overlay_color(): void {
        $overlay_type   =   $this->params->get('background_image_overlay', '');
        if (!empty($overlay_type)) {
            $background = $this->params->get('background_setting', '');
            $overlay_style_cls      =   '.moon-element-overlay';
            if ($background == 'video') {
                $this->add_class('position-relative');
                $overlay_style_cls  =   ' > ' . $overlay_style_cls;
            } else {
                $this->add_class('position-relative moon-element-overlay');
            }

            switch ($overlay_type) {
                case 'color':
                    $background_image_overlay_color     =   style::get_color($this->params->get('background_image_overlay_color', ''));
                    if (!empty($background_image_overlay_color)) {
                        $overlay_style   =   new style('#' . $this->get_attribute('id') . $overlay_style_cls . ':before', '', $this->is_root);
                        $overlay_style->add_css('background-color', $background_image_overlay_color['light']);
                        $overlay_style->render();

                        $overlay_style   =   new style('#' . $this->get_attribute('id') . $overlay_style_cls . ':before', 'dark', $this->is_root);
                        $overlay_style->add_css('background-color', $background_image_overlay_color['dark']);
                        $overlay_style->render();
                    }
                    break;
                case 'gradient':
                    $background_image_overlay_gradient  =   $this->params->get('background_image_overlay_gradient', '');
                    if (!empty($background_image_overlay_gradient)) {
                        $overlay_style   =   new style('#' . $this->get_attribute('id') . $overlay_style_cls . ':before', '', $this->is_root);
                        $overlay_style->add_css('background-image', style::get_gradient_value($background_image_overlay_gradient));
                        $overlay_style->render();
                    }
                    break;
                case 'pattern':
                    $background_image_overlay_pattern   =   $this->params->get('background_image_overlay_pattern', '');
                    $background_image_overlay_color     =   style::get_color($this->params->get('background_image_overlay_color', ''));
                    if (!empty($background_image_overlay_pattern)) {
                        $overlay_style   =   new style('#' . $this->get_attribute('id') . $overlay_style_cls . ':before', '', $this->is_root);
                        if ($background_image_overlay_color) {
                            $overlay_style_dark   =   new style('#' . $this->get_attribute('id') . $overlay_style_cls . ':before', 'dark', $this->is_root);
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

    protected function _margin_padding(): void
    {
        $margin = $this->params->get('margin', '');
        $padding = $this->params->get('padding', '');
        style::set_spacing_style($this->style, $margin, 'margin');
        style::set_spacing_style($this->style, $padding, 'padding');
    }

    protected function _typography(): void
    {
        if (!$this->params->get('custom_colors', 0)) {
            return;
        }
        $text_color =   style::get_color($this->params->get('text_color', ''));
        $this->style->add_css('color', $text_color['light']);
        $this->style_dark->add_css('color', $text_color['dark']);

        $link_color         =   style::get_color($this->params->get('link_color', ''));
        $link_hover_color   =   style::get_color($this->params->get('link_hover_color', ''));
        $link = $this->style->add_child('a');
        $link_hover = $this->style->add_child('a:hover');
        $link->add_css('color', $link_color['light']);
        $link_hover->add_css('color', $link_hover_color['light']);

        $link_dark      = $this->style_dark->add_child('a');
        $link_hover_dark = $this->style_dark->add_child('a:hover');
        $link_dark->add_css('color', $link_color['dark']);
        $link_hover_dark->add_css('color', $link_hover_color['dark']);
    }

    protected function _animation(): void
    {
        $animation = $this->params->get('animation', '');
        if (empty($animation) || $this->transform_loaded) {
            return;
        }
        $document = framework::get_document();

        $this->add_attribute('data-animation', $animation);

        $delay = $this->params->get('animation_delay', '');
        if (!empty($delay)) {
            $this->add_attribute('data-animation-delay', $delay);
        }

        $duration = $this->params->get('animation_duration', '');
        if (!empty($duration)) {
            $this->add_attribute('data-animation-duration', $duration);
        }

        $this->add_attribute('style', 'visibility: hidden;');
        $animation_element = $this->params->get('animation_element', '');
        if (!empty($animation_element)) {
            $this->add_attribute('data-animation-element', $animation_element);
        }
        $this->add_attribute('data-animation-loop', $this->params->get('animation_loop', 0));
        $this->add_attribute('data-animation-stagger', $this->params->get('animation_stagger', 200));
        $document->load_animation();
    }

    protected function _transform(): void
    {
        $transform_scenes = new sub_form($this->params->get('transform_scenes',''));
        $scenes = [];
        $scroll_settings = [];
        $timeline_settings = [];
        if (!empty($transform_scenes->get_data())) {
            foreach ($transform_scenes->get_data() as $scene) {
                $animations = $scene->params->to_array();
                $from = [];
                $to = [];
                $config = [];
                foreach ($animations as $animation => $value) {
                    if (!empty($value)) {
                        if (utilities::is_json_string($value)) {
                            $tmp = json_decode($value, true);
                            $animation_name = match ($animation) {
                                'translate_x' => 'x',
                                'translate_y' => 'y',

                                'rotate', 'rotate_z' => 'rotation',
                                'rotate_x' => 'rotateX',
                                'rotate_y' => 'rotateY',

                                'scale' => 'scale',
                                'scale_x' => 'scaleX',
                                'scale_y' => 'scaleY',

                                'skew_x' => 'skewX',
                                'skew_y' => 'skewY',

                                'opacity' => 'opacity',

                                default => $animation
                            };
                            if (isset($tmp['from']) && $tmp['from'] !== '') {
                                $from[$animation_name] = $tmp['from'];
                            }
                            if (isset($tmp['to']) && $tmp['to'] !== '') {
                                $to[$animation_name] = $tmp['to'];
                            }
                        } else {
                            $config[$animation] = $value;
                        }
                    }
                }
                if (!empty($from) && !empty($to)) {
                    $to = array_merge($to, $config);
                    $scenes[] = [
                        'from' => $from,
                        'to' => $to
                    ];
                } elseif (!empty($from)) {
                    $from = array_merge($from, $config);
                    $scenes[] = [
                        'from' => $from
                    ];
                } elseif (!empty($to)) {
                    $to = array_merge($to, $config);
                    $scenes[] = [
                        'to' => $to
                    ];
                }
            }
            $start = $this->params->get('transform_start', '');
            $end = $this->params->get('transform_end', '');
            $transform_scrub = $this->params->get('transform_scrub', 3);
            $transform_repeat = $this->params->get('transform_repeat', 0);
            $transform_pin = $this->params->get('transform_pin', 0);
            $transform_markers = $this->params->get('transform_markers', 0);
            $transform_toggle_actions = $this->params->get('transform_toggle_actions', '');
            $transform_element = $this->params->get('transform_element', '');
            if (!empty($start)) {
                $scroll_settings['start'] = $start;
            }
            if (!empty($end)) {
                $scroll_settings['end'] = $end;
            }
            if (!empty($transform_scrub)) {
                $scroll_settings['scrub'] = $transform_scrub;
            }
            if (!empty($transform_repeat)) {
                $timeline_settings['repeat'] = $transform_repeat;
            }
            if (!empty($transform_pin)) {
                $scroll_settings['pin'] = true;
                $transform_pin_spacing = $this->params->get('transform_pin_spacing', 1);
                if (empty($transform_pin_spacing)) {
                    $scroll_settings['pinSpacing'] = false;
                }
            }
            if (!empty($transform_markers)) {
                $scroll_settings['markers'] = true;
            }
            if (!empty($transform_toggle_actions)) {
                $scroll_settings['toggleActions'] = $transform_toggle_actions;
            }
            if (!empty($scenes)) {
                $this->add_attribute('data-transform-scenes', htmlspecialchars(json_encode($scenes), ENT_QUOTES, 'UTF-8'));
                if (!empty($scroll_settings)) {
                    $this->add_attribute('data-transform-scroll', htmlspecialchars(json_encode($scroll_settings), ENT_QUOTES, 'UTF-8'));
                }
                if (!empty($timeline_settings)) {
                    $this->add_attribute('data-transform-timeline', htmlspecialchars(json_encode($timeline_settings), ENT_QUOTES, 'UTF-8'));
                }
                if (!empty($transform_element)) {
                    $this->add_attribute('data-transform-trigger', htmlspecialchars($transform_element, ENT_QUOTES, 'UTF-8'));
                }
                $document = framework::get_document();
                $document->load_gsap('ScrollTrigger');
                $document->load_transform();
                $this->transform_loaded = true;
            }
        }
    }

    public function _custom_css(): void
    {
        $custom_css = $this->params->get('custom_css', '');
        if (!empty($custom_css)) {
            $scss = new \ScssPhp\ScssPhp\Compiler();
            $css = $scss->compileString('#' . $this->id .'{'.$custom_css.'}')->getCss();
            if (!empty($css)) {
                framework::get_document()->add_style_declaration($css);
            }
        }
    }
}
