<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Framework;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
$params = $this->params;
$element = $this;

$slides     = new SubForm($params->get('slides', ''));
if (!count($slides->getData())) {
    return false;
}

$document = Framework::getDocument();
$style = $element->style;
$style_dark = $element->style_dark;

$media_position     =   $params->get('media_position', 'top');

$overlay_text_color =   $params->get('overlay_text_color', '');
$overlay_text_color =   $overlay_text_color !== '' ? ' ' . $overlay_text_color : '';
$min_height         =   $params->get('min_height', '');
$slider_height      =   $params->get('slider_height', '');
$autoplay           =   $params->get('autoplay', 0);
$interval           =   $params->get('interval', 5);
$interval           =   $interval * 1000;
$overlay_type       =   $params->get('overlay_type', '');
$overlay_color      =   $params->get('overlay_color', '');
$effect_type        =   $params->get('effect_type', 'theater');

$overlay_position   =   $params->get('overlay_position', 'justify-content-center align-items-center');
$overlay_position   =   $overlay_position !== '' ? ' ' . $overlay_position : '';
$overlay_max_width  =   $params->get('overlay_max_width', '');
$overlay_max_width  =   $overlay_max_width !== '' ? ' as-width-'. $overlay_max_width : '';

$title_html_element =   $params->get('title_html_element', 'h3');
$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    Style::renderTypography('#'.$element->id.' .moon-heading', $title_font_style, null, $element->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$meta_font_style    =   $params->get('meta_font_style');
if (!empty($meta_font_style)) {
    Style::renderTypography('#'.$element->id.' .moon-meta', $meta_font_style, null, $element->isRoot);
}
$meta_position      =   $params->get('meta_position', 'before');
$meta_heading_margin=   $params->get('meta_heading_margin', '');

$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    Style::renderTypography('#'.$element->id.' .moon-text', $content_font_style, null, $element->isRoot);
}
$button_size        =   $params->get('button_size', '');
$button_size        =   $button_size ? ' '. $button_size : '';

$btn_radius         =   $params->get('btn_border_radius', '');
$btn_radius         =   $btn_radius ? ' '. $btn_radius : '';

$image_height      =   $params->get('image_height', '');
$image_width      =   $params->get('image_width', '');

$image_height_data = json_decode($image_height, true);
$image_width_data = json_decode($image_width, true);

if (json_last_error() === JSON_ERROR_NONE && is_array($image_width_data)) {
    $style->child('.uk-slider-image')->addResponsiveCSS('width', $image_width_data, $image_width_data['postfix']);

}
if (json_last_error() === JSON_ERROR_NONE && is_array($image_height_data)) {
    $style->child('.uk-slider-image')->addResponsiveCSS('height', $image_height_data, $image_height_data['postfix']);

}
$image_radius=  $params->get('image_radius', '');
if (!empty($image_radius)) {
    Style::setSpacingStyle($element->style->child('.uk-slider-image'), $image_radius, 'radius');
}
$image_border    =   json_decode($params->get('image_border', ''), true);
if (!empty($image_border)) {
    Style::addBorderStyle('#'. $element->id . ' .uk-slider-image', $image_border, 'global', $element->isRoot);
}


$overlay_positions = $params->get('overlay_positions','');
$overlay_pos_int   = ( $overlay_positions == 'top' || $overlay_positions == 'bottom' ) ? ' uk-flex-1' : '';
if ( ( $overlay_positions == 'top' ) || ( $overlay_positions == 'left' ) || ( $overlay_positions == 'bottom' ) || ( $overlay_positions == 'right' ) ) {
    $overlay_positions = ' uk-flex-' . $overlay_positions;
} elseif ( $overlay_positions == 'top-left' ) {
    $overlay_positions = ' uk-flex-top uk-flex-left';
} elseif ( $overlay_positions == 'top-right' ) {
    $overlay_positions = ' uk-flex-top uk-flex-right';
} elseif ( $overlay_positions == 'top-center' ) {
    $overlay_positions = ' uk-flex-top uk-flex-center';
} elseif ( $overlay_positions == 'center-left' ) {
    $overlay_positions = ' uk-flex-left uk-flex-middle';
} elseif ( $overlay_positions == 'center-right' ) {
    $overlay_positions = ' uk-flex-right uk-flex-middle';
} elseif ( $overlay_positions == 'center-center' ) {
    $overlay_positions = ' uk-flex-center uk-flex-middle';
} elseif ( $overlay_positions == 'bottom-left' ) {
    $overlay_positions = ' uk-flex-bottom uk-flex-left';
} elseif ( $overlay_positions == 'bottom-center' ) {
    $overlay_positions = ' uk-flex-bottom uk-flex-center';
} elseif ( $overlay_positions == 'bottom-right' ) {
    $overlay_positions = ' uk-flex-bottom uk-flex-right';
}

