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
defined('MOODLE_INTERNAL') || die;
class moon_element {
    public string $name = '';
    public string $title = '';
    public string $description = '';
    public string $icon = '';
    public string $category = '';
    public string $element_type = '';
    public bool $multiple = true;
    public array $fields = [];
    protected string $_fieldSet = '';
    public function __construct($data = [])
    {
        $this->name = $data['name'] ?? '';
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->icon = $data['icon'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->element_type = $data['element_type'] ?? '';
        $this->multiple = $data['multiple'] ?? true;
        $this->fields = $data['fields'] ?? constants::get_element_default_options();
        $this->set_fields();
    }
    public function add_field_set($value, $key = ''): void
    {
        $field_set = $key;
        if (empty($field_set)) {
            $field_set = $this->_fieldSet;
        }
        if (!empty($field_set)) {
            $this->fields[$field_set] = $value;
        }
    }
    public function add_multi_field_set($fields): void
    {
        $this->fields = $fields;
    }
    public function add_field($field, $value, $field_set = ''): void
    {
        $_fieldSet = $field_set;
        if (empty($_fieldSet)) {
            $_fieldSet = $this->_fieldSet;
        }
        if (!empty($_fieldSet)) {
            $this->fields[$_fieldSet]['fields'][$field] = $value;
        }
    }
    public function set_fields(): void {}
    public function get_fields(): array
    {
        return $this->fields;
    }
    public function set_field_set($field_set): void
    {
        $this->_fieldSet = $field_set;
    }
    public function get_field_set(): string
    {
        return $this->_fieldSet;
    }
}