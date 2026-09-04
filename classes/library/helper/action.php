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
use local_moon\library\element\layout;
use local_moon\library\framework;

defined('MOODLE_INTERNAL') || die;

class action extends client {
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
        $files = media::list($this->filearea, $this->itemid, $folder);
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
        $list['current_folder'] = rtrim(framework::get_theme()->name . $folder, '/');
        return $this->response_data(['data' => \json_encode($list)]);
    }

    public function upload() : array {
        global $USER;
        $fs = \get_file_storage();
        $usercontext = \context_user::instance($USER->id, MUST_EXIST);
        if (!$fs->file_exists($usercontext->id, 'user', 'draft', $this->params['fileInfo']['itemid'], '/', $this->params['fileInfo']['filename'])) {
            throw new \moodle_exception(text::_('error_draft_file_not_found'));
        }

        $file = $fs->get_file($usercontext->id, 'user', 'draft', $this->params['fileInfo']['itemid'], '/', $this->params['fileInfo']['filename']);

        $folder = $this->params['folder'];
        if (!empty($folder) && $folder != '/') {
            $folder = '/'.$folder.'/';
        } else {
            $folder = '/';
        }

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
            return $this->response_data(['status' => 'error', 'message' => text::_('error_can_not_save_file')]);
        }

        // Get access URL.
        $url = media::url($storedfile);

        return $this->response_data(['data' => \json_encode([
            'filename' => $storedfile->get_filename(),
            'url'      => $url,
            'size'     => display_size($storedfile->get_filesize()),
            'mimetype' => $storedfile->get_mimetype(),
        ])]);
    }

    public function folder(): array {
        $folder = $this->params['name'];
        $dir = $this->params['folder'];
        $created = media::create_folder($dir.'/'.$folder, $this->filearea, $this->itemid);
        if ($created) {
            return $this->response_data(['data' => 'Folder '.$folder.' created successfully']);
        } else {
            return $this->response_data(['data' => 'Folder '.$folder.' created failed']);
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
            $deleted = media::delete_folder($folder.'/'.$name, $this->filearea, $this->itemid);
        } else {
            $deleted = media::delete($name, $folder, $this->filearea, $this->itemid);
        }
        return $this->response_data(['data' => \json_encode($deleted)]);
    }

    public function rename() : array
    {
        $oldname = $this->params['name'];
        $newname = $this->params['new_name'];
        $folder  = $this->params['folder'];
        $type = $this->params['type'];
        if ($type == 'folder') {
            $result = media::rename_folder($folder.'/'.$oldname, $folder.'/'.$newname, $this->filearea, $this->itemid);
        } else {
            $result = media::rename_file($oldname, $newname, $this->filearea, $this->itemid, $folder);
        }
        return $this->response_data(['data' => \json_encode($result)]);
    }

    // Layout Actions
    public function get_layouts(): array
    {
        $return = layout::get_datalayouts(framework::get_theme()->name, $this->filearea);
        return $this->response_data(['data' => \json_encode($return)]);
    }

    /**
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function save_layout(): array
    {
        $filename = $this->params['name'];
        $layout_type = $this->params['layout'];
        $layout_data = $this->params['data'];
        if (!utilities::is_json_string($layout_data)) {
            throw new \moodle_exception('error_data_json_invalid', 'local_moon');
        }

        $layout = [
            'title'     => $this->params['title'],
            'desc'      => $this->params['desc'],
            'layout'    => $layout_type,
            'thumbnail' => $this->params['thumbnail_old'],
            'data'      => json_decode($layout_data, true),
        ];

        // Validate layout data
        if (empty($layout['data']['devices']) || empty($layout['data']['sections'])) {
            throw new \moodle_exception('error_layout_is_empty', 'local_moon');
        }

        if (!empty($layout_type) && $layout_type !== 'custom') {
            $layout_name = $layout_type;
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
        $bak_file = null;
        $file_is_exist = media::exists($layout_name . '.json', '/', $this->filearea, $this->itemid);
        if ($file_is_exist) {
            $oldlayout = media::data($layout_name . '.json', '/', $this->filearea, $this->itemid);
            if ($oldlayout) {
                $bak_file = media::create_from_string($oldlayout, $layout_name . '.bak.json', '/draft/', $this->filearea, $this->itemid);
            }
        }
        $json = \json_encode($layout);
        $should_create = !$file_is_exist || !empty($bak_file);
        if ($should_create && media::create_from_string($json, $layout_name . '.json', '/', $this->filearea, $this->itemid)) {
            if ($file_is_exist && !empty($bak_file)) {
                $bak_file->delete();
            }
        }
        return $this->response_data(['data' => \json_encode($layout)]);
    }

    public function get_layout() : array {
        $layout         = layout::get_data_layout($this->params['name'], $this->filearea);
        if (!is_string($layout['data'])) {
            $layout['data'] = \json_encode($layout['data']);
        }
        return $this->response_data(['data' => \json_encode($layout)]);
    }

    public function delete_layouts() : array {
        $layouts        = $this->params['layouts'];
        if (layout::delete_datalayouts($layouts, $this->filearea)) {
            return $this->response_data(['message' => 'Layouts deleted successfully']);
        } else {
            return $this->response_data(['message' => 'Failed to delete layouts']);
        }
    }

    // Font actions
    public function get_fonts() : array
    {
        return $this->response_data(['data' => font::get_all_fonts()]);
    }

    public function get_icons() : array
    {
        $this->format = 'html';
        $source       = $this->params['source'];
        $return = ['success' => true];
        if ($source === 'astroid') {
            $return['results'] = font::font_astroid_icons();
        } else {
            $return['results'] = font::font_awesome_icons(true);
        }

        return $this->response_data(['data' => \json_encode($return)]);
    }

    public function clear_cache() : array
    {
        theme_reset_all_caches();
        media::empty_folder('/', 'css');
        return $this->response_data(['message' => text::_('theme_cache_cleared')]);
    }

    public function get_presets() : array
    {
        $theme = framework::get_theme();
        $presets = $theme->get_presets();
        $data       =   array();
        for ($i = 0; $i<count($presets); $i++) {
            $preset     =   $presets[$i];
            $item       =   array();
            $item['title']  =   $preset['title'];
            $item['desc']   =   $preset['desc'];
            $arr_name        =   explode(' ',$preset['title']);
            $ava_name        =   '';
            for ($j=0; $j<count($arr_name) && $j<3; $j++){
                if ($word = trim($arr_name[$j])) {
                    $ava_name.=$word[0];
                }
            }
            $item['keyword']    = $ava_name;
            $item['thumbnail']  = $preset['thumbnail'];
            $item['demo']       = !empty($preset['demo']) ? $preset['demo'] : '';
            $item['name']       = $preset['name'];
            $item['source']     = $preset['source'];
            $data[]             = $item;
        }
        return $data;
    }

    public function load_preset() : array|string
    {
        global $CFG;
        try {
            $file           = $this->params['name'];
            if (media::exists($file.'.json', '/', 'presets', 0)) {
                $preset = media::data($file.'.json', '/', 'presets', 0);
                if (!$preset) {
                    throw new \Exception(text::_('error_loading_presets').': '.$file.'.json');
                }
                $data = \json_decode($preset, true);
                if (!isset($data['preset']) || empty($data['preset'])) {
                    throw new \Exception(text::_('error_data_json_invalid'));
                }
                return $data['preset'];
            }

            $theme = framework::get_theme();
            $presets_path = $CFG -> dirroot . "/theme/{$theme->name}/moon/presets/";
            $file_name      = $presets_path.$file.'.json';
            if (file_exists($file_name)) {
                $json           = file_get_contents($presets_path.$file.'.json');
                if (!$json) {
                    throw new \Exception(text::_('error_loading_presets').': '.$presets_path.$file.'.json');
                }
                $data = \json_decode($json, true);
                if (!isset($data['preset']) || empty($data['preset'])) {
                    throw new \Exception(text::_('error_data_json_invalid'));
                }
                return $data['preset'];
            } else {
                throw new \Exception(text::_('error_file_not_found').': '.$presets_path.$file.'.json');
            }
        } catch (\Exception $e) {
            $this->error_response($e);
        }
    }

    public function import_preset() : string
    {
        global $USER;
        try {
            $usercontext = \context_user::instance($USER->id, MUST_EXIST);
            $theme = framework::get_theme();
            $preset = [
                'title' => $this->params['title'],
                'desc' => $this->params['desc'],
                'thumbnail' => '', 'demo' => '',
                'preset' => ''
            ];
            $preset_name = uniqid('preset-');

            $fs = \get_file_storage();
            if (!$fs->file_exists($usercontext->id, 'user', 'draft', $this->params['itemid'], '/', $this->params['filename'])) {
                throw new \Exception(text::_('error_file_not_found'));
            }

            $file = $fs->get_file($usercontext->id, 'user', 'draft', $this->params['itemid'], '/', $this->params['filename']);
            if ($file) {
                $pathinfo = pathinfo($file->get_filename());
                $uploaded_file_extension = $pathinfo['extension'];
                $uploaded_file_extension = strtolower($uploaded_file_extension);
                if ($uploaded_file_extension != 'json') {
                    throw new \Exception(text::_('error_invalid_extension'));
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
                    throw new \Exception(text::_('error_data_json_invalid'));
                }

                media::create_from_string(\json_encode($preset), $preset_name . '.json', '/', 'presets', 0, 'theme_'.$theme->name);
                $file->delete();
            }
            return $preset_name;
        } catch (\Exception $e) {
            $this->error_response($e);
        }
    }

    public function delete_preset() : bool
    {
        global $CFG;
        try {
            // Check for request forgeries.
            $theme = framework::get_theme();
            $file           = $this->params['name'];

            if (media::exists($file.'.json', '/', 'presets', 0)) {
                media::delete($file.'.json', '/', 'presets', 0);
            }

            $presets_path = $CFG -> dirroot . "/theme/{$theme->name}/moon/presets/";

            $file_name      = $presets_path.$file.'.json';
            if (file_exists($file_name)) {
                if (!@unlink($file_name)) {
                    throw new \Exception('Failed to delete preset file: ' . $file_name);
                }
            }
            return true;
        } catch (\Exception $e) {
            $this->error_response($e);
        }
    }
}