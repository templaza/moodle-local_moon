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

defined('MOODLE_INTERNAL') || die;
use local_moon\library\helper\moon_element;
class moon_element_row extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'row',
            'title' => 'row',
            'description' => 'Row layout of Moodle',
        ]);
    }

    public function set_fields() : void
    {
        $this->set_field_set('design-settings');

        $this->add_field('moon_element_vertical_alignment', [
            "group"       => "general",
            "type"        => "list",
            "label"       => "vertical_alignment",
            "description" => "vertical_alignment_desc",
            "default"     => "",
            "options"     => [
                ""       => "inherit",
                "start"  => "top",
                "center" => "middle",
                "end"    => "bottom",
            ],
        ]);

        $this->add_field('device_gutter_settings', [
            "type"        => "group",
            "label"       => "device_gutter_settings",
            "description" => "device_gutter_settings_desc",
        ]);

        $this->add_field('gutter_xs', [
            "group"       => "device_gutter_settings",
            "type"        => "list",
            "label"       => "mobile_gutter",
            "description" => "mobile_gutter_desc",
            "default"     => "",
            "options"     => [
                "" => "inherit",
                "0" => "gx-0",
                "1" => "gx-1",
                "2" => "gx-2",
                "3" => "gx-3",
                "4" => "gx-4",
                "5" => "gx-5",
            ],
        ]);

        $this->add_field('gutter_sm', [
            "group"       => "device_gutter_settings",
            "type"        => "list",
            "label"       => "small_gutter",
            "description" => "small_gutter_desc",
            "default"     => "",
            "options"     => [
                "" => "inherit",
                "0" => "gx-sm-0",
                "1" => "gx-sm-1",
                "2" => "gx-sm-2",
                "3" => "gx-sm-3",
                "4" => "gx-sm-4",
                "5" => "gx-sm-5",
            ],
        ]);

        $this->add_field('gutter_md', [
            "group"       => "device_gutter_settings",
            "type"        => "list",
            "label"       => "medium_gutter",
            "description" => "medium_gutter_desc",
            "default"     => "",
            "options"     => [
                "" => "inherit",
                "0" => "gx-md-0",
                "1" => "gx-md-1",
                "2" => "gx-md-2",
                "3" => "gx-md-3",
                "4" => "gx-md-4",
                "5" => "gx-md-5",
            ],
        ]);

        $this->add_field('gutter_lg', [
            "group"       => "device_gutter_settings",
            "type"        => "list",
            "label"       => "large_gutter",
            "description" => "large_gutter_desc",
            "default"     => "",
            "options"     => [
                "" => "inherit",
                "0" => "gx-lg-0",
                "1" => "gx-lg-1",
                "2" => "gx-lg-2",
                "3" => "gx-lg-3",
                "4" => "gx-lg-4",
                "5" => "gx-lg-5",
            ],
        ]);

        $this->add_field('gutter_xl', [
            "group"       => "device_gutter_settings",
            "type"        => "list",
            "label"       => "xlarge_gutter",
            "description" => "xlarge_gutter_desc",
            "default"     => "",
            "options"     => [
                "" => "inherit",
                "0" => "gx-xl-0",
                "1" => "gx-xl-1",
                "2" => "gx-xl-2",
                "3" => "gx-xl-3",
                "4" => "gx-xl-4",
                "5" => "gx-xl-5",
            ],
        ]);

        $this->add_field('gutter_xxl', [
            "group"       => "device_gutter_settings",
            "type"        => "list",
            "label"       => "xxlarge_gutter",
            "description" => "xxlarge_gutter_desc",
            "default"     => "",
            "options"     => [
                "" => "inherit",
                "0" => "gx-xxl-0",
                "1" => "gx-xxl-1",
                "2" => "gx-xxl-2",
                "3" => "gx-xxl-3",
                "4" => "gx-xxl-4",
                "5" => "gx-xxl-5",
            ],
        ]);
    }
}