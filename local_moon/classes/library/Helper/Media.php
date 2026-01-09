<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\library\Helper;
defined('MOODLE_INTERNAL') || die;

use core_reportbuilder\local\filters\select;
use local_moon\library\Framework;
use moodle_url;
use context_system;
use stored_file;

class Media {

    /**
     * Serve files from the component file areas.
     *
     * @param string $component
     * @param stdClass $course
     * @param stdClass $cm
     * @param context $context
     * @param string $filearea
     * @param array $args
     * @param bool $forcedownload
     * @param array $options
     * @return bool
     */
    public static function pluginFile($component, $context, $filearea, $args, $forcedownload, array $options = []) {
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return false;
        }

        $fs = get_file_storage();
        $itemid = array_shift($args);
        $filename = array_pop($args);
        $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

        $file = $fs->get_file($context->id, $component, $filearea, $itemid, $filepath, $filename);

        if (!$file || $file->is_directory()) {
            return false;
        }

        send_stored_file($file, 0, 0, $forcedownload, $options);
    }

    /**
     * Tạo một file mới trong filearea từ nội dung string.
     *
     * @param string $content   Nội dung của file
     * @param string $filename  Tên file (vd: "config.json")
     * @param string $filepath  Đường dẫn trong filearea (vd: "/settings/")
     * @param string $filearea  Vùng file (vd: "media")
     * @param int    $itemid    ID của item (mặc định 0)
     * @return stored_file|null
     */
    public static function create_from_string(
        string $content,
        string $filename,
        string $filepath = '/',
        string $filearea = 'media',
        int $itemid = 0
    ): ?stored_file {
        global $USER;

        $fs = get_file_storage();
        $context = \context_system::instance();
        $component = Framework::getTheme()->getName();

        // Chuẩn hóa tên và đường dẫn
        $filename = clean_param($filename, PARAM_FILE);
        $filepath = trim($filepath, '/');
        $filepath = empty($filepath) ? '/' : '/' . $filepath . '/';

        // Nếu file đã tồn tại thì xóa trước khi ghi mới
        if ($fs->file_exists($context->id, $component, $filearea, $itemid, $filepath, $filename)) {
            $oldfile = $fs->get_file($context->id, $component, $filearea, $itemid, $filepath, $filename);
            if ($oldfile) {
                $oldfile->delete();
            }
        }

        // Dữ liệu record file
        $filerecord = [
            'contextid' => $context->id,
            'component' => $component,
            'filearea'  => $filearea,
            'itemid'    => $itemid,
            'filepath'  => $filepath,
            'filename'  => $filename,
            'userid'    => $USER->id ?? 0,
        ];

        // Tạo file từ nội dung string
        $file = $fs->create_file_from_string($filerecord, $content);
        return $file ?: null;
    }

    /**
     * Upload một file media (ảnh/video) lên filearea của plugin.
     */
    public static function upload(array $file, string $filepath = '/', string $filearea = 'media', int $itemid = 0): ?stored_file {
        global $USER;

        $fs = get_file_storage();
        $context = context_system::instance();

        if (empty($file['tmp_name'])) {
            return null;
        }

        $record = [
            'contextid' => $context->id,
            'component' => Framework::getTheme()->getName(),
            'filearea'  => $filearea,
            'itemid'    => $itemid,
            'filepath'  => $filepath,
            'filename'  => clean_param($file['name'], PARAM_FILE),
            'userid'    => $USER->id ?? 0,
        ];

        return $fs->create_file_from_pathname($record, $file['tmp_name']);
    }

    public static function create_folder(string $folderpath, string $filearea = 'media', int $itemid = 0): bool {
        global $USER;
        $context = \context_system::instance();
        $fs = get_file_storage();

        // Chuẩn hóa đường dẫn
        $folderpath = trim($folderpath, '/');
        $filepath = empty($folderpath) ? '/' : '/' . $folderpath . '/';

        // Kiểm tra đã tồn tại chưa
        if ($fs->file_exists($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, '.')) {
            return false; // đã tồn tại
        }

        $fs->create_directory($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, $USER->id ?? 0);
        return true;
    }

    /**
     * Xóa folder và toàn bộ file con trong filearea của plugin.
     *
     * @param string $folderpath Đường dẫn folder (vd: gallery/sub)
     * @param string $filearea
     * @param int $itemid
     * @return array
     */
    public static function delete_folder(string $folderpath, string $filearea = 'media', int $itemid = 0): array {
        $context = \context_system::instance();
        $fs = get_file_storage();

        // Chuẩn hóa đường dẫn folder
        $folderpath = trim($folderpath, '/');
        $filepath = empty($folderpath) ? '/' : '/' . $folderpath . '/';

        // Lấy tất cả file trong folder này (bao gồm subfolder)
        $files = $fs->get_area_files($context->id, Framework::getTheme()->getName(), $filearea, $itemid, '', false);
        $deleted = 0;

        foreach ($files as $file) {
            if (strpos($file->get_filepath(), $filepath) === 0) {
                $file->delete();
                $deleted++;
            }
        }

        // Xóa luôn record của chính folder đó (filename='.')
        $folder = $fs->get_file($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, '.');
        if ($folder) {
            $folder->delete();
        }

        return [
            'success' => true,
            'deleted' => $deleted,
            'folder' => $filepath,
            'message' => "Folder '{$folderpath}' and {$deleted} files deleted."
        ];
    }

    /**
     * Xóa toàn bộ file trong folder nhưng không xóa chính folder đó.
     *
     * @param string $folderpath Đường dẫn folder (vd: gallery/sub)
     * @param string $filearea
     * @param int $itemid
     * @return array
     */
    public static function empty_folder(string $folderpath, string $filearea = 'media', int $itemid = 0): array {
        $context = \context_system::instance();
        $fs = get_file_storage();
        $component = Framework::getTheme()->getName();

        // Chuẩn hóa đường dẫn
        $folderpath = trim($folderpath, '/');
        $filepath = empty($folderpath) ? '/' : '/' . $folderpath . '/';

        // Kiểm tra folder tồn tại
        if (!$fs->file_exists($context->id, $component, $filearea, $itemid, $filepath, '.')) {
            return [
                'success' => false,
                'message' => "Folder '{$folderpath}' does not exist"
            ];
        }
        $files = $fs->get_area_files($context->id, $component, $filearea, $itemid, '', false);
        $deleted = 0;

        foreach ($files as $file) {
            // Bỏ qua record folder (.)
            if ($file->get_filename() === '.') {
                continue;
            }

            if (strpos($file->get_filepath(), $filepath) === 0) {
                $file->delete();
                $deleted++;
            }
        }

        return [
            'success' => true,
            'deleted' => $deleted,
            'folder' => $filepath,
            'message' => "Folder '{$folderpath}' emptied ({$deleted} files removed)"
        ];
    }

    /**
     * Lấy URL truy cập (pluginfile.php) cho 1 file.
     */
    public static function url(stored_file $file): string {
        return moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        )->out(false);
    }

    /**
     * Lấy danh sách file trong filearea (ví dụ: gallery, videos).
     */
    public static function list(string $filearea = 'media', int $itemid = 0, string $filepath = '/', string $filter = ''): array {
        $context = context_system::instance();
        $fs = get_file_storage();

        $files = $fs->get_area_files($context->id, Framework::getTheme()->getName(), $filearea, $itemid, 'timemodified DESC', true);
        $list = [];
        foreach ($files as $file) {
            if ($file->get_filepath() !== $filepath) {
                if ($file->is_directory() && $filter == '') {
                    $pos = strpos($file->get_filepath(), $filepath);
                    if ($pos === false) {
                        continue;
                    }
                    $start = $pos + strlen($filepath);
                    $dirPath = substr($file->get_filepath(), $start);
                    if (empty($dirPath)) {
                        continue;
                    }
                    $dirPath = rtrim($dirPath, '/');
                    if (strpos($dirPath, '/') !== false) {
                        continue;
                    }
                    $list[] = [
                        'filename' => $dirPath,
                        'isdir'    => true,
                        'url'      => self::url($file),
                        'filepath' => $file->get_filepath(),
                        'size'     => display_size($file->get_filesize()),
                        'time'     => userdate($file->get_timemodified())
                    ];
                } else {
                    continue;
                }
            }
            if (!$file->is_directory() && ($filter == '' || str_contains($file->get_mimetype(), $filter))) {
                $list[] = [
                    'filename' => $file->get_filename(),
                    'isdir'    => $file->is_directory(),
                    'url'      => self::url($file),
                    'filepath' => $file->get_filepath(),
                    'size'     => display_size($file->get_filesize()),
                    'time'     => userdate($file->get_timemodified()),
                    'mimetype' => $file->get_mimetype(),
                    'content'  => $file->get_content()
                ];
            }
        }
        return $list;
    }

    /**
     * Đổi tên file trong filearea.
     *
     * @param string $oldname  Tên file cũ (vd: banner.jpg)
     * @param string $newname  Tên file mới (vd: hero.jpg)
     * @param string $filearea Vùng file
     * @param int    $itemid   Item ID (mặc định 0)
     * @param string $folderpath Đường dẫn thư mục (vd: gallery/)
     * @return array
     */
    public static function rename_file(
        string $oldname,
        string $newname,
        string $filearea = 'media',
        int $itemid = 0,
        string $folderpath = ''
    ): array {
        $context = \context_system::instance();
        $fs = get_file_storage();
        $filepath = trim($folderpath, '/');
        $filepath = empty($filepath) ? '/' : '/' . $filepath . '/';
        $oldname = clean_param($oldname, PARAM_FILE);
        $newname = clean_param($newname, PARAM_FILE);

        $file = $fs->get_file($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, $oldname);

        if (!$file) {
            throw new \moodle_exception("File '{$oldname}' not found in '{$filepath}'");
        }

        // Kiểm tra nếu tên mới đã tồn tại
        if ($fs->file_exists($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, $newname)) {
            throw new \moodle_exception("A file named '{$newname}' already exists.");
        }

        // Tạo file mới từ file cũ
        $newfile = $fs->create_file_from_storedfile([
            'contextid' => $context->id,
            'component' => Framework::getTheme()->getName(),
            'filearea'  => $filearea,
            'itemid'    => $itemid,
            'filepath'  => $filepath,
            'filename'  => $newname,
        ], $file);

        // Xóa file cũ
        $file->delete();

        return [
            'success' => true,
            'oldname' => $oldname,
            'newname' => $newname,
            'url' => self::url($newfile),
            'message' => "File renamed from '{$oldname}' to '{$newname}'"
        ];
    }

    /**
     * Đổi tên thư mục trong filearea.
     *
     * @param string $oldfolder Đường dẫn cũ (vd: gallery/old)
     * @param string $newfolder Đường dẫn mới (vd: gallery/new)
     * @param string $filearea
     * @param int    $itemid
     * @return array
     */
    public static function rename_folder(string $oldfolder, string $newfolder, string $filearea = 'media', int $itemid = 0): array {
        $context = \context_system::instance();
        $fs = get_file_storage();

        $oldpath = '/' . trim($oldfolder, '/') . '/';
        $newpath = '/' . trim($newfolder, '/') . '/';
        $component = Framework::getTheme()->getName();

        // Kiểm tra folder cũ có tồn tại không
        if (!$fs->file_exists($context->id, $component, $filearea, $itemid, $oldpath, '.')) {
            throw new \moodle_exception("Folder '{$oldfolder}' not found");
        }

        // Nếu folder mới đã tồn tại → báo lỗi
        if ($fs->file_exists($context->id, $component, $filearea, $itemid, $newpath, '.')) {
            throw new \moodle_exception("Folder '{$newfolder}' already exists");
        }

        // Tạo folder mới
        $fs->create_directory($context->id, $component, $filearea, $itemid, $newpath);

        // Duyệt tất cả file trong filearea
        $files = $fs->get_area_files($context->id, $component, $filearea, $itemid, '', false);
        $moved = 0;

        foreach ($files as $file) {
            $fp = $file->get_filepath();

            if (strpos($fp, $oldpath) === 0) {
                // Tính đường dẫn mới bằng cách thay thế oldpath -> newpath
                $newfilepath = str_replace($oldpath, $newpath, $fp);

                // Tạo file mới
                $newrecord = [
                    'contextid' => $context->id,
                    'component' => $component,
                    'filearea'  => $filearea,
                    'itemid'    => $itemid,
                    'filepath'  => $newfilepath,
                    'filename'  => $file->get_filename(),
                ];
                $fs->create_file_from_storedfile($newrecord, $file);
                $file->delete();
                $moved++;
            }
        }

        // Xóa folder cũ (record filename='.')
        $oldfolderfile = $fs->get_file($context->id, $component, $filearea, $itemid, $oldpath, '.');
        if ($oldfolderfile) {
            $oldfolderfile->delete();
        }

        return [
            'success' => true,
            'moved' => $moved,
            'oldfolder' => $oldfolder,
            'newfolder' => $newfolder,
            'message' => "Folder renamed from '{$oldfolder}' to '{$newfolder}' ({$moved} files moved)"
        ];
    }

    /**
     * Xóa file trong filearea theo tên.
     */
    public static function delete(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): array {
        $context = context_system::instance();
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, $filename);
        if ($file) {
            try {
                $filenameVal = $file->get_filename();
                $file->delete();
                return [
                    'success' => true,
                    'filename' => $filenameVal,
                    'message' => 'File deleted successfully'
                ];
            } catch (\Exception $e) {
                debugging('Media::delete() failed: ' . $e->getMessage(), DEBUG_DEVELOPER);
                return [
                    'success' => false,
                    'message' => 'Error deleting file: ' . $e->getMessage()
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'File not found'
        ];
    }

    /**
     * Kiểm tra file tồn tại trong filearea.
     */
    public static function exists(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): bool {
        $context = context_system::instance();
        $fs = get_file_storage();
        return $fs->file_exists($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, $filename);
    }

    /**
     * Lấy thumbnail (nếu là ảnh).
     */
    public static function thumbnail(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): ?string {
        $context = context_system::instance();
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, $filename);

        if (!$file || strpos($file->get_mimetype(), 'image/') !== 0) {
            return null;
        }

        // Trả về URL ảnh thu nhỏ (có thể xử lý resize sau)
        return self::url($file);
    }

    /**
     * Get data of a file
     */
    public static function data(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): ?string {
        $context = context_system::instance();
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, $filename);

        if (!$file) {
            return null;
        }

        return $file->get_content();
    }

    /**
     * Get data of a file
     */
    public static function file(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): ?stored_file {
        $context = context_system::instance();
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, Framework::getTheme()->getName(), $filearea, $itemid, $filepath, $filename);
        return $file ?? null;
    }
}