<?php
/**
 * GTranslate language switcher — header, mobile drawer, and footer.
 *
 * Requires the free "Translate WordPress with GTranslate" plugin.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the GTranslate shortcode is available.
 *
 * @return bool
 */
function superb_gtranslate_is_active() {
	return shortcode_exists( 'gtranslate' ) || shortcode_exists( 'GTranslate' );
}

/**
 * GTranslate shortcode tag (plugin registers lowercase and uppercase variants).
 *
 * @return string
 */
function superb_gtranslate_shortcode() {
	$atts = 'widget_look="dropdown_with_flags"';

	if ( shortcode_exists( 'gtranslate' ) ) {
		return '[gtranslate ' . $atts . ']';
	}
	if ( shortcode_exists( 'GTranslate' ) ) {
		return '[GTranslate ' . $atts . ']';
	}

	return '';
}

/**
 * Keep GTranslate placement in theme slots only (no floating/menu injection).
 *
 * @param mixed $data GTranslate settings array.
 * @return mixed
 */
function superb_gtranslate_force_theme_placement( $data ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}

	$data['show_in_menu']               = '';
	$data['floating_language_selector'] = 'no';
	$data['widget_look']                = 'dropdown_with_flags';
	$data['flag_size']                  = 16;

	// Match Superb dark header/footer chrome.
	$data['switcher_text_color']             = '#FFFFFF';
	$data['switcher_arrow_color']            = '#FFFFFF';
	$data['switcher_border_color']           = '#3D3D3D';
	$data['switcher_background_color']       = '#2A2A2A';
	$data['switcher_background_shadow_color'] = '#2A2A2A';
	$data['switcher_background_hover_color']   = '#353535';
	$data['dropdown_text_color']             = '#FFFFFF';
	$data['dropdown_hover_color']              = '#3A3A3A';
	$data['dropdown_background_color']         = '#2D2D2D';

	return $data;
}
add_filter( 'option_GTranslate', 'superb_gtranslate_force_theme_placement' );

/**
 * Apply wider header nav on first paint when a non-English GTranslate cookie is set.
 */
function superb_header_nav_expanded_early_class() {
	if ( empty( $_COOKIE['googtrans'] ) ) {
		return;
	}

	$parts  = explode( '/', sanitize_text_field( wp_unslash( $_COOKIE['googtrans'] ) ) );
	$target = isset( $parts[2] ) ? $parts[2] : '';

	if ( $target && 'en' !== $target ) {
		echo "<script>document.documentElement.classList.add('superb-header-nav-expanded');</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'superb_header_nav_expanded_early_class', 1 );

/**
 * Render the language switcher when GTranslate is active.
 *
 * @param string $context Placement key: header, mobile, footer.
 */
function superb_gtranslate_switcher( $context = 'header' ) {
	if ( ! superb_gtranslate_is_active() ) {
		return;
	}

	$shortcode = superb_gtranslate_shortcode();
	if ( '' === $shortcode ) {
		return;
	}

	$allowed = array( 'header', 'mobile', 'footer' );
	if ( ! in_array( $context, $allowed, true ) ) {
		$context = 'header';
	}

	if ( in_array( $context, array( 'mobile', 'footer' ), true ) ) {
		echo '<p class="superb-gtranslate__label">' . esc_html__( 'Language', 'kadence-child-superb' ) . '</p>';
	}

	printf(
		'<div class="superb-gtranslate superb-gtranslate--%1$s" data-superb-gtranslate="%1$s" aria-label="%2$s">%3$s</div>',
		esc_attr( $context ),
		esc_attr__( 'Select language', 'kadence-child-superb' ),
		do_shortcode( $shortcode ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode HTML from GTranslate plugin.
	);
}

/**
 * Remind admins to install GTranslate when missing (dashboard only).
 */
function superb_gtranslate_admin_notice() {
	if ( ! is_admin() || ! current_user_can( 'install_plugins' ) || superb_gtranslate_is_active() ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'themes' !== $screen->id ) {
		return;
	}

	echo '<div class="notice notice-info"><p>'
		. esc_html__( 'Superb Painting: install and activate GTranslate to show the language switcher in the header and footer.', 'kadence-child-superb' )
		. ' <a href="' . esc_url( admin_url( 'plugin-install.php?s=gtranslate&tab=search&type=term' ) ) . '">'
		. esc_html__( 'Install GTranslate', 'kadence-child-superb' )
		. '</a></p></div>';
}
add_action( 'admin_notices', 'superb_gtranslate_admin_notice' );
