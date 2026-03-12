<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;

$pricing_items     = new SubForm($params->get('pricing_items', ''));
if (!count($pricing_items->getData())) {
    return false;
}

$document = Framework::getDocument();
$document->loadUIKit();
$style = $element->style;
$style_dark = $element->style_dark;

$price   =   $params->get('price', '');
$title   =   $params->get('title', '');
$price_meta   =   $params->get('meta', '');
$meta_alignment   =   $params->get('meta_alignment', '');

$symbol   =   $params->get('price_symbol', '');
$symbol_pos   =   $params->get('symbol_pos', '');

$price_description   =   $params->get('description', '');
$label_text   =   $params->get('label_text', '');

$price_icon = $params->get('price_icon', '');
$button_url = $params->get('button_url', '');
$button_text = $params->get('button_text', '');
$button_target = !empty($params->get('button_target')) ? ' target="'.$params->get('button_target').'"' : '';

$icon_on_price = '';
if(isset($price_icon)){
   $icon_on_price   .=  '<div class="ui_icon_on_price"><i class="' . $price_icon .'" aria-hidden="true"></i></div>';
}
$overlay_padding   =   $params->get('overlay_padding', '');
if (!empty($overlay_padding)) {
    Style::setSpacingStyle($this->style->child('.uk-card-body'), $overlay_padding);
}

$overlay_bg_color     = Style::getColor($params->get('overlay_bg_color', ''));
$style->child('.uk-position-cover')->addCss('background-color', $overlay_bg_color['light']);
$style_dark->child('.uk-position-cover')->addCss('background-color', $overlay_bg_color['dark']);


$output     =   '<div class="ui-pricing ui-pricing-body ui-pricing-style1 uk-card">';



$output     .=  '<div class=" uk-margin-remove-first-child uk-card-body">';
if ( $title || $price_meta) {
    $output .= '<div class="ui-pricing-header">';
    if ( $meta_alignment == 'top' ) {
        $output .= ( $price_meta ) ? '<span class="plan-period">' . $price_meta . '</span>' : '';
    }
    $output .= '<h2 class="uk-card-title pricing-title uk-inline">'.$title.'</h2>';
    if ( $meta_alignment == 'inline' ) {
        $output .= ( $price_meta ) ? '<span class="plan-period">' . $price_meta . '</span>' : '';
    }
    if ( empty( $meta_alignment ) ) {
        $output .= ( $price_meta ) ? '<span class="plan-period">' . $price_meta . '</span>' : '';
    }
    $output .= '</div>';
}

$output .= $icon_on_price;
$output .= '<div class="pricing-value">';
if($symbol_pos !='right'){
    $output .= ( $symbol ) ? '<span class="pricing-symbol">' . $symbol . '</span>' : '';
}
$output .= ( $price ) ? '<span class="pricing-amount">' . $price . '</span>' : '';
if($symbol_pos =='right'){
    $output .= ( $symbol ) ? '<span class="pricing-symbol">' . $symbol . '</span>' : '';
}
$output .= ( $label_text ) ? '<div class="tz-price-table_featured f-2"><div class="tz-price-table_featured-inner">' . $label_text . '</div></div>' : '';
$output .= '</div>';

$output .= ( $price_description ) ? '<div class="plan-description">' . $price_description . '</div>' : '';


    $output .= '<div class="pricing-features">';

    $output .= '<ul class="uk-list">';

    foreach ( $pricing_items->getData() as $key => $item) {
        $text       = $item->params->get('item_title','');
        $text_color   = Style::getColor($item->params->get('item_title_color', ''));
        $item_icon_color   = Style::getColor($item->params->get('item_icon_color', ''));
        $key++;
        $item_key = '.repeater-item-'.$key.'';
        $style->child(''.$item_key.' .el-content')->addCss('color', $text_color['light']);
        $style_dark->child(''.$item_key.' .el-content')->addCss('color', $text_color['dark']);
        $style->child(''.$item_key.' .pricing-icon')->addCss('color', $item_icon_color['light']);
        $style_dark->child(''.$item_key.' .pricing-icon')->addCss('color', $item_icon_color['dark']);

        //Icon style
        $icon               = $item->params->get('item_icon','');
        $media              = '';
        if ($icon) {
          $media   .=  '<i class="' . $icon .'" aria-hidden="true"></i>';
        }

        $output .= '<li class="ui-item repeater-item-'.$key.'">';
        if ( $media ) {
            $output .= '<div class="uk-grid-small uk-child-width-expand uk-flex-nowrap uk-flex-middle" data-uk-grid>';
            $output .= '<div class="uk-width-auto pricing-icon uk-flex uk-flex-middle ">';
            $output .= $media;
            $output .= '</div>';
            $output .= '<div>';
        }

        $output .= '<div class="el-content uk-panel">';
        $output .= $text;
        $output .= '</div>';

        if ( $media ) {
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</li>';
    }
    $output .= '</ul>';

    $output .= '</div>';


$output     .=  $button_url ? '<div class="ui-button"><a class="uk-button uk-width-1-1" href="'.$button_url.'"'.$button_target.'>'.$button_text.'</a></div>' : '';

$output     .=  '</div>';

$output     .=  '</div>';
echo $output;

$title_border    =   json_decode($params->get('title_border', ''), true);
if (!empty($title_border)) {
    Style::addBorderStyle('#'. $element->id . ' .uk-card-title', $title_border, 'global', $element->isRoot);
}
$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    Style::renderTypography('#'.$element->id.' .uk-card-title', $title_font_style, null, $element->isRoot);
}
$title_heading_margin   =   $params->get('title_heading_margin', '');
if (!empty($title_heading_margin)) {
    Style::setSpacingStyle($element->style->child('.uk-card-title'), $title_heading_margin, 'margin');
}
$title_heading_padding   =   $params->get('title_heading_padding', '');
if (!empty($title_heading_padding)) {
    Style::setSpacingStyle($element->style->child('.uk-card-title'), $title_heading_padding);
}
$title_radius  =   $params->get('title_radius', '');
if (!empty($title_radius)) {
    Style::setSpacingStyle($element->style->child('.uk-card-title'), $title_radius,'radius');
}

