<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package csomaster
 */

if ( ! function_exists( 'csomaster_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function csomaster_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( '%s', 'post date', 'csomaster' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'csomaster_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function csomaster_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'By %s', 'post author', 'csomaster' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span> • '
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'csomaster_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function csomaster_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) { ?>
			
		<div class="row feed-title-container">
			<div class="col-4">
				<h2 style="text-align: left;">Other Stories</h2>
			</div>
			<div class="col-2 start-11 sm-start-1 see-all-link">
				<a class="button-with-icon icon-right" href="<?= get_post_type_archive_link( 'post' ); ?>">See all news</a>
			</div>
		</div><?php
			csomaster_post_list('post', 4, null, 3);

		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'csomaster' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'csomaster' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'csomaster_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function csomaster_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;


if ( ! function_exists( 'csomaster_post_category') ) :
	/**
	 * Displays Posts Categories
	 */

	 function csomaster_post_category() {
		
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		} 

		$categories = get_the_category();
		$category;
		 
		if(count($categories) > 0) {
			$category = $categories[0];
			echo '<span class="category '.$category->slug.'">'.$category->name.'</span>';
		}

		return;
	 }

endif;


if ( ! function_exists( 'csomaster_post_category_attribute') ) :
	/**
	 * Displays Posts Categories
	 */

	 function csomaster_post_category_attribute() {
		
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		} 

		$categories = get_the_category();
		$category;
		 
		if(count($categories) > 0) {
			$category = $categories[0];
			echo 'data-category="'.$category->slug.'"';
		}

		return;
	 }

endif;


if ( ! function_exists( 'csomaster_category_list') ) :
	/**
	 * Displays Posts Categories
	 */

	 function csomaster_category_list() {
		
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		} 

		$categories = get_categories();
		$output = '';

		foreach($categories as $category) :

			$name = $category->name;
			$slug = $category->slug;

			$output = $output. '<li class="filter-item" data-slug="'.$slug.'"><a href="'.$slug.'" class="button pill">'.$name.'</a></li>';

		endforeach;

		echo $output;

		return;
	 }

endif;


if ( ! function_exists( 'csomaster_post_list') ) :
	/**
	* Displays A List of Posts
	*/
	function csomaster_post_list( $args = array() ) {
		$postType = $args['post'] ?? 'post';
		$numberOfPosts	= $args['number'] ?? 4;
		$category = $args['category'] ?? null;
		$postIds = $args['posts'] ?? null;
		$displayWrapper = $args['wrapper'] ?? true;

		$postOptions = $args['options'] ?? array();

	
		$args = array(
			'posts_per_page' => $numberOfPosts,
			'post_type' => $postType,
			'order'=>'DESC',
			'orderby'=>'ID'
		);

		if(!empty($category)) {
			$args['category_name'] = $category;
		}

		if (get_post_type() !== 'page') {
			$postId = get_the_ID();
			$args['post__not_in'] = array($postId);
		}

		if(!empty($postIds)) {
			$args['post__in'] = $postIds;
		}

		$morePosts = new WP_Query(
			$args
		);

		if( $morePosts->have_posts() ) : ?>

		<?php if($displayWrapper):?><ul class="post-list row"><?php endif; ?>
		<?php
			while( $morePosts->have_posts() ): $morePosts->the_post();
			

			get_template_part( 'template-parts/parts/post','item', $postOptions );

		    endwhile; ?>
		<?php if($displayWrapper):?></ul><?php endif; ?>
		<?php endif;
		wp_reset_postdata();
	}
endif;

if ( ! function_exists( 'csomaster_number_pagination') ) :
	/**
	* Displays A List of Posts
	*/
	function csomaster_number_pagination() {
		$args = array(
			'base'               => '%_%',
			'format'             => '?paged=%#%',
			'total'              => 1,
			'current'            => 0,
			'show_all'           => false,
			'end_size'           => 1,
			'mid_size'           => 2,
			'prev_next'          => true,
			'prev_text'          => __('«'),
			'next_text'          => __('»'),
			'type'               => 'plain',
			'add_args'           => false,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => ''
		);
		
		global $wp_query;
		$big = 9999999; // need an unlikely integer
		
		echo paginate_links( 
			array(
			   'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			   'format' => '?paged=%#%',
			   'current' => max( 1, get_query_var('paged') ),
			   'total' => $wp_query->max_num_pages,
			   'prev_text' => __('<img src="#" />'),
			   'next_text' => __('<img src="#" />')
			)
		);
	}	/*»«*/

endif;