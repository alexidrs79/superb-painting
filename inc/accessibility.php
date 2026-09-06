<?php
/**
 * Accessibility fixes — skip link, main landmark, CF7 labels.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * CF7 field labels for explicit label[for] association.
 *
 * @return array<string, array<int, string>>
 */
function superb_cf7_field_labels() {
	return array(
		'your-name'     => array( 'Full Name' ),
		'your-phone'    => array( 'Phone', 'Phone Number' ),
		'your-email'    => array( 'Email', 'Email Address' ),
		'your-suburb'   => array( 'Suburb', 'Property Suburb' ),
		'job-type'      => array( 'Type of Job' ),
		'your-rooms'    => array( 'Number of Rooms' ),
		'property-type' => array( 'Property Type' ),
		'your-service'  => array( 'Service Required' ),
		'your-message'  => array( 'Brief Description', 'Tell Us About Your Project', 'About Your Project' ),
		'your-photos'   => array( 'Upload Photos of Your Space' ),
	);
}

/**
 * Ensure CF7 controls have ids and matching label[for] attributes.
 *
 * @param string $html Form HTML.
 * @return string
 */
function superb_cf7_ensure_labeled_fields( $html ) {
	foreach ( superb_cf7_field_labels() as $name => $labels ) {
		$wrap_pattern = '/(<span class="wpcf7-form-control-wrap[^"]*" data-name="' . preg_quote( $name, '/' ) . '"[^>]*>)\s*(<(?:select|input|textarea)\b)([^>]*)(>)/is';

		$html = preg_replace_callback(
			$wrap_pattern,
			function ( $matches ) use ( $name ) {
				$attrs = $matches[3];
				if ( ! preg_match( '/\bid=/i', $attrs ) ) {
					$attrs .= ' id="' . esc_attr( $name ) . '"';
				}
				return $matches[1] . $matches[2] . $attrs . $matches[4];
			},
			$html,
			1
		);

		foreach ( $labels as $label ) {
			$label_pattern = preg_quote( $label, '/' );

			$html = preg_replace(
				'/(<label(?![^>]*\bfor=)[^>]*>)\s*' . $label_pattern . '(\s*\*?)(\s*<\/label>)\s*(<span class="wpcf7-form-control-wrap[^"]*" data-name="' . preg_quote( $name, '/' ) . '")/is',
				'<label for="' . esc_attr( $name ) . '">' . $label . '$2</label>' . "\n" . '$4',
				$html,
				1
			);

			$html = preg_replace(
				'/(<label(?![^>]*\bfor=)[^>]*>)\s*' . $label_pattern . '(\s*\*?)(\s*<\/label>)/is',
				'<label for="' . esc_attr( $name ) . '">' . $label . '$2</label>',
				$html,
				1
			);
		}
	}

	return $html;
}
add_filter( 'wpcf7_form_elements', 'superb_cf7_ensure_labeled_fields', 20 );
