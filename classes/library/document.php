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
defined('MOODLE_INTERNAL') || die();

use local_moon\library\helper\utilities;
use local_moon\library\helper\style;
use local_moon\library\helper\media;
use moodle_url;

/**
 * Main Theme Framework class
 * Usage: $fw = new \local_moon\core\theme('mymoon');
 */
class document {
    protected string $themename;
    protected array $_styles = ['global' => [], 'larger_desktop' => [], 'large_desktop' => [], 'desktop' => [], 'tablet' => [], 'landscape_mobile' => [], 'mobile' => []];
    protected array $_scripts = ['head' => [], 'body' => []];

    protected array $_javascripts = ['head' => [], 'body' => []];
    protected array $_stylesheets = [];
    protected array $_metas = [], $_links = [];
    protected static array $_layout_paths = [];
    protected array $script_options = [];
    protected array $_is_loaded = [];
    public bool $rtl = false;

    public function __construct() {
        global $CFG;
        if (function_exists('right_to_left') && right_to_left()) {
            // RTL language
            $this->rtl = true;
        } else {
            // LTR language
            $this->rtl = false;
        }
        $this->add_layout_path($CFG->dirroot . '/theme/'.framework::get_theme()->name.'/layout/');
    }

    public function get_styles() : void
    {
        utilities::favicon();
        utilities::colors();
        utilities::typography();
        utilities::preloader();
        utilities::back_to_top();
        utilities::page_settings();
    }

    public function get_scripts() : void{
        utilities::color_mode();
        utilities::sticky_menu();
    }

    public function get_moon_assets() : string{
        $this->get_styles();
        $this->custom_css();
        $content = $this->render_links();
        $content .= $this->get_stylesheets();

        $this->get_scripts();
        return $content;
    }

    public function render_footer() : string{
        return $this->load_preloader() . $this->load_back_to_top();
    }

    public function get_theme_mode() : string{
        return framework::get_theme()->get_color_mode();
    }

    public function custom_css(): void
    {
        $css = $this->render_css();
        // page css();
        $page_css_hash = md5($css);
        if (!media::exists($page_css_hash. '.css', '/', 'css')) {
            $css_file = media::create_from_string($css, $page_css_hash. '.css', '/', 'css');
        } else {
            $css_file = media::file($page_css_hash. '.css', '/', 'css');
        }
        $this->add_style_sheet(media::url($css_file));
    }

    public function add_layout_path($path): void
    {
        self::$_layout_paths[] = $path;
    }

    public function add_script($url, $attrs = [], $in_head = false): void
    {
        if (empty($url)) {
            return;
        }
        global $PAGE;
        $link = new moodle_url(
            $url,
            $attrs
        );
        $PAGE->requires->js($link, $in_head);
    }

    public function add_script_declaration($content): void
    {
        if (empty($content)) {
            return;
        }
        global $PAGE;
        $PAGE->requires->js_init_code($content);
    }

    public function add_style_declaration($content, $device = 'global'): void
    {
        if (empty($content)) {
            return;
        }
        $this->_styles[$device][] = trim($content);
    }

    public function add_style_sheet($url, $attribs = ['rel' => 'stylesheet', 'type' => 'text/css'], $shifted = 0): void
    {
        if (!is_array($url)) {
            $url = [$url];
        }
        if (!isset($attribs['rel'])) {
            $attribs['rel'] = 'stylesheet';
        }
        if (!isset($attribs['type'])) {
            $attribs['type'] = 'text/css';
        }
        foreach ($url as $u) {
            if (!empty(trim($u))) {
                $stylesheet = ['url' => $u, 'attribs' => $attribs, 'shifted' => $shifted];
                $this->_stylesheets[md5($u)] = $stylesheet;
            }
        }
    }

    public function render_css(): string
    {
        $css_script = '';
        foreach ($this->_styles as $device => $css) {
            $css_content = implode('', $this->_styles[$device]);
            if (!empty($css_content)) {
                $css_script .= style::get_css($css_content, $device);
            }
        }
        return $css_script;
    }

