<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;

$document = Framework::getDocument();

$params         = $this->params;
$element = $this;

$title          = $params->get('title', '');
$image          = $params->get('image', '');
$image_dark     = $params->get('image_dark', '');
$figure_caption = $params->get('figure_caption', '');
$use_link       = $params->get('use_link', 0);
$link           = $params->get('link', '');
$target         = $params->get('target', '');
$target         = $target !== '' ? ' target="'.$target.'"' : '';

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
        echo '<a href="'.$link.'" title="'.$title.'"'.$target.'>';
    }

    if (!empty($figure_caption)) {
        echo '<figure class="m-0">';
    }
    echo '<div class="as-image-wrapper uk-overflow-hidden moon-image-element position-relative overflow-hidden'. $display . $border_radius . $box_shadow . $hover_effect . $transition . '">';
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