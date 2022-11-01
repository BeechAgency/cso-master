<?php 
    $fields = $args;
    $items = get_sub_field('_items');

    foreach($items as $item) : ?>

<div class="xy-col xy-grid para-list-row" data-xy-col="12">
    <div class="xy-col" data-xy-col="xl-5 lg-5 md-5 sm-12">
        <div class="heading-group">
            <?= conditionally_output_field($item['subtitle'], '<h5>', '</h5>'); ?>
            <?= conditionally_output_field($item['title'], '<h2>', '</h2>'); ?>
        </div>
    </div>
    <div class="xy-col" data-xy-col="xl-5 lg-5 md-5 sm-12" data-xy-start="xl-7 lg-7 md-7 sm-auto">
        <?= !empty($item['subtitle']) ? '<span class="vertical-spacer"></span>' : ''; ?>
        <?= apply_filters('the_content', $item['content']); ?>
    </div>
</div>
<?php endforeach; ?>