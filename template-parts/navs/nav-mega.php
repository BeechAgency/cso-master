<?php 
    $bg = $args['mega_navigation_background_color'];


    $school_details = get_school_details();

    $school_address = $school_details['school_address'];
    $school_phone = $school_details['school_phone'];
    $school_email = $school_details['school_email'];
    $school_social = $school_details['school_social'];

    //var_dump(json_encode(wp_get_nav_menu_items('mega-menu')));

    $menu_items = wp_get_nav_menu_items('mega-menu');

    $menu_lvl0 = array();
    $menu_lvl1 = array();
    $menu_lvl2 = array();

    $items = array();

    foreach($menu_items as $item) {
        $ID = $item->ID;
        $parent = $item->menu_item_parent;
        $title = $item->title;
        $url = $item->url;
        $classes = $item->classes;
        $target = $item->target;
        $attr_title = $item->attr_title;
        $description = $item->description;

        if($parent == 0) {
            $menu_lvl0[] = array(
                'ID' => $ID,
                'parent' => $parent,
                'title' => $title,
                'url' => $url,
                'classes' => $classes,
                'target' => $target,
                'attr_title' => $attr_title,
                'description' => $description,
                'children' => array()
            );
        } else {
            // check if parent is in lvl0
            $parent_lvl0 = array_filter($menu_lvl0, function($item) use ($parent) {
                return $item['ID'] == $parent;
            });

            if(!empty( $parent_lvl0 )) {

                $menu_lvl1[] = array(
                    'ID' => $ID,
                    'parent' => $parent,
                    'title' => $title,
                    'url' => $url,
                    'classes' => $classes,
                    'target' => $target,
                    'attr_title' => $attr_title,
                    'description' => $description,
                    'children' => array()
                );

            } else {

                $menu_lvl2[] = array(
                    'ID' => $ID,
                    'parent' => $parent,
                    'title' => $title,
                    'url' => $url,
                    'classes' => $classes,
                    'target' => $target,
                    'attr_title' => $attr_title,
                    'description' => $description,
                    'children' => array()
                );

            }

            
        }
    }
?>
<aside class="mega-menu-wrapper <?= $bg ?>" id="megaMenuWrapper">
    <div class="mega-menu-inner has-gutter"> 
        <div class="mega-menu-header xy-flex">
            <div class="xy-col" data-xy-col="xl-3 lg-3 md-6 sm-12">
                <a href="/"><?= get_acf_image('logo_white', 'full', 'main', 'option', 'logo') ?></a>
            </div>
            <div class="xy-col pull-right" data-xy-col="xl-9 lg-9 md-6 sm-12" data-xy-start="xl-auto lg-auto md-auto sm-auto">
                <?php csomaster_nav_location('header-utility'); ?>
            </div>
        </div>
        <div class="nav-mega" id="megaMenu">
        <?php 
            $icon =  '<svg xmlns="http://www.w3.org/2000/svg" width="15.427" height="28.379" viewBox="0 0 15.427 28.379"><g id="Arrow_2_right" data-name="Arrow 2 right" transform="translate(0.528 0.533)"><path id="Path_402" data-name="Path 402" d="M546.6,278l13.831,13.713-13.831,13.6" transform="translate(-546.602 -278)" fill="none" stroke="currentColor" stroke-width="1.5"/></g></svg>';
            echo '<ul class="mega-menu-group menu" data-menu-level="0">';
            foreach( $menu_lvl0 as $item ) {
                echo "<li class='menu-item' data-group-id='{$item['ID']}'><a href='{$item['url']}'><span>{$item['title']}</span> $icon</a>";
            }
            echo '</ul>';

            echo '<ul class="mega-menu-group menu"  data-menu-level="1" data-group-active="">';
            foreach( $menu_lvl1 as $item ) {
                echo "<li class='menu-item hidden' data-parent-id='{$item['parent']}' data-group-id='{$item['ID']}'><a href='{$item['url']}'><span>{$item['title']}</span> $icon</a>";
            }
            echo '</ul>';

            echo '<ul class="mega-menu-group menu"  data-menu-level="2" data-group-active="">';
            foreach( $menu_lvl2 as $item ) {
                echo "<li class='menu-item hidden' data-parent-id='{$item['parent']}' data-group-id='{$item['ID']}'><a href='{$item['url']}'><span>{$item['title']}</span></a>";
            }
            echo '</ul>';

        ?>
        </div>
        <?php 
           $toggle_button = '<button class="item-toggle"><svg width="13" height="7" viewBox="0 0 13 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L6.5 6L12 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>';
           echo '<div id="megaMenuMobile" class="mega-mobile-wrapper">';
           csomaster_nav_location('mega', array('after' => $toggle_button)); 
           echo '</div>';
        ?>
        <div class="mega-menu-footer xy-grid">
            <div class="xy-col" data-xy-col="xl-3 lg-3 md-6 sm-12">
                <?= conditionally_output_field($school_address, '<p>', '</p>'); ?>
            </div>
            <div class="xy-col" data-xy-col="xl-4 lg-4 md-6 sm-12">
                <?= conditionally_output_field($school_phone, '<p>'.$school_email.'<br />', '</p>'); ?>
            </div>
            <div class="xy-col" data-xy-col="xl-5 lg-5 md-6 sm-12">
                <?= get_term_dates(); ?>
            </div>
        </div>
    </div>
</aside>