$overlay_align = $params->get('overlay_align','');

$slider_style = $params->get('slider_style', 'style1');
$min_height = $params->get('min_height', '');
$max_height = $params->get('max_height', '');
$height = $params->get('slider_height', '');
$slideshow_transition = $params->get('slideshow_transition', '');

$attrs_slideshow[] = '';
$attrs_slideshow[] = ( isset( $slideshow_transition ) ) ? 'animation: ' . $slideshow_transition : '';
$attrs_slideshow[] = ( isset( $min_height ) ) ? 'min-height: ' . $min_height : 'min-height: 300';
$attrs_slideshow[] = ( isset( $max_height ) ) ? 'max-height: ' . $max_height : '';
$attrs_slideshow[] = (  $autoplay  ) ? 'autoplay: 1' : '';
$attrs_slideshow   = ' data-uk-slideshow="' . implode( '; ', array_filter( $attrs_slideshow ) ) . '"';

$kenburns = $params->get('kenburns_transition','');

$kenburns_transition = ( isset( $kenburns ) && $kenburns ) ? ' uk-transform-origin-' . $kenburns : '';

$kenburns_duration = $params->get('kenburns_duration','');

if ( $kenburns_duration ) {
    $kenburns_duration = ' style="-webkit-animation-duration: ' . $kenburns_duration . 's; animation-duration: ' . $kenburns_duration . 's;"';
}

$navigation = $params->get('navigation', '');
$nav_color     = Style::getColor($params->get('navigation_color', ''));
$nav_hover_color     = Style::getColor($params->get('navigation_color_hover', ''));
$nav_bg_color     = Style::getColor($params->get('navigation_bg_color', ''));
$nav_bg_hover_color     = Style::getColor($params->get('navigation_bg_color_hover', ''));
$next_text = $params->get('slidenav_next_text', '');
$preview_text = $params->get('slidenav_preview_text', '');

$dot_options = $params->get('dot_style', '');
$dot_below = $params->get('dot_below', '');
$dot_position = $params->get('dot_position', 'uk-position-bottom-center');
$dot_margin = $params->get('dot_margin', '');
$dot_border_color     = Style::getColor($params->get('dot_border_color', ''));
$dot_color     = Style::getColor($params->get('dot_color', ''));
$dot_hover_color     = Style::getColor($params->get('dot_hover_color', ''));

$height_cls = '';
if ( $height == 'full' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . '"';
} elseif ( $height == 'percent' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . 'offset-bottom: 20"';
} elseif ( $height == 'section' ) {
    $height_cls .= ' data-uk-height-viewport="offset-top: true; ' . $min_height . 'offset-bottom: !.elementor-section +"';
}

