<?php 
    $fields = $args;
    $image = $fields['image'];
    

    $grid_cols = "xl-4 lg-5 md-12";
    $grid_start = "auto";

    $header_grid_cols = "xl-5 lg-5 md-8 sm-12 sm-auto";
    $header_grid_start = "auto";
    $body_grid_cols = "12";
    $body_grid_start = "auto";

    $items = get_sub_field('_items');

    $items_count = count($items);

    /*
        faq-list 
        faq-grid
        faq-expanded
    */
    if($fields['style'] === 'faq-list' ) :
        $header_grid_cols = "xl-4 lg-4 md-8 sm-12";
        $header_grid_start = "xl-1 lg-1 md-auto sm-auto";

        $body_grid_cols = "xl-5 lg-5 md-12 sm-12";
        $body_grid_start = "xl-7 lg-7 md-auto sm-auto";

        $grid_cols = "xl-4 lg-5 md-12";
        $grid_start = "auto";

    elseif($fields['style'] === 'faq-grid' ) :

        $grid_cols = "xl-4 lg-5 md-12 sm-12";
        $grid_start = "auto";

    elseif($fields['style'] === 'faq-expanded' ) :

    endif; 
?>
<div class="xy-col text-wrapper" data-xy-col="<?= $header_grid_cols ?>" data-xy-start="<?= $header_grid_start ?>">
    <div class="heading-group">
        <?php /* conditionally_output_field($fields['subtitle'], '<h5>', '</h5>'); */ ?>
        <?php /* conditionally_output_field($fields['title'], '<h2>', '</h2>'); */ ?>
        <?= apply_filters('the_content', $fields['content']); ?>
    </div>
</div>
<div class="xy-col xy-grid faq-wrapper <?= $fields['style'] ?>" data-xy-col="<?= $body_grid_cols ?>"  data-xy-start="<?= $body_grid_start ?>">
    <?php 
        $i = 0;
        foreach($items as $item) : 
            $grid_cols = 12;
            $grid_start = 'auto';

            if($fields['style'] === 'faq-grid' || $fields['style'] === 'faq-expanded' ) :

                $grid_cols = "xl-5 lg-5 md-12 sm-12";
                $grid_start = "xl-1 lg-1 md-auto sm-auto";

                if ($i % 2 !== 0) $grid_start = "xl-7 lg-7 md-auto sm-auto";
                
            endif;

    ?>
    <div class="xy-col" data-xy-col="<?= $grid_cols ?>" data-xy-start="<?= $grid_start ?>">
        <div class="faq-item">
            <div class="faq-question">
                <h5>
                    <?= $item['title'] ?>
                </h5>
            </div>
            <div class="faq-answer">
                <?= apply_filters('the_content', $item['content']) ?>
            </div>
        </div>
    </div>
    <?php 
        $i++;
        endforeach; ?>
</div>