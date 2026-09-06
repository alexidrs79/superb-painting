<?php
/**
 * Content filters — portable URLs, theme path fixes.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Normalize theme asset URLs in HTML content.
 *
 * @param string $content Post content.
 * @return string
 */
function superb_normalize_theme_content_urls( $content ) {
	if ( empty( $content ) || ! is_string( $content ) ) {
		return $content;
	}

	$theme_slug = 'kadence-child-superb';
	$theme_path = '/wp-content/themes/' . $theme_slug . '/';
	$theme_uri  = trailingslashit( SUPERB_THEME_URI );
	$home       = untrailingslashit( home_url() );

	// Repair URLs doubled by replacing a path segment inside an absolute URL.
	$content = preg_replace(
		'#https?://[^/\s"\']+https?://[^/\s"\']+(/wp-content/themes/' . preg_quote( $theme_slug, '#' ) . '/)#',
		$home . '$1',
		$content
	);

	$local_hosts = array(
		'http://superb-painting.local',
		'https://superb-painting.local',
	);

	foreach ( $local_hosts as $host ) {
		$content = str_replace( $host . $theme_path, $theme_uri, $content );
	}

	foreach ( $local_hosts as $host ) {
		$content = str_replace( $host, $home, $content );
	}

	// Root-relative theme paths inside HTML attributes only.
	$content = preg_replace(
		'#(?<=["\'])' . preg_quote( $theme_path, '#' ) . '#',
		$theme_uri,
		$content
	);

	return $content;
}

/**
 * Repair invalid testimonial ARIA roles in stored page content.
 *
 * @param string $content Post content.
 * @return string
 */
function superb_fix_testimonial_aria_roles( $content ) {
	if ( empty( $content ) || false === strpos( $content, 'testimonial-card' ) ) {
		return $content;
	}

	$content = str_replace( ' role="listitem"', '', $content );
	$content = str_replace( ' role="list"', '', $content );

	if ( false !== strpos( $content, 'testimonials-carousel' ) ) {
		$content = preg_replace(
			'/class="testimonials-carousel"(?![^>]*\baria-label=)/',
			'class="testimonials-carousel" aria-label="Client testimonials"',
			$content
		);
	}

	return $content;
}

/**
 * Replace hardcoded local / theme URLs in post content on render.
 *
 * @param string $content Post content.
 */
function superb_filter_post_content_urls( $content ) {
	$content = superb_fix_testimonial_aria_roles( $content );
	return superb_normalize_theme_content_urls( $content );
}
add_filter( 'the_content', 'superb_filter_post_content_urls', 8 );

/**
 * Repair doubled theme URLs persisted in post content.
 *
 * @param bool $dry_run Count only.
 * @return int Number of posts updated.
 */
function superb_repair_broken_content_urls( $dry_run = false ) {
	global $wpdb;

	$posts = $wpdb->get_results(
		"SELECT ID, post_content FROM {$wpdb->posts}
		WHERE post_content LIKE '%http://%http://%'
		AND post_status IN ('publish','draft','private')"
	);

	$updated = 0;

	foreach ( $posts as $post ) {
		$new_content = superb_normalize_theme_content_urls( $post->post_content );
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
