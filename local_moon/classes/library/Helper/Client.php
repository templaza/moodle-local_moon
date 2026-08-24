<?php

/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2023 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

namespace local_moon\library\Helper;

defined('MOODLE_INTERNAL') or die;

class Client
{
    protected $format = 'json';

    public function __construct()
    {
        $this->format = optional_param('format', 'json', PARAM_ALPHANUMEXT);
    }

    protected function responseMime(): void
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
        $this->responseMime();
        switch ($this->format) {
            case 'json':
                if (!$raw) {
                    $return = $this->responseData(['data' => $data]);
                } else {
                    $return = $data;
                }
                $data = \json_encode($return);
                break;
        }
        echo $data;
        exit();
    }

    public function errorResponse(\Exception $e)
    {
        $this->responseMime();
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

    public function responseData ($array = []): array
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
