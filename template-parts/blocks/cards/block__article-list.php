<?php 
    $fields = $args;

    $title_alignment = !empty($fields['title_alignment']) ? $fields['title_alignment'] : get_sub_field('_title_alignment');
    $posts_type = !empty($fields['posts_type']) ? $fields['posts_type'] : get_sub_field('_posts_type');

    $card_background_color = !empty($fields['background_color_cards']) ? $fields['background_color_cards'] : get_sub_field('_background_color_cards');

    $list = array();

    $posts = !empty($fields['posts']) ? $fields['posts'] : get_sub_field('_posts');
    $category = !empty($fields['filter_categories']) ? $fields['filter_categories'] : get_sub_field('_categories');


    $postListOptions = array(
        'post' => 'post',
        'number' => 12,
        'wrapper' => false,
        'options' => array(
            'wrapper' => 'article',
            'class' => 'card carousel-cell '.$card_background_color,
        ), 
        'orderby' => 'date',
        'order'   => 'DESC'
    );


    if($posts_type === 'category') :
        if(!empty($category)) :
            $postListOptions['category'] = $category->name;
        endif;
    endif;

    if($posts_type === 'posts') :
        if(!empty($posts)) :
            $postListOptions['posts'] = $posts;
            $postListOptions['number'] = count($posts);
        endif;
    endif;

?>

<div class="xy-col text-wrapper" data-xy-col="12">
    <div class="heading-group <?= $title_alignment ?>">
        <?= apply_filters('the_content', $fields['title']); ?>
    </div>
    <?php if($fields['style'] === 'style-image-icon') echo $cta_row; ?>
</div>

<div class="carousel-wrapper type-<?= $posts_type ?> xy-col" data-xy-col="12">
    <div class="post-carousel" data-flickity='{ "contain": true, "groupCells": false, "percentPosition": false, "cellAlign": "left", "prevNextButtons": true, "autoPlay": false, "wrapAround" : false, "pageDots" : false, "arrowShape": { "x0": 10, "x1": 55, "y1": 45, "x2": 60, "y2": 40, "x3": 20 } }'>
        <?php csomaster_post_list($postListOptions); ?>
    </div>
</div>
