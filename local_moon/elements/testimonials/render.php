<?php
defined('MOODLE_INTERNAL') || die;
use local_moon\library\Helper\Style;
use local_moon\library\Helper\SubForm;
use local_moon\library\Framework;
$params = $this->params;
$element = $this;
$testimonials     = new SubForm($params->get('testimonials', ''));
if (!count($testimonials->getData())) {
    return false;
}
$style = $element->style;
$style_dark = $element->style_dark;

$enable_slider      =   $params->get('enable_slider', 0);
$slider_autoplay    =   $params->get('slider_autoplay', 0);
$slider_nav         =   $params->get('slider_nav', 1);
$slider_scrollbar   =   $params->get('slider_scrollbar', 0);
$nav_position       =   $params->get('nav_position', '');
$nav_position       =   $nav_position !== '' ? ' ' . $nav_position : $nav_position;
$slider_dotnav      =   $params->get('slider_dotnav', 0);
$interval           =   $params->get('interval', 3);
$slide_settings     =   array();
$slide_responsive   =   array();
$row_column_cls     =   'row';

$responsive_key     =   [
    'xs'    => '',
    'sm'    => '576',
    'md'    => '768',
    'lg'    => '992',
    'xl'    => '1200',
    'xxl'   => '1400',
];
foreach ($responsive_key as $key => $min_width) {
    $column         =   $params->get($key . '_column', '');
    $slidesPerGroup =   $params->get($key . '_slidesPerGroup', '');
    $gutter         =   $params->get('column_gutter_' . $key, '10');
    if ($enable_slider && !empty($column)) {
        if (!count($slide_settings)) {
            $slide_settings[]       =   'slidesPerView: ' . $column;
            if ($slidesPerGroup == '') {
                $slide_settings[]       =   'slidesPerGroup: ' . $slidesPerGroup;
            }
            $slide_settings[]       =   'spaceBetween: ' . $gutter;
        } elseif (!empty($min_width)) {
            $slide_responsive[]     =   $min_width . ': {slidesPerView: '.$column.($slidesPerGroup ? ',slidesPerGroup: '.$slidesPerGroup : '').',spaceBetween: '.$gutter.'}';
        }
    } else {
        if (!empty($column)) {
            $row_column_cls .=  ' row-cols' . ($key !== 'xs' ? '-' . $key : '') . '-' . $column;
        }
    }
}

if ($slider_autoplay) {
    $slide_settings[]       =   'autoplay: {delay: '.($interval * 1000).'}';
}

if ($slider_dotnav) {
    $slide_settings[]       =   'pagination: {el: ".swiper-pagination",clickable: true,}';
}

if ($slider_nav) {
    $slide_settings[]       =   'navigation: {nextEl: ".swiper-button-next",prevEl: ".swiper-button-prev",}';
}
$speed              =   $params->get('speed', 0);
if (!empty($speed)) {
    $slide_settings[]   =   'speed:' . ($speed * 1000);
}
$loop               =   $params->get('loop', 0);
if (!empty($loop)) {
    $slide_settings[]   =   'loop:true';
}
$freemode           =   $params->get('freemode', 0);
if (!empty($freemode)) {
    $slide_settings[]   =   'freeMode: true';
}
$dir                =   $params->get('direction', '');
//$slide_settings[]   =   'autoHeight: true';
if (count($slide_responsive)) {
    $slide_settings[]       =   'breakpoints: {'.implode(',', $slide_responsive).'}';
}

$responsive_key     =   ['xxl', 'xl', 'lg', 'md', 'sm', 'xs'];
$gutter_cls         =   '';
foreach ($responsive_key as $key) {
    if ($key !== 'xs') {
        $row_gutter         =   $params->get('row_gutter_'.$key, '');
        $column_gutter      =   $params->get('column_gutter_'. $key, '');
        if ($row_gutter) {
            $gutter_cls     .=  ' gy-' . $key . '-' . $row_gutter;
        }
        if ($column_gutter) {
            $gutter_cls     .=  ' gx-' . $key . '-' . $column_gutter;
        }
    } else {
        $row_gutter         =   $params->get('row_gutter', 3);
        $column_gutter      =   $params->get('column_gutter', 3);
        $gutter_cls         .=  ' gy-' . $row_gutter;
        $gutter_cls         .=  ' gx-' . $column_gutter;
    }
}

