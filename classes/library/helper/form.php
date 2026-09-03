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
 * @package   local_moon
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

namespace local_moon\library\helper;
defined('MOODLE_INTERNAL') || die;
use local_moon\library\framework;
use local_moon\library\helper\constants;

class form
{
    public mixed $id = null;
    public mixed $type = '';
    public string $title = '';
    public string $icon = '';
    public string $color = '';
    public mixed $category = null;
    public bool $multiple = true;
    public string $description = '';
    public string $element_type = 'layout';
    protected string $config_file = '';
    protected array $default_config = [];
    protected string $default_pro_config_file = '';
    protected string $layout = '';
    public array $params = [];
    public mixed $data = [];
    protected mixed $template = null;
    protected $config = null;
    protected array $form = [];
    protected mixed $raw_data = null;
    protected array $subform = [];
    protected string $mode = '';

    public function __construct($type = '', $data = [], $mode = '')
    {
        global $CFG;
        $this->type = $type;
        $this->mode = $mode;
        if (!empty($data)) {
            if ($type == 'subform') {
                $this->subform = $data;
            } else {
                $this->id = $data['id'];
                $this->data = isset($data['params']) ? $data['params'] : [];
                $this->raw_data = $data;
            }
        }

        $this->template = framework::get_theme();

        $template_name = $this->template->get_name();

        if ($type !== 'subform' && $type !=='sublayout') {
            $library_elements_directory = $CFG->dirroot . '/local/moon/elements/';
            $template_elements_directory = $CFG->dirroot . '/theme/' . $template_name . '/elements/';

            $this->default_config = constants::get_element_default_options();

            switch ($this->type) {
                case 'section':
                    $this->config_file = $library_elements_directory . 'section.php';
                    break;
                case 'column':
                    $this->config_file = $library_elements_directory . 'column.php';
                    break;
                case 'row':
                    $this->config_file = $library_elements_directory . 'row.php';
                    break;
                default:
                    if (file_exists(path::clean($library_elements_directory . $this->type . '/config.php'))) {
                        $this->config_file = path::clean($library_elements_directory . $this->type . '/config.php');
                        $this->layout = path::clean($library_elements_directory . $this->type . '/render.php');
                    }
                    break;
            }

            if (file_exists(path::clean($template_elements_directory . $this->type . '/config.php'))) {
                $this->config_file = path::clean($template_elements_directory . $this->type . '/config.php');
                $this->layout = path::clean($template_elements_directory . $this->type . '/render.php');
            }
        }

        if ($this->type !='sublayout') {
            if ($this->config_file) {
                $this->load_config();
            }
            $this->load_form();
        }
    }

    protected function load_config(): void
    {
        require_once($this->config_file);
        $classname = 'moon_element_' . $this->type;
        if (!class_exists($classname) && class_exists('\\' . $classname)) {
            $classname = '\\' . $classname;
        }
        if (!class_exists($classname)) {
            throw new \RuntimeException("Element class {$classname} not found");
        }
        $element = new $classname();
        $this->config = $element;
        $this->title = $element->title;
        $this->element_type = $element->element_type;
        $this->icon = $element->icon;
        $this->category = explode(',', $element->category);
        for ($i = 0 ; $i < count($this->category); $i++) {
            $this->category[$i] = text::_($this->category[$i]);
        }
        $this->description = $element->description;
        $this->multiple = $element->multiple;
    }

    public function load_form(): void
    {
        if ($this->type !== 'subform') {
            $this->form = $this->config->fields;
        } else {
            if ($this->subform['formtype'] == 'string') {
                $this->form = $this->subform['formsource'];
            } else {
                require_once($this->subform['formsource']);
                if (isset($configs) && is_array($configs)) {
                    $this->form = $configs;
                }
            }
        }

        $form_data = [];
        $fieldsets = $this->form;
        foreach ($fieldsets as $key => $fieldset) {
            $fields = $fieldset['fields'];
            foreach ($fields as $fkey => $field) {
                if ($field['type'] !== 'group') {
                    $form_data[] = ['name' => $fkey, 'value' => ($field['default'] ?? '')];
                }
            }
        }

        $this->params = $form_data;
    }

