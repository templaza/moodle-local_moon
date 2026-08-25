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
use local_moon\library\Element\Layout;
use local_moon\library\Framework;

defined('MOODLE_INTERNAL') || die;

class Action extends Client {
    public string $filearea = '';
    public int $itemid = 0;
    public array $params = [];
    public function __construct($params)
    {
        parent::__construct();
        $this->params = $params;
        $this->filearea = $params['filearea'] ?? 'media';
        $this->itemid = $params['itemid'] ?? 0;
    }

    // Media Actions
    public function list() : array {
        $folder = $this->params['folder'];
        if (!empty($folder) && $folder != '/') {
            $folder = '/'.$folder.'/';
        } else {
            $folder = '/';
        }
        $files = Media::list($this->filearea, $this->itemid, $folder);
        $images = array();
        $folders = array();
        $docs = array();
        $videos = array();

        foreach ($files as $file) {
            $tmp = new \stdClass();
            if ($file['isdir']) {
                $tmp->name = $file['filename'];
                $tmp->path = $file['url'];
                $tmp->path_relative = trim($file['filepath'], '/');
                $folders[] = $tmp;
            } else {
                $tmp->name = $file['filename'];
                $tmp->title = $file['filename'];
                $tmp->path = $file['url'];
                $tmp->path_relative = $file['url'];
                $tmp->size = $file['size'];
                switch ($file['mimetype']) {
                    case 'application/pdf':
                    case 'application/msword':
                    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    case 'application/vnd.ms-excel':
                    case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    case 'application/vnd.ms-powerpoint':
                    case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                    case 'text/plain':
                    case 'application/zip':
                    case 'application/x-7z-compressed':
                    case 'application/octet-stream':
                        $docs[] = $tmp;
                        break;
                    default:
                        $primary = strtok($file['mimetype'], '/');
                        if ($primary === 'image') {
                            $images[] = $tmp;
                        } elseif ($primary === 'video') {
                            $videos[] = $tmp;
                        } else {
                            $docs[] = $tmp;
                        }
                }
            }
        }

        $list = array('folders' => $folders, 'docs' => $docs, 'images' => $images, 'videos' => $videos);
        $list['current_folder'] = rtrim(Framework::getTheme()->name . $folder, '/');
        return $this->responseData(['data' => \json_encode($list)]);
    }

    public function upload() : array {
        $fs = \get_file_storage();
        if (!$fs->file_exists($this->params['fileInfo']['contextid'], $this->params['fileInfo']['component'], $this->params['fileInfo']['filearea'], $this->params['fileInfo']['itemid'], $this->params['fileInfo']['filepath'], $this->params['fileInfo']['filename'])) {
            throw new \moodle_exception(Text::_('error_file_not_found'));
        }

        $file = $fs->get_file($this->params['fileInfo']['contextid'], $this->params['fileInfo']['component'], $this->params['fileInfo']['filearea'], $this->params['fileInfo']['itemid'], $this->params['fileInfo']['filepath'], $this->params['fileInfo']['filename']);

        $folder = $this->params['folder'];
        if (!empty($folder) && $folder != '/') {
            $folder = '/'.$folder.'/';
        } else {
            $folder = '/';
        }

        global $USER;
        $context = \context_system::instance();
        $storedfile = $fs->create_file_from_storedfile([
            'contextid' => $context->id,
            'component' => 'theme_' . $this->params['theme'],
            'filearea'  => $this->filearea,
            'itemid'    => $this->itemid,
            'filepath'  => $folder,
            'userid'    => $USER->id ?? 0,
        ], $file);

        // Delete old file.
        $file->delete();

        if (!$storedfile) {
            return $this->responseData(['status' => 'error', 'message' => 'Failed to store file']);
        }

        // Get access URL.
        $url = Media::url($storedfile);

        return $this->responseData(['data' => \json_encode([
            'filename' => $storedfile->get_filename(),
            'url'      => $url,
            'size'     => display_size($storedfile->get_filesize()),
            'mimetype' => $storedfile->get_mimetype(),
        ])]);
    }

