<?php
/**
 * One-time site setup: theme, pages, CF7 forms, menu, permalinks.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register suburb and service custom post types.
 */
function superb_register_post_types() {
	register_post_type(
		'suburb',
		array(
			'labels'            => array(
				'name'               => __( 'Suburbs', 'kadence-child-superb' ),
				'singular_name'      => __( 'Suburb', 'kadence-child-superb' ),
				'add_new'            => __( 'Add New Suburb', 'kadence-child-superb' ),
				'add_new_item'       => __( 'Add New Suburb', 'kadence-child-superb' ),
				'edit_item'          => __( 'Edit Suburb', 'kadence-child-superb' ),
				'new_item'           => __( 'New Suburb', 'kadence-child-superb' ),
				'view_item'          => __( 'View Suburb', 'kadence-child-superb' ),
				'search_items'       => __( 'Search Suburbs', 'kadence-child-superb' ),
				'not_found'          => __( 'No suburbs found', 'kadence-child-superb' ),
				'not_found_in_trash' => __( 'No suburbs found in Trash', 'kadence-child-superb' ),
				'all_items'          => __( 'All Suburbs', 'kadence-child-superb' ),
				'menu_name'          => __( 'Suburbs', 'kadence-child-superb' ),
			),
			'public'            => true,
			'has_archive'       => false,
			'rewrite'           => array( 'slug' => 'suburbs' ),
			'menu_icon'         => 'dashicons-location-alt',
			'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'      => true,
			'show_in_nav_menus' => true,
		)
	);

	register_post_type(
		'service',
		array(
			'labels'            => array(
				'name'               => __( 'Services', 'kadence-child-superb' ),
				'singular_name'      => __( 'Service', 'kadence-child-superb' ),
				'add_new'            => __( 'Add New Service', 'kadence-child-superb' ),
				'add_new_item'       => __( 'Add New Service', 'kadence-child-superb' ),
				'edit_item'          => __( 'Edit Service', 'kadence-child-superb' ),
				'new_item'           => __( 'New Service', 'kadence-child-superb' ),
				'view_item'          => __( 'View Service', 'kadence-child-superb' ),
				'search_items'       => __( 'Search Services', 'kadence-child-superb' ),
				'not_found'          => __( 'No services found', 'kadence-child-superb' ),
				'not_found_in_trash' => __( 'No services found in Trash', 'kadence-child-superb' ),
				'all_items'          => __( 'All Services', 'kadence-child-superb' ),
				'menu_name'          => __( 'Services', 'kadence-child-superb' ),
			),
			'public'            => true,
			'has_archive'       => false,
			'rewrite'           => array( 'slug' => 'services' ),
			'menu_icon'         => 'dashicons-art',
			'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'      => true,
			'show_in_nav_menus' => true,
		)
	);
}
add_action( 'init', 'superb_register_post_types' );

/**
 * Pattern slugs per marketing page (source of truth for page content).
 *
 * @return array<string, array<int, string>>
 */
function superb_get_marketing_page_patterns() {
	return array(
		'home'      => array(
			'hero-split',
			'stats-row',
			'services-grid',
			'partners-strip',
			'credentials-strip',
			'why-choose-us',
			'google-reviews',
			'our-process',
			'suburbs-preview',
			'gallery-preview',
			'cta-banner',
		),
		'about'     => array(
			'about-hero',
			'about-story',
			'about-milestones',
			'about-values',
			'about-trust',
			'about-team',
			'about-testimonial',
			'about-cta',
		),
		'services'  => array(
			'services-page-hero',
			'services-page-intro',
			'services-page-grid',
			'services-page-showcase',
			'services-page-trust',
			'services-page-cta',
		),
		'suburbs'   => array(
			'suburbs-page-hero',
			'suburbs-page-intro',
			'suburbs-page-grid',
			'suburbs-page-cta',
		),
		'contact'   => array(
			'contact-page-hero',
			'contact-page-main',
			'contact-page-reassurance',
		),
		'faq'       => array(
			'faq-page-hero',
			'faq-page-content',
			'faq-page-cta',
		),
		'thank-you' => array(
			'thank-you-page-hero',
			'thank-you-page-content',
		),
	);
}

