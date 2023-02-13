<?php 
    $post_type = get_post_type();
?>

<?php csomaster_before_custom_header_text_layout(); ?>

<h1 class="header-title"><?= $args['header_title']; ?></h1>
<?php 
    if(is_single() && $post_type === 'post'): 
        $post_id = get_the_ID();
        $user_id = get_post_field( 'post_author', $post_id );
        $author = get_the_author_meta('display_name', $user_id);

        echo '<p>';
        echo !empty($author) ? '<span class="author">'.$author.'</span> — ' : '';
        echo '<span class="date">'.get_the_date().'</span>';
        echo '</p>';
    endif; 
?>
<?= $args['header_text']; ?>
<?php if(
    !empty($args['header_text_cta']['link']) || 
    !empty($args['header_text_cta_secondary']['link'])) : ?>
<div class="button-row flex-row">
    <?= do_a_cta($args['header_text_cta']); ?>
    <?= do_a_cta($args['header_text_cta_secondary']); ?>
</div>
<?php endif; ?>

<?php csomaster_after_custom_header_text_layout(); ?>
