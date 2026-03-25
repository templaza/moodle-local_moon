<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;
$style = $this->style;
$progresss     = new SubForm($params->get('progress', ''));
if (!count($progresss->data)) {
    return false;
}
$text = '';
$text .='<div class="tz-progress">';
foreach ($progresss->data as $key => $item) {
    $title = $item->params->get('title', '');
    $percent = $item->params->get('percent', '');
    $text .='<div id="progress-'.$item->id.'" class="progress-item">';
    if($title){
        $text .='<h4>'.$title.'</h4>';
    }
    $text .='<div class="progress" role="progressbar" aria-label="" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar" style="width: '.$percent.'%">'.$percent.'%</div>
    </div>
    </div>';
    $color    =   Style::getColor($item->params->get('color', ''));
    if($color){
        $element->style->child('#progress-'.$item->id.' .progress-bar')->addCss('background-color', $color['light']);
        $element->style_dark->child('#progress-'.$item->id.' .progress-bar')->addCss('background-color', $color['dark']);
    }
}
$text .='</div>';

echo $text;
$text_font_style =   $params->get('text_font_style');
if (!empty($text_font_style)) {
    Style::renderTypography('#'.$element->id.' .text-inner', $text_font_style, null, $element->isRoot);
}

$title_margin   =   $params->get('title_margin', '');
if (!empty($title_margin)) {
    Style::setSpacingStyle($element->style->child('h4'), $title_margin, 'margin');
}
$item_margin   =   $params->get('item_margin', '');
if (!empty($item_margin)) {
    Style::setSpacingStyle($element->style->child('.progress'), $item_margin, 'margin');
}