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
 * @package   Moon Framework
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;

$galleries     = new SubForm($params->get('galleries', ''));
if (!count($galleries->getData())) {
    return false;
}

$document = Framework::getDocument();
$document->loadUIKit();
$document->loadFancyBox();
$style = $element->style;
$style_dark = $element->style_dark;


$masonry =   $params->get('masonry', 0);
if($masonry){
    $masonry = 'masonry:true';
}else{
    $masonry = '';
}

$thumbnail_hover           =   $params->get('thumbnail_hover', 0);
$thumbnail_hover_transition   =   $params->get('thumbnail_hover_transition', 'uk-transition-fade');
$cover_image           =   $params->get('cover_image', 0);
$image_lightbox           =   $params->get('image_lightbox', 0);
if($thumbnail_hover){
    $thumbnail_hover = 'uk-transition-toggle';
}else{
    $thumbnail_hover = '';
}

$gallery_column =   $params->get('gallery_column', 'col-lg-4');

$image_radius=  $params->get('image_radius', '');
if (!empty($image_radius)) {
    Style::setSpacingStyle($element->style->child('.ui-gallery-thumbnail'), $image_radius, 'radius');
    Style::setSpacingStyle($element->style->child('.uk-position-cover'), $image_radius, 'radius');
    $style->child('.ui-gallery-thumbnail')->addCss('overflow', 'hidden');
}
$overlay_padding   =   $params->get('overlay_padding', '');
if (!empty($overlay_padding)) {
    Style::setSpacingStyle($this->style->child('.uk-card-body'), $overlay_padding);
}
$column_padding   =   $params->get('column_padding', '');
if (!empty($column_padding)) {
    Style::setSpacingStyle($this->style->child('.gallery-item'), $column_padding);
}
$row_margin   =   $params->get('row_margin', '');
if (!empty($row_margin)) {
    Style::setSpacingStyle($this->style->child('* + .uk-grid-margin'), $row_margin,'margin');
}
$overlay_bg_color     = Style::getColor($params->get('overlay_bg_color', ''));
$style->child('.uk-overlay')->addCss('background-color', $overlay_bg_color['light']);
$style_dark->child('.uk-overlay')->addCss('background-color', $overlay_bg_color['dark']);

$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    Style::renderTypography('#'.$element->id.' .moon-heading', $title_font_style, null, $element->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$output  =   '';

$output  .=   '<div class="ui-gallery">';
$output  .=   '<div class="ui-gallery-inner">';
$output  .=   '<div class="ui-gallery-items row g-4"  data-uk-grid="masonry:true;">';
foreach ($galleries->getData() as $key => $slide) {
    $output .= '<div class="'.$gallery_column.' col-md-4 col-sm-12 gallery-item '.$thumbnail_hover.'">';
    $output .= '<div class="uk-article uk-card uk-overflow-hidden ">';

    $output .= '<div class="ui-gallery-thumbnail uk-display-block uk-card-media-top'.$cover_image.'">    
    <img class=" uk-transition-scale-up uk-transition-opaque" alt="'.$slide->params->get('title').'" src="'. $slide->params->get('image') .'" />
    </div>';

    $output .= '<div class="uk-position-cover uk-overlay uk-overlay-primary uk-transition-fade"></div>';

    $output .= '<div class="ui-gallery-info-wrap  uk-position-bottom uk-light '.$thumbnail_hover_transition.'">';
    $output .= '<div class="uk-card-body">';
    if($slide->params->get('title')){
        $output .= '<h3 class="ui-title uk-margin-remove-top">' . $slide->params->get('title') . '</h3>';
    }
    $output .= '</div>';
    $output .= '</div>';
    if($image_lightbox){
        $output .= '<a class="uk-position-cover gallery-lightbox" data-fancybox="gallery-'.$this->id.'" href="'. $slide->params->get('image') .'"></a>';
    }
    $output .=  '</div>';//end gallery item
    $output .=  '</div>';
}
$output     .=  '</div>';

$output     .=  '</div>';
$output     .=  '</div>';

echo $output;

$document->addScriptDeclaration("Fancybox.bind('[data-fancybox=\"gallery-{$this->id}\"]');");