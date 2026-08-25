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
use local_moon\library\Helper\MoonElement;
class MoonElementColumn extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'column',
            'title' => 'column',
            'description' => 'Column layout of Moodle',
        ]);
    }

    public function setFields() : void
    {
        $this->setFieldSet('design-settings');

        $this->addField('device_order_settings', [
            "type"  => "group",
            "label" => "device_order",
        ]);

        $this->addField('column_order_xl', [
            "group"   => "device_order_settings",
            "type"    => "list",
            "label"   => "xlarge_order",
            "default" => "0",
            "options" => [
                "0"  => "default",
                "1"  => "1",
                "2"  => "2",
                "3"  => "3",
                "4"  => "4",
                "5"  => "5",
                "6"  => "6",
                "7"  => "7",
                "8"  => "8",
                "9"  => "9",
                "10" => "10",
                "11" => "11",
                "12" => "12",
            ],
        ]);

        $this->addField('column_order_lg', [
            "group"   => "device_order_settings",
            "type"    => "list",
            "label"   => "large_order",
            "default" => "0",
            "options" => [
                "0"  => "default",
                "1"  => "1",
                "2"  => "2",
                "3"  => "3",
                "4"  => "4",
                "5"  => "5",
                "6"  => "6",
                "7"  => "7",
                "8"  => "8",
                "9"  => "9",
                "10" => "10",
                "11" => "11",
                "12" => "12",
            ],
        ]);

        $this->addField('column_order_md', [
            "group"   => "device_order_settings",
            "type"    => "list",
            "label"   => "medium_order",
            "default" => "0",
            "options" => [
                "0"  => "default",
                "1"  => "1",
                "2"  => "2",
                "3"  => "3",
                "4"  => "4",
                "5"  => "5",
                "6"  => "6",
                "7"  => "7",
                "8"  => "8",
                "9"  => "9",
                "10" => "10",
                "11" => "11",
                "12" => "12",
            ],
        ]);

        $this->addField('column_order_sm', [
            "group"   => "device_order_settings",
            "type"    => "list",
            "label"   => "small_order",
            "default" => "0",
            "options" => [
                "0"  => "default",
                "1"  => "1",
                "2"  => "2",
                "3"  => "3",
                "4"  => "4",
                "5"  => "5",
                "6"  => "6",
                "7"  => "7",
                "8"  => "8",
                "9"  => "9",
                "10" => "10",
                "11" => "11",
                "12" => "12",
            ],
        ]);

        $this->addField('column_order_xs', [
            "group"   => "device_order_settings",
            "type"    => "list",
            "label"   => "xsmall_order",
            "default" => "0",
            "options" => [
                "0"  => "default",
                "1"  => "1",
                "2"  => "2",
                "3"  => "3",
                "4"  => "4",
                "5"  => "5",
                "6"  => "6",
                "7"  => "7",
                "8"  => "8",
                "9"  => "9",
                "10" => "10",
                "11" => "11",
                "12" => "12",
            ],
        ]);
    }
}