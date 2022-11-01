<?php
    $nav_style = $args['navigation_style'];
    $nav_classes = $args['header_template'] === 'alternative' ? 'has-gutter' : '';
    $nav_wrapper_classes = $args['header_template'] === 'alternative' ?  ' header-alternative' : '';
    $nav_wrapper_classes .= $args['header_template'] === 'alternative' ? ' '. $args['primary_navigation_background_color'] : '';
    $nav_wrapper_classes .= ' nav-style_'.$args['navigation_style'];
    

    $logo = wp_get_attachment_image_src($args['logo_white'], 'full');

?>
<nav class="header-primary-nav xy-col <?= $nav_wrapper_classes ?>" data-xy-items="align-center" data-xy-col="12">
    <?php if($args['navigation_style'] === 'centred'): ?>
    <div class="nav-inner xy-flex  <?= $nav_classes ?>">
        <div class="xy-col" data-xy-col="xl-5 lg-5 md-5 sm-12" data-xy-items="justify-start">
            <?php csomaster_nav_location('header-primary'); ?>
        </div>
        <div class="xy-col" data-xy-col="xl-2 lg-2 md-2 sm-12"  data-xy-items="justify-center">
            <a href="/"><?= get_acf_image('logo_white', 'full', 'main', 'option', 'logo') ?></a>
        </div>
        <div class="xy-col" data-xy-col="xl-5 lg-5 md-5 sm-12">
            <?php csomaster_nav_location('header-utility'); ?>
        </div>
    </div>

    <?php else: ?>
    <div class="nav-inner xy-flex  <?= $nav_classes ?>">
        <div class="xy-col" data-xy-col="xl-3 lg-3 md-6 sm-3">
            <a href="/"><?= get_acf_image('logo_white', 'full', 'main', 'option', 'logo') ?></a>
        </div>
        <div class="xy-col" data-xy-col="xl-7 lg-7 md-auto sm-auto" data-xy-items="justify-center">
            <?php csomaster_nav_location('header-primary'); ?>
        </div>
        <div class="xy-col" data-xy-col="xl-2 lg-2 md-6 sm-9">
            <?php csomaster_nav_location('header-utility'); ?>
        </div>
    </div>
    <?php endif; ?>
</nav>
