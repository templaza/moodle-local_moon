<?php
/**
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2025 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */
namespace local_moon\library;
defined('MOODLE_INTERNAL') || die();

use local_moon\library\Helper\Utilities;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\Media;
use moodle_url;

/**
 * Main Theme Framework class
 * Usage: $fw = new \local_moon\core\theme('mymoon');
 */
class Document {
    protected string $themename;
    protected array $_styles = ['global' => [], 'larger_desktop' => [], 'large_desktop' => [], 'desktop' => [], 'tablet' => [], 'landscape_mobile' => [], 'mobile' => []];
    protected array $_scripts = ['head' => [], 'body' => []];

    protected array $_javascripts = ['head' => [], 'body' => []];
    protected array $_stylesheets = [];
    protected array $_metas = [], $_links = [];
    protected static array $_layout_paths = [];
    protected array $scriptOptions = [];
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
        $this->addLayoutPath($CFG->dirroot . '/theme/'.Framework::getTheme()->name.'/layout/');
    }

    public function getStyles() : void
    {
        Utilities::favicon();
        Utilities::colors();
        Utilities::typography();
        Utilities::preloader();
        Utilities::backtoTop();
        Utilities::page_settings();
    }

    public function getScripts() : void{
        global $PAGE;
        $theme = Framework::getTheme();
        $color_mode = $theme->getColorMode();
        if ($color_mode) {
            $params = $theme->getParams();
            $enable_color_mode_transform    =   $params->get('enable_color_mode_transform', 0);
            if ($enable_color_mode_transform) {
                $colormode_transform_type               =   $params->get('colormode_transform_type', 'light_dark');
                $astroid_colormode_transform_offset     =   $params->get('astroid_colormode_transform_offset', 50);
                if ($colormode_transform_type === 'light_dark') {
                    $from   =   'light';
                    $to     =   'dark';
                } else {
                    $from   =   'dark';
                    $to     =   'light';
                }
                $PAGE->requires->js_call_amd(
                    'local_moon/colortransform',
                    'init',
                    [
                        'from' => $from,
                        'to' => $to,
                        'offset' => $astroid_colormode_transform_offset
                    ]
                );
            } else {
                $PAGE->requires->js_call_amd(
                    'local_moon/colormode',
                    'init',
                    [
                        'mode' => $color_mode,
                        'templatehash' => md5($theme->name)
                    ]
                );
            }
        }
    }

    public function getMoonAssets() : string{
        $this->getStyles();
        $this->customCss();
        $content = $this->renderLinks();
        $content .= $this->getStylesheets();

        $this->getScripts();
        return $content;
    }

    public function renderFooter() : string{
        return $this->loadPreloader() . $this->loadBackToTop();
    }

    public function getThemeMode() : string{
        return Framework::getTheme()->getColorMode();
    }

    public function customCss(): void
    {
        $css = $this->renderCss();
        // page css();
        $pageCSSHash = md5($css);
        if (!Media::exists($pageCSSHash. '.css', '/', 'css')) {
            $cssFile = Media::create_from_string($css, $pageCSSHash. '.css', '/', 'css');
        } else {
            $cssFile = Media::file($pageCSSHash. '.css', '/', 'css');
        }
        $this->addStyleSheet(Media::url($cssFile));
    }

    public function addLayoutPath($path): void
    {
        self::$_layout_paths[] = $path;
    }

    public function addScript($url, $attrs = [], $inHead = false): void
    {
        if (empty($url)) {
            return;
        }
        global $PAGE;
        $link = new moodle_url(
            $url,
            $attrs
        );
        $PAGE->requires->js($link, $inHead);
    }

    public function addScriptDeclaration($content): void
    {
        if (empty($content)) {
            return;
        }
        global $PAGE;
        $PAGE->requires->js_init_code($content);
    }

    public function addStyleDeclaration($content, $device = 'global'): void
    {
        if (empty($content)) {
            return;
        }
        $this->_styles[$device][] = trim($content);
    }

    public function addStyleSheet($url, $attribs = ['rel' => 'stylesheet', 'type' => 'text/css'], $shifted = 0): void
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

    public function renderCss(): string
    {
        $cssScript = '';
        foreach ($this->_styles as $device => $css) {
            $cssContent = implode('', $this->_styles[$device]);
            if (!empty($cssContent)) {
                $cssScript .= Style::getCss($cssContent, $device);
            }
        }
        return $cssScript;
    }

    /**
     * @param $url
     * @param true $addRoot
     * @return string
     */
    protected function _systemUrl($url, true $addRoot = true): string
    {
        global $CFG;
        $template = Framework::getTheme();

        // If already an absolute URL (http/https) or protocol-relative, return as-is.
        if (preg_match('#^https?://#i', $url) || strpos($url, '//') === 0) {
            return $url;
        }

        $root = $addRoot ? rtrim($CFG->wwwroot, '/') . '/' : '';
        $trimmed = ltrim($url, '/');

        $candidates = [
            'local/moon/assets/' . $trimmed => $CFG->dirroot . '/local/moon/assets/' . $trimmed,
            'theme/' . $template->name . '/assets/' . $trimmed => $CFG->dirroot . '/theme/' . $template->name . '/assets/' . $trimmed,
            $trimmed => $CFG->dirroot . '/' . $trimmed,
        ];

        foreach ($candidates as $webPath => $fsPath) {
            if (file_exists($fsPath)) {
                return $root . $webPath;
            }
        }

        return $url;
    }

    /**
     * Render all stylesheets added to the document head
     * @return string stylesheet tags
     */
    public function getStylesheets(): string
    {
        $keys = array_keys($this->_stylesheets);
        foreach ($keys as $index => $key) {
            if ($this->_stylesheets[$key]['shifted']) {
                $newindex = $index + (int) $this->_stylesheets[$key]['shifted'];
                $this->moveFile($keys, $index, $newindex);
            }
        }
        $content = '';
        foreach ($keys as $key) {
            $stylesheet = $this->_stylesheets[$key];
            $content .= '<link href="' . $this->_systemUrl($stylesheet['url']) . '"';
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
    public function addLink(string $href = '', string $rel = 'stylesheet', array $attribs = ['type' => 'text/css']): void
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
    public function renderLinks(): string
    {
        $html = '';
        foreach ($this->_links as $link) {
            $html .= '<link';
            if (!empty($link['href'])) {
                $html .= ' href="' . $this->_systemUrl($link['href']) . '"';
            }
            if (!empty($link['rel'])) {
                $html .= ' rel="' . $link['rel'] . '"';
            }
            foreach ($link['attribs'] as $attribProp => $attribVal) {
                $html .= ' ' . $attribProp . '="' . $attribVal . '"';
            }
            $html .= ' />';
        }
        return $html;
    }

    public function addScriptOptions($key, $options, $merge = true): static
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

    public function getScriptOptions($key = null)
    {
        if ($key) {
            return (empty($this->scriptOptions[$key])) ? [] : $this->scriptOptions[$key];
        }

        return $this->scriptOptions;
    }

    public function moveFile(&$array, $a, $b): void
    {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

    public function include($section, $displayData = [], $return = false) : string
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

    private function _positionLayouts(): array
    {
        $params = Framework::getTheme()->getParams();
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

    public function _positionContent($position, $load = 'after'): string
    {
        $contents = $this->_positionLayouts();
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

    public function loadAnimation(): void
    {
        if (!isset($this->_is_loaded['animation'])) {
            global $PAGE;
            $PAGE->requires->css('/local/moon/assets/animate/animate.min.css');
            $PAGE->requires->js('/local/moon/assets/animate/animate.min.js');
            $this->_is_loaded['animation'] = true;
        }
    }

    public function loadBackToTop(): string
    {
        global $PAGE;
        $params = Framework::getTheme()->getParams();
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

    public function loadPreloader(): string
    {
        $params = Framework::getTheme()->getParams();
        $enable_preloader = $params->get('preloader', 1);
        if (!$enable_preloader) {
            return '';
        }

        $preloader_setting = $params->get('preloader_setting', 'animations');
        $preloader_animation = $params->get('preloader_animation', 'circle');
        if($preloader_setting == "animation"){
            switch ($preloader_animation) {
                case 'rotating-plane':
                    $preloaderHTML = '<div class="sk-rotating-plane"></div>';
                    break;
                case 'double-bounce':
                    $preloaderHTML = '<div class="sk-double-bounce"><div class="sk-child sk-double-bounce1"></div><div class="sk-child sk-double-bounce2"></div></div>';
                    break;
                case 'wave':
                    $preloaderHTML = '<div class="sk-wave"><div class="sk-rect sk-rect1"></div><div class="sk-rect sk-rect2"></div><div class="sk-rect sk-rect3"></div><div class="sk-rect sk-rect4"></div><div class="sk-rect sk-rect5"></div></div>';
                    break;
                case 'wandering-cubes':
                    $preloaderHTML = '<div class="sk-wandering-cubes"><div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div></div>';
                    break;
                case 'pulse':
                    $preloaderHTML = '<div class="sk-spinner sk-spinner-pulse"></div>';
                    break;
                case 'chasing-dots':
                    $preloaderHTML = '<div class="sk-chasing-dots"><div class="sk-child sk-dot1"></div><div class="sk-child sk-dot2"></div></div>';
                    break;
                case 'three-bounce':
                    $preloaderHTML = '<div class="sk-three-bounce"> <div class="sk-child sk-bounce1"></div><div class="sk-child sk-bounce2"></div><div class="sk-child sk-bounce3"></div></div>';
                    break;
                case 'circle':
                    $preloaderHTML = '<div class="sk-circle"> <div class="sk-circle1 sk-child"></div><div class="sk-circle2 sk-child"></div><div class="sk-circle3 sk-child"></div><div class="sk-circle4 sk-child"></div><div class="sk-circle5 sk-child"></div><div class="sk-circle6 sk-child"></div><div class="sk-circle7 sk-child"></div><div class="sk-circle8 sk-child"></div><div class="sk-circle9 sk-child"></div><div class="sk-circle10 sk-child"></div><div class="sk-circle11 sk-child"></div><div class="sk-circle12 sk-child"></div></div>';
                    break;
                case 'cube-grid':
                    $preloaderHTML = '<div class="sk-cube-grid"> <div class="sk-cube sk-cube1"></div><div class="sk-cube sk-cube2"></div><div class="sk-cube sk-cube3"></div><div class="sk-cube sk-cube4"></div><div class="sk-cube sk-cube5"></div><div class="sk-cube sk-cube6"></div><div class="sk-cube sk-cube7"></div><div class="sk-cube sk-cube8"></div><div class="sk-cube sk-cube9"></div></div>';
                    break;
                case 'fading-circle':
                    $preloaderHTML = '<div class="sk-fading-circle"> <div class="sk-circle1 sk-circle"></div><div class="sk-circle2 sk-circle"></div><div class="sk-circle3 sk-circle"></div><div class="sk-circle4 sk-circle"></div><div class="sk-circle5 sk-circle"></div><div class="sk-circle6 sk-circle"></div><div class="sk-circle7 sk-circle"></div><div class="sk-circle8 sk-circle"></div><div class="sk-circle9 sk-circle"></div><div class="sk-circle10 sk-circle"></div><div class="sk-circle11 sk-circle"></div><div class="sk-circle12 sk-circle"></div></div>';
                    break;
                case 'folding-cube':
                    $preloaderHTML = '<div class="sk-folding-cube"> <div class="sk-cube1 sk-cube"></div><div class="sk-cube2 sk-cube"></div><div class="sk-cube4 sk-cube"></div><div class="sk-cube3 sk-cube"></div></div>';
                    break;
                case 'bouncing-loader':
                    $preloaderHTML = '<div class="bouncing-loader"><div></div><div></div><div></div></div>';
                    break;
                case 'donut':
                    $preloaderHTML = '<div class="donut"></div>';
                    break;
                case 'triple-spinner':
                    $preloaderHTML = '<div class="triple-spinner"></div>';
                    break;
                case 'cm-spinner':
                    $preloaderHTML = '<div class="cm-spinner"></div>';
                    break;
                case 'hm-spinner':
                    $preloaderHTML = '<div class="hm-spinner"></div>';
                    break;
                case 'reverse-spinner':
                    $preloaderHTML = '<div class="reverse-spinner"></div>';
                    break;
                default:
                    $preloaderHTML = '';
                    break;
            }
        } elseif ($preloader_setting == "image") {
            $preloaderHTML = '<div class="preloader-image"></div>';

        } elseif ($preloader_setting == "fontawesome") {
            $preloader_fontawesome = $params->get('preloader_fontawesome', '');
            $preloaderHTML = '<div class="preload_fontawesome '.$preloader_fontawesome.'"></div>';
        }
        global $PAGE;
        $PAGE->requires->js_call_amd('local_moon/preloader', 'init', [
            'duration' => '800ms',
        ]);

        return '<div id="moon-preloader" class="d-flex align-items-center justify-content-center position-fixed top-0 start-0 bottom-0 end-0">' . $preloaderHTML . '</div>';
    }

    public function loadASIcon(): void
    {
        if (!isset($this->_is_loaded['asicon'])) {
            global $PAGE;
            $PAGE->requires->css('/local/moon/assets/linearicons/font.min.css');
            $this->_is_loaded['asicon'] = true;
        }
    }

    public function loadUIKit(): void
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
            $heading = new Style('.uk-h1, .uk-h2, .uk-h3, .uk-h4, .uk-h5, .uk-h6, .uk-heading-2xlarge, .uk-heading-3xlarge, .uk-heading-large, .uk-heading-medium, .uk-heading-small, .uk-heading-xlarge, h1, h2, h3, h4, h5, h6');
            $heading->addCss('color', 'var(--bs-heading-color)');
            $heading->render();
            $link = new Style('.uk-link, a');
            $link->addCss('color', 'rgba(var(--bs-link-color-rgb),var(--bs-link-opacity,1))');
            $link->hover()->addCss('color', 'var(--bs-link-hover-color)');
            $link->render();

            $this->_is_loaded['uikit'] = true;
        }
    }

    public function loadGSAP($plugin = ''): void
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

    public function loadArtSlider(): void
    {
        if (!isset($this->_is_loaded['art_slider'])) {
            global $PAGE;
            $PAGE->requires->css('/local/moon/assets/art_slider/css/base.min.css');
            $PAGE->requires->js('/local/moon/assets/art_slider/js/index.min.js');
            $this->_is_loaded['art_slider'] = true;
        }
    }

    public function loadFancyBox(): void
    {
        if (!isset($this->_is_loaded['fancybox'])) {
            global $PAGE;
            $PAGE->requires->css("/local/moon/assets/fancybox/fancybox.css");
            $PAGE->requires->js('/local/moon/assets/fancybox/fancybox.umd.js', true);
            $this->_is_loaded['fancybox'] = true;
        }
    }

    public function loadMasonry($selector = ''): void
    {
        global $PAGE;
        if (!isset($this->_is_loaded['masonry'])) {
            $PAGE->requires->js('/local/moon/assets/masonry/masonry.pkgd.min.js', true);
            $this->_is_loaded['masonry'] = true;
        }
        if (!empty($selector)) {
            $this->addScriptDeclaration('window.addEventListener(\'load\', () => {new Masonry( \''.$selector.'\', {itemSelector: \''.$selector.' > div\',percentPosition: true}); document.querySelectorAll(\''.$selector.'\').forEach(element => element.classList.remove("as-loading")); });');
        }
    }

    public function loadSwiper($obj = '', $config = ''): void
    {
        global $PAGE;
        if (!isset($this->_is_loaded['swiper'])) {
            $PAGE->requires->css("/local/moon/assets/swiper/swiper-bundle.min.css");
            $PAGE->requires->js('/local/moon/assets/swiper/swiper-bundle.min.js', true);
            $this->_is_loaded['swiper'] = true;
        }
        if (!empty($obj) && !empty($config)) {
            $this->addScriptDeclaration('new Swiper(\''.$obj.'\', {'.$config.'});');
        }
    }

    public function loadVideoBG(): void
    {
        if (!isset($this->_is_loaded['video_bg'])) {
            $this->addScript('/local/moon/assets/videobg/videobg.min.js');
            $this->_is_loaded['video_bg'] = true;
        }
    }
}
