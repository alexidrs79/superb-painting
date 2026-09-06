<?php
/**
 * Site content guardrails — verify marketing pages, CPT templates, and CF7 stay in sync.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * PHP template expectations (source files, not DB).
 *
 * @return array<string, array{forbidden: array<int, string>, required: array<int, string>}>
 */
function superb_get_template_expectations() {
	return array(
		'single-suburb.php' => array(
			'forbidden' => array( 'Clients Say', 'testimonial-card' ),
			'required'  => array( 'superb_quote_form variant="sidebar"', 'Opening Hours' ),
		),
	);
}

/**
 * Per-URL HTML expectations for rendered pages.
 *
 * @return array<string, array{label: string, required: array<int, string>, forbidden: array<int, string>}>
 */
function superb_get_page_expectations() {
	return array(
		'/' => array(
			'label'     => 'Home',
			'required'  => array( 'hero-split', 'services-grid', 'why-choose-us', 'our-process', 'testimonials', 'quote-form', 'service-card' ),
			'forbidden' => array(),
		),
		'/about/' => array(
			'label'     => 'About',
			'required'  => array( 'about-story', 'about-values', 'about-team', 'page-hero' ),
			'forbidden' => array(),
		),
		'/services/' => array(
			'label'     => 'Services',
			'required'  => array( 'service-card', 'page-hero', 'services-grid--listing' ),
			'forbidden' => array(),
		),
		'/suburbs/' => array(
			'label'     => 'Suburbs',
			'required'  => array( 'suburb-card', 'page-hero' ),
			'forbidden' => array(),
		),
		'/contact/' => array(
			'label'     => 'Contact',
			'required'  => array(
				'superb-map-embed',
				'<iframe',
				'superb-sidebar',
				'Prefer to Talk',
				'superb-quote-form--contact',
				'name="property-type"',
				'Submit My Quote Request',
			),
			'forbidden' => array(),
		),
		'/faq/' => array(
			'label'     => 'FAQ',
			'required'  => array( 'faq-item', 'page-hero' ),
			'forbidden' => array(),
		),
		'/thank-you/' => array(
			'label'     => 'Thank You',
			'required'  => array( 'thank-you', 'page-hero' ),
			'forbidden' => array(),
		),
		'/gallery/' => array(
			'label'     => 'Gallery',
			'required'  => array( 'gallery-page', 'gallery-filters', 'gallery-masonry' ),
			'forbidden' => array(),
		),
	);
}

/**
 * Fetch rendered HTML for a path.
 *
 * @param string $path Site path, e.g. /contact/.
 * @return string|WP_Error
 */
