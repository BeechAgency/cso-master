<?php
    $header_classes = ''. $args['header_background_color'] .' '. $args['header_text_color'] .' '. $args['header_text_alignment'] .' header-alternative';

    $post_type = get_post_type();

    $background_image = wp_get_attachment_image_src($args['header_image'], 'full');

    if($post_type === 'post' && empty($background_image)) {
        $background_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
    }

    $flex_align = $args['header_text_alignment'] == 'text-center' ? 'align-center' : 'align-start';

    $header_classes .= empty($background_image) ? ' no-image' : ' has-image';

    $text_cols = !empty($background_image) ? 'xl-6 lg-6 md-7 sm-12' : '12';
    
?>

<?php get_template_part( 'template-parts/navs/nav', 'primary', $args ); ?>

<header class="<?= $header_classes ?>">

    <div class="xy-flex has-gutter" data-xy-flex="row space-between" data-xy-items="<?= $flex_align ?>" data-xy-col="12">

        <div class="text-wrapper xy-col" data-xy-col="<?= $text_cols ?>" data-xy-items="align-center">
            <?php get_template_part( 'template-parts/headers/components/layout', 'text', $args ); ?>
        </div>
        <?php if(!empty($background_image)): ?>
        <div class="image-wrapper xy-col" data-xy-col="xl-6 lg-6 md-5 sm-12"  data-xy-items="align-center">
            <img src="<?= $background_image[0] ?>" alt="" class="full-bleed aspect-1-1" />
        </div>
        <?php endif; ?>
    </div>
</header>