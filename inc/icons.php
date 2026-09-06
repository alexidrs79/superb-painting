<?php
/**
 * Lucide-style SVG icon library for Superb Painting.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Icon path definitions (Lucide icons, 24×24).
 *
 * @return array<string, string>
 */
function superb_icon_paths() {
	return array(
		'phone'         => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'mail'          => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'map-pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'clock'         => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'shield'        => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>',
		'shield-check'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/>',
		'check'         => '<path d="M20 6 9 17l-5-5"/>',
		'check-circle'  => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
		'paintbrush'    => '<path d="m14.5 18.5 3-3L19 14l-3 3"/><path d="M9 11l3 3"/><path d="M6 8l7 7"/><path d="M2 22 8 16"/>',
		'palette'       => '<circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/>',
		'paint-roller'  => '<rect width="16" height="6" x="2" y="2" rx="2"/><path d="M10 16v-2a2 2 0 0 1 2-2h8a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect width="4" height="6" x="8" y="16" rx="1"/>',
		'timer'         => '<line x1="10" x2="14" y1="2" y2="2"/><line x1="12" x2="15" y1="14" y2="11"/><circle cx="12" cy="14" r="8"/>',
		'lock'          => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
		'star'          => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
		'award'         => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
		'file-text'     => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
		'home'          => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
		'building'      => '<rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/>',
		'building-2'    => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
		'layout-grid'   => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
		'sparkles'      => '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>',
		'hammer'        => '<path d="m15 12-8.373 8.373a1 1 0 1 1-3-3L12 9"/><path d="m18 15 4-4"/><path d="m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172V7l-2.26-2.26a6 6 0 0 0-4.202-1.756L9 2.96l.92.82A6.18 6.18 0 0 1 12 8.4V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5"/>',
		'briefcase'     => '<path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>',
		'heart-handshake' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M12 5 9.04 7.96a2.17 2.17 0 0 0 0 3.08v0c.82.82 2.13.85 3 .07l2.07-1.9a2.82 2.82 0 0 1 3.79 0l1.12 1.02"/><path d="m18 15-2-2"/><path d="m15 18-2-2"/>',
		'zap'           => '<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>',
		'facebook'      => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'instagram'     => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>',
	);
}

/**
 * Render a Lucide-style SVG icon.
 *
 * @param string $name Icon name.
 * @param array  $args size, class, filled (bool).
 */
function superb_icon( $name, $args = array() ) {
	$paths = superb_icon_paths();
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$defaults = array(
		'size'   => 24,
		'class'  => '',
		'filled' => in_array( $name, array( 'star', 'facebook' ), true ),
	);
	$args     = wp_parse_args( $args, $defaults );
	$size     = (int) $args['size'];
	$class    = trim( 'superb-icon superb-icon--' . sanitize_html_class( $name ) . ' ' . $args['class'] );
	$filled   = (bool) $args['filled'];

	if ( $filled ) {
		return sprintf(
			'<svg class="%s" width="%d" height="%d" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">%s</svg>',
			esc_attr( $class ),
			$size,
			$size,
			$paths[ $name ]
		);
	}

	return sprintf(
		'<svg class="%s" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%s</svg>',
		esc_attr( $class ),
		$size,
		$size,
		$paths[ $name ]
	);
}

/**
 * Star rating row markup.
 *
 * @param int $count Number of stars.
 * @param int $size  Icon size.
 */
function superb_icon_stars( $count = 5, $size = 16 ) {
	$count = max( 1, min( 5, (int) $count ) );
	$html  = '<span class="superb-stars" role="img" aria-label="' . esc_attr( $count ) . ' out of 5 stars">';
	for ( $i = 0; $i < $count; $i++ ) {
		$html .= superb_icon( 'star', array( 'size' => $size, 'class' => 'superb-star' ) );
	}
	$html .= '</span>';
	return $html;
}

/**
 * Contact details list with icons.
 */
function superb_contact_details_markup() {
	ob_start();
	?>
	<ul class="superb-icon-list">
		<li><?php echo superb_icon( 'phone', array( 'size' => 18 ) ); ?> <a href="<?php echo esc_url( SUPERB_PHONE_LINK ); ?>"><?php echo esc_html( SUPERB_PHONE ); ?></a></li>
		<li><?php echo superb_icon( 'mail', array( 'size' => 18 ) ); ?> <a href="mailto:<?php echo esc_attr( SUPERB_EMAIL ); ?>"><?php echo esc_html( SUPERB_EMAIL ); ?></a></li>
		<li><?php echo superb_icon( 'map-pin', array( 'size' => 18 ) ); ?> Melbourne, Victoria, Australia</li>
		<li><?php echo superb_icon( 'clock', array( 'size' => 18 ) ); ?> 7 days: Mon–Sun 7am–7pm</li>
	</ul>
	<?php
	return ob_get_clean();
}

