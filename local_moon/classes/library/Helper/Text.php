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

namespace local_moon\library\Helper;
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
class Text
{
    public static function _($identifier) : string
    {
        if (empty($identifier)) {
            return '';
        }
        if (get_string_manager()->string_exists($identifier, 'theme_' . Framework::getTheme()->name)) {
            return get_string($identifier, 'theme_' . Framework::getTheme()->name);
        } elseif (get_string_manager()->string_exists($identifier, 'local_moon')) {
            return get_string($identifier, 'local_moon');
        } else {
            return $identifier;
        }
    }
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
    public static function shortify($text)
    {
        $text = self::slugify($text);
        $return = [];
        $text = explode('-', $text);
        foreach ($text as $t) {
            $key        =   substr($t, 0, 1);
            if (count($return) == 0 && preg_match('/[^a-z]/', $key)) {
                $key    =   'as';
            }
            $return[]   =   $key;
        }
        return implode('', $return);
    }

    public static function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0) {
                return true;
            }
        }

        return false;
    }
}