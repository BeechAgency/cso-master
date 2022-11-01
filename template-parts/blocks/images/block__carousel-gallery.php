<?php 
    $fields = $args;
    $image = $fields['image'];

    $gallery = get_sub_field('_gallery');
    $gallery_count = count($gallery);

    $grid_cols = "xl-4 lg-5 md-12";
    $grid_start = "xl-1 lg-1 md-1";
    $grid_row = 'auto';
    $grid_row_span = 'auto';
?>

<div class="carousel-wrapper" data-xy-col="12">
    <div class="gallery-carousel"  data-flickity='{ "cellAlign": "left", "prevNextButtons": false, "autoPlay": true }'>
    <?php 
        foreach($gallery as $image) : 
    ?>
        <figure class="carousel-cell object-fit aspect-16-9" data-xy-col="<?= $grid_cols ?>" data-xy-start="<?= $grid_start ?>" data-xy-row="<?= $grid_row ?>" data-xy-row-span="<?= $grid_row_span ?>">
            <?= wp_get_attachment_image($image, 'full', 0, array('class'=> '')); ?>
            <?= conditionally_output_field(wp_get_attachment_caption($image), '<figcaption class="gallery-caption">', '</figcaption>'); ?>
        </figure>
    <?php 
        endforeach; 
    ?>
    </div>
</div>