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
use local_moon\library\helper\form;
use local_moon\library\helper\constants;
use local_moon\library\helper\font;
class moon_element_shapes extends moon_element {
    public function __construct()
    {
        parent::__construct([
            'name' => 'shapes',
            'title' => 'Shape',
            'description' => 'Add Shape for block',
            'icon' => 'as-icon as-icon-puzzle',
            'category' => 'utility',
            'element_type' => 'widget'
        ]);
    }
    public function set_fields(): void {
        $this->set_field_set('general-settings');

        $this->add_field('widget_styles', [
            'type'  => 'group',
            'label' => 'widget_styles',
        ]);
        $this->add_field('shape_style', [
            'group'   => 'general',
            'type'    => 'list',
            'label'   => 'Shape',
            'default' => '',
            'options' => [
                'wave'         => 'wave style1',
                'wave2' => 'wave style2',
                'wave3' => 'background-title',
            ],
        ]);

        $this->add_field('shape_color', [
            "group"      => "general",
            "type"       => "color",
            "label"      => "color",
        ]);


    }
}