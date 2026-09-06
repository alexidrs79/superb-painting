<?php
/**
 * One-time CPT content seeding from theme defaults.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Seed a service CPT post from PHP definitions (empty fields only).
 *
 * @param int    $post_id Post ID.
 * @param string $slug    Service slug.
 */
function superb_seed_service_post( $post_id, $slug ) {
	$def = superb_get_service( $slug );
	if ( ! $def || ! function_exists( 'update_field' ) ) {
		return;
	}

	superb_update_field_if_empty( $post_id, 'service_tagline', $def['tagline'] ?? '' );
	superb_ensure_text_field_content( $post_id, 'service_intro', $def['intro'] ?? '' );

	superb_migrate_service_features_field( $post_id );
	if ( superb_field_is_empty( get_field( 'service_features_list', $post_id ) ) && ! empty( $def['included'] ) ) {
		update_field( 'service_features_list', superb_lines_to_textarea( $def['included'] ), $post_id );
	}

	superb_migrate_service_steps_field( $post_id );
	if ( ! empty( $def['steps'] ) ) {
		$has_steps = false;
		for ( $i = 1; $i <= superb_service_step_count(); $i++ ) {
			if ( ! superb_field_is_empty( get_field( 'service_step_' . $i . '_title', $post_id ) ) ) {
				$has_steps = true;
				break;
			}
		}
		if ( ! $has_steps ) {
			foreach ( $def['steps'] as $index => $step ) {
				if ( $index >= superb_service_step_count() ) {
					break;
				}
				$n = $index + 1;
				update_field( 'service_step_' . $n . '_title', $step['title'] ?? '', $post_id );
				update_field( 'service_step_' . $n . '_desc', $step['desc'] ?? '', $post_id );
			}
		}
	}

	superb_migrate_service_faq_field( $post_id );
	if ( ! empty( $def['faq'] ) ) {
		$has_faq = false;
		for ( $i = 1; $i <= superb_service_faq_count(); $i++ ) {
			if ( ! superb_field_is_empty( get_field( 'service_faq_' . $i . '_question', $post_id ) ) ) {
				$has_faq = true;
				break;
			}
		}
		if ( ! $has_faq ) {
			foreach ( $def['faq'] as $index => $item ) {
				if ( $index >= superb_service_faq_count() ) {
					break;
				}
				$n = $index + 1;
				update_field( 'service_faq_' . $n . '_question', $item['q'] ?? '', $post_id );
				update_field( 'service_faq_' . $n . '_answer', $item['a'] ?? '', $post_id );
			}
		}
	}

	superb_update_field_if_empty( $post_id, 'service_cta_heading', 'Ready to get started?' );
	superb_update_field_if_empty( $post_id, 'service_cta_text', 'Book your free on-site quote today. No obligation, no hidden costs.' );
	superb_update_field_if_empty( $post_id, 'service_cta_button', 'Get My Free Quote' );

	$icon_map = array(
		'interior-wall-painting'   => 'paint-roller',
		'kitchen-cabinet-painting' => 'layout-grid',
		'special-finishes'         => 'sparkles',
		'apartment-painting'       => 'building-2',
		'new-build-painting'       => 'hammer',
		'commercial-painting'      => 'briefcase',
	);
	superb_update_field_if_empty( $post_id, 'service_icon', $icon_map[ $slug ] ?? 'paint-roller' );

	if ( ! empty( $def['title'] ) ) {
		wp_update_post(
			array(
				'ID'         => $post_id,
				'post_title' => $def['title'],
			)
		);
	}

	if ( superb_field_is_empty( get_field( 'service_card_excerpt', $post_id ) ) && ! empty( $def['intro'] ) ) {
		update_field( 'service_card_excerpt', wp_trim_words( $def['intro'], 24, '…' ), $post_id );
	}

	$showcase_slugs = array( 'interior-wall-painting', 'special-finishes', 'apartment-painting' );
	if ( in_array( $slug, $showcase_slugs, true ) && superb_field_is_empty( get_field( 'service_showcase_featured', $post_id ) ) ) {
		update_field( 'service_showcase_featured', 1, $post_id );
	}

	if ( superb_field_is_empty( get_field( 'service_before_image', $post_id ) ) || superb_field_is_empty( get_field( 'service_after_image', $post_id ) ) ) {
		$random = $def['random'] ?? $post_id;
		$ba     = superb_before_after_images( $random );
		if ( superb_field_is_empty( get_field( 'service_before_image', $post_id ) ) && ! empty( $ba['before'] ) ) {
			superb_attach_theme_url_to_acf_image( $post_id, 'service_before_image', $ba['before'], 'Before — ' . ( $def['title'] ?? $slug ) );
		}
		if ( superb_field_is_empty( get_field( 'service_after_image', $post_id ) ) && ! empty( $ba['after'] ) ) {
			superb_attach_theme_url_to_acf_image( $post_id, 'service_after_image', $ba['after'], 'After — ' . ( $def['title'] ?? $slug ) );
		}
	}

	if ( ! has_post_thumbnail( $post_id ) ) {
		$hero_url = superb_service_hero_image_url( $slug );
		if ( $hero_url ) {
			$attach_id = superb_import_theme_image( $hero_url, ( $def['title'] ?? $slug ) . ' hero' );
			if ( $attach_id ) {
				set_post_thumbnail( $post_id, $attach_id );
			}
		}
	}

	superb_mark_content_seeded( $post_id );
}