function superb_audit_fetch_html( $path ) {
	$url      = home_url( $path );
	$response = wp_remote_get(
		$url,
		array(
			'timeout'   => 20,
			'sslverify' => false,
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 400 ) {
		return new WP_Error( 'superb_audit_http', 'HTTP ' . $code . ' for ' . $url );
	}

	return wp_remote_retrieve_body( $response );
}

/**
 * Run string expectation checks against HTML.
 *
 * @param string $html       Page HTML.
 * @param array  $required   Substrings that must exist.
 * @param array  $forbidden  Substrings that must not exist.
 * @return array<int, array{check: string, pass: bool, detail: string}>
 */
function superb_audit_check_strings( $html, $required, $forbidden ) {
	$results = array();

	foreach ( $required as $needle ) {
		$pass      = ( false !== stripos( $html, $needle ) );
		$results[] = array(
			'check'  => 'required:' . $needle,
			'pass'   => $pass,
			'detail' => $pass ? 'Found' : 'Missing',
		);
	}

	foreach ( $forbidden as $needle ) {
		$pass      = ( false === stripos( $html, $needle ) );
		$results[] = array(
			'check'  => 'forbidden:' . $needle,
			'pass'   => $pass,
			'detail' => $pass ? 'Absent (good)' : 'Present (should be removed)',
		);
	}

	return $results;
}

/**
 * Audit PHP template source files.
 *
 * @return array<int, array<string, mixed>>
 */
function superb_audit_templates() {
	$out          = array();
	$expectations = superb_get_template_expectations();

	foreach ( $expectations as $file => $rules ) {
		$path = SUPERB_THEME_DIR . '/' . $file;
		if ( ! is_readable( $path ) ) {
			$out[] = array(
				'template' => $file,
				'pass'     => false,
				'checks'   => array(
					array(
						'check'  => 'file_exists',
						'pass'   => false,
						'detail' => 'Template file missing',
					),
				),
			);
			continue;
		}

		$source = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$checks = superb_audit_check_strings(
			$source,
			$rules['required'] ?? array(),
			$rules['forbidden'] ?? array()
		);
		$pass   = ! in_array( false, wp_list_pluck( $checks, 'pass' ), true );

		$out[] = array(
			'template' => $file,
			'pass'     => $pass,
			'checks'   => $checks,
		);
	}

	return $out;
}

/**
 * Audit sync state (patterns, CF7).
 *
 * @return array<string, mixed>
 */
function superb_audit_sync_state() {
	$checks = array();

	$cf7_ok = ! function_exists( 'superb_cf7_forms_need_sync' ) || ! superb_cf7_forms_need_sync();
	$checks[] = array(
		'check'  => 'cf7_forms_version',
		'pass'   => $cf7_ok,
		'detail' => $cf7_ok ? SUPERB_CF7_FORMS_VERSION : 'CF7 forms out of sync — reload site or bump SUPERB_CF7_FORMS_VERSION',
	);

	$pattern_stale = function_exists( 'superb_marketing_patterns_need_sync' ) && superb_marketing_patterns_need_sync();
	$checks[]      = array(
		'check'  => 'marketing_patterns',
		'pass'   => ! $pattern_stale,
		'detail' => $pattern_stale ? 'Pattern files newer than last sync — visit ?superb_sync_pages=1' : 'Marketing pages in sync',
	);

	$full_id = (int) get_option( 'superb_cf7_full_id' );
	$checks[] = array(
		'check'  => 'cf7_full_form_id',
		'pass'   => $full_id > 0,
		'detail' => $full_id ? 'ID ' . $full_id : 'Superb Full Quote form ID missing',
	);

	return array(
		'pass'   => ! in_array( false, wp_list_pluck( $checks, 'pass' ), true ),
		'checks' => $checks,
	);
}

/**
 * Audit all marketing pages + sample CPT singles.
 *
 * @return array<string, mixed>
 */
function superb_run_site_audit() {
	$pages   = superb_get_page_expectations();
	$results = array();

	// Sample CPT singles — one of each type.
	$sample_service = get_posts(
		array(
			'post_type'      => 'service',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);
	if ( $sample_service ) {
		$pages[ '/services/' . $sample_service[0]->post_name . '/' ] = array(
			'label'     => 'Service single (' . $sample_service[0]->post_title . ')',
			'required'  => array( 'superb-sidebar', 'superb-quote-form', 'page-hero' ),
			'forbidden' => array(),
		);
	}

	$sample_suburb = get_posts(
		array(
			'post_type'      => 'suburb',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	if ( $sample_suburb ) {
		$pages[ '/suburbs/' . $sample_suburb[0]->post_name . '/' ] = array(
			'label'     => 'Suburb single (' . $sample_suburb[0]->post_title . ')',
			'required'  => array( 'superb-sidebar', 'superb-quote-form', 'Services We Offer', 'Opening Hours' ),
			'forbidden' => array( 'Clients Say', 'testimonial-card' ),
		);
	}

	foreach ( $pages as $path => $expect ) {
		$html = superb_audit_fetch_html( $path );

		if ( is_wp_error( $html ) || '' === $html ) {
			$results[] = array(
				'path'  => $path,
				'label' => $expect['label'],
				'pass'  => false,
				'checks' => array(
					array(
						'check'  => 'fetch',
						'pass'   => false,
						'detail' => is_wp_error( $html ) ? $html->get_error_message() : 'Empty response',
					),
				),
			);
			continue;
		}

		$checks = superb_audit_check_strings(
			$html,
			$expect['required'] ?? array(),
			$expect['forbidden'] ?? array()
		);
		$pass   = ! in_array( false, wp_list_pluck( $checks, 'pass' ), true );

		$results[] = array(
			'path'   => $path,
			'label'  => $expect['label'],
			'pass'   => $pass,
			'checks' => $checks,
		);
	}

	$templates  = superb_audit_templates();
	$sync       = superb_audit_sync_state();
	$page_pass  = ! in_array( false, wp_list_pluck( $results, 'pass' ), true );
	$tmpl_pass  = ! in_array( false, wp_list_pluck( $templates, 'pass' ), true );
	$all_pass   = $page_pass && $tmpl_pass && $sync['pass'];

	return array(
		'ok'        => $all_pass,
		'timestamp' => gmdate( 'c' ),
		'sync'      => $sync,
		'templates' => $templates,
		'pages'     => $results,
		'fixes'     => superb_audit_fix_hints( $sync, $results, $templates ),
	);
}

/**
 * Actionable fix hints when audit fails.
 *
 * @param array $sync      Sync audit block.
 * @param array $pages     Page audit results.
 * @param array $templates Template audit results.
 * @return array<int, string>
 */
function superb_audit_fix_hints( $sync, $pages, $templates ) {
	$hints = array();

	if ( ! $sync['pass'] ) {
		$hints[] = 'Visit ' . home_url( '/?superb_sync_pages=1' ) . ' to re-sync marketing pages from patterns.';
		$hints[] = 'Reload any page once to auto-sync CF7 forms, or delete option superb_cf7_forms_version in wp_options.';
	}

	foreach ( $pages as $page ) {
		if ( $page['pass'] ) {
			continue;
		}
		foreach ( $page['checks'] as $check ) {
			if ( $check['pass'] ) {
				continue;
			}
			if ( 0 === strpos( $check['check'], 'forbidden:' ) ) {
				$hints[] = $page['label'] . ': remove "' . substr( $check['check'], 10 ) . '" from theme template or page content.';
			}
		}
	}

	foreach ( $templates as $tmpl ) {
		if ( ! $tmpl['pass'] ) {
			$hints[] = 'Fix theme template ' . $tmpl['template'] . ' — see audit checks.';
		}
	}

	return array_values( array_unique( $hints ) );
}
