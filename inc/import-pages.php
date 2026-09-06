<?php
/**
 * WP-CLI command to import page content from HTML files.
 *
 * Usage: wp eval-file wp-content/themes/kadence-child-superb/inc/import-pages.php
 *
 * @package Kadence_Child_Superb
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pages = array(
	'home'     => array( 'title' => 'Home', 'file' => 'home.html', 'front' => true ),
	'about'    => array( 'title' => 'About Us', 'file' => 'about.html' ),
	'services' => array( 'title' => 'Services', 'file' => 'services.html' ),
	'suburbs'  => array( 'title' => 'Suburbs', 'file' => 'suburbs.html' ),
	'contact'  => array( 'title' => 'Contact Us', 'file' => 'contact.html' ),
);

$content_dir = get_stylesheet_directory() . '/content/';

foreach ( $pages as $slug => $page ) {
	$file = $content_dir . $page['file'];
	if ( ! file_exists( $file ) ) {
		WP_CLI::warning( "Missing: {$page['file']}" );
		continue;
	}

	$content = file_get_contents( $file );
	$existing = get_page_by_path( $slug );

	$post_data = array(
		'post_title'   => $page['title'],
		'post_name'    => $slug,
		'post_content' => $content,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);

	if ( $existing ) {
		$post_data['ID'] = $existing->ID;
		$id = wp_update_post( $post_data );
		WP_CLI::log( "Updated page: {$page['title']}" );
	} else {
		$id = wp_insert_post( $post_data );
		WP_CLI::log( "Created page: {$page['title']}" );
	}

	if ( ! empty( $page['front'] ) && $id ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $id );
		WP_CLI::log( 'Set as front page.' );
	}
}

// Gallery page with template.
$gallery = get_page_by_path( 'gallery' );
$gallery_data = array(
	'post_title'   => 'Gallery',
	'post_name'    => 'gallery',
	'post_content' => '',
	'post_status'  => 'publish',
	'post_type'    => 'page',
);
if ( $gallery ) {
	$gallery_data['ID'] = $gallery->ID;
	$gid = wp_update_post( $gallery_data );
} else {
	$gid = wp_insert_post( $gallery_data );
}
if ( $gid ) {
	update_post_meta( $gid, '_wp_page_template', 'page-gallery.php' );
	WP_CLI::log( 'Gallery page ready with template.' );
}

// Import service CPT posts.
$service_dir = $content_dir . 'services/';
if ( is_dir( $service_dir ) ) {
	foreach ( glob( $service_dir . '*.html' ) as $file ) {
		$slug    = basename( $file, '.html' );
		$title   = ucwords( str_replace( '-', ' ', $slug ) );
		$content = file_get_contents( $file );
		$existing = get_page_by_path( $slug, OBJECT, 'service' );

		$data = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'service',
		);
		if ( $existing ) {
			$data['ID'] = $existing->ID;
			wp_update_post( $data );
		} else {
			wp_insert_post( $data );
		}
		WP_CLI::log( "Service: {$title}" );
	}
}

// Import suburb CPT posts.
$suburb_dir = $content_dir . 'suburbs/';
if ( is_dir( $suburb_dir ) ) {
	foreach ( glob( $suburb_dir . '*.html' ) as $file ) {
		$slug    = basename( $file, '.html' );
		$title   = ucwords( str_replace( '-', ' ', $slug ) );
		$content = file_get_contents( $file );
		$existing = get_page_by_path( $slug, OBJECT, 'suburb' );

		$data = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'suburb',
		);
		if ( $existing ) {
			$data['ID'] = $existing->ID;
			wp_update_post( $data );
		} else {
			wp_insert_post( $data );
		}
		WP_CLI::log( "Suburb: {$title}" );
	}
}

WP_CLI::success( 'Import complete. Activate child theme and flush permalinks.' );
