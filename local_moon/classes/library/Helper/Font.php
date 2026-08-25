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

/**
 * @package   Astroid Framework
 * @author    Astroid Framework Team https://astroidframe.work
 * @copyright Copyright (C) 2024 AstroidFrame.work.
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

namespace local_moon\library\Helper;

use local_moon\library\Framework;

defined('MOODLE_INTERNAL') or die;

class Font
{
    public static array $system_fonts = [
        "" => 'Default',
        "Arial, Helvetica, sans-serif" => 'Arial, Helvetica',
        "Arial Black, Gadget, sans-serif" => 'Arial Black, Gadget',
        "Bookman Old Style, serif" => 'Bookman Old Style',
        "Comic Sans MS, cursive" => 'Comic Sans MS',
        "Courier, monospace" => 'Courier',
        "Garamond, serif" => 'Garamond',
        "Georgia, serif" => 'Georgia',
        "Impact, Charcoal, sans-serif" => 'Impact, Charcoal',
        "Lucida Console, Monaco, monospace" => 'Lucida Console, Monaco',
        "Lucida Sans Unicode, sans-serif" => 'Lucida Sans Unicode',
        "MS Sans Serif, Geneva, sans-serif" => 'MS Sans Serif, Geneva',
        "MS Serif, New York, sans-serif" => 'MS Serif, New York',
        "Palatino Linotype, Book Antiqua, Palatino, serif" => 'Palatino Linotype, Book Antiqua, Palatino',
        "Tahoma, Geneva, sans-serif" => 'Tahoma, Geneva',
        "Times New Roman, Times, serif" => 'Times New Roman, Times',
        "Trebuchet MS, Helvetica, sans-serif" => 'Trebuchet MS, Helvetica',
        "Verdana, Geneva, sans-serif" => 'Verdana, Geneva'
    ];

    public static array $get_default_font_value = [
        'font_face' => '',
        'alt_font_face' => '',
        'font_size' => ['global' => ''],
        'font_size_unit' => ['global' => 'em'],
        'font_color' => '{"light":"","dark":""}',
        'letter_spacing' => ['global' => ''],
        'letter_spacing_unit' => ['global' => 'em'],
        'line_height' => ['global' => ''],
        'line_height_unit' => ['global' => 'em'],
        'font_weight' => '',
        'font_style' => [],
        'text_transform' => 'none',
    ];
    public static function get_system_fonts(): array
    {
        $system_fonts = array();
        foreach (self::$system_fonts as $s_font_value => $s_font_title) {
            $system_fonts[]  =   [
                'value'  =>  $s_font_value,
                'text'   =>  $s_font_title
            ];
        }
        return $system_fonts;
    }

    public static function text_transform (): array
    {
        return [
            'none' => Text::_('inherit'),
            'uppercase' => Text::_('uppercase'),
            'lowercase' => Text::_('lowercase'),
            'capitalize' => Text::_('capitalize')
        ];
    }

    public static function font_properties (): array
    {
        return [
            'font_family' => Text::_('font_family'),
            'font_family_alt' => Text::_('font_family_alt'),
            'font_size' => Text::_('font_size'),
            'font_weight' => Text::_('font_weight'),
            'letter_spacing' => Text::_('letter_spacing'),
            'line_height' => Text::_('line_height'),
            'text_transform' => Text::_('text_transform'),
            'font_style' => Text::_('font_style'),
            'font_color' => Text::_('font_color'),
            'preview' => Text::_('preview'),
            'inherit' => Text::_('inherit'),
        ];
    }

    public static function googleFonts(): array
    {
        $fonts = Utilities::getJSONData('webfonts');
        $options = [];
        if (!isset($fonts['items'])) {
            return $options;
        }

        foreach ($fonts['items'] as $font) {
            $variants = [];
            if (count($font['variants']) > 1) {
                foreach ($font['variants'] as $v) {
                    if ($v == 'regular') {
                        $variants[] = '400';
                    } else if ($v == 'italic') {
                        $variants[] = '400i';
                    } else {
                        $variants[] = str_replace('talic', '', $v);
                    }
                }
            }
            $value = str_replace(' ', '+', $font['family']);
            if (!empty($variants)) {
                $value .= ':' . implode(',', $variants);
            }
            $options[$font['category']][$value] = $font['family'];
        }
        return $options;
    }

    public static function getAllFonts(): false|string
    {
        $googleFonts = self::googleFonts();
        $rt_fonts   =   array(
            'system' => array([
                'value' => '__default',
                'text'  => Text::_('default')
            ]),
            'google' => array([
                'value' => '__default',
                'text'  => Text::_('default')
            ]),
            'local'  => array([
                'value' => '__default',
                'text'  => Text::_('default')
            ])
        );

        foreach (self::$system_fonts as $name => $system_font) {
            $rt_fonts['system'][]     =   [
                'value' => $name,
                'text'  => $system_font
            ];
        }

        $uploadedFonts = self::getUploadedFonts(Framework::getTheme()->name);

        if (!empty($uploadedFonts)) {
            foreach ($uploadedFonts as $uploaded_font) {
                $rt_fonts['local'][]     =   [
                    'value' => $uploaded_font['id'],
                    'text'  => $uploaded_font['name']
                ];
            }
        }

        foreach ($googleFonts as $group => $fonts) {
            foreach ($fonts as $fontValue => $font) {
                $rt_fonts['google'][]     =   [
                    'value' => $fontValue,
                    'text'  => $font
                ];
            }
        }
        return \json_encode($rt_fonts);
    }

    public static function getUploadedFonts($template): array
    {
        if (empty($template)) {
            return [];
        }

        global $CFG;
        $template_fonts_path        =   $CFG->dirroot . "/theme/{$template}/fonts";
        if (!file_exists($template_fonts_path)) {
            return [];
        }
        return self::getLocalFonts($template_fonts_path);
    }

    public static function getLocalFonts($template_fonts_path): array
    {
        $fonts = [];
        $font_extensions = ['otf', 'ttf', 'woff'];
        foreach (scandir($template_fonts_path) as $font_path) {
            if (is_file($template_fonts_path . '/' . $font_path)) {
                $pathinfo = pathinfo($template_fonts_path . '/' . $font_path);
                if (in_array($pathinfo['extension'], $font_extensions)) {
                    $font = \FontLib\Font::load($template_fonts_path . '/' . $font_path);
                    $font->parse();
                    $fontname = $font->getFontFullName();
                    $fontid = 'library-font-' . Text::slugify($fontname);
                    if (!isset($fonts[$fontid])) {
                        $fonts[$fontid] = [];
                        $fonts[$fontid]['id'] = $fontid;
                        $fonts[$fontid]['name'] = $fontname;
                        $fonts[$fontid]['files'] = [];
                    }
                    $fonts[$fontid]['files'][] = $font_path;
                }
            }
        }
        return $fonts;
    }

    public static function fontAstroidIcons() {
        $icons = self::_getASIcons();
        $array = [];
        $array[] = ['value' => '', 'name' => 'None'];
        foreach ($icons as $icon) {
            $array[] = ['value' => $icon['value'], 'name' => '<i class="' . $icon['value'] . '"></i> ' . $icon['name']];
        }
        $icons = $array;
        return $icons;
    }

    public static function _getASIcons()
    {
        global $CFG;
        if (Media::exists('asicon.json' ,'/', 'astroid_icon')) {
            return json_decode(Media::data('asicon.json' ,'/', 'astroid_icon'), true);
        }
        $json = file_get_contents($CFG -> dirroot . '/local/moon/assets/linearicons/Linearicons.json');
        $json = \json_decode($json, true);
        $icons = [];
        foreach ($json['selection'] as $icon) {
            $icons[] = ['value' => 'as-icon as-icon-' . $icon['name'], 'name' => $icon['name'], 'type' => 'as-icon'];
        }
        Media::create_from_string(json_encode($icons), 'asicon.json' ,'/', 'astroid_icon');
        return $icons;
    }

    public static function fontAwesomeIcons($html = false)
    {
        $icons = self::_getFAIcons();
        if ($html) {
            $array = [];
            $array[] = ['value' => '', 'name' => 'None'];
            foreach ($icons as $icon) {
                $array[] = ['value' => $icon['value'], 'name' => '<i class="' . $icon['value'] . '"></i> ' . $icon['name']];
            }
            $icons = $array;
        }
        return $icons;
    }

    public static function _getFAIcons()
    {
        global $CFG;
        if (Media::exists('fontawesome.json' ,'/', 'fontawesome_icon')) {
            return json_decode(Media::data('fontawesome.json' ,'/', 'fontawesome_icon'), true);
        }

        $json = file_get_contents($CFG -> dirroot . '/local/moon/assets/fontawesome/metadata/icons.json');
        $json = \json_decode($json, true);

        $icons = [];
        foreach ($json as $icon => $info) {
            foreach ($info['styles'] as $style) {
                $icons[] = ['value' => 'fa' . substr($style, 0, 1) . ' fa-' . $icon, 'name' => $info['label'], 'type' => $style];
            }
        }
        Media::create_from_string(json_encode($icons), 'fontawesome.json' ,'/', 'fontawesome_icon');
        return $icons;
    }

    public static function getFontType($value)
    {
        $type = 'google';
        if (Text::startsWith($value, 'library-font-')) {
            $type = 'local';
        }
        if (isset(self::$system_fonts[$value])) {
            $type = 'system';
        }
        return $type;
    }

    public static function getFontFamily($value)
    {
        $type = self::getFontType($value);
        switch ($type) {
            case 'google':
                $value = '"'.self::loadGoogleFont($value).'"';
                break;
            case 'local':
                $value = '"'.self::loadLocalFont($value).'"';
                break;
            case 'system':
                return $value;
                break;
        }
        return $value;
    }

    public static function loadGoogleFont($value): array|string
    {
        $value = str_replace(',', ';', $value);
        $value = str_replace(':', ':ital,wght@', $value);

        $wght = substr($value, strpos($value, "@") + 1);

        $_value = explode(';', $wght);
        foreach ($_value as &$_v) {
            $_v = explode('i', $_v);
            if (count($_v) == 2) {
                $_v = '1,' . $_v[0];
            } else {
                $_v = '0,' . $_v[0];
            }
        }
        sort($_value);

        if (strpos($value, "@") > 0) {
            $value = str_replace($wght, implode(';', $_value), $value);
        }

        if ($value) {
            $document = Framework::getDocument();
            $document->addStyleSheet('https://fonts.gstatic.com', ['rel' => 'preconnect']);
            $document->addStyleSheet('https://fonts.googleapis.com/css2?family=' . $value . '&display=swap');
        } else {
            return '';
        }

        @list($font, $variants) = explode(":", $value);

        return str_replace('+', ' ', $font);
    }

    public static function loadLocalFont($value)
    {
        global $CFG;
        $template = Framework::getTheme();
        $document = Framework::getDocument();
        $uploaded_fonts = self::getUploadedFonts($template->name);
        $template_media_fonts_path  = $CFG -> dirroot . "/theme/{$template->name}/fonts";
        if (file_exists($template_media_fonts_path)) {
            $font_path      =       $CFG->wwwroot . "/theme/{$template->name}/fonts/";
        }
        if (isset($uploaded_fonts[$value])) {
            $files = $uploaded_fonts[$value]['files'];
            $value = $uploaded_fonts[$value]['name'];
            foreach ($files as $file) {
                $document->addStyleDeclaration('@font-face { font-family: "' . $value . '"; src: url("' . $font_path . $file . '");}');
            }
        }
        return $value;
    }

    public static function loadFontAwesome(): void
    {
        $params = Helper::getPluginParams();
        $source = $params->get('astroid_load_fontawesome', "cdn");
        $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
        switch ($source) {
            case 'cdn':
                $wa->registerAndUseStyle('fontawesome', "https://use.fontawesome.com/releases/v" . Helper\Constants::$fontawesome_version . "/css/all.css");
                break;
            case 'local':
                $wa->registerAndUseStyle('fontawesome', 'media/astroid/assets/vendor/fontawesome/css/all.min.css');
                break;
            default:
                if (Framework::isAdmin()) {
                    $wa->registerAndUseStyle('fontawesome', 'media/astroid/assets/vendor/fontawesome/css/all.min.css');
                }
                break;
        }
    }
}
