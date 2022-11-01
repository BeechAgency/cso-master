<?php
if(have_rows('blocks')): 
    $c = 0;

    while(have_rows('blocks')): 
        the_row(); 

        get_template_part( 'template-parts/blocks/block', null, array('c' => $c) );

    $c++;
    endwhile;  
endif; ?>