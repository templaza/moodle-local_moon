<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
define('AJAX_SCRIPT', true);
require_once(__DIR__ . '/../../../config.php');

require_login();
if (!is_siteadmin()) {
    throw new required_capability_exception(
        context_system::instance(),
        'moodle/site:config',
        'nopermissions',
        ''
    );
}
require_sesskey();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moon/ajax/action.php'));

use local_moon\library\Framework;
use local_moon\library\Helper\Action;

header('Content-Type: application/json; charset=utf-8');
$theme_name = optional_param('theme', $PAGE->theme, PARAM_ALPHANUMEXT);
$task = optional_param('task', '', PARAM_ALPHA);
$filearea = optional_param('filearea', 'media', PARAM_ALPHANUMEXT);
$itemid = optional_param('itemid', 0, PARAM_INT);

Framework::init($theme_name);
$exec = new Action($filearea, $itemid);
try {
    if (!method_exists($exec, $task)) {
        throw new Exception('Method not found');
    }
    $result = $exec->$task();
    $exec->response($result);
} catch (Exception $e) {
    $exec->errorResponse($e);
}