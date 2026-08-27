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

namespace local_moon\library\element;

use local_moon\library\framework;
use local_moon\library\helper\media;
use local_moon\library\helper\text;
use local_moon\library\helper\path;

defined('MOODLE_INTERNAL') || die;

class layout
{
    public static function render($role = ''): string
    {
        $template = framework::get_theme();
        $layout = $template->get_layout();
        if (!$layout) {
            return '';
        }
        $data = $layout['data'];
        $devices = isset($data['devices']) && $data['devices'] ? $data['devices'] : [
            [
                'code'=> 'lg',
                'icon'=> 'fa-solid fa-computer',
                'title'=> 'title'
            ]
        ];
        $content = '';
        foreach ($data['sections'] as $section) {
            $section = new section($section, $devices, [], $role);
            $content .= $section->render();
        }
        return $content;
    }

    public static function render_sublayout($source, $type = 'layouts', $options = array(), $role = ''): string
    {
        $sublayout  = self::get_data_layout($source, $type);
        if (!isset($sublayout['data']) || !$sublayout['data']) {
            return '';
        }
        if (is_string($sublayout['data'])) {
            $layout     = \json_decode($sublayout['data'], true);
        } else {
            $layout     = $sublayout['data'];
        }
        $devices    = isset($layout['devices']) && $layout['devices'] ? $layout['devices'] : [
            [
                'code'=> 'lg',
                'icon'=> 'fa-solid fa-computer',
                'title'=> 'title'
            ]
        ];
        $options['layout_type'] = $type;
        $options['source'] = $source;
        $content = '';
        foreach ($layout['sections'] as $section) {
            $section = new section($section, $devices, $options, $role);
            $content .= $section->render();
        }
        return $content;
    }

    public static function get_datalayouts($template = '', $type = ''): array
    {
        global $CFG;
        if (!$template) {
            $template = framework::get_theme()->name;
        }
        $layouts = array_merge(
            self::read_layouts_from_path($CFG->dirroot . "/theme/{$template}/moon/{$type}/", $template, $type),
            self::read_layouts_from_data($type, 0)
        );
        return self::merge_layouts($layouts);
    }

    public static function read_layouts_from_data($filearea = '', $itemid = 0): array
    {
        $files = media::list($filearea, $itemid, '/', 'json');
        return array_map(function ($file) use ($filearea, $itemid) {
            if (!empty($file['content'])) {
                $json = $file['content'];
                $data = \json_decode($json, true);
                return [
                    'title' => text::_($data['title'] ?? $file['filename']),
                    'desc' => text::_($data['desc'] ?? ''),
                    'layout' => $data['layout'] ?? 'custom',
                    'thumbnail' => !empty($data['thumbnail']) ? media::thumbnail($data['thumbnail'], '/', $filearea, $itemid) : '',
                    'name' => pathinfo($file['filename'] ?? '', PATHINFO_FILENAME)
                ];
            }
            return [];
        }, $files);
    }

    public static function read_layouts_from_path($path, $template, $type): array
    {
        if (!file_exists($path)) {
            return [];
        }
        $files = array_filter(glob($path . '*.json'), 'is_file');
        return array_map(function ($file) use ($template, $type) {
            global $CFG;
            $json = file_get_contents($file);
            $data = \json_decode($json, true);
            return [
                'title' => text::_($data['title'] ?? pathinfo($file, PATHINFO_FILENAME)),
                'desc' => text::_($data['desc'] ?? ''),
                'layout' => $data['layout'] ?? 'custom',
                'thumbnail' => !empty($data['thumbnail']) ? $CFG->wwwroot . "/theme/{$template}/assets/images/{$type}/" . $data['thumbnail'] : '',
                'name' => pathinfo($file, PATHINFO_FILENAME)
            ];
        }, $files);
    }

    private static function merge_layouts($layouts): array
    {
        $merged = [];
        foreach ($layouts as $layout) {
            $key = $layout['name'];
            $merged[$key] = $layout;
        }
        return array_values($merged);
    }

    public static function get_data_layout($filename = '', $type = '') : array
    {
        global $CFG;
        $template   =   framework::get_theme()->name;
        if (!$filename) {
            if ($type == 'article_layouts') {
                if (media::exists('default.json', '/', $type, 0)) {
                    $json = media::data('default.json', '/', $type, 0);
                } elseif (file_exists(path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/default.json"))) {
                    $layout_path = path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/default.json");
                    $json = file_get_contents($layout_path);
                } else {
                    $layout_path = path::clean($CFG->dirroot . '/local/moon/assets/json/'.$type.'/default.json');
                    $json = file_get_contents($layout_path);
                }
            } else {
                return [];
            }
        } else {
            $default = framework::get_theme()->get_params()->get('layout', '');
            if (media::exists($filename . '.json', '/', $type, 0)) {
                $json = media::data($filename . '.json', '/', $type, 0);
                if (empty($json)) {
                    if (media::exists($filename . '.bak.json', '/draft/', $type, 0)) {
                        $json = media::data($filename . '.bak.json', '/draft/', $type, 0);
                    }
                }
            } elseif (media::exists($filename . '.bak.json', '/draft/', $type, 0)) {
                $json = media::data($filename . '.bak.json', '/draft/', $type, 0);
            } elseif (media::exists($default . '.json', '/', $type, 0)) {
                $json = media::data($default . '.json', '/', $type, 0);
            } elseif (file_exists(path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/" . $filename . '.json'))){
                $json = file_get_contents(path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/" . $filename . '.json'));
            } elseif (file_exists(path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/" . $default . '.json'))){
                $json = file_get_contents(path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/" . $default . '.json'));
            } else {
                return [];
            }
        }
        return \json_decode($json, true);
    }

    public static function delete_datalayouts($layouts = [], $type = '')
    {
        global $CFG;
        if (empty($layouts)) {
            return false;
        }
        $template = framework::get_theme()->name;

        $layouts_path = path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/");
        $images_path = path::clean($CFG->dirroot . "/theme/{$template}/images/{$type}/");

        $delete_file = function ($path, $layout) use ($images_path) {
            if (file_exists($path . $layout . '.json')) {
                $json = file_get_contents($path . $layout . '.json');
                $data = \json_decode($json, true);
                @unlink($path . $layout . '.json');
                if (!empty($data['thumbnail']) && file_exists($images_path . $data['thumbnail'])) {
                    @unlink($images_path . $data['thumbnail']);
                }
            }
        };
        array_map(function ($layout) use ($type, $layouts_path, $delete_file) {
            if (media::exists($layout . '.json', '/', $type, 0)) {
                $json = media::data($layout . '.json', '/', $type, 0);
                $data = \json_decode($json, true);
                media::delete($layout . '.json', '/', $type, 0);
                if (!empty($data['thumbnail']) && media::exists($data['thumbnail'], '/', $type, 0)) {
                    media::delete($data['thumbnail'], '/', $type, 0);
                }
            }
            $delete_file($layouts_path, $layout);
        }, $layouts);

        return true;
    }
}
