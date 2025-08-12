<?php
// Step 1: Process St Nicks content builder fields early in the process
add_action( 'dt_after_set_meta', 'csomaster_process_stnicks_content', 5, 3 );
function csomaster_process_stnicks_content( $meta, $existing_meta, $post_id ) {
	if ( get_post_type( $post_id ) !== 'post' ) return;
	
	// Check if this post originates from St Nicks (check both meta arrays)
	$original_site_url = '';
	if ( ! empty( $meta['dt_original_site_url'][0] ) ) {
		$original_site_url = $meta['dt_original_site_url'][0];
	} elseif ( ! empty( $existing_meta['dt_original_site_url'][0] ) ) {
		$original_site_url = $existing_meta['dt_original_site_url'][0];
	}
	
	if ( $original_site_url !== 'https://stnicks.org.au' ) {
		return;
	}
	
	error_log("🏫 Processing St Nicks content for post $post_id from $original_site_url");
	
	// Find all content_builder fields
	$content_builder_fields = [];
	
	foreach ( $meta as $key => $value ) {
		if ( strpos( $key, 'content_builder_singl_' ) === 0 && strpos( $key, '_text_area' ) !== false && strpos( $key, '_content_builder_singl_' ) !== 0 ) {
			// Extract the index number from the field name
			preg_match('/content_builder_singl_(\d+)_text_area/', $key, $matches);
			if ( isset( $matches[1] ) ) {
				$index = intval( $matches[1] );
				$content_builder_fields[$index] = $value[0];
			}
		}
	}
	
	// Sort by index to maintain order
	ksort( $content_builder_fields );
	
	error_log("🔍 Found " . count( $content_builder_fields ) . " content builder fields");
	
	if ( ! empty( $content_builder_fields ) ) {
		// Combine all content parts
		$combined_content = implode( "\n\n", $content_builder_fields );
		
		// Get the current post content (intro)
		$post = get_post( $post_id );
		$current_content = $post->post_content;
		
		// Combine intro with ACF content
		$new_content = $current_content . "\n\n" . $combined_content;
		
		// Update the post content
		wp_update_post( [
			'ID' => $post_id,
			'post_content' => $new_content
		] );
		
		error_log("✅ Updated post $post_id content with " . count( $content_builder_fields ) . " ACF content sections");
		
		// Extract gallery IDs from the content and manually download media
		preg_match_all('/\[gallery[^\]]*ids="([^"]+)"[^\]]*\]/', $new_content, $gallery_matches);
		if ( ! empty( $gallery_matches[1] ) ) {
			$gallery_ids = [];
			foreach ( $gallery_matches[1] as $ids_string ) {
				$ids = explode( ',', $ids_string );
				$gallery_ids = array_merge( $gallery_ids, array_map( 'trim', $ids ) );
			}
			
			if ( ! empty( $gallery_ids ) ) {
				error_log("🖼️ Found gallery IDs to process: " . implode( ', ', $gallery_ids ));
				
				// Get the original site URL for media downloads
				$original_site_url = '';
				if ( ! empty( $meta['dt_original_site_url'][0] ) ) {
					$original_site_url = $meta['dt_original_site_url'][0];
				} elseif ( ! empty( $existing_meta['dt_original_site_url'][0] ) ) {
					$original_site_url = $existing_meta['dt_original_site_url'][0];
				}
				
				// Manually download each gallery image
				foreach ( $gallery_ids as $gallery_id ) {
					csomaster_download_remote_media( $gallery_id, $original_site_url, $post_id );
				}
			}
		}
	}
}

// Helper function to download remote media
function csomaster_download_remote_media( $media_id, $original_site_url, $post_id ) {
	// Check if we already have this media locally
	$existing = get_posts( [
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'numberposts' => 1,
		'meta_query'  => [
			[
				'key'     => 'dt_original_media_id',
				'value'   => $media_id,
				'compare' => '='
			]
		]
	] );
	
	if ( ! empty( $existing ) ) {
		error_log("✅ Media $media_id already exists locally as {$existing[0]->ID}");
		return $existing[0]->ID;
	}
	
	// Get media info from remote site
	$remote_url = trailingslashit( $original_site_url ) . 'wp-json/wp/v2/media/' . $media_id;
	$response = wp_remote_get( $remote_url );
	
	if ( is_wp_error( $response ) ) {
		error_log("❌ Failed to get media info for $media_id: " . $response->get_error_message());
		return false;
	}
	
	$media_data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $media_data['source_url'] ) ) {
		error_log("❌ No source URL found for media $media_id");
		return false;
	}
	
	// Download the image
	$image_url = $media_data['source_url'];
	$image_response = wp_remote_get( $image_url );
	
	if ( is_wp_error( $image_response ) ) {
		error_log("❌ Failed to download image $image_url: " . $image_response->get_error_message());
		return false;
	}
	
	// Get image data
	$image_data = wp_remote_retrieve_body( $image_response );
	$filename = basename( $image_url );
	
	// Upload to WordPress
	$upload = wp_upload_bits( $filename, null, $image_data );
	if ( $upload['error'] ) {
		error_log("❌ Failed to upload image $filename: " . $upload['error']);
		return false;
	}
	
	// Create attachment
	$attachment = [
		'post_mime_type' => $media_data['mime_type'] ?? wp_check_filetype( $filename )['type'],
		'post_title'     => $media_data['title']['rendered'] ?? sanitize_file_name( $filename ),
		'post_content'   => $media_data['description']['rendered'] ?? '',
		'post_excerpt'   => $media_data['caption']['rendered'] ?? '',
		'post_status'    => 'inherit'
	];
	
	$attachment_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
	
	if ( is_wp_error( $attachment_id ) ) {
		error_log("❌ Failed to create attachment for $filename");
		return false;
	}
	
	// Generate attachment metadata
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
	wp_update_attachment_metadata( $attachment_id, $attachment_data );
	
	// Store the original media ID for reference
	update_post_meta( $attachment_id, 'dt_original_media_id', $media_id );
	update_post_meta( $attachment_id, 'dt_original_media_url', $image_url );
	
	error_log("✅ Downloaded media $media_id as local attachment $attachment_id");
	
	return $attachment_id;
}

