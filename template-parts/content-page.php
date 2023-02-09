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

	<?php include(dirname(__DIR__).'/template-parts/blocks.php'); ?>
	
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
