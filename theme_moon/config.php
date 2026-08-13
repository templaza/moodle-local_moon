<?php
defined('MOODLE_INTERNAL') || die();
use local_moon\library\Helper\Utilities;
global $CFG;
$THEME->name = 'moon';
$THEME->sheets = [];
$THEME->editor_sheets = [];
$THEME->parents = ['boost'];
$THEME->enable_dock = false;
$THEME->yuicssmodules = array();
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->scss = function($theme) {
    return theme_moon_get_main_scss_content($theme);
};
$THEME->layouts = Utilities::getLayouts($THEME->name);