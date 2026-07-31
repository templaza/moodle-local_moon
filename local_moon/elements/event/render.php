<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
use local_moon\library\Blocks\EventHandler;

$params = $this->params;
$element = $this;
$style = $element->style;

$document = Framework::getDocument();
$document->loadUIKit();
$list_events     = new SubForm($params->get('list_events', ''));


if (!count($list_events->getData())) {
    return false;
}
$title_html_element =   $params->get('title_html_element', 'h3');
$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    Style::renderTypography('#'.$element->id.' .as-list-title', $title_font_style, null, $element->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    Style::renderTypography('#'.$element->id.' .as-list-desc', $content_font_style, null, $element->isRoot);
}

$item_margin    =   $params->get('item_margin', '');
$item_padding   =   $params->get('item_padding', '');
$list_style     =   $params->get('list_style', 'ul');
$title_width    =   intval($params->get('title_width', 3));

if($list_style=='custom'){
    $class = 'list-unstyled';
}

echo '<div class="' . $class . '">';
foreach ($list_events->getData() as $list) {



    $icon_type      =   $list->params->get('icon_type', 'fontawesome');
    if ($icon_type === 'fontawesome') {
        $icon       =   $list->params->get('fa_icon', '');
    } else {
        $icon       =   $list->params->get('custom_icon', '');
    }
    $title_only = $list->params->get('title', '');
    $title          =   ($icon ? '<i class="'.$icon.' me-2"></i>' : '').$list->params->get('title', '');

    $description    =   $list->params->get('description', '');
    if ($list_style === 'list-description') {
        echo '<dt class="as-list-title as-list-icon col-'.$title_width.'">'.$title.'</dt>';
        echo '<dd class="as-list-desc col-'.($title_width < 12 ? 12-$title_width : 12).'">'.$description.'</dd>';
    }
}
echo '</div>';

if (!empty($title_heading_margin)) {
    Style::setSpacingStyle($element->style->child('.as-list-title'), $title_heading_margin, 'margin');
}

// Item Margin
if (!empty($item_margin)) {
    Style::setSpacingStyle($element->style->child('.list-item'), $item_margin, 'margin');
}
// Item Padding
if (!empty($item_padding)) {
    Style::setSpacingStyle($element->style->child('.list-item'), $item_padding);
}
$icon_color   =   Style::getColor($params->get('icon_color', ''));
$element->style->child('.as-list-icon i')->addCss('color', $icon_color['light']);
$element->style_dark->child('.as-list-icon i')->addCss('color', $icon_color['dark']);

$icon_margin=  $params->get('icon_padding', '');
if (!empty($icon_margin)) {
    if($list_style === 'custom') {
        Style::setSpacingStyle($element->style->child('.as-list-icon'), $icon_margin, 'padding');

    }else{
        Style::setSpacingStyle($element->style->child('.as-list-icon i'), $icon_margin, 'padding');
    }
}
$icon_listsize        =   $params->get('icon_size', '');
$icon_size = json_decode($icon_listsize, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $style->child('.as-list-icon i')->addResponsiveCSS('font-size', $icon_size, $icon_size['postfix']);
}