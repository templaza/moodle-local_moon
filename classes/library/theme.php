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

namespace local_moon\library;
defined('MOODLE_INTERNAL') || die;
use local_moon\library\element\layout;
use local_moon\library\helper\path;
use local_moon\library\helper\registry;
use local_moon\library\helper\utilities;
use theme_config;
use local_moon\library\helper\text;

class theme {
    public string $name = 'moon';
    public object $theme;
    protected array $fields = [];
    protected object|null $params = null;
    protected array|null $config = null;
    public function __construct($theme = null) {
        global $PAGE, $CFG;
        if (!defined('CLI_SCRIPT')) {
            // If $PAGE exists but has no context yet, set system context as fallback.
            if (isset($PAGE) && empty($PAGE->context)) {
                $PAGE->set_context(\context_system::instance());
            }
        }
        if (empty($theme) && isset($PAGE) && $PAGE->theme) {
            $this->theme = $PAGE->theme;
            $this->name  = $PAGE->theme->name;
        } else {
            $themename = $theme ?? get_config('core', 'theme');
            $this->theme = theme_config::load($themename);
            $this->name  = $this->theme->name;
        }
        $this->config = $this->get_theme_configs();
        $this->params = new registry($this->theme->settings);
        if (empty($this->params->get('layout')) && empty($this->params->get('header'))) {
            $preset_path = $CFG -> dirroot . "/theme/{$this->name}/moon/presets/default.json";
            if (file_exists($preset_path)) {
                $preset = \json_decode(\file_get_contents($preset_path), true);
                if (!empty($preset['preset'])) {
                    $this->params->load_array($preset['preset']);
                }
            }
        }
    }
    public function get_name() : string
    {
        return 'theme_' . $this->name;
    }
    public function get_theme_configs() : array|null
    {
        if ($this->config) return $this->config;
        $this->config = utilities::get_theme_configs($this->name);
        return $this->config;
    }
    public function get_params()
    {
        return $this->params;
    }
    public function is_moon() : bool
    {
        return is_array($this->config) && isset($this->config['framework']) && $this->config['framework'] == 'moon';
    }
    public function add_fields($fieldset, $setting): void
    {
        if (!isset($this->fields[$fieldset])) {
            $this->fields[$fieldset] = $setting;
        } else {
            $this->fields[$fieldset] = array_merge($this->fields[$fieldset], $setting);
        }
    }

    public function get_fields(): array
    {
        return $this->fields;
    }

    public function load_settings(): void
    {
        foreach ($this->fields as $key => $fieldset) {
            foreach ($fieldset['fields'] as $fieldname => $value) {
                if ($value['type'] == 'group') {
                    continue;
                }
                $this->fields[$key]['fields'][$fieldname]['value'] = $this->params->get($fieldname, $this->fields[$key]['fields'][$fieldname]['default'] ?? '');
            }
        }
    }

    public function get_layouts(): array
    {
        return layout::get_datalayouts(framework::get_theme()->get_name(), 'main_layouts');
    }

    public function get_layout($layout = '') : array|false
    {
        global $PAGE;
        return empty($layout) ? (layout::get_data_layout($PAGE->pagelayout, 'main_layouts') ?: false) : (layout::get_data_layout($layout, 'main_layouts') ?: false);
    }

    public function register_layout(string $name, array $data): void {
        if (empty($name) || empty($data['file'])) {
            debugging("registerLayout(): Missing layout name or file.", DEBUG_DEVELOPER);
            return;
        }

        // Normalize layout information.
        $layout = [
            'file' => $data['file'],
            'regions' => $data['regions'] ?? ['side-pre', 'side-post'],
            'defaultregion' => $data['defaultregion'] ?? 'side-pre',
        ];

        // Merge additional options.
        if (!empty($data['options'])) {
            $layout = array_merge($layout, $data['options']);
        }
        // Write into theme layouts.
        $this->theme->layouts[$name] = $layout;
    }

