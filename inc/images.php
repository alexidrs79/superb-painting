<?php
/**
 * Theme sample images — painting & interior photography bundled locally.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || defined( 'SUPERB_THEME_CLI' ) || exit;

/**
 * Catalog of bundled sample images (filename relative to assets/images/).
 *
 * @return array<string, string>
 */
function superb_get_theme_images() {
	return array(
		'hero'             => 'hero-bg.jpg',
		'painter-working'  => 'sample/painter-working.jpg',
		'paint-roller'     => 'sample/paint-roller.jpg',
		'paint-supplies'   => 'sample/paint-supplies.jpg',
		'living-room'      => 'sample/living-room.jpg',
		'living-room-2'    => 'sample/living-room-2.jpg',
		'kitchen-modern'   => 'sample/kitchen-modern.jpg',
		'kitchen-white'    => 'sample/kitchen-white.jpg',
		'cabinet-kitchen'  => 'sample/cabinet-kitchen.jpg',
		'feature-wall'     => 'sample/feature-wall.jpg',
		'apartment'        => 'sample/apartment.jpg',
		'office'           => 'sample/office.jpg',
		'house-exterior'   => 'sample/house-exterior.jpg',
		'bedroom'          => 'sample/bedroom.jpg',
		'dining-room'      => 'sample/dining-room.jpg',
		'bathroom'         => 'sample/bathroom.jpg',
		'hallway'          => 'sample/hallway.jpg',
		'wall-prep'        => 'sample/wall-prep.jpg',
		'fresh-interior'   => 'sample/fresh-interior.jpg',
		'new-build'        => 'sample/new-build.jpg',
		'painter-roller'   => 'sample/painter-roller.jpg',
		'painter-brush'    => 'sample/painter-brush.jpg',
		'painter-detail'   => 'sample/painter-detail.jpg',
		'master-painters-mpa' => 'credentials/master-painters-mpa.png',
		'dulux-accredited'    => 'credentials/dulux-accredited.png',
	);
}

/**
 * Named image pools for rotating placeholders.
 *
 * @return array<string, array<int, string>>
 */
function superb_get_image_pools() {
	return array(
		'suburb_card' => array(
			'living-room',
			'living-room-2',
			'bedroom',
			'dining-room',
			'kitchen-modern',
			'feature-wall',
			'apartment',
			'hallway',
			'house-exterior',
			'fresh-interior',
			'bathroom',
			'cabinet-kitchen',
		),
		'gallery'     => array(
			'living-room',
			'living-room-2',
			'feature-wall',
			'house-exterior',
			'cabinet-kitchen',
			'kitchen-white',
			'apartment',
			'office',
			'bedroom',
			'dining-room',
			'bathroom',
			'fresh-interior',
		),
		'team'        => array(
			'painter-roller',
			'painter-brush',
			'painter-detail',
		),
	);
}

/**
 * Service slug → hero image key.
 *
 * @return array<string, string>
 */
function superb_service_hero_image_keys() {
	return array(
		'interior-wall-painting'  => 'living-room',
		'kitchen-cabinet-painting' => 'cabinet-kitchen',
		'special-finishes'        => 'feature-wall',
		'feature-walls'           => 'feature-wall',
		'apartment-painting'      => 'apartment',
		'new-build-painting'      => 'new-build',
		'commercial-painting'     => 'office',
	);
}

/**
 * @param string $key Image key.
 * @return string|null
 */
function superb_theme_image_url( $key ) {
	$images = superb_get_theme_images();
	if ( ! isset( $images[ $key ] ) ) {
		return null;
	}

	return SUPERB_THEME_URI . '/assets/images/' . ltrim( $images[ $key ], '/' );
}

/**
 * Credential / accreditation logo image markup.
 *
 * @param string $key   Image key (master-painters-mpa|dulux-accredited).
 * @param array  $attrs Optional img attributes (class, loading, etc.).
 * @return string
 */
