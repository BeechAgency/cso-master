<?php 
    $fields = $args;
    $image = $fields['image'];
?>
<div class="xy-col" data-xy-col="12" data-xy-items="align-center">
    <div class="image-wrapper">
        <?= $image ?>
    </div>
    <?= conditionally_output_field($fields['caption'], '<div class="caption has-white-color">', '</div>'); ?>
</div>
