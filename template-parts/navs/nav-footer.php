<?php 
    $footer_background_color = get_field('footer_background_color', 'option');
    $footer_auxiliary_background_color = get_field('footer_auxiliary_background_color', 'option');
    
    $school_details = get_school_details();

    $address = $school_details['school_address'];
    $school_phone = $school_details['school_phone'];
    $school_email = $school_details['school_email'];
    $school_social = $school_details['school_social'];
    $footer_text = get_field('footer_text', 'option');
    $search_background_image = get_field('search_background_image', 'option');
    $search_background_style = "background: linear-gradient(0deg, var(--primary-dark) 0%, rgba(0,0,0,0.4) 100%), url($search_background_image); background-position: top center;";
?>
<footer>

    <div class="xy-grid has-gutter <?= $footer_background_color ?>">
        <div class="xy-col" data-xy-col="xl-4 lg-4 md-12 sm-12">
            <?= conditionally_output_field($footer_text, '<h2>', '</h2>'); ?>
            <?= conditionally_output_field($school_phone, '<p class="phone">', '</p>'); ?>
            <?= conditionally_output_field($address, '<p class="address">', '</p>'); ?>
            <?= conditionally_output_field($school_email, '<p class="email">', '</p>'); ?>

            <?php if($school_social) : ?>
                <ul class="social">
                    <?php foreach($school_social as $social => $value) : 
                        if(empty($value)) continue; 
                        // get theme path
                        $theme_path = get_template_directory_uri();
                        ?>
                        <li>
                            <a href="<?= $value ?>" target="_blank" class="<?= $social ?>"><?php /* ucfirst($social); */ echo "<img src='$theme_path/images/$social.svg' />"; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?= conditionally_output_field(get_acf_image('logo','full','main','option'), '<div class="footer-logo">','</div>'); ?>
        </div>
        <nav class="xy-col" data-xy-col="xl-6 lg-6 md-12 sm-12" data-xy-start="xl-7 lg-7 md-auto sm-auto">
            <?php csomaster_nav_location('footer'); ?>
        </nav>
    </div>

    <div class="xy-grid has-gutter <?= $footer_auxiliary_background_color ?>">
        <div class="breadcrumbs xy-col" data-xy-col="xl-6 lg-6 md-6 sm-12">
            <?= the_breadcrumb(); ?>
        </div>
        <nav class="nav xy-col" data-xy-col="xl-6 lg-6 md-6 sm-12">
            <?php csomaster_nav_location('footer-auxiliary'); ?>
        </nav>
    </div>
</footer>
<div class="site-search-outer closed" id="siteSearch" style="<?= $search_background_style ?>">
    <div class="site-search-inner">
        <div class="site-search-nav">
            <div class="site-logo-wrap">
                <?= get_acf_image('logo_white', 'full', 'main', 'option', 'logo') ?>
            </div>
            <div class="btn-close menu-item-btn"><a title="Menu" href="#" id="closeSearch" >Close <img src="<?= get_template_directory_uri().'/images/icons/close.svg'; ?>" class="menu-icon" /></a></div>
        </div>
        <?= get_search_form(); ?>
        <!--
        <form action="/" method="get" class="search-form">
            <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" placeholder="Search" />
            <input type="submit" value="" />
        </form>
        -->
    </div>
</div>