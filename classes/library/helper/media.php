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

namespace local_moon\library\helper;
defined('MOODLE_INTERNAL') || die;

use local_moon\library\framework;
use moodle_url;
use context_system;
use stored_file;

class media {

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
    public static function plugin_file($component, $context, $filearea, $args, $forcedownload, array $options = []) {
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
     * Create a new file from a string
     *
     * @param string $content   File content
     * @param string $filename  File name (e.g., "config.json")
     * @param string $filepath  Path within the file area (e.g., "/settings/")
     * @param string $filearea  File area (e.g., "media")
     * @param int    $itemid    Item ID (default 0)
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
        $component = framework::get_theme()->get_name();

        // Normalize file name and path.
        $filename = clean_param($filename, PARAM_FILE);
        $filepath = trim($filepath, '/');
        $filepath = empty($filepath) ? '/' : '/' . $filepath . '/';

        // If the file already exists, delete it before writing a new one.
        if ($fs->file_exists($context->id, $component, $filearea, $itemid, $filepath, $filename)) {
            $oldfile = $fs->get_file($context->id, $component, $filearea, $itemid, $filepath, $filename);
            if ($oldfile) {
                $oldfile->delete();
            }
        }

        // File record data.
        $filerecord = [
            'contextid' => $context->id,
            'component' => $component,
            'filearea'  => $filearea,
            'itemid'    => $itemid,
            'filepath'  => $filepath,
            'filename'  => $filename,
            'userid'    => $USER->id ?? 0,
        ];

        // Create file from string content.
        $file = $fs->create_file_from_string($filerecord, $content);
        return $file ?: null;
    }

