<?php

$page_for_posts = get_option( 'page_for_posts' );
$page_id = get_the_ID();

if(is_home()) {
    $page_id = $page_for_posts;
}

$header_data = get_header_data($page_id);

if(is_single() && get_post_type() !== 'post') {
    $header_data = apply_filters('csomaster_custom_header_data', 'csomaster_set_custom_header_data');
}

$header_template = $header_data['header_style'] === 'full' ? 'standard' : ( $header_data['header_style'] === 'slider' ? 'slider' : 'alternative' );

$header_data['header_template'] = $header_template;

//var_dump(csomaster_custom_header_data());
/*
if(!empty(csomaster_custom_header_data())) {
    $header_data = csomaster_custom_header_data();
}
*/


// if $header_template is alternative, then we need to change the auxiliary navigation background color to secondary
if($header_template === 'alternative') {
    $header_data['auxiliary_navigation_background_color'] = $header_data['auxiliary_navigation_background_color_inverse'];
}

?>

<?php if((bool) $header_data['auxiliary_navigation_display'] === true): ?>
    <?php get_template_part( 'template-parts/navs/nav', 'auxiliary', $header_data ); ?>
<?php endif; ?>
<?php get_template_part( "template-parts/navs/nav", "mega", $header_data ); ?>
<?php get_template_part( 'template-parts/headers/header', $header_template, $header_data ); ?>
    