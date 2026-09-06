<?php
/**
 * Primary navigation — Services & Suburbs dropdown menus.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

define( 'SUPERB_MENU_CONFIG_VERSION', '2.2.2' );

/**
 * Top-level pages in menu order.
 *
 * @return string[]
 */
function superb_primary_menu_page_slugs() {
	return array( 'home', 'about', 'services', 'suburbs', 'gallery', 'contact' );
}

/**
 * CPT slugs that receive dropdown children under a parent page.
 *
 * @return array<string, string>
 */
function superb_primary_menu_dropdown_map() {
	return array(
		'services' => 'service',
		'suburbs'  => 'suburb',
	);
}

/**
 * Remove every item from a nav menu (children before parents).
 *
 * @param int $menu_id Menu term ID.
 */
function superb_clear_nav_menu_items( $menu_id ) {
	$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
	if ( ! $items ) {
		return;
	}

	$remaining = array();
	foreach ( $items as $item ) {
		$remaining[ (int) $item->ID ] = (int) $item->menu_item_parent;
	}

	while ( ! empty( $remaining ) ) {
		$deleted_any = false;

		foreach ( $remaining as $id => $parent_id ) {
			if ( 0 === $parent_id || ! isset( $remaining[ $parent_id ] ) ) {
				wp_delete_post( $id, true );
				unset( $remaining[ $id ] );
				$deleted_any = true;
			}
		}

		if ( ! $deleted_any ) {
			foreach ( array_keys( $remaining ) as $id ) {
				wp_delete_post( $id, true );
			}
			break;
		}
	}
}

/**
 * Build or rebuild the Primary Menu with Services/Suburbs sub-items.
 *
 * @param bool $force Skip version guard.
 * @return array Log lines.
 */
function superb_configure_primary_menu( $force = false ) {
	$log = array();

	if ( ! $force && get_option( 'superb_menu_config_version' ) === SUPERB_MENU_CONFIG_VERSION ) {
		return $log;
	}

	$menu_name = 'Primary Menu';
	$menu      = wp_get_nav_menu_object( $menu_name );

	if ( $menu ) {
		$deleted = wp_delete_nav_menu( (int) $menu->term_id );
		if ( is_wp_error( $deleted ) ) {
			$log[] = 'Menu delete failed — clearing items instead: ' . $deleted->get_error_message();
			superb_clear_nav_menu_items( (int) $menu->term_id );
			$menu_id = (int) $menu->term_id;
		} else {
			$log[] = 'Deleted old Primary Menu for clean rebuild';
			$menu_id = wp_create_nav_menu( $menu_name );
		}
	} else {
		$menu_id = wp_create_nav_menu( $menu_name );
	}

	if ( is_wp_error( $menu_id ) || ! $menu_id ) {
		$log[] = 'Primary menu configuration failed.';
		return $log;
	}

	$menu_id = (int) $menu_id;
	$log[]   = 'Created menu: Primary Menu';

	$position = 1;

	foreach ( superb_primary_menu_page_slugs() as $slug ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			continue;
		}

		$parent_item_id = wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => get_the_title( $page ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page->ID,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $position,
			)
		);

		if ( is_wp_error( $parent_item_id ) ) {
			$log[] = 'Menu item failed: ' . $slug;
			continue;
		}

		++$position;

		if ( ! isset( superb_primary_menu_dropdown_map()[ $slug ] ) ) {
			continue;
		}

		$post_type = superb_primary_menu_dropdown_map()[ $slug ];
		$children  = superb_get_menu_dropdown_posts( $slug, $post_type );

		foreach ( $children as $child ) {
			if ( ! empty( $child['custom'] ) ) {
				$child_args = array(
					'menu-item-title'     => $child['title'],
					'menu-item-url'       => $child['url'],
					'menu-item-type'      => 'custom',
					'menu-item-status'    => 'publish',
					'menu-item-parent-id' => (int) $parent_item_id,
					'menu-item-position'  => $position,
				);
			} else {
				$child_args = array(
					'menu-item-title'     => $child['title'],
					'menu-item-object'    => $post_type,
					'menu-item-object-id' => $child['id'],
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
					'menu-item-parent-id' => (int) $parent_item_id,
					'menu-item-position'  => $position,
				);
			}

			$child_id = wp_update_nav_menu_item( $menu_id, 0, $child_args );

			if ( ! is_wp_error( $child_id ) ) {
				++$position;
			}
		}

		$log[] = ucfirst( $slug ) . ' menu: ' . count( $children ) . ' sub-items';
	}

	$locations            = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	$locations['mobile']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	update_option( 'superb_menu_config_version', SUPERB_MENU_CONFIG_VERSION );
	$log[] = 'Primary Menu assigned with dropdowns';

	return $log;
}

