<?php
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
}