/**
 * Seed suburb CPT post (empty fields only).
 *
 * @param int    $post_id Post ID.
 * @param string $slug    Suburb slug.
 */
function superb_seed_suburb_post( $post_id, $slug ) {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$name = get_the_title( $post_id );
	$def  = null;
	foreach ( superb_get_suburb_definitions() as $sub ) {
		if ( $sub['slug'] === $slug ) {
			$def = $sub;
			break;
		}
	}

	$content = superb_get_suburb_content( $slug );
	$desc    = $content
		? implode( "\n\n", $content['paragraphs'] )
		: implode( "\n\n", superb_suburb_default_description( $name ) );
	$work    = ( $content && ! empty( $content['work_summary'] ) )
		? $content['work_summary']
		: superb_suburb_default_work_summary( $name );

	superb_update_field_if_empty( $post_id, 'suburb_area', $def['area'] ?? 'Greater Melbourne' );
	superb_update_field_if_empty( $post_id, 'suburb_about_heading', 'About Our Work in ' . $name );
	superb_ensure_text_field_content( $post_id, 'suburb_description', $desc );
	superb_update_field_if_empty( $post_id, 'work_summary', $work );
	superb_update_field_if_empty( $post_id, 'suburb_job_count', '50+' );
	superb_update_field_if_empty( $post_id, 'suburb_satisfaction', '100%' );
	superb_update_field_if_empty( $post_id, 'suburb_response_time', 'Under 2 hours' );
	superb_update_field_if_empty( $post_id, 'suburb_local_copy', $content['local'] ?? '' );

	superb_migrate_suburb_services_field( $post_id );
	if ( superb_field_is_empty( get_field( 'suburb_services_list', $post_id ) ) ) {
		$list = ( $content && ! empty( $content['services'] ) ) ? $content['services'] : superb_suburb_services_list();
		update_field( 'suburb_services_list', superb_lines_to_textarea( $list ), $post_id );
	}

	superb_update_field_if_empty( $post_id, 'suburb_gallery_heading', 'Our Work in ' . $name );

	superb_update_field_if_empty( $post_id, 'suburb_cta_heading', 'Need a painter in ' . $name . '? Let\'s talk.' );
	superb_update_field_if_empty( $post_id, 'suburb_cta_text', 'Get a free, no-obligation quote from our local team. We respond within 24 hours.' );
	superb_update_field_if_empty( $post_id, 'suburb_cta_button', 'Get My Free Quote' );
	superb_update_field_if_empty( $post_id, 'suburb_card_excerpt', 'Interior, exterior & cabinet painting in ' . $name );

	$nearby = get_field( 'suburb_nearby', $post_id );
	if ( superb_field_is_empty( $nearby ) && $content && ! empty( $content['nearby'] ) ) {
		$ids = array();
		foreach ( $content['nearby'] as $nearby_slug ) {
			$p = get_page_by_path( $nearby_slug, OBJECT, 'suburb' );
			if ( $p ) {
				$ids[] = $p->ID;
			}
		}
		if ( $ids ) {
			update_field( 'suburb_nearby', $ids, $post_id );
		}
	}

	if ( ! has_post_thumbnail( $post_id ) && function_exists( 'superb_seed_suburb_featured_image' ) ) {
		superb_seed_suburb_featured_image( $post_id, $slug, $name );
	}

	superb_seed_suburb_gallery_if_empty( $post_id, $name );

	if ( superb_field_is_empty( get_post_field( 'post_excerpt', $post_id ) ) ) {
		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_excerpt' => 'Interior, exterior & cabinet painting in ' . $name,
			)
		);
	}

	superb_mark_content_seeded( $post_id );
}