    public function folder(): array {
        $folder = $this->params['name'];
        $dir = $this->params['folder'];
        $created = Media::create_folder($dir.'/'.$folder, $this->filearea, $this->itemid);
        if ($created) {
            return $this->responseData(['data' => 'Folder '.$folder.' created successfully']);
        } else {
            return $this->responseData(['data' => 'Folder '.$folder.' created failed']);
        }
    }

    public function delete(): array {
        $name = $this->params['name'];
        $folder = $this->params['folder'];
        $type = $this->params['type'];
        if (!empty($folder) && $folder != '/') {
            $folder = '/'.$folder.'/';
        } else {
            $folder = '/';
        }
        if ($type == 'folder') {
            $deleted = Media::delete_folder($folder.'/'.$name, $this->filearea, $this->itemid);
        } else {
            $deleted = Media::delete($name, $folder, $this->filearea, $this->itemid);
        }
        return $this->responseData(['data' => \json_encode($deleted)]);
    }

    public function rename() : array
    {
        $oldname = $this->params['name'];
        $newname = $this->params['new_name'];
        $folder  = $this->params['folder'];
        $type = $this->params['type'];
        if ($type == 'folder') {
            $result = Media::rename_folder($folder.'/'.$oldname, $folder.'/'.$newname, $this->filearea, $this->itemid);
        } else {
            $result = Media::rename_file($oldname, $newname, $this->filearea, $this->itemid, $folder);
        }
        return $this->responseData(['data' => \json_encode($result)]);
    }

    // Layout Actions
    public function getLayouts(): array
    {
        $return = Layout::getDatalayouts(Framework::getTheme()->name, $this->filearea);
        return $this->responseData(['data' => \json_encode($return)]);
    }

