<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;

$slides     = new SubForm($params->get('slides', ''));
if (!count($slides->getData())) {
    return false;
}

$document = Framework::getDocument();
$style = $element->style;
$style_dark = $element->style_dark;

$card_size          =   $params->get('overlay_padding', '');
$card_size          =   $card_size ? ' card-size-' . $card_size : '';

$media_position     =   $params->get('media_position', 'top');

$overlay_text_color =   $params->get('overlay_text_color', '');
$overlay_text_color =   $overlay_text_color !== '' ? ' ' . $overlay_text_color : '';
$min_height         =   $params->get('min_height', '');
$slider_height      =   $params->get('slider_height', '');
$overlay_max_width  =   $params->get('overlay_max_width', '');
$overlay_max_width  =   $overlay_max_width !== '' ? ' as-width-'. $overlay_max_width : '';
$autoplay           =   $params->get('autoplay', 0);
$interval           =   $params->get('interval', 5);
$interval           =   $interval * 1000;
$overlay_type       =   $params->get('overlay_type', '');
$overlay_color      =   $params->get('overlay_color', '');
$effect_type        =   $params->get('effect_type', 'theater');
$overlay_position   =   $params->get('overlay_position', 'justify-content-center align-items-center');
$overlay_position   =   $overlay_position !== '' ? ' ' . $overlay_position : '';


$title_html_element =   $params->get('title_html_element', 'h3');
$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    Style::renderTypography('#'.$element->id.' .moon-heading', $title_font_style, null, $element->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$meta_font_style    =   $params->get('meta_font_style');
if (!empty($meta_font_style)) {
    Style::renderTypography('#'.$element->id.' .moon-meta', $meta_font_style, null, $element->isRoot);
}
$meta_position      =   $params->get('meta_position', 'before');
$meta_heading_margin=   $params->get('meta_heading_margin', '');

$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    Style::renderTypography('#'.$element->id.' .moon-text', $content_font_style, null, $element->isRoot);
}
$button_size        =   $params->get('button_size', '');
$button_size        =   $button_size ? ' '. $button_size : '';

$btn_radius         =   $params->get('btn_border_radius', '');
$btn_radius         =   $btn_radius ? ' '. $btn_radius : '';

$image_height      =   $params->get('image_height', '');
$image_width      =   $params->get('image_width', '');

$image_height_data = json_decode($image_height, true);
$image_width_data = json_decode($image_width, true);

if (json_last_error() === JSON_ERROR_NONE && is_array($image_width_data)) {
    $style->child('.uk-slider-image')->addResponsiveCSS('width', $image_width_data, $image_width_data['postfix']);

}
if (json_last_error() === JSON_ERROR_NONE && is_array($image_height_data)) {
    $style->child('.uk-slider-image')->addResponsiveCSS('height', $image_height_data, $image_height_data['postfix']);

}
$image_radius=  $params->get('image_radius', '');
if (!empty($image_radius)) {
    Style::setSpacingStyle($element->style->child('.uk-slider-image'), $image_radius, 'radius');
}
$image_border    =   json_decode($params->get('image_border', ''), true);
if (!empty($image_border)) {
    Style::addBorderStyle('#'. $element->id . ' .uk-slider-image', $image_border, 'global', $element->isRoot);
}

$min_height = $params->get('min_height', '');
$max_height = $params->get('max_height', '');
$height = $params->get('slider_height', '');
$slideshow_transition = $params->get('slideshow_transition', '');

$attrs_slideshow[] = '';
$attrs_slideshow[] = ( isset( $slideshow_transition ) ) ? 'animation: ' . $slideshow_transition : '';
$attrs_slideshow[] = ( isset( $min_height ) ) ? 'min-height: ' . $min_height : 'min-height: 300';
$attrs_slideshow[] = ( isset( $max_height ) ) ? 'max-height: ' . $max_height : '';
$attrs_slideshow[] = (  $autoplay  ) ? 'autoplay: 1' : '';
$attrs_slideshow   = ' data-uk-slideshow="' . implode( '; ', array_filter( $attrs_slideshow ) ) . '"';


$height_cls = '';
if ( $height == 'full' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . '"';
} elseif ( $height == 'percent' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . 'offset-bottom: 20"';
} elseif ( $height == 'section' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . 'offset-bottom: !.elementor-section +"';
}

$document->loadUIKit();

echo '<div class="uk-position-relative uk-visible-toggle" '.$attrs_slideshow.'>';
echo '<div class="uk-slideshow-items">';
foreach ($slides->getData() as $key => $slide) {
    echo '<div class="container">';
    echo '<div class="row align-items-center">';

    echo '<div class="col-md-6 uk-slider-content order-2 order-md-1">';
    if (!empty($slide->params->get('meta')) && $meta_position == 'before') {
        echo '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
    }
    if (!empty($slide->params->get('title'))) {
        echo '<'.$title_html_element.' class="moon-heading">'. $slide->params->get('title') . '</'.$title_html_element.'>';
    }
    if (!empty($slide->params->get('meta')) && $meta_position == 'after') {
        echo '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
    }
    if (!empty($slide->params->get('description'))) {
        $content        = format_text($slide->params->get('description', ''), FORMAT_HTML, ['context' => $this->context]);
        echo '<div class="moon-text">' . $content . '</div>';
    }
    $target = !empty($slide->params->get('link_target')) ? ' target="'.$slide->params->get('link_target').'"' : '';
    if (!empty($slide->params->get('link'))) {
        echo '<div class="moon-button mt-5"><a class="btn btn-' .(intval($params->get('button_outline', 0)) ? 'outline-' : ''). $params->get('button_style', '') . $button_size . $btn_radius . '" href="' . $slide->params->get('link') . '"'.$target.'>' . $slide->params->get('link_title') . '</a></div>';
    }
    echo '</div>';

    echo '<div class="col-md-6 order-1 order-md-2">';
    echo '<div class="uk-flex uk-flex-right ">';
    echo '<div class="uk-slider-image uk-cover-container">';
    echo '<img data-uk-cover src="'. $slide->params->get('image') .'" class="object-fit-cover w-100 h-100" alt="'.$slide->params->get('title').'">';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
}
echo '</div>';
echo '
<a class="uk-position-center-left uk-position-small uk-hidden-hover" href data-uk-slidenav-previous data-uk-slideshow-item="previous"></a>
<a class="uk-position-center-right uk-position-small uk-hidden-hover" href data-uk-slidenav-next data-uk-slideshow-item="next"></a>
';
echo '</div>';


if (!empty($title_heading_margin)) {
    Style::setSpacingStyle($element->style->child('.moon-heading'), $title_heading_margin, 'margin');
}
if (!empty($meta_heading_margin)) {
    Style::setSpacingStyle($element->style->child('.moon-meta'), $meta_heading_margin, 'margin');
}