$document->loadUIKit();
if($slider_style=='style1'){
    echo '<div class="uk-position-relative uk-visible-toggle" '.$attrs_slideshow.'>';
    echo '<div class="uk-slideshow-items">';
    foreach ($slides->getData() as $key => $slide) {
        echo '<div class="container">';
        echo '<div class="row align-items-center">';

        echo '<div class="col-md-6 uk-slider-content order-2 order-md-1">';
        if (!empty($slide->params->get('meta')) && $meta_position == 'before') {
            echo '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
        }
        if (!empty($slide->params->get('title'))) {
            echo '<'.$title_html_element.' class="moon-heading">'. $slide->params->get('title') . '</'.$title_html_element.'>';
        }
        if (!empty($slide->params->get('meta')) && $meta_position == 'after') {
            echo '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
        }
        if (!empty($slide->params->get('description'))) {
            $content        = format_text($slide->params->get('description', ''), FORMAT_HTML, ['context' => $this->context]);
            echo '<div class="moon-text">' . $content . '</div>';
        }
        $target = !empty($slide->params->get('link_target')) ? ' target="'.$slide->params->get('link_target').'"' : '';
        if (!empty($slide->params->get('link'))) {
            echo '<div class="moon-button mt-5"><a class="btn btn-' .(intval($params->get('button_outline', 0)) ? 'outline-' : ''). $params->get('button_style', '') . $button_size . $btn_radius . '" href="' . $slide->params->get('link') . '"'.$target.'>' . $slide->params->get('link_title') . '</a></div>';
        }
        echo '</div>';

        echo '<div class="col-md-6 order-1 order-md-2">';
        echo '<div class="uk-flex uk-flex-right ">';
        echo '<div class="uk-slider-image uk-cover-container">';
        echo '<img data-uk-cover src="'. $slide->params->get('image') .'" class="object-fit-cover w-100 h-100" alt="'.$slide->params->get('title').'">';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    if($navigation){
        echo '
    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href data-uk-slidenav-previous data-uk-slideshow-item="previous"></a>
    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href data-uk-slidenav-next data-uk-slideshow-item="next"></a>
    ';
    }
    echo '</div>';
}elseif($slider_style=='style2'){
    $output      = '';

    $output .= '<div class="ui-slideshow-wrapper">';
    $output .= '<div class="ui-slideshow-wrapper-inner">';

    $output .= '<div class="ui-slideshow"' . $attrs_slideshow . '>';

    $output .= '<div class="uk-position-relative">';

    $output .= '<ul class="uk-slideshow-items uk-cover-container uk-overflow-hidden "' . $height_cls . '>';

        foreach ( $slides->getData() as $key => $slide ) {
            $media_type = ( isset( $value['media_type'] ) && $value['media_type'] ) ? $value['media_type'] : '';
            $video      = ( isset( $value['video'] ) && $value['video'] ) ? $value['video'] : '';
            $video_fallback = ( isset( $value['video_fallback'] ) && isset( $value['video_fallback']['url'] ) && $value['video_fallback']['url'] ) ? $value['video_fallback']['url'] : '';

            $media_item = ( isset( $value['image'] ) && $value['image'] ) ? $value['image'] : '';
            $image_src  = isset( $media_item['url'] ) ? $media_item['url'] : '';

            $item_title      = ( isset( $value['title'] ) && $value['title'] ) ? $value['title'] : '';
            $item_meta       = ( isset( $value['meta'] ) && $value['meta'] ) ? $value['meta'] : '';
            $item_content    = ( isset( $value['content'] ) && $value['content'] ) ? $value['content'] : '';
            $image_panel     = ( isset( $value['image_panel'] ) && $value['image_panel'] ) ? 1 : 0;
            $button_shape    = ( isset( $value['button_shape'] ) && $value['button_shape'] ) ? ' uk-border-'. $value['button_shape'] : '';

            $media_background = ($media_type == 'video') ? ($video_fallback ? ' style="background: url(\''.$video_fallback.'\')" 50% 50%; background-size: cover;' : '') : '';

            $media_overlay    = ( $image_panel ) ? '<div class="ui-background-cover uk-position-cover elementor-repeater-item-'. $value['_id'] .'"></div>' : '';

            $image_alt      = ( isset( $value['image_alt'] ) && $value['image_alt'] ) ? $value['image_alt'] : '';
            $title_alt_text = ( isset( $value['title'] ) && $value['title'] ) ? $value['title'] : '';

            $button_title   =   ( isset( $value['button_title'] ) && $value['button_title'] ) ? $value['button_title'] : '';

//            if ( empty( $button_title ) ) {
//                $button_title .= $all_button_title;
//            }

            $check_target = ( isset( $instance['link_new_tab'] ) && $instance['link_new_tab'] ) ? $instance['link_new_tab'] : '';

            $render_linkscroll = ( empty( $check_target ) && isset($button_link['url']) && strpos( $button_link['url'], '#' ) === 0 ) ? ' uk-scroll' : '';
            $number = intval($key + 1);
            $output .= '<li class="el-item ui-media uk-margin-remove item-' . $key . '"' . $media_background . ' data-number="'.$number.'">';
            $output .= ( $kenburns_transition ) ? '<div class=" uk-position-cover uk-animation-kenburns uk-animation-reverse' . $kenburns_transition . '"' . $kenburns_duration . '>' : '';

            if ($media_type == 'video') {
                $video_parse = parse_url( $video );
                switch ( $video_parse['host'] ) {
                    case 'youtu.be':
                        $id  = trim( $video_parse['path'], '/' );
                        $src = '//www.youtube.com/embed/' . $id;
                        $output .= '<iframe src="'.$src.'?iv_load_policy=3&amp;autoplay=1&amp;controls=0&amp;showinfo=0&amp;rel=0&amp;loop=1&amp;modestbranding=1&amp;wmode=transparent&amp;playsinline=1" width="1920" height="1080"  allowfullscreen data-uk-cover></iframe>';
                        break;

                    case 'www.youtube.com':
                    case 'youtube.com':
                        parse_str( $video_parse['query'], $query );
                        $id  = $query['v'];
                        $src = '//www.youtube.com/embed/' . $id;
                        $output .= '<iframe src="'.$src.'?iv_load_policy=3&amp;autoplay=1&amp;controls=0&amp;showinfo=0&amp;rel=0&amp;loop=1&amp;modestbranding=1&amp;wmode=transparent&amp;playsinline=1" width="1920" height="1080" allowfullscreen data-uk-cover></iframe>';
                        break;

                    case 'vimeo.com':
                    case 'www.vimeo.com':
                        $id  = trim( $video_parse['path'], '/' );
                        $src = '//player.vimeo.com/video/' . $id;
                        $output .= '<iframe src="'.$src.'?autoplay=1&amp;loop=1&amp;muted=1&amp;autopause=0&amp;title=0&amp;byline=0&amp;portrait=0&amp;controls=0" width="1920" height="1080"  allowfullscreen data-uk-cover></iframe>';
                        break;
                    default :
                        $output .= '<video src="'.$video.'" autoplay loop muted playsinline data-uk-cover></video>';
                        break;
                }
            } else {
                $output .= '<img class="ui-image" src="' . $slide->params->get('image') . '" data-uk-cover>';
            }

            $output .= ( $kenburns_transition ) ? '</div>' : '';

            $output .= $media_overlay;

            $output .= '<div class="ui-content-wrap container uk-position-cover uk-flex ' . $overlay_positions . ' '.$overlay_align.' ">';

            $output .= '<div class="' . $overlay_pos_int . ' ' .$overlay_max_width.'">';

            if (!empty($slide->params->get('meta')) && $meta_position == 'before') {
                $output .= '<div class="moon-meta" data-uk-slideshow-parallax="y: -50,0,0; opacity: 1,1,0">' . $slide->params->get('meta') . '</div>';
            }

            if (!empty($slide->params->get('title'))) {
                $output .= '<'.$title_html_element.' class="moon-heading" data-uk-slideshow-parallax="y: -50,0,0; opacity: 1,1,0">'. $slide->params->get('title') . '</'.$title_html_element.'>';
            }

            if (!empty($slide->params->get('meta')) && $meta_position == 'after') {
                $output .= '<div class="moon-meta">' . $slide->params->get('meta') . '</div>';
            }
            if (!empty($slide->params->get('description'))) {
                $content = format_text($slide->params->get('description', ''), FORMAT_HTML, ['context' => $this->context]);
                $output .= '<div class="moon-text" data-uk-slideshow-parallax="y: 50,0,0; opacity: 1,1,0">' . $content . '</div>';
            }
            $target = !empty($slide->params->get('link_target')) ? ' target="'.$slide->params->get('link_target').'"' : '';
            if (!empty($slide->params->get('link'))) {
                $output .= '<div class="moon-button mt-5"><a class="btn btn-' .(intval($params->get('button_outline', 0)) ? 'outline-' : ''). $params->get('button_style', '') . $button_size . $btn_radius . '" href="' . $slide->params->get('link') . '"'.$target.'>' . $slide->params->get('link_title') . '</a></div>';
            }

            $output .= '</div>';

            $output .= '</div>';

            $output .= '</li>';
        }

    $output .= '</ul>';

    if($navigation){
        $output .= '
    <a class="uk-position-center-left tz-sidenav uk-position-small uk-hidden-hover" href data-uk-slidenav-previous data-uk-slideshow-item="previous">'.$preview_text.'</a>
    <a class="uk-position-center-right tz-sidenav uk-position-small uk-hidden-hover" href data-uk-slidenav-next data-uk-slideshow-item="next">'.$next_text.'</a>
    ';
    }

    if ( $dot_options == 'dotnav' ) {
            $output .= '<div class="ui-nav-control ' .  $dot_position .  '"> ';
            $output .= '<ul class="uk-slideshow-nav uk-dotnav "></ul>';
            $output .= '</div> ';
    } elseif ( $dot_options == 'thumbnav' ) {



    } elseif ( $dot_options == 'title' ) {

    }

    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';
    echo $output;

}

if (!empty($title_heading_margin)) {
    Style::setSpacingStyle($element->style->child('.moon-heading'), $title_heading_margin, 'margin');
}
if (!empty($meta_heading_margin)) {
    Style::setSpacingStyle($element->style->child('.moon-meta'), $meta_heading_margin, 'margin');
}
$style->child('.uk-slidenav')->addCss('color', $nav_color['light']);
$style_dark->child('.uk-slidenav')->addCss('color', $nav_color['dark']);
$style->child('.uk-slidenav:hover')->addCss('color', $nav_hover_color['light']);
$style_dark->child('.uk-slidenav:hover')->addCss('color', $nav_hover_color['dark']);

$style->child('.uk-slidenav::before')->addCss('background-color', $nav_color['light']);
$style_dark->child('.uk-slidenav::before')->addCss('background-color', $nav_color['dark']);
$style->child('.uk-slidenav:hover::before')->addCss('background-color', $nav_hover_color['light']);
$style_dark->child('.uk-slidenav:hover::before')->addCss('background-color', $nav_hover_color['dark']);

$style->child('.uk-slidenav')->addCss('background-color', $nav_bg_color['light']);
$style_dark->child('.uk-slidenav')->addCss('background-color', $nav_bg_color['dark']);
$style->child('.uk-slidenav:hover')->addCss('background-color', $nav_bg_hover_color['light']);
$style_dark->child('.uk-slidenav:hover')->addCss('background-color', $nav_bg_hover_color['dark']);

$nav_padding   =   $params->get('navigation_padding', '');
if (!empty($nav_padding)) {
    Style::setSpacingStyle($this->style->child('.uk-slidenav'), $nav_padding);
}
$slideshow_padding   =   $params->get('slideshow_padding', '');
if (!empty($slideshow_padding)) {
    Style::setSpacingStyle($this->style->child('.uk-slideshow'), $slideshow_padding);
}
$overlay_padding   =   $params->get('overlay_padding', '');
if (!empty($overlay_padding)) {
    Style::setSpacingStyle($this->style->child('.ui-content-wrap'), $overlay_padding);
}
if (!empty($dot_margin)) {
    Style::setSpacingStyle($element->style->child('.ui-nav-control'), $dot_margin, 'margin');
}

$style->child('.uk-dotnav > * > *')->addCss('border-color', $dot_border_color['light']);
$style_dark->child('.uk-dotnav > * > *')->addCss('border-color', $dot_border_color['dark']);
$style->child('.uk-dotnav > .uk-active > *')->addCss('background-color', $dot_color['light']);
$style_dark->child('.uk-dotnav > .uk-active > *')->addCss('background-color', $dot_color['dark']);

$style->child('.uk-dotnav > * > :hover')->addCss('background-color', $dot_hover_color['light']);
$style_dark->child('.uk-dotnav > * > :hover')->addCss('background-color', $dot_hover_color['dark']);