    /**
     * Upload a media file (image/video) to the plugin file area.
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
            'component' => framework::get_theme()->get_name(),
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

        // Normalize path.
        $folderpath = trim($folderpath, '/');
        $filepath = empty($folderpath) ? '/' : '/' . $folderpath . '/';

        // Check whether it already exists.
        if ($fs->file_exists($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, '.')) {
            return false; // already exists
        }

        $fs->create_directory($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, $USER->id ?? 0);
        return true;
    }

    /**
     * Delete a folder and all child files in the plugin file area.
     *
     * @param string $folderpath Folder path (e.g., gallery/sub)
     * @param string $filearea
     * @param int $itemid
     * @return array
     */
    public static function delete_folder(string $folderpath, string $filearea = 'media', int $itemid = 0): array {
        $context = \context_system::instance();
        $fs = get_file_storage();

        // Normalize folder path.
        $folderpath = trim($folderpath, '/');
        $filepath = empty($folderpath) ? '/' : '/' . $folderpath . '/';

        // Get all files in this folder (including subfolders).
        $files = $fs->get_area_files($context->id, framework::get_theme()->get_name(), $filearea, $itemid, '', false);
        $deleted = 0;

        foreach ($files as $file) {
            if (strpos($file->get_filepath(), $filepath) === 0) {
                $file->delete();
                $deleted++;
            }
        }

        // Also delete the folder record itself (filename='.')
        $folder = $fs->get_file($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, '.');
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
     * Delete all files in a folder without deleting the folder itself.
     *
     * @param string $folderpath Folder path (e.g., gallery/sub)
     * @param string $filearea
     * @param int $itemid
     * @return array
     */
    public static function empty_folder(string $folderpath, string $filearea = 'media', int $itemid = 0): array {
        $context = \context_system::instance();
        $fs = get_file_storage();
        $component = framework::get_theme()->get_name();

        // Normalize path.
        $folderpath = trim($folderpath, '/');
        $filepath = empty($folderpath) ? '/' : '/' . $folderpath . '/';

        // Check whether the folder exists.
        if (!$fs->file_exists($context->id, $component, $filearea, $itemid, $filepath, '.')) {
            return [
                'success' => false,
                'message' => "Folder '{$folderpath}' does not exist"
            ];
        }
        $files = $fs->get_area_files($context->id, $component, $filearea, $itemid, '', false);
        $deleted = 0;

        foreach ($files as $file) {
            // Skip folder record (.).
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
     * Get the access URL (pluginfile.php) for a file.
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
     * Get the list of files in a file area (e.g., gallery, videos).
     */
    public static function list(string $filearea = 'media', int $itemid = 0, string $filepath = '/', string $filter = ''): array {
        $context = context_system::instance();
        $fs = get_file_storage();

        $files = $fs->get_area_files($context->id, framework::get_theme()->get_name(), $filearea, $itemid, 'timemodified DESC', true);
        $list = [];
        foreach ($files as $file) {
            if ($file->get_filepath() !== $filepath) {
                if ($file->is_directory() && $filter == '') {
                    $pos = strpos($file->get_filepath(), $filepath);
                    if ($pos === false) {
                        continue;
                    }
                    $start = $pos + strlen($filepath);
                    $dir_path = substr($file->get_filepath(), $start);
                    if (empty($dir_path)) {
                        continue;
                    }
                    $dir_path = rtrim($dir_path, '/');
                    if (strpos($dir_path, '/') !== false) {
                        continue;
                    }
                    $list[] = [
                        'filename' => $dir_path,
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
     * Rename a file in the file area.
     *
     * @param string $oldname  Old file name (e.g., banner.jpg)
     * @param string $newname  New file name (e.g., hero.jpg)
     * @param string $filearea File area
     * @param int    $itemid   Item ID (default 0)
     * @param string $folderpath Folder path (e.g., gallery/)
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

        $file = $fs->get_file($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, $oldname);

        if (!$file) {
            throw new \moodle_exception("File '{$oldname}' not found in '{$filepath}'");
        }

        // Check whether the new name already exists.
        if ($fs->file_exists($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, $newname)) {
            throw new \moodle_exception("A file named '{$newname}' already exists.");
        }

        // Create new file from old file.
        $newfile = $fs->create_file_from_storedfile([
            'contextid' => $context->id,
            'component' => framework::get_theme()->get_name(),
            'filearea'  => $filearea,
            'itemid'    => $itemid,
            'filepath'  => $filepath,
            'filename'  => $newname,
        ], $file);

        // Delete old file.
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
     * Rename a folder in the file area.
     *
     * @param string $oldfolder Old path (e.g., gallery/old)
     * @param string $newfolder New path (e.g., gallery/new)
     * @param string $filearea
     * @param int    $itemid
     * @return array
     */
    public static function rename_folder(string $oldfolder, string $newfolder, string $filearea = 'media', int $itemid = 0): array {
        $context = \context_system::instance();
        $fs = get_file_storage();

        $oldpath = '/' . trim($oldfolder, '/') . '/';
        $newpath = '/' . trim($newfolder, '/') . '/';
        $component = framework::get_theme()->get_name();

        // Check whether the old folder exists.
        if (!$fs->file_exists($context->id, $component, $filearea, $itemid, $oldpath, '.')) {
            throw new \moodle_exception("Folder '{$oldfolder}' not found");
        }

        // If the new folder already exists, throw an error.
        if ($fs->file_exists($context->id, $component, $filearea, $itemid, $newpath, '.')) {
            throw new \moodle_exception("Folder '{$newfolder}' already exists");
        }

        // Create new folder.
        $fs->create_directory($context->id, $component, $filearea, $itemid, $newpath);

        // Iterate through all files in the file area.
        $files = $fs->get_area_files($context->id, $component, $filearea, $itemid, '', false);
        $moved = 0;

        foreach ($files as $file) {
            $fp = $file->get_filepath();

            if (strpos($fp, $oldpath) === 0) {
                // Compute new path by replacing oldpath -> newpath.
                $newfilepath = str_replace($oldpath, $newpath, $fp);

                // Create new file.
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

        // Delete old folder (record filename='.')
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
     * Delete a file in the file area by name.
     */
    public static function delete(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): array {
        $context = context_system::instance();
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, $filename);
        if ($file) {
            try {
                $filename_val = $file->get_filename();
                $file->delete();
                return [
                    'success' => true,
                    'filename' => $filename_val,
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
     * Check whether a file exists in the file area.
     */
    public static function exists(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): bool {
        $context = context_system::instance();
        $fs = get_file_storage();
        return $fs->file_exists($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, $filename);
    }

    /**
     * Get thumbnail (if it is an image).
     */
    public static function thumbnail(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): ?string {
        $context = context_system::instance();
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, $filename);

        if (!$file || strpos($file->get_mimetype(), 'image/') !== 0) {
            return null;
        }

        // Return thumbnail URL (resize can be handled later).
        return self::url($file);
    }

    /**
     * Get data of a file
     */
    public static function data(string $filename, string $filepath = '/', string $filearea = 'media', int $itemid = 0): ?string {
        $context = context_system::instance();
        $fs = get_file_storage();
        $file = $fs->get_file($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, $filename);

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
        $file = $fs->get_file($context->id, framework::get_theme()->get_name(), $filearea, $itemid, $filepath, $filename);
        return $file ?? null;
    }
}