<?php 
    $fields = $args;
    $image = $fields['image'];

    $gallery = get_sub_field('_gallery');
    $gallery_count = count($gallery);

    $image_section = "<div class='xy-col image-wrapper' data-xy-col='xl-6 lg-6 md-12' data-xy-start='xl-7 lg-7 md-auto'>$image</div>";

    $grid_cols = "xl-4 lg-5 md-12";
    $grid_start = "xl-1 lg-1 md-1";
    $grid_row = 'auto';
    $grid_row_span = 'auto';

    switch($gallery_count):
        case 3:
            $grid_cols = "xl-4 lg-4 md-4 sm-12";
            $grid_start = "auto";
            break;
        case 4:
            $grid_cols = "xl-3 lg-3 md-6 sm-6";
            $grid_start = "auto";
            break;
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
            $grid_cols = "";
            $grid_start = "";
            break;
    endswitch;
?>
<div class="xy-col gallery-wrapper" data-xy-col="12">
    <div class="xy-grid">
        <?php 
            $img_n = 1;
            foreach($gallery as $image) : 
                $position = do_gallery_image_logic($img_n, $gallery_count);

                if($gallery_count > 4) {
                    $grid_cols = $position['grid_col'];
                    $grid_start = $position['grid_start'];
                    $grid_row_span = $position['grid_row_span'];
                    $grid_row = $position['grid_row'];
                }
                
        ?>
        <!-- Image: <?= $img_n ?> -->
        <figure class="image-wrapper object-fit xy-col" data-xy-col="<?= $grid_cols ?>" data-xy-start="<?= $grid_start ?>" data-xy-row="<?= $grid_row ?>" data-xy-row-span="<?= $grid_row_span ?>">
            <?= wp_get_attachment_image($image, 'full', 0, array('class'=> '')); ?>
            <?= conditionally_output_field(wp_get_attachment_caption($image), '<figcaption class="gallery-caption has-white-color">', '</figcaption>'); ?>
        </figure>
        <?php 
            $img_n++;
            endforeach; 
        ?>
    </div>
    <?= conditionally_output_field($fields['caption'], '<p class="gallery-caption">', '</p>'); ?>
</div>
<?php 
    if($fields['position'] !== 'image-left') :
        echo $image_section;
    endif;
?>