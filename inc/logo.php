<?php
/**
 * Site logo — import and Kadence header/footer configuration.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'SUPERB_LOGO_SOURCE_URL' ) ) {
	define( 'SUPERB_LOGO_SOURCE_URL', 'http://superbpainting.com/wp-content/uploads/2026/06/cropped-222.png' );
}

if ( ! defined( 'SUPERB_LOGO_PATH' ) ) {
	define( 'SUPERB_LOGO_PATH', SUPERB_THEME_DIR . '/assets/images/logo.png' );
}

/**
 * Public logo URL (attachment or bundled asset).
 */
function superb_get_logo_url() {
	$attach_id = (int) get_option( 'superb_logo_attachment_id', 0 );
	if ( $attach_id ) {
		$url = wp_get_attachment_image_url( $attach_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}

	if ( file_exists( SUPERB_LOGO_PATH ) ) {
		return SUPERB_THEME_URI . '/assets/images/logo.png';
	}

	return SUPERB_LOGO_SOURCE_URL;
}

/**
 * Import bundled logo into the media library.
 *
 * @return int Attachment ID.
 */
function superb_import_logo_attachment() {
	$existing = (int) get_option( 'superb_logo_attachment_id', 0 );
	if ( $existing && get_post( $existing ) ) {
		return $existing;
	}

	if ( ! file_exists( SUPERB_LOGO_PATH ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_bits( 'superb-painting-logo.png', null, file_get_contents( SUPERB_LOGO_PATH ) );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$attachment = array(
		'post_mime_type' => 'image/png',
		'post_title'     => 'Superb Painting Logo',
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $upload['file'] );
	if ( ! $attach_id || is_wp_error( $attach_id ) ) {
		return 0;
	}

	$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	update_option( 'superb_logo_attachment_id', $attach_id );

	return $attach_id;
}

/**
 * Apply logo when theme version changes.
 */
function superb_maybe_apply_site_logo() {
	if ( get_option( 'superb_logo_applied' ) === SUPERB_THEME_VERSION ) {
		return;
	}
	superb_apply_site_logo();
	update_option( 'superb_logo_applied', SUPERB_THEME_VERSION );
}
add_action( 'after_setup_theme', 'superb_maybe_apply_site_logo', 35 );

/**
 * Apply logo to WordPress + Kadence theme mods.
 */
function superb_apply_site_logo() {
	$attach_id = superb_import_logo_attachment();
	if ( $attach_id ) {
		set_theme_mod( 'custom_logo', $attach_id );
	}

	set_theme_mod(
		'logo_layout',
		array(
			'include' => array(
				'desktop' => 'logo',
				'tablet'  => 'logo',
				'mobile'  => 'logo',
			),
			'layout'  => array(
				'desktop' => 'standard',
				'tablet'  => 'standard',
				'mobile'  => 'standard',
			),
		)
	);

	set_theme_mod(
		'logo_width',
		array(
			'size' => array(
				'desktop' => 160,
				'tablet'  => 140,
				'mobile'  => 120,
			),
			'unit' => array(
				'desktop' => 'px',
				'tablet'  => 'px',
				'mobile'  => 'px',
			),
		)
	);
}

/**
 * Logo markup for footer and other templates.
 *
 * @param array $args Optional img attributes.
 */
function superb_logo_markup( $args = array() ) {
	$defaults = array(
		'class'   => 'superb-logo',
		'loading' => 'lazy',
		'width'   => 160,
		'height'  => 160,
	);
	$args     = wp_parse_args( $args, $defaults );

	$url = superb_get_logo_url();
	if ( ! $url ) {
		return '';
	}

	$alt = get_bloginfo( 'name', 'display' );

	return sprintf(
		'<img src="%1$s" alt="%2$s" class="%3$s" width="%4$d" height="%5$d" loading="%6$s" decoding="async">',
		esc_url( $url ),
		esc_attr( $alt ),
		esc_attr( $args['class'] ),
		(int) $args['width'],
		(int) $args['height'],
		esc_attr( $args['loading'] )
	);
}
