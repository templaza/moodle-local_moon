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

defined('MOODLE_INTERNAL') || die;

class column extends base_element
{
    public $section, $row, $size = 12, $component = false;
    public function __construct($data, $section, $row, $role = '')
    {
        $this->section = $section;
        $this->row = $row;
        if (empty($this->options)) {
            $this->options = $section->options;
        }
        if (is_int($data['size']) || is_string($data['size'])) {
            $tmp = intval($data['size']);
            $this->size = [
                'xxl' => $tmp,
                'xl' => $tmp,
                'lg' => $tmp,
                'md' => 12,
                'sm' => 12,
                'xs' => 12,
            ];
        } else {
            $this->size = $data['size'];
        }
        parent::__construct($data, $section->devices, $section->options, $role);
    }

    public function render()
    {
        foreach ($this->_data['elements'] as $element) {
            $element = new element($element, $this->section, $this->row, $this, $this->role);
            $element_content = $element->render();
            if (!empty($element->content)) {
                $this->content .= $element_content;
            }
        }
        return $this->wrap();
    }

    protected function _getclasses(): void
    {
        foreach ($this->devices as $device) {
            $size = $device['code'];
            if ($size != 'xs') {
                if (isset($this->size[$size]) && $this->size[$size]) {
                    $this->add_class('col-' . $size . '-' . $this->size[$size]);
                }
            } else {
                if (isset($this->size[$size]) && $this->size[$size]) {
                    $this->add_class('col-' . $this->size[$size]);
                }
            }
            if ($this->params->get('hideon'.$size, 0)) {
                $this->add_class('hideon' . $size);
            }
        }

        //Column Order
        $column_order_xl     =   intval($this->params->get('column_order_xl', 0));
        $column_order_lg     =   intval($this->params->get('column_order_lg', 0));
        $column_order_md     =   intval($this->params->get('column_order_md', 0));
        $column_order_sm     =   intval($this->params->get('column_order_sm', 0));
        $column_order_xs     =   intval($this->params->get('column_order_xs', 0));
        if ($column_order_xl || $column_order_lg || $column_order_md || $column_order_sm || $column_order_xs) {
            $this->add_class('order-xl-'.$column_order_xl);
            $this->add_class('order-lg-'.$column_order_lg);
            $this->add_class('order-md-'.$column_order_md);
            $this->add_class('order-sm-'.$column_order_sm);
            $this->add_class('order-'.$column_order_xs);
        }
        parent::_getclasses();
    }
}
