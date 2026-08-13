<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\Style;
$params         = $this->params;
$element = $this;

$style = $this->style;
$style_dark = $this->style_dark;
$title          = $params->get('title', '');
$html_element   = $params->get('html_element', 'h2');
$font_style     = $params->get('font_style', null);
$use_link       = $params->get('use_link', 0);
$link           = $params->get('link', '');
$add_icon       = $params->get('add_icon', 0);
$icon           = $params->get('icon', '');
$icon_color     = Style::getColor($params->get('icon_color', ''));
$style->child('.moon-icon')->addCss('color', $icon_color['light']);
$style_dark->child('.moon-icon')->addCss('color', $icon_color['dark']);

$title_heading_margin=  $params->get('title_heading_margin', '');

$title_clone       = $params->get('title_clone', 0);
$title_clone_txt           = $params->get('title_clone_txt', '');

// Meta
$meta = $params->get('meta_text', '');
$meta_font_style     = $params->get('meta_font_style', null);
$meta_heading_margin =  $params->get('meta_heading_margin', '');
$meta_heading_padding =  $params->get('meta_heading_padding', '');
$meta_position=  $params->get('meta_position', 'before');
$meta_border    =   json_decode($params->get('meta_border', ''), true);

if (!empty($meta_border)) {
    Style::addBorderStyle('#'. $element->id . ' .heading-meta', $meta_border, 'global', $element->isRoot);
}
$meta_radius=  $params->get('meta_radius', '');
if (!empty($meta_radius)) {
    Style::setSpacingStyle($element->style->child(' .heading-meta'), $meta_radius, 'radius');
}
$meta_cls = '';
$meta_line       = $params->get('meta_line', 0);
if($meta_line==1){
    $meta_cls = ' meta-line';
    $line_height      =   $params->get('line_height', '');
    $line_width      =   $params->get('line_width', '');
    $line_height_data = json_decode($line_height, true);
    $line_width_data = json_decode($line_width, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($line_width_data)) {
        $style->child('.meta-line:before')->addResponsiveCSS('width', $line_width_data, $line_width_data['postfix']);
    }
    if (json_last_error() === JSON_ERROR_NONE && is_array($line_height_data)) {
        $style->child('.meta-line:before')->addResponsiveCSS('height', $line_height_data, $line_height_data['postfix']);
    }
    $line_color     = Style::getColor($params->get('line_color', ''));
    $style->child('.meta-line:before')->addCss('background-color', $line_color['light']);
    $style_dark->child('.meta-line:before')->addCss('background-color', $line_color['dark']);
}

if (!empty($title)) {
    if (($meta !== '' || $meta_line === 1) && $meta_position === 'before') {
        echo '<div class="heading-meta '.$meta_cls.'">'.$meta.'</div>';
    }
    if ($use_link) {
        echo '<a href="'.$link.'" title="'.$title.'">';
    }
    echo '<'.$html_element.' class="heading">'. ($add_icon && $icon ? '<i class="'.$icon.' moon-icon me-2"></i>' : '') . $title . '</'.$html_element.'>';
    if ($use_link) {
        echo '</a>';
    }
    if($title_clone){
        echo '<div class="heading-clone position-absolute">'.$title_clone_txt.'</div>';
    }
    if (($meta !== '' || $meta_line === 1) && $meta_position === 'after') {
        echo '<div class="heading-meta '.$meta_cls.'">'.$meta.'</div>';
    }
}
if (!empty($font_style)) {
    Style::renderTypography('#'.$this->id.' .heading', $font_style, null, $this->isRoot);
}
if (!empty($title_heading_margin)) {
    Style::setSpacingStyle($this->style->child('.heading'), $title_heading_margin, 'margin');
}
if (!empty($meta_font_style)) {
    Style::renderTypography('#'.$this->id.' .heading-meta', $meta_font_style, null, $this->isRoot);
}
if (!empty($meta_heading_margin)) {
    Style::setSpacingStyle($this->style->child('.heading-meta'), $meta_heading_margin, 'margin');
}
if (!empty($meta_heading_padding)) {
    Style::setSpacingStyle($this->style->child('.heading-meta'), $meta_heading_padding);
}

$title_clone_margin=  $params->get('title_clone_margin', '');
if (!empty($title_clone_margin)) {
    Style::setSpacingStyle($this->style->child('.heading-clone'), $title_clone_margin, 'margin');
}

$clone_font_style     = $params->get('title_clone_font_style', null);
if (!empty($font_style)) {
    Style::renderTypography('#'.$this->id.' .heading-clone', $clone_font_style, null, $this->isRoot);
}