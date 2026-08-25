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

defined('MOODLE_INTERNAL') || die;
use local_moon\library\helper\moon_element;
class moon_element_activity_header extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'activity_header',
            'title' => 'Activity Header',
            'description' => 'Activity Header Section',
            'icon' => 'as-icon as-icon-notification-circle',
            'category' => 'system',
            'element_type' => 'system',
            'multiple' => false,
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');
        $this->add_field( 'enable_heading_title',  [
            "group" => "general",
            "type" => "radio",
            "label" => "enable_heading_title",
            "description" => "enable_heading_title_desc",
            "default" => 0,
            "attributes" => [
                "role" => "switch"
            ]
        ]);
    }
}