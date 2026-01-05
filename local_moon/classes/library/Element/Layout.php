<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
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
            $template = Framework::getTheme()->getName();
        }
        $layouts = array_merge(
            self::readLayoutsFromPath($CFG->dirroot . "/theme/{$template}/params/{$type}/", $template, $type),
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
        $template   =   Framework::getTheme()->getName();
        if (!$filename) {
            if ($type == 'article_layouts') {
                if (Media::exists('default.json', '/', $type, 0)) {
                    $json = Media::data('default.json', '/', $type, 0);
                    return \json_decode($json, true);
                } elseif (file_exists(Path::clean($CFG->dirroot . "/theme/{$template}/params/{$type}/default.json"))) {
                    $layout_path = Path::clean($CFG->dirroot . "/theme/{$template}/params/{$type}/default.json");
                } else {
                    $layout_path = Path::clean($CFG->dirroot . '/local/moon/assets/json/article_layouts/default.json');
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
                return \json_decode($json, true);
            } elseif (Media::exists($filename . '.bak.json', '/draft/', $type, 0)) {
                $json = Media::data($filename . '.bak.json', '/draft/', $type, 0);
                return \json_decode($json, true);
            } elseif (file_exists(Path::clean($CFG->dirroot . "/theme/{$template}/params/{$type}/" . $filename . '.json'))){
                $layout_path = Path::clean($CFG->dirroot . "/theme/{$template}/params/{$type}/" . $filename . '.json');
            } elseif (Media::exists($default . '.json', '/', $type, 0)) {
                $json = Media::data($default . '.json', '/', $type, 0);
                return \json_decode($json, true);
            } else {
                return [];
            }
        }

        if (!file_exists($layout_path)) {
            return [];
        }
        $json = file_get_contents($layout_path);
        return \json_decode($json, true);
    }

    public static function deleteDatalayouts($layouts = [], $type = '')
    {
        global $CFG;
        if (empty($layouts)) {
            return false;
        }
        $template = Framework::getTheme()->getName();

        $layouts_path = Path::clean($CFG->dirroot . "/theme/{$template}/params/{$type}/");
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