function superb_credential_logo_img( $key, $attrs = array() ) {
	$url = superb_theme_image_url( $key );
	if ( ! $url ) {
		return '';
	}

	$labels = array(
		'master-painters-mpa' => 'Master Painters Association',
		'dulux-accredited'    => 'Dulux Accredited Painter',
	);

	$defaults = array(
		'class'   => 'superb-credential-logo',
		'loading' => 'lazy',
		'alt'     => $labels[ $key ] ?? 'Accreditation logo',
	);

	$attrs = array_merge( $defaults, $attrs );
	$html  = '<img src="' . esc_url( $url ) . '"';

	foreach ( $attrs as $name => $value ) {
		if ( null === $value || '' === $value ) {
			continue;
		}
		$html .= ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}

	$html .= '>';

	return $html;
}

/**
 * Homepage hero background image URL (LCP).
 *
 * @return string
 */
function superb_get_hero_image_url() {
	$url = superb_theme_image_url( 'hero' );
	return $url ? $url : SUPERB_THEME_URI . '/assets/images/hero-bg.jpg';
}

/**
 * Deterministic index from seed string or number.
 *
 * @param mixed $seed Seed value.
 * @param int   $count Pool size.
 */
function superb_image_index_for_seed( $seed, $count ) {
	if ( $count < 1 ) {
		return 0;
	}
	return abs( crc32( (string) $seed ) ) % $count;
}

/**
 * Pick an image URL from a named pool.
 *
 * @param string     $pool_name Pool key.
 * @param int|string $seed      Seed for deterministic selection.
 */
function superb_image_from_pool( $pool_name, $seed = 0 ) {
	$pools = superb_get_image_pools();
	if ( empty( $pools[ $pool_name ] ) ) {
		return superb_theme_image_url( 'living-room' );
	}

	$pool  = $pools[ $pool_name ];
	$index = superb_image_index_for_seed( $seed, count( $pool ) );

	return superb_theme_image_url( $pool[ $index ] );
}

/**
 * Suburb card thumbnail URL.
 *
 * @param int|string $slug_or_id Suburb slug or post ID.
 */
function superb_suburb_card_image_url( $slug_or_id ) {
	return superb_image_from_pool( 'suburb_card', $slug_or_id );
}

/**
 * Image pool key for a suburb slug or post ID.
 *
 * @param int|string $slug_or_id Suburb slug or post ID.
 */
function superb_suburb_card_image_key( $slug_or_id ) {
	$pools = superb_get_image_pools();
	$pool  = $pools['suburb_card'] ?? array( 'living-room' );
	$index = superb_image_index_for_seed( $slug_or_id, count( $pool ) );

	return $pool[ $index ];
}

/**
 * Suburb hero background URL — featured image first, then pool fallback.
 *
 * @param int $post_id Suburb post ID.
 */
function superb_suburb_hero_image_url( $post_id ) {
	if ( $post_id && has_post_thumbnail( $post_id ) ) {
		$url = get_the_post_thumbnail_url( $post_id, 'hero-bg' );
		if ( ! $url ) {
			$url = get_the_post_thumbnail_url( $post_id, 'large' );
		}
		if ( $url ) {
			return $url;
		}
	}

	$slug = $post_id ? get_post_field( 'post_name', $post_id ) : '';

	return superb_suburb_card_image_url( $slug ?: $post_id );
}

/**
 * Import a bundled theme image into the media library (cached by key).
 *
 * @param string $key   Image key from superb_get_theme_images().
 * @param string $title Attachment title.
 * @return int Attachment ID.
 */
