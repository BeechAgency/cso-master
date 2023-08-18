<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package csomaster
 */

get_header();

?>

		<?php
		while ( have_posts() ) :
			the_post();
			var_dump( post_password_required() );
			if ( post_password_required() ) {
			// Content to display when the post is password protected

			echo '<div class="custom-password-form"><div class="inner">';
				echo '<h3>Sorry, this page is password protected.</h3>';
				echo get_the_password_form(); 
			echo '</div></div>';

		} else {

			get_template_part( 'template-parts/content', get_post_type() );

		}

		endwhile; // End of the loop.
		?>


<?php

get_footer();