/**
 * Trust badge icon markup.
 *
 * @param string $type shield|insured|paint|response.
 */
function superb_trust_icon( $type ) {
	$map = array(
		'shield'   => 'shield-check',
		'insured'  => 'check-circle',
		'paint'    => 'palette',
		'response' => 'timer',
	);
	$name = isset( $map[ $type ] ) ? $map[ $type ] : 'check';
	return superb_icon( $name, array( 'size' => 32, 'class' => 'trust-badge__svg' ) );
}

/**
 * Inline icon with label (hero badges, reassurance items).
 *
 * @param string $icon Icon name.
 * @param string $label Label text.
 * @param int    $size Icon size.
 */
function superb_icon_label( $icon, $label, $size = 16 ) {
	return '<span class="superb-icon-label">' . superb_icon( $icon, array( 'size' => $size ) ) . ' <span>' . esc_html( $label ) . '</span></span>';
}

/**
 * Service card title → icon map.
 *
 * @return array<string, string>
 */
function superb_service_icon_map() {
	return array(
		'Interior & Exterior Painting'        => 'paint-roller',
		'Interior & Exterior Wall Painting'   => 'paint-roller',
		'Kitchen Cabinet, Doors, Metal TwoPak Painting' => 'layout-grid',
		'Kitchen Cabinet Painting' => 'layout-grid',
		'Special Finishes'         => 'sparkles',
		'Feature Walls'            => 'sparkles',
		'Apartment Painting'       => 'building-2',
		'New Build & Renovation'   => 'hammer',
		'Commercial Interior & Exterior'      => 'briefcase',
	);
}

/**
 * Feature block title → icon map.
 *
 * @return array<string, string>
 */
function superb_feature_icon_map() {
	return array(
		'Master Painters & Dulux Accredited'  => 'award',
		'VBA Licensed & Fully Insured'        => 'shield-check',
		'Free On-Site Quotes Within 24 Hours' => 'clock',
		'Premium Paints & Materials Only'     => 'palette',
		'We Treat Your Home With Respect'     => 'heart-handshake',
	);
}

/**
 * Restore icons stripped by WordPress when saving HTML blocks.
 *
 * @param string $content Post content.
 */
function superb_restore_stripped_icons( $content ) {
	if ( false === strpos( $content, 'service-card__icon' )
		&& false === strpos( $content, 'feature-block__icon' )
		&& false === strpos( $content, 'hero-split__badge' )
		&& false === strpos( $content, 'testimonial-card__stars' ) ) {
		return $content;
	}

	$content = (string) preg_replace_callback(
		'/<div class="service-card__icon">\s*<\/div>\s*<h3>(.*?)<\/h3>/',
		function ( $matches ) {
			$title = trim( html_entity_decode( wp_strip_all_tags( $matches[1] ), ENT_QUOTES, 'UTF-8' ) );
			$icon  = superb_service_icon_map()[ $title ] ?? 'paintbrush';
			return '<div class="service-card__icon">' . superb_icon( $icon, array( 'size' => 28 ) ) . '</div><h3>' . $matches[1] . '</h3>';
		},
		$content
	);

	$content = (string) preg_replace_callback(
		'/<div class="feature-block__icon">\s*<\/div>\s*<h3>(.*?)<\/h3>/',
		function ( $matches ) {
			$title = trim( html_entity_decode( wp_strip_all_tags( $matches[1] ), ENT_QUOTES, 'UTF-8' ) );
			$icon  = superb_feature_icon_map()[ $title ] ?? 'check-circle';
			return '<div class="feature-block__icon">' . superb_icon( $icon, array( 'size' => 24 ) ) . '</div><h3>' . $matches[1] . '</h3>';
		},
		$content
	);

	$badge_icons = array(
		'Master Painters Member' => 'award',
		'Dulux Accredited'       => 'palette',
		'VBA Licensed'           => 'shield-check',
		'$20M Insured'           => 'shield',
		'Free Quotes'            => 'file-text',
	);
	$content = (string) preg_replace_callback(
		'/<span class="hero-split__badge">\s*([^<]+?)<\/span>/',
		function ( $matches ) use ( $badge_icons ) {
			$label = trim( html_entity_decode( $matches[1], ENT_QUOTES, 'UTF-8' ) );
			if ( isset( $badge_icons[ $label ] ) && false === strpos( $matches[0], 'superb-icon' ) ) {
				return '<span class="hero-split__badge">' . superb_icon( $badge_icons[ $label ], array( 'size' => 16 ) ) . ' ' . esc_html( $label ) . '</span>';
			}
			return $matches[0];
		},
		$content
	);

	$stars = superb_icon_stars( 5 );
	$content = str_replace( '<div class="testimonial-card__stars"></div>', '<div class="testimonial-card__stars">' . $stars . '</div>', $content );

	return $content;
}