    public function get_info(): array
    {
        return [
            'type' => $this->type,
            'title' => text::_($this->title),
            'icon' => $this->icon,
            'category' => $this->category,
            'element_type' => $this->element_type,
            'description' => text::_($this->description),
            'multiple' => $this->multiple,
            'params' => $this->params,
        ];
    }

    public function render_json($type = 'system'): array
    {
        switch ($type) {
            case 'sublayout':
                return array('info' => [
                    'type' => 'sublayout',
                    'title' => 'Sublayouts',
                    'icon' => 'as-icon as-icon-layers',
                ], 'type' => $type);
                break;
            default:
                $settings = $this->get_form();
                foreach ($settings as $key => $fieldset) {
                    $settings[$key]['name'] = $key;
                }
                $form_content = array();
                foreach ($settings as $key => $setting) {
                    if ($this->mode === 'article_data' && isset($setting['articleData']) && $setting['articleData'] === false) {
                        continue;
                    }
                    $fieldset = new \stdClass();
                    $fieldset->name = $key;
                    $fieldset->label = text::_($setting['label'] ?? '');
                    $fieldset->description = isset($setting['description']) ? text::_($setting['description']) : '';
                    $fieldset->icon = $setting['icon'] ?? '';
                    $groups = [];
                    foreach ($setting['fields'] as $gkey => $field) {
                        if ($field['type'] == 'group') {
                            $groups[$gkey] = ['title' => text::_($field['label'] ?? ''), 'icon' => ($field['icon'] ?? ''), 'description' => text::_($field['description'] ?? ''), 'fields' => [], 'help' => ($field['help'] ?? ''), 'option-type' => ($field['option-type'] ?? '')];
                        }
                    }

                    $groups['none'] = ['fields' => []];

                    foreach ($setting['fields'] as $fkey => $field) {
                        if ($field['type'] == 'group') {
                            continue;
                        }

                        if ($this->mode === 'article_data' && isset($field['articleData']) && $field['articleData'] === false) {
                            continue;
                        }

                        $input = $field['attributes'] ?? [];
                        $input['id'] = 'moon_form_' . $fkey;
                        $input['name'] = 'params[' .$fkey .']';
                        $input['type'] = 'astroid' . $field['type'];
                        if (isset($field['options']) && is_array($field['options']) && count($field['options']) > 0) {
                            $input['options'] = [];
                            foreach ($field['options'] as $option_key => $option_text) {
                                $option = new \stdClass();
                                $option->value = $option_key;
                                $option->text = text::_($option_text);
                                $input['options'][] = $option;
                            }
                        }

                        $field_group = $field['group'] ?? 'none';
                        $field_tmp  =   [
                            'id'            =>  'moon_el_form_' . $fkey,
                            'name'          =>  $fkey,
                            'value'         =>  $field['default'] ?? '',
                            'label'         =>  text::_($field['label'] ?? ''),
                            'description'   =>  text::_($field['description'] ?? ''),
                            'input'         =>  $input,
                            'type'          =>  'json',
                            'group'         =>  $fieldset->name,
                            'ngShow'        =>  settings::replace_relationship_operators($field['conditions'] ?? ''),
                            'help'          =>  $field['help'] ?? '',
                        ];

                        $groups[$field_group]['fields'][] = $field_tmp;
                    }
                    // Get sidebar data
                    $fieldset->childs   = $groups;
                    $form_content[] = $fieldset;
                }
                return array('content' => $form_content, 'info' => $this->get_info(), 'type' => $type);
                break;
        }
    }

    public function get_form()
    {
        return $this->form;
    }

    protected function get_params()
    {
        $form_data = [];
        foreach ($this->data as $data) {
            $data = (array) $data;
            $form_data[$data['name']] = $data['value'];
        }
        /* $params = [];
        foreach ($this->params as $param) {
           $param = (array) $param;
           if (isset($formData[$param['name']])) {
              $params[$param['name']] = $formData[$param['name']];
           } else {
              $params[$param['name']] = $param['value'];
           }
        } */

        return new params($form_data);
    }
}
