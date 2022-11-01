<?php    
    $c = isset($c) ? $c : csomaster_rows_set_c($args);
    $fields = get_block_fields();

    $fields['c'] = $c;

    $class_list = $fields['layout'] .' '. $fields['style'] .' '. $fields['position'] .' '.  $fields['background_color'] .' '. $fields['text_color'];
    $class_list .= $fields['school_style'] ? ' has-school-style' : '';

    // split layout by first - and create two variables - $block_type and $template
    $block_type = explode('-', $fields['layout'], 2)[0];
    $template = explode('-', $fields['layout'], 2)[1];

    // set xy-grid variable as xy-grid if $fields['layout'] is not images-block__carousel-gallery
    $xy_grid_classes = $fields['layout'] === 'images-block__carousel-gallery' ? 'gallery-outer' : 'xy-grid';

    if($fields['layout'] === 'cards-block__feature') {
        $background_color_card = get_sub_field('_background_color_card');
        $background_color_lower = get_sub_field('_background_color_lower');

        $class_list .= ' '. $background_color_lower;
        $xy_grid_classes .= ' '. $background_color_card;
    }
?>

<section class="block <?= $class_list ?>" id="row<?= $c ?>" data-block-style="<?= $fields['layout']. ' ' .$fields['style']; ?>">
    <div class="<?= $xy_grid_classes ?>">
        <?php get_template_part( "template-parts/blocks/$block_type/$template", null, $fields ); ?>
    </div>
</section>