<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;
$style = $element->style;

$document = Framework::getDocument();
$document->loadUIKit();
$list_items     = new SubForm($params->get('list_items', ''));
if (!count($list_items->getData())) {
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
$vertical_align     =   $params->get('vertical_align', 'ul');
$list_style     =   $params->get('list_style', 'ul');
$title_width    =   intval($params->get('title_width', 3));
$heading    =   $params->get('title_heading', '');

$tag = match ($list_style) {
    'ol', 'list-group-numbered' => 'ol',
    'list-description' => 'dl',
    default => 'ul',
};

$class = match ($list_style) {
    'list-unstyled', 'list-inline', 'list-group' => $list_style,
    'list-group-flush', 'list-group-numbered' => 'list-group '. $list_style,
    'list-description' => 'row',
    default => ''
};

$class_item = match ($list_style) {
    'list-group', 'list-group-flush', 'list-group-numbered' => 'list-item list-group-item d-flex align-items-start',
    'list-inline' => 'list-item list-inline-item',
    default => 'list-item'
};

$class_item_inner = match ($list_style) {
    'list-group-numbered' => ' ms-2',
    default => ''
};
if($list_style=='custom'){
    $class = 'list-unstyled';
}
if($heading){
    echo '<h4 class="list-heading">'.$heading.'</h4>';
}
echo '<'.$tag.' class="' . $class . ' style-'.$list_style.'">';
foreach ($list_items->getData() as $list) {
    $icon_type      =   $list->params->get('icon_type', 'fontawesome');
    $icon_color          =   Style::getColor($list->params->get('icon_color_item', ''));
    $icon_bg_color          =   Style::getColor($list->params->get('icon_bg_item', ''));
    if($icon_color){
        $element->style->child('#tzlist-'.$list->id.' i')->addCss('color', $icon_color['light']);
        $element->style_dark->child('#tzlist-'.$list->id.' i')->addCss('color', $icon_color['dark']);
    }
    if($icon_bg_color){
        $element->style->child('#tzlist-'.$list->id.' .as-list-icon')->addCss('background-color', $icon_bg_color['light']);
        $element->style_dark->child('#tzlist-'.$list->id.' .as-list-icon')->addCss('background-color', $icon_bg_color['dark']);
    }

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
    } elseif($list_style === 'custom') {
        echo '<li id="tzlist-'.$list->id.'" class="'.$class_item.'">';
        echo '<div class="list-item-inner uk-flex'.$class_item_inner.' '.$vertical_align.'">';
        echo $icon ? '<div  class="as-list-icon"><i class="'.$icon.'"></i></div>' : '';
        echo '<div class="list-item-info">';
        echo $title_only ? '<'.$title_html_element.' class="as-list-title">'. $title_only . '</'.$title_html_element.'>' : '';
        echo $description ? '<div class="as-list-desc">'. $description . '</div>' : '';
        echo '</div>';
        echo '</div>';
        echo '</li>';
    } elseif($list_style === 'list-inline') {
        echo '<li id="tzlist-'.$list->id.'" class="'.$class_item.'">';
        echo '<div class="list-item-inner uk-flex'.$class_item_inner.' uk-child-1-3@m uk-grid-collapse" data-uk-grid>';
        echo $icon ? '<div  class="as-list-icon uk-width-auto"><i class="'.$icon.' me-2"></i></div>' : '';
        echo $title_only ? '<'.$title_html_element.' class="uk-width-auto as-list-title">'. $title_only . '</'.$title_html_element.'>' : '';
        echo $description ? '<div class="as-list-desc uk-width-expand@m">'. $description . '</div>' : '';
        echo '</div>';
        echo '</li>';
    } else {
        echo '<li class="'.$class_item.'">';
        echo '<div class="list-item-inner'.$class_item_inner.'">';
        echo $title ? '<'.$title_html_element.' class="as-list-title as-list-icon">'. $title . '</'.$title_html_element.'>' : '';
        echo $description ? '<div class="as-list-desc">'. $description . '</div>' : '';
        echo '</div>';
        echo '</li>';
    }
}
echo '</'.$tag.'>';

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

$icon_padding=  $params->get('icon_padding', '');
if (!empty($icon_padding)) {
    if($list_style === 'custom') {
        Style::setSpacingStyle($element->style->child('.as-list-icon'), $icon_padding, 'padding');

    }else{
        Style::setSpacingStyle($element->style->child('.as-list-icon i'), $icon_padding, 'padding');
    }
}
$icon_margin=  $params->get('icon_margin', '');
if (!empty($icon_margin)) {
    Style::setSpacingStyle($element->style->child('.as-list-icon'), $icon_margin, 'margin');
}
$icon_listsize        =   $params->get('icon_size', '');
$icon_size = json_decode($icon_listsize, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $style->child('.as-list-icon i')->addResponsiveCSS('font-size', $icon_size, $icon_size['postfix']);
}
$icon_height      =   $params->get('icon_height', '');
$icon_width      =   $params->get('icon_width', '');

$icon_height_data = json_decode($icon_height, true);
$icon_width_data = json_decode($icon_width, true);

if (json_last_error() === JSON_ERROR_NONE && is_array($icon_width_data)) {
    $style->child('.as-list-icon')->addResponsiveCSS('width', $icon_width_data, $icon_width_data['postfix']);
}
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_height_data)) {
    $style->child('.as-list-icon')->addResponsiveCSS('height', $icon_height_data, $icon_height_data['postfix']);
}
$icon_border    =   json_decode($params->get('icon_border', ''), true);
if (!empty($icon_border)) {
    Style::addBorderStyle('#'. $element->id . ' .as-list-icon', $icon_border, 'global', $element->isRoot);
}
$icon_radius=  $params->get('icon_radius', '');
if (!empty($icon_radius)) {
    Style::setSpacingStyle($element->style->child('.as-list-icon'), $icon_radius, 'radius');
}
$heading_font_style   =   $params->get('heading_font_style');
if (!empty($heading_font_style)) {
    Style::renderTypography('#'.$element->id.' .list-heading', $heading_font_style, null, $element->isRoot);
}
$heading_margin=  $params->get('heading_margin', '');
if (!empty($heading_margin)) {
    Style::setSpacingStyle($element->style->child('.list-heading'), $heading_margin, 'margin');
}