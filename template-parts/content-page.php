<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package csomaster
 */
?>

<?php get_template_part( 'template-parts/navs/nav','in-page', array( 'page_id' => $page_id )); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >

	<div class="entry-content">

	<?php  include(dirname(__DIR__).'/template-parts/blocks.php'); ?>

	<?php if(have_rows('content_block')):?>

		<?php $c = 0; while(have_rows('content_block')): $c++;?> 

			<?php the_row();

				//get_template_part( 'template-parts/content-blocks/'.get_row_layout(), '' );
				include(dirname(__DIR__).'/template-parts/content-blocks/'.get_row_layout().'.php');
			?>

		<?php endwhile;
	endif; ?>


		<?php
		//the_content();
		/*
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'guttentest' ),
				'after'  => '</div>',
			)
		); */
		?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
