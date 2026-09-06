<?php
/**
 * SEO — meta descriptions, Open Graph, schema markup.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default site meta description.
 */
function superb_default_meta_description() {
	return 'Professional interior and exterior painting across Melbourne. Free quotes within 24 hours. Master Painters members — Dulux accredited — 25+ years experience.';
}

/**
 * Meta description for the current request.
 *
 * @return string
 */
function superb_get_meta_description() {
	if ( is_singular() ) {
		$excerpt = get_the_excerpt();
		if ( $excerpt ) {
			return superb_trim_meta_description( $excerpt );
		}

		if ( is_singular( 'service' ) && function_exists( 'get_field' ) ) {
			$tagline = get_field( 'service_tagline' );
			if ( $tagline ) {
				return superb_trim_meta_description( $tagline );
			}
		}

		if ( is_singular( 'suburb' ) ) {
			return superb_trim_meta_description(
				sprintf(
					'Interior and exterior painting in %s, Melbourne. Free on-site quotes from professional experienced painters.',
					get_the_title()
				)
			);
		}
	}

	if ( is_front_page() ) {
		return superb_default_meta_description();
	}

	$page_descriptions = array(
		'about'     => 'Meet the Superb Painting team — Melbourne interior and exterior painters with 25+ years experience, Master Painters members and Dulux accredited.',
		'services'  => 'Interior and exterior painting services in Melbourne — wall painting, kitchen TwoPak, special finishes, apartments, new builds and commercial.',
		'suburbs'   => 'Superb Painting serves 50+ Melbourne suburbs. Find your local interior and exterior painters with free on-site quotes.',
		'gallery'   => 'Browse recent interior and exterior painting projects across Melbourne — homes, apartments, kitchens and commercial spaces.',
		'contact'   => 'Request a free painting quote from Superb Painting. Call 0400 164 985 or email info@superbpainting.com — we respond within 24 hours.',
		'faq'       => 'Answers to common questions about Melbourne painting quotes, pricing, prep work, licensing and project timelines from Superb Painting.',
		'thank-you' => 'Thank you for your quote request. Superb Painting will respond within 24 hours with your free on-site quote.',
	);

	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( isset( $page_descriptions[ $slug ] ) ) {
			return $page_descriptions[ $slug ];
		}
	}

	$tagline = get_bloginfo( 'description', 'display' );
	if ( $tagline ) {
		return superb_trim_meta_description( $tagline );
	}

	return superb_default_meta_description();
}

/**
 * Trim meta description to a sensible length.
 *
 * @param string $text Copy.
 * @param int    $max  Character limit.
 * @return string
 */
function superb_trim_meta_description( $text, $max = 160 ) {
	$text = wp_strip_all_tags( $text );
	$text = preg_replace( '/\s+/u', ' ', trim( $text ) );

	if ( mb_strlen( $text ) <= $max ) {
		return $text;
	}

	return rtrim( mb_substr( $text, 0, $max - 1 ), ".,;:!? " ) . '…';
}

/**
 * Descriptive CTA label for service links (PageSpeed / a11y).
 *
 * @param string $service_name Service title.
 * @return string
 */
function superb_service_link_label( $service_name ) {
	return sprintf(
		/* translators: %s: painting service name */
		__( 'Learn more about %s', 'kadence-child-superb' ),
		$service_name
	);
}

/**
 * LocalBusiness + WebSite JSON-LD.
 */
function superb_output_schema() {
	if ( is_admin() ) {
		return;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type'            => 'PaintingContractor',
				'@id'              => home_url( '/#organization' ),
				'name'             => 'Superb Painting',
				'url'              => home_url( '/' ),
				'telephone'        => SUPERB_PHONE,
				'email'            => SUPERB_EMAIL,
				'image'            => SUPERB_THEME_URI . '/assets/images/logo.png',
				'description'      => 'Professional interior painting for homes, apartments and commercial spaces across Melbourne.',
				'areaServed'       => array(
					'@type' => 'City',
					'name'  => 'Melbourne',
				),
				'address'          => array(
					'@type'           => 'PostalAddress',
					'addressLocality' => 'Melbourne',
					'addressRegion'   => 'VIC',
					'addressCountry'  => 'AU',
				),
				'priceRange'       => '$$',
				'openingHoursSpecification' => array(
					array(
						'@type'     => 'OpeningHoursSpecification',
						'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
						'opens'     => '07:00',
						'closes'    => '19:00',
					),
				),
			),
			array(
				'@type'     => 'WebSite',
				'@id'       => home_url( '/#website' ),
				'url'       => home_url( '/' ),
				'name'      => get_bloginfo( 'name' ),
				'publisher' => array( '@id' => home_url( '/#organization' ) ),
			),
		),
	);

	if ( is_singular( 'service' ) ) {
		$schema['@graph'][] = array(
			'@type'       => 'Service',
			'name'        => get_the_title(),
			'description' => wp_strip_all_tags( get_the_excerpt() ?: get_the_title() ),
			'provider'    => array( '@id' => home_url( '/#organization' ) ),
			'areaServed'  => 'Melbourne',
			'url'         => get_permalink(),
		);
	}

	if ( is_page( 'faq' ) ) {
		$faq_items = array(
			array( 'How quickly can I get a quote?', 'We respond within 24 hours and typically visit within 1–2 business days for a free on-site quote.' ),
			array( 'Is the quote fixed price?', 'Yes. The written quote is the price you pay unless the agreed scope changes.' ),
			array( 'Are you licensed and insured?', 'Professional experienced painters — Master Painters members with $20M public liability insurance.' ),
		);
		$entities  = array();
		foreach ( $faq_items as $item ) {
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => $item[0],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $item[1],
				),
			);
		}
		$schema['@graph'][] = array(
			'@type'      => 'FAQPage',
			'mainEntity' => $entities,
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'superb_output_schema', 5 );

/**
 * Basic Open Graph + theme color.
 */
function superb_output_meta_tags() {
	if ( is_admin() ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = superb_get_meta_description();
	$image       = function_exists( 'superb_get_hero_image_url' ) ? superb_get_hero_image_url() : SUPERB_THEME_URI . '/assets/images/hero-bg.jpg';
	$url         = is_singular() ? get_permalink() : home_url( '/' );

	if ( has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( null, 'hero-bg' ) ?: $image;
	}

	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta name="theme-color" content="#1A1A1A">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}
add_action( 'wp_head', 'superb_output_meta_tags', 6 );

/**
 * Ensure the header logo link has an accessible name.
 *
 * @param string $html Logo markup.
 * @return string
 */
function superb_accessible_custom_logo( $html ) {
	if ( ! $html || false !== strpos( $html, 'aria-label=' ) ) {
		return $html;
	}

	$label = sprintf(
		/* translators: %s: site name */
		__( '%s home', 'kadence-child-superb' ),
		get_bloginfo( 'name', 'display' )
	);

	return preg_replace(
		'/<a\s+/',
		'<a aria-label="' . esc_attr( $label ) . '" ',
		$html,
		1
	);
}
add_filter( 'get_custom_logo', 'superb_accessible_custom_logo' );
