<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package csomaster
 */

get_header();

$page_for_posts = get_option( 'page_for_posts' );
$page_id = get_the_ID();

if(is_home()) {
    $page_id = $page_for_posts;
}

$section_bg = get_field('background_color', $page_id);
$card_bg = get_field('background_color_cards', $page_id);
$card_text = get_field('text_color', $page_id);

?>
	<section class="block post-block__post-grid <?= $section_bg. ' '.$card_text ?>">
		<?php
		if ( have_posts() ) :
			if ( is_home() && ! is_front_page() ) :
			endif;
			/* Start the Loop */
			?>
			<ul class="post-list xy-grid">
			<?php
				$n = 0;
				$nCount = get_option('posts_per_page');

				while ( have_posts() ) :
					the_post();
					echo "<!--post n: ".$n." -->";

					/*
					* Include the Post-Type-specific template for the content.
					* If you want to override this in a child theme, then include a file
					* called content-___.php (where ___ is the Post Type name) and that will be used instead.
					*/

					get_template_part( 'template-parts/parts/post', 'item', 
						array(
							'wrapper' => 'li', 
							'class' => "card xy-col $card_bg", 
							'grid' => 'xl-4 lg-4 md-6 sm-12'
							) 
						);

					$n++;
				endwhile;
			?>
			</ul>

			<?php

		else :
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>

			</div>

			<?php
			the_posts_navigation();
			?>
	</section>
<?php
get_footer();
