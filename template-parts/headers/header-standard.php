<?php
    $header_classes = ''. $args['header_background_color'] .' '. $args['header_text_color'] .' '. $args['header_text_alignment'];

    $header_classes .= $args['header_gradient'] ? ' has-top-gradient' : '';

    $background_image = wp_get_attachment_image_src($args['header_image'], 'full');

    $flex_align = $args['header_text_alignment'] == 'text-center' ? 'align-center' : 'align-start';

    $header_classes .= !empty($args['header_video']) ? ' has-video' : '';

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
?>

<header class="<?= $header_classes ?>" <?= $background_style; ?>>
    <div class="xy-flex has-gutter vh100" data-xy-flex="column space-between" data-xy-items="<?= $flex_align ?>" data-xy-col="12">
        <?php get_template_part( 'template-parts/navs/nav', 'primary', $args ); ?>

        <div class="text-wrapper xy-col" data-xy-col="xl-8 lg-8 md-10 sm-12">
            <?php get_template_part( 'template-parts/headers/components/layout', 'text', $args ); ?>
        </div>
    </div>
    <?php if(!empty($args['header_video'])): ?>
        <div class="video-wrapper">
            <video autoplay muted loop playsinline poster="<?= $background_image[0]; ?>">
                <source src="<?= $args['header_video'] ?>" type="video/mp4">
            </video>
        </div>
    <?php endif; ?>
</header>