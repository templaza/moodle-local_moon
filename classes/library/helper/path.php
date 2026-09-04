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

class path {
    /**
     * Function to strip additional / or \ in a path name.
     *
     * @param   string  $path  The path to clean.
     * @param   string  $ds    Directory separator (optional).
     *
     * @return  string  The cleaned path.
     *
     * @since   1.0
     * @throws  \UnexpectedValueException If $path is not a string.
     */
    public static function clean($path, $ds = \DIRECTORY_SEPARATOR): string
    {
        if ($path === '') {
            return '';
        }

        if (!\is_string($path)) {
            throw new \InvalidArgumentException('You must specify a non-empty path to clean');
        }

        $stream = explode('://', $path, 2);
        $scheme = '';
        $path   = $stream[0];

        if (\count($stream) >= 2) {
            $scheme = $stream[0] . '://';
            $path   = $stream[1];
        }

        $path = trim($path);

        // Remove double slashes and backslashes and convert all slashes and backslashes to DIRECTORY_SEPARATOR
        // If dealing with a UNC path don't forget to prepend the path with a backslash.
        if (($ds == '\\') && ($path[0] == '\\') && ($path[1] == '\\')) {
            $path = '\\' . preg_replace('#[/\\\\]+#', $ds, $path);
        } else {
            $path = preg_replace('#[/\\\\]+#', $ds, $path);
        }

        return $scheme . $path;
    }
}