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

use local_moon\library\Framework;

Framework::getTheme()->addFields(
    'colours',
    [
        'label' => 'colors',
        'icon' => 'as-icon as-icon-palette',
        'order' => 5,
        'fields' => [
            'body' => ["type" => "group", "label" => "body", "description" => "body_color_desc"],
            'header_colors' => ["type" => "group", "label" => "header", "description" => "header_color_desc"],
            'sticky_header' => ["type" => "group", "label" => "sticky_header", "description" => "sticky_header_color_desc"],
            'main_menu' => ["type" => "group", "label" => "main_menu", "description" => "main_menu_color_desc"],
            'dropdown_menu' => ["type" => "group", "label" => "dropdown_menu", "description" => "dropdown_menu_color_desc"],
            'off_canvas' => ["type" => "group", "label" => "block_drawer", "description" => "off_canvas_color_desc"],
            'mobilemenu' => ["type" => "group", "label" => "mobilemenu", "description" => "mobilemenu_color_desc"],

            // Body
            'body_background_color' => [
                "group" => "body",
                "type" => "color",
                "label" => "background_color",
                "description" => "body_background_color_desc",
            ],
            'body_heading_color' => [
                "group" => "body",
                "type" => "color",
                "label" => "heading_color",
                "description" => "heading_color_desc",
            ],
            'body_text_color' => [
                "group" => "body",
                "type" => "color",
                "label" => "text_color",
                "description" => "body_text_color_desc",
            ],
            'body_link_color' => [
                "group" => "body",
                "type" => "color",
                "label" => "link_color",
                "description" => "body_link_color_desc",
            ],
            'body_link_hover_color' => [
                "group" => "body",
                "type" => "color",
                "label" => "link_hover_color",
                "description" => "body_link_hover_color_desc",
            ],

            'header_bg' => [
                "group" => "header_colors",
                "type" => "color",
                "label" => "background_color",
                "description" => "header_bg_desc",
            ],
            'header_text_color' => [
                "group" => "header_colors",
                "type" => "color",
                "label" => "text_color",
                "description" => "header_text_color_desc",
            ],
            'header_heading_color' => [
                "group" => "header_colors",
                "type" => "color",
                "label" => "heading_color",
                "description" => "heading_color_desc",
            ],
            'header_link_color' => [
                "group" => "header_colors",
                "type" => "color",
                "label" => "link_color",
                "description" => "header_link_color_desc",
            ],
            'header_link_hover_color' => [
                "group" => "header_colors",
                "type" => "color",
                "label" => "link_hover_color",
                "description" => "header_link_hover_color_desc",
            ],

            'stick_header_bg_color' => [
                "group" => "sticky_header",
                "type" => "color",
                "label" => "background_color",
                "description" => "stick_header_bg_color_desc",
            ],
            'stick_header_menu_link_color' => [
                "group" => "sticky_header",
                "type" => "color",
                "label" => "menu_link_color",
                "description" => "stick_header_menu_link_color_desc",
            ],
            'stick_header_menu_link_active_color' => [
                "group" => "sticky_header",
                "type" => "color",
                "label" => "menu_link_active_color",
                "description" => "stick_header_menu_link_active_color_desc",
            ],
            'stick_header_menu_link_hover_color' => [
                "group" => "sticky_header",
                "type" => "color",
                "label" => "menu_link_hover_color",
                "description" => "stick_header_menu_link_hover_color_desc",
            ],
            'stick_header_mobile_menu_icon_color' => [
                "group" => "sticky_header",
                "type" => "color",
                "label" => "hamburger_icon_color",
                "description" => "hamburger_icon_color_desc",
            ],

            'main_menu_link_color' => [
                "group" => "main_menu",
                "type" => "color",
                "label" => "link_color",
                "description" => "main_menu_link_color_desc",
            ],
            'main_menu_link_background' => [
                "group" => "main_menu",
                "type" => "color",
                "label" => "link_background",
                "description" => "main_menu_link_background_desc",
            ],
            'main_menu_link_hover_color' => [
                "group" => "main_menu",
                "type" => "color",
                "label" => "link_hover_color",
                "description" => "main_menu_link_hover_color_desc",
            ],
            'main_menu_hover_background' => [
                "group" => "main_menu",
                "type" => "color",
                "label" => "hover_background",
                "description" => "main_menu_hover_background_desc",
            ],
            'main_menu_link_active_color' => [
                "group" => "main_menu",
                "type" => "color",
                "label" => "link_active_color",
                "description" => "main_menu_link_active_color_desc",
            ],
            'main_menu_active_background' => [
                "group" => "main_menu",
                "type" => "color",
                "label" => "active_background",
                "description" => "main_menu_active_background_desc",
            ],

            'dropdown_bg_color' => [
                "group" => "dropdown_menu",
                "type" => "color",
                "label" => "background_color",
                "description" => "dropdown_bg_color_desc",
            ],
            'dropdown_link_color' => [
                "group" => "dropdown_menu",
                "type" => "color",
                "label" => "link_color",
                "description" => "dropdown_link_color_desc",
            ],
            'dropdown_menu_active_link_color' => [
                "group" => "dropdown_menu",
                "type" => "color",
                "label" => "link_active_color",
                "description" => "dropdown_menu_active_link_color_desc",
            ],
            'dropdown_menu_active_bg_color' => [
                "group" => "dropdown_menu",
                "type" => "color",
                "label" => "active_background",
                "description" => "dropdown_menu_active_bg_color_desc",
            ],
            'dropdown_menu_link_hover_color' => [
                "group" => "dropdown_menu",
                "type" => "color",
                "label" => "menu_link_hover_color",
                "description" => "dropdown_menu_link_hover_color_desc",
            ],
            'dropdown_menu_hover_bg_color' => [
                "group" => "dropdown_menu",
                "type" => "color",
                "label" => "hover_background",
                "description" => "dropdown_menu_hover_bg_color_desc",
            ],

            'offcanvas_backgroundcolor' => [
                "group" => "off_canvas",
                "type" => "color",
                "label" => "background_color",
                "description" => "offcanvas_backgroundcolor_desc",
            ],
            'offcanvas_heading_color' => [
                "group" => "off_canvas",
                "type" => "color",
                "label" => "heading_color",
                "description" => "heading_color_desc",
            ],
            'offcanvas_text_color' => [
                "group" => "off_canvas",
                "type" => "color",
                "label" => "text_color",
                "description" => "offcanvas_text_color_desc",
            ],
            'offcanvas_link_color' => [
                "group" => "off_canvas",
                "type" => "color",
                "label" => "link_color",
                "description" => "offcanvas_link_color_desc",
            ],
            'offcanvas_link_hover_color' => [
                "group" => "off_canvas",
                "type" => "color",
                "label" => "link_hover_color",
                "description" => "link_hover_color_desc",
            ],
            'offcanvas_active_link_color' => [
                "group" => "off_canvas",
                "type" => "color",
                "label" => "link_active_color",
                "description" => "offcanvas_active_link_color_desc",
            ],
            'offcanvas_icon_color' => [
                "group" => "off_canvas",
                "type" => "color",
                "label" => "hamburger_icon_color",
                "description" => "hamburger_icon_color_desc",
            ],

            'mobilemenu_backgroundcolor' => [
                "group" => "mobilemenu",
                "type" => "color",
                "label" => "background_color",
                "description" => "mobilemenu_backgroundcolor_desc",
            ],
            'mobilemenu_menu_text_color' => [
                "group" => "mobilemenu",
                "type" => "color",
                "label" => "text_color",
                "description" => "mobilemenu_text_color_desc",
            ],
            'mobilemenu_menu_link_color' => [
                "group" => "mobilemenu",
                "type" => "color",
                "label" => "link_color",
                "description" => "mobilemenu_link_color_desc",
            ],
            'mobilemenu_menu_active_link_color' => [
                "group" => "mobilemenu",
                "type" => "color",
                "label" => "link_active_color",
                "description" => "mobilemenu_active_link_color_desc",
            ],
            'mobilemenu_menu_active_bg_color' => [
                "group" => "mobilemenu",
                "type" => "color",
                "label" => "active_background",
                "description" => "mobilemenu_active_link_background_desc",
            ],
            'mobilemenu_menu_icon_color' => [
                "group" => "mobilemenu",
                "type" => "color",
                "label" => "hamburger_icon_color",
                "description" => "hamburger_icon_color_desc",
            ],
            'mobilemenu_menu_active_icon_color' => [
                "group" => "mobilemenu",
                "type" => "color",
                "label" => "hamburger_active_icon_color",
                "description" => "hamburger_active_icon_color_desc",
            ],
        ]
    ]
);