/**
 * Process shortcodes inside core/html blocks (patterns saved to pages).
 *
 * @param string $block_content Block HTML.
 * @param array  $block         Block data.
 */
function superb_html_block_shortcodes( $block_content, $block ) {
	if ( isset( $block['blockName'] ) && 'core/html' === $block['blockName'] ) {
		return do_shortcode( $block_content );
	}
	return $block_content;
}
add_filter( 'render_block', 'superb_html_block_shortcodes', 9, 2 );

/**
 * Replace legacy emoji / unicode icons in post content.
 *
 * @param string $content Post content.
 */
function superb_icons_in_content( $content ) {
	if ( empty( $content ) || ! is_string( $content ) ) {
		return $content;
	}

	$stars = superb_icon_stars( 5 );
	$content = str_replace( '<div class="testimonial-card__stars">★★★★★</div>', '<div class="testimonial-card__stars">' . $stars . '</div>', $content );
	$content = str_replace( '★★★★★', $stars, $content );

	$replacements = array(
		'<p>📞 ' => '<p class="superb-icon-line">' . superb_icon( 'phone', array( 'size' => 18, 'class' => 'superb-icon-inline' ) ) . ' ',
		'<p>✉ '  => '<p class="superb-icon-line">' . superb_icon( 'mail', array( 'size' => 18, 'class' => 'superb-icon-inline' ) ) . ' ',
		'<p>📍 ' => '<p class="superb-icon-line">' . superb_icon( 'map-pin', array( 'size' => 18, 'class' => 'superb-icon-inline' ) ) . ' ',
		'<p>⏰ ' => '<p class="superb-icon-line">' . superb_icon( 'clock', array( 'size' => 18, 'class' => 'superb-icon-inline' ) ) . ' ',
		'<span>✓ ' => '<span class="superb-icon-label">' . superb_icon( 'check', array( 'size' => 16 ) ) . ' <span>',
	);

	foreach ( $replacements as $search => $replace ) {
		if ( false !== strpos( $content, $search ) ) {
			$content = str_replace( $search, $replace, $content );
		}
	}

	// Credential badge emojis in about page content.
	$credential_icons = array(
		'<div class="credential-badge"><span>🛡️</span>' => '<div class="credential-badge"><span class="credential-badge__icon">' . superb_icon( 'shield-check', array( 'size' => 28 ) ) . '</span>',
		'<div class="credential-badge"><span>✓</span>'  => '<div class="credential-badge"><span class="credential-badge__icon">' . superb_icon( 'check-circle', array( 'size' => 28 ) ) . '</span>',
		'<div class="credential-badge"><span>🎨</span>'  => '<div class="credential-badge"><span class="credential-badge__icon">' . superb_icon( 'palette', array( 'size' => 28 ) ) . '</span>',
		'<div class="credential-badge"><span>⭐</span>'  => '<div class="credential-badge"><span class="credential-badge__icon">' . superb_icon( 'award', array( 'size' => 28 ) ) . '</span>',
	);

	foreach ( $credential_icons as $search => $replace ) {
		$content = str_replace( $search, $replace, $content );
	}

	return superb_restore_stripped_icons( $content );
}
add_filter( 'the_content', 'superb_icons_in_content', 12 );

/**
 * Shortcode: [superb_icon name="phone" size="18"]
 *
 * @param array $atts Shortcode attributes.
 */
function superb_icon_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'name'   => 'check',
			'size'   => 24,
			'class'  => '',
			'filled' => '',
		),
		$atts,
		'superb_icon'
	);
	return superb_icon(
		$atts['name'],
		array(
			'size'   => (int) $atts['size'],
			'class'  => $atts['class'],
			'filled' => filter_var( $atts['filled'], FILTER_VALIDATE_BOOLEAN ),
		)
	);
}
add_shortcode( 'superb_icon', 'superb_icon_shortcode' );

