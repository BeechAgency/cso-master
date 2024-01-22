<?php
    $args;

    extract($args);

    $text = array(
        'header_title' => $slide_title,
        'header_text' => $slide_text,
        'header_text_cta' => $slide_text_cta,
        'header_text_cta_secondary' => null
    );
    $pos = get_post_meta( $slide_image, 'bg_pos_desktop', true );


?>

<div class="header-slide vh100 xy-flex" data-xy-items="end" style="background: linear-gradient(0deg, var(--base-background-color) 0%, transparent 35%, transparent 80%, rgba(0,0,0, 0.5) 100%);">
    <img src="<?= wp_get_attachment_image_src( $slide_image, 'full')[0]; ?>" class="header-slider-image" <?= !empty($pos) ? "style='object-position: $pos;'" : '' ?> />
    <?php if(!empty($slide_image_mobile)): 
        $pos_mobile = get_post_meta( $slide_image_mobile, 'bg_pos_desktop', true );
        ?>
        <img src="<?= wp_get_attachment_image_src( $slide_image_mobile, 'full')[0]; ?>" class="header-slider-image header-slide-image-mobile" <?= !empty($pos) ? "style='object-position: $pos_mobile;'" : '' ?> />
    <?php endif; ?> 
    <div class="text-wrapper xy-col has-gutter" data-xy-col="xl-8 lg-8 md-10 sm-12">
        <?php get_template_part( 'template-parts/headers/components/layout', 'text', $text ); ?>
    </div>
</div>