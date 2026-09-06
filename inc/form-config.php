<?php
/**
 * Quote form mail routing — CC notification addresses.
 *
 * CC emails are managed in Settings → Quote Form Emails unless
 * SUPERB_FORM_CC_EMAILS is defined in functions.php (code override).
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

define( 'SUPERB_FORM_CC_OPTION', 'superb_form_cc_emails' );

/**
 * Default CC addresses (used when the option has never been saved).
 *
 * @return string[]
 */
function superb_get_form_cc_email_defaults() {
	return array(
		'superbpainting2026@gmail.com',
		'koranj.1987@gmail.com',
	);
}

/**
 * Sanitized CC addresses for quote form notifications.
 *
 * @return string[]
 */
function superb_get_form_cc_emails() {
	if ( defined( 'SUPERB_FORM_CC_EMAILS' ) && is_array( SUPERB_FORM_CC_EMAILS ) ) {
		return array_values(
			array_unique(
				array_filter(
					array_map( 'sanitize_email', SUPERB_FORM_CC_EMAILS )
				)
			)
		);
	}

	$stored = get_option( SUPERB_FORM_CC_OPTION, false );

	if ( false === $stored ) {
		$stored = superb_get_form_cc_email_defaults();
	}

	if ( ! is_array( $stored ) ) {
		$stored = array();
	}

	return array_values(
		array_unique(
			array_filter(
				array_map( 'sanitize_email', $stored )
			)
		)
	);
}

/**
 * Whether CC emails are locked by a PHP constant in functions.php.
 *
 * @return bool
 */
function superb_form_cc_emails_are_locked() {
	return defined( 'SUPERB_FORM_CC_EMAILS' ) && is_array( SUPERB_FORM_CC_EMAILS );
}

if ( is_admin() ) {
	require_once __DIR__ . '/form-settings.php';
}
