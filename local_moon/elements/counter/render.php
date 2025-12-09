<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;
$items     = new SubForm($params->get('items', ''));
if (!count($items->data)) {
    return false;
}
$document = Framework::getDocument();
$style = $element->style;
$style_dark = $element->style_dark;
$row_column_cls     =   '';

$responsive_key     =   ['xxl', 'xl', 'lg', 'md', 'sm', 'xs'];
foreach ($responsive_key as $key) {
    $default        =   match ($key) {
        'xxl', 'xl' =>  '',
        'lg'        =>  3,
        default     =>  1
    };
    $column         =   $params->get($key . '_column', $default);

    if ($key !== 'xs') {
        $row_column_cls     .=  $column ? ' row-cols-'. $key .'-' . $column : '';
        $row_gutter         =   $params->get('row_gutter_'.$key, '');
        $column_gutter      =   $params->get('column_gutter_'. $key, '');
        if ($row_gutter) {
            $row_column_cls .=  ' gy-' . $key . '-' . $row_gutter;
        }
        if ($column_gutter) {
            $row_column_cls .=  ' gx-' . $key . '-' . $column_gutter;
        }
    } else {
        $row_column_cls     .=  $column ? ' row-cols-' . $column : '';
        $row_gutter         =   $params->get('row_gutter', 3);
        $column_gutter      =   $params->get('column_gutter', 3);
        $row_column_cls     .=  ' gy-' . $row_gutter;
        $row_column_cls     .=  ' gx-' . $column_gutter;
    }
}

$card_style         =   $params->get('card_style', '');
$card_style         =   $card_style ? ' text-bg-' . $card_style : '';

$card_size          =   $params->get('card_size', '');
$card_size          =   $card_size ? ' card-size-' . $card_size : '';

$card_rounded_size  =   $params->get('card_rounded_size', '3');
$border_radius      =   $params->get('card_border_radius', '');
$bd_radius          =   $border_radius != '' ? ' rounded-' . $border_radius : ' rounded-' . $card_rounded_size;

$enable_grid_match  =   $params->get('enable_grid_match', 0);

$box_shadow         =   $params->get('card_box_shadow', '');
$box_shadow         =   $box_shadow ? ' ' . $box_shadow : '';
$box_shadow_hover   =   $params->get('card_box_shadow_hover', '');
$box_shadow_hover   =   $box_shadow_hover ? ' ' . $box_shadow_hover : '';

$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    Style::renderTypography('#'.$element->id.' .moon-text', $content_font_style, null, $element->isRoot);
}

$transition     = $params->get('hover_transition', '');
$transition     = $transition !== '' ? ' as-transition-' . $transition : '';

$card_hover_transition     = $params->get('card_hover_transition', '');
$card_hover_transition     = $card_hover_transition !== '' ? ' as-transition-' . $card_hover_transition : '';

$use_masonry        =   $params->get('use_masonry', 0);
echo '<div class="row'.($use_masonry ? ' as-masonry as-loading' : '').$row_column_cls.'">';
foreach ($items->data as $key => $grid) {

    echo '<div id="grid-'. $grid -> id .'" class="as-grid"><div class="card' . $card_style . $box_shadow . $box_shadow_hover .$bd_radius . $card_hover_transition . ($enable_grid_match ? ' h-100' : '') . '">';

    echo '<div class="card-body' . $card_size . '">'; // Start Card-Body

    if (!empty($grid->params->get('title', ''))) {
        echo '<div class="moon-number">' . $grid->params->get('title', '') . '</div>';
    }

    echo '</div>'; // End Card-Body

    echo '</div></div>';
}
echo '</div>';
if ($use_masonry) {
    $document->loadMasonry('#'. $element->id .' .as-masonry');
}
if ($params->get('card_style', '') == 'custom') {
    $text_color     =   Style::getColor($params->get('text_color', ''));
    $style->child('.as-grid > .card')->addCss('color', $text_color['light']);
    $style_dark->child('.as-grid > .card')->addCss('color', $text_color['dark']);

    $bg_color       =   Style::getColor($params->get('bg_color', ''));
    $style->child('.as-grid > .card')->addCss('background-color', $bg_color['light']);
    $style_dark->child('.as-grid > .card')->addCss('background-color', $bg_color['dark']);

    $card_border    =   json_decode($params->get('card_border', ''), true);
    if (!empty($card_border)) {
        Style::addBorderStyle('#'. $element->id . ' .as-grid > .card', $card_border, 'global', $element->isRoot);
    }
}