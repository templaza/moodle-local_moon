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
use local_moon\library\Helper\Font;
use local_moon\library\Blocks\CourseHandler;
class MoonElementCourse_Category_Tab extends MoonElement {
    public function __construct()
    {
        parent::__construct([
            'name' => 'course_category_tab',
            'title' => 'Course by Category Tab',
            'description' => 'get Course by Category display tab',
            'icon' => 'fa-solid fa-table',
            'category' => 'Course',
            'element_type' => 'widget'
        ]);
    }
    public function setFields(): void {
        $moonCourseHandler = new CourseHandler();
        $course_categories = $moonCourseHandler->moonCourseAllCategory();
        $this->setFieldSet('general-settings');

        $this->addField('title_options',  [
            "type" => "group",
            "label" => "title_options",
        ]);
        $this->addField('tab_options',  [
            "type" => "group",
            "label" => "tab_options",
        ]);
        $this->addField('slider_options',  [
            "type" => "group",
            "label" => "slider_options",
        ]);

        $this->addField('layout', [
            "group"      => "general",
            "type"       => "list",
            "label"      => "layout",
            "default"    => "style1",
            "options"    => [
                "style1" => "Style 1",
                "style2" => "Style 2",
            ],
        ]);

        $this->addField('course_category', [
            "group"      => "general",
            "type"       => "multiselect",
            "label"      => "choose_category",
            "default"    => ['1','2'],
            "options"    => $course_categories,
        ]);
        $this->addField('course_limit', [
            "group"       => "general",
            "type"        => "text",
            "label"       => "course_limit",
            "default"    => "6",
            "dynamic"     => true,
        ]);

        $this->addField('title_font_style', [
            "group"      => "title_options",
            "type"       => "typography",
            "attributes" => [
                'options' => [
                    "colorpicker" => true,
                    'stylepicker' => true,
                    'fontpicker' => true,
                    'sizepicker' => true,
                    'letterspacingpicker' => true,
                    'lineheightpicker' => true,
                    'weightpicker' => true,
                    'transformpicker' => true,
                    'columns' => 1,
                    'preview' => false,
                    'collapse' => true,
                    'system_fonts' => Font::get_system_fonts(),
                    'text_transform_options' => Font::text_transform(),
                    'lang' => Font::font_properties(),
                ],
                'lang' => Font::font_properties(),
                'value' => Font::$get_default_font_value
            ],
        ]);
        $this->addField('title_margin', [
            "group" => "title_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);
        $this->addField('tab_margin', [
            "group" => "tab_options",
            "type"  => "spacing",
            "label" => "margin",
        ]);

        $this->addField('autoplay', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'autoplay',
            "conditions" => "[layout]=='style2'",
        ]);
        $this->addField('slider_column', [
            "group"      => "slider_options",
            "type"       => "list",
            "label"      => "slider_column",
            "default"    => "col-lg-4",
            "options"    => [
                "col-lg-12" => "1 column",
                "col-lg-6" => "2 columns",
                "col-lg-4" => "3 columns",
                "col-lg-3" => "4 columns",
                "col-lg-2" => "6 columns",
            ],
            "conditions" => "[layout]=='style2'",
        ]);

        $this->addField('navigation', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => '0',
            'label'   => 'Navigation',
            "conditions" => "[layout]=='style2'",
        ]);
        $this->addField('navigation_color', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "color",
            'conditions' => "[navigation]==1 AND [layout]=='style2'",
        ]);
        $this->addField('navigation_bg_color', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "background_color",
            'conditions' => "[navigation]==1 AND [layout]=='style2'",
        ]);
        $this->addField('navigation_color_hover', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "color_hover",
            'conditions' => "[navigation]==1 AND [layout]=='style2'",
        ]);
        $this->addField('navigation_bg_color_hover', [
            "group"      => "slider_options",
            "type"       => "color",
            "label"      => "background_hover_color",
            'conditions' => "[navigation]==1 AND [layout]=='style2'",
        ]);
        $this->addField('navigation_padding', [
            'group'      => 'slider_options',
            'type'       => 'spacing',
            'label'      => 'padding',
            'conditions' => "[navigation]==1 AND [layout]=='style2'",
        ]);

        $this->addField('dot', [
            'group'   => 'slider_options',
            'type'    => 'radio',
            "attributes" => [
                "role" => "switch"
            ],
            'default' => 1,
            'label'   => 'Dotnav',
            'conditions' => "[navigation]==1 AND [layout]=='style2'",
        ]);
        $this->addField('dot_margin', [
            "group" => "slider_options",
            "type"  => "spacing",
            "label" => "margin",
            'conditions' => "[dot]==1 AND [layout]=='style2'",
        ]);

    }
}