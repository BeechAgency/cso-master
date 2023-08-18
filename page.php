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

if( have_posts() ) :
	
	while ( have_posts() ) :
		the_post();

		if ( post_password_required() ) {
			// Content to display when the post is password protected

			echo '<div class="custom-password-form"><div class="inner">';
				echo '<h3>Sorry, this page is password protected.</h3>';
				echo get_the_password_form(); 
			echo '</div></div>';
			
		} else {
			// Display the content for non-protected posts
			//the_content();
			get_template_part( 'template-parts/content', 'page' );
		}

	endwhile; // End of the loop.

endif;

get_footer();
