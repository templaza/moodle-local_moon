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

/**
* @package   Astroid Framework
* @author    Astroid Framework Team https://astroidframe.work
* @copyright Copyright (C) 2025 AstroidFrame.work.
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/

namespace local_moon\library\Helper;

class SubForm {
    public array $data = [];
    public function __construct($data = '{}') {
        $data = json_decode($data);
        if (!empty($data)) {
            foreach ($data as $value) {
                $tmp = (object) [
                    'id' => $value->id,
                    'params' => Utilities::loadParams($value->params)
                ];
                $dynamic_data = $this->getDynamicContent($tmp);
                if (!empty($dynamic_data)) {
                    foreach ($dynamic_data as $idx => $dynamic_data_item) {
                        $params_data = new Registry();
                        $params_data->merge($tmp->params);
                        foreach ($dynamic_data_item as $key => $item_value) {
                            $params_data->set($key, $item_value);
                        }
                        $this->data[] = (object) [
                            'id' => $value->id . '_' . $idx,
                            'params' => $params_data
                        ];
                    }
                } else {
                    $this->data[] = $tmp;
                }
            }
        }
    }

    public function getDynamicContent($item = null) {
        $dynamic_data = [];
//        if (Helper::isPro() && !empty($item)) {
//            $dynamic_params = $item->params->get('dynamic_content_settings');
//            if (!empty($dynamic_params)) {
//                $dynamic_content = new DynamicContent(
//                    $dynamic_params->source,
//                    $dynamic_params->start,
//                    $dynamic_params->quantity,
//                    $dynamic_params->conditions,
//                    $dynamic_params->order,
//                    $dynamic_params->order_dir,
//                    $dynamic_params->dynamic_content,
//                    $dynamic_params->options
//                );
//                $dynamic_data = $dynamic_content->getContent();
//            }
//        }
        return $dynamic_data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}