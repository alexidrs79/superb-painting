<?php
/**
 * Programmatic Kadence header / theme configuration.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Configure Kadence header: utility bar + logo / nav / CTA main row.
 *
 * @param bool $force Skip version guard and re-apply mods.
 */
function superb_configure_kadence_header( $force = false ) {
	if ( ! $force && get_option( 'superb_header_config_version' ) === SUPERB_THEME_VERSION ) {
		return;
	}

	set_theme_mod(
		'header_desktop_items',
		array(
			'top'    => array(
				'top_left'         => array(),
				'top_left_center'  => array(),
				'top_center'       => array(),
				'top_right_center' => array(),
				'top_right'        => array(),
			),
			'main'   => array(
				'main_left'         => array( 'logo' ),
				'main_left_center'  => array(),
				'main_center'       => array( 'navigation' ),
				'main_right_center' => array(),
				'main_right'        => array( 'button' ),
			),
			'bottom' => array(
				'bottom_left'         => array(),
				'bottom_left_center'  => array(),
				'bottom_center'       => array(),
				'bottom_right_center' => array(),
				'bottom_right'        => array(),
			),
		)
	);

	set_theme_mod(
		'header_mobile_items',
		array(
			'popup'  => array(
				'popup_content' => array( 'mobile-navigation' ),
			),
			'top'    => array(
				'top_left'   => array(),
				'top_center' => array(),
				'top_right'  => array(),
			),
			'main'   => array(
				'main_left'   => array( 'logo' ),
				'main_center' => array(),
				'main_right'  => array( 'popup-toggle' ),
			),
			'bottom' => array(
				'bottom_left'   => array(),
				'bottom_center' => array(),
				'bottom_right'  => array(),
			),
		)
	);

	set_theme_mod(
		'header_main_background',
		array(
			'desktop' => array( 'color' => '#1A1A1A' ),
			'tablet'  => array( 'color' => '#1A1A1A' ),
			'mobile'  => array( 'color' => '#1A1A1A' ),
		)
	);

	set_theme_mod(
		'header_wrap_background',
		array(
			'desktop' => array( 'color' => '#1A1A1A' ),
		)
	);

	set_theme_mod(
		'header_main_height',
		array(
			'size' => array(
				'desktop' => 90,
				'tablet'  => 80,
				'mobile'  => 76,
			),
			'unit' => array(
				'desktop' => 'px',
				'tablet'  => 'px',
				'mobile'  => 'px',
			),
		)
	);

	set_theme_mod(
		'primary_navigation_color',
		array(
			'color'  => '#ffffff',
			'hover'  => '#C9A84C',
			'active' => '#C9A84C',
		)
	);

	set_theme_mod(
		'primary_navigation_spacing',
		array(
			'size' => 1.1,
			'unit' => 'em',
		)
	);

	set_theme_mod( 'header_sticky', 'main' );
	set_theme_mod( 'mobile_header_sticky', 'no' );
	set_theme_mod( 'header_button_label', '' );
	set_theme_mod( 'header_button_link', home_url( '/contact/#quote-form' ) );
	set_theme_mod( 'header_button_style', 'filled' );
	set_theme_mod( 'header_button_size', 'medium' );

	set_theme_mod(
		'header_button_color',
		array(
			'color'           => '#ffffff',
			'hover'           => '#ffffff',
			'background'      => '#C9A84C',
			'backgroundHover' => '#A8873A',
		)
	);

	set_theme_mod( 'page_title', false );
	set_theme_mod( 'page_layout', 'fullwidth' );
	set_theme_mod( 'page_content_style', 'unboxed' );
	set_theme_mod( 'page_vertical_padding', 'hide' );

	set_theme_mod( 'primary_navigation_open_type', 'hover' );
	set_theme_mod( 'mobile_navigation_collapse', true );
	set_theme_mod( 'mobile_navigation_parent_toggle', false );

	set_theme_mod(
		'dropdown_navigation_color',
		array(
			'color'  => '#1A1A1A',
			'hover'  => '#C9A84C',
			'active' => '#C9A84C',
		)
	);

	set_theme_mod(
		'dropdown_navigation_background',
		array(
			'color'  => '#FFFFFF',
			'hover'  => '#EFEFEC',
			'active' => '#EFEFEC',
		)
	);

	set_theme_mod(
		'dropdown_navigation_divider',
		array(
			'width' => 1,
			'unit'  => 'px',
			'style' => 'solid',
			'color' => '#eeeeee',
		)
	);

	set_theme_mod(
		'dropdown_navigation_width',
		array(
			'size' => 100,
			'unit' => '%',
		)
	);

	update_option( 'superb_header_config_version', SUPERB_THEME_VERSION );
}

/**
 * Remove default Kadence header button output (we use custom CTA).
 */
function superb_header_button_setup() {
	remove_action( 'kadence_header_button', 'Kadence\header_button', 10 );
}
add_action( 'after_setup_theme', 'superb_header_button_setup', 25 );

add_action( 'after_setup_theme', 'superb_configure_kadence_header', 30 );