/**
 * Human-readable titles for marketing pages.
 *
 * @return array<string, string>
 */
function superb_marketing_page_titles() {
	return array(
		'home'      => 'Home',
		'about'     => 'About Us',
		'services'  => 'Services',
		'suburbs'   => 'Suburbs',
		'contact'   => 'Contact Us',
		'faq'       => 'FAQ',
		'thank-you' => 'Thank You',
	);
}

/**
 * Compose page content from pattern PHP files (renders icons via PHP).
 *
 * @param array $slugs Pattern slugs without .php.
 */
function superb_compose_page_from_patterns( $slugs ) {
	$content = '';
	foreach ( $slugs as $slug ) {
		$file = SUPERB_THEME_DIR . '/patterns/' . $slug . '.php';
		if ( ! file_exists( $file ) ) {
			continue;
		}
		ob_start();
		include $file;
		$content .= ob_get_clean() . "\n";
	}
	return trim( $content );
}

/**
 * Push composed pattern content to DB and sync content/{slug}.html snapshot.
 *
 * @param string $slug     Page slug.
 * @param array  $patterns Pattern slugs without .php.
 * @return bool Whether content was synced.
 */
function superb_sync_page_from_patterns( $slug, $patterns ) {
	$content = superb_compose_page_from_patterns( $patterns );
	if ( ! $content ) {
		return false;
	}

	$html_file = SUPERB_THEME_DIR . '/content/' . $slug . '.html';
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	file_put_contents( $html_file, $content );

	$titles = superb_marketing_page_titles();
	$title  = isset( $titles[ $slug ] ) ? $titles[ $slug ] : ucfirst( str_replace( '-', ' ', $slug ) );
	$page   = get_page_by_path( $slug );

	if ( ! $page ) {
		$page_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
			),
			true
		);
		if ( is_wp_error( $page_id ) ) {
			return false;
		}
		if ( 'home' !== $slug ) {
			update_post_meta( $page_id, '_wp_page_template', 'page-templates/template-superb-fullwidth.php' );
		}
		if ( function_exists( 'superb_apply_page_layout_meta' ) ) {
			superb_apply_page_layout_meta( $page_id );
		}
		if ( 'home' === $slug ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $page_id );
		}
		return true;
	}

	wp_update_post(
		array(
			'ID'           => $page->ID,
			'post_content' => $content,
		)
	);

	return true;
}

/**
 * Refresh marketing page content from theme patterns (icons, latest markup).
 */
function superb_refresh_marketing_pages() {
	foreach ( superb_get_marketing_page_patterns() as $slug => $patterns ) {
		superb_sync_page_from_patterns( $slug, $patterns );
	}
	update_option( 'superb_marketing_sync_time', time() );
}

/**
 * Whether any pattern file is newer than the last marketing sync.
 *
 * @return bool
 */
