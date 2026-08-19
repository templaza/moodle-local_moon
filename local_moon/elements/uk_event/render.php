<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
use local_moon\library\Blocks\EventHandler;

$params = $this->params;
$style = $this->style;
$style_dark = $this->style_dark;
$document = Framework::getDocument();
$document->loadUIKit();
$list_events     = new SubForm($params->get('list_events', ''));

if (!count($list_events->getData())) {
    return false;
}
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

        $row_column_cls .=  ' gy-' . $key . '-' . $row_gutter;
        $row_column_cls .=  ' gx-' . $key . '-' . $column_gutter;

    } else {
        $row_column_cls     .=  $column ? ' row-cols-' . $column : '';
        $row_gutter         =   $params->get('row_gutter', 3);
        $column_gutter      =   $params->get('column_gutter', 3);
        $row_column_cls     .=  ' gy-' . $row_gutter;
        $row_column_cls     .=  ' gx-' . $column_gutter;
    }
}
$content_hover_transition     = $params->get('media_hover_transition', '');

$enable_image_cover =   $params->get('enable_image_cover', 0);

$event_layout   =   $params->get('event_layout','');
$button_text   =   $params->get('button_text','');
$button_icon   =   $params->get('button_icon','');
$button_align   =   $params->get('button_align','');

$image_layout   =   $params->get('image_layout');
$image_height      =   $params->get('image_height', '');
$image_height_data = json_decode($image_height, true);

