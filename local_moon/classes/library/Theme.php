<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\library;
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Element\Layout;
use local_moon\library\Helper\Path;
use local_moon\library\Helper\Registry;
use local_moon\library\Helper\Utilities;
use theme_config;
use local_moon\library\Helper\Text;

class Theme {
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
        $this->config = $this->getThemeConfigs();
        $this->params = new Registry($this->theme->settings);
        if (empty($this->params->get('layout')) && empty($this->params->get('header'))) {
            $preset_path = $CFG -> dirroot . "/theme/{$this->name}/moon/presets/default.json";
            if (file_exists($preset_path)) {
                $preset = \json_decode(\file_get_contents($preset_path), true);
                if (!empty($preset['preset'])) {
                    $this->params->loadArray($preset['preset']);
                }
            }
        }
    }
    public function getName() : string
    {
        return 'theme_' . $this->name;
    }
    public function getThemeConfigs() : array|null
    {
        if ($this->config) return $this->config;
        $this->config = Utilities::getThemeConfigs($this->name);
        return $this->config;
    }
    public function getParams()
    {
        return $this->params;
    }
    public function isMoon() : bool
    {
        return is_array($this->config) && isset($this->config['framework']) && $this->config['framework'] == 'moon';
    }
    public function addFields($fieldset, $setting): void
    {
        if (!isset($this->fields[$fieldset])) {
            $this->fields[$fieldset] = $setting;
        } else {
            $this->fields[$fieldset] = array_merge($this->fields[$fieldset], $setting);
        }
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function loadSettings(): void
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

    public function getLayouts(): array
    {
        return Layout::getDatalayouts(Framework::getTheme()->getName(), 'main_layouts');
    }

    public function getLayout($layout = '') : array|false
    {
        global $PAGE;
        return empty($layout) ? (Layout::getDataLayout($PAGE->pagelayout, 'main_layouts') ?: false) : (Layout::getDataLayout($layout, 'main_layouts') ?: false);
    }

    public function registerLayout(string $name, array $data): void {
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

    public function getElementLayout($type) : string
    {
        global $CFG;
        $template_path = $CFG->dirroot . "/theme/{$this->name}/elements";
        if (file_exists(Path::clean($template_path . '/' . $type . '/render.php'))) {
            return Path::clean($template_path . '/' . $type . '/render.php');
        }
        $local_path = $CFG->dirroot . '/local/moon/elements';
        if (file_exists(Path::clean($local_path . '/' . $type . '/render.php'))) {
            return Path::clean($local_path . '/' . $type . '/render.php');
        }
        debugging("getElementLayout(): Element layout not found for type: {$type}", DEBUG_DEVELOPER);
        return '';
    }
    public function getColorMode() :string {
        $colorMode = $this->params->get('astroid_color_mode_enable', 0);
        if ($colorMode == 2) {
            return 'dark';
        }

        $colorModeDefault = $this->params->get('astroid_color_mode_default', 'auto');

        if ($colorMode == 1) {
            if ($this->params->get('enable_color_mode_transform', 0)) {
                return $this->params->get('colormode_transform_type', 'light_dark') === 'light_dark' ? 'light' : 'dark';
            }
            $clientColor = optional_param('color_mode', '', PARAM_ALPHAEXT);
            return !empty($clientColor)
                ? $clientColor
                : ($_COOKIE['moon-color-mode-' . md5($this->name)] ?? $colorModeDefault);
        }

        return 'light';
    }
    public function getRegions() : array
    {
        return $this->config['regions'] ?? [];
    }
    public function getThemeVariables()
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

        $variables = $this->_variableOverrides($variables);

        return $variables;
    }

    protected function _variableOverrides($variables)
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

    public function getActualColorMode(): string
    {
        $colorMode = $this->getColorMode();
        return ($colorMode == 'auto') ? 'light' : $colorMode;
    }
    public function getPresets(): array
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
                $preset['title'] = Text::_($data['title']);
            }
            if (isset($data['desc'])) {
                $preset['desc'] = Text::_($data['desc']);
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