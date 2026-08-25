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

namespace local_moon\library\element;

use local_moon\library\framework;

defined('MOODLE_INTERNAL') || die;

class section extends base_element
{
    public $hasComponent = false;
    public $hasHeader = false;
    public $hasFooter = false;

    public function render()
    {
        $content = '';
        foreach ($this->_data['rows'] as $row) {
            $row = new row($row, $this, $this->role);
            $content .= $row->render();
        }
        $container = $this->_container();
        if (!empty($content) && !empty($container)) {
            $this->content .= '<div class="' . $container . '">';
        }
        $this->content .= $content;
        if (!empty($content) && !empty($container)) {
            $this->content .= '</div>';
        }
        return $this->wrap();
    }

    protected function _container()
    {
        $container = $this->params->get('layout_type', '');
        $custom_class = $this->params->get('custom_container_class', '');
        switch ($container) {
            case '':
                if ($this->isRoot) {
                    $container = 'container';
                } else {
                    $container = '';
                }
                break;
            case 'no-container':
                $container = '';
                break;
            case 'container-with-no-gutters':
                $container = 'container';
                break;
            case 'container-fluid-with-no-gutters':
                $container = 'container-fluid';
                break;
        }
        if (!empty($container) && !empty($custom_class)) {
            $container .= ' ' . $custom_class;
        }
        return $container;
    }
}