    /**
     * @param $url
     * @param bool $add_root
     * @return string
     */
    protected function _system_url($url, bool $add_root = true): string
    {
        global $CFG;
        $template = framework::get_theme();

        // If already an absolute URL (http/https) or protocol-relative, return as-is.
        if (preg_match('#^https?://#i', $url) || strpos($url, '//') === 0) {
            return $url;
        }

        $root = $add_root ? rtrim($CFG->wwwroot, '/') . '/' : '';
        $trimmed = ltrim($url, '/');

        $candidates = [
            'local/moon/assets/' . $trimmed => $CFG->dirroot . '/local/moon/assets/' . $trimmed,
            'theme/' . $template->name . '/assets/' . $trimmed => $CFG->dirroot . '/theme/' . $template->name . '/assets/' . $trimmed,
            $trimmed => $CFG->dirroot . '/' . $trimmed,
        ];

        foreach ($candidates as $web_path => $fs_path) {
            if (file_exists($fs_path)) {
                return $root . $web_path;
            }
        }

        return $url;
    }

    /**
     * Render all stylesheets added to the document head
     * @return string stylesheet tags
     */
    public function get_stylesheets(): string
    {
        $keys = array_keys($this->_stylesheets);
        foreach ($keys as $index => $key) {
            if ($this->_stylesheets[$key]['shifted']) {
                $newindex = $index + (int) $this->_stylesheets[$key]['shifted'];
                $this->move_file($keys, $index, $newindex);
            }
        }
        $content = '';
        foreach ($keys as $key) {
            $stylesheet = $this->_stylesheets[$key];
            $content .= '<link href="' . $this->_system_url($stylesheet['url']) . '"';
            foreach ($stylesheet['attribs'] as $prop => $value) {
                $content .= ' ' . $prop . '="' . $value . '"';
            }
            $content .= ' />' . "\n";
        }
        return $content;
    }

    /**
     * Add a link to the document head
     * @param string $href
     * @param string $rel
     * @param array $attribs
     * @return void
     */
    public function add_link(string $href = '', string $rel = 'stylesheet', array $attribs = ['type' => 'text/css']): void
    {
        $this->_links[md5($href)] = [
            'href' => $href,
            'rel' => $rel,
            'attribs' => $attribs
        ];
    }

    /**
     * Render all links added to the document head
     * @return string link tags
     */
    public function render_links(): string
    {
        $html = '';
        foreach ($this->_links as $link) {
            $html .= '<link';
            if (!empty($link['href'])) {
                $html .= ' href="' . $this->_system_url($link['href']) . '"';
            }
            if (!empty($link['rel'])) {
                $html .= ' rel="' . $link['rel'] . '"';
            }
            foreach ($link['attribs'] as $attrib_prop => $attrib_val) {
                $html .= ' ' . $attrib_prop . '="' . $attrib_val . '"';
            }
            $html .= ' />';
        }
        return $html;
    }

    public function add_script_options($key, $options, $merge = true): static
    {
        if (empty($this->scriptOptions[$key])) {
            $this->scriptOptions[$key] = [];
        }

        if ($merge && \is_array($options)) {
            $this->scriptOptions[$key] = array_replace_recursive($this->scriptOptions[$key], $options);
        } else {
            $this->scriptOptions[$key] = $options;
        }

        return $this;
    }

    public function get_script_options($key = null)
    {
        if ($key) {
            return (empty($this->scriptOptions[$key])) ? [] : $this->scriptOptions[$key];
        }

        return $this->scriptOptions;
    }