$card_style         =   $params->get('card_style', '');
$card_style         =   $card_style ? ' text-bg-' . $card_style : '';

$card_size          =   $params->get('card_size', '');
$card_size          =   $card_size ? ' card-size-' . $card_size : '';

$card_rounded_size  =   $params->get('card_rounded_size', '3');
$border_radius      =   $params->get('card_border_radius', '');
$bd_radius          =   $border_radius != '' ? ' rounded-' . $border_radius : ' rounded-' . $card_rounded_size;

$avatar_width_cls    =   '';
$avatar_position    =   $params->get('avatar_position', 'top');
if ($avatar_position == 'right') {
    $avatar_width_cls.=  'order-2';
} else {
    $avatar_width_cls.=  'order-0';
}
$xxl_column_avatar   =   $params->get('xxl_column_avatar', '');
$avatar_width_cls    .=  $xxl_column_avatar ? ' col-xxl-' . $xxl_column_avatar : '';
$xl_column_avatar    =   $params->get('xl_column_avatar', '');
$avatar_width_cls    .=  $xl_column_avatar ? ' col-xl-' . $xl_column_avatar : '';
$lg_column_avatar    =   $params->get('lg_column_avatar', 4);
$avatar_width_cls    .=  $lg_column_avatar ? ' col-lg-' . $lg_column_avatar : '';
$md_column_avatar    =   $params->get('md_column_avatar', 12);
$avatar_width_cls    .=  $md_column_avatar ? ' col-md-' . $md_column_avatar : '';
$sm_column_avatar    =   $params->get('sm_column_avatar', 12);
$avatar_width_cls    .=  $sm_column_avatar ? ' col-sm-' . $sm_column_avatar : '';
$xs_column_avatar    =   $params->get('xs_column_avatar', 12);
$avatar_width_cls    .=  $xs_column_avatar ? ' col-' . $xs_column_avatar : '';

$enable_grid_match  =   $params->get('enable_grid_match', 0);

$box_shadow         =   $params->get('card_box_shadow', '');
$box_shadow         =   $box_shadow ? ' ' . $box_shadow : '';
$box_shadow_hover   =   $params->get('card_box_shadow_hover', '');
$box_shadow_hover   =   $box_shadow_hover ? ' ' . $box_shadow_hover : '';

$title_html_element =   $params->get('title_html_element', 'h3');
$title_font_style   =   $params->get('title_font_style');
if (!empty($title_font_style)) {
    Style::renderTypography('#'.$element->id.' .as-author-name', $title_font_style, null, $element->isRoot);
}
$title_heading_margin=  $params->get('title_heading_margin', '');

$designation_font_style    =   $params->get('designation_font_style');
if (!empty($designation_font_style)) {
    Style::renderTypography('#'.$element->id.' .as-author-designation', $designation_font_style, null, $element->isRoot);
}

$designation_heading_margin=   $params->get('designation_heading_margin', '');
$designation_position      =   $params->get('designation_position', 'after');

$content_margin     =   $params->get('content_margin', '');
$content_font_style =   $params->get('content_font_style');
if (!empty($content_font_style)) {
    Style::renderTypography('#'.$element->id.' .as-author-message', $content_font_style, null, $element->isRoot);
}

$image_max_width        =   $params->get('image_max_width', '200');
$image_rounded_size     =   $params->get('image_rounded_size', '3');
$image_border_radius    =   $params->get('image_border_radius', '0');
$image_border_radius    =   $image_border_radius != 'rounded' ? ' rounded-' . $image_border_radius : ' rounded-' . $image_rounded_size;

$image_border           =   json_decode($params->get('image_border', ''), true);

$hover_effect   = $params->get('hover_effect', '');
$hover_effect   = $hover_effect !== '' ? ' as-effect-' . $hover_effect : '';
$transition     = $params->get('hover_transition', '');
$transition     = $transition !== '' ? ' as-transition-' . $transition : '';

$card_hover_transition     = $params->get('card_hover_transition', '');
$card_hover_transition     = $card_hover_transition !== '' ? ' as-transition-' . $card_hover_transition : '';

