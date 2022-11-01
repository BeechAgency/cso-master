<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package csomaster
 */


$class_list = 'text-block__text basic has-transparent-background-color has-black-color';
$fields = array();

$fields['style'] = 'basic';
$fields['content'] = get_the_content();
$fields['subtitle'] = null;
$fields['title'] = null;
$fields['cta'] = null;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<section class="block <?= $class_list ?>" id="rowContent">
		<div class="xy-grid">
			<?php get_template_part( "template-parts/blocks/text/block__text", null, $fields ); ?>
		</div>
	</section>
</article><!-- #post-<?php the_ID(); ?> -->
