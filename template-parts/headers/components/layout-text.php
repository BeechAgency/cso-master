<?php 
    $post_type = get_post_type();
?>
<h1 class="header-title"><?= $args['header_title']; ?></h1>
<?php if(is_single()): 

    $post_id = get_the_ID();
    $user_id = get_post_field( 'post_author', $post_id );
    $author= get_the_author_meta('display_name', $user_id);

    echo '<p>';
    echo !empty($author) ? '<span class="author">'.$author.'</span> — ' : '';
    echo '<span class="date">'.get_the_date().'</span>';
    echo '</p>';
    endif; 
?>
<?= $args['header_text']; ?>
<div class="button-row flex-row">
    <?= do_a_cta($args['header_text_cta']); ?>
    <?= do_a_cta($args['header_text_cta_secondary']); ?>
</div>
