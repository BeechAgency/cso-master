<?php 
    $id = get_the_ID();
    $type = get_post_type();
    $wrapper = $args['wrapper'] ?? 'article';
    $class = $args['class'] ?? 'card';
    $grid = $args['grid'] ?? null;

    $image_id = get_post_thumbnail_id();

    if($type === 'page') {
        $image_id = get_field('header_image', $id);
        
    }
?>
<<?=$wrapper ?> class="<?= $class ?>" <?= !empty($grid) ? 'data-xy-col="'.$grid.'"' : ''; ?> >
    <a href="<?= get_the_permalink(); ?>"><?php if( has_post_thumbnail() || $type === 'page' ) { echo wp_get_attachment_image($image_id,'large'); } ?></a>
    <div class="card-content">
        <?php if( $type === 'post') :?> <h6 class="card-date"><?= get_the_date(); ?></h6> <?php endif; ?>
        <h4 class="card-title"><a href="<?= get_the_permalink(); ?>"><?= get_the_title(); ?></a></h4>
        <p class="card-text"><?= get_the_excerpt(); ?></p>

        <a href="<?= get_the_permalink(); ?>" class="btn btn-primary">Read More</a>
    </div>
    
</<?= $wrapper ?>>