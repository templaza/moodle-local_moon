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
use local_moon\library\framework;

class constants
{
    public static $moon_version = '1.0.5';
    public static $fontawesome_version = '7.3.1';
    public static $fancybox_version = '6.0';
    public static $animatecss_version = '3.7.0';
    public static $lenis_version = '1.3.8';
    public static $forum_link = 'https://github.com/templaza/moon-framework/issues';
    public static $documentation_link = 'https://docs.moonframe.work/';
    public static $video_tutorial_link = 'https://www.youtube.com/channel/UCUHl1uU0Ofkyo-1ke-K4_xg';
    public static $donate_link = 'https://ko-fi.com/astroidframework';
    public static $github_link = 'https://github.com/templaza/moon-framework';
    public static $download_link = 'https://github.com/templaza/moon-framework/releases/latest';
    public static $releases_link = 'https://github.com/templaza/moon-framework/releases';
    public static $moon_link = 'https://moonframe.work/';
    public static $templates_link = 'https://moonframe.work/';
    public static $jed_link = 'https://marketplace.moodle.com';
    public static $go_pro = 'https://moonframe.work';

    /**
     * Return configurations of Manager
     * @params string $mode
     * @return array
     */
    public static function manager_configs($mode = '') : array
    {
        global $DB, $USER, $CFG;
        require_once($CFG->libdir . '/externallib.php');
        $theme = framework::get_theme();
        $enable_widget  =   1;
        $tiny_mce_license =   '';
        $cache = \cache::make('local_moon', 'upload_token');
        $upload_token = $cache->get('local_moon_upload_token');

        if (empty($upload_token)) {
            $service = $DB->get_record(
                'external_services',
                ['shortname' => 'local_moon'],
                '*',
                MUST_EXIST
            );

            $validuntil = time() + $CFG->sessiontimeout;

            $upload_token = \external_generate_token(
                EXTERNAL_TOKEN_PERMANENT,
                $service,
                $USER->id,
                \context_system::instance(),
                $validuntil
            );
            $cache->set('local_moon_upload_token', $upload_token);
        }
        return [
            'site_url'              =>  $CFG->wwwroot . '/',
            'base_url'              =>  $CFG->wwwroot,
            'root_url'              =>  $CFG->wwwroot . '/',
            'template_id'           => $theme->name,
            'template_name'         => $theme->name,
            'tpl_template_name'     => $theme->name,
            'template_title'        => get_string('pluginname', 'theme_' . $theme->name),
            'enable_widget'         => $enable_widget,
            'astroid_version'       => self::$moon_version,
            'astroid_link'          => self::$moon_link,
            'document_link'         => self::$documentation_link,
            'video_tutorial'        => self::$video_tutorial_link,
            'donate_link'           => self::$donate_link,
            'github_link'           => self::$github_link,
            'jed_link'              => self::$jed_link,
            'jtemplate_link'        => parse_url($CFG->wwwroot, PHP_URL_PATH) .'/admin/themeselector.php',
            'astroid_admin_token'   => sesskey(),
            'upload_token'          => $upload_token,
            'astroid_action'        => $CFG->wwwroot . '/lib/ajax/service.php',
            'form_template'         => utilities::get_form_template($mode),
            'tiny_mce_license'      => empty($tiny_mce_license) ? 'gpl' : $tiny_mce_license,
            'is_pro'                => false,
            'dynamic_source'        => self::$dynamic_sources,
            'dynamic_source_fields' => self::dynamic_source_fields(),
            'dynamic_source_options'=> self::get_dynamic_options(),
            'astroid_legacy'        => false,
            'cms_name'              => 'moodle',
            'layouts'               => self::get_layouts(),
            'theme_config'          => $theme->get_theme_configs()
        ];
    }

    public static array $layouts = [
        'base',
        'standard',
        'course',
        'coursecategory',
        'incourse',
        'frontpage',
        'admin',
        'mycourses',
        'mydashboard',
        'mypublic',
        'login',
        'popup',
        'frametop',
        'embedded',
        'maintenance',
        'print',
        'redirect',
        'report',
        'secure',
        'custom',
    ];

    public static function get_layouts(): array
    {
        $return = [];
        foreach (self::$layouts as $layout) {
            $return[$layout] = text::_($layout);
        }
        return $return;
    }

    public static function get_default_layout(): false|string
    {
        global $CFG;
        $path = $CFG->dirroot . '/local/moon/assets/json/layouts/default.json';
        if (is_readable($path)) {
            return \file_get_contents($path);
        }
        return '';
    }

