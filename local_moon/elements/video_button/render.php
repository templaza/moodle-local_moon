<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\Style;
use local_moon\library\Framework;
$params         = $this->params;
$title                  = $params->get('title', '');
$url                    = $params->get('url', '');
$button_size            = $params->get('button_size', 24);
$width                  = $params->get('width', '');
$height                 = $params->get('height', '');
$use_border             = $params->get('use_border', '');
$border_width           = $params->get('border_width', '');
$ripple_color           = Style::getColor($params->get('ripple_color', ''));
$color                  = Style::getColor($params->get('color', ''));
$color_hover            = Style::getColor($params->get('color_hover', ''));
$background_color       = Style::getColor($params->get('background_color', ''));
$background_color_hover = Style::getColor($params->get('background_color_hover', ''));
$border_color           = Style::getColor($params->get('border_color', ''));

if (!empty($url)) {
    echo '<a class="video-button button-ripple d-inline-flex align-items-center justify-content-center rounded-pill" href="'.$url.'" title="'.$title.'" data-fancybox="moon-'.$this->id.'"><span class="d-inline-flex justify-content-center align-items-center"><i class="fas fa-play"></i></span></a>';
    $document = Framework::getDocument();
    $document->loadFancyBox();
    $document->addScriptDeclaration("Fancybox.bind('[data-fancybox=\"moon-{$this->id}\"]');");
    $style = $this->style;
    $style_dark = $this->style_dark;

    $style->child('.video-button')->addCss('font-size', $button_size . 'px');
    $style->child('.video-button i')->addCss('width', $button_size . 'px');
    $style->child('.video-button i')->addCss('height', $button_size . 'px');

    if ($ripple_color) {
        $style->child('.button-ripple:before')->addCss('box-shadow', '0 0 0 0 '.$ripple_color['light']);
        $style->child('.button-ripple:after')->addCss('box-shadow', '0 0 0 0 '.$ripple_color['light']);
        $style_dark->child('.button-ripple:before')->addCss('box-shadow', '0 0 0 0 '.$ripple_color['dark']);
        $style_dark->child('.button-ripple:after')->addCss('box-shadow', '0 0 0 0 '.$ripple_color['dark']);
    }

    $style->child('.video-button')->addCss('color', $color['light']);
    $style_dark->child('.video-button')->addCss('color', $color['dark']);
    $style->child('.video-button')->addCss('background-color', $background_color['light']);
    $style_dark->child('.video-button')->addCss('background-color', $background_color['dark']);

    $style->child('.video-button')->hover()->addCss('color', $color_hover['light']);
    $style_dark->child('.video-button')->hover()->addCss('color', $color_hover['dark']);
    $style->child('.video-button')->hover()->addCss('background-color', $background_color_hover['light']);
    $style_dark->child('.video-button')->hover()->addCss('background-color', $background_color_hover['dark']);

    $style->child('.video-button')->addCss('width', $width. 'px');
    $style->child('.video-button:before')->addCss('width', $width. 'px');
    $style->child('.video-button:after')->addCss('width', $width. 'px');

    $style->child('.video-button')->addCss('height', $height. 'px');
    $style->child('.video-button:before')->addCss('height', $height. 'px');
    $style->child('.video-button:after')->addCss('height', $height. 'px');

    if ($use_border) {
        $style->child('.video-button')->addCss('border-style', 'solid');
        $style->child('.video-button')->addCss('border-color', $border_color['light']);
        $style_dark->child('.video-button')->addCss('border-color', $border_color['dark']);
        $style->child('.video-button')->addCss('border-width', $border_width . 'px');
    }
}