function superb_marketing_patterns_need_sync() {
	$last_sync = (int) get_option( 'superb_marketing_sync_time', 0 );

	foreach ( superb_get_marketing_page_patterns() as $patterns ) {
		foreach ( $patterns as $slug ) {
			$file = SUPERB_THEME_DIR . '/patterns/' . $slug . '.php';
			if ( file_exists( $file ) && filemtime( $file ) > $last_sync ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Auto-sync marketing pages in local when patterns change (once per request).
 */
function superb_maybe_auto_sync_marketing_pages() {
	if ( ! function_exists( 'wp_get_environment_type' ) || 'local' !== wp_get_environment_type() ) {
		return;
	}
	if ( is_admin() && ! wp_doing_ajax() ) {
		return;
	}
	if ( ! superb_marketing_patterns_need_sync() ) {
		return;
	}

	superb_refresh_marketing_pages();
}
add_action( 'init', 'superb_maybe_auto_sync_marketing_pages', 5 );

/**
 * Re-run polish when theme version bumps (CPT migration, menu, layout).
 */
function superb_maybe_run_polish() {
	if ( get_option( 'superb_polish_complete' ) === SUPERB_THEME_VERSION ) {
		return;
	}
	superb_run_polish();
}
add_action( 'init', 'superb_maybe_run_polish', 15 );

/**
 * Run complete Superb Painting setup.
 *
 * @return array Results log.
 */
function superb_run_setup() {
	$log = array();

	$required = array(
		'contact-form-7/wp-contact-form-7.php',
		'advanced-custom-fields/acf.php',
	);
	if ( ! function_exists( 'activate_plugin' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	foreach ( $required as $plugin ) {
		if ( ! is_plugin_active( $plugin ) ) {
			$result = activate_plugin( $plugin, '', false, true );
			if ( is_wp_error( $result ) ) {
				$log[] = 'Plugin failed: ' . $plugin . ' — ' . $result->get_error_message();
			} else {
				$log[] = 'Activated plugin: ' . $plugin;
			}
		}
	}

	$child = 'kadence-child-superb';
	if ( get_stylesheet() !== $child ) {
		switch_theme( $child );
		$log[] = 'Activated theme: ' . $child;
	} else {
		$log[] = 'Theme already active: ' . $child;
	}

	if ( function_exists( 'superb_cf7_create_forms' ) ) {
		$forms = superb_cf7_create_forms();
		$log[] = 'CF7 Hero Quote form ID: ' . ( $forms['hero'] ?: 'failed' );
		$log[] = 'CF7 Quick Quote form ID: ' . ( $forms['quick'] ?: 'failed' );
		$log[] = 'CF7 Full Quote form ID: ' . ( $forms['full'] ?: 'failed' );
	}

	if ( function_exists( 'superb_refresh_marketing_pages' ) ) {
		superb_refresh_marketing_pages();
		$log[] = 'Marketing pages synced from theme patterns';
	}

	$home = get_page_by_path( 'home' );
	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
		$log[] = 'Set front page: Home';
	}

	$gallery_data = array(
		'post_title'   => 'Gallery',
		'post_name'    => 'gallery',
		'post_content' => '',
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);
	$gallery = get_page_by_path( 'gallery' );
	if ( $gallery ) {
		$gallery_data['ID'] = $gallery->ID;
		$gid                = wp_update_post( $gallery_data, true );
	} else {
		$gid = wp_insert_post( $gallery_data, true );
	}
	if ( $gid && ! is_wp_error( $gid ) ) {
		update_post_meta( $gid, '_wp_page_template', 'page-gallery.php' );
		$log[] = 'Gallery page ready with template';
	}

	if ( function_exists( 'superb_configure_primary_menu' ) ) {
		$menu_log = superb_configure_primary_menu( true );
		$log      = array_merge( $log, $menu_log );
	}

	flush_rewrite_rules();
	update_option( 'superb_setup_complete', SUPERB_THEME_VERSION );
	$log[] = 'Setup complete!';

	return $log;
}

/**
 * Polish pass: layout templates, header, CPT cleanup, suburb seeding, ACF.
 *
 * @return array Results log.
 */
function superb_run_polish() {
	$log = array();

	foreach ( superb_fullwidth_page_slugs() as $slug ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			$log[] = 'Page not found for layout: ' . $slug;
			continue;
		}
		if ( 'home' !== $slug ) {
			update_post_meta( $page->ID, '_wp_page_template', 'page-templates/template-superb-fullwidth.php' );
		}
		superb_apply_page_layout_meta( $page->ID );
		$log[] = 'Full-width layout applied: ' . $page->post_title;
	}

	if ( function_exists( 'superb_refresh_marketing_pages' ) ) {
		superb_refresh_marketing_pages();
		$log[] = 'Refreshed marketing pages from theme patterns';
	}

	if ( function_exists( 'superb_configure_kadence_header' ) ) {
		superb_configure_kadence_header( true );
		$log[] = 'Kadence header configured (utility bar + nav + CTA)';
	}

	if ( function_exists( 'superb_configure_kadence_palette' ) ) {
		superb_configure_kadence_palette( true );
		$log[] = 'Kadence global palette synced to Superb colors';
	}

	if ( function_exists( 'superb_migrate_content_images' ) ) {
		$migrated = superb_migrate_content_images();
		$log[]    = 'Migrated legacy picsum images in ' . $migrated . ' posts';
	}

	if ( function_exists( 'superb_repair_broken_content_urls' ) ) {
		$repaired = superb_repair_broken_content_urls();
		$log[]    = 'Repaired broken theme image URLs in ' . $repaired . ' posts';
	}

	if ( function_exists( 'superb_migrate_content_colors' ) ) {
		$migrated = superb_migrate_content_colors();
		$log[]    = 'Migrated legacy colors in ' . $migrated . ' posts';
	}

	foreach ( superb_get_service_definitions() as $slug => $def ) {
		$post = superb_ensure_service_post( $slug );
		$log[] = $post ? 'Service ensured: ' . $def['title'] : 'Service failed: ' . $slug;
	}

	foreach ( superb_get_suburb_definitions() as $sub ) {
		$post = superb_ensure_suburb_post( $sub );
		$log[] = $post ? 'Suburb ensured: ' . $sub['name'] : 'Suburb failed: ' . $sub['slug'];
	}

	if ( function_exists( 'superb_seed_all_suburb_nearby' ) ) {
		superb_seed_all_suburb_nearby();
		$log[] = 'Suburb nearby relationships seeded where empty';
	}

	if ( function_exists( 'superb_seed_all_suburb_galleries' ) ) {
		superb_seed_all_suburb_galleries();
		$log[] = 'Suburb galleries seeded where empty';
	}

	if ( function_exists( 'superb_migrate_all_cms_fields' ) ) {
		superb_migrate_all_cms_fields();
		$log[] = 'Migrated legacy repeater fields to free-ACF format';
	}

	foreach ( superb_get_service_definitions() as $slug => $def ) {
		$post = get_page_by_path( $slug, OBJECT, 'service' );
		if ( $post ) {
			superb_seed_service_post( $post->ID, $slug );
		}
	}
	foreach ( superb_get_suburb_definitions() as $sub ) {
		$post = get_page_by_path( $sub['slug'], OBJECT, 'suburb' );
		if ( $post ) {
			superb_seed_suburb_post( $post->ID, $sub['slug'] );
		}
	}
	$log[] = 'Re-seeded empty CMS fields after migration';

	if ( function_exists( 'superb_cf7_create_forms' ) ) {
		$forms = superb_cf7_create_forms();
		update_option( 'superb_cf7_forms_version', SUPERB_CF7_FORMS_VERSION );
		$log[] = 'CF7 Hero Quote form ID: ' . ( $forms['hero'] ?: 'failed' );
		$log[] = 'CF7 Quick Quote form ID: ' . ( $forms['quick'] ?: 'failed' );
		$log[] = 'CF7 Full Quote form ID: ' . ( $forms['full'] ?: 'failed' );
	}

	if ( function_exists( 'superb_apply_site_logo' ) ) {
		superb_apply_site_logo();
		update_option( 'superb_logo_applied', SUPERB_THEME_VERSION );
		$log[] = 'Site logo applied';
	}

	if ( function_exists( 'superb_configure_primary_menu' ) ) {
		$menu_log = superb_configure_primary_menu( true );
		$log      = array_merge( $log, $menu_log );
	}

	flush_rewrite_rules();
	update_option( 'superb_polish_complete', SUPERB_THEME_VERSION );
	delete_option( 'superb_cms_backfill_version' );
	$log[] = 'Polish complete!';

	return $log;
}

/**
 * Auto-run setup once on theme activation.
 */
function superb_on_theme_activation() {
	if ( get_option( 'superb_setup_complete' ) !== SUPERB_THEME_VERSION ) {
		superb_run_setup();
	}
	if ( get_option( 'superb_polish_complete' ) !== SUPERB_THEME_VERSION ) {
		superb_run_polish();
	}
}
add_action( 'after_switch_theme', 'superb_on_theme_activation' );