    public static function get_element_default_options(): array
    {
        $repeater_options = [
            'general-settings' => [
                'label'  => 'general',
                'fields' => [
                    // groups
                    'move_settings'    => [
                        'type'        => 'group',
                        'label'       => 'move',
                        'description' => 'move_desc',
                    ],
                    'opacity_settings' => [
                        'type'        => 'group',
                        'label'       => 'opacity',
                        'description' => 'opacity_desc',
                    ],
                    'rotate_settings'  => [
                        'type'        => 'group',
                        'label'       => 'rotate',
                        'description' => 'rotate_desc',
                    ],
                    'scale_settings'   => [
                        'type'        => 'group',
                        'label'       => 'scale',
                        'description' => 'scale_desc',
                    ],
                    'skew_settings'    => [
                        'type'        => 'group',
                        'label'       => 'skew',
                        'description' => 'skew_desc',
                    ],
                    'tween_settings'    => [
                        'type'        => 'group',
                        'label'       => 'tween',
                        'description' => 'tween_desc',
                    ],

                    // from/to fields (grouped via astroidgroup attribute)
                    'translate_x' => [
                        'group'       => 'move_settings',
                        'type'        => 'fromto',
                        'label'       => 'translate_x_label',
                        'description' => 'translate_x_desc',
                        "attributes" => [
                            'unit'        => 'px',
                        ],
                    ],
                    'translate_y' => [
                        'group'       => 'move_settings',
                        'type'        => 'fromto',
                        'label'       => 'translate_y_label',
                        'description' => 'translate_y_desc',
                        "attributes" => [
                            'unit'        => 'px',
                        ],
                    ],
                    'rotate_x' => [
                        'group'       => 'rotate_settings',
                        'type'        => 'fromto',
                        'label'       => 'rotate_x_label',
                        'description' => 'rotate_x_desc',
                        "attributes" => [
                            'unit'        => 'deg',
                        ],
                    ],
                    'rotate_y' => [
                        'group'       => 'rotate_settings',
                        'type'        => 'fromto',
                        'label'       => 'rotate_y_label',
                        'description' => 'rotate_y_desc',
                        "attributes" => [
                            'unit'        => 'deg',
                        ],
                    ],
                    'rotate_z' => [
                        'group'       => 'rotate_settings',
                        'type'        => 'fromto',
                        'label'       => 'rotate_z_label',
                        'description' => 'rotate_z_desc',
                        'unit'        => 'deg',
                    ],
                    'scale_x' => [
                        'group'       => 'scale_settings',
                        'type'        => 'fromto',
                        'label'       => 'scale_x_label',
                        'description' => 'scale_x_desc',
                    ],
                    'scale_y' => [
                        'group'       => 'scale_settings',
                        'type'        => 'fromto',
                        'label'       => 'scale_y_label',
                        'description' => 'scale_y_desc',
                    ],
                    'opacity' => [
                        'group'       => 'opacity_settings',
                        'type'        => 'fromto',
                        'label'       => 'opacity',
                        'description' => 'opacity_desc',
                    ],
                    'skew_x' => [
                        'group'       => 'skew_settings',
                        'type'        => 'fromto',
                        'label'       => 'skew_x_label',
                        'description' => 'skew_x_desc',
                        'unit'        => 'deg',
                    ],
                    'skew_y' => [
                        'group'       => 'skew_settings',
                        'type'        => 'fromto',
                        'label'       => 'skew_y_label',
                        'description' => 'skew_y_desc',
                        'unit'        => 'deg',
                    ],
                    'duration' => [
                        'group'       => 'tween_settings',
                        'type'        => 'range',
                        'label'       => 'duration_label',
                        'description' => 'duration_desc',
                        'default'     => '0.5',
                        'attributes'  => [
                            'min'     => '0',
                            'max'     => '10',
                            'step'    => '0.1',
                            'postfix' => 'seconds',
                        ],
                    ],

                    'stagger' => [
                        'group'       => 'tween_settings',
                        'type'        => 'range',
                        'label'       => 'stagger_label',
                        'description' => 'stagger_desc',
                        'default'     => '0',
                        'attributes'  => [
                            'min'     => '0',
                            'max'     => '10',
                            'step'    => '0.01',
                            'postfix' => 'seconds',
                        ],
                    ],

                ],
            ],
        ];
        $repeater   = new form('subform', ['formsource' => $repeater_options, 'formtype' => 'string']);
        $transform_form = $repeater->render_json('subform');
        $transform_form['countLabel'] = 'Scene';
        return [
            'general-settings' => [
                'label' => 'general',
                'fields' => [
                    'general' => ["type" => "group", "label" => "general"],
                    'title' => [
                        "group" => "general",
                        "type" => "text",
                        "label" => "title",
                        "default" => "",
                    ],
                    'customclass' => [
                        "type" => "text",
                        "label" => "custom_class",
                        "description" => "custom_class_desc",
                        "default" => "",
                    ],
                    'customid' => [
                        "type" => "text",
                        "label" => "custom_id",
                        "description" => "custom_id_desc",
                        "default" => "",
                    ],
                ]
            ],
            'design-settings' => [
                'label' => 'design',
                'fields' => [
                    'general'                       => ['type' => 'group'],
                    'spacing_settings'              => ['type' => 'group', 'label' => 'spacing_settings'],
                    'custom_settings'               => ['type' => 'group', 'label' => 'custom_settings'],

                    'moon_element_tag' => [
                        "group" => "general",
                        "type" => "list",
                        "label" => "element_tag",
                        "description" => "element_tag_desc",
                        'default' => 'div',
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
                    ],

                    'moon_element_overflow' => [
                        "group" => "general",
                        "type" => "list",
                        "label" => "element_overflow",
                        'default' => '',
                        'options' => [
                            ''     => 'default',
                            'overflow-hidden' => 'hidden',
                        ],
                    ],

                    'max_width' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'max_width',
                        'description' => 'max_width_desc',
                        'default' => '',
                        'options' => [
                            '' => 'inherit',
                            'xxsmall' => 'xxsmall',
                            'xsmall' => 'xsmall',
                            'small' => 'small',
                            'medium' => 'medium',
                            'large' => 'large',
                            'xlarge' => 'xlarge',
                            'xxlarge' => 'xxlarge',
                        ],
                    ],
                    'max_width_breakpoint' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'max_width_breakpoint',
                        'description' => 'max_width_breakpoint_desc',
                        'default' => '',
                        'conditions' => "[max_width]!=''",
                        'options' => [
                            '' => 'inherit',
                            'sm' => 'breakpoint_sm',
                            'md' => 'breakpoint_md',
                            'lg' => 'breakpoint_lg',
                            'xl' => 'breakpoint_xl',
                            'xxl' => 'breakpoint_xxl',
                        ],
                    ],
                    'block_align' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'block_alignment',
                        'description' => 'block_alignment_desc',
                        'default' => '',
                        'conditions' => "[max_width]!=''",
                        'options' => [
                            '' => 'inherit',
                            'start' => 'left',
                            'center' => 'center',
                            'end' => 'right',
                        ],
                    ],
                    'block_align_breakpoint' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'block_alignment_breakpoint',
                        'description' => 'block_alignment_breakpoint_desc',
                        'default' => '',
                        'conditions' => "[max_width]!=''",
                        'options' => [
                            '' => 'inherit',
                            'sm' => 'breakpoint_sm',
                            'md' => 'breakpoint_md',
                            'lg' => 'breakpoint_lg',
                            'xl' => 'breakpoint_xl',
                            'xxl' => 'breakpoint_xxl',
                        ],
                    ],
                    'block_align_fallback' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'block_alignment_fallback',
                        'description' => 'block_alignment_fallback_desc',
                        'default' => '',
                        'conditions' => "[max_width]!='' AND [block_align_breakpoint]!=''",
                        'options' => [
                            '' => 'inherit',
                            'start' => 'left',
                            'center' => 'center',
                            'end' => 'right',
                        ],
                    ],

                    'text_alignment' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'text_alignment',
                        'description' => 'text_alignment_desc',
                        'default' => '',
                        'options' => [
                            '' => 'inherit',
                            'start' => 'left',
                            'center' => 'center',
                            'end' => 'right',
                        ],
                    ],
                    'text_alignment_breakpoint' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'text_alignment_breakpoint',
                        'description' => 'text_alignment_breakpoint_desc',
                        'default' => '',
                        'conditions' => "[text_alignment]!=''",
                        'options' => [
                            '' => 'inherit',
                            'sm' => 'breakpoint_sm',
                            'md' => 'breakpoint_md',
                            'lg' => 'breakpoint_lg',
                            'xl' => 'breakpoint_xl',
                            'xxl' => 'breakpoint_xxl',
                        ],
                    ],
                    'text_alignment_fallback' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'text_alignment_fallback',
                        'description' => 'text_alignment_fallback_desc',
                        'default' => '',
                        'conditions' => "[text_alignment]!='' AND [text_alignment_breakpoint]!=''",
                        'options' => [
                            '' => 'inherit',
                            'start' => 'left',
                            'center' => 'center',
                            'end' => 'right',
                        ],
                    ],

                    'background_setting' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'background_type',
                        'description' => 'background_type_desc',
                        'default' => '0',
                        'options' => [
                            '0' => 'none',
                            'color' => 'color',
                            'image' => 'image',
                            'video' => 'video',
                            'gradient' => 'gradient',
                        ],
                    ],
                    'background_color' => [
                        'group' => 'general',
                        'type' => 'color',
                        'label' => 'background_color',
                        'conditions' => "[background_setting] =='color'",
                        'description' => 'background_color_desc',
                    ],
                    'img_background_color' => [
                        'group' => 'general',
                        'type' => 'color',
                        'label' => 'background_color',
                        'conditions' => "[background_setting] =='image'",
                    ],
                    'background_image' => [
                        'group' => 'general',
                        'type' => 'media',
                        'label' => 'background_image',
                        'conditions' => "[background_setting] =='image' OR [background_setting] =='video'",
                    ],
                    'background_repeat' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'background_repeat',
                        'hint' => 'background_repeat',
                        'conditions' => "[background_setting] =='image'",
                        'options' => [
                            '' => 'inherit',
                            'no-repeat' => 'no_repeat',
                            'repeat-x' => 'repeat_x',
                            'repeat-y' => 'repeat_y',
                        ],
                    ],
                    'background_size' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'background_size',
                        'conditions' => "[background_setting] =='image'",
                        'options' => [
                            '' => 'inherit',
                            'cover' => 'cover',
                            'contain' => 'contain',
                        ],
                    ],
                    'background_attachment' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'background_attachment',
                        'conditions' => "[background_setting] =='image'",
                        'options' => [
                            '' => 'inherit',
                            'scroll' => 'scroll',
                            'fixed' => 'fixed',
                        ],
                    ],
                    'background_position' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'background_position',
                        'conditions' => "[background_setting] =='image'",
                        'options' => [
                            "" => "inherit",
                            "left top" => "left_top",
                            "left center" => "left_center",
                            "left bottom" => "left_bottom",
                            "right top" => "right_top",
                            "right center" => "right_center",
                            "right bottom" => "right_bottom",
                            "center top" => "center_top",
                            "center center" => "center_center",
                            "center bottom" => "center_bottom",
                        ],
                    ],
                    'background_video' => [
                        'group' => 'general',
                        'type' => 'media',
                        'label' => 'background_video',
                        "attributes" => [
                            "media" => "videos",
                        ],
                        'conditions' => "[background_setting]=='video'",
                    ],
                    'background_image_overlay' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'overlay_color',
                        'description' => 'overlay_color_desc',
                        'default' => '0',
                        'conditions' => "[background_setting]=='image' OR [background_setting]=='video'",
                        'options' => [
                            "" => "none",
                            "color" => "color",
                            "gradient" => "gradient",
                            "pattern" => "pattern"
                        ],
                    ],
                    'background_image_overlay_color' => [
                        'group' => 'general',
                        'type' => 'color',
                        'label' => 'background_color',
                        'conditions' => "([background_setting]=='image' OR [background_setting]=='video') AND ([background_image_overlay]=='color' OR [background_image_overlay]=='pattern')",
                    ],
                    'background_image_overlay_gradient' => [
                        'group' => 'general',
                        'type' => 'gradient',
                        'label' => 'gradient_color',
                        'conditions' => "([background_setting]=='image' OR [background_setting]=='video') AND [background_image_overlay]=='gradient'",
                    ],
                    'background_image_overlay_pattern' => [
                        'group' => 'general',
                        'type' => 'media',
                        'label' => 'pattern',
                        'conditions' => "([background_setting]=='image' OR [background_setting]=='video') AND [background_image_overlay]=='pattern'",
                    ],
                    'background_gradient' => [
                        'group' => 'general',
                        'type' => 'gradient',
                        'label' => 'gradient',
                        'conditions' => "[background_setting] =='gradient'",
                    ],

                    // Parallax
                    'enable_background_parallax' => [
                        'group'       => 'general',
                        'type'        => 'radio',
                        'label'       => 'enable_parallax',
                        'description' => 'enable_parallax_desc',
                        'default'     => '0',
                        'attributes'  => ["role" => "switch"],
                    ],

                    'background_parallax_speed' => [
                        'group'       => 'general',
                        'type'        => 'range',
                        'label'       => 'parallax_speed',
                        'description' => 'parallax_speed_desc',
                        'attributes' => [
                            'min'         => 0,
                            'max'         => 1,
                            'step'        => 0.01,
                            'postfix'     => 'seconds',
                        ],
                        'conditions'  => "([background_setting]=='image' OR [background_setting]=='video') AND [enable_background_parallax]==1",
                    ],

                    'background_parallax_scrub' => [
                        'group'       => 'general',
                        'type'        => 'range',
                        'label'       => 'scrub_label',
                        'description' => 'scrub_desc',
                        'attributes' => [
                            'min'         => 0.1,
                            'max'         => 10,
                            'step'        => 0.1,
                            'postfix'     => 'seconds',
                        ],
                        'default'     => 2,
                        'conditions'  => "([background_setting]=='image' OR [background_setting]=='video') AND [enable_background_parallax]==1",
                    ],


                    'border_style' => [
                        'group' => 'general',
                        'type' => 'border',
                        'label' => 'border_options',
                    ],
                    'border_radius' => [
                        'group' => 'general',
                        'type' => 'range',
                        'label' => 'border_radius',
                        'attributes' => [
                            'min' => 0,
                            'max' => 300,
                            'step' => 1,
                            'postfix' => 'px',
                            'responsive' => true,
                        ]
                    ],
                    'custom_colors' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'custom_colors',
                        'default' => 0,
                        "attributes" => [
                            "role" => "switch"
                        ]
                    ],
                    'text_color' => [
                        'group' => 'general',
                        'type' => 'color',
                        'label' => 'text_color',
                        'conditions' => "[custom_colors]==1",
                    ],
                    'link_color' => [
                        'group' => 'general',
                        'type' => 'color',
                        'label' => 'link_color',
                        'conditions' => "[custom_colors]==1",
                    ],
                    'link_hover_color' => [
                        'group' => 'general',
                        'type' => 'color',
                        'label' => 'link_hover_color',
                        'conditions' => "[custom_colors]==1",
                    ],

                    'astroid_element_sticky_effect' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'sticky_effect',
                        'description' => 'sticky_effect_desc',
                        'default' => '',
                        'options' => [
                            '' => 'inherit',
                            'top' => 'top',
                            'bottom' => 'bottom',
                        ],
                    ],
                    'astroid_element_sticky_effect_offset' => [
                        'group' => 'general',
                        'type' => 'range',
                        'label' => 'sticky_effect_offset',
                        'description' => 'sticky_effect_offset_desc',
                        "attributes" => [
                            'min' => 0,
                            'max' => 2000,
                            'step' => 1,
                            'postfix' => 'px|vh|rem|em|%',
                            'responsive' => true,
                        ],
                        'conditions' => "[astroid_element_sticky_effect]!=''",
                    ],
                    'astroid_element_sticky_effect_breakpoint' => [
                        'group' => 'general',
                        'type' => 'list',
                        'label' => 'sticky_effect_offset_breakpoint',
                        'description' => 'sticky_effect_offset_breakpoint_desc',
                        'default' => '',
                        'conditions' => "[astroid_element_sticky_effect]!=''",
                        'options' => [
                            '' => 'inherit',
                            'sm' => 'breakpoint_sm',
                            'md' => 'breakpoint_md',
                            'lg' => 'breakpoint_lg',
                            'xl' => 'breakpoint_xl',
                            'xxl' => 'breakpoint_xxl',
                        ],
                    ],

                    'margin' => [
                        'group' => 'spacing_settings',
                        'type' => 'spacing',
                        'label' => 'margin',
                    ],
                    'padding' => [
                        'group' => 'spacing_settings',
                        'type' => 'spacing',
                        'label' => 'padding',
                    ],

                    'custom_css' => [
                        'group' => 'custom_settings',
                        'type' => 'textarea',
                        'label' => 'custom_css',
                        'description' => 'custom_css_desc',
                        "attributes" => [
                            'code' => 'scss',
                        ],
                    ],
                ]
            ],
            'animation-settings' => [
                'label' => 'animation',
                'fields' => [
                    'animation_settings'            => ['type' => 'group', 'label' => 'animation_basic'],
                    'transform_settings'            => ['type' => 'group', 'label' => 'transform_settings'],
                    'animation_background_settings' => ['type' => 'group', 'label' => 'animation_background_settings'],
                    // Animation Settings
                    'animation' => [
                        'group' => 'animation_settings',
                        'type' => 'animations',
                        'attributes' => [
                            'options' => self::get_animations(),
                        ],
                        'label' => 'animation_type',
                        'description' => 'animation_type_desc',
                    ],
                    'animation_delay' => [
                        'group' => 'animation_settings',
                        'type' => 'text',
                        'label' => 'animation_delay',
                        'default' => '500',
                        "attributes" => [
                            'hint' => '500',
                        ],
                        'conditions' => "[animation]!=''",
                    ],
                    'animation_duration' => [
                        'group' => 'animation_settings',
                        'type' => 'text',
                        'label' => 'animation_duration',
                        'default' => '500',
                        "attributes" => [
                            'hint' => '500',
                        ],
                        'conditions' => "[animation]!=''",
                    ],
                    'animation_loop' => [
                        'group'       => 'animation_settings',
                        'type'        => 'radio',
                        'label'       => 'animation_loop',
                        'description' => 'animation_loop_desc',
                        'default'     => 0,
                        "attributes" => [
                            "role" => "switch"
                        ],
                        'conditions'      => "[animation]!=''",
                    ],
                    'animation_stagger' => [
                        'group'       => 'animation_settings',
                        'type'        => 'range',
                        'label'       => 'stagger_time',
                        'description' => 'stagger_time_desc',
                        'default'     => '200',
                        'attributes' => [
                            'min'         => 0,
                            'max'         => 2000,
                            'step'        => 1,
                            'postfix'     => 'milliseconds',
                        ],
                        'conditions'      => "[animation]!=''",
                    ],
                    'animation_element' => [
                        'group'       => 'animation_settings',
                        'type'        => 'text',
                        'label'       => 'animation_element',
                        'description' => 'animation_element_desc',
                        'default'     => '',
                        'conditions'      => "[animation]!=''",
                    ],

                    // Transform Settings
                    'transform_scenes' => [
                        'group' => 'transform_settings',
                        'type'        => 'subform',
                        'label'       => 'transform_scenes',
                        'description' => 'transform_scenes_desc',
                        "attributes" => [
                            'form'    =>  $transform_form
                        ],
                    ],

                    'transform_timeline_settings_divider' => [
                        'group'      => 'transform_settings',
                        'type'       => 'divider',
                        'conditions' => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_timeline_settings' => [
                        'group'       => 'transform_settings',
                        'type'        => 'heading',
                        'label'       => 'timeline_settings',
                        'description' => 'timeline_settings_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_element' => [
                        'group'       => 'transform_settings',
                        'type'        => 'text',
                        'label'       => 'animation_element',
                        'description' => 'animation_element_desc',
                        'default'     => '',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_repeat' => [
                        'group'       => 'transform_settings',
                        'type'        => 'range',
                        'label'       => 'repeat',
                        'description' => 'repeat_desc',
                        'default'     => '0',
                        'attributes'  => [
                            'min'     => '-1',
                            'max'     => '10',
                            'step'    => '1',
                            'postfix' => 'times',
                        ],
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_scroll_settings_divider' => [
                        'group' => 'transform_settings',
                        'type'  => 'divider',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_scroll_settings' => [
                        'group'       => 'transform_settings',
                        'type'        => 'heading',
                        'label'       => 'scroll_settings_title',
                        'description' => 'scroll_settings_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_start' => [
                        'group'       => 'transform_settings',
                        'type'        => 'text',
                        'label'       => 'start_label',
                        'description' => 'start_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_end' => [
                        'group'       => 'transform_settings',
                        'type'        => 'text',
                        'label'       => 'end_label',
                        'description' => 'end_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_scrub' => [
                        'group'       => 'transform_settings',
                        'type'        => 'range',
                        'attributes'  => [
                            'min'         => 0,
                            'max'         => 20,
                            'step'        => 0.1,
                            'postfix'     => 'seconds',
                        ],
                        'default'     => 2,
                        'label'       => 'scrub_label',
                        'description' => 'scrub_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_pin' => [
                        'group'       => 'transform_settings',
                        'type'        => 'radio',
                        'attributes'  => ["role" => "switch"],
                        'default'     => 0,
                        'label'       => 'pin_label',
                        'description' => 'pin_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_pin_spacing' => [
                        'group'       => 'transform_settings',
                        'type'        => 'radio',
                        'attributes'  => ["role" => "switch"],
                        'default'     => 1,
                        'label'       => 'pin_spacing_label',
                        'description' => 'pin_spacing_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]' AND [transform_pin]==1",
                    ],

                    'transform_markers' => [
                        'group'       => 'transform_settings',
                        'type'        => 'radio',
                        'attributes'  => ["role" => "switch"],
                        'default'     => 0,
                        'label'       => 'markers_label',
                        'description' => 'markers_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],

                    'transform_toggle_actions' => [
                        'group'       => 'transform_settings',
                        'type'        => 'text',
                        'label'       => 'toggle_actions_label',
                        'description' => 'toggle_actions_desc',
                        'conditions'  => "[transform_scenes]!='' AND [transform_scenes]!='[]'",
                    ],
                ]
            ],
            'visibility-settings' => [
                'label' => 'visibility',
                'fields' => [
                    'general' => ['type' => 'group'],
                    'hideonxs' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'extra_small_devices',
                        'description' => 'extra_small_devices_desc',
                        'default' => 0,
                        "attributes" => [
                            "role" => "switch"
                        ],
                    ],
                    'hideonsm' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'small_devices',
                        'description' => 'small_devices_desc',
                        'default' => 0,
                        "attributes" => [
                            "role" => "switch"
                        ],
                    ],
                    'hideonmd' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'medium_devices',
                        'description' => 'medium_devices_desc',
                        'default' => 0,
                        "attributes" => [
                            "role" => "switch"
                        ],
                    ],
                    'hideonlg' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'large_devices',
                        'description' => 'large_devices_desc',
                        'default' => 0,
                        "attributes" => [
                            "role" => "switch"
                        ],
                    ],
                    'hideonxl' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'extra_large_devices',
                        'description' => 'extra_large_devices_desc',
                        'default' => 0,
                        "attributes" => [
                            "role" => "switch"
                        ],
                    ],
                    'hideonxxl' => [
                        'group' => 'general',
                        'type' => 'radio',
                        'label' => 'extra_extra_large_devices',
                        'description' => 'extra_extra_large_devices_desc',
                        'default' => 0,
                        "attributes" => [
                            "role" => "switch"
                        ],
                    ],
                ]
            ]
        ];
    }

    public static $bootstrap_colors = [
        '' => 'default',
        'blue' => 'blue',
        'indigo' => 'indigo',
        'purple' => 'purple',
        'pink' => 'pink',
        'red' => 'red',
        'orange' => 'orange',
        'yellow' => 'yellow',
        'green' => 'green',
        'teal' => 'teal',
        'cyan' => 'cyan',
        'white' => 'white',
        'gray100' => 'light_gray',
        'gray600' => 'gray',
        'gray800' => 'gray_dark',
        'custom' => 'custom'
    ];

    public static function get_animations() : array
    {
        $options = array();
        foreach (self::$animations as $group => $animations) {
            foreach ($animations as $key => $value) {
                $options[] = [
                    'value' => $key,
                    'text'  => $value
                ];
            }
        }
        return $options;
    }

    public static $animations = [
        '' => ['' => 'None'],
        'Attention Seekers' => [
            'bounce' => 'bounce',
            'flash' => 'flash',
            'pulse' => 'pulse',
            'rubberBand' => 'rubberBand',
            'shake' => 'shake',
            'swing' => 'swing',
            'tada' => 'tada',
            'wobble' => 'wobble',
            'jello' => 'jello',
            'heartBeat' => 'heartBeat'
        ],
        'Bouncing Entrances' => [
            'bounceIn' => 'bounceIn',
            'bounceInDown' => 'bounceInDown',
            'bounceInLeft' => 'bounceInLeft',
            'bounceInRight' => 'bounceInRight',
            'bounceInUp' => 'bounceInUp'
        ],
        'Fading Entrances' => [
            'fadeIn' => 'fadeIn',
            'fadeInDown' => 'fadeInDown',
            'fadeInDownBig' => 'fadeInDownBig',
            'fadeInLeft' => 'fadeInLeft',
            'fadeInLeftBig' => 'fadeInLeftBig',
            'fadeInRight' => 'fadeInRight',
            'fadeInRightBig' => 'fadeInRightBig',
            'fadeInUp' => 'fadeInUp',
            'fadeInUpBig' => 'fadeInUpBig',
            'fadeInTopLeft' => 'fadeInTopLeft',
            'fadeInTopRight' => 'fadeInTopRight',
            'fadeInBottomLeft' => 'fadeInBottomLeft',
            'fadeInBottomRight' => 'fadeInBottomRight',
        ],
        'Flippers Entrances' => [
            'flip' => 'flip',
            'flipInX' => 'flipInX',
            'flipInY' => 'flipInY'
        ],
        'Lightspeed Entrances' => [
            'lightSpeedInRight' => 'lightSpeedInRight',
            'lightSpeedInLeft' => 'lightSpeedInLeft',
        ],
        'Rotating Entrances' => [
            'rotateIn' => 'rotateIn',
            'rotateInDownLeft' => 'rotateInDownLeft',
            'rotateInDownRight' => 'rotateInDownRight',
            'rotateInUpLeft' => 'rotateInUpLeft',
            'rotateInUpRight' => 'rotateInUpRight'
        ],
        'Sliding Entrances' => [
            'slideInUp' => 'slideInUp',
            'slideInDown' => 'slideInDown',
            'slideInLeft' => 'slideInLeft',
            'slideInRight' => 'slideInRight',
        ],
        'Zoom Entrances' => [
            'zoomIn' => 'zoomIn',
            'zoomInDown' => 'zoomInDown',
            'zoomInLeft' => 'zoomInLeft',
            'zoomInRight' => 'zoomInRight',
            'zoomInUp' => 'zoomInUp',
        ],
        'Specials' => [
            'hinge' => 'hinge',
            'jackInTheBox' => 'jackInTheBox',
            'rollIn' => 'rollIn',
        ],
    ];

    public static $menu_animations = [
        '' => ['' => 'None'],
        'Bouncing Entrances' => [
            'bounceIn' => 'bounceIn',
            'bounceInDown' => 'bounceInDown',
            'bounceInLeft' => 'bounceInLeft',
            'bounceInRight' => 'bounceInRight',
            'bounceInUp' => 'bounceInUp'
        ],
        'Fading Entrances' => [
            'fadeIn' => 'fadeIn',
            'fadeInDown' => 'fadeInDown',
            'fadeInDownBig' => 'fadeInDownBig',
            'fadeInLeft' => 'fadeInLeft',
            'fadeInLeftBig' => 'fadeInLeftBig',
            'fadeInRight' => 'fadeInRight',
            'fadeInRightBig' => 'fadeInRightBig',
            'fadeInUp' => 'fadeInUp',
            'fadeInUpBig' => 'fadeInUpBig'
        ],
        'Rotating Entrances' => [
            'rotateIn' => 'rotateIn',
            'rotateInDownLeft' => 'rotateInDownLeft',
            'rotateInDownRight' => 'rotateInDownRight',
            'rotateInUpLeft' => 'rotateInUpLeft',
            'rotateInUpRight' => 'rotateInUpRight'
        ],
        'Sliding Entrances' => [
            'slideInUp' => 'slideInUp',
            'slideInDown' => 'slideInDown',
            'slideInLeft' => 'slideInLeft',
            'slideInRight' => 'slideInRight'
        ],
        'Zoom Entrances' => [
            'zoomIn' => 'zoomIn',
            'zoomInDown' => 'zoomInDown',
            'zoomInLeft' => 'zoomInLeft',
            'zoomInRight' => 'zoomInRight',
            'zoomInUp' => 'zoomInUp'
        ],
        'Specials' => [
            'hinge' => 'hinge',
            'jackInTheBox' => 'jackInTheBox',
            'rollIn' => 'rollIn',
            'rollOut' => 'rollOut'
        ],
    ];

    public static array $hover_transition = [
        ""                   => "default",
        "scale-up"           => "scale_up",
        "scale-down"         => "scale_down",
        "bob"                => "bob",
        "pulse"              => "pulse",
        "pulse-grow"         => "pulse_grow",
        "pulse-shrink"       => "pulse_shrink",
        "push"               => "push",
        "pop"                => "pop",
        "bounce-in"          => "bounce_in",
        "bounce-out"         => "bounce_out",
        "rotate"             => "rotate",
        "grow-rotate"        => "grow_rotate",
        "float"              => "float",
        "sink"               => "sink",
        "hang"               => "hang",
        "skew"               => "skew",
        "skew-forward"       => "skew_forward",
        "skew-backward"      => "skew_backward",
        "wobble-vertical"    => "wobble_vertical",
        "wobble-horizontal"  => "wobble_horizontal",
        "wobble-to-bottom-right" => "wobble_to_bottom_right",
        "wobble-to-top-right"    => "wobble_to_top_right",
        "wobble-top"         => "wobble_top",
        "wobble-bottom"      => "wobble_bottom",
        "wobble-skew"        => "wobble_skew",
        "buzz"               => "buzz",
        "buzz-out"           => "buzz_out",
    ];

    public static $icons = [
        [
            'fas fa-long-arrow-alt-up' => 'Alternate Long Arrow Up',
            'fas fa-arrow-up' => 'arrow-up',
            'fas fa-arrow-circle-up' => 'Arrow Circle Up',
            'fas fa-arrow-alt-circle-up' => 'Alternate Arrow Circle Up',
            'fas fa-angle-double-up' => 'Angle Double Up',
            'fas fa-sort-up' => 'Sort Up (Ascending)',
            'fas fa-level-up-alt' => 'Level Up Alternate',
            'fas fa-cloud-upload-alt' => 'Cloud Upload Alternate',
            'fas fa-chevron-up' => 'chevron-up',
            'fas fa-chevron-circle-up' => 'Chevron Circle Up',
            'fas fa-hand-point-up' => 'Hand Pointing Up',
            'far fa-hand-point-up' => 'Hand Pointing Up',
            'fas fa-caret-square-up' => 'Caret Square Up',
            'far fa-caret-square-up' => 'Caret Square Up',
        ]
    ];

    public static $dynamic_sources = [
        'none' => 'None',
        'content' => 'Articles',
        'categories' => 'Categories',
        'users' => 'Users',
    ];

    public static function dynamic_source_fields(): array
    {
        return [
            'none' => [
                'value' => 'none',
                'name' => 'None',
                'fields' => [],
                'order' => [],
                'filters' => [],
                'joins' => []
            ],
            'content' => [
                'value' => 'content',
                'name' => 'Articles',
                'fields' => [
                    'title' => 'Title',
                    'introtext' => 'Intro Text',
                    'text' => 'Content',
                    'created' => 'Created Date',
                    'modified' => 'Modified Date',
                    'publish_up' => 'Published',
                    'created_by' => 'Created By',
                    'modified_by' => 'Modified By',
                    'featured' => 'Featured',
                    'images.image_intro' => 'Intro Image',
                    'images.image_intro_alt' => 'Intro Image Alt',
                    'images.image_intro_caption' => 'Intro Image Caption',
                    'images.image_fulltext' => 'Full Image',
                    'images.image_fulltext_alt' => 'Full Image Alt',
                    'images.image_fulltext_caption' => 'Full Image Caption',
                    'link' => 'Link',
                    'urls.urla' => 'Link A',
                    'urls.urlatext' => 'Link A Text',
                    'urls.urlb' => 'Link B',
                    'urls.urlbtext' => 'Link B Text',
                    'urls.urlc' => 'Link C',
                    'urls.urlctext' => 'Link C Text',
                    'rating' => 'Rating',
                    'votes' => 'Votes',
                    'hits' => 'Hits',
                    'event.afterDisplayTitle' => 'Event After Display Title',
                    'event.beforeDisplayContent' => 'Event Before Display Content',
                    'event.afterDisplayContent' => 'Event After Display Content',
                    'state' => 'State',
                    'id' => 'ID',
                    'alias' => 'Alias',
                ],
                'order' => [
                    'title' => 'Title',
                    'created' => 'Created Date',
                    'modified' => 'Modified Date',
                    'publish_up' => 'Published',
                    'ordering' => 'Ordering',
                    'hits' => 'Hits',
                    'random' => 'Random',
                ],
                'filters' => [
                    'content',
                ],
                'joins' => [],
                'where' => [
                    'content.state = 1',
                ],
                'depends' => [
                    'categories'
                ],
            ],
            'categories' => [
                'value' => 'categories',
                'name' => 'Categories',
                'fields' => [
                    'title' => 'Title',
                    'description' => 'Description',
                    'created_time' => 'Created Date',
                    'modified_time' => 'Modified Date',
                    'created_user_id' => 'Created By',
                    'modified_user_id' => 'Modified By',
                    'params.image' => 'Image',
                    'params.image_alt' => 'Image Alt',
                    'link' => 'Link',
                    'article_count' => 'Article Count',
                    'id' => 'ID',
                    'alias' => 'Alias',
                ],
                'order' => [
                    'title' => 'Title',
                    'created_time' => 'Created Date',
                    'modified_time' => 'Modified Date',
                    'hits' => 'Hits',
                    'random' => 'Random',
                ],
                'filters' => [
                    'content','categories'
                ],
                'joins' => [
                    'content' => [
                        'join' => 'LEFT',
                        'on' => 'content.catid = categories.id',
                    ]
                ],
                'where' => [
                    'categories.published = 1',
                    'categories.extension = "com_content"',
                ],
                'depends' => []
            ],
            'users' => [
                'value' => 'users',
                'name' => 'Users',
                'fields' => [
                    'name' => 'Name',
                    'username' => 'Username',
                    'email' => 'Email',
                    'registerDate' => 'Register Date',
                    'lastvisitDate' => 'Last Visit Date',
                    'link' => 'Link',
                    'user_groups' => 'User Groups',
                    'id' => 'ID',
                ],
                'order' => [
                    'name' => 'Name',
                    'username' => 'Username',
                    'email' => 'Email',
                    'registerDate' => 'Register Date',
                    'lastvisitDate' => 'Last Visit Date',
                ],
                'filters' => [
                    'content','categories','users'
                ],
                'joins' => [
                    'content' => [
                        'join' => 'LEFT',
                        'on' => 'content.created_by = users.id',
                    ],
                    'categories' => [
                        'join' => 'LEFT',
                        'on' => 'categories.created_user_id = users.id',
                    ],
                ],
                'where' => [
                    'users.block = 0',
                ],
                'depends' => []
            ],
        ];
    }

    public static function get_dynamic_options(): array
    {
        return [
            'categories' => [],
            'parent_category' => []
        ];
    }

    public static $preloder_animations = [
        [
            'audio' => ['label' => 'Audio', 'image' => 'preloader/audio.svg'],
            'ball_triangle' => ['label' => 'Ball Triangle', 'image' => 'preloader/ball-triangle.svg'],
            'bars' => ['label' => 'Bars', 'image' => 'preloader/bars.svg'],
            'circles' => ['label' => 'Circles', 'image' => 'preloader/circles.svg'],
            'grid' => ['label' => 'Grid', 'image' => 'preloader/grid.svg'],
            'oval' => ['label' => 'Oval', 'image' => 'preloader/oval.svg'],
            'puff' => ['label' => 'Puff', 'image' => 'preloader/puff.svg'],
            'rings' => ['label' => 'Rings', 'image' => 'preloader/rings.svg'],
            'spinning_circles' => ['label' => 'Spinning Circles', 'image' => 'preloader/spinning-circles.svg'],
            'tail_spin' => ['label' => 'Tail Spin', 'image' => 'preloader/tail-spin.svg'],
            'three_dots' => ['label' => 'Three Dots', 'image' => 'preloader/three-dots.svg'],
        ]
    ];

    public static $social_profiles = [
        ['title' => 'Behance', 'link' => '', 'icons' => ['fab fa-behance', 'fab fa-behance-square'], 'color' => '#2252FF', 'enabled' => false, 'icon' => 'fab fa-behance'],
        ['title' => 'Dribbble', 'link' => '', 'icons' => ['fab fa-dribbble', 'fab fa-dribbble-square'], 'color' => '#F10A77', 'enabled' => false, 'icon' => 'fab fa-dribbble'],
        ['title' => 'Facebook', 'link' => '', 'icons' => ['fab fa-facebook-f', 'fab fa-facebook', 'fab fa-facebook-square'], 'color' => '#39539E', 'enabled' => false, 'icon' => 'fab fa-facebook-f'],
        ['title' => 'TikTok', 'link' => '', 'icons' => ['fa-brands fa-tiktok'], 'color' => '#000000', 'enabled' => false, 'icon' => 'fa-brands fa-tiktok'],
        ['title' => 'Flickr', 'link' => '', 'icons' => ['fab fa-flickr'], 'color' => '#0054E3', 'enabled' => false, 'icon' => 'fab fa-flickr'],
        ['title' => 'GitHub', 'link' => '', 'icons' => ['fab fa-github', 'fab fa-github-square', 'fab fa-github-alt'], 'color' => '#171515', 'enabled' => false, 'icon' => 'fab fa-github'],
        ['title' => 'Instagram', 'link' => '', 'icons' => ['fab fa-instagram'], 'color' => '#467FAA', 'enabled' => false, 'icon' => 'fab fa-instagram'],
        ['title' => 'LinkedIn', 'link' => '', 'icons' => ['fab fa-linkedin-in', 'fab fa-linkedin'], 'color' => '#006FB8', 'enabled' => false, 'icon' => 'fab fa-linkedin-in'],
        ['title' => 'Messenger', 'link' => '', 'icons' => ['fab fa-facebook-messenger'], 'color' => '#3876C4', 'enabled' => false, 'icon' => 'fab fa-facebook-messenger'],
        ['title' => 'Pinterest', 'link' => '', 'icons' => ['fab fa-pinterest', 'fab fa-pinterest-square', 'fab fa-pinterest-p'], 'color' => '#DB0000', 'enabled' => false, 'icon' => 'fab fa-pinterest'],
        ['title' => 'reddit', 'link' => '', 'icons' => ['fab fa-reddit', 'fab fa-reddit-square', 'fab fa-reddit-alien'], 'color' => '#FF2400', 'enabled' => false, 'icon' => 'fab fa-reddit'],
        ['title' => 'Slack', 'link' => '', 'icons' => ['fab fa-slack', 'fab fa-slack-hash'], 'color' => '#50364C', 'enabled' => false, 'icon' => 'fab fa-slack'],
        ['title' => 'SoundCloud', 'link' => '', 'icons' => ['fab fa-soundcloud'], 'color' => '#FF0000', 'enabled' => false, 'icon' => 'fab fa-soundcloud'],
        ['title' => 'Spotify', 'link' => '', 'icons' => ['fab fa-spotify'], 'color' => '#00E155', 'enabled' => false, 'icon' => 'fab fa-spotify'],
        ['title' => 'Twitter', 'link' => '', 'icons' => ['fab fa-twitter', 'fab fa-twitter-square'], 'color' => '#3DA9F6', 'enabled' => false, 'icon' => 'fab fa-twitter'],
        ['title' => 'X Twitter', 'link' => '', 'icons' => ['fa-brands fa-x-twitter', 'fa-brands fa-square-x-twitter'], 'color' => '#3DA9F6', 'enabled' => false, 'icon' => 'fa-brands fa-x-twitter'],
        ['title' => 'Telegram', 'link' => '', 'icons' => ['fab fa-telegram-plane', 'fab fa-telegram'], 'color' => '#004056', 'enabled' => false, 'icon' => 'fab fa-telegram-plane'],
        ['title' => 'Tumblr', 'link' => '', 'icons' => ['fab fa-tumblr', 'fab fa-tumblr-square'], 'color' => '#00263C', 'enabled' => false, 'icon' => 'fab fa-tumblr'],
        ['title' => 'VK', 'link' => '', 'icons' => ['fab fa-vk'], 'color' => '#4273AD', 'enabled' => false, 'icon' => 'fab fa-vk'],
        ['title' => 'WhatsApp', 'link' => '', 'icons' => ['fab fa-whatsapp', 'fab fa-whatsapp-square'], 'color' => '#00C033', 'enabled' => false, 'icon' => 'fab fa-whatsapp'],
        ['title' => 'YouTube', 'link' => '', 'icons' => ['fab fa-youtube', 'fab fa-youtube-square'], 'color' => '#DE0000', 'enabled' => false, 'icon' => 'fab fa-youtube'],
        ['title' => 'Discord', 'link' => '', 'icons' => ['fa-brands fa-discord'], 'color' => '#5d69f2', 'enabled' => false, 'icon' => 'fa-brands fa-discord'],
        ['title' => 'WeChat', 'link' => '', 'icons' => ['fa-brands fa-weixin'], 'color' => '#33c206', 'enabled' => false, 'icon' => 'fa-brands fa-weixin'],
        ['title' => 'Weibo', 'link' => '', 'icons' => ['fa-brands fa-weibo'], 'color' => '#e60e29', 'enabled' => false, 'icon' => 'fa-brands fa-weibo'],
        ['title' => 'Snapchat', 'link' => '', 'icons' => ['fa-brands fa-snapchat', 'fa-brands fa-square-snapchat'], 'color' => '#FFE500', 'enabled' => false, 'icon' => 'fa-brands fa-snapchat'],
    ];

    public static $easing = [
        'easeInSine' => '(x)=>{return 1-Math.cos((x*Math.PI)/2)}',
        'easeOutSine' => '(x)=>{return Math.sin((x*Math.PI)/2)}',
        'easeInOutSine' => '(x)=>{return-(Math.cos(Math.PI*x)-1)/2}',
        'easeInCubic' => '(x)=>{return x*x*x}',
        'easeOutCubic' => '(x)=>{return 1-Math.pow(1-x,3)}',
        'easeInOutCubic' => '(x)=>{return x<0.5?4*x*x*x:1-Math.pow(-2*x+2,3)/2}',
        'easeInQuint' => '(x)=>{return x*x*x*x*x}',
        'easeOutQuint' => '(x)=>{return 1-Math.pow(1-x,5)}',
        'easeInOutQuint' => '(x)=>{return x<0.5?16*x*x*x*x*x:1-Math.pow(-2*x+2,5)/2}',
        'easeInCirc' => '(x)=>{return 1-Math.sqrt(1-Math.pow(x,2))}',
        'easeOutCirc' => '(x)=>{return Math.sqrt(1-Math.pow(x-1,2))}',
        'easeInOutCirc' => '(x)=>{return x<0.5?(1-Math.sqrt(1-Math.pow(2*x,2)))/2:(Math.sqrt(1-Math.pow(-2*x+2,2))+1)/2}',
        'easeInElastic' => '(x)=>{const c4=(2*Math.PI)/3;return x===0?0:x===1?1:-Math.pow(2,10*x-10)*Math.sin((x*10-10.75)*c4)}',
        'easeOutElastic' => '(x)=>{const c4=(2*Math.PI)/3;return x===0?0:x===1?1:Math.pow(2,-10*x)*Math.sin((x*10-0.75)*c4)+1}',
        'easeInOutElastic' => '(x)=>{const c5=(2*Math.PI)/4.5;return x===0?0:x===1?1:x<0.5?-(Math.pow(2,20*x-10)*Math.sin((20*x-11.125)*c5))/2:(Math.pow(2,-20*x+10)*Math.sin((20*x-11.125)*c5))/2+1}',
        'easeInQuad' => '(x)=>{return x*x}',
        'easeOutQuad' => '(x)=>{return 1-(1-x)*(1-x)}',
        'easeInOutQuad' => '(x)=>{return x<0.5?2*x*x:1-Math.pow(-2*x+2,2)/2}',
        'easeInQuart' => '(x)=>{return x*x*x*x}',
        'easeOutQuart' => '(x)=>{return 1-Math.pow(1-x,4)}',
        'easeInOutQuart' => '(x)=>{return x<0.5?8*x*x*x*x:1-Math.pow(-2*x+2,4)/2}',
        'easeInExpo' => '(x)=>{return x===0?0:Math.pow(2,10*x-10)}',
        'easeOutExpo' => '(x)=>{return x===1?1:1-Math.pow(2,-10*x)}',
        'easeInOutExpo' => '(x)=>{return x===0?0:x===1?1:x<0.5?Math.pow(2,20*x-10)/2:(2-Math.pow(2,-20*x+10))/2}',
        'easeInBack' => '(x)=>{const c1=1.70158;const c3=c1+1;return c3*x*x*x-c1*x*x}',
        'easeOutBack' => '(x)=>{const c1=1.70158;const c3=c1+1;return 1+c3*Math.pow(x-1,3)+c1*Math.pow(x-1,2)}',
        'easeInOutBack' => '(x)=>{const c1=1.70158;const c2=c1*1.525;return x<0.5?(Math.pow(2*x,2)*((c2+1)*2*x-c2))/2:(Math.pow(2*x-2,2)*((c2+1)*(x*2-2)+c2)+2)/2}',
        'easeInBounce' => '(x)=>{const n1=7.5625;const d1=2.75;let t=1-x;if(t<1/d1){return 1-(n1*t*t)}else if(t<2/d1){return 1-(n1*(t-=1.5/d1)*t+0.75)}else if(t<2.5/d1){return 1-(n1*(t-=2.25/d1)*t+0.9375)}else{return 1-(n1*(t-=2.625/d1)*t+0.984375)}}',
        'easeOutBounce' => '(x)=>{const n1=7.5625;const d1=2.75;if(x<1/d1){return n1*x*x}else if(x<2/d1){return n1*(x-=1.5/d1)*x+0.75}else if(x<2.5/d1){return n1*(x-=2.25/d1)*x+0.9375}else{return n1*(x-=2.625/d1)*x+0.984375}}',
    ];

    public static $preloaders = [
        'rotating-plane' => [
            'name' => 'rotating-plane',
            'code' => '<div class="sk-rotating-plane"></div>',
        ],
        'fading-circle' => [
            'name' => 'fading-circle',
            'code' => '<div class="sk-fading-circle"><div class="sk-circle1 sk-circle"></div><div class="sk-circle2 sk-circle"></div><div class="sk-circle3 sk-circle"></div><div class="sk-circle4 sk-circle"></div><div class="sk-circle5 sk-circle"></div><div class="sk-circle6 sk-circle"></div><div class="sk-circle7 sk-circle"></div><div class="sk-circle8 sk-circle"></div><div class="sk-circle9 sk-circle"></div><div class="sk-circle10 sk-circle"></div><div class="sk-circle11 sk-circle"></div><div class="sk-circle12 sk-circle"></div></div>',
        ],
        'folding-cube' => [
            'name' => 'folding-cube',
            'code' => '<div class="sk-folding-cube"><div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div>',
        ],
        'double-bounce' => [
            'name' => 'double-bounce',
            'code' => '<div class="sk-double-bounce"><div class="sk-child sk-double-bounce1"></div><div class="sk-child sk-double-bounce2"></div></div>',
        ],
        'wave' => [
            'name' => 'wave',
            'code' => '<div class="sk-wave"><div class="sk-rect sk-rect1"></div><div class="sk-rect sk-rect2"></div><div class="sk-rect sk-rect3"></div><div class="sk-rect sk-rect4"></div><div class="sk-rect sk-rect5"></div></div>',
        ],
        'wandering-cubes' => [
            'name' => 'wandering-cubes',
            'code' => '<div class="sk-wandering-cubes"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div></div>',
        ],
        'pulse' => [
            'name' => 'pulse',
            'code' => '<div class="sk-spinner sk-spinner-pulse"></div>',
        ],
        'chasing-dots' => [
            'name' => 'chasing-dots',
            'code' => '<div class="sk-chasing-dots"><div class="sk-child sk-dot1"></div><div class="sk-child sk-dot2"></div></div>',
        ],
        'three-bounce' => [
            'name' => 'three-bounce',
            'code' => '<div class="sk-three-bounce"><div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>',
        ],
        'circle' => [
            'name' => 'circle',
            'code' => '<div class="sk-circle"><div class="sk-circle1 sk-child"></div><div class="sk-circle2 sk-child"></div><div class="sk-circle3 sk-child"></div><div class="sk-circle4 sk-child"></div><div class="sk-circle5 sk-child"></div><div class="sk-circle6 sk-child"></div><div class="sk-circle7 sk-child"></div><div class="sk-circle8 sk-child"></div><div class="sk-circle9 sk-child"></div><div class="sk-circle10 sk-child"></div><div class="sk-circle11 sk-child"></div><div class="sk-circle12 sk-child"></div></div>',
        ],
        'cube-grid' => [
            'name' => 'cube-grid',
            'code' => '<div class="sk-cube-grid"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>',
        ],
        'bouncing-loader' => [
            'name' => 'bouncing-loader',
            'code' => '<div class="bouncing-loader"><div></div><div></div><div></div></div>',
        ],
        'donut' => [
            'name' => 'donut',
            'code' => '<div class="donut"></div>',
        ],
        'triple-spinner' => [
            'name' => 'triple-spinner',
            'code' => '<div class="triple-spinner"></div>',
        ],
        'cm-spinner' => [
            'name' => 'cm-spinner',
            'code' => '<div class="cm-spinner"></div>',
        ],
        'hm-spinner' => [
            'name' => 'hm-spinner',
            'code' => '<div class="hm-spinner"></div>',
        ],
        'reverse-spinner' => [
            'name' => 'reverse-spinner',
            'code' => '<div class="reverse-spinner"></div>',
        ]
    ];

    public static $preloaders_font = [
        'spinner' => [
            'name'      => 'fas fa-spinner fa-spin',
            'code'      => 'fas fa-spinner',
            'animate'   => 'spin'
        ],
        'circle-notch' => [
            'name' => 'fas fa-circle-notch fa-spin',
            'code' => 'fas fa-circle-notch',
            'animate'   => 'spin'
        ],
        'sync' => [
            'name' => 'fas fa-sync fa-spin',
            'code' => 'fas fa-sync',
            'animate'   => 'spin'
        ],
        'cog' => [
            'name' => 'fas fa-cog fa-spin',
            'code' => 'fas fa-cog',
            'animate'   => 'spin'
        ],
        'spinner fa-pulse' => [
            'name' => 'fas fa-spinner fa-pulse',
            'code' => 'fas fa-spinner',
            'animate'   => 'spin-pulse'
        ],
        'stroopwafel' => [
            'name' => 'fas fa-stroopwafel fa-spin',
            'code' => 'fas fa-stroopwafel',
            'animate'   => 'spin'
        ],
        'sun' => [
            'name' => 'fas fa-sun fa-spin',
            'code' => 'fas fa-sun',
            'animate'   => 'spin'
        ],
        'asterisk' => [
            'name' => 'fas fa-asterisk fa-spin',
            'code' => 'fas fa-asterisk',
            'animate'   => 'spin'
        ],
        'atom' => [
            'name' => 'fas fa-atom fa-spin',
            'code' => 'fas fa-atom',
            'animate'   => 'spin'
        ],
        'certificate' => [
            'name' => 'fas fa-certificate fa-spin',
            'code' => 'fas fa-certificate',
            'animate'   => 'spin'
        ],
        'compact-disc' => [
            'name' => 'fas fa-compact-disc fa-spin',
            'code' => 'fas fa-compact-disc',
            'animate'   => 'spin'
        ],
        'compass' => [
            'name' => 'fas fa-compass fa-spin',
            'code' => 'fas fa-compass',
            'animate'   => 'spin'
        ],
        'crosshairs' => [
            'name' => 'fas fa-crosshairs fa-spin',
            'code' => 'fas fa-crosshairs',
            'animate'   => 'spin'
        ],
        'dharmachakra' => [
            'name' => 'fas fa-dharmachakra fa-spin',
            'code' => 'fas fa-dharmachakra',
            'animate'   => 'spin'
        ],
        'bahai' => [
            'name' => 'fas fa-bahai fa-spin',
            'code' => 'fas fa-bahai',
            'animate'   => 'spin'
        ],
        'life-ring' => [
            'name' => 'fas fa-life-ring fa-spin',
            'code' => 'fas fa-life-ring',
            'animate'   => 'spin'
        ],
        'yin-yang' => [
            'name' => 'fas fa-yin-yang fa-spin',
            'code' => 'fas fa-yin-yang',
            'animate'   => 'spin'
        ],
        'sync-alt' => [
            'name' => 'fas fa-sync-alt fa-spin',
            'code' => 'fas fa-sync-alt',
            'animate'   => 'spin'
        ],

    ];
    public static $layout_grids = [
        [12],
        [10, 2],
        [9, 3],
        [8, 4],
        [7, 5],
        [6, 6],
        [4, 4, 4],
        [3, 6, 3],
        [2, 6, 4],
        [3, 3, 3, 3],
        [2, 2, 2, 2, 2, 2]
    ];

    public function get_constant($variable)
    {
        if (isset($this->{"$" . $variable})) {
            return $this->{"$" . $variable};
        } else {
            return null;
        }
    }
}
