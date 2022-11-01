<?php 
    $fields = $args;

    $grid_cols = "xl-6 lg-6 md-10 sm-12";
    $grid_start = "xl-1 lg-1 md-1 sm-auto";

    if($fields['position'] === 'quote-right') :
        $grid_start = "xl-7 lg-7 md-2 sm-auto";

    elseif($fields['position'] === 'quote-center') :
        $grid_cols = "xl-8 lg-8 md-10 sm-12";
        $grid_start = "xl-3 lg-3 md-2 sm-auto";

    endif;
?>

<div class="xy-col text-wrapper" data-xy-col="<?= $grid_cols ?>" data-xy-start="<?= $grid_start ?>">
    <?= conditionally_output_field($fields['subtitle'], '<h5>', '</h5>'); ?>
    <?= conditionally_output_field($fields['title'], '<h2>', '</h2>'); ?>
    <?= conditionally_output_field($fields['caption'], '<p class="attribution">', '</p>'); ?>
</div>