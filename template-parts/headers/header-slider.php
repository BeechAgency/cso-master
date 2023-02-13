<?php
    $header_classes = ''. $args['header_background_color'] .' '. $args['header_text_color'] .' '. $args['header_text_alignment'] .' has-slider vh100';

    $header_classes .= $args['header_gradient'] ? ' has-top-gradient' : '';

    $background_image = wp_get_attachment_image_src($args['header_image'], 'full');

    $flex_align = $args['header_text_alignment'] == 'text-center' ? 'align-center' : 'align-start';


    $background_image_position = !empty($background_image[0]) ? 'top center' : '';

    // Handle the image position
    if(!empty($background_image[0])) {
        $background_image_id = attachment_url_to_postid($background_image[0]);
        $background_meta = get_post_meta( $background_image_id, 'bg_pos_desktop' );

        $background_image_position = count($background_meta) > 0 ? $background_meta[0] : $background_image_position;
    }

    $background_style = !empty($background_image[0]) ? 'style="background: linear-gradient(0deg, var(--base-background-color) 0%, transparent 35%), url('.$background_image[0].')  no-repeat '.$background_image_position.'; background-size: cover;"' : '';

    if(!empty($args['header_video'])) {
        $background_style = 'style="background: linear-gradient(0deg, var(--base-background-color) 0%, transparent 35%, transparent 80%, rgba(0,0,0, 0.5) 100%);"';
    }


    $slider_slides = get_field('header_slider');
?>

<header class="<?= $header_classes ?>" <?= $background_style; ?>>
    <div class="xy-flex has-gutter" data-xy-flex="column space-between" data-xy-items="<?= $flex_align ?>" data-xy-col="12">
        <?php get_template_part( 'template-parts/navs/nav', 'primary', $args ); ?>
    </div>
    <?php
    if(have_rows('header_slider')): ?>
    <div class='header-slider-wrapper xy-col' data-flickity='{ "freesScroll" : false, "wrapAround": true, "fade": true, "autoPlay": 6000, "pauseAutoPlayOnHover": true, "arrowShape": { "x0": 10, "x1": 55, "y1": 45, "x2": 60, "y2": 40, "x3": 20 } }' data-xy-col="12">
    <?php
        foreach ( $slider_slides as $slide ) :
            get_template_part( 'template-parts/headers/components/layout', 'slide', $slide ); 
        endforeach;
    echo '</div>';
    endif;
    ?>
</header>