    public function get_element_layout($type) : string
    {
        global $CFG;
        $template_path = $CFG->dirroot . "/theme/{$this->name}/elements";
        if (file_exists(path::clean($template_path . '/' . $type . '/render.php'))) {
            return path::clean($template_path . '/' . $type . '/render.php');
        }
        $local_path = $CFG->dirroot . '/local/moon/elements';
        if (file_exists(path::clean($local_path . '/' . $type . '/render.php'))) {
            return path::clean($local_path . '/' . $type . '/render.php');
        }
        debugging("getElementLayout(): Element layout not found for type: {$type}", DEBUG_DEVELOPER);
        return '';
    }
    public function get_color_mode() :string {
        $color_mode = $this->params->get('astroid_color_mode_enable', 0);
        if ($color_mode == 2) {
            return 'dark';
        }

        $color_mode_default = $this->params->get('astroid_color_mode_default', 'auto');

        if ($color_mode == 1) {
            if ($this->params->get('enable_color_mode_transform', 0)) {
                return $this->params->get('colormode_transform_type', 'light_dark') === 'light_dark' ? 'light' : 'dark';
            }
            $client_color = optional_param('color_mode', '', PARAM_ALPHAEXT);
            return !empty($client_color)
                ? $client_color
                : ($_COOKIE['moon-color-mode-' . md5($this->name)] ?? $color_mode_default);
        }

        return 'light';
    }
    public function get_regions() : array
    {
        return $this->config['regions'] ?? [];
    }
    public function get_theme_variables()
    {
        $variables = [];
        $variables['blue'] = $this->params->get('theme_blue', '#007bff');
        $variables['indigo'] = $this->params->get('theme_indigo', '#6610f2');
        $variables['purple'] = $this->params->get('theme_purple', '#6f42c1');
        $variables['pink'] = $this->params->get('theme_pink', '#e83e8c');
        $variables['red'] = $this->params->get('theme_red', '#dc3545');
        $variables['orange'] = $this->params->get('theme_orange', '#fd7e14');
        $variables['yellow'] = $this->params->get('theme_yellow', '#ffc107');
        $variables['green'] = $this->params->get('theme_green', '#28a745');
        $variables['teal'] = $this->params->get('theme_teal', '#20c997');
        $variables['cyan'] = $this->params->get('theme_cyan', '#17a2b8');
        $variables['white'] = $this->params->get('theme_white', '#fff');
        $variables['gray100'] = $this->params->get('theme_gray100', '#f8f9fa');
        $variables['gray600'] = $this->params->get('theme_gray600', '#6c757d');
        $variables['gray800'] = $this->params->get('theme_gray800', '#343a40');

        $primary = $this->params->get('theme_primary', '');
        if (!empty($primary)) {
            $variables['primary'] = ($primary == 'custom' ? $this->params->get('theme_primary_custom', $variables['blue']) : $variables[$primary]);
        }

        $secondary = $this->params->get('theme_secondary', '');
        if (!empty($secondary)) {
            $variables['secondary'] = ($secondary == 'custom' ? $this->params->get('theme_secondary_custom', $variables['gray600']) : $variables[$secondary]);
        }

        $success = $this->params->get('theme_success', '');
        if (!empty($success)) {
            $variables['success'] = ($success == 'custom' ? $this->params->get('theme_success_custom', $variables['green']) : $variables[$success]);
        }

        $info = $this->params->get('theme_info', '');
        if (!empty($info)) {
            $variables['info'] = ($info == 'custom' ? $this->params->get('theme_info_custom', $variables['cyan']) : $variables[$info]);
        }

        $warning = $this->params->get('theme_warning', '');
        if (!empty($warning)) {
            $variables['warning'] = ($warning == 'custom' ? $this->params->get('theme_warning_custom', $variables['yellow']) : $variables[$warning]);
        }

        $danger = $this->params->get('theme_danger', '');
        if (!empty($danger)) {
            $variables['danger'] = ($danger == 'custom' ? $this->params->get('theme_danger_custom', $variables['red']) : $variables[$danger]);
        }

        $light = $this->params->get('theme_light', '');
        if (!empty($light)) {
            $variables['light'] = ($light == 'custom' ? $this->params->get('theme_light_custom', $variables['gray100']) : $variables[$light]);
        }

        $dark = $this->params->get('theme_dark', '');
        if (!empty($dark)) {
            $variables['dark'] = ($dark == 'custom' ? $this->params->get('theme_dark_custom', $variables['gray800']) : $variables[$dark]);
        }

        $variables = $this->_variable_overrides($variables);

        return $variables;
    }

    protected function _variable_overrides($variables)
    {
        $sass_overrides = $this->params->get('sass_overrides', '{}');
        $sass_overrides = \json_decode($sass_overrides, true);
        if (empty($sass_overrides)) {
            return $variables;
        }

        foreach ($sass_overrides as $sass_override) {
            $variable = $sass_override['variable'];
            if (!empty($variable) && !empty($sass_override['value'])) {
                if (substr($variable, 0, 1) === "$") {
                    $variable = ltrim($variable, '$');
                }
                $variables[$variable] = $sass_override['value'];
            }
        }
        return $variables;
    }

    public function get_actual_color_mode(): string
    {
        $color_mode = $this->get_color_mode();
        return ($color_mode == 'auto') ? 'light' : $color_mode;
    }
    public function get_presets(): array
    {
        global $CFG;
        $presets_path = $CFG -> dirroot . "/theme/{$this->name}/moon/presets/";

        if (!file_exists($presets_path)) {
            return [];
        }
        $files = array_filter(glob($presets_path . '*.json'), 'is_file');
        $presets    =   [];
        foreach ($files as $file) {
            $json = file_get_contents($file);
            $data = \json_decode($json, true);
            $preset = ['title' => pathinfo($file)['filename'], 'desc' => '', 'thumbnail' => '', 'demo' => '', 'preset' => [], 'name' => pathinfo($file)['filename']];
            if (!empty($data['title'])) {
                $preset['title'] = text::_($data['title']);
            }
            if (isset($data['desc'])) {
                $preset['desc'] = text::_($data['desc']);
            }
            if (!empty($data['thumbnail'])) {
                $preset['thumbnail'] = $CFG -> wwwroot . '/theme/' . $this->name . '/' . $data['thumbnail'];
            }
            if (isset($data['demo'])) {
                $preset['demo'] = $data['demo'];
            }
            if (isset($data['preset'])) {
                $preset['preset'] = $data['preset'];
            }
            $presets[] = $preset;
        }
        return $presets;
    }
}