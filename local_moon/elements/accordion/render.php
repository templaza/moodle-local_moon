<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;
$accordions     = new SubForm($params->get('accordions', ''));
if (!count($accordions->data)) {
    return false;
}
$document = Framework::getDocument();
$document->loadUIKit();
$box_shadow         =   $params->get('box_shadow', '');
$box_shadow         =   $box_shadow ? ' ' . $box_shadow : '';
$box_shadow_hover   =   $params->get('box_shadow_hover', '');
$box_shadow_hover   =   $box_shadow_hover ? ' ' . $box_shadow_hover : '';

$style          = $params->get('style', '');
$style          = $params->get('style', '');
$style          = $style !== '' ? ' '. $style : '';

$collapse       = $params->get('collapse', '');
$always_open    = $params->get('always_open', 0);
//$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
//$wa->useScript('bootstrap.collapse');
echo '<div class="accordion'.$style.'" id="accordion-'.$element->id.'">';
foreach ($accordions->data as $key => $accordion) {
    echo '<div class="accordion-item '.$box_shadow . $box_shadow_hover.'">';

    echo '<h2 class="accordion-header">';
    echo '<button class="accordion-button'.($key != 0 || $collapse === 'close-all' ? ' collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'.$element->id.$key.'" aria-expanded="true" aria-controls="collapse'.$element->id.$key.'">'.$accordion->params->get('title', '').'</button>';
    echo '</h2>';

    echo '<div id="collapse'.$element->id.$key.'" class="accordion-collapse collapse'.($key == 0 && $collapse === '' ? ' show' : '').'"'.(!$always_open ? ' data-bs-parent="#accordion-'.$element->id.'"' : '').'>';
    echo '<div class="accordion-body">'. $accordion->params->get('content', '') . '</div>';
    echo '</div>';

    echo '</div>';
}
echo '</div>';

$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    Style::renderTypography('#'.$element->id.' .accordion-button', $title_font_style, null, $element->isRoot);
}

$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    Style::renderTypography('#'.$element->id.' .accordion-body', $content_font_style, null, $element->isRoot);
}

$color          = Style::getColor($params->get('color', ''));
$color_hover    = Style::getColor($params->get('color_hover', ''));
$color_active   = Style::getColor($params->get('color_active', ''));
$bgcolor        = Style::getColor($params->get('bgcolor', ''));
$bgcolor_hover  = Style::getColor($params->get('bgcolor_hover', ''));
$bgcolor_active = Style::getColor($params->get('bgcolor_active', ''));

$bgcolor_content        = Style::getColor($params->get('bgcolor_content', ''));

// Color style
$element->style->child('.accordion-button')->addCss('color', $color['light']);
$element->style_dark->child('.accordion-button')->addCss('color', $color['dark']);
$element->style->child('.accordion-button')->hover()->addCss('color', $color_hover['light']);
$element->style_dark->child('.accordion-button')->hover()->addCss('color', $color_hover['dark']);
$element->style->child('.accordion-button:not(.collapsed)')->addCss('color', $color_active['light']);
$element->style_dark->child('.accordion-button:not(.collapsed)')->addCss('color', $color_active['dark']);

// Background color style
$element->style->child('.accordion-button')->addCss('background-color', $bgcolor['light']);
$element->style_dark->child('.accordion-button')->addCss('background-color', $bgcolor['dark']);
$element->style->child('.accordion-button')->hover()->addCss('background-color', $bgcolor_hover['light']);
$element->style_dark->child('.accordion-button')->hover()->addCss('background-color', $bgcolor_hover['dark']);
$element->style->child('.accordion-button:not(.collapsed)')->addCss('background-color', $bgcolor_active['light']);
$element->style_dark->child('.accordion-button:not(.collapsed)')->addCss('background-color', $bgcolor_active['dark']);

//content background

$element->style->child('.accordion-body')->addCss('background-color', $bgcolor['light']);
$element->style_dark->child('.accordion-body')->addCss('background-color', $bgcolor['dark']);


$title_padding   =   $params->get('title_padding', '');
if (!empty($title_padding)) {
    Style::setSpacingStyle($element->style->child('.accordion-button'), $title_padding);
}
$content_padding   =   $params->get('content_padding', '');
if (!empty($content_padding)) {
    Style::setSpacingStyle($element->style->child('.accordion-body'), $content_padding);
}
$title_border    =   json_decode($params->get('title_border', ''), true);
if (!empty($title_border)) {
    Style::addBorderStyle('#'. $element->id . ' .accordion-button', $title_border, 'global', $element->isRoot);
}
$item_radius  =   $params->get('item_radius', '');
if (!empty($item_radius)) {
    Style::setSpacingStyle($element->style->child('.accordion-item'), $item_radius,'radius');
}
$title_radius  =   $params->get('title_radius', '');
if (!empty($title_radius)) {
    Style::setSpacingStyle($element->style->child('.accordion-button'), $title_radius,'radius');
}
$item_margin=  $params->get('item_margin', '');
if (!empty($item_margin)) {
    Style::setSpacingStyle($element->style->child('.accordion-item'), $item_margin, 'margin');
}