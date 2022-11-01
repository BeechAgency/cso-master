<?php 
?>
<h1 class="header-title"><?= $args['header_title']; ?></h1>
<?php if(is_single()): echo '<span class="date">'.get_the_date().'</span>'; endif; ?>
<?= $args['header_text']; ?>
<div class="button-row flex-row">
    <?= do_a_cta($args['header_text_cta']); ?>
    <?= do_a_cta($args['header_text_cta_secondary']); ?>
</div>
