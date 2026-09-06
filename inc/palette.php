<?php
/**
 * Superb Painting color palette — Kadence sync and content migration.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Kadence global palette slots mapped to Superb brand colors.
 *
 * @return array<int, array{color:string, slug:string, name:string}>
 */
function superb_kadence_palette_colors() {
	return array(
		array( 'color' => '#C9A84C', 'slug' => 'palette1', 'name' => 'Gold Accent' ),
		array( 'color' => '#A8873A', 'slug' => 'palette2', 'name' => 'Gold Dark' ),
		array( 'color' => '#1A1A1A', 'slug' => 'palette3', 'name' => 'Charcoal' ),
		array( 'color' => '#2D2D2D', 'slug' => 'palette4', 'name' => 'Dark Surface' ),
		array( 'color' => '#6B6B6B', 'slug' => 'palette5', 'name' => 'Muted Text' ),
		array( 'color' => '#B0B0B0', 'slug' => 'palette6', 'name' => 'Subtle Heading' ),
		array( 'color' => '#F8F8F6', 'slug' => 'palette7', 'name' => 'Background' ),
		array( 'color' => '#EFEFEC', 'slug' => 'palette8', 'name' => 'Background Light' ),
		array( 'color' => '#ffffff', 'slug' => 'palette9', 'name' => 'White' ),
		array( 'color' => '#ffffff', 'slug' => 'palette10', 'name' => 'White Complement' ),
		array( 'color' => '#13612e', 'slug' => 'palette11', 'name' => 'Success' ),
		array( 'color' => '#333333', 'slug' => 'palette12', 'name' => 'Utility Dark' ),
		array( 'color' => '#c0392b', 'slug' => 'palette13', 'name' => 'Error' ),
		array( 'color' => '#A8873A', 'slug' => 'palette14', 'name' => 'Warning' ),
		array( 'color' => '#C9A84C', 'slug' => 'palette15', 'name' => 'Rating' ),
	);
}

/**
 * Legacy hex → new palette replacements for stored post content.
 *
 * @return array<string, string>
 */
function superb_palette_color_replacements() {
	return array(
		'#1B2B4B'                    => '#1A1A1A',
		'#1b2b4b'                    => '#1A1A1A',
		'#152238'                    => '#2D2D2D',
		'#F97316'                    => '#C9A84C',
		'#f97316'                    => '#C9A84C',
		'#ea580c'                    => '#A8873A',
		'#EF4444'                    => '#c0392b',
		'#ef4444'                    => '#c0392b',
		'#6B7280'                    => '#6B6B6B',
		'#6b7280'                    => '#6B6B6B',
		'#111827'                    => '#111111',
		'#F9FAFB'                    => '#F8F8F6',
		'#f9fafb'                    => '#F8F8F6',
		'#EFF6FF'                    => '#EFEFEC',
		'#eff6ff'                    => '#EFEFEC',
		'#1F2937'                    => '#1A1A1A',
		'#1f2937'                    => '#1A1A1A',
		'#E5E7EB'                    => '#dddddd',
		'#e5e7eb'                    => '#dddddd',
		'rgba(27, 43, 75,'           => 'rgba(26, 26, 26,',
		'rgba(27,43,75,'              => 'rgba(26,26,26,',
		'rgba(249, 115, 22,'         => 'rgba(201, 168, 76,',
		'rgba(249,115,22,'            => 'rgba(201,168,76,',
	);
}

/**
 * Push Superb palette into Kadence global palette option.
 *
 * @param bool $force Skip version guard.
 */
function superb_configure_kadence_palette( $force = false ) {
	if ( ! $force && get_option( 'superb_palette_config_version' ) === SUPERB_THEME_VERSION ) {
		return;
	}

	$colors = superb_kadence_palette_colors();
	$data   = array(
		'palette'        => $colors,
		'second-palette' => $colors,
		'third-palette'  => $colors,
		'active'         => 'palette',
	);

	update_option( 'kadence_global_palette', wp_json_encode( $data ) );
	update_option( 'superb_palette_config_version', SUPERB_THEME_VERSION );
}

/**
 * Replace legacy palette hex values in all published content.
 *
 * @return int Number of posts updated.
 */
function superb_migrate_content_colors() {
	$replacements = superb_palette_color_replacements();
	$updated      = 0;

	$posts = get_posts(
		array(
			'post_type'      => array( 'page', 'post', 'service', 'suburb' ),
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	foreach ( $posts as $post_id ) {
		$content = get_post_field( 'post_content', $post_id );
		if ( ! is_string( $content ) || '' === $content ) {
			continue;
		}

		$new_content = str_replace( array_keys( $replacements ), array_values( $replacements ), $content );
		if ( $new_content === $content ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_content' => $new_content,
			)
		);
		++$updated;
	}

	return $updated;
}

add_action( 'after_setup_theme', 'superb_configure_kadence_palette', 29 );
