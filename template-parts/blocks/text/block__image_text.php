<?php 
    $fields = $args;
    $image = $fields['image'];


    $video_group = get_sub_field('_video');
    $video_url = !empty($video_group) ? $video_group['video'] : null ;
    $video_type = !empty($video_group) ? $video_group['type'] : null ;

    $media_type = !empty($video_url) ? 'video' : 'image';
    
    $output_media = $media_type === 'image' ? $image : do_video_field($video_url, $video_type);


    $image_section = "<div class='xy-col image-wrapper type-$media_type' data-xy-col='xl-6 lg-6 md-12' data-xy-start='xl-7 lg-7 md-auto'>$output_media</div>";

    $grid_cols = "xl-4 lg-5 md-12";
    $grid_start = "xl-1 lg-1 md-1";

    if($fields['position'] === 'image-left') :
        $grid_cols = "xl-4 lg-5 md-12";
        $grid_start = "xl-8 lg-8 md-auto sm-auto";

        $image_section = "<div class='xy-col image-wrapper type-$media_type' data-xy-col='xl-6 lg-6 md-12'>$output_media</div>";

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