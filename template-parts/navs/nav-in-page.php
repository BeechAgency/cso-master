<?php
    $display = (bool) get_field('sub_nav_display');
    $fields = array('sub_nav_pages', 'sub_nav_background', 'sub_nav_text_color');

    $sn_options = array();

    foreach($fields as $field) {
        $sn_options[$field] = get_field($field);
    }

    if(!empty($display) && $display === true) :
?>
<section class="in-page-nav <?= $sn_options['sub_nav_background']; ?> <?= $sn_options['sub_nav_text_color'] ?>">
    <?php if(!empty($sn_options['sub_nav_pages'])) : ?>
    <div class="in-page-nav-inner has-gutter">
        <ul class="in-page-nav-list">
            <?php foreach($sn_options['sub_nav_pages'] as $page) : ?>
                <li class="in-page-nav-item">
                    <a href="<?= get_permalink($page->ID); ?>" class="in-page-nav-link"><?= $page->post_title; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>