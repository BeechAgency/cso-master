<?php 
    $fields = $args;
    $image = $fields['image'];

    $video_group = get_sub_field('_video');
    $video_url = $video_group['video'];
    $video_type = $video_group['type'];

    $type = !empty($video_url) ? 'video' : 'image';
?>
<div class="xy-col" data-xy-col="12" data-xy-items="align-center">
    <div class="image-wrapper">
        <?= $type === 'image' ? $image : do_video_field($video_url, $video_type); ?>
    </div>
    <?= conditionally_output_field($fields['caption'], '<div class="caption has-white-color">', '</div>'); ?>
</div>