$overlay_html = $img_attr= $content_position= $start_icon = $end_icon=$img_transition=$toggle='';
if($image_layout=='overlay'){
    $overlay_html = '<div class="moon-bg-overlay uk-position-cover"></div>';
    $content_position     = 'uk-overlay '. $params->get('content_position', '').'';
}
if($image_height_data['global'] !='' || $image_height_data['larger_desktop'] !=''  || $image_height_data['desktop'] !=''  || $image_height_data['tablet'] !=''  || $image_height_data['mobile'] !='' ){
    $img_attr = 'data-uk-cover';
}
if ($params->get('start_icon', '')) {
    $start_icon      = '<i class="'.$params->get('start_icon', '').'"></i>';
}
if ($params->get('end_icon', '')) {
    $end_icon      = '<i class="'.$params->get('end_icon', '').'"></i>';
}
$image_hover_transition      =   $params->get('image_hover_transition', '');
if($image_hover_transition !=''){
    $toggle = ' uk-transition-toggle';
    $img_transition = ' uk-transition-scale-up uk-transition-opaque';
}
$event_cls = 'row '.$row_column_cls.'';
if($event_layout=='list'){
    $event_cls = ' event-element';
}
echo '<div class="'.$event_cls.'">';
foreach ($list_events->data as $key => $grid) {
    $media          =   '';
    global $DB;
    $eventid = $grid->params->get('event', '');
    if($eventid){
        $event = $DB->get_record('event', ['id' => $eventid]);
        $moonEventHandler = new EventHandler();
        $url = $moonEventHandler->moon_get_event_link($eventid);
    }
    if ($grid->params->get('image', '')) {
        $media      = '<div class="event-media  uk-position-relative uk-overflow-hidden">';
        $media     .= '<a href="'.$url.'"><img '.$img_attr.' class="tz-img-grid '.$img_transition.'" src="'. $grid->params->get('image', '') .'" alt="'.$event->name.'"></a>';
        $media     .= '</div>';
    }
    if($event_layout=='list'){
        echo '<div id="event-'. $grid -> id .'" class="moon-event">
        <div class="event-item row uk-flex uk-flex-middle">';
        if($eventid){
            $date = userdate($event->timestart);
            $timestamp = strtotime($date);
            $day = date('d', $timestamp);

            echo '<div class="col-md-2 text-md-center">';
            echo '<div class="start-day">' .$day. '</div>';
            echo '<div class="start-month">' .date('F, Y', $timestamp). '</div>';
            echo '</div>';
            echo '<div class="col-md-8 event-summary">';
            echo '<h3 class="event-title"><a href="'.$url.'">' .$event->name.'</a></h3>';
            echo '<div class="event-end event-duration uk-flex uk-flex-middle">'.$end_icon.' ' . userdate($event->timestart + $event->timeduration).'</div>';
            echo '</div>';
            if($button_text || $button_icon){
                echo '<div class="col-md-2 '.$button_align.' ">';
                echo '<div class="event-btn"><a class="event-readmore uk-inline-block" href="'.$url.'">' .$button_text.' <i class="'.$button_icon.'"></i></a></div>';
                echo '</div>';
            }

            echo '</div></div>';
        }
    }else{
        echo '<div id="event-'. $grid -> id .'" class="moon-event "><div class="event-item uk-overflow-hidden uk-position-relative '.$toggle.'">';
        echo $media;
        if($eventid){
            echo $overlay_html;
            echo '<div class="event-summary '.$content_position.'"> <h3 class="event-title"><a href="'.$url.'">' .$event->name.'</a></h3>';
            echo '<div class="event-duration"><div class="event-start uk-flex uk-flex-middle">'.$start_icon.' ' . userdate($event->timestart).'</div>';
            echo '<div class="event-end uk-flex uk-flex-middle">'.$end_icon.' ' . userdate($event->timestart + $event->timeduration).'</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div></div>';
    }

}
echo '</div>';

$item_bg_color     = Style::getColor($params->get('item_bg_color', ''));
$style->child('.event-item')->addCss('background-color', $item_bg_color['light']);
$style_dark->child('.event-item')->addCss('background-color', $item_bg_color['dark']);
$item_border    =   json_decode($params->get('item_border', ''), true);
if (!empty($item_border)) {
    Style::addBorderStyle('#'. $this->id . ' .event-item', $item_border, 'global', $this->isRoot);
}
$item_border_radius      =   $params->get('item_border_radius', '');
if (!empty($item_border_radius)) {
    Style::setSpacingStyle($this->style->child('.event-item'), $item_border_radius,'radius');
}
$item_card_padding   =   $params->get('item_card_padding', '');
if (!empty($item_card_padding)) {
    Style::setSpacingStyle($this->style->child('.event-item'), $item_card_padding);
}
$content_padding   =   $params->get('content_padding', '');
if (!empty($content_padding)) {
    Style::setSpacingStyle($this->style->child('.event-summary'), $content_padding);
}
$overlay_type       =   $params->get('overlay_type', '');
switch ($overlay_type) {
    case 'color':
        $overlay_color      =   Style::getColor($params->get('overlay_color', ''));
        $style->child('.moon-bg-overlay')->addCss('background-color', $overlay_color['light']);
        $style_dark->child('.moon-bg-overlay')->addCss('background-color', $overlay_color['dark']);
        break;
    case 'gradient':
        $overlay_gradient   =   $params->get('overlay_gradient', '');
        if (!empty($overlay_gradient)) {
            $style->child('.moon-bg-overlay')->addCss('background-image', Style::getGradientValue($overlay_gradient));
        }
        break;
}

$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    Style::renderTypography('#'.$this->id.' .event-title a', $title_font_style, null, $this->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');
if (!empty($title_heading_margin)) {
    Style::setSpacingStyle($this->style->child('.event-title'), $title_heading_margin, 'margin');
}
$duration_font_style   =   $params->get('duration_font_style');
if (!empty($duration_font_style)) {
    Style::renderTypography('#'.$this->id.' .event-duration', $duration_font_style, null, $this->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');
if (!empty($title_heading_margin)) {
    Style::setSpacingStyle($this->style->child('.event-title'), $title_heading_margin, 'margin');
}
$image_radius      =   $params->get('image_radius', '');
if (!empty($image_radius)) {
    Style::setSpacingStyle($this->style->child('.event-media'), $image_radius,'radius');
}
if (json_last_error() === JSON_ERROR_NONE && is_array($image_height_data)) {
    $style->child('.event-media')->addResponsiveCSS('height', $image_height_data, $image_height_data['postfix']);
}
$icon_size        =   $params->get('icon_size', '12');
$icon_size = json_decode($icon_size, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($icon_size)) {
    $style->child('.event-duration i')->addResponsiveCSS('font-size', $icon_size, $icon_size['postfix']);
}
$icon_margin   =   $params->get('icon_margin', '');
if (!empty($icon_margin)) {
    Style::setSpacingStyle($style->child('.event-duration i'), $icon_margin,'margin');
}

$button_color     = Style::getColor($params->get('button_color', ''));
$button_hover_color     = Style::getColor($params->get('button_color_hover', ''));
$button_bg_color     = Style::getColor($params->get('button_bg_color', ''));
$button_bg_hover_color     = Style::getColor($params->get('button_bg_color_hover', ''));

$style->child('.event-readmore')->addCss('color', $button_color['light']);
$style_dark->child('.event-readmore')->addCss('color', $button_color['dark']);
$style->child('.event-readmore:hover')->addCss('color', $button_hover_color['light']);
$style_dark->child('.event-readmore:hover')->addCss('color', $button_hover_color['dark']);

$style->child('.event-readmore')->addCss('background-color', $button_bg_color['light']);
$style_dark->child('.event-readmore')->addCss('background-color', $button_bg_color['dark']);
$style->child('.event-readmore:hover')->addCss('background-color', $button_bg_hover_color['light']);
$style_dark->child('.event-readmore:hover')->addCss('background-color', $button_bg_hover_color['dark']);

$button_padding   =   $params->get('button_padding', '');
if (!empty($button_padding)) {
    Style::setSpacingStyle($this->style->child('.event-readmore'), $button_padding);
}
$button_margin   =   $params->get('button_margin', '');
if (!empty($button_margin)) {
    Style::setSpacingStyle($this->style->child('.event-readmore'), $button_margin,'margin');
}

$button_radius=  $params->get('button_radius', '');
if (!empty($button_radius)) {
    Style::setSpacingStyle($this->style->child('.event-readmore'), $button_radius, 'radius');
}