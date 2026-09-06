<?php
/**
 * CLI script to generate service page HTML files.
 * Run: php inc/generate-services.php
 */

define( 'SUPERB_THEME_CLI', true );
define( 'SUPERB_THEME_DIR', dirname( __DIR__ ) );
define( 'SUPERB_THEME_URI', '/wp-content/themes/kadence-child-superb' );

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', SUPERB_THEME_DIR . '/' );
}

if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}
if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $url ) {
		return filter_var( $url, FILTER_SANITIZE_URL );
	}
}

require_once SUPERB_THEME_DIR . '/inc/images.php';
require_once SUPERB_THEME_DIR . '/inc/service-data.php';
require_once SUPERB_THEME_DIR . '/inc/service-content.php';

foreach ( superb_get_service_definitions() as $slug => $data ) {
	$data['slug'] = $slug;
	$markup       = superb_get_service_page_markup( $data );
	file_put_contents( SUPERB_THEME_DIR . '/content/services/' . $slug . '.html', $markup );
	echo "Created services/{$slug}.html\n";
}
