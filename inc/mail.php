<?php
/**
 * Mail delivery helpers — SMTP timeouts and LiteSpeed exclusions for CF7 + FluentSMTP.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

if ( defined( 'SUPERB_MAIL_HELPERS_LOADED' ) ) {
	return;
}
define( 'SUPERB_MAIL_HELPERS_LOADED', true );

/**
 * Fail SMTP quickly instead of hanging REST/AJAX for 60+ seconds.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer Mailer instance.
 */
function superb_mail_phpmailer_timeout( $phpmailer ) {
	$phpmailer->Timeout       = 15;
	$phpmailer->SMTPKeepAlive = false;
}
add_action( 'phpmailer_init', 'superb_mail_phpmailer_timeout', 1 );

/**
 * @param string[] $excludes LiteSpeed JS excludes.
 * @return string[]
 */
function superb_litespeed_js_excludes( $excludes ) {
	$add = array(
		'contact-form-7',
		'wpcf7',
		'google-recaptcha',
		'recaptcha',
		'grecaptcha',
		'fluent-smtp',
		'fluentsmtp',
		'gtranslate',
		'gt_switcher',
		'gt_translate',
		'superb-interactions',
		'superb-site-enhancements',
		'wp-json/contact-form-7',
	);

	return array_merge( (array) $excludes, $add );
}
add_filter( 'litespeed_optimize_js_excludes', 'superb_litespeed_js_excludes' );
add_filter( 'litespeed_optm_js_defer_exc', 'superb_litespeed_js_excludes' );
add_filter( 'litespeed_optm_gm_js_exc', 'superb_litespeed_js_excludes' );

/**
 * Never cache Contact Form 7 REST submissions.
 *
 * @param mixed           $result  Response.
 * @param WP_REST_Server  $server  Server.
 * @param WP_REST_Request $request Request.
 * @return mixed
 */
function superb_cf7_rest_nocache( $result, $server, $request ) {
	if ( $request instanceof WP_REST_Request && false !== strpos( $request->get_route(), 'contact-form-7' ) && ! headers_sent() ) {
		header( 'X-LiteSpeed-Cache-Control: no-cache' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	}

	return $result;
}
add_filter( 'rest_pre_dispatch', 'superb_cf7_rest_nocache', 0, 3 );
