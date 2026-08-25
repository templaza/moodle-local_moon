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

defined('MOODLE_INTERNAL') or die;

class client
{
    protected $format = 'json';

    public function __construct()
    {
        $this->format = optional_param('format', 'json', PARAM_ALPHANUMEXT);
    }

    protected function response_mime(): void
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $type = isset($mime_types[$this->format]) ? $mime_types[$this->format] : $mime_types['json'];

        header('Content-Type: ' . $type);
    }

    public function response($data, $raw = false)
    {
        $this->response_mime();
        switch ($this->format) {
            case 'json':
                if (!$raw) {
                    $return = $this->response_data(['data' => $data]);
                } else {
                    $return = $data;
                }
                $data = \json_encode($return);
                break;
        }
        echo $data;
        exit();
    }

    public function error_response(\Exception $e)
    {
        $this->response_mime();
        switch ($this->format) {
            case 'json':
                $return = [];
                $return['status'] = 'error';
                $return['code'] = $e->getCode();
                $return['message'] = $e->getMessage();
                $data = \json_encode($return);
                break;
            default:
                $data = $e->getCode() . ' : ' . $e->getMessage();
                break;
        }
        echo $data;
        exit();
    }

    public function response_data ($array = []): array
    {
        $return = [
            'status' => 'success',
            'code' => 200,
            'message' => '',
            'data' => ''
        ];
        return array_merge($return, $array);
    }
}
