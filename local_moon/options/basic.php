<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Constants;
Framework::getTheme()->addFields(
    'basic',
    [
        'label' => 'basic',
        'icon' => 'fas fa-cog',
        'order' => 1,
        'fields' => [
            // Group
            'page_settings' => ["type" => "group", "label" => "page_settings"],
            'preloader_options' => ["type" => "group", "label" => "preloader_options"],
            'webmanifest' => ["type" => "group", "label" => "favicon_site_webmanifest"],
            'backtotop_options' => ["type" => "group", "label" => "backtotop_options"],
            'colormode' => ["type" => "group", "label" => "color_mode"],

            // Page Settings
            'theme_layout' => [
                "group" => "page_settings",
                "type" => "radio",
                "label" => "theme_layout",
                "description" => "",
                "default" => "wide",
                "options" => [
                    "wide" => "wide",
                    "boxed" => "boxed"
                ]
            ],
            'body_padding' => [
                "group" => "page_settings",
                "type" => "spacing",
                "label" => "body_padding",
                "description" => "",
            ],

            'layout_background_image' => [
                "group" => "page_settings",
                "type" => "media",
                "attributes" => [
                    "media" => "images",
                ],
                "label" => "background_image",
                "default" => "",
                "conditions" => "[theme_layout]=='boxed'"
            ],
            'layout_background_repeat' => [
                "group" => "page_settings",
                "type" => "list",
                "label" => "background_repeat",
                "default" => "",
                "options" => [
                    "" => "inherit",
                    "no-repeat" => "no_repeat",
                    "repeat-x" => "repeat_x",
                    "repeat-y" => "repeat_y",
                    "repeat" => "repeat_all"
                ],
                "conditions" => "[theme_layout]=='boxed'"
            ],
            'layout_background_size' => [
                "group" => "page_settings",
                "type" => "list",
                "label" => "background_size",
                "default" => "",
                "options" => [
                    "" => "inherit",
                    "cover" => "cover",
                    "contain" => "contain",
                ],
                "conditions" => "[theme_layout]=='boxed'"
            ],
            'layout_background_position' => [
                "group" => "page_settings",
                "type" => "list",
                "label" => "background_position",
                "default" => "",
                "options" => [
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
                "conditions" => "[theme_layout]=='boxed'"
            ],
            'layout_background_attachment' => [
                "group" => "page_settings",
                "type" => "list",
                "label" => "background_attachment",
                "default" => "",
                "options" => [
                    "" => "inherit",
                    "fixed" => "fixed",
                    "scroll" => "scroll"
                ],
                "conditions" => "[theme_layout]=='boxed'"
            ],
            'theme_width' => [
                "group" => "page_settings",
                "type" => "text",
                "label" => "theme_width",
                "description" => "theme_width_desc",
                "default" => "",
                "attributes" => [
                    "hint" => "1400px",
                ],
            ],
            'container_background_setting' => [
                "group" => "page_settings",
                "type" => "radio",
                "label" => "container_background_setting",
                "description" => "container_background_setting_desc",
                "default" => "",
                "options" => [
                    "" => "inherit",
                    "image" => "background_image",
                    "video" => "background_video",
                ],
            ],
            'container_img_background_color' => [
                "group" => "page_settings",
                "type" => "color",
                "label" => "background_color",
                "default" => "",
                "conditions" => "[container_background_setting] =='image'"
            ],
            'container_background_image' => [
                "group" => "page_settings",
                "type" => "media",
                "attributes" => [
                    "media" => "images",
                ],
                "label" => "background_image",
                "default" => "",
                "conditions" => "[container_background_setting] =='image' OR [container_background_setting] =='video'"
            ],
            'container_background_repeat' => [
                "group" => "page_settings",
                "type" => "list",
                "label" => "background_repeat",
                "default" => "",
                "options" => [
                    "" => "inherit",
                    "no-repeat" => "no_repeat",
                    "repeat-x" => "repeat_x",
                    "repeat-y" => "repeat_y",
                    "repeat" => "repeat_all"
                ],
                "conditions" => "[container_background_setting] =='image'"
            ],
            'container_background_size' => [
                "group" => "page_settings",
                "type" => "list",
                "label" => "background_size",
                "default" => "",
                "options" => [
                    "" => "inherit",
                    "cover" => "cover",
                    "contain" => "contain",
                ],
                "conditions" => "[container_background_setting] =='image'"
            ],
            'container_background_attachment' => [
                "group" => "page_settings",
                "type" => "list",
                "label" => "background_attachment",
                "default" => "",
                "options" => [
                    "" => "inherit",
                    "fixed" => "fixed",
                    "scroll" => "scroll"
                ],
                "conditions" => "[container_background_setting] =='image'"
            ],
            'container_background_position' => [
                "group" => "page_settings",
                "type" => "list",
                "label" => "background_position",
                "default" => "",
                "options" => [
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
                "conditions" => "[container_background_setting] =='image'"
            ],
            'container_background_video' => [
                "group" => "page_settings",
                "type" => "media",
                "attributes" => [
                    "media" => "videos",
                ],
                "label" => "background_video",
                "default" => "",
                "conditions" => "[container_background_setting]=='video'"
            ],
            'container_background_image_overlay' => [
                "group" => "page_settings",
                "type" => "radio",
                "label" => "overlay_color",
                "default" => "",
                "options" => [
                    "" => "none",
                    "color" => "color",
                    "gradient" => "gradient",
                    "pattern" => "pattern"
                ],
                "conditions" => "[container_background_setting]=='image' OR [container_background_setting]=='video'"
            ],
            'container_background_image_overlay_color' => [
                "group" => "page_settings",
                "type" => "color",
                "label" => "background_color",
                "default" => "",
                "conditions" => "([container_background_setting]=='image' OR [container_background_setting]=='video') AND ([container_background_image_overlay]=='color' OR [container_background_image_overlay]=='pattern')"
            ],
            'container_background_image_overlay_gradient' => [
                "group" => "page_settings",
                "type" => "gradient",
                "label" => "gradient_color",
                "default" => "",
                "conditions" => "([container_background_setting]=='image' OR [container_background_setting]=='video') AND [container_background_image_overlay]=='gradient'"
            ],
            'container_background_image_overlay_pattern' => [
                "group" => "page_settings",
                "type" => "media",
                "attributes" => [
                    "media" => "images",
                ],
                "label" => "pattern",
                "default" => "",
                "conditions" => "([container_background_setting]=='image' OR [container_background_setting]=='video') AND [container_background_image_overlay]=='pattern'"
            ],

            // Preloader Settings
            'preloader' => [
                "group" => "preloader_options",
                "type" => "radio",
                "label" => "enable_preloader",
                "description" => "enable_preloader_desc",
                "default" => 1,
                "attributes" => [
                    "role" => "switch"
                ]
            ],
            'preloader_setting' => [
                "group" => "preloader_options",
                "type" => "radio",
                "label" => "type",
                "default" => "animation",
                "options" => [
                    "animation" => "animation",
                    "image" => "image",
                    "fontawesome" => "fontawesome",
                ],
                "conditions" => "[preloader]=='1'"
            ],
            'preloader_image' => [
                "group" => "preloader_options",
                "type" => "media",
                "attributes" => [
                    "media" => "images",
                ],
                "label" => "SELECT_IMAGE",
                "default" => "",
                "conditions" => "[preloader]=='1' AND [preloader_setting] =='image'"
            ],
            'preloader_animation' => [
                "group" => "preloader_options",
                "type" => "preloaders",
                "attributes" => [
                    "preloader" => Constants::$preloaders,
                    "style" => 'animation',
                ],
                "label" => "preloader_animation",
                "description" => "preloader_animation_desc",
                "default" => "circle",
                "conditions" => "[preloader]=='1' AND [preloader_setting] =='animation'"
            ],
            'preloader_fontawesome' => [
                "group" => "preloader_options",
                "type" => "preloaders",
                "attributes" => [
                    "preloader" => Constants::$preloadersFont,
                    "style" => 'fontawesome',
                ],
                "label" => "preloader_animation",
                "description" => "preloader_animation_desc",
                "default" => "fas fa-sun fa-spin",
                "conditions" => "[preloader]=='1' AND [preloader_setting] =='fontawesome'"
            ],
            'preloader_color' => [
                "group" => "preloader_options",
                "type" => "color",
                "label" => "preloader_color",
                "description" => "preloader_color_desc",
                "default" => "",
                "conditions" => "[preloader]=='1' AND ([preloader_setting] =='animation' OR [preloader_setting] =='fontawesome')"
            ],
            'preloader_bgcolor' => [
                "group" => "preloader_options",
                "type" => "color",
                "label" => "preloader_background_color",
                "description" => "preloader_background_color_desc",
                "default" => "",
                "conditions" => "[preloader]=='1'"
            ],
            'preloader_size' => [
                "group" => "preloader_options",
                "type" => "range",
                "label" => "preloader_size",
                "description" => "preloader_size_desc",
                "default" => 40,
                "attributes" => [
                    "min" => 20,
                    "max" => 500,
                    "step" => 1,
                    "postfix" => "px"
                ],
                "conditions" => "[preloader]=='1' AND ([preloader_setting] =='animations' OR [preloader_setting] =='fontawesome')"
            ],

            // Webmanifest
            'apple_touch_icon' => [
                "group" => "webmanifest",
                "type" => "media",
                "attributes" => [
                    "media" => "images",
                ],
                "label" => "favicon_apple_touch_icon",
                "description" => "favicon_apple_touch_icon_desc",
                "default" => ""
            ],
            'site_webmanifest' => [
                "group" => "webmanifest",
                "type" => "text",
                "attributes" => [
                    "hint" => "manifest.json",
                ],
                "label" => "favicon_site_webmanifest",
                "description" => "favicon_site_webmanifest_desc",
                "default" => ""
            ],

            // Back to top
            'backtotop' => [
                "group" => "backtotop_options",
                "type" => "radio",
                "label" => "enable_backtotop",
                "description" => "enable_backtotop_desc",
                "default" => 1,
                "attributes" => [
                    "role" => "switch"
                ]
            ],
            'backtotop_icon' => [
                "group" => "backtotop_options",
                "type" => "icon",
                "label" => "backtotop_icon",
                "description" => "backtotop_icon_desc",
                "default" => "fas fa-arrow-up",
                "conditions" => "[backtotop]=='1'"
            ],
            'backtotop_icon_size' => [
                "group" => "backtotop_options",
                "type" => "range",
                "label" => "backtotop_icon_size",
                "description" => "backtotop_icon_size_desc",
                "default" => 20,
                "attributes" => [
                    "min" => 20,
                    "max" => 200,
                    "step" => 1,
                    "postfix" => "px"
                ],
                "conditions" => "[backtotop]=='1'"
            ],
            'backtotop_icon_padding' => [
                "group" => "backtotop_options",
                "type" => "range",
                "label" => "backtotop_icon_padding",
                "description" => "backtotop_icon_padding_desc",
                "default" => 10,
                "attributes" => [
                    "min" => 5,
                    "max" => 100,
                    "step" => 1,
                    "postfix" => "px"
                ],
                "conditions" => "[backtotop]=='1'"
            ],
            'backtotop_border_style' => [
                "group" => "backtotop_options",
                "type" => "border",
                "label" => "backtotop_border_style",
                "description" => "backtotop_border_style_desc",
                "conditions" => "[backtotop]=='1'"
            ],
            'backtotop_icon_color' => [
                "group" => "backtotop_options",
                "type" => "color",
                "label" => "backtotop_icon_color",
                "description" => "backtotop_icon_color_desc",
                "default" => "",
                "conditions" => "[backtotop]=='1'"
            ],
            'backtotop_icon_bgcolor' => [
                "group" => "backtotop_options",
                "type" => "color",
                "label" => "backtotop_icon_background_color",
                "description" => "backtotop_icon_background_color_desc",
                "default" => "",
                "conditions" => "[backtotop]=='1'"
            ],
            'backtotop_icon_style' => [
                "group" => "backtotop_options",
                "type" => "list",
                "label" => "backtotop_icon_style",
                "description" => "backtotop_icon_style_desc",
                "default" => "rounded",
                "options" => [
                    "circle" => "circle",
                    "rounded" => "rounded",
                    "square" => "square",
                ],
                "conditions" => "[backtotop]=='1'"
            ],
            'backtotop_on_mobile' => [
                "group" => "backtotop_options",
                "type" => "radio",
                "label" => "backtotop_on_mobile",
                "description" => "backtotop_on_mobile_desc",
                "default" => 1,
                "attributes" => [
                    "role" => "switch"
                ],
                "conditions" => "[backtotop]=='1'"
            ],

            // Colormode
            'astroid_color_mode_enable' => [
                "group" => "colormode",
                "type" => "radio",
                "label" => "color_mode",
                "description" => "color_mode_desc",
                "default" => 1,
                "options" => [
                    "0" => "color_mode_light",
                    "1" => "color_mode_light_dark",
                    "2" => "color_mode_dark",
                ],
            ],
            'enable_color_mode_transform' => [
                "group" => "colormode",
                "type" => "radio",
                "label" => "enable_color_mode_transform",
                "description" => "enable_color_mode_transform_desc",
                "default" => 0,
                "attributes" => [
                    "role" => "switch"
                ],
                "conditions" => "[astroid_color_mode_enable]=='1'"
            ],
            'astroid_color_mode_default' => [
                "group" => "colormode",
                "type" => "radio",
                "label" => "color_mode_default",
                "description" => "color_mode_default_desc",
                "default" => 'auto',
                "options" => [
                    "auto" => "color_mode_auto",
                    "light" => "color_mode_light",
                    "dark" => "color_mode_dark",
                ],
                "conditions" => "[astroid_color_mode_enable]=='1' AND [enable_color_mode_transform]==0"
            ],
            'colormode_transform_type' => [
                "group" => "colormode",
                "type" => "list",
                "label" => "transform_type",
                "description" => "transform_type_desc",
                "default" => "light_dark",
                "options" => [
                    "light_dark" => "light_dark",
                    "dark_light" => "dark_light",
                ],
                "conditions" => "[astroid_color_mode_enable]=='1' AND [enable_color_mode_transform]==1"
            ],
            'astroid_colormode_transform_offset' => [
                "group" => "colormode",
                "type" => "range",
                "label" => "transform_offset",
                "description" => "transform_offset_desc",
                "default" => 50,
                "attributes" => [
                    "min" => 1,
                    "max" => 100,
                    "step" => 1,
                    "postfix" => "px"
                ],
                "conditions" => "[backtotop]=='1'"
            ],
        ]
    ]
);