$overlay_text_color =   $params->get('overlay_text_color', '');
$overlay_text_color =   $overlay_text_color !== '' ? ' ' . $overlay_text_color : '';

$enable_rating      =   $params->get('enable_rating', 0);

// Alignment
$text_alignment             =   $params->get('text_alignment','');
$text_alignment_breakpoint  =   $params->get('text_alignment_breakpoint','');
$text_alignment_fallback    =   $params->get('text_alignment_fallback','');
if ($text_alignment) {
    $alignment              =   ' justify-content' . ($text_alignment_breakpoint ? '-' . $text_alignment_breakpoint : '') . '-' . $text_alignment . ($text_alignment_fallback ? ' justify-content-' . $text_alignment_fallback : '');
} else {
    $alignment              =   '';
}

$use_masonry        =   $params->get('use_masonry', 0);
if ($enable_slider) {
    echo '<div class="swiper"'.(!empty($dir) ? ' dir="'.$dir.'"' : '').'>';
}
echo '<div class="moon-grid '.($enable_slider ? 'swiper-wrapper' : $row_column_cls.$gutter_cls . ($use_masonry ? ' as-masonry as-loading' : '')).$overlay_text_color.'">';
foreach ($testimonials->getData() as $key => $testimonial) {
    $avatar =   $testimonial->params->get('avatar', '');
    $rating =   $testimonial->params->get('rating', 5);
    $media  =   '';
    if ($avatar) {
        $media      =   '<div class="as-author-avatar d-inline-block position-relative overflow-hidden' . $image_border_radius . $box_shadow . $hover_effect . $transition . '">';
        $media      .=  '<img class="' . ($avatar_position == 'left' || $avatar_position == 'right' ? 'object-fit-cover w-100 h-100 ' : '') .'" src="'. $avatar .'" alt="'.$testimonial->params->get('title', '').'">';
        $media      .=  '</div>';
    }

    echo '<div id="testimonial-'. $testimonial -> id .'" class="swiper-slide"><div class="card' . $card_style . $box_shadow . $box_shadow_hover .$bd_radius . $card_hover_transition . ($enable_grid_match ? ' h-100' : '') . '">';
    if ($avatar_position == 'left' || $avatar_position == 'right') {
        echo '<div class="row g-0">';
        echo '<div class="'.$avatar_width_cls.'">';
    }
    if ($avatar_position == 'left' || $avatar_position == 'right') {
        echo $media;
    }
    if ($avatar_position == 'left' || $avatar_position == 'right') {
        echo '</div>';
        echo '<div class="col order-1">';
    }

    echo '<div class="order-1 card-body'.$card_size.'">'; // Start Card-Body

    if ($avatar_position == 'top') {
        echo '<div class="d-flex align-items-start">';
        echo $media;
        if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'before') {
            echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
        }
        echo '<div class="top-name">';
            if (!empty($testimonial->params->get('title', ''))) {
                echo '<'.$title_html_element.' class="as-author-name">'. $testimonial->params->get('title', '') . '</'.$title_html_element.'>';
            }
            if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'after') {
                echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
            }
            if (!empty($testimonial->params->get('link', '')) && !empty($testimonial->params->get('link_title', ''))) {
                echo '<a class="as-author-url" href="'.$testimonial->params->get('link', '').'" target="_blank">' . $testimonial->params->get('link_title', '') . '</a>';
            }
        echo '</div>';
        echo '</div>';
        if (!empty($enable_rating)) {
            echo '<div class="as-rating-block row row-cols-auto gx-2'.$alignment.'">';
            for ($i = 0; $i < 5 ; $i++) {
                if ($i < $rating) {
                    if ($rating - $i >= 1) {
                        echo '<div class="as-star"><i class="fa-solid fa-star"></i></div>';
                    } else {
                        echo '<div class="as-star"><i class="fa-solid fa-star-half-stroke"></i></div>';
                    }
                } else {
                    echo '<div class="as-star"><i class="fa-regular fa-star"></i></div>';
                }
            }
            echo '</div>';
        }
    }
    if (!empty($testimonial->params->get('message', ''))) {
        echo '<div class="as-author-message">' . $testimonial->params->get('message', '') . '</div>';
    }
    if ($avatar_position == 'bottom') {
        echo $media;

        if (!empty($enable_rating)) {
            echo '<div class="as-rating-block row row-cols-auto gx-2'.$alignment.'">';
            for ($i = 0; $i < 5 ; $i++) {
                if ($i < $rating) {
                    if ($rating - $i >= 1) {
                        echo '<div class="as-star"><i class="fa-solid fa-star"></i></div>';
                    } else {
                        echo '<div class="as-star"><i class="fa-solid fa-star-half-stroke"></i></div>';
                    }
                } else {
                    echo '<div class="as-star"><i class="fa-regular fa-star"></i></div>';
                }
            }
            echo '</div>';
        }
        if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'before') {
            echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
        }
        if (!empty($testimonial->params->get('title', ''))) {
            echo '<'.$title_html_element.' class="as-author-name">'. $testimonial->params->get('title', '') . '</'.$title_html_element.'>';
        }
        if (!empty($testimonial->params->get('designation', '')) && $designation_position == 'after') {
            echo '<div class="as-author-designation">' . $testimonial->params->get('designation', '') . '</div>';
        }
        if (!empty($testimonial->params->get('link', '')) && !empty($testimonial->params->get('link_title', ''))) {
            echo '<a class="as-author-url" href="'.$testimonial->params->get('link', '').'" target="_blank">' . $testimonial->params->get('link_title', '') . '</a>';
        }
    }
    echo '</div>'; // End Card-Body

    if ($avatar_position == 'left' || $avatar_position == 'right') {
        echo '</div>';
        echo '</div>';
    }

    echo '</div></div>';
}
echo '</div>';
if ($enable_slider) {
    if ($slider_dotnav) {
        echo '<div class="swiper-pagination"></div>';
    }
    if ($slider_nav) {
        echo '<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>';
    }
    if ($slider_scrollbar) {
        echo '<div class="swiper-scrollbar"></div>';
    }
    echo '</div>';
}
$document = Framework::getDocument();