/**
 * Import theme image URL into media library and set ACF image field.
 *
 * @param int    $post_id Post ID.
 * @param string $field   ACF field name.
 * @param string $url     Image URL.
 * @param string $alt     Alt text.
 */
function superb_attach_theme_url_to_acf_image( $post_id, $field, $url, $alt = '' ) {
	if ( ! function_exists( 'update_field' ) || superb_field_is_empty( $url ) ) {
		return;
	}

	$attach_id = superb_import_theme_image( $url, $alt );
	if ( $attach_id ) {
		update_field( $field, $attach_id, $post_id );
	}
}

/**
 * Sideload a theme asset into the media library (once per URL).
 *
 * @param string $url Image URL.
 * @param string $alt Alt text.
 */
function superb_import_theme_image( $url, $alt = '' ) {
	$hash     = md5( $url );
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'meta_key'       => '_superb_theme_image_hash',
			'meta_value'     => $hash,
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	if ( ! function_exists( 'media_sideload_image' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$attach_id = media_sideload_image( $url, 0, $alt, 'id' );
	if ( is_wp_error( $attach_id ) ) {
		return 0;
	}
	update_post_meta( (int) $attach_id, '_superb_theme_image_hash', $hash );
	return (int) $attach_id;
}

/**
 * Ensure service CPT exists and is seeded.
 *
 * @param string $slug Service slug.
 * @return WP_Post|null
 */
function superb_ensure_service_post( $slug ) {
	$def = superb_get_service( $slug );
	if ( ! $def ) {
		return null;
	}

	$post = get_page_by_path( $slug, OBJECT, 'service' );
	if ( ! $post ) {
		$id = wp_insert_post(
			array(
				'post_title'   => $def['title'],
				'post_name'    => $slug,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'service',
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			return null;
		}
		$post = get_post( $id );
	} elseif ( $def['title'] !== $post->post_title ) {
		wp_update_post(
			array(
				'ID'         => $post->ID,
				'post_title' => $def['title'],
			)
		);
	}

	superb_seed_service_post( $post->ID, $slug );

	return $post;
}

/**
 * Ensure suburb CPT exists and is seeded.
 *
 * @param array $sub Suburb definition row.
 * @return WP_Post|null
 */
function superb_ensure_suburb_post( $sub ) {
	$post = get_page_by_path( $sub['slug'], OBJECT, 'suburb' );
	if ( ! $post ) {
		$id = wp_insert_post(
			array(
				'post_title'   => $sub['name'],
				'post_name'    => $sub['slug'],
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'suburb',
				'post_excerpt' => 'Interior, exterior & cabinet painting in ' . $sub['name'],
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			return null;
		}
		$post = get_post( $id );
	}

	superb_seed_suburb_post( $post->ID, $sub['slug'] );

	return $post;
}

/**
 * Second-pass seed for suburb nearby relationships (after all posts exist).
 */
function superb_seed_all_suburb_nearby() {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}

	foreach ( superb_get_suburb_definitions() as $sub ) {
		$post = get_page_by_path( $sub['slug'], OBJECT, 'suburb' );
		if ( ! $post ) {
			continue;
		}
		if ( ! superb_field_is_empty( get_field( 'suburb_nearby', $post->ID ) ) ) {
			continue;
		}

		$content = superb_get_suburb_content( $sub['slug'] );
		if ( ! $content || empty( $content['nearby'] ) ) {
			continue;
		}

		$ids = array();
		foreach ( $content['nearby'] as $nearby_slug ) {
			$nearby = get_page_by_path( $nearby_slug, OBJECT, 'suburb' );
			if ( $nearby ) {
				$ids[] = $nearby->ID;
			}
		}
		if ( $ids ) {
			update_field( 'suburb_nearby', $ids, $post->ID );
		}
	}
}

/**
 * Seed gallery repeater rows for all suburbs missing images.
 */
function superb_seed_all_suburb_galleries() {
	foreach ( superb_get_suburb_definitions() as $sub ) {
		$post = get_page_by_path( $sub['slug'], OBJECT, 'suburb' );
		if ( $post ) {
			superb_seed_suburb_gallery_if_empty( $post->ID, $sub['name'] );
		}
	}
}

/**
 * Backfill empty ACF fields for all suburb and service CPT posts.
 *
 * Must run on acf/init (after field groups register) so update_field works in admin.
 */
function superb_backfill_all_cpt_acf_fields() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	foreach ( superb_get_service_definitions() as $slug => $def ) {
		$post = get_page_by_path( $slug, OBJECT, 'service' );
		if ( $post ) {
			if ( function_exists( 'superb_migrate_cms_fields' ) ) {
				superb_migrate_cms_fields( $post->ID );
			}
			superb_seed_service_post( $post->ID, $slug );
		}
	}

	foreach ( superb_get_suburb_definitions() as $sub ) {
		$post = get_page_by_path( $sub['slug'], OBJECT, 'suburb' );
		if ( $post ) {
			if ( function_exists( 'superb_migrate_cms_fields' ) ) {
				superb_migrate_cms_fields( $post->ID );
			}
			superb_seed_suburb_post( $post->ID, $sub['slug'] );
		}
	}

	$extra = get_posts(
		array(
			'post_type'      => array( 'suburb', 'service' ),
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
		)
	);
	foreach ( $extra as $post_id ) {
		$slug = get_post_field( 'post_name', $post_id );
		$type = get_post_type( $post_id );
		if ( function_exists( 'superb_migrate_cms_fields' ) ) {
			superb_migrate_cms_fields( $post_id );
		}
		if ( 'suburb' === $type ) {
			superb_seed_suburb_post( $post_id, $slug );
		} elseif ( 'service' === $type ) {
			superb_seed_service_post( $post_id, $slug );
		}
	}

	if ( function_exists( 'superb_seed_all_suburb_nearby' ) ) {
		superb_seed_all_suburb_nearby();
	}
	if ( function_exists( 'superb_seed_all_suburb_galleries' ) ) {
		superb_seed_all_suburb_galleries();
	}
}

/**
 * Run CPT ACF backfill once per theme version (after ACF field groups load).
 */
function superb_migrate_legacy_cpt_records() {
	$legacy = get_page_by_path( 'feature-walls', OBJECT, 'service' );
	if ( $legacy && ! get_page_by_path( 'special-finishes', OBJECT, 'service' ) ) {
		wp_update_post(
			array(
				'ID'          => $legacy->ID,
				'post_name'   => 'special-finishes',
				'post_title'  => 'Special Finishes',
				'post_status' => 'publish',
			)
		);
	}

	if ( function_exists( 'superb_get_retired_suburb_slugs' ) ) {
		foreach ( superb_get_retired_suburb_slugs() as $slug ) {
			$post = get_page_by_path( $slug, OBJECT, 'suburb' );
			if ( $post && 'publish' === $post->post_status ) {
				wp_update_post(
					array(
						'ID'          => $post->ID,
						'post_status' => 'draft',
					)
				);
			}
		}
	}

	$hampton = get_page_by_path( 'hampton', OBJECT, 'suburb' );
	if ( $hampton && ! get_page_by_path( 'hampden', OBJECT, 'suburb' ) ) {
		wp_update_post(
			array(
				'ID'          => $hampton->ID,
				'post_name'   => 'hampden',
				'post_title'  => 'Hampden',
				'post_status' => 'publish',
			)
		);
	}

	$hampden = get_page_by_path( 'hampden', OBJECT, 'suburb' );
	if ( $hampden && function_exists( 'update_field' ) ) {
		$name = 'Hampden';
		wp_update_post(
			array(
				'ID'           => $hampden->ID,
				'post_title'   => $name,
				'post_excerpt' => 'Interior, exterior & cabinet painting in ' . $name,
			)
		);
		update_field( 'suburb_card_excerpt', 'Interior, exterior & cabinet painting in ' . $name, $hampden->ID );
		update_field( 'suburb_about_heading', 'About Our Work in ' . $name, $hampden->ID );
		update_field( 'suburb_description', implode( "\n\n", superb_suburb_default_description( $name ) ), $hampden->ID );
		update_field( 'work_summary', superb_suburb_default_work_summary( $name ), $hampden->ID );
		update_field( 'suburb_gallery_heading', 'Our Work in ' . $name, $hampden->ID );
		update_field( 'suburb_cta_heading', 'Need a painter in ' . $name . '? Let\'s talk.', $hampden->ID );
	}

	$peninsula = get_page_by_path( 'peninsula-surrounds', OBJECT, 'suburb' );
	if ( $peninsula ) {
		wp_update_post(
			array(
				'ID'         => $peninsula->ID,
				'post_title' => 'Peninsula & Around',
			)
		);
	}

	$special = get_page_by_path( 'special-finishes', OBJECT, 'service' );
	if ( $special ) {
		superb_force_refresh_service_definition( $special->ID, 'special-finishes' );
	}
}

/**
 * Overwrite service ACF content from theme definitions (for content updates).
 *
 * @param int    $post_id Post ID.
 * @param string $slug    Service slug.
 */
function superb_force_refresh_service_definition( $post_id, $slug ) {
	if ( ! function_exists( 'update_field' ) || ! function_exists( 'superb_get_service' ) ) {
		return;
	}

	$def = superb_get_service( $slug );
	if ( ! $def ) {
		return;
	}

	if ( ! empty( $def['title'] ) ) {
		wp_update_post(
			array(
				'ID'         => $post_id,
				'post_title' => $def['title'],
			)
		);
	}

	if ( ! empty( $def['tagline'] ) ) {
		update_field( 'service_tagline', $def['tagline'], $post_id );
	}

	if ( ! empty( $def['intro'] ) ) {
		update_field( 'service_intro', $def['intro'], $post_id );
		update_field( 'service_card_excerpt', wp_trim_words( $def['intro'], 24, '…' ), $post_id );
	}

	if ( ! empty( $def['included'] ) ) {
		update_field( 'service_features_list', superb_lines_to_textarea( $def['included'] ), $post_id );
	}

	if ( ! empty( $def['faq'] ) ) {
		foreach ( $def['faq'] as $index => $item ) {
			if ( $index >= superb_service_faq_count() ) {
				break;
			}
			$n = $index + 1;
			update_field( 'service_faq_' . $n . '_question', $item['q'] ?? '', $post_id );
			update_field( 'service_faq_' . $n . '_answer', $item['a'] ?? '', $post_id );
		}
	}
}

/**
 * Run CPT ACF backfill once per theme version (after ACF field groups load).
 */
function superb_maybe_backfill_cms_acf() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}
	$ran_version = get_option( 'superb_cms_backfill_version', '' );
	if ( $ran_version === SUPERB_THEME_VERSION ) {
		return;
	}
	superb_migrate_legacy_cpt_records();
	superb_backfill_all_cpt_acf_fields();
	update_option( 'superb_cms_backfill_version', SUPERB_THEME_VERSION );
}
add_action( 'acf/init', 'superb_maybe_backfill_cms_acf', 25 );

/**
 * Ensure intro/description text fields are populated (handles broken empty WYSIWYG saves).
 *
 * @param int    $post_id Post ID.
 * @param string $field   Field name.
 * @param string $value   Plain-text value.
 */
function superb_ensure_text_field_content( $post_id, $field, $value ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}
	if ( superb_field_is_empty( $value ) ) {
		return;
	}
	$current = get_field( $field, $post_id );
	if ( ! superb_field_is_empty( $current ) ) {
		return;
	}
	update_field( $field, $value, $post_id );
}