$pricing_font_style   =   $params->get('pricing_font_style');
if (!empty($pricing_font_style)) {
    Style::renderTypography('#'.$element->id.' .pricing-amount', $pricing_font_style, null, $element->isRoot);
}
$price_margin   =   $params->get('price_margin', '');
if (!empty($price_margin)) {
    Style::setSpacingStyle($element->style->child('.pricing-value'), $price_margin, 'margin');
}
$price_icon_size      =   $params->get('price_icon_size', '');
$icon_size = json_decode($price_icon_size, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $style->child('.ui_icon_on_price')->addResponsiveCSS('font-size', $icon_size, $icon_size['postfix']);
}
$price_icon_color     = Style::getColor($params->get('price_icon_color', ''));
$style->child('.ui_icon_on_price')->addCss('color', $price_icon_color['light']);
$style_dark->child('.ui_icon_on_price')->addCss('color', $price_icon_color['dark']);

$symbol_font_style   =   $params->get('symbol_font_style');
if (!empty($symbol_font_style)) {
    Style::renderTypography('#'.$element->id.' .pricing-symbol', $symbol_font_style, null, $element->isRoot);
}
$symbol_margin   =   $params->get('symbol_margin', '');
if (!empty($symbol_margin)) {
    Style::setSpacingStyle($element->style->child('.pricing-symbol'), $symbol_margin, 'margin');
}

$description_font_style   =   $params->get('description_font_style');
if (!empty($description_font_style)) {
    Style::renderTypography('#'.$element->id.' .plan-description', $description_font_style, null, $element->isRoot);
}

$listing_border    =   json_decode($params->get('listing_border', ''), true);
if (!empty($listing_border)) {
    Style::addBorderStyle('#'. $element->id . ' .pricing-features', $listing_border, 'global', $element->isRoot);
}
$listing_margin   =   $params->get('listing_margin', '');
if (!empty($listing_margin)) {
    Style::setSpacingStyle($element->style->child('.pricing-features'), $listing_margin, 'margin');
}
$listing_padding   =   $params->get('listing_padding', '');
if (!empty($listing_padding)) {
    Style::setSpacingStyle($element->style->child('.pricing-features'), $listing_padding);
}

$button_font_style   =   $params->get('button_font_style');
if (!empty($button_font_style)) {
    Style::renderTypography('#'.$element->id.' .uk-button', $button_font_style, null, $element->isRoot);
}
$button_margin   =   $params->get('button_margin', '');
if (!empty($button_margin)) {
    Style::setSpacingStyle($element->style->child('.ui-button'), $button_margin, 'margin');
}
$button_padding   =   $params->get('button_padding', '');
if (!empty($button_padding)) {
    Style::setSpacingStyle($element->style->child('.uk-button'), $button_padding);
}
$button_radius  =   $params->get('button_radius', '');
if (!empty($button_radius)) {
    Style::setSpacingStyle($element->style->child('.uk-button'), $button_radius,'radius');
}
$button_border    =   json_decode($params->get('button_border', ''), true);
if (!empty($button_border)) {
    Style::addBorderStyle('#'. $element->id . ' .uk-button', $button_border, 'global', $element->isRoot);
}
$button_bg_color     = Style::getColor($params->get('button_bg_color', ''));
$style->child('.uk-button')->addCss('background-color', $button_bg_color['light']);
$style_dark->child('.uk-button')->addCss('background-color', $button_bg_color['dark']);

$button_bg_color_hover     = Style::getColor($params->get('button_bg_color_hover', ''));
$style->child('.uk-button:hover')->addCss('background-color', $button_bg_color_hover['light']);
$style_dark->child('.uk-button:hover')->addCss('background-color', $button_bg_color_hover['dark']);

$button_color_hover     = Style::getColor($params->get('button_color_hover', ''));
$style->child('.uk-button:hover')->addCss('color', $button_color_hover['light']);
$style_dark->child('.uk-button:hover')->addCss('color', $button_color_hover['dark']);