if ($enable_slider) {
    $document->loadSwiper('#'.$element->id.' .swiper', implode(',', $slide_settings));
} elseif ($use_masonry) {
    $document->loadMasonry('#'. $element->id .' .as-masonry');
}

if ($params->get('card_size', '') == 'custom') {
    $card_padding   =   $params->get('card_padding', '');
    if (!empty($card_padding)) {
        Style::setSpacingStyle($element->style->child('.card-size-custom'), $card_padding);
    }
}
if (!empty($image_max_width)) {
    $element->style->child('.as-author-avatar > img')->addCss('max-width', $image_max_width . 'px');
}
if (!empty($title_heading_margin)) {
    Style::setSpacingStyle($element->style->child('.as-author-name'), $title_heading_margin, 'margin');
}
if (!empty($designation_heading_margin)) {
    Style::setSpacingStyle($element->style->child('.as-author-designation'), $designation_heading_margin, 'margin');
}
if (!empty($content_margin)) {
    Style::setSpacingStyle($element->style->child('.as-author-message'), $content_margin, 'margin');
}
if (!empty($image_border)) {
    Style::addBorderStyle('#'. $element->id . ' .as-author-avatar', $image_border);
}
if (!empty($enable_rating)) {
    $rating_color   =   Style::getColor($params->get('rating_color', ''));
    $rating_margin=   $params->get('rating_margin', '');
    $element->style->child('.as-rating-block')->addCss('color', $rating_color['light']);
    $element->style_dark->child('.as-rating-block')->addCss('color', $rating_color['dark']);
    if (!empty($rating_margin)) {
        Style::setSpacingStyle($element->style->child('.as-rating-block'), $rating_margin, 'margin');
    }
}
if ($params->get('card_style', '') == 'custom') {
    $text_color     =   Style::getColor($params->get('text_color', ''));
    $style->child('.card')->addCss('color', $text_color['light']);
    $style_dark->child('.card')->addCss('color', $text_color['dark']);

    $bg_color       =   Style::getColor($params->get('bg_color', ''));
    $style->child('.card')->addCss('background-color', $bg_color['light']);
    $style_dark->child('.card')->addCss('background-color', $bg_color['dark']);

    $card_border    =   json_decode($params->get('card_border', ''), true);
    if (!empty($card_border)) {
        Style::addBorderStyle('#'. $element->id . ' .card', $card_border, 'global', $element->isRoot);
    }
}