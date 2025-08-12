<?php 
    $fields = $args;
    $image = $fields['image'];
    
    $grid_cols = "xl-4 lg-5 md-12";
    $grid_start = "auto";

    /*
    style-image-icon : Centre Text and Icon and image
    style-image-text : Text over image
    style-card : Summary card
    style-icon-only : Icon and text
    style-lines : Simple lines
    style-lines-grid : Simple lines grid
    style-underlined-icon : Underlined Icon
    */
    $card_background = get_sub_field('_background_color_cards');
    $cards = get_sub_field('_cards');
    $cards_count = is_countable($cards) ? count($cards) : 0;

    $title_alignment = get_sub_field('_title_alignment');

    $cta_row = '<div class="button-row">'.do_a_cta($fields['cta']).do_a_cta($fields['cta_secondary']).'</div>';

    
    if($cards_count === 3 || $cards_count === 1) :
        $grid_cols = "xl-4 lg-4 md-6 sm-12";

    elseif($cards_count === 2) :
        $grid_cols = "xl-6 lg-6 md-6 sm-12";

    elseif($cards_count === 4) :
        $grid_cols = "xl-3 lg-3 md-6 sm-12";

    endif;
    
    if($fields['style'] === 'style-lines' || $fields['style'] === 'style-lines-grid' || $fields['style'] === 'style-underlined-icon') :
        $card_background = '';
    endif; 
?>
<div class="xy-col text-wrapper" data-xy-col="12">
    <div class="heading-group <?= $title_alignment ?>">
        <?php /* conditionally_output_field($fields['subtitle'], '<h5>', '</h5>'); */ ?>
        <?php /* conditionally_output_field($fields['title'], '<h2>', '</h2>'); */ ?>
        <?= apply_filters('the_content', $fields['title']); ?>
    </div>
    <?php if($fields['style'] === 'style-image-icon') echo $cta_row; ?>
</div>
<div class="xy-col xy-grid card-wrapper" data-xy-col="12">
    <?php 
        $card_n = 1;
        if( !empty($cards) ) :
            foreach($cards as $card) : 

                if($fields['style'] === 'style-lines' || $fields['style'] === 'style-lines-grid' || $fields['style'] === 'style-underlined-icon') $card['image'] = null;
                if($fields['style'] === 'style-lines-grid'):
                    if($card_n === 1 || $card_n === 3) { $grid_cols = 'xl-4 lg-4 md-6 sm-12'; $grid_start = 'xl-3 lg-3 md-auto'; }
                    if($card_n === 2 || $card_n === 4) { $grid_cols = 'xl-4 lg-4 md-6 sm-12'; $grid_start = ''; }

                endif;

                if( !empty($card['colors']) && !empty($card['colors']['background']) ) {  $card_background = $card['colors']['background']. ' '. $card['colors']['text']; }
        ?>
        <div class="xy-col card <?= $fields['style'].' '.$card_background ?>" data-xy-col="<?= $grid_cols ?>" data-xy-start="<?= $grid_start ?>">
            <div class="card-inner">
                <div class="card-image">
                    <?= wp_get_attachment_image($card['image'], 'full', 0, array('class'=> '')); ?>
                </div>
                <div class="card-content">
                    <?= wp_get_attachment_image($card['icon'], 'full', 0, array('class'=> 'icon')); ?>
                    <?= conditionally_output_field($card['title'], '<h4>', '</h4>'); ?>
                    <?= conditionally_output_field($card['text'], '<p>', '</p>'); ?>
                    <?= do_a_cta(  $card['cta'] ); ?>
                </div>
            </div>
        </div>
        <?php 
            $card_n++;
            endforeach; 
        endif;
        ?>
</div>
<?php if($fields['style'] !== 'style-image-icon') echo '<div class="xy-col '.$title_alignment.'" data-xy-col="12">'.$cta_row.'</div>'; ?>