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

use local_moon\library\Framework;
use local_moon\library\Helper\Constants;
use local_moon\library\Helper\Text;
Framework::getTheme()->addFields(
    'social_profile',
    [
        'label' => 'social_profiles',
        'icon' => 'as-icon as-icon-share2',
        'order' => 6,
        'fields' => [
            'social_profile' => ["type" => "group", "label" => "social_profiles", "description" => "social_profiles_desc"],
            'enable_social_profiler' => [
                'group' => 'social_profile',
                'type' => 'radio',
                'label' => 'enable_social_profiles',
                'description' => 'enable_social_profiles_desc',
                'default' => '1',
                "attributes" => [
                    "role" => "switch"
                ]
            ],

            'social_profiles_position' => [
                'group' => 'social_profile',
                'type' => 'regions',
                'label' => 'select_region',
                'description' => 'select_region_desc',
                'attributes' => [
                    'astroid_content_layout' => 'social',
                ],
                'conditions' => "[enable_social_profiler]==true",
            ],

            'social_profiles_load_position' => [
                'group' => 'social_profile',
                'type' => 'list',
                'label' => 'feature_load_region',
                'description' => 'feature_load_region_desc',
                'default' => 'after',
                'attributes' => [
                    'astroid_content_layout_load' => 'social_profiles_position',
                ],
                'options' => [
                    'after' => 'after_region',
                    'before' => 'before_region',
                ],
                'conditions' => "[enable_social_profiler]==true",
            ],

            'social_profiles_gutter' => [
                'group' => 'social_profile',
                'type' => 'list',
                'label' => 'gutter',
                'description' => 'gutter_desc',
                'default' => '',
                'conditions' => "[enable_social_profiler]==true",
                'options' => [
                    ''  => 'default',
                    '1' => 'X-Small',
                    '2' => 'Small',
                    '3' => 'Medium',
                    '4' => 'Large',
                    '5' => 'X-Large',
                ],
            ],

            'social_profiles_fontsize' => [
                'group' => 'social_profile',
                'type' => 'text',
                'label' => 'font_size',
                'attributes' => [
                    'hint'  => '16px',
                ],
                'conditions' => "[enable_social_profiler]==true",
            ],

            'social_profiles_style' => [
                'group' => 'social_profile',
                'type' => 'list',
                'label' => 'style',
                'description' => 'style_desc',
                'default' => '1',
                'options' => [
                    '1' => 'inherit',
                    '2' => 'brand_color',
                ],
                'conditions' => "[enable_social_profiler]==true",
            ],

            'social_icon_color' => [
                'group' => 'social_profile',
                'type' => 'color',
                'label' => 'color',
                'description' => 'color_desc',
                'conditions' => "[enable_social_profiler]==true AND [social_profiles_style]=='1'",
            ],

            'social_icon_color_hover' => [
                'group' => 'social_profile',
                'type' => 'color',
                'label' => 'color_hover',
                'description' => 'color_hover_desc',
                'conditions' => "[enable_social_profiler]==true AND [social_profiles_style]=='1'",
            ],

            'social_profiles' => [
                'group' => 'social_profile',
                'type' => 'socialprofiles',
                'conditions' => "[enable_social_profiler]==true",
                'attributes' => [
                    'options' =>  Constants::$social_profiles,
                    'lang'   => [
                        'social_brands'  => Text::_('social_brands'),
                        'social_search'  => Text::_('social_search'),
                        'add_profile'  => Text::_('add_profile'),
                        'add_custom_social_label'  => Text::_('add_custom_profile'),
                        'astroid_color'  => Text::_('color'),
                        'astroid_icon'  => Text::_('icon'),
                        'astroid_title'  => Text::_('title'),
                        'astroid_icon_class'  => Text::_('icon_class'),
                        'astroid_link'  => Text::_('link_url'),
                        'astroid_mobile_number'  => Text::_('mobile_number'),
                        'astroid_skype_id'  => Text::_('skype_id'),
                        'astroid_username'  => Text::_('username'),
                        'astroid_social_link_placeholder'  => Text::_('social_link_placeholder'),
                        'astroid_social_whatsapp_placeholder'  => Text::_('social_whatsapp_placeholder'),
                        'astroid_social_telegram_placeholder'  => Text::_('social_telegram_placeholder'),
                        'astroid_social_skype_placeholder'  => Text::_('social_skype_placeholder'),
                    ]
                ],
            ],
        ]
    ]
);