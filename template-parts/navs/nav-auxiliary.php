<?php

?>
<nav class="header-auxiliary-nav xy-grid has-gutter <?= $args['auxiliary_navigation_background_color'] ?>" data-xy-items="align-center">
    <div class="xy-col" data-xy-col="xl-6 lg-8 md-4 sm-12">
        <?= conditionally_output_field($args['auxiliary_navigation_text'], '<p class="small">', '</p>'); ?>
    </div>
    <div class="xy-col" data-xy-col="xl-6 lg-4 md-8 sm-12">
        <?php csomaster_nav_location('header-auxiliary'); ?>
    </div>
</nav>