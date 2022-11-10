<?php 
    $fields = $args;

    $style = $fields['style'];

    $grid_cols = '';
    $grid_start ='';


    $cols1 = 'xl-6 lg-6 md-10 sm-12';
    $cols2 = 'xl-6 lg-6 md-6 sm-12';
    $cols3 = 'xl-4 lg-4 md-10 sm-12';
    $cols4 = 'xl-3 lg-3 md-10 sm-12';

    $start1 = 'xl-7 lg-7 md-auto sm-auto';
    $start2 = 'auto';
    $start3 = 'auto';
    $start4 = 'auto';

    $column_count = get_sub_field('_columns') > 0 ? count(get_sub_field('_columns')) : 0;
    // switch statement to determine column count and output

    if($style === 'basic') :
        $grid_cols = 'xl-6 lg-6 md-10 sm-12';
        $grid_start = "xl-4 lg-4 md-auto sm-auto";

    elseif($style === 'full-feature') :
        $grid_cols = 'xl-8 lg-8 md-12 sm-12';
        $grid_start = "xl-3 lg-3 md-auto sm-auto";

    elseif($style === 'form') :
        $grid_cols = 'xl-3 lg-3 md-10 sm-12';
        $grid_start = "auto";

    elseif($style === 'columns') :
        switch($column_count) {
            case 1:
                $cols = $cols1;
                $start = $start1;
                $grid_cols = 'xl-6 lg-6 md-8 sm-12';
                $grid_start = "auto";
    
                break;
    
            case 2:
                $cols = $cols2;
                $start = $start2;
                $grid_cols = 'xl-6 lg-6 md-8 sm-12';
                $grid_start = "auto";
    
                break;
    
            case 3:
                $cols =  $cols3;
                $start = $start3;
    
                $grid_cols = 'xl-8 lg-8 md-8 sm-12';
                $grid_start = "xl-3 lg-3 md-auto sm-auto";
    
                break;
    
            case 4:
                $cols = $cols4;
                $start = $start4;
    
                $grid_cols = 'xl-8 lg-8 md-8 sm-12';
                $grid_start = "xl-3 lg-3 md-auto sm-auto";
    
                break;
            default:
                break;
        }
    endif;    

    //$cta_group = '<div class="button-group">'.do_a_cta($fields['cta']).do_a_cta($fields['cta_secondary']).'</div>';

?>
<div class="xy-col text-wrapper" data-xy-col="<?= $grid_cols ?>" data-xy-start="<?= $grid_start ?>" >
    <?php /* conditionally_output_field($fields['subtitle'], '<h5>', '</h5>'); ?>
    <?= conditionally_output_field($fields['title'], '<h2>', '</h2>'); */ 
        echo apply_filters('the_content', $fields['title']);
    ?>
    <?php 
    if($style === 'basic' || $style === 'full-feature' || $style === 'form')  : 
        echo apply_filters('the_content', $fields['content']);
        if ( !empty($fields['cta']) ) {
            echo '<p class="btn-align-'.$fields['cta']['align'].'">'.do_a_cta($fields['cta']).'</p>';
        }
    endif; ?>
</div>
<?php
    // loop through column acf field outputing content variable
    if($style === 'columns') : ?>

<div class="xy-col xy-grid text-column-wrapper" data-xy-col="12">

    <?php
        
        if(have_rows('_columns')):
            while(have_rows('_columns')):
                the_row(); 
                ?>
    
    <div class="xy-col text-column" data-xy-col="<?= $cols ?>" data-xy-start="<?= $start ?>">
        <?php $content = get_sub_field('content'); 
                echo apply_filters('the_content', $content);?>
    </div>
            <?php
            endwhile; // End content loop

        endif; // End content loop if. ?>
</div>
<?php
    endif; // End style if.
?>
<?php if($style === 'form') : ?>
<div class="xy-col" data-xy-col="xl-8 lg-8 md-9 sm-12" data-xy-start="xl-5 lg-5 md-auto sm-auto">
    <?= apply_filters('the_content', get_sub_field('_form_content')); ?>
</div>
<?php endif; ?>
