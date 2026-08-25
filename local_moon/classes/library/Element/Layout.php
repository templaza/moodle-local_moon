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

namespace local_moon\library\Element;

use local_moon\library\Framework;
use local_moon\library\Helper\Media;
use local_moon\library\Helper\Text;
use local_moon\library\Helper\Path;

defined('MOODLE_INTERNAL') || die;

class Layout
{
    public static function render($role = ''): string
    {
        $template = Framework::getTheme();
        $layout = $template->getLayout();
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
            $section = new Section($section, $devices, [], $role);
            $content .= $section->render();
        }
        return $content;
    }

    public static function renderSublayout($source, $type = 'layouts', $options = array(), $role = ''): string
    {
        $sublayout  = self::getDataLayout($source, $type);
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
            $section = new Section($section, $devices, $options, $role);
            $content .= $section->render();
        }
        return $content;
    }

    public static function getDatalayouts($template = '', $type = ''): array
    {
        global $CFG;
        if (!$template) {
            $template = Framework::getTheme()->name;
        }
        $layouts = array_merge(
            self::readLayoutsFromPath($CFG->dirroot . "/theme/{$template}/moon/{$type}/", $template, $type),
            self::readLayoutsFromData($type, 0)
        );
        return self::mergeLayouts($layouts);
    }

    public static function readLayoutsFromData($filearea = '', $itemid = 0): array
    {
        $files = Media::list($filearea, $itemid, '/', 'json');
        return array_map(function ($file) use ($filearea, $itemid) {
            if (!empty($file['content'])) {
                $json = $file['content'];
                $data = \json_decode($json, true);
                return [
                    'title' => Text::_($data['title'] ?? $file['filename']),
                    'desc' => Text::_($data['desc'] ?? ''),
                    'layout' => $data['layout'] ?? 'custom',
                    'thumbnail' => !empty($data['thumbnail']) ? Media::thumbnail($data['thumbnail'], '/', $filearea, $itemid) : '',
                    'name' => pathinfo($file['filename'] ?? '', PATHINFO_FILENAME)
                ];
            }
            return [];
        }, $files);
    }

    public static function readLayoutsFromPath($path, $template, $type): array
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
                'title' => Text::_($data['title'] ?? pathinfo($file, PATHINFO_FILENAME)),
                'desc' => Text::_($data['desc'] ?? ''),
                'layout' => $data['layout'] ?? 'custom',
                'thumbnail' => !empty($data['thumbnail']) ? $CFG->wwwroot . "/theme/{$template}/assets/images/{$type}/" . $data['thumbnail'] : '',
                'name' => pathinfo($file, PATHINFO_FILENAME)
            ];
        }, $files);
    }

    private static function mergeLayouts($layouts): array
    {
        $merged = [];
        foreach ($layouts as $layout) {
            $key = $layout['name'];
            $merged[$key] = $layout;
        }
        return array_values($merged);
    }

    public static function getDataLayout($filename = '', $type = '') : array
    {
        global $CFG;
        $template   =   Framework::getTheme()->name;
        if (!$filename) {
            if ($type == 'article_layouts') {
                if (Media::exists('default.json', '/', $type, 0)) {
                    $json = Media::data('default.json', '/', $type, 0);
                } elseif (file_exists(Path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/default.json"))) {
                    $layout_path = Path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/default.json");
                    $json = file_get_contents($layout_path);
                } else {
                    $layout_path = Path::clean($CFG->dirroot . '/local/moon/assets/json/'.$type.'/default.json');
                    $json = file_get_contents($layout_path);
                }
            } else {
                return [];
            }
        } else {
            $default = Framework::getTheme()->getParams()->get('layout', '');
            if (Media::exists($filename . '.json', '/', $type, 0)) {
                $json = Media::data($filename . '.json', '/', $type, 0);
                if (empty($json)) {
                    if (Media::exists($filename . '.bak.json', '/draft/', $type, 0)) {
                        $json = Media::data($filename . '.bak.json', '/draft/', $type, 0);
                    }
                }
            } elseif (Media::exists($filename . '.bak.json', '/draft/', $type, 0)) {
                $json = Media::data($filename . '.bak.json', '/draft/', $type, 0);
            } elseif (Media::exists($default . '.json', '/', $type, 0)) {
                $json = Media::data($default . '.json', '/', $type, 0);
            } elseif (file_exists(Path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/" . $filename . '.json'))){
                $json = file_get_contents(Path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/" . $filename . '.json'));
            } elseif (file_exists(Path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/" . $default . '.json'))){
                $json = file_get_contents(Path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/" . $default . '.json'));
            } else {
                return [];
            }
        }
        return \json_decode($json, true);
    }

    public static function deleteDatalayouts($layouts = [], $type = '')
    {
        global $CFG;
        if (empty($layouts)) {
            return false;
        }
        $template = Framework::getTheme()->name;

        $layouts_path = Path::clean($CFG->dirroot . "/theme/{$template}/moon/{$type}/");
        $images_path = Path::clean($CFG->dirroot . "/theme/{$template}/images/{$type}/");

        $deleteFile = function ($path, $layout) use ($images_path) {
            if (file_exists($path . $layout . '.json')) {
                $json = file_get_contents($path . $layout . '.json');
                $data = \json_decode($json, true);
                @unlink($path . $layout . '.json');
                if (!empty($data['thumbnail']) && file_exists($images_path . $data['thumbnail'])) {
                    @unlink($images_path . $data['thumbnail']);
                }
            }
        };
        array_map(function ($layout) use ($type, $layouts_path, $deleteFile) {
            if (Media::exists($layout . '.json', '/', $type, 0)) {
                $json = Media::data($layout . '.json', '/', $type, 0);
                $data = \json_decode($json, true);
                Media::delete($layout . '.json', '/', $type, 0);
                if (!empty($data['thumbnail']) && Media::exists($data['thumbnail'], '/', $type, 0)) {
                    Media::delete($data['thumbnail'], '/', $type, 0);
                }
            }
            $deleteFile($layouts_path, $layout);
        }, $layouts);

        return true;
    }

    public static function loadModuleLayout($id)
    {
        $layout_path = Path::clean(JPATH_SITE . '/media/mod_moon_layout/params/' . $id . '.json');
        if (empty($id) || !file_exists($layout_path)) {
            return ['sections' => []];
        }
        $json = file_get_contents($layout_path);
        $data = \json_decode($json, true);

        return $data;
    }
}