// Step 1.5: Force gallery media processing for St Nicks posts
add_filter( 'dt_media', 'csomaster_force_stnicks_gallery_media', 10, 2 );
function csomaster_force_stnicks_gallery_media( $media, $post_array ) {
	// Check if this is a St Nicks post
	if ( empty( $post_array['meta']['dt_original_site_url'][0] ) || $post_array['meta']['dt_original_site_url'][0] !== 'https://stnicks.org.au' ) {
		return $media;
	}
	
	// Extract gallery IDs from content
	$content = $post_array['post_content'];
	preg_match_all('/\[gallery[^\]]*ids="([^"]+)"[^\]]*\]/', $content, $gallery_matches);
	
	if ( ! empty( $gallery_matches[1] ) ) {
		$gallery_ids = [];
		foreach ( $gallery_matches[1] as $ids_string ) {
			$ids = explode( ',', $ids_string );
			$gallery_ids = array_merge( $gallery_ids, array_map( 'trim', $ids ) );
		}
		
		// Add gallery IDs to media list
		foreach ( $gallery_ids as $gallery_id ) {
			if ( ! in_array( $gallery_id, $media ) ) {
				$media[] = $gallery_id;
			}
		}
		
		error_log("📦 Forcing media import of gallery IDs: " . implode( ', ', $gallery_ids ));
	}
	
	return $media;
}

// Step 2: Set default header styling for all distributed posts
add_action( 'dt_after_set_meta', 'csomaster_set_default_header_styling', 25, 3 );
function csomaster_set_default_header_styling( $meta, $existing_meta, $post_id ) {
	if ( get_post_type( $post_id ) !== 'post' ) return;
	
	// Set default header styling for all distributed posts
	update_field( 'header_background_color', 'has-primary-dark-background-color', $post_id );
	update_field( 'header_text_color', 'has-white-color', $post_id );
	update_field( 'header_gradient', 1, $post_id );
	
	error_log("🎨 Set default header styling for distributed post $post_id");
}

// Step 3: Flag the post for delayed remapping
add_action( 'dt_after_set_meta', 'csomaster_flag_post_for_header_image_remap', 30, 3 );
function csomaster_flag_post_for_header_image_remap( $meta, $existing_meta, $post_id ) {
	if ( get_post_type( $post_id ) !== 'post' ) return;

    error_log("🟡 Post $post_id is being processed");
	error_log("📝 Current data: " . print_r($meta, true) . " ". print_r($existing_meta, true) .' '.print_r($post_id, true) );
	
	$featured_image_id = get_post_thumbnail_id($post_id);
    // Capture the header_image meta early, before ACF wipes it
    if ( ! empty( $meta['header_image'][0] ) ) {
    	error_log("🟡 Post $post_id flagged for delayed header_image remap");
		update_post_meta( $post_id, '_needs_header_image_remap', true );
        update_post_meta( $post_id, '_header_image_remap', $meta['header_image'][0] );
        error_log("📝 Stored original header_image {". $meta['header_image'][0] ."} in _header_image_remap for post $post_id");
	} elseif( ! empty( $featured_image_id ) ) {
    	error_log("🟡 Post $post_id flagged for delayed header_image remap");
		update_post_meta( $post_id, '_needs_header_image_remap', true );
		update_post_meta( $post_id, '_header_image_remap', $featured_image_id );
		error_log("🖼️ Stored featured image {". $featured_image_id ."} in _header_image_remap for post $post_id");
	} else {
		error_log("🟡 Post $post_id has no header_image or featured image set, skipping remap");
	}
}

