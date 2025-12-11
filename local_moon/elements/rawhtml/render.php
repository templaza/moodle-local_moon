<?php
defined('MOODLE_INTERNAL') || die;
$params         = $this->params;
$content        = $params->get('content', '');
$content        = format_text($content, FORMAT_HTML, ['context' => $this->context]);

if (!empty($content)) {
    echo $content;
}