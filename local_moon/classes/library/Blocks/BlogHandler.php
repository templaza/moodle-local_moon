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

/*
* BLOG HANDLER
*/
namespace local_moon\library\Blocks;

defined('MOODLE_INTERNAL') || die();

use context_user;
use moodle_url;

class BlogHandler {

    function moon_get_blog_image_url(int $blogid): ?string {
        global $DB;
        $context = \context_system::instance();
        $fs = get_file_storage();

        $files = $fs->get_area_files(
            $context->id,
            'blog',
            'attachment',
            $blogid,
            'filename',
            false
        );

        if (empty($files)) {
            return null;
        }

        foreach ($files as $file) {
            if ($file->is_valid_image()) {
                return moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false);
            }
        }

        return null;

    }
    function moon_get_blog_comment_count(int $blogid): ?int {
        global $DB;

        if ($blogid <= 0) {
            return null;
        }

        $sql = "SELECT COUNT(*)
            FROM {comments}
            WHERE commentarea = :area
              AND itemid = :itemid";

        $params = [
            'area'   => 'blog_post',
            'itemid' => $blogid
        ];

        try {
            return (int)$DB->count_records_sql($sql, $params);
        } catch (Exception $e) {
            return null;
        }
    }

}

