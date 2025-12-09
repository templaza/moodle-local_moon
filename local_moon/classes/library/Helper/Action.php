<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\library\Helper;
use local_moon\library\Element\Layout;
use local_moon\library\Framework;

defined('MOODLE_INTERNAL') || die;

class Action extends Client {
    public string $filearea = '';
    public int $itemid = 0;
    public function __construct($filearea, $itemid)
    {
        parent::__construct();
        $this->filearea = $filearea;
        $this->itemid = $itemid;
    }

    // Media Actions
    public function list() : array {
        $folder = optional_param('folder', '/', PARAM_PATH);
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
        return $list;
    }

    public function upload() : array {
        if (empty($_FILES['file']['tmp_name'])) {
            return ['success' => false, 'message' => 'No file uploaded'];
        }
        $folder = optional_param('folder', '/', PARAM_PATH);
        if (!empty($folder) && $folder != '/') {
            $folder = '/'.$folder.'/';
        } else {
            $folder = '/';
        }
        $storedfile = Media::upload($_FILES['file'], $folder, $this->filearea, $this->itemid);

        if (!$storedfile) {
            return ['success' => false, 'message' => 'Failed to store file'];
        }

        // Lấy URL truy cập
        $url = Media::url($storedfile);

        return [
            'filename' => $storedfile->get_filename(),
            'url'      => $url,
            'size'     => display_size($storedfile->get_filesize()),
            'mimetype' => $storedfile->get_mimetype(),
        ];
    }

    public function folder(): array {
        $folder = required_param('name', PARAM_PATH);
        $dir = optional_param('dir', '', PARAM_PATH);
        $created = Media::create_folder($dir.'/'.$folder, $this->filearea, $this->itemid);
        return [
            'folder' => $folder,
            'message' => 'Folder created successfully',
            'created' => $created
        ];
    }

    public function delete(): array {
        $name = required_param('name', PARAM_PATH);
        $folder = optional_param('dir', '', PARAM_PATH);
        $type = optional_param('type', '', PARAM_ALPHA);
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
        return $deleted;
    }

    public function rename() : array
    {
        $oldname = required_param('name', PARAM_FILE);
        $newname = required_param('new_name', PARAM_FILE);
        $folder  = optional_param('dir', '', PARAM_PATH);
        $type = optional_param('type', '', PARAM_ALPHA);
        if ($type == 'folder') {
            $result = Media::rename_folder($folder.'/'.$oldname, $folder.'/'.$newname, $this->filearea, $this->itemid);
        } else {
            $result = Media::rename_file($oldname, $newname, $this->filearea, $this->itemid, $folder);
        }
        return $result;
    }

    // Layout Actions
    public function getLayouts(): void
    {
        $return = Layout::getDatalayouts(Framework::getTheme()->getName(), $this->filearea);
        $this->response($return);
    }

    /**
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function saveLayout(): void
    {
        $filename = optional_param('name', '', PARAM_ALPHANUMEXT);
        $layoutType = optional_param('layout', '', PARAM_ALPHANUMEXT);

        $layout = [
            'title'     => optional_param('title', 'layout', PARAM_TEXT),
            'desc'      => optional_param('desc', '', PARAM_TEXT),
            'layout'    => $layoutType,
            'thumbnail' => optional_param('thumbnail_old', '', PARAM_TEXT),
            'data'      => json_decode(optional_param('data', '{"sections":[]}', PARAM_RAW), true),
        ];
        if (!empty($layoutType) && $layoutType !== 'custom') {
            $layout_name = $layoutType;
        } elseif (!$filename) {
            $base = clean_param($layout['title'] ?? '', PARAM_ALPHANUMEXT);
            if ($base === '') {
                $base = 'layout';
            }
            $layout_name = uniqid($base . '-');
        } else {
            $layout_name = $filename;
        }

        $thumbnail_file =  $_FILES['thumbnail'] ?? null;

        if (\is_array($thumbnail_file)) {
            // Make sure that file uploads are enabled in php.
            if (!(bool) \ini_get('file_uploads')) {
                throw new \Exception('File upload is not enabled in PHP', 400);
            }
            // Is the PHP tmp directory missing?
            if ($thumbnail_file['error'] && ($thumbnail_file['error'] == UPLOAD_ERR_NO_TMP_DIR)) {
                throw new \Exception('There was an error uploading this thumbnail to the server.', 400);
            }
            $pathinfo = pathinfo($thumbnail_file['name']);
            $uploadedFileExtension = $pathinfo['extension'];
            $uploadedFileExtension = strtolower($uploadedFileExtension);
            $validExts  =   ['jpg', 'jpeg', 'png', 'bmp'];
            if (!in_array($uploadedFileExtension, $validExts)) {
                throw new \Exception(Text::_('INVALID EXTENSION'));
            }

            $fileTemp       = $thumbnail_file['tmp_name'];
            $thumbnail      = file_get_contents($fileTemp);
            if ($layout['thumbnail'] != '' && Media::exists($layout['thumbnail'], '/', $this->filearea, $this->itemid)) {
                Media::delete($layout['thumbnail'], '/', $this->filearea, $this->itemid);
            }

            $storedfile = Media::create_from_string($thumbnail, $layout_name.'.'.$uploadedFileExtension, '/', $this->filearea, $this->itemid);
            $layout['thumbnail'] = Media::thumbnail($layout_name.'.'.$uploadedFileExtension, '/', $this->filearea, $this->itemid);
            if (!$storedfile) {
                throw new \Exception('Failed to store file');
            }
        }
        $layout['name'] = $layout_name;
        Media::create_from_string(\json_encode($layout), $layout_name . '.json', '/', $this->filearea, $this->itemid);
        $this->response($layout);
    }

    public function getLayout() : void {
        $filename       = optional_param('name', '', PARAM_ALPHANUMEXT);
        $layout         = Layout::getDataLayout($filename, $this->filearea);
        if (!is_string($layout['data'])) {
            $layout['data'] = \json_encode($layout['data']);
        }
        $this->response($layout);
    }

    public function deleteLayouts() : void {
        $layouts        = optional_param_array('layouts', null, PARAM_ALPHANUMEXT);
        $this->response(Layout::deleteDatalayouts($layouts, $this->filearea));
    }

    // Font actions
    public function getFonts() : void
    {
        $this->format = 'html';
        $this->response(Font::getAllFonts());
    }

    public function getIcons() : void
    {
        $this->format = 'html';
        $source       = optional_param('source', '', PARAM_ALPHANUMEXT);
        $return = ['success' => true];
        if ($source === 'astroid') {
            $return['results'] = Font::fontAstroidIcons();
        } else {
            $return['results'] = Font::fontAwesomeIcons(true);
        }

        $this->response(json_encode($return), true);
    }

    public function clearCache() : void
    {
        theme_reset_all_caches();
        $this->response(['message' => Text::_('theme_cache_cleared')]);
    }
}