function superb_import_theme_image_attachment( $key, $title = '' ) {
	$option_key = 'superb_img_attach_' . sanitize_key( $key );
	$existing   = (int) get_option( $option_key, 0 );
	if ( $existing && get_post( $existing ) ) {
		return $existing;
	}

	$images = superb_get_theme_images();
	if ( empty( $images[ $key ] ) ) {
		return 0;
	}

	$path = SUPERB_THEME_DIR . '/assets/images/' . ltrim( $images[ $key ], '/' );
	if ( ! file_exists( $path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$filename = basename( $path );
	$upload   = wp_upload_bits( $filename, null, file_get_contents( $path ) );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$filetype   = wp_check_filetype( $filename );
	$attach_id  = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
			'post_title'     => $title ? $title : ucwords( str_replace( '-', ' ', $key ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( ! $attach_id || is_wp_error( $attach_id ) ) {
		return 0;
	}

	$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	update_option( $option_key, $attach_id );

	return $attach_id;
}

/**
 * Set suburb featured image from bundled pool when none is set.
 *
 * @param int    $post_id Suburb post ID.
 * @param string $slug    Suburb slug.
 * @param string $name    Suburb display name.
 */
function superb_seed_suburb_featured_image( $post_id, $slug, $name = '' ) {
	if ( ! $post_id || has_post_thumbnail( $post_id ) ) {
		return;
	}

	$key       = superb_suburb_card_image_key( $slug );
	$attach_id = superb_import_theme_image_attachment( $key, trim( $name . ' featured image' ) );
	if ( $attach_id ) {
		set_post_thumbnail( $post_id, $attach_id );
	}
}

/**
 * Service page hero background URL.
 *
 * @param string $slug Service slug.
 */
function superb_service_hero_image_url( $slug ) {
	$keys = superb_service_hero_image_keys();
	$key  = $keys[ $slug ] ?? 'living-room';

	return superb_theme_image_url( $key );
}

/**
 * CSS class for service hero background (no inline styles).
 *
 * @param string $slug Service slug.
 */
function superb_service_hero_css_class( $slug ) {
	$keys      = superb_service_hero_image_keys();
	$key       = $keys[ $slug ] ?? 'living-room';
	$class_key = str_replace( '_', '-', $key );

	if ( function_exists( 'sanitize_html_class' ) ) {
		return 'page-hero--img-' . sanitize_html_class( $class_key );
	}

	return 'page-hero--img-' . preg_replace( '/[^a-z0-9\-]/', '', strtolower( $class_key ) );
}

/**
 * Before / after image pair for a service or seed.
 *
 * @param int|string $seed Seed value.
 * @return array{before:string, after:string}
 */
function superb_before_after_images( $seed = 0 ) {
	$before_pool = array( 'wall-prep', 'paint-roller', 'painter-working' );
	$after_pool  = array( 'fresh-interior', 'living-room', 'kitchen-white', 'feature-wall' );

	$before_key = $before_pool[ superb_image_index_for_seed( $seed . '-before', count( $before_pool ) ) ];
	$after_key  = $after_pool[ superb_image_index_for_seed( $seed . '-after', count( $after_pool ) ) ];

	return array(
		'before' => superb_theme_image_url( $before_key ),
		'after'  => superb_theme_image_url( $after_key ),
	);
}

/**
 * Render an img tag for a theme image.
 *
 * @param string $key   Image key.
 * @param array  $attrs HTML attributes.
 */
function superb_theme_image_tag( $key, $attrs = array() ) {
	$url = superb_theme_image_url( $key );
	if ( ! $url ) {
		return '';
	}

	$defaults = array(
		'src'     => $url,
		'alt'     => '',
		'loading' => 'lazy',
	);

	$attrs = array_merge( $defaults, $attrs );

	$html = '<img';
	foreach ( $attrs as $name => $value ) {
		if ( '' === $value && 'alt' !== $name ) {
			continue;
		}
		$html .= ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
	}
	$html .= '>';

	return $html;
}

/**
 * [superb_img key="living-room" alt="..." class="..."]
 *
 * @param array $atts Shortcode attributes.
 */
function superb_shortcode_img( $atts ) {
	$atts = shortcode_atts(
		array(
			'key'     => 'living-room',
			'alt'     => '',
			'class'   => '',
			'loading' => 'lazy',
		),
		$atts,
		'superb_img'
	);

	return superb_theme_image_tag(
		$atts['key'],
		array_filter(
			array(
				'alt'     => $atts['alt'],
				'class'   => $atts['class'],
				'loading' => $atts['loading'],
			)
		)
	);
}

/**
 * [superb_suburb_img slug="hawthorn" class="suburb-card__img" alt="Hawthorn"]
 *
 * @param array $atts Shortcode attributes.
 */
function superb_shortcode_suburb_img( $atts ) {
	$atts = shortcode_atts(
		array(
			'slug'    => '',
			'alt'     => '',
			'class'   => 'suburb-card__img',
			'loading' => 'lazy',
		),
		$atts,
		'superb_suburb_img'
	);

	$url = superb_suburb_card_image_url( $atts['slug'] ?: $atts['alt'] );

	return sprintf(
		'<img src="%s" alt="%s" class="%s" loading="%s">',
		esc_url( $url ),
		esc_attr( $atts['alt'] ),
		esc_attr( $atts['class'] ),
		esc_attr( $atts['loading'] )
	);
}

/**
 * [superb_gallery_img index="1" alt="Recent project"]
 *
 * @param array $atts Shortcode attributes.
 */
function superb_shortcode_gallery_img( $atts ) {
	$atts = shortcode_atts(
		array(
			'index'   => '1',
			'alt'     => '',
			'class'   => '',
			'loading' => 'lazy',
		),
		$atts,
		'superb_gallery_img'
	);

	$url = superb_image_from_pool( 'gallery', (int) $atts['index'] );

	$class = $atts['class'] ? ' class="' . esc_attr( $atts['class'] ) . '"' : '';

	return sprintf(
		'<img src="%s" alt="%s"%s loading="%s">',
		esc_url( $url ),
		esc_attr( $atts['alt'] ),
		$class,
		esc_attr( $atts['loading'] )
	);
}

/**
 * Replace legacy picsum.photos URLs in stored content.
 *
 * @param bool $dry_run Count only.
 * @return int Number of posts updated.
 */
function superb_migrate_content_images( $dry_run = false ) {
	global $wpdb;

	$posts = $wpdb->get_results(
		"SELECT ID, post_content FROM {$wpdb->posts}
		WHERE post_content LIKE '%picsum.photos%'
		AND post_status IN ('publish','draft','private')"
	);

	$updated = 0;

	foreach ( $posts as $post ) {
		$new_content = superb_replace_picsum_urls( $post->post_content );
		if ( $new_content === $post->post_content ) {
			continue;
		}

		if ( ! $dry_run ) {
			wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_content' => $new_content,
				)
			);
		}
		++$updated;
	}

	return $updated;
}

/**
 * Swap picsum URLs in HTML for theme image URLs.
 *
 * @param string $content HTML content.
 */
function superb_replace_picsum_urls( $content ) {
	if ( false === strpos( $content, 'picsum.photos' ) ) {
		return $content;
	}

	$content = preg_replace_callback(
		'#https://picsum\.photos/[^"\')\s]+(?:\?random=(\d+))?#',
		function ( $matches ) {
			$seed = isset( $matches[1] ) ? (int) $matches[1] : wp_rand( 1, 999 );
			return esc_url( superb_image_from_pool( 'suburb_card', $seed ) );
		},
		$content
	);

	return $content;
}

if ( function_exists( 'add_action' ) ) {
	add_action(
		'init',
		function () {
			add_shortcode( 'superb_img', 'superb_shortcode_img' );
			add_shortcode( 'superb_suburb_img', 'superb_shortcode_suburb_img' );
			add_shortcode( 'superb_gallery_img', 'superb_shortcode_gallery_img' );
		}
	);
}
