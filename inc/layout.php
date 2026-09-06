<?php
/**
 * Full-width layout helpers for marketing pages.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Page slugs that use full-width marketing layout.
 *
 * @return string[]
 */
function superb_fullwidth_page_slugs() {
	return array( 'home', 'about', 'services', 'suburbs', 'contact', 'faq', 'thank-you' );
}

/**
 * Whether current request is a superb full-width marketing page.
 */
function superb_is_fullwidth_page() {
	if ( is_front_page() ) {
		return true;
	}
	if ( ! is_page() ) {
		return false;
	}
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	if ( in_array( $slug, superb_fullwidth_page_slugs(), true ) ) {
		return true;
	}
	return is_page_template( 'page-templates/template-superb-fullwidth.php' );
}

/**
 * Apply Kadence per-page layout meta for full-width pages.
 *
 * @param int $post_id Post ID.
 */
function superb_apply_page_layout_meta( $post_id ) {
	update_post_meta( $post_id, '_kad_post_title', 'hide' );
	update_post_meta( $post_id, '_kad_post_layout', 'fullwidth' );
	update_post_meta( $post_id, '_kad_post_content_style', 'unboxed' );
	update_post_meta( $post_id, '_kad_post_vertical_padding', 'hide' );
	update_post_meta( $post_id, '_kad_post_feature', 'hide' );
}

/**
 * Kadence layout filter for marketing pages.
 *
 * @param array $layout Layout config.
 * @return array
 */
function superb_kadence_post_layout( $layout ) {
	if ( ! superb_is_fullwidth_page() && ! is_singular( array( 'service', 'suburb' ) ) ) {
		return $layout;
	}
	$layout['title']    = 'hide';
	$layout['layout']   = 'fullwidth';
	$layout['boxed']    = 'unboxed';
	$layout['vpadding'] = 'hide';
	$layout['sidebar']  = 'disable';
	return $layout;
}
add_filter( 'kadence_post_layout', 'superb_kadence_post_layout' );

/**
 * Body class for full-width marketing pages.
 *
 * @param array $classes Body classes.
 * @return array
 */
function superb_fullwidth_body_class( $classes ) {
	if ( superb_is_fullwidth_page() ) {
		$classes[] = 'superb-fullwidth-page';
	}
	if ( is_singular( 'service' ) || is_singular( 'suburb' ) ) {
		$classes[] = 'superb-fullwidth-page';
		$classes[] = 'superb-cpt-page';
	}
	if ( is_page_template( 'page-gallery.php' ) ) {
		$classes[] = 'superb-fullwidth-page';
	}
	return $classes;
}
add_filter( 'body_class', 'superb_fullwidth_body_class', 20 );

/**
 * Output marketing page content without Kadence boxed wrapper.
 */
function superb_marketing_content() {
	while ( have_posts() ) {
		the_post();
		echo '<main id="main" class="site-main superb-marketing-main" tabindex="-1">';
		echo '<div class="superb-marketing-content">';
		the_content();
		echo '</div></main>';
	}
}
