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
class MoonElementSection extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'section',
            'title' => 'Section',
            'description' => 'Section layout of Moodle',
        ]);
    }

    public function setFields() : void
    {
        $this->setFieldSet('general-settings');
        $this->addField('layout_type', [
            "type" => "list",
            "label" => "layout_type",
            'default' => '',
            'options' => [
                '' => 'JDEFAULT',
                'container' => 'ASTROID_CONTAINER',
                'container-fluid' => 'ASTROID_CONTAINER_FLUID',
                'container-with-no-gutters' => 'ASTROID_CONTAINER_WITH_NO_GUTTERS',
                'container-fluid-with-no-gutters' => 'ASTROID_CONTAINER_FLUID_WITH_NO_GUTTERS',
                'no-container' => 'ASTROID_ELEMENT_LAYOUT_SECTION_LAYOUT_OPTIONS_WITHOUT_CONTAINER',
                'custom-container' => 'ASTROID_ELEMENT_LAYOUT_SECTION_LAYOUT_OPTIONS_CUSTOM',
            ],
        ]);

        $this->setFieldSet('design-settings');
        $this->addField('moon_element_tag', [
            "group" => "general",
            "type" => "list",
            "label" => "element_tag",
            "description" => "element_tag_desc",
            'default' => 'section',
            'options' => [
                'div'     => 'div',
                'section' => 'section',
                'header'  => 'header',
                'footer'  => 'footer',
                'aside'   => 'aside',
                'nav'     => 'nav',
                'article' => 'article',
                'address' => 'address',
                'hgroup'  => 'hgroup',
                'main'    => 'main',
            ],
        ]);
    }
}