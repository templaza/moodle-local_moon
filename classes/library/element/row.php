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

use local_moon\library\framework;

defined('MOODLE_INTERNAL') || die;

class row extends base_element
{
    public $section;
    public function __construct($data, $section, $role = '')
    {
        $this->section = $section;
        if (empty($this->options)) {
            $this->options = $section->options;
        }
        $data['fill'] = $data['fill'] ?? true;
        parent::__construct($data, $section->devices, $section->options, $role);
    }

    public function render()
    {
        $columns = $this->_data['cols'];
        $buffer_size = [
            'xxl' => 0,
            'xl' => 0,
            'lg' => 0,
            'md' => 0,
            'sm' => 0,
            'xs' => 0,
        ];
        $component_index = 0;
        $prev_col_index = null;

        foreach ($this->_data['cols'] as $col_index => $col) {
            $column = new column($col, $this->section, $this, $this->role);
            $columns[$col_index] = $column;
            $column->render();
            if ($column->component) {
                $component_index = $col_index;
            }
        }

        if (isset($this->_data['fill']) && $this->_data['fill']) {
            foreach ($columns as $col_index => $column) {
                if (empty($column->content)) {
                    foreach ($column->size as $key => $size) {
                        $buffer_size[$key] += $column->size[$key];
                    }
                    unset($columns[$col_index]);
                } else {
                    if ($this->section->hasComponent) {
                        foreach ($columns[$component_index]->size as $key => $size) {
                            $columns[$component_index]->size[$key] += $buffer_size[$key];
                            if ($columns[$component_index]->size[$key] > 12) $columns[$component_index]->size[$key] = 12;
                        }
                        $buffer_size = [
                            'xxl' => 0,
                            'xl' => 0,
                            'lg' => 0,
                            'md' => 0,
                            'sm' => 0,
                            'xs' => 0,
                        ];
                    } else {
                        if (isset($columns[$prev_col_index])) {
                            foreach ($columns[$prev_col_index]->size as $key => $size) {
                                $columns[$prev_col_index]->size[$key] += $buffer_size[$key];
                                if ($columns[$prev_col_index]->size[$key] > 12) $columns[$prev_col_index]->size[$key] = 12;
                            }
                        } else {
                            foreach ($columns[$col_index]->size as $key => $size) {
                                $columns[$col_index]->size[$key] += $buffer_size[$key];
                                if ($columns[$col_index]->size[$key] > 12) $columns[$col_index]->size[$key] = 12;
                            }
                        }
                        $buffer_size = [
                            'xxl' => 0,
                            'xl' => 0,
                            'lg' => 0,
                            'md' => 0,
                            'sm' => 0,
                            'xs' => 0,
                        ];
                    }
                    $prev_col_index = $col_index;
                }
            }
        }

        if (!empty($columns)) {
            if (isset($this->_data['fill']) && $this->_data['fill']) {
                if ($this->section->hasComponent) {
                    foreach ($columns[$component_index]->size as $key => $size) {
                        if ($buffer_size[$key]) {
                            $columns[$component_index]->size[$key] += $buffer_size[$key];
                            if ($columns[$component_index]->size[$key] > 12) $columns[$component_index]->size[$key] = 12;
                        }
                    }
                } else if ($prev_col_index !== null) {
                    foreach ($columns[$prev_col_index]->size as $key => $size) {
                        if ($buffer_size[$key]) {
                            $columns[$prev_col_index]->size[$key] += $buffer_size[$key];
                            if ($columns[$prev_col_index]->size[$key]>12) $columns[$prev_col_index]->size[$key] = 12;
                        }
                    }
                }
            }
            foreach ($columns as $column) {
                $this->content  .=  $column->wrap();
            }
        }
        return $this->wrap();
    }

    protected function _getclasses(): void
    {
        $this->add_class('row');

        $layout_type = $this->section->params->get('layout_type', '');

        if (in_array($layout_type, ['no-container', 'custom-container', 'container-with-no-gutters', 'container-fluid-with-no-gutters'])) {
            $this->add_class('no-gutters gx-0');
        }

        $sizes = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];
        foreach ($sizes as $size) {
            $gutter = $this->params->get('gutter_'.$size, '');
            if ($gutter !== '') {
                if ($size == 'xs') {
                    $this->add_class('gx-' . $gutter);
                } else {
                    $this->add_class('gx-' . $size . '-' . $gutter);
                }
            }
        }

        $moon_element_vertical_alignment = $this->params->get('moon_element_vertical_alignment', '');
        if (!empty($moon_element_vertical_alignment)) {
            $this->add_class('align-items-' . $moon_element_vertical_alignment);
        }
        parent::_getclasses();
    }
}
