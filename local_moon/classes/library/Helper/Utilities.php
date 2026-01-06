<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\library\Helper;
defined('MOODLE_INTERNAL') || die;

use context_system;
use local_moon\library\Framework;
use ScssPhp\ScssPhp\Exception\SassException;

class Utilities
{
    public static function getThemeConfigs($theme = ''): array|null
    {
        global $CFG;
        if (!file_exists($CFG->dirroot . '/theme/' . $theme . '/config.json')) {
            return null;
        }
        return json_decode(file_get_contents($CFG->dirroot . '/theme/' . $theme . '/config.json'), true);
    }
    public static function getLayoutsByType($theme, $filearea = 'main_layouts', $itemid = 0): array
    {
        $context = context_system::instance();
        $fs = get_file_storage();

        $files = $fs->get_area_files($context->id, $theme, $filearea, $itemid, 'timemodified DESC', false);
        $list = [];
        foreach ($files as $file) {
            if (str_contains($file->get_mimetype(), 'json')) {
                $list[$file->get_filename()] = json_decode($file->get_content(), true);
            }
        }
        return $list;
    }
    public static function getLayouts($theme): array
    {
        $layouts = self::getLayoutsByType('theme_' . $theme);
        $configs = self::getThemeConfigs($theme);
        $default = get_config('theme_'. $theme, 'layout');
        $return = [];
        if (!empty($default)) {
            foreach (Constants::$layouts as $layout) {
                if ($layout !== 'custom') {
                    $return[$layout] = [
                        'file' => 'default.php',
                        'regions' => $configs['layouts'][$layout]['regions'] ?? $configs['regions'],
                        'defaultregion' => $configs['layouts'][$layout]['defaultregion'] ?? $configs['defaultregion'],
                    ];
                    if (isset($configs['layouts'][$layout]['options'])) {
                        $return[$layout]['options'] = $configs['layouts'][$layout]['options'];
                    }
                }
            }
        }
        foreach ($layouts as $name => $layout) {
            if (empty($layout['layout']) || $layout['layout'] === 'custom') {
                $return[pathinfo($name, PATHINFO_FILENAME)] = [
                    'file' => 'default.php',
                    'regions' => $configs['regions'],
                    'defaultregion' => $configs['defaultregion'],
                ];
            } elseif (isset($configs['layouts'][$layout['layout']])) {
                $return[$layout['layout']] = [
                    'file' => 'default.php',
                    'regions' => $configs['layouts'][$layout['layout']]['regions'],
                    'defaultregion' => $configs['layouts'][$layout['layout']]['defaultregion'],
                ];
                if (isset($configs['layouts'][$layout['layout']]['options'])) {
                    $return[$layout['layout']]['options'] = $configs['layouts'][$layout['layout']]['options'];
                }
            } else {
                $return[$layout['layout']] = [
                    'file' => 'default.php',
                    'regions' => $configs['regions'],
                    'defaultregion' => $configs['defaultregion'],
                ];
            }
        }
        return $return;
    }
    /**
     * Lưu cấu hình (key => value) vào database Moodle.
     *
     * @param string $name  Tên config
     * @param mixed  $value Giá trị cần lưu
     * @param string $plugin Tên plugin (vd: local_moon hoặc theme_moon)
     * @return bool
     */
    public static function saveConfig(string $name, $value, string $plugin = 'local_moon'): bool {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        set_config($name, $value, $plugin);
        return true;
    }