/**
 * Detect duplicate top-level or child items in the Primary Menu.
 *
 * @param int|null $menu_id Menu term ID.
 * @return bool
 */
function superb_primary_menu_has_duplicates( $menu_id = null ) {
	if ( null === $menu_id ) {
		$menu = wp_get_nav_menu_object( 'Primary Menu' );
		if ( ! $menu ) {
			return false;
		}
		$menu_id = (int) $menu->term_id;
	}

	$items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
	if ( ! $items ) {
		return false;
	}

	$seen = array();
	foreach ( $items as $item ) {
		$key = (int) $item->menu_item_parent . '|' . (string) $item->type . '|' . (string) $item->object . '|' . (int) $item->object_id;
		if ( isset( $seen[ $key ] ) ) {
			return true;
		}
		$seen[ $key ] = true;
	}

	return false;
}

/**
 * Rebuild Primary Menu when version changes or duplicate items are detected.
 */
function superb_maybe_repair_primary_menu() {
	if ( wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	$needs_version = get_option( 'superb_menu_config_version' ) !== SUPERB_MENU_CONFIG_VERSION;
	$needs_dedupe  = superb_primary_menu_has_duplicates();

	if ( ! $needs_version && ! $needs_dedupe ) {
		return;
	}

	superb_configure_primary_menu( true );
}
add_action( 'init', 'superb_maybe_repair_primary_menu', 25 );

/**
 * Posts to show under a Services or Suburbs menu parent.
 *
 * @param string $parent_slug Page slug (services|suburbs).
 * @param string $post_type   CPT slug.
 * @return array<int, array{id:int, title:string}>
 */
function superb_get_menu_dropdown_posts( $parent_slug, $post_type ) {
	$children = array();

	if ( 'service' === $post_type && function_exists( 'superb_get_service_definitions' ) ) {
		foreach ( superb_get_service_definitions() as $slug => $def ) {
			if ( ! empty( $def['external_url'] ) ) {
				$children[] = array(
					'custom' => true,
					'title'  => $def['title'],
					'url'    => $def['external_url'],
				);
				continue;
			}

			$post = get_page_by_path( $slug, OBJECT, 'service' );
			if ( ! $post ) {
				continue;
			}
			$children[] = array(
				'id'    => (int) $post->ID,
				'title' => $def['title'],
			);
		}
		return $children;
	}

	if ( 'suburb' === $post_type && function_exists( 'superb_get_suburb_definitions' ) ) {
		$defs = superb_get_suburb_definitions();
		usort(
			$defs,
			function ( $a, $b ) {
				return strcasecmp( $a['name'], $b['name'] );
			}
		);
		foreach ( $defs as $sub ) {
			$post = get_page_by_path( $sub['slug'], OBJECT, 'suburb' );
			if ( ! $post ) {
				continue;
			}
			$children[] = array(
				'id'    => (int) $post->ID,
				'title' => $sub['name'],
			);
		}

		$children[] = array(
			'custom' => true,
			'title'  => 'Others',
			'url'    => home_url( SUPERB_SUBURBS_URL ),
		);
	}

	return $children;
}