// Step 2.5: Remap gallery IDs after media processing
add_action( 'dt_after_set_meta', 'csomaster_remap_stnicks_gallery_ids', 40, 3 );
function csomaster_remap_stnicks_gallery_ids( $meta, $existing_meta, $post_id ) {
	if ( get_post_type( $post_id ) !== 'post' ) return;
	
	// Check if this post originates from St Nicks
	$original_site_url = '';
	if ( ! empty( $meta['dt_original_site_url'][0] ) ) {
		$original_site_url = $meta['dt_original_site_url'][0];
	} elseif ( ! empty( $existing_meta['dt_original_site_url'][0] ) ) {
		$original_site_url = $existing_meta['dt_original_site_url'][0];
	}
	
	if ( $original_site_url !== 'https://stnicks.org.au' ) {
		return;
	}
	
	$post = get_post( $post_id );
	$content = $post->post_content;
	
	// Find all gallery shortcodes and remap their IDs
	$content = preg_replace_callback(
		'/\[gallery([^\]]*ids="[^"]+")([^\]]*)\]/',
		function( $matches ) use ( $post_id ) {
			$shortcode_atts = $matches[1] . $matches[2];
			
			// Extract current IDs
			preg_match('/ids="([^"]+)"/', $shortcode_atts, $id_matches);
			if ( empty( $id_matches[1] ) ) {
				return $matches[0];
			}
			
			$original_ids = explode( ',', $id_matches[1] );
			$new_ids = [];
			
			foreach ( $original_ids as $original_id ) {
				$original_id = trim( $original_id );
				
				// Find the local attachment with this original ID
				$local = get_posts( [
					'post_type'   => 'attachment',
					'post_status' => 'inherit',
					'numberposts' => 1,
					'meta_query'  => [
						[
							'key'     => 'dt_original_media_id',
							'value'   => $original_id,
							'compare' => '='
						]
					]
				] );
				
				if ( ! empty( $local ) ) {
					$new_ids[] = $local[0]->ID;
					error_log("🔄 Remapped gallery ID $original_id to {$local[0]->ID}");
				} else {
					$new_ids[] = $original_id; // Keep original if no local version found
					error_log("⚠️ No local media found for gallery ID $original_id");
				}
			}
			
			// Replace the IDs in the shortcode
			$new_ids_string = implode( ',', $new_ids );
			$new_shortcode = str_replace( $id_matches[1], $new_ids_string, $matches[0] );
			
			return $new_shortcode;
		},
		$content
	);
	
	// Update the post content with remapped gallery IDs
	if ( $content !== $post->post_content ) {
		wp_update_post( [
			'ID' => $post_id,
			'post_content' => $content
		] );
		error_log("✅ Remapped gallery IDs in post $post_id content");
	}
}

add_action( 'acf/save_post', 'csomaster_remap_header_image_on_acf_save', 20 );
function csomaster_remap_header_image_on_acf_save( $post_id ) {
	// Bail early if this isn't a real post
	if ( ! is_numeric( $post_id ) ) return;
	if ( get_post_type( $post_id ) !== 'post' ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( wp_is_post_revision( $post_id ) ) return;
	if ( wp_is_post_autosave( $post_id ) ) return;
	

	error_log("📌 ACF save_post triggered for post ID $post_id, type is ".get_post_type( $post_id ));
	error_log("🧪 Raw header_image meta: " . print_r( get_metadata( 'post', $post_id, '_header_image_remap', true ), true ));

	$original_id = get_metadata( 'post', $post_id, '_header_image_remap', true );
	if ( ! $original_id ) {
		error_log("❌ No original header_image found on post $post_id");
		return;
	}

	$local = get_posts( [
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'numberposts' => 1,
		'meta_query'  => [
			[
				'key'     => 'dt_original_media_id',
				'value'   => $original_id,
				'compare' => '='
			]
		]
	] );

	if ( empty( $local ) ) {
		error_log("❌ No local attachment found matching dt_original_media_id = $original_id");
		return;
	}

	$local_id = $local[0]->ID;

    error_log("🔄 Remapping header_image from $original_id to $local_id on post $post_id");

	// Use update_field for ACF
	$result = update_field( 'header_image', $local_id, $post_id );

	if ( $result ) {
		error_log("✅ ACF update: header_image updated to $local_id on post $post_id");

        // Clear it up
        delete_post_meta( $post_id, '_needs_header_image_remap' );
        delete_post_meta( $post_id, '_header_image_remap' );
	} else {
		error_log("❌ ACF update failed for post $post_id");
	}
}

// Step 2: Force header_image media pull
add_filter( 'dt_media', 'csomaster_force_header_image_media_pull', 10, 2 );
function csomaster_force_header_image_media_pull( $media, $post_array ) {
    if ( empty( $post_array['meta']['header_image'][0] ) ) {
        return $media;
    }

    $header_image_id = $post_array['meta']['header_image'][0];

    // Add to media list if not already included
    if ( ! in_array( $header_image_id, $media ) ) {
        $media[] = $header_image_id;
        error_log("📦 Forcing media import of header_image ID $header_image_id");
    }

    return $media;
}