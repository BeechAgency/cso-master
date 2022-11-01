<?php 
    $fields = $args;
    $image = $fields['image'];


    $image_section = "<div class='xy-col image-wrapper' data-xy-col='xl-6 lg-6 md-12' data-xy-start='xl-7 lg-7 md-auto'>$image</div>";

    $grid_cols = "xl-4 lg-5 md-12";
    $grid_start = "xl-1 lg-1 md-1";

    if($fields['position'] === 'image-left') :
        $grid_cols = "xl-4 lg-5 md-12";
        $grid_start = "xl-8 lg-8 md-auto sm-auto";

        $image_section = "<div class='xy-col image-wrapper' data-xy-col='xl-6 lg-6 md-12'>$image</div>";

        echo $image_section;
    endif;
?>
<div class="xy-col text-wrapper" data-xy-col="<?= $grid_cols ?>" data-xy-start="<?= $grid_start ?>" data-xy-items="align-center">
    <?= conditionally_output_field($fields['subtitle'], '<h5>', '</h5>'); ?>
    <?= conditionally_output_field($fields['title'], '<h2>', '</h2>'); ?>
    <?= apply_filters('the_content', $fields['content']); ?>
    <?= do_a_cta($fields['cta']); ?>
    <?= do_a_cta($fields['cta_secondary']); ?>
</div>
<?php 
    if($fields['position'] !== 'image-left') :
        echo $image_section;
    endif;
?>