    /**
     * Lấy giá trị config.
     */
    public static function getConfig(string $name, string $plugin = 'local_moon', $default = null): mixed {
        $value = get_config($plugin, $name);

        if ($value === null) {
            return $default;
        }

        $decoded = json_decode($value, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $value;
    }

    public static function getFormTemplate($mode = ''): array
    {
        $form_template = array();
        $moonElements = self::getAllMoonElements($mode);
        foreach ($moonElements as $moonElement) {
            $form_template[$moonElement->type] = $moonElement->renderJson('addon');
        }
        if ($mode !== 'article_data') {
            foreach (['section', 'row', 'column', 'sublayout'] as $form_type) {
                $form = new Form($form_type, [], $mode);
                $form_template[$form_type] = $form_type == 'sublayout' ? $form->renderJson('sublayout') : $form->renderJson();
            }
        }
        return $form_template;
    }

    public static function getAllMoonElements($mode = ''): array
    {
        global $CFG;
        $template = Framework::getTheme();
        $template_name = $template->getName();

        // Template Directories
        $elements_dir = $CFG->dirroot . '/local/moon/elements/';
        $template_elements_dir = $CFG->dirroot . '/theme/' . $template_name . '/elements/';

        // Getting Elements from Template Directories
        $elements = self::folders($elements_dir, '.', false, true);

        if ($template_name && file_exists($template_elements_dir)) {
            $template_elements = self::folders($template_elements_dir, '.', false, true);
            // Merging Elements
            $elements = array_merge($elements, $template_elements);
        }

        $return = array();

        foreach ($elements as $element_dir) {
            // String manipulation should be faster than pathinfo() on newer PHP versions.
            $slash = strrpos($element_dir, '/');

            if ($slash === false) {
                continue;
            }

            $type = substr($element_dir, $slash + 1);
            $config_file = $element_dir . '/config.php';
            if (file_exists($config_file)) {
                $element = new Form($type, [], $mode);
                $return[] = $element;
            }
        }
        //exit;
        return $return;
    }

    public static function folders($dir, $filter = '.', $recurse = false, $full = false, $exclude = ['.svn', 'CVS', '.DS_Store', '__MACOSX'], $excludeFilter = ['^\..*']) : array
    {
        $elements = [];
        if (is_dir($dir)) {
            // Compute the excludefilter string
            if (\count($excludeFilter)) {
                $excludeFilterString = '/(' . implode('|', $excludeFilter) . ')/';
            } else {
                $excludeFilterString = '';
            }
            // Get the folders
            $elements = self::_items($dir, $filter, $recurse, $full, $exclude, $excludeFilterString, false);

            // Sort the folders
            asort($elements);

            return array_values($elements);
        }
        return $elements;
    }
    protected static function _items($path, $filter, $recurse, $full, $exclude, $excludeFilterString, $findfiles)
    {
        if (\function_exists('set_time_limit')) {
            set_time_limit(ini_get('max_execution_time'));
        }

        $arr = [];

        // Read the source directory
        if (!($handle = @opendir($path))) {
            return $arr;
        }

        while (($file = readdir($handle)) !== false) {
            if (
                $file != '.' && $file != '..' && !\in_array($file, $exclude)
                && (empty($excludeFilterString) || !preg_match($excludeFilterString, $file))
            ) {
                // Compute the fullpath
                $fullpath = $path . '/' . $file;

                // Compute the isDir flag
                $isDir = is_dir($fullpath);

                if (($isDir xor $findfiles) && preg_match("/$filter/", $file)) {
                    // (fullpath is dir and folders are searched or fullpath is not dir and files are searched) and file matches the filter
                    if ($full) {
                        // Full path is requested
                        $arr[] = $fullpath;
                    } else {
                        // Filename is requested
                        $arr[] = $file;
                    }
                }

                if ($isDir && $recurse) {
                    // Search recursively
                    if (\is_int($recurse)) {
                        // Until depth 0 is reached
                        $arr = array_merge($arr, self::_items($fullpath, $filter, $recurse - 1, $full, $exclude, $excludeFilterString, $findfiles));
                    } else {
                        $arr = array_merge($arr, self::_items($fullpath, $filter, $recurse, $full, $exclude, $excludeFilterString, $findfiles));
                    }
                }
            }
        }

        closedir($handle);

        return $arr;
    }
    public static function getJSONData($name)
    {
        global $CFG;
        $json = file_get_contents($CFG->dirroot . '/local/moon/assets/json/' . $name . '.json');
        return \json_decode($json, true);
    }

    public static function isJsonString($string): bool
    {
        return preg_match('/^\s*(\{.*\}|\[.*\])\s*$/s', $string) === 1;
    }

    /**
     * @throws SassException
     */
    public static function getMoonCss(): string
    {
        global $CFG;
        $scss = new \ScssPhp\ScssPhp\Compiler();
        $scss->setImportPaths($CFG->dirroot . '/local/moon/assets/scss/');
        $content = static::getMoonSCSSVariables() . '@import "style";';
        return $scss->compileString($content)->getCss();
    }

    public static function getMoonSCSSVariables(): string
    {
        $template = Framework::getTheme();
        $variables = $template->getThemeVariables();
        $content = '';
        if (!empty($variables)) {
            $colorMode =   $template->getActualColorMode();
            foreach ($variables as $key => $variable) {
                $result = json_decode($variable);
                if (json_last_error() === JSON_ERROR_NONE) {
                    if (!empty($result->{$colorMode})) {
                        $content .= '$' . $key . ': ' . $result->{$colorMode} . ' !default;';
                    } else {
                        unset($variables[$key]);
                    }
                } else {
                    $content .= '$' . $key . ': ' . $variable . ' !default;';
                }
            }
        }
        return $content;
    }

    public static function typography(): void
    {
        $params = Framework::getTheme()->getParams();
        $customselector = $params->get('custom_typography_selectors', '');
        $logo_type = $params->get('logo_type', 'none');

        $types = array(
            'body' => ['body', '.body'],
            'h1' => ['h1', '.h1'],
            'h2' => ['h2', '.h2'],
            'h3' => ['h3', '.h3'],
            'h4' => ['h4', '.h4'],
            'h5' => ['h5', '.h5'],
            'h6' => ['h6', '.h6'],
            'logo' => ['.moon-logo-text', '.moon-logo-text > a.site-title'],
            'logo_tag_line' => '.moon-logo-text > p.site-tagline',
            'menu' => [
                '.primary-navigation > .navigation li.nav-item'
            ],
            'submenu' => [
                '.nav-submenu-container .nav-submenu > li',
                '.nav-submenu',
                '.moon-mobile-menu .nav-child .menu-go-back',
                '.moon-mobile-menu .nav-child .nav-item-submenu > .as-menu-item',
                '.nav-item-submenu .as-menu-item'
            ],
            'secondmenu' => [
                '.secondary-navigation .navigation .nav-tabs .nav-item'
            ],
            'custom' => $customselector
        );
        $bodyTypography = null;
        foreach ($types as $type => $selector) {
            if (empty($selector)) {
                continue;
            }

            if ($logo_type != 'text' && ($type == 'logo' || $type == 'logo_tag_line')) {
                continue;
            }

            if ($params->exists($type . '_typography')) {
                $status = $params->get($type . '_typography');
            } else {
                $status = $params->get($type . 's_typography');
            }
            if (!empty($status) && trim($status) !== 'custom') {
                continue;
            }
            $typography = $params->get($type . '_typography_options', null);
            if (empty($typography)) {
                continue;
            }
            if ($type == 'body') {
                $bodyTypography = $typography;
            }
            Style::renderTypography($selector, $typography, $bodyTypography, true);
        }
    }

    public static function colors(): void
    {
        $params = Framework::getTheme()->getParams();
        $root = new Style(':root, [data-bs-theme="light"]', '', true);
        $root_dark = new Style('[data-bs-theme="dark"]', '', true);
        // Body
        $body_background_color  =   Style::getColor($params->get('body_background_color', ''));
        $body_text_color        =   Style::getColor($params->get('body_text_color', ''));
        $body_link_color        =   Style::getColor($params->get('body_link_color', ''));
        $body_link_hover_color  =   Style::getColor($params->get('body_link_hover_color', ''));
        $body_heading_color     =   Style::getColor($params->get('body_heading_color', ''));

        $root->addCss('--bs-body-bg', $body_background_color['light']);
        $root->addCss('--bs-body-color', $body_text_color['light']);
        $root->addCss('--bs-link-color', $body_link_color['light']);
        $rgba = self::getRgbaValues($body_link_color['light']);
        if (!empty($rgba)) {
            $root->addCss('--bs-link-color-rgb', $rgba['r'].','.$rgba['g'].','.$rgba['b']);
            $root->addCss('--bs-link-opacity', $rgba['a']);
        }
        $root->addCss('--bs-link-hover-color', $body_link_hover_color['light']);
        $rgba = self::getRgbaValues($body_link_hover_color['light']);
        if (!empty($rgba)) {
            $root->addCss('--bs-link-hover-color-rgb', $rgba['r'].','.$rgba['g'].','.$rgba['b']);
        }

        $root_dark->addCss('--bs-body-bg', $body_background_color['dark']);
        $root_dark->addCss('--bs-body-color', $body_text_color['dark']);
        $root_dark->addCss('--bs-link-color', $body_link_color['dark']);
        $rgba = self::getRgbaValues($body_link_color['dark']);
        if (!empty($rgba)) {
            $root_dark->addCss('--bs-link-color-rgb', $rgba['r'].','.$rgba['g'].','.$rgba['b']);
            $root_dark->addCss('--bs-link-opacity', $rgba['a']);
        }
        $root_dark->addCss('--bs-link-hover-color', $body_link_hover_color['dark']);
        $rgba = self::getRgbaValues($body_link_hover_color['dark']);
        if (!empty($rgba)) {
            $root_dark->addCss('--bs-link-hover-color-rgb', $rgba['r'].','.$rgba['g'].','.$rgba['b']);
        }

        $root->addCss('--bs-heading-color', $body_heading_color['light']);
        $root_dark->addCss('--bs-heading-color', $body_heading_color['dark']);

        // Header
        $header_text_color      =   Style::getColor($params->get('header_text_color', ''));
        $header_bg              =   Style::getColor($params->get('header_bg', ''));
        $header_heading_color   =   Style::getColor($params->get('header_heading_color', ''));
        $header_link_color      =   Style::getColor($params->get('header_link_color', ''));
        $header_link_hover_color=   Style::getColor($params->get('header_link_hover_color', ''));

        $root->addCss('--mf-header-text-color', $header_text_color['light']);
        $root->addCss('--mf-header-heading-color', $header_heading_color['light']);
        $root->addCss('--mf-header-link-color', $header_link_color['light']);
        $root->addCss('--mf-header-link-hover-color', $header_link_hover_color['light']);

        $root_dark->addCss('--mf-header-text-color', $header_text_color['dark']);
        $root_dark->addCss('--mf-header-heading-color', $header_heading_color['dark']);
        $root_dark->addCss('--mf-header-link-color', $header_link_color['dark']);
        $root_dark->addCss('--mf-header-link-hover-color', $header_link_hover_color['dark']);

        $root->addCss('--mf-header-bg', $header_bg['light']);
        $root_dark->addCss('--mf-header-bg', $header_bg['dark']);

        // Sticky Header
        $stick_header_bg_color              =   Style::getColor($params->get('stick_header_bg_color', ''));
        $stick_header_menu_link_color       =   Style::getColor($params->get('stick_header_menu_link_color', ''));
        $stick_header_menu_link_hover_color =   Style::getColor($params->get('stick_header_menu_link_hover_color', ''));
        $stick_header_menu_link_active_color=   Style::getColor($params->get('stick_header_menu_link_active_color', ''));

        $root->addCss('--mf-stick-header-bg-color', $stick_header_bg_color['light']);
        $root->addCss('--mf-stick-header-menu-link-color', $stick_header_menu_link_color['light']);
        $root->addCss('--mf-stick-header-menu-link-hover-color', $stick_header_menu_link_hover_color['light']);
        $root->addCss('--mf-stick-header-menu-link-active-color', $stick_header_menu_link_active_color['light']);

        $root_dark->addCss('--mf-stick-header-bg-color', $stick_header_bg_color['dark']);
        $root_dark->addCss('--mf-stick-header-menu-link-color', $stick_header_menu_link_color['dark']);
        $root_dark->addCss('--mf-stick-header-menu-link-hover-color', $stick_header_menu_link_hover_color['dark']);
        $root_dark->addCss('--mf-stick-header-menu-link-active-color', $stick_header_menu_link_active_color['dark']);

        // Menu
        $main_menu_link_color           =   Style::getColor($params->get('main_menu_link_color', ''));
        $main_menu_link_background      =   Style::getColor($params->get('main_menu_link_background', ''));
        $main_menu_link_hover_color     =   Style::getColor($params->get('main_menu_link_hover_color', ''));
        $main_menu_link_active_color    =   Style::getColor($params->get('main_menu_link_active_color', ''));
        $main_menu_active_background    =   Style::getColor($params->get('main_menu_active_background', ''));
        $main_menu_hover_background     =   Style::getColor($params->get('main_menu_hover_background', ''));

        $root->addCss('--mf-main-menu-link-color', $main_menu_link_color['light']);
        $root->addCss('--mf-main-menu-link-background', $main_menu_link_background['light']);
        $root->addCss('--mf-main-menu-link-hover-color', $main_menu_link_hover_color['light']);
        $root->addCss('--mf-main-menu-hover-background', $main_menu_hover_background['light']);
        $root->addCss('--mf-main-menu-link-active-color', $main_menu_link_active_color['light']);
        $root->addCss('--mf-main-menu-active-background', $main_menu_active_background['light']);

        $root_dark->addCss('--mf-main-menu-link-color', $main_menu_link_color['dark']);
        $root_dark->addCss('--mf-main-menu-link-background', $main_menu_link_background['dark']);
        $root_dark->addCss('--mf-main-menu-link-hover-color', $main_menu_link_hover_color['dark']);
        $root_dark->addCss('--mf-main-menu-hover-background', $main_menu_hover_background['dark']);
        $root_dark->addCss('--mf-main-menu-link-active-color', $main_menu_link_active_color['dark']);
        $root_dark->addCss('--mf-main-menu-active-background', $main_menu_active_background['dark']);

        // Dropdown Menu
        $dropdown_bg_color  =   Style::getColor($params->get('dropdown_bg_color', ''));
        $dropdown_link_color            =   Style::getColor($params->get('dropdown_link_color', ''));
        $dropdown_menu_link_hover_color =   Style::getColor($params->get('dropdown_menu_link_hover_color', ''));
        $dropdown_menu_hover_bg_color   =   Style::getColor($params->get('dropdown_menu_hover_bg_color', ''));
        $dropdown_menu_active_bg_color  =   Style::getColor($params->get('dropdown_menu_active_bg_color', ''));
        $dropdown_menu_active_link_color=   Style::getColor($params->get('dropdown_menu_active_link_color', ''));

        $root->addCss('--mf-dropdown-bg-color', $dropdown_bg_color['light']);
        $root_dark->addCss('--mf-dropdown-bg-color', $dropdown_bg_color['dark']);

        $root->addCss('--mf-dropdown-link-color', $dropdown_link_color['light']);
        $root_dark->addCss('--mf-dropdown-link-color', $dropdown_link_color['dark']);

        $root->addCss('--mf-dropdown-menu-link-hover-color', $dropdown_menu_link_hover_color['light']);
        $root_dark->addCss('--mf-dropdown-menu-link-hover-color', $dropdown_menu_link_hover_color['dark']);

        $root->addCss('--mf-dropdown-menu-hover-bg-color', $dropdown_menu_hover_bg_color['light']);
        $root_dark->addCss('--mf-dropdown-menu-hover-bg-color', $dropdown_menu_hover_bg_color['dark']);

        $root->addCss('--mf-dropdown-menu-active-link-color', $dropdown_menu_active_link_color['light']);
        $root_dark->addCss('--mf-dropdown-menu-active-link-color', $dropdown_menu_active_link_color['dark']);

        $root->addCss('--mf-dropdown-menu-active-bg-color', $dropdown_menu_active_bg_color['light']);
        $root_dark->addCss('--mf-dropdown-menu-active-bg-color', $dropdown_menu_active_bg_color['dark']);

        // Sticky Menu
        $stick_header_mobile_menu_icon_color = Style::getColor($params->get('stick_header_mobile_menu_icon_color', ''));
        $root->addCss('--mf-stick-header-mobile-menu-icon-color', $stick_header_mobile_menu_icon_color['light']);
        $root_dark->addCss('--mf-stick-header-mobile-menu-icon-color', $stick_header_mobile_menu_icon_color['dark']);

        // Offcanvas Menu
        $offcanvas_background_color = Style::getColor($params->get('offcanvas_backgroundcolor', ''));
        $offcanvas_link_color = Style::getColor($params->get('offcanvas_link_color', ''));
        $offcanvas_menu_text_color = Style::getColor($params->get('offcanvas_text_color', ''));
        $offcanvas_link_hover_color = Style::getColor($params->get('offcanvas_link_hover_color', ''));
        $offcanvas_active_link_color = Style::getColor($params->get('offcanvas_active_link_color', ''));
        $offcanvas_icon_color = Style::getColor($params->get('offcanvas_icon_color', ''));
        $offcanvas_heading_color = Style::getColor($params->get('offcanvas_heading_color', ''));

        $root->addCss('--mf-offcanvas-text-color', $offcanvas_menu_text_color['light']);
        $root->child('.drawer.drawer-right')->addCss('--bs-body-color', $offcanvas_menu_text_color['light']);
        $root->addCss('--mf-offcanvas-backgroundcolor', $offcanvas_background_color['light']);
        $root->addCss('--mf-offcanvas-link-color', $offcanvas_link_color['light']);
        $root->addCss('--mf-offcanvas-link-hover-color', $offcanvas_link_hover_color['light']);
        $root->addCss('--mf-offcanvas-active-link-color', $offcanvas_active_link_color['light']);
        $root->addCss('--mf-offcanvas-heading-color', $offcanvas_heading_color['light']);

        $root_dark->addCss('--mf-offcanvas-text-color', $offcanvas_menu_text_color['dark']);
        $root_dark->child('.drawer.drawer-right')->addCss('--bs-body-color', $offcanvas_menu_text_color['dark']);
        $root_dark->addCss('--mf-offcanvas-backgroundcolor', $offcanvas_background_color['dark']);
        $root_dark->addCss('--mf-offcanvas-link-color', $offcanvas_link_color['dark']);
        $root_dark->addCss('--mf-offcanvas-link-hover-color', $offcanvas_link_hover_color['dark']);
        $root_dark->addCss('--mf-offcanvas-active-link-color', $offcanvas_active_link_color['dark']);
        $root_dark->addCss('--mf-offcanvas-heading-color', $offcanvas_heading_color['dark']);

        $root->addCss('--mf-offcanvas-toggle-color', $offcanvas_icon_color['light']);
        $root_dark->addCss('--mf-offcanvas-toggle-color', $offcanvas_icon_color['dark']);

        // Mobile Menu
        $mobilemenu_background_color = Style::getColor($params->get('mobilemenu_backgroundcolor', ''));
        $mobilemenu_link_color = Style::getColor($params->get('mobilemenu_menu_link_color', ''));
        $mobilemenu_menu_text_color = Style::getColor($params->get('mobilemenu_menu_text_color', ''));
        $mobilemenu_hover_background_color = Style::getColor($params->get('mobilemenu_hover_background_color', ''));
        $mobilemenu_active_link_color = Style::getColor($params->get('mobilemenu_menu_active_link_color', ''));
        $mobilemenu_active_background_color = Style::getColor($params->get('mobilemenu_menu_active_bg_color', ''));
        $mobilemenu_menu_icon_color = Style::getColor($params->get('mobilemenu_menu_icon_color', ''));
        $mobilemenu_menu_active_icon_color = Style::getColor($params->get('mobilemenu_menu_active_icon_color', ''));

        $root->addCss('--mf-mobilemenu-backgroundcolor', $mobilemenu_background_color['light']);
        $root->addCss('--mf-mobilemenu-menu-text-color', $mobilemenu_menu_text_color['light']);
        $root->addCss('--mf-mobilemenu-menu-link-color', $mobilemenu_link_color['light']);
        $root->addCss('--mf-mobilemenu-hover-background-color', $mobilemenu_hover_background_color['light']);
        $root->addCss('--mf-mobilemenu-menu-active-link-color', $mobilemenu_active_link_color['light']);
        $root->addCss('--mf-mobilemenu-menu-active-bg-color', $mobilemenu_active_background_color['light']);

        $root_dark->addCss('--mf-mobilemenu-backgroundcolor', $mobilemenu_background_color['dark']);
        $root_dark->addCss('--mf-mobilemenu-menu-text-color', $mobilemenu_menu_text_color['dark']);
        $root_dark->addCss('--mf-mobilemenu-menu-link-color', $mobilemenu_link_color['dark']);
        $root_dark->addCss('--mf-mobilemenu-hover-background-color', $mobilemenu_hover_background_color['dark']);
        $root_dark->addCss('--mf-mobilemenu-menu-active-link-color', $mobilemenu_active_link_color['dark']);
        $root_dark->addCss('--mf-mobilemenu-menu-active-bg-color', $mobilemenu_active_background_color['dark']);

        $root->addCss('--mf-mobilemenu-menu-icon-color', $mobilemenu_menu_icon_color['light']);
        $root_dark->addCss('--mf-mobilemenu-menu-icon-color', $mobilemenu_menu_icon_color['dark']);

        $root->addCss('--mf-mobilemenu-menu-active-icon-color', $mobilemenu_menu_active_icon_color['light']);
        $root_dark->addCss('--mf-mobilemenu-menu-active-icon-color', $mobilemenu_menu_active_icon_color['dark']);

        // Contact Icon
        $contact_icon_color     =   Style::getColor($params->get('icon_color', ''));
        $root->addCss('--mf-contact-info-icon-color', $contact_icon_color['light']);
        $root_dark->addCss('--mf-contact-info-icon-color', $contact_icon_color['dark']);

        $root->render();
        $root_dark->render();
    }

    public static function backToTop() : bool {
        $params = Framework::getTheme()->getParams();
        $enable_backtotop = $params->get('backtotop', 1);
        if (!$enable_backtotop) {
            return false;
        }

        $backtotop_icon_size    = $params->get('backtotop_icon_size', 20);
        $backtotop_icon_padding = $params->get('backtotop_icon_padding', 10);
        $backtotop_icon_color   = Style::getColor($params->get('backtotop_icon_color', ''));
        $backtotop_icon_bgcolor = Style::getColor($params->get('backtotop_icon_bgcolor', ''));
        $backtotop_icon_style   = $params->get('backtotop_icon_style', 'circle');

        $a_style        =   new Style('#moon-backtotop', '', true);
        $a_style_dark   =   new Style('#moon-backtotop', 'dark', true);
        $i_style        =   new Style('#moon-backtotop > i', '', true);
        $i_style_dark   =   new Style('#moon-backtotop > i', 'dark', true);
        $i_style->addResponsiveCSS('font-size', $backtotop_icon_size , 'px');
        $i_style->addCss('color', $backtotop_icon_color['light']);
        $i_style_dark->addCss('color', $backtotop_icon_color['dark']);

        if ($backtotop_icon_style == 'rounded') {
            $border_radius          = ($backtotop_icon_padding * 2 + $backtotop_icon_size) * 0.1;
            $a_style->addCss('border-radius', round($border_radius) . 'px !important');
        }

        $i_style->addResponsiveCSS('width', $backtotop_icon_size , 'px');
        $i_style->addResponsiveCSS('height', $backtotop_icon_size , 'px');
        $i_style->addResponsiveCSS('line-height', $backtotop_icon_size , 'px');
        $i_style->addCss('text-align', 'center');

        $a_style->addCss('background', $backtotop_icon_bgcolor['light']);
        $a_style_dark->addCss('background', $backtotop_icon_bgcolor['dark']);
        $a_style->addResponsiveCSS('padding', $backtotop_icon_padding , 'px');
        $border = json_decode($params->get('backtotop_border_style', ''), true);
        if (!empty($border)) {
            $a_style->addBorder($border);
        }

        $a_style->render();
        $i_style->render();
        $a_style_dark->render();
        $i_style_dark->render();

        return true;
    }

    public static function favicon(): void
    {
        global $CFG;
        $params = Framework::getTheme()->getParams();
        $document = Framework::getDocument();
        $apple_touch_icon = $params->get('apple_touch_icon', '');
        if (!empty($apple_touch_icon)) {
            $image_type =   getimagesize($apple_touch_icon);
            $document->addLink($apple_touch_icon, 'apple-touch-icon', ['type' => $image_type['mime'], 'sizes' => 'any']);
        }

        $site_webmanifest = $params->get('site_webmanifest', '');
        if (!empty($site_webmanifest)) {
            if ( (strpos( $site_webmanifest, 'http://' ) !== false) || (strpos( $site_webmanifest, 'https://' ) !== false) ) {
                $site_webmanifest = $params->get('site_webmanifest', '');
            } else {
                $site_webmanifest = $CFG->wwwroot . $params->get('site_webmanifest', '');
            }
            $document->addLink($site_webmanifest, 'manifest', ['type' => 'application/json', 'crossorigin' => 'use-credentials']);
        }
    }

    public static function preloader(): void
    {
        $params = Framework::getTheme()->getParams();
        $document = Framework::getDocument();

        $enable_preloader = $params->get('preloader', 1);
        if (!$enable_preloader) {
            return;
        }

        $preloader_setting = $params->get('preloader_setting', 'animations');
        $preloader_animation = $params->get('preloader_animation', 'circle');
        $preloader_size = $params->get('preloader_size', 40);
        $preloader_color = Style::getColor($params->get('preloader_color', ''));
        $preloader_bgcolor = Style::getColor($params->get('preloader_bgcolor', ''));
        $preloaderStyles='';
        if($preloader_setting == "animation"){
            switch ($preloader_animation) {
                case 'rotating-plane':
                    $preloaderStyles .= '.sk-rotating-plane{width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;background-color:' . $preloader_color['light'] . ';margin:0 auto;-webkit-animation:sk-rotatePlane 1.2s infinite ease-in-out;animation:sk-rotatePlane 1.2s infinite ease-in-out}@-webkit-keyframes sk-rotatePlane{0%{-webkit-transform:perspective(120px) rotateX(0) rotateY(0);transform:perspective(120px) rotateX(0) rotateY(0)}50%{-webkit-transform:perspective(120px) rotateX(-180.1deg) rotateY(0);transform:perspective(120px) rotateX(-180.1deg) rotateY(0)}100%{-webkit-transform:perspective(120px) rotateX(-180deg) rotateY(-179.9deg);transform:perspective(120px) rotateX(-180deg) rotateY(-179.9deg)}}@keyframes sk-rotatePlane{0%{-webkit-transform:perspective(120px) rotateX(0) rotateY(0);transform:perspective(120px) rotateX(0) rotateY(0)}50%{-webkit-transform:perspective(120px) rotateX(-180.1deg) rotateY(0);transform:perspective(120px) rotateX(-180.1deg) rotateY(0)}100%{-webkit-transform:perspective(120px) rotateX(-180deg) rotateY(-179.9deg);transform:perspective(120px) rotateX(-180deg) rotateY(-179.9deg)}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-rotating-plane{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'double-bounce':
                    $preloaderStyles .= '.sk-double-bounce{width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;position:relative;margin:0 auto}.sk-double-bounce .sk-child{width:100%;height:100%;border-radius:50%;background-color:' . $preloader_color['light'] . ';opacity:.6;position:absolute;top:0;left:0;-webkit-animation:sk-doubleBounce 2s infinite ease-in-out;animation:sk-doubleBounce 2s infinite ease-in-out}.sk-double-bounce .sk-double-bounce2{-webkit-animation-delay:-1s;animation-delay:-1s}@-webkit-keyframes sk-doubleBounce{0%,100%{-webkit-transform:scale(0);transform:scale(0)}50%{-webkit-transform:scale(1);transform:scale(1)}}@keyframes sk-doubleBounce{0%,100%{-webkit-transform:scale(0);transform:scale(0)}50%{-webkit-transform:scale(1);transform:scale(1)}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-double-bounce .sk-child{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'wave':
                    $preloaderStyles .= '.sk-wave{margin:0 auto;width:50px;height:' . $preloader_size . 'px;text-align:center;font-size:10px}.sk-wave .sk-rect{background-color:' . $preloader_color['light'] . ';height:100%;width:6px;display:inline-block;-webkit-animation:sk-waveStretchDelay 1.2s infinite ease-in-out;animation:sk-waveStretchDelay 1.2s infinite ease-in-out}.sk-wave .sk-rect1{-webkit-animation-delay:-1.2s;animation-delay:-1.2s}.sk-wave .sk-rect2{-webkit-animation-delay:-1.1s;animation-delay:-1.1s}.sk-wave .sk-rect3{-webkit-animation-delay:-1s;animation-delay:-1s}.sk-wave .sk-rect4{-webkit-animation-delay:-.9s;animation-delay:-.9s}.sk-wave .sk-rect5{-webkit-animation-delay:-.8s;animation-delay:-.8s}@-webkit-keyframes sk-waveStretchDelay{0%,100%,40%{-webkit-transform:scaleY(.4);transform:scaleY(.4)}20%{-webkit-transform:scaleY(1);transform:scaleY(1)}}@keyframes sk-waveStretchDelay{0%,100%,40%{-webkit-transform:scaleY(.4);transform:scaleY(.4)}20%{-webkit-transform:scaleY(1);transform:scaleY(1)}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-wave .sk-rect{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'wandering-cubes':
                    $preloaderStyles .= '.sk-wandering-cubes{margin:0 auto;width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;position:relative}.sk-wandering-cubes .sk-cube{background-color:' . $preloader_color['light'] . ';width:10px;height:10px;position:absolute;top:0;left:0;-webkit-animation:sk-wanderingCube 1.8s ease-in-out -1.8s infinite both;animation:sk-wanderingCube 1.8s ease-in-out -1.8s infinite both}.sk-wandering-cubes .sk-cube2{-webkit-animation-delay:-.9s;animation-delay:-.9s}@-webkit-keyframes sk-wanderingCube{0%{-webkit-transform:rotate(0);transform:rotate(0)}25%{-webkit-transform:translateX(30px) rotate(-90deg) scale(.5);transform:translateX(30px) rotate(-90deg) scale(.5)}50%{-webkit-transform:translateX(30px) translateY(30px) rotate(-179deg);transform:translateX(30px) translateY(30px) rotate(-179deg)}50.1%{-webkit-transform:translateX(30px) translateY(30px) rotate(-180deg);transform:translateX(30px) translateY(30px) rotate(-180deg)}75%{-webkit-transform:translateX(0) translateY(30px) rotate(-270deg) scale(.5);transform:translateX(0) translateY(30px) rotate(-270deg) scale(.5)}100%{-webkit-transform:rotate(-360deg);transform:rotate(-360deg)}}@keyframes sk-wanderingCube{0%{-webkit-transform:rotate(0);transform:rotate(0)}25%{-webkit-transform:translateX(30px) rotate(-90deg) scale(.5);transform:translateX(30px) rotate(-90deg) scale(.5)}50%{-webkit-transform:translateX(30px) translateY(30px) rotate(-179deg);transform:translateX(30px) translateY(30px) rotate(-179deg)}50.1%{-webkit-transform:translateX(30px) translateY(30px) rotate(-180deg);transform:translateX(30px) translateY(30px) rotate(-180deg)}75%{-webkit-transform:translateX(0) translateY(30px) rotate(-270deg) scale(.5);transform:translateX(0) translateY(30px) rotate(-270deg) scale(.5)}100%{-webkit-transform:rotate(-360deg);transform:rotate(-360deg)}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-wandering-cubes .sk-cube{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'pulse':
                    $preloaderStyles .= '.sk-spinner-pulse{width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;margin:0 auto;background-color:' . $preloader_color['light'] . ';border-radius:100%;-webkit-animation:sk-pulseScaleOut 1s infinite ease-in-out;animation:sk-pulseScaleOut 1s infinite ease-in-out}@-webkit-keyframes sk-pulseScaleOut{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}@keyframes sk-pulseScaleOut{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-spinner-pulse{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'chasing-dots':
                    $preloaderStyles .= '.sk-chasing-dots{margin:0 auto;width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;position:relative;text-align:center;-webkit-animation:sk-chasingDotsRotate 2s infinite linear;animation:sk-chasingDotsRotate 2s infinite linear}.sk-chasing-dots .sk-child{width:60%;height:60%;display:inline-block;position:absolute;top:0;background-color:' . $preloader_color['light'] . ';border-radius:100%;-webkit-animation:sk-chasingDotsBounce 2s infinite ease-in-out;animation:sk-chasingDotsBounce 2s infinite ease-in-out}.sk-chasing-dots .sk-dot2{top:auto;bottom:0;-webkit-animation-delay:-1s;animation-delay:-1s}@-webkit-keyframes sk-chasingDotsRotate{100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@keyframes sk-chasingDotsRotate{100%{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}@-webkit-keyframes sk-chasingDotsBounce{0%,100%{-webkit-transform:scale(0);transform:scale(0)}50%{-webkit-transform:scale(1);transform:scale(1)}}@keyframes sk-chasingDotsBounce{0%,100%{-webkit-transform:scale(0);transform:scale(0)}50%{-webkit-transform:scale(1);transform:scale(1)}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-chasing-dots .sk-child{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'three-bounce':
                    $preloaderStyles .= '.sk-three-bounce{margin:0 auto;width:80px;text-align:center}.sk-three-bounce .sk-child{width:20px;height:20px;background-color:' . $preloader_color['light'] . ';border-radius:100%;display:inline-block;-webkit-animation:sk-three-bounce 1.4s ease-in-out 0s infinite both;animation:sk-three-bounce 1.4s ease-in-out 0s infinite both}.sk-three-bounce .sk-bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.sk-three-bounce .sk-bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes sk-three-bounce{0%,100%,80%{-webkit-transform:scale(0);transform:scale(0)}40%{-webkit-transform:scale(1);transform:scale(1)}}@keyframes sk-three-bounce{0%,100%,80%{-webkit-transform:scale(0);transform:scale(0)}40%{-webkit-transform:scale(1);transform:scale(1)}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-three-bounce .sk-child{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'circle':
                    $preloaderStyles .= '.sk-circle{margin:0 auto;width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;position:relative}.sk-circle .sk-child{width:100%;height:100%;position:absolute;left:0;top:0}.sk-circle .sk-child:before{content:"";display:block;margin:0 auto;width:15%;height:15%;background-color:' . $preloader_color['light'] . ';border-radius:100%;-webkit-animation:sk-circleBounceDelay 1.2s infinite ease-in-out both;animation:sk-circleBounceDelay 1.2s infinite ease-in-out both}.sk-circle .sk-circle2{-webkit-transform:rotate(30deg);-ms-transform:rotate(30deg);transform:rotate(30deg)}.sk-circle .sk-circle3{-webkit-transform:rotate(60deg);-ms-transform:rotate(60deg);transform:rotate(60deg)}.sk-circle .sk-circle4{-webkit-transform:rotate(90deg);-ms-transform:rotate(90deg);transform:rotate(90deg)}.sk-circle .sk-circle5{-webkit-transform:rotate(120deg);-ms-transform:rotate(120deg);transform:rotate(120deg)}.sk-circle .sk-circle6{-webkit-transform:rotate(150deg);-ms-transform:rotate(150deg);transform:rotate(150deg)}.sk-circle .sk-circle7{-webkit-transform:rotate(180deg);-ms-transform:rotate(180deg);transform:rotate(180deg)}.sk-circle .sk-circle8{-webkit-transform:rotate(210deg);-ms-transform:rotate(210deg);transform:rotate(210deg)}.sk-circle .sk-circle9{-webkit-transform:rotate(240deg);-ms-transform:rotate(240deg);transform:rotate(240deg)}.sk-circle .sk-circle10{-webkit-transform:rotate(270deg);-ms-transform:rotate(270deg);transform:rotate(270deg)}.sk-circle .sk-circle11{-webkit-transform:rotate(300deg);-ms-transform:rotate(300deg);transform:rotate(300deg)}.sk-circle .sk-circle12{-webkit-transform:rotate(330deg);-ms-transform:rotate(330deg);transform:rotate(330deg)}.sk-circle .sk-circle2:before{-webkit-animation-delay:-1.1s;animation-delay:-1.1s}.sk-circle .sk-circle3:before{-webkit-animation-delay:-1s;animation-delay:-1s}.sk-circle .sk-circle4:before{-webkit-animation-delay:-.9s;animation-delay:-.9s}.sk-circle .sk-circle5:before{-webkit-animation-delay:-.8s;animation-delay:-.8s}.sk-circle .sk-circle6:before{-webkit-animation-delay:-.7s;animation-delay:-.7s}.sk-circle .sk-circle7:before{-webkit-animation-delay:-.6s;animation-delay:-.6s}.sk-circle .sk-circle8:before{-webkit-animation-delay:-.5s;animation-delay:-.5s}.sk-circle .sk-circle9:before{-webkit-animation-delay:-.4s;animation-delay:-.4s}.sk-circle .sk-circle10:before{-webkit-animation-delay:-.3s;animation-delay:-.3s}.sk-circle .sk-circle11:before{-webkit-animation-delay:-.2s;animation-delay:-.2s}.sk-circle .sk-circle12:before{-webkit-animation-delay:-.1s;animation-delay:-.1s}@-webkit-keyframes sk-circleBounceDelay{0%,100%,80%{-webkit-transform:scale(0);transform:scale(0)}40%{-webkit-transform:scale(1);transform:scale(1)}}@keyframes sk-circleBounceDelay{0%,100%,80%{-webkit-transform:scale(0);transform:scale(0)}40%{-webkit-transform:scale(1);transform:scale(1)}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-circle .sk-child:before{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'cube-grid':
                    $preloaderStyles .= '.sk-cube-grid{width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;margin:0 auto}.sk-cube-grid .sk-cube{width:33.33%;height:33.33%;background-color:' . $preloader_color['light'] . ';float:left;-webkit-animation:sk-cubeGridScaleDelay 1.3s infinite ease-in-out;animation:sk-cubeGridScaleDelay 1.3s infinite ease-in-out}.sk-cube-grid .sk-cube1{-webkit-animation-delay:.2s;animation-delay:.2s}.sk-cube-grid .sk-cube2{-webkit-animation-delay:.3s;animation-delay:.3s}.sk-cube-grid .sk-cube3{-webkit-animation-delay:.4s;animation-delay:.4s}.sk-cube-grid .sk-cube4{-webkit-animation-delay:.1s;animation-delay:.1s}.sk-cube-grid .sk-cube5{-webkit-animation-delay:.2s;animation-delay:.2s}.sk-cube-grid .sk-cube6{-webkit-animation-delay:.3s;animation-delay:.3s}.sk-cube-grid .sk-cube7{-webkit-animation-delay:0ms;animation-delay:0ms}.sk-cube-grid .sk-cube8{-webkit-animation-delay:.1s;animation-delay:.1s}.sk-cube-grid .sk-cube9{-webkit-animation-delay:.2s;animation-delay:.2s}@-webkit-keyframes sk-cubeGridScaleDelay{0%,100%,70%{-webkit-transform:scale3D(1,1,1);transform:scale3D(1,1,1)}35%{-webkit-transform:scale3D(0,0,1);transform:scale3D(0,0,1)}}@keyframes sk-cubeGridScaleDelay{0%,100%,70%{-webkit-transform:scale3D(1,1,1);transform:scale3D(1,1,1)}35%{-webkit-transform:scale3D(0,0,1);transform:scale3D(0,0,1)}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-cube-grid .sk-cube{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'fading-circle':
                    $preloaderStyles .= '.sk-fading-circle{margin:0 auto;width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;position:relative}.sk-fading-circle .sk-circle{width:100%;height:100%;position:absolute;left:0;top:0}.sk-fading-circle .sk-circle:before{content:"";display:block;margin:0 auto;width:15%;height:15%;background-color:' . $preloader_color['light'] . ';border-radius:100%;-webkit-animation:sk-circleFadeDelay 1.2s infinite ease-in-out both;animation:sk-circleFadeDelay 1.2s infinite ease-in-out both}.sk-fading-circle .sk-circle2{-webkit-transform:rotate(30deg);-ms-transform:rotate(30deg);transform:rotate(30deg)}.sk-fading-circle .sk-circle3{-webkit-transform:rotate(60deg);-ms-transform:rotate(60deg);transform:rotate(60deg)}.sk-fading-circle .sk-circle4{-webkit-transform:rotate(90deg);-ms-transform:rotate(90deg);transform:rotate(90deg)}.sk-fading-circle .sk-circle5{-webkit-transform:rotate(120deg);-ms-transform:rotate(120deg);transform:rotate(120deg)}.sk-fading-circle .sk-circle6{-webkit-transform:rotate(150deg);-ms-transform:rotate(150deg);transform:rotate(150deg)}.sk-fading-circle .sk-circle7{-webkit-transform:rotate(180deg);-ms-transform:rotate(180deg);transform:rotate(180deg)}.sk-fading-circle .sk-circle8{-webkit-transform:rotate(210deg);-ms-transform:rotate(210deg);transform:rotate(210deg)}.sk-fading-circle .sk-circle9{-webkit-transform:rotate(240deg);-ms-transform:rotate(240deg);transform:rotate(240deg)}.sk-fading-circle .sk-circle10{-webkit-transform:rotate(270deg);-ms-transform:rotate(270deg);transform:rotate(270deg)}.sk-fading-circle .sk-circle11{-webkit-transform:rotate(300deg);-ms-transform:rotate(300deg);transform:rotate(300deg)}.sk-fading-circle .sk-circle12{-webkit-transform:rotate(330deg);-ms-transform:rotate(330deg);transform:rotate(330deg)}.sk-fading-circle .sk-circle2:before{-webkit-animation-delay:-1.1s;animation-delay:-1.1s}.sk-fading-circle .sk-circle3:before{-webkit-animation-delay:-1s;animation-delay:-1s}.sk-fading-circle .sk-circle4:before{-webkit-animation-delay:-.9s;animation-delay:-.9s}.sk-fading-circle .sk-circle5:before{-webkit-animation-delay:-.8s;animation-delay:-.8s}.sk-fading-circle .sk-circle6:before{-webkit-animation-delay:-.7s;animation-delay:-.7s}.sk-fading-circle .sk-circle7:before{-webkit-animation-delay:-.6s;animation-delay:-.6s}.sk-fading-circle .sk-circle8:before{-webkit-animation-delay:-.5s;animation-delay:-.5s}.sk-fading-circle .sk-circle9:before{-webkit-animation-delay:-.4s;animation-delay:-.4s}.sk-fading-circle .sk-circle10:before{-webkit-animation-delay:-.3s;animation-delay:-.3s}.sk-fading-circle .sk-circle11:before{-webkit-animation-delay:-.2s;animation-delay:-.2s}.sk-fading-circle .sk-circle12:before{-webkit-animation-delay:-.1s;animation-delay:-.1s}@-webkit-keyframes sk-circleFadeDelay{0%,100%,39%{opacity:0}40%{opacity:1}}@keyframes sk-circleFadeDelay{0%,100%,39%{opacity:0}40%{opacity:1}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-fading-circle .sk-circle:before{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'folding-cube':
                    $preloaderStyles .= '.sk-folding-cube{margin:0 auto;width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;position:relative;-webkit-transform:rotateZ(45deg);transform:rotateZ(45deg)}.sk-folding-cube .sk-cube{float:left;width:50%;height:50%;position:relative;-webkit-transform:scale(1.1);-ms-transform:scale(1.1);transform:scale(1.1)}.sk-folding-cube .sk-cube:before{content:"";position:absolute;top:0;left:0;width:100%;height:100%;background-color:' . $preloader_color['light'] . ';-webkit-animation:sk-foldCubeAngle 2.4s infinite linear both;animation:sk-foldCubeAngle 2.4s infinite linear both;-webkit-transform-origin:100% 100%;-ms-transform-origin:100% 100%;transform-origin:100% 100%}.sk-folding-cube .sk-cube2{-webkit-transform:scale(1.1) rotateZ(90deg);transform:scale(1.1) rotateZ(90deg)}.sk-folding-cube .sk-cube3{-webkit-transform:scale(1.1) rotateZ(180deg);transform:scale(1.1) rotateZ(180deg)}.sk-folding-cube .sk-cube4{-webkit-transform:scale(1.1) rotateZ(270deg);transform:scale(1.1) rotateZ(270deg)}.sk-folding-cube .sk-cube2:before{-webkit-animation-delay:.3s;animation-delay:.3s}.sk-folding-cube .sk-cube3:before{-webkit-animation-delay:.6s;animation-delay:.6s}.sk-folding-cube .sk-cube4:before{-webkit-animation-delay:.9s;animation-delay:.9s}@-webkit-keyframes sk-foldCubeAngle{0%,10%{-webkit-transform:perspective(140px) rotateX(-180deg);transform:perspective(140px) rotateX(-180deg);opacity:0}25%,75%{-webkit-transform:perspective(140px) rotateX(0);transform:perspective(140px) rotateX(0);opacity:1}100%,90%{-webkit-transform:perspective(140px) rotateY(180deg);transform:perspective(140px) rotateY(180deg);opacity:0}}@keyframes sk-foldCubeAngle{0%,10%{-webkit-transform:perspective(140px) rotateX(-180deg);transform:perspective(140px) rotateX(-180deg);opacity:0}25%,75%{-webkit-transform:perspective(140px) rotateX(0);transform:perspective(140px) rotateX(0);opacity:1}100%,90%{-webkit-transform:perspective(140px) rotateY(180deg);transform:perspective(140px) rotateY(180deg);opacity:0}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .sk-folding-cube .sk-cube:before{background-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'bouncing-loader':
                    $preloaderStyles .= '.bouncing-loader{display:flex;justify-content:center;margin: 0 auto;}.bouncing-loader>div{width:' . $preloader_size . 'px;height:' . $preloader_size . 'px;margin:1rem 0.2rem 0;background:' . $preloader_color['light'] . ';border-radius:50%;animation:bouncing-loader 0.6s infinite alternate;}.bouncing-loader>div:nth-child(2){animation-delay:0.2s;}.bouncing-loader>div:nth-child(3){animation-delay:0.4s;}@keyframes bouncing-loader{to{opacity:0.1;transform:translate3d(0, -1rem, 0);}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .bouncing-loader>div{background:' . $preloader_color['dark'] . ';}';
                    break;
                case 'donut':
                    $preloaderStyles .= '@keyframes donut-spin{ 0% { transform:rotate(0deg); } 100% { transform:rotate(360deg); } } .donut {display:inline-block;border:4px solid rgba(0, 0, 0, 0.1);border-left-color:' . $preloader_color['light'] . ';border-radius:50%;margin:0 auto;width: ' . $preloader_size . 'px;height: ' . $preloader_size . 'px;animation:donut-spin 1.2s linear infinite;}';
                    $preloaderStyles .= '[data-bs-theme=dark] .donut {border-left-color:' . $preloader_color['dark'] . ';}';
                    break;
                case 'triple-spinner':
                    $preloaderStyles .= '.triple-spinner {display: block;position: relative;width: ' . $preloader_size . 'px;height: ' . $preloader_size . 'px;border-radius: 50%;border: 2px solid transparent;border-top: 2px solid ' . $preloader_color['light'] . ';border-left: 2px solid ' . $preloader_color['light'] . ';-webkit-animation: preload-spin 2s linear infinite;animation: preload-spin 2s linear infinite;}.triple-spinner::before, .triple-spinner::after {content: "";position: absolute;border-radius: 50%;border: 2px solid transparent;}.triple-spinner::before {opacity: 0.85;top: 8%;left: 8%;right: 8%;bottom: 8%;border-top-color: ' . $preloader_color['light'] . ';border-left-color: ' . $preloader_color['light'] . ';-webkit-animation: preload-spin 3s linear infinite;animation: preload-spin 3.5s linear infinite;}.triple-spinner::after {opacity: 0.7;top: 18%;left: 18%;right: 18%;bottom: 18%;border-top-color: ' . $preloader_color['light'] . ';border-left-color: ' . $preloader_color['light'] . ';-webkit-animation: preload-spin 1.5s linear infinite;animation: preload-spin 1.75s linear infinite;}@-webkit-keyframes preload-spin {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}@keyframes preload-spin {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .triple-spinner {border-top: 2px solid ' . $preloader_color['dark'] . ';border-left: 2px solid ' . $preloader_color['dark'] . ';}[data-bs-theme=dark] .triple-spinner::before {border-top-color: ' . $preloader_color['dark'] . ';border-left-color: ' . $preloader_color['dark'] . ';}[data-bs-theme=dark] .triple-spinner::after {border-top-color: ' . $preloader_color['dark'] . ';border-left-color: ' . $preloader_color['dark'] . ';}';
                    break;
                case 'cm-spinner':
                    $preloaderStyles .= '.cm-spinner {height: ' . $preloader_size . 'px;width: ' . $preloader_size . 'px;border: 2px solid transparent;border-radius: 50%;border-top: 2px solid ' . $preloader_color['light'] . ';-webkit-animation: preload-spin 4s linear infinite;animation: preload-spin 4s linear infinite;position: relative;}.cm-spinner::before, .cm-spinner::after {content: "";position: absolute;top: 10%;bottom: 10%;left: 10%;right: 10%;border-radius: 50%;border: 2px solid transparent;}.cm-spinner::before {opacity: 0.8;border-top-color: ' . $preloader_color['light'] . ';-webkit-animation: 3s preload-spin linear infinite;animation: 3s preload-spin linear infinite;}.cm-spinner::after {opacity: 0.9;border-top-color: ' . $preloader_color['light'] . ';-webkit-animation: preload-spin 1.5s linear infinite;animation: preload-spin 1.5s linear infinite;}@-webkit-keyframes preload-spin {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}@keyframes preload-spin {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .cm-spinner {border-top: 2px solid ' . $preloader_color['dark'] . ';}[data-bs-theme=dark] .cm-spinner::before {border-top-color: ' . $preloader_color['dark'] . ';}[data-bs-theme=dark] .cm-spinner::after {border-top-color: ' . $preloader_color['dark'] . ';}';
                    break;
                case 'hm-spinner':
                    $preloaderStyles .= '.hm-spinner{height: ' . $preloader_size . 'px;width: ' . $preloader_size . 'px;border: 2px solid transparent;border-top-color: ' . $preloader_color['light'] . ';border-bottom-color: ' . $preloader_color['light'] . ';border-radius: 50%;position: relative;-webkit-animation: preload-spin 3s linear infinite;animation: preload-spin 3s linear infinite;}.hm-spinner::before{opacity: 0.7;content: "";position: absolute;top: 15%;right: 15%;bottom: 15%;left: 15%;border: 2px solid transparent;border-top-color: ' . $preloader_color['light'] . ';border-bottom-color: ' . $preloader_color['light'] . ';border-radius: 50%;-webkit-animation: preload-spin 1.5s linear infinite;animation: preload-spin 1.5s linear infinite;}@-webkit-keyframes preload-spin {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}@keyframes preload-spin {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .hm-spinner{border-top-color: ' . $preloader_color['dark'] . ';border-bottom-color: ' . $preloader_color['dark'] . ';}[data-bs-theme=dark] .hm-spinner::before{border-top-color: ' . $preloader_color['dark'] . ';border-bottom-color: ' . $preloader_color['dark'] . ';}';
                    break;
                case 'reverse-spinner':
                    $preloaderStyles .= '.reverse-spinner {position: relative;height: ' . $preloader_size . 'px;width: ' . $preloader_size . 'px;border: 2px solid transparent;border-top-color: ' . $preloader_color['light'] . ';border-left-color: ' . $preloader_color['light'] . ';border-radius: 50%;-webkit-animation: preload-spin 1.5s linear infinite;animation: preload-spin 1.5s linear infinite;}.reverse-spinner::before {position: absolute;top: 15%;left: 15%;right: 15%;bottom: 15%;content: "";border: 2px solid transparent;border-top-color: ' . $preloader_color['light'] . ';border-left-color: ' . $preloader_color['light'] . ';border-radius: 50%;-webkit-animation: preload-spin-back 1s linear infinite;animation: preload-spin-back 1s linear infinite;}@-webkit-keyframes preload-spin-back {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(-720deg);transform: rotate(-720deg);}}@keyframes preload-spin-back {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(-720deg);transform: rotate(-720deg);}}@-webkit-keyframes preload-spin {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}@keyframes preload-spin {from {-webkit-transform: rotate(0deg);transform: rotate(0deg);}to {-webkit-transform: rotate(360deg);transform: rotate(360deg);}}';
                    $preloaderStyles .= '[data-bs-theme=dark] .reverse-spinner {border-top-color: ' . $preloader_color['dark'] . ';border-left-color: ' . $preloader_color['dark'] . ';}[data-bs-theme=dark] .reverse-spinner::before {border-top-color: ' . $preloader_color['dark'] . ';border-left-color: ' . $preloader_color['dark'] . ';}';
                    break;
            }
        } elseif ($preloader_setting == "image") {
            $preloader_image = $params->get('preloader_image', '');
            $styles = [];
            if (!empty($preloader_image)) {
                $styles[] = 'background-image:url(' . $preloader_image . ')';
                $styles[] = 'background-repeat:' . $params->get('preloader_image_repeat', 'inherit');
                $styles[] = 'background-size:' . $params->get('preloader_image_size', 'inherit');
                $styles[] = 'background-position:' . $params->get('preloader_image_position', 'inherit');
                $styles[] = 'height:'.'100%';
                $styles[] = 'width:'.'100%';
            }
            $preloaderStyles .= '.preloader-image{ '.implode(';', $styles).' }';

        } elseif ($preloader_setting == "fontawesome") {
            $preloaderStyles .= '.preload_fontawesome{font-size:'.$preloader_size.'px; color: '.$preloader_color['light'].'; display: flex;justify-content: center;margin: 0 auto;}';
            $preloaderStyles .= '[data-bs-theme=dark] .preload_fontawesome{color: '.$preloader_color['dark'].';}';
        }
        $preloaderStyles    .=  '#moon-preloader{background:' . $preloader_bgcolor['light'] . ';z-index: 99999;}';
        $preloaderStyles    .=  '[data-bs-theme=dark] #moon-preloader{background:' . $preloader_bgcolor['dark'] . ';}';
        $document->addStyleDeclaration($preloaderStyles);
    }

    public static function getRgbaValues(string $rgbaString): ?array
    {
        // Match the rgba or rgb pattern
        if (preg_match('/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)/', $rgbaString, $matches)) {
            return [
                'r' => (int)$matches[1],
                'g' => (int)$matches[2],
                'b' => (int)$matches[3],
                'a' => isset($matches[4]) ? (float)$matches[4] : 1.0, // Default alpha to 1.0 if not provided
            ];
        }

        // Return null if the string is not in the correct format
        return null;
    }

    public static function loadRegion($region, $classes = [], $tag = 'aside', $fakeblocksonly = false): string
    {
        global $OUTPUT;
        if (empty($region)) {
            return '';
        }
        $addblockbutton = $OUTPUT->addblockbutton($region);
        $blockshtml = $OUTPUT->blocks($region, $classes, $tag, $fakeblocksonly);
        $hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
        $content = Framework::getDocument()->_positionContent($region, 'before');
        if ($hasblocks) {
            $content .= $addblockbutton . $blockshtml;
        }
        $content .= Framework::getDocument()->_positionContent($region, 'after');
        return $content;
    }

    public static function loadParams($data): Registry
    {
        $params_data   = new Registry();
        if (isset($data) && !empty($data)) {
            $params = [];
            foreach ($data as $param) {
                if (is_array($param)) {
                    $params[$param['name']] = $param['value'];
                } elseif (is_object($param)) {
                    $params[$param->name] = $param->value;
                }
            }
            $params_data->loadArray($params);
        }
        return $params_data;
    }
}