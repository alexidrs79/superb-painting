<?php
/**
 * Front-end performance — LCP, render-blocking, resource hints.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Preconnect to Google Fonts (async stylesheet still benefits).
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Relation type.
 * @return array
 */
function superb_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'superb_resource_hints', 10, 2 );

/**
 * Load Google Fonts without blocking first paint.
 *
 * @param string $html   Link tag HTML.
 * @param string $handle Style handle.
 * @return string
 */
function superb_async_google_fonts( $html, $handle ) {
	if ( 'superb-google-fonts' !== $handle ) {
		return $html;
	}

	$async = str_replace(
		array( "media='all'", 'media="all"' ),
		array( "media='print' onload=\"this.media='all'\"", 'media="print" onload="this.media=\'all\'"' ),
		$html
	);

	return $async . '<noscript>' . $html . '</noscript>';
}
add_filter( 'style_loader_tag', 'superb_async_google_fonts', 10, 2 );

/**
 * Defer non-critical theme scripts.
 *
 * @param string $tag    Script tag.
 * @param string $handle Handle.
 * @param string $src    Source URL.
 * @return string
 */
function superb_defer_theme_scripts( $tag, $handle, $src ) {
	if ( is_admin() ) {
		return $tag;
	}

	$defer_handles = array(
		'superb-interactions',
		'superb-animations',
		'superb-quote-form',
		'superb-nav-dropdown',
		'superb-site-enhancements',
		'superb-gallery',
	);

	if ( in_array( $handle, $defer_handles, true ) && false === strpos( $tag, ' defer' ) ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}

	// GoDaddy injected scripts (cannot be removed from theme; defer reduces render impact).
	if ( $src && false !== strpos( $src, 'scc-c2' ) && false === strpos( $tag, ' defer' ) ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'superb_defer_theme_scripts', 10, 3 );

/**
 * Minimal above-the-fold CSS so the hero can paint before the full stylesheet.
 */
function superb_inline_critical_css() {
	if ( is_admin() || ! is_front_page() ) {
		return;
	}

	echo '<style id="superb-critical-css">'
		. '.hero-split{position:relative;z-index:0;isolation:isolate;min-height:100vh;display:flex;align-items:center;background-color:#1a1a1a}'
		. '.hero-split__backdrop{position:absolute;inset:0;z-index:0;overflow:hidden;pointer-events:none}'
		. '.hero-split__bg{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;z-index:0}'
		. '.hero-split__overlay{position:absolute;inset:0;z-index:1;background:linear-gradient(105deg,rgba(26,26,26,.88) 0%,rgba(26,26,26,.72) 45%,rgba(26,26,26,.55) 100%)}'
		. '.hero-split__inner{position:relative;z-index:1;display:grid;grid-template-columns:55% 45%;gap:40px;align-items:center;max-width:1200px;margin:0 auto;padding:100px 24px 80px;width:100%}'
		. '.hero-split__content h1{color:#fff;font-family:Montserrat,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;font-weight:700;line-height:1.15;margin:0 0 20px;font-size:clamp(2rem,5vw,3.5rem)}'
		. '.hero-split__content p{color:rgba(255,255,255,.9);font-size:1.125rem;margin:0 0 32px;max-width:520px}'
		. '@media(max-width:992px){.hero-split{min-height:auto;display:block;background-color:#f8f8f6}.hero-split__backdrop{display:none}.hero-split__inner{grid-template-columns:1fr;padding:0;gap:0}}'
		. '</style>' . "\n";
}
add_action( 'wp_head', 'superb_inline_critical_css', 1 );

/**
 * Preload LCP hero image early with high priority.
 */
function superb_preload_lcp_image() {
	if ( is_admin() || ! is_front_page() || ! function_exists( 'superb_get_hero_image_url' ) ) {
		return;
	}

	$hero = superb_get_hero_image_url();
	echo '<link rel="preload" as="image" href="' . esc_url( $hero ) . '" fetchpriority="high">' . "\n";
}
add_action( 'wp_head', 'superb_preload_lcp_image', 2 );

/**
 * Markup for the discoverable hero background image.
 *
 * @return string
 */
function superb_hero_lcp_image_markup() {
	if ( ! function_exists( 'superb_get_hero_image_url' ) ) {
		return '';
	}

	$img = sprintf(
		'<img class="hero-split__bg" src="%1$s" alt="" width="1920" height="1341" fetchpriority="high" decoding="async">',
		esc_url( superb_get_hero_image_url() )
	);

	return '<div class="hero-split__backdrop" aria-hidden="true">' . $img . '<div class="hero-split__overlay"></div></div>';
}

/**
 * Inject hero LCP image into stored homepage markup (no DB re-sync required).
 *
 * @param string $content Post content.
 * @return string
 */
function superb_inject_hero_lcp_image( $content ) {
	if ( ! is_front_page() || empty( $content ) || false === strpos( $content, 'hero-split' ) ) {
		return $content;
	}

	if ( false !== strpos( $content, 'hero-split__backdrop' ) ) {
		return $content;
	}

	if ( false !== strpos( $content, 'hero-split__bg' ) ) {
		return preg_replace(
			'/(<section class="hero-split">)\s*(<img class="hero-split__bg"[^>]*>)/',
			'$1<div class="hero-split__backdrop" aria-hidden="true">$2<div class="hero-split__overlay"></div></div>',
			$content,
			1
		);
	}

	$backdrop = superb_hero_lcp_image_markup();
	if ( ! $backdrop ) {
		return $content;
	}

	return preg_replace(
		'/(<section class="hero-split">)/',
		'$1' . $backdrop,
		$content,
		1
	);
}
add_filter( 'the_content', 'superb_inject_hero_lcp_image', 7 );
