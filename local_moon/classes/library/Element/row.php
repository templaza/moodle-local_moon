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
        $bufferSize = [
            'xxl' => 0,
            'xl' => 0,
            'lg' => 0,
            'md' => 0,
            'sm' => 0,
            'xs' => 0,
        ];
        $componentIndex = 0;
        $prevColIndex = null;

        foreach ($this->_data['cols'] as $colIndex => $col) {
            $column = new column($col, $this->section, $this, $this->role);
            $columns[$colIndex] = $column;
            $column->render();
            if ($column->component) {
                $componentIndex = $colIndex;
            }
        }

        if (isset($this->_data['fill']) && $this->_data['fill']) {
            foreach ($columns as $colIndex => $column) {
                if (empty($column->content)) {
                    foreach ($column->size as $key => $size) {
                        $bufferSize[$key] += $column->size[$key];
                    }
                    unset($columns[$colIndex]);
                } else {
                    if ($this->section->hasComponent) {
                        foreach ($columns[$componentIndex]->size as $key => $size) {
                            $columns[$componentIndex]->size[$key] += $bufferSize[$key];
                            if ($columns[$componentIndex]->size[$key] > 12) $columns[$componentIndex]->size[$key] = 12;
                        }
                        $bufferSize = [
                            'xxl' => 0,
                            'xl' => 0,
                            'lg' => 0,
                            'md' => 0,
                            'sm' => 0,
                            'xs' => 0,
                        ];
                    } else {
                        if (isset($columns[$prevColIndex])) {
                            foreach ($columns[$prevColIndex]->size as $key => $size) {
                                $columns[$prevColIndex]->size[$key] += $bufferSize[$key];
                                if ($columns[$prevColIndex]->size[$key] > 12) $columns[$prevColIndex]->size[$key] = 12;
                            }
                        } else {
                            foreach ($columns[$colIndex]->size as $key => $size) {
                                $columns[$colIndex]->size[$key] += $bufferSize[$key];
                                if ($columns[$colIndex]->size[$key] > 12) $columns[$colIndex]->size[$key] = 12;
                            }
                        }
                        $bufferSize = [
                            'xxl' => 0,
                            'xl' => 0,
                            'lg' => 0,
                            'md' => 0,
                            'sm' => 0,
                            'xs' => 0,
                        ];
                    }
                    $prevColIndex = $colIndex;
                }
            }
        }

        if (!empty($columns)) {
            if (isset($this->_data['fill']) && $this->_data['fill']) {
                if ($this->section->hasComponent) {
                    foreach ($columns[$componentIndex]->size as $key => $size) {
                        if ($bufferSize[$key]) {
                            $columns[$componentIndex]->size[$key] += $bufferSize[$key];
                            if ($columns[$componentIndex]->size[$key] > 12) $columns[$componentIndex]->size[$key] = 12;
                        }
                    }
                } else if ($prevColIndex !== null) {
                    foreach ($columns[$prevColIndex]->size as $key => $size) {
                        if ($bufferSize[$key]) {
                            $columns[$prevColIndex]->size[$key] += $bufferSize[$key];
                            if ($columns[$prevColIndex]->size[$key]>12) $columns[$prevColIndex]->size[$key] = 12;
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
