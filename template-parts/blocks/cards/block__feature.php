<?php 
    $fields = $args;
    $image = $fields['image'];

    $grid_cols = "xl-4 lg-4 md-7 sm-12";
    $grid_start = "xl-8 lg-8 md-6 sm-auto";

    $background_color_card = get_sub_field('_background_color_card');
    $background_color_lower = get_sub_field('_background_color_lower');

?>
<div class='xy-col image-wrapper' data-xy-col='"xl-6 lg-6 md-5 sm-12'>
    <?=$image ?>
</div>
<div class="xy-col text-wrapper <?= $background_color_card ?>" data-xy-col="<?= $grid_cols ?>" data-xy-start="<?= $grid_start ?>" data-xy-items="align-center">

    <?= apply_filters( 'the_content', $fields['content']); ?>

</div>