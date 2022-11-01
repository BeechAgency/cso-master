<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package csomaster
 */

get_header();

?>


	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', 'page' );
		/*
		if( '' !== get_post()->post_content ) :?>
			<div class="row">
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		<?php endif; */
	endwhile; // End of the loop.
	?>


<?php

get_footer();