    public function move_file(&$array, $a, $b): void
    {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

    public function include($section, $display_data = [], $return = false) : string
    {
        global $CFG;
        $path = null;
        $name = str_replace('.', '/', $section);
        $layout_paths = self::$_layout_paths;

        $layout_paths[] = $CFG -> dirroot . '/local/moon/layout/';
        foreach ($layout_paths as $layout_path) {
            $layout_path = substr($layout_path, -1) == '/' ? $layout_path : $layout_path . '/';
            if (file_exists($layout_path . $name . '.php')) {
                $path = $layout_path . $name . '.php';
                break;
            }
        }

        if ($path === null) {
            return '';
        }

        ob_start();
        include $path;
        $content = ob_get_clean();

        if ($return) {
            return trim($content);
        }
        echo trim($content);
        return '';
    }

    private function _position_layouts(): array
    {
        $params = framework::get_theme()->get_params();
        $astroidcontentlayouts = $params->get('astroidcontentlayouts', '');
        $return = [];
        if (!empty($astroidcontentlayouts)) {
            $astroidcontentlayouts = explode(',', $astroidcontentlayouts);
            foreach ($astroidcontentlayouts as $astroidcontentlayout) {
                $astroidcontentlayout = explode(':', $astroidcontentlayout);
                if (isset($return[$astroidcontentlayout[1]])) {
                    $return[$astroidcontentlayout[1]][] = $astroidcontentlayout[0] . ':' . $astroidcontentlayout[2];
                } else {
                    $return[$astroidcontentlayout[1]] = [];
                    $return[$astroidcontentlayout[1]][] = $astroidcontentlayout[0] . ':' . $astroidcontentlayout[2];
                }
            }
        }
        return $return;
    }

    public function _position_content($position, $load = 'after'): string
    {
        $contents = $this->_position_layouts();
        $return = '';
        if (!empty($contents[$position])) {
            foreach ($contents[$position] as $layout) {
                $layout = explode(':', $layout);
                if ($layout[1] == $load) {
                    $return .= $this->include($layout[0], [], true);
                }
            }
        }
        return $return;
    }

    public function load_animation(): void
    {
        if (!isset($this->_is_loaded['animation'])) {
            global $PAGE;
            $PAGE->requires->css('/local/moon/assets/animate/animate.min.css');
            $PAGE->requires->js('/local/moon/assets/animate/animate.min.js');
            $this->_is_loaded['animation'] = true;
        }
    }

    public function load_back_to_top(): string
    {
        global $PAGE;
        $params = framework::get_theme()->get_params();
        $enable_backtotop = $params->get('backtotop', 1);
        if (!$enable_backtotop) {
            return '';
        }
        $backtotop_icon         = $params->get('backtotop_icon', 'fas fa-arrow-up');
        $backtotop_on_mobile    = $params->get('backtotop_on_mobile', 1);
        $backtotop_icon_style   = $params->get('backtotop_icon_style', 'circle');
        $class[] = $backtotop_icon_style;

        if (!$backtotop_on_mobile) {
            $class[] = 'hideonsm';
            $class[] = 'hideonxs';
        }

        $PAGE->requires->js_call_amd('local_moon/backtotop', 'init', [
            'enable' => $enable_backtotop,
        ]);

        return '<button type="button" title="Back to Top" id="moon-backtotop" class="btn ' . implode(' ', $class) . '" ><i class="' . $backtotop_icon . '"></i></button>';
    }

    public function load_preloader(): string
    {
        $params = framework::get_theme()->get_params();
        $enable_preloader = $params->get('preloader', 1);
        if (!$enable_preloader) {
            return '';
        }

        $preloader_setting = $params->get('preloader_setting', 'animations');
        $preloader_animation = $params->get('preloader_animation', 'circle');
        if($preloader_setting == "animation"){
            switch ($preloader_animation) {
                case 'rotating-plane':
                    $preloader_html = '<div class="sk-rotating-plane"></div>';
                    break;
                case 'double-bounce':
                    $preloader_html = '<div class="sk-double-bounce"><div class="sk-child sk-double-bounce1"></div><div class="sk-child sk-double-bounce2"></div></div>';
                    break;
                case 'wave':
                    $preloader_html = '<div class="sk-wave"><div class="sk-rect sk-rect1"></div><div class="sk-rect sk-rect2"></div><div class="sk-rect sk-rect3"></div><div class="sk-rect sk-rect4"></div><div class="sk-rect sk-rect5"></div></div>';
                    break;
                case 'wandering-cubes':
                    $preloader_html = '<div class="sk-wandering-cubes"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div></div>';
                    break;
                case 'pulse':
                    $preloader_html = '<div class="sk-spinner sk-spinner-pulse"></div>';
                    break;
                case 'chasing-dots':
                    $preloader_html = '<div class="sk-chasing-dots"><div class="sk-child sk-dot1"></div><div class="sk-child sk-dot2"></div></div>';
                    break;
                case 'three-bounce':
                    $preloader_html = '<div class="sk-three-bounce"> <div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>';
                    break;
                case 'circle':
                    $preloader_html = '<div class="sk-circle"> <div class="sk-circle1 sk-child"></div><div class="sk-circle2 sk-child"></div><div class="sk-circle3 sk-child"></div><div class="sk-circle4 sk-child"></div><div class="sk-circle5 sk-child"></div><div class="sk-circle6 sk-child"></div><div class="sk-circle7 sk-child"></div><div class="sk-circle8 sk-child"></div><div class="sk-circle9 sk-child"></div><div class="sk-circle10 sk-child"></div><div class="sk-circle11 sk-child"></div><div class="sk-circle12 sk-child"></div></div>';
                    break;
                case 'cube-grid':
                    $preloader_html = '<div class="sk-cube-grid"> <div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>';
                    break;
                case 'fading-circle':
                    $preloader_html = '<div class="sk-fading-circle"> <div class="sk-circle1 sk-circle"></div><div class="sk-circle2 sk-circle"></div><div class="sk-circle3 sk-circle"></div><div class="sk-circle4 sk-circle"></div><div class="sk-circle5 sk-circle"></div><div class="sk-circle6 sk-circle"></div><div class="sk-circle7 sk-circle"></div><div class="sk-circle8 sk-circle"></div><div class="sk-circle9 sk-circle"></div><div class="sk-circle10 sk-circle"></div><div class="sk-circle11 sk-circle"></div><div class="sk-circle12 sk-circle"></div></div>';
                    break;
                case 'folding-cube':
                    $preloader_html = '<div class="sk-folding-cube"> <div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div>';
                    break;
                case 'bouncing-loader':
                    $preloader_html = '<div class="bouncing-loader"><div></div><div></div><div></div></div>';
                    break;
                case 'donut':
                    $preloader_html = '<div class="donut"></div>';
                    break;
                case 'triple-spinner':
                    $preloader_html = '<div class="triple-spinner"></div>';
                    break;
                case 'cm-spinner':
                    $preloader_html = '<div class="cm-spinner"></div>';
                    break;
                case 'hm-spinner':
                    $preloader_html = '<div class="hm-spinner"></div>';
                    break;
                case 'reverse-spinner':
                    $preloader_html = '<div class="reverse-spinner"></div>';
                    break;
                default:
                    $preloader_html = '';
                    break;
            }
        } elseif ($preloader_setting == "image") {
            $preloader_html = '<div class="preloader-image"></div>';

        } elseif ($preloader_setting == "fontawesome") {
            $preloader_fontawesome = $params->get('preloader_fontawesome', '');
            $preloader_html = '<div class="preload_fontawesome '.$preloader_fontawesome.'"></div>';
        }
        global $PAGE;
        $PAGE->requires->js_call_amd('local_moon/preloader', 'init', [
            'duration' => '800ms',
        ]);

        return '<div id="moon-preloader" class="d-flex align-items-center justify-content-center position-fixed top-0 start-0 bottom-0 end-0">' . $preloader_html . '</div>';
    }

    public function load_as_icon(): void
    {
        if (!isset($this->_is_loaded['asicon'])) {
            global $PAGE;
            $PAGE->requires->css('/local/moon/assets/linearicons/font.min.css');
            $this->_is_loaded['asicon'] = true;
        }
    }

    public function load_ui_kit(): void
    {
        if (!isset($this->_is_loaded['uikit'])) {
            global $PAGE;
            if ($this->rtl) {
                $PAGE->requires->css('/local/moon/assets/uikit/css/uikit.rtl.min.css');
            } else {
                $PAGE->requires->css('/local/moon/assets/uikit/css/uikit.min.css');
            }
            $PAGE->requires->js('/local/moon/assets/uikit/js/uikit.min.js', true);
            $PAGE->requires->js('/local/moon/assets/uikit/js/uikit-icons.min.js', true);

            // Override default uikit colors
            $heading = new style('.uk-h1, .uk-h2, .uk-h3, .uk-h4, .uk-h5, .uk-h6, .uk-heading-2xlarge, .uk-heading-3xlarge, .uk-heading-large, .uk-heading-medium, .uk-heading-small, .uk-heading-xlarge, h1, h2, h3, h4, h5, h6');
            $heading->add_css('color', 'var(--bs-heading-color)');
            $heading->render();
            $link = new style('.uk-link, a');
            $link->add_css('color', 'rgba(var(--bs-link-color-rgb),var(--bs-link-opacity,1))');
            $link->hover()->add_css('color', 'var(--bs-link-hover-color)');
            $link->render();

            $this->_is_loaded['uikit'] = true;
        }
    }

    public function load_gsap($plugin = ''): void
    {
        global $PAGE;
        if (!isset($this->_is_loaded['gsap'])) {
            $PAGE->requires->js('/local/moon/assets/gsap/gsap.min.js', true);
            $this->_is_loaded['gsap'] = [];
        }

        if (!empty($plugin) && !in_array($plugin, $this->_is_loaded['gsap'])) {
            $PAGE->requires->js('/local/moon/assets/gsap/'.$plugin.'.min.js', true);
            $this->_is_loaded['gsap'][] = $plugin;
        }
    }

    public function load_art_slider(): void
    {
        if (!isset($this->_is_loaded['art_slider'])) {
            global $PAGE;
            $PAGE->requires->css('/local/moon/assets/art_slider/css/base.min.css');
            $PAGE->requires->js('/local/moon/assets/art_slider/js/index.min.js');
            $this->_is_loaded['art_slider'] = true;
        }
    }

    public function load_fancy_box(): void
    {
        if (!isset($this->_is_loaded['fancybox'])) {
            global $PAGE;
            $PAGE->requires->css("/local/moon/assets/fancybox/fancybox.css");
            $PAGE->requires->js('/local/moon/assets/fancybox/fancybox.umd.js', true);
            $this->_is_loaded['fancybox'] = true;
        }
    }

    public function load_masonry($selector = ''): void
    {
        global $PAGE;
        if (!isset($this->_is_loaded['masonry'])) {
            $PAGE->requires->js('/local/moon/assets/masonry/masonry.pkgd.min.js', true);
            $this->_is_loaded['masonry'] = true;
        }
        if (!empty($selector)) {
            $this->add_script_declaration('window.addEventListener(\'load\', () => {new Masonry( \''.$selector.'\', {itemSelector: \''.$selector.' > div\',percentPosition: true}); document.querySelectorAll(\''.$selector.'\').forEach(element => element.classList.remove("as-loading")); });');
        }
    }

    public function load_swiper($obj = '', $config = ''): void
    {
        global $PAGE;
        if (!isset($this->_is_loaded['swiper'])) {
            $PAGE->requires->css("/local/moon/assets/swiper/swiper-bundle.min.css");
            $PAGE->requires->js('/local/moon/assets/swiper/swiper-bundle.min.js', true);
            $this->_is_loaded['swiper'] = true;
        }
        if (!empty($obj) && !empty($config)) {
            $this->add_script_declaration('new Swiper(\''.$obj.'\', {'.$config.'});');
        }
    }

    public function load_video_bg(): void
    {
        if (!isset($this->_is_loaded['video_bg'])) {
            $this->add_script('/local/moon/assets/videobg/videobg.min.js');
            $this->_is_loaded['video_bg'] = true;
        }
    }

    public function load_parallax(): void
    {
        if (!isset($this->_is_loaded['parallax'])) {
            $this->add_script('/local/moon/assets/parallax/parallax.min.js');
            $this->_is_loaded['parallax'] = true;
        }
    }

    public function load_transform(): void
    {
        if (!isset($this->_is_loaded['transform'])) {
            $this->add_script('/local/moon/assets/transform/js/index.min.js');
            $this->_is_loaded['transform'] = true;
        }
    }

    public function load_images_loaded(): void
    {
        if (!isset($this->_is_loaded['imagesloaded'])) {
            $this->add_script('/local/moon/assets/imagesloaded/imagesloaded.pkgd.min.js', [], true);
            $this->_is_loaded['imagesloaded'] = true;
        }
    }
}