    /**
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function saveLayout(): array
    {
        $filename = $this->params['name'];
        $layoutType = $this->params['layout'];
        $layoutData = $this->params['data'];
        if (!Utilities::isJsonString($layoutData)) {
            throw new \moodle_exception('error_data_json_invalid', 'local_moon');
        }

        $layout = [
            'title'     => $this->params['title'],
            'desc'      => $this->params['desc'],
            'layout'    => $layoutType,
            'thumbnail' => $this->params['thumbnail_old'],
            'data'      => json_decode($layoutData, true),
        ];

        // Validate layout data
        if (empty($layout['data']['devices']) || empty($layout['data']['sections'])) {
            throw new \moodle_exception('error_layout_is_empty', 'local_moon');
        }

        if (!empty($layoutType) && $layoutType !== 'custom') {
            $layout_name = $layoutType;
        } elseif (!$filename) {
            $base = clean_param($layout['title'] ?? '', PARAM_ALPHANUMEXT);
            if ($base === '') {
                $base = 'layout';
            }
            $layout_name = strtolower(uniqid($base));
        } else {
            $layout_name = $filename;
        }

//        $thumbnail_file =  $_FILES['thumbnail'] ?? null;
//
//        if (\is_array($thumbnail_file)) {
//            // Make sure that file uploads are enabled in php.
//            if (!(bool) \ini_get('file_uploads')) {
//                throw new \Exception('File upload is not enabled in PHP', 400);
//            }
//            // Is the PHP tmp directory missing?
//            if ($thumbnail_file['error'] && ($thumbnail_file['error'] == UPLOAD_ERR_NO_TMP_DIR)) {
//                throw new \Exception('There was an error uploading this thumbnail to the server.', 400);
//            }
//            $pathinfo = pathinfo($thumbnail_file['name']);
//            $uploadedFileExtension = $pathinfo['extension'];
//            $uploadedFileExtension = strtolower($uploadedFileExtension);
//            $validExts  =   ['jpg', 'jpeg', 'png', 'bmp'];
//            if (!in_array($uploadedFileExtension, $validExts)) {
//                throw new \Exception(Text::_('INVALID EXTENSION'));
//            }
//
//            $fileTemp       = $thumbnail_file['tmp_name'];
//            $thumbnail      = file_get_contents($fileTemp);
//            if ($layout['thumbnail'] != '' && Media::exists($layout['thumbnail'], '/', $this->filearea, $this->itemid)) {
//                Media::delete($layout['thumbnail'], '/', $this->filearea, $this->itemid);
//            }
//
//            $storedfile = Media::create_from_string($thumbnail, $layout_name.'.'.$uploadedFileExtension, '/', $this->filearea, $this->itemid);
//            $layout['thumbnail'] = Media::thumbnail($layout_name.'.'.$uploadedFileExtension, '/', $this->filearea, $this->itemid);
//            if (!$storedfile) {
//                throw new \Exception('Failed to store file');
//            }
//        }
        $layout['name'] = $layout_name;
        $bakFile = null;
        $fileIsExist = Media::exists($layout_name . '.json', '/', $this->filearea, $this->itemid);
        if ($fileIsExist) {
            $oldlayout = Media::data($layout_name . '.json', '/', $this->filearea, $this->itemid);
            if ($oldlayout) {
                $bakFile = Media::create_from_string($oldlayout, $layout_name . '.bak.json', '/draft/', $this->filearea, $this->itemid);
            }
        }
        $json = \json_encode($layout);
        $shouldCreate = !$fileIsExist || !empty($bakFile);
        if ($shouldCreate && Media::create_from_string($json, $layout_name . '.json', '/', $this->filearea, $this->itemid)) {
            if ($fileIsExist && !empty($bakFile)) {
                $bakFile->delete();
            }
        }
        return $this->responseData(['data' => \json_encode($layout)]);
    }

    public function getLayout() : array {
        $layout         = Layout::getDataLayout($this->params['name'], $this->filearea);
        if (!is_string($layout['data'])) {
            $layout['data'] = \json_encode($layout['data']);
        }
        return $this->responseData(['data' => \json_encode($layout)]);
    }

    public function deleteLayouts() : array {
        $layouts        = $this->params['layouts'];
        if (Layout::deleteDatalayouts($layouts, $this->filearea)) {
            return $this->responseData(['message' => 'Layouts deleted successfully']);
        } else {
            return $this->responseData(['message' => 'Failed to delete layouts']);
        }
    }

    // Font actions
    public function getFonts() : array
    {
        return $this->responseData(['data' => Font::getAllFonts()]);
    }

    public function getIcons() : array
    {
        $this->format = 'html';
        $source       = $this->params['source'];
        $return = ['success' => true];
        if ($source === 'astroid') {
            $return['results'] = Font::fontAstroidIcons();
        } else {
            $return['results'] = Font::fontAwesomeIcons(true);
        }

        return $this->responseData(['data' => \json_encode($return)]);
    }

    public function clearCache() : array
    {
        theme_reset_all_caches();
        Media::empty_folder('/', 'css');
        return $this->responseData(['message' => Text::_('theme_cache_cleared')]);
    }

    public function getPresets() : array
    {
        $theme = Framework::getTheme();
        $presets = $theme->getPresets();
        $data       =   array();
        for ($i = 0; $i<count($presets); $i++) {
            $preset     =   $presets[$i];
            $item       =   array();
            $item['title']  =   $preset['title'];
            $item['desc']   =   $preset['desc'];
            $arrName        =   explode(' ',$preset['title']);
            $avaName        =   '';
            for ($j=0; $j<count($arrName) && $j<3; $j++){
                if ($word = trim($arrName[$j])) {
                    $avaName.=$word[0];
                }
            }
            $item['keyword']    = $avaName;
            $item['thumbnail']  = $preset['thumbnail'];
            $item['demo']       = !empty($preset['demo']) ? $preset['demo'] : '';
            $item['name']       = $preset['name'];
            $data[]             = $item;
        }
        return $data;
    }

    public function loadPreset() : array|string
    {
        global $CFG;
        try {
            $theme = Framework::getTheme();
            $presets_path = $CFG -> dirroot . "/theme/{$theme->name}/moon/presets/";
            $file           = $this->params['name'];
            $file_name      = $presets_path.$file.'.json';
            if (file_exists($file_name)) {
                $json           = file_get_contents($presets_path.$file.'.json');
                if (!$json) {
                    throw new \Exception(Text::_('error_loading_presets').': '.$presets_path.$file.'.json');
                }
                $data = \json_decode($json, true);
                if (!isset($data['preset']) || empty($data['preset'])) {
                    throw new \Exception(Text::_('error_data_json_invalid'));
                }
                return $data['preset'];
            } else {
                throw new \Exception(Text::_('error_file_not_found').': '.$presets_path.$file.'.json');
            }
        } catch (\Exception $e) {
            $this->errorResponse($e);
        }
    }

    public function importPreset() : string
    {
        global $CFG;
        try {
            $theme = Framework::getTheme();
            $presets_path = $CFG -> dirroot . "/theme/{$theme->name}/moon/presets/";
            $preset = [
                'title' => $this->params['title'],
                'desc' => $this->params['desc'],
                'thumbnail' => '', 'demo' => '',
                'preset' => ''
            ];
            $preset_name = uniqid('preset-');

            $fs = \get_file_storage();
            if (!$fs->file_exists($this->params['fileInfo']['contextid'], $this->params['fileInfo']['component'], $this->params['fileInfo']['filearea'], $this->params['fileInfo']['itemid'], $this->params['fileInfo']['filepath'], $this->params['fileInfo']['filename'])) {
                throw new \Exception(Text::_('error_file_not_found'));
            }

            $file = $fs->get_file($this->params['fileInfo']['contextid'], $this->params['fileInfo']['component'], $this->params['fileInfo']['filearea'], $this->params['fileInfo']['itemid'], $this->params['fileInfo']['filepath'], $this->params['fileInfo']['filename']);

            $pathinfo = pathinfo($this->params['fileInfo']['filename']);
            $uploadedFileExtension = $pathinfo['extension'];
            $uploadedFileExtension = strtolower($uploadedFileExtension);
            if ($uploadedFileExtension != 'json') {
                throw new \Exception(Text::_('error_invalid_extension'));
            }

            $json           = $file->get_content();
            $config         = json_decode($json, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (!isset($config['preset'])) {
                    $preset['preset'] = $json;
                } else {
                    $preset['preset'] = $config['preset'];
                }
            } else {
                throw new \Exception(Text::_('error_data_json_invalid'));
            }

            $uploadPath = $presets_path . $preset_name . '.json';
            if (!is_dir($presets_path)) {
                if (!mkdir($presets_path, 0755, true) && !is_dir($presets_path)) {
                    throw new \Exception('Failed to create presets directory: ' . $presets_path);
                }
            }
            if (file_put_contents($uploadPath, \json_encode($preset)) === false) {
                throw new \Exception('Failed to write preset file: ' . $uploadPath);
            }
            $file->delete();
            return $preset_name;
        } catch (\Exception $e) {
            $this->errorResponse($e);
        }
    }

    public function deletePreset() : bool
    {
        global $CFG;
        try {
            // Check for request forgeries.
            $theme = Framework::getTheme();
            $presets_path = $CFG -> dirroot . "/theme/{$theme->name}/moon/presets/";
            $file           = $this->params['name'];
            $file_name      = $presets_path.$file.'.json';
            if (file_exists($file_name)) {
                if (!@unlink($file_name)) {
                    throw new \Exception('Failed to delete preset file: ' . $file_name);
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->errorResponse($e);
        }
    }
}