/**
 * Shortcode: [superb_stars count="5"]
 *
 * @param array $atts Shortcode attributes.
 */
function superb_stars_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'count' => 5,
			'size'  => 16,
		),
		$atts,
		'superb_stars'
	);
	return superb_icon_stars( (int) $atts['count'], (int) $atts['size'] );
}
add_shortcode( 'superb_stars', 'superb_stars_shortcode' );

/**
 * Shortcode: [superb_contact_details]
 */
function superb_contact_details_shortcode() {
	return superb_contact_details_markup();
}
add_shortcode( 'superb_contact_details', 'superb_contact_details_shortcode' );

/**
 * Social profile links with icons (footer, contact sidebar, etc.).
 *
 * @param array $args class (extra wrapper classes), size (icon px).
 */
function superb_social_links_markup( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class' => '',
			'size'  => 18,
			'title' => '',
		)
	);
	$class = trim( (string) $args['class'] );
	$size  = (int) $args['size'];
	$title = trim( (string) $args['title'] );
	ob_start();

	if ( $title ) {
		echo '<div class="' . esc_attr( trim( 'superb-social-block ' . $class ) ) . '">';
		echo '<h4 class="superb-social-block__title">' . esc_html( $title ) . '</h4>';
	} else {
		$class = trim( 'superb-footer__social ' . $class );
	}
	?>
	<div class="<?php echo esc_attr( $title ? 'superb-footer__social' : $class ); ?>">
		<a href="#" aria-label="Facebook"><?php echo superb_icon( 'facebook', array( 'size' => $size, 'filled' => true ) ); ?></a>
		<a href="#" aria-label="Instagram"><?php echo superb_icon( 'instagram', array( 'size' => $size ) ); ?></a>
	</div>
	<?php
	if ( $title ) {
		echo '</div>';
	}

	return ob_get_clean();
}

/**
 * Shortcode: [superb_social_links class="superb-social--spaced" title="Social Links"]
 *
 * @param array $atts Shortcode attributes.
 */
function superb_social_links_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'class' => '',
			'size'  => 18,
			'title' => '',
		),
		$atts,
		'superb_social_links'
	);
	return superb_social_links_markup(
		array(
			'class' => $atts['class'],
			'size'  => (int) $atts['size'],
			'title' => $atts['title'],
		)
	);
}
add_shortcode( 'superb_social_links', 'superb_social_links_shortcode' );

/**
 * Google Maps embed for contact sidebar.
 */
function superb_contact_map_markup() {
	$src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d402044.3134679838!2d144.763434!3d-37.8136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad646b5d2b0bd667%3A0x504567298b473bf!2sMelbourne%20VIC!5e0!3m2!1sen!2sau!4v1718500000000!5m2!1sen!2sau';
	ob_start();
	?>
	<div class="superb-map-embed">
		<iframe
			src="<?php echo esc_url( $src ); ?>"
			width="100%"
			height="280"
			allowfullscreen=""
			loading="lazy"
			referrerpolicy="no-referrer-when-downgrade"
			title="<?php esc_attr_e( 'Superb Painting — Greater Melbourne service area', 'kadence-child-superb' ); ?>"
		></iframe>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode: [superb_contact_map]
 */
function superb_contact_map_shortcode() {
	return superb_contact_map_markup();
}
add_shortcode( 'superb_contact_map', 'superb_contact_map_shortcode' );

/**
 * Contact page reassurance strip.
 */
function superb_contact_reassurance_markup() {
	$items = array(
		array( 'check', 'No obligation' ),
		array( 'timer', 'Response within 24hrs' ),
		array( 'file-text', 'Fixed-price quotes' ),
	);
	$html = '<section class="contact-reassurance" aria-label="Quote reassurance">';
	foreach ( $items as $item ) {
		$html .= superb_icon_label( $item[0], $item[1], 16 );
	}
	$html .= '</section>';
	return $html;
}

/**
 * Shortcode: [superb_contact_reassurance]
 */
function superb_contact_reassurance_shortcode() {
	return superb_contact_reassurance_markup();
}
add_shortcode( 'superb_contact_reassurance', 'superb_contact_reassurance_shortcode' );

/**
 * Export icon SVG for use in static HTML pattern files.
 *
 * @param string $name Icon name.
 * @param int    $size Size in px.
 */
function superb_icon_html( $name, $size = 24 ) {
	return superb_icon( $name, array( 'size' => $size ) );
}
