<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;

$document = Framework::getDocument();

$params         = $this->params;
$element = $this;
$style = $element->style;
$title          = $params->get('title', '');
$image          = $params->get('image', '');
$image_dark     = $params->get('image_dark', '');
$figure_caption = $params->get('figure_caption', '');
$use_link       = $params->get('use_link', 0);
$link           = $params->get('link', '');
$target         = $params->get('target', '');
$target         = $target !== '' ? ' target="'.$target.'"' : '';

$shape          = $params->get('img_shape', '');

$border_radius      =   $params->get('img_border_radius', '');
$rounded_size       =   $params->get('image_rounded_size', '3');
if ($border_radius == 'rounded') {
    $border_radius  =   ' ' . $border_radius . '-' . $rounded_size;
} elseif($border_radius =='custom') {
    $image_radius=  $params->get('image_radius', '');
    if (!empty($image_radius)) {
        Style::setSpacingStyle($element->style->child('.moon-image-element'), $image_radius, 'radius');
    }
} else{
    $border_radius  =   $border_radius !== '' ? ' ' . $border_radius : '';
    }
$image_height      =   $params->get('image_height', '');
$image_width      =   $params->get('image_width', '');

$image_height_data = json_decode($image_height, true);
$image_width_data = json_decode($image_width, true);

if (json_last_error() === JSON_ERROR_NONE && is_array($image_width_data)) {
    $style->child('.moon-image-element')->addResponsiveCSS('width', $image_width_data, $image_width_data['postfix']);
}
if (json_last_error() === JSON_ERROR_NONE && is_array($image_height_data)) {
    $style->child('.moon-image-element')->addResponsiveCSS('height', $image_height_data, $image_height_data['postfix']);
}
$cus_cl = '';
if(isset($image_height) && isset($image_width)){
    $cus_cl = ' custom-size ';
}

$image_border    =   json_decode($params->get('image_border', ''), true);
if (!empty($image_border)) {
    Style::addBorderStyle('#'. $element->id . ' .as-image', $image_border, 'global', $element->isRoot);
}

$box_shadow     = $params->get('box_shadow', '');
$box_shadow     = $box_shadow !== '' ? ' ' . $box_shadow : '';
$hover_effect   = $params->get('hover_effect', '');
$hover_effect   = $hover_effect !== '' ? ' as-effect-' . $hover_effect : '';
$transition     = $params->get('hover_transition', '');
$transition     = $transition !== '' ? ' as-transition-' . $transition : '';
$display        = $params->get('display', '');
$display        = $display !== '' ? ' ' . $display : '';
if (!empty($image)) {
    if ($use_link) {
        echo '<a class="uk-inline" href="'.$link.'" title="'.$title.'"'.$target.'>';
    }

    if (!empty($figure_caption)) {
        echo '<figure class="m-0">';
    }
    echo '<div class="as-image-wrapper uk-overflow-hidden moon-image-element position-relative overflow-hidden'. $display .$cus_cl. $border_radius . $box_shadow . $hover_effect . $transition . '">';
    echo '<img class="as-image" src="'. $image .'" alt="'.$title.'">';
    if (!empty($image_dark)) {
        echo '<img class="as-image-dark d-none" src="'. $image_dark.'" alt="'.$title.'">';
        $this->style_dark->child('.as-image')->addCss('display', 'none !important');
        $this->style_dark->child('.as-image-dark')->addCss('display', 'inline-block !important');
    }
    echo '</div>';
    if (!empty($figure_caption)) {
        echo '<figcaption class="figure-caption">'.$figure_caption.'</figcaption>';
        echo '</figure>';
    }
    if ($use_link) {
        echo '</a>';
    }
}
$mask_scale         = $params->get('mask_scale', '');
$mask_repeat         = $params->get('mask_repeat', '');
$mask_position         = $params->get('mask_position', '');
if($shape=='style1'){
    $shape_style = '/local/moon/assets/images/shapes/style1.svg';
    $style->child('.as-image-wrapper img')->addCss('-webkit-mask-image', 'url('.$shape_style.')');
    $style->child('.as-image-wrapper img')->addCss('-webkit-mask-repeat', $mask_repeat);
    $style->child('.as-image-wrapper img')->addCss('-webkit-mask-position', $mask_position);
}
$mask_scale_size = json_decode($mask_scale, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($mask_scale_size)) {
    $element->style->child('.as-image-wrapper img')->addResponsiveCSS('-webkit-mask-size', $mask_scale_size, $mask_scale_size['postfix']);
}