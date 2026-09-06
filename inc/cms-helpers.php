<?php
/**
 * CMS helpers — ACF empty checks, field fallbacks, card copy.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether a post has completed one-time content seeding.
 *
 * @param int $post_id Post ID.
 */
function superb_is_content_seeded( $post_id ) {
	return (bool) get_post_meta( $post_id, '_superb_content_seeded', true );
}

/**
 * Mark post as seeded.
 *
 * @param int $post_id Post ID.
 */
function superb_mark_content_seeded( $post_id ) {
	update_post_meta( $post_id, '_superb_content_seeded', '1' );
}

/**
 * Whether a marketing page should skip auto content sync.
 *
 * @param int $page_id Page ID.
 */
function superb_is_page_content_locked( $page_id ) {
	return (bool) get_post_meta( $page_id, '_superb_page_locked', true );
}

/**
 * Whether an ACF/meta field is empty.
 *
 * @param mixed $value Field value.
 */
function superb_field_is_empty( $value ) {
	if ( is_array( $value ) ) {
		return empty( $value );
	}
	if ( is_string( $value ) ) {
		return '' === trim( wp_strip_all_tags( $value ) );
	}
	return null === $value || false === $value;
}

/**
 * Update ACF field only when currently empty.
 *
 * @param int    $post_id Post ID.
 * @param string $field   Field name.
 * @param mixed  $value   Value to set.
 */
function superb_update_field_if_empty( $post_id, $field, $value ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return false;
	}
	$current = get_field( $field, $post_id );
	if ( ! superb_field_is_empty( $current ) ) {
		return false;
	}
	if ( superb_field_is_empty( $value ) ) {
		return false;
	}
	update_field( $field, $value, $post_id );
	return true;
}

/**
 * Get suburb card excerpt for grids.
 *
 * @param int $post_id Suburb post ID.
 */
function superb_get_suburb_card_excerpt( $post_id ) {
	$custom = function_exists( 'get_field' ) ? get_field( 'suburb_card_excerpt', $post_id ) : '';
	if ( ! superb_field_is_empty( $custom ) ) {
		return $custom;
	}
	$excerpt = get_post_field( 'post_excerpt', $post_id );
	if ( ! superb_field_is_empty( $excerpt ) ) {
		return $excerpt;
	}
	return 'Interior, exterior & cabinet painting in ' . get_the_title( $post_id );
}

/**
 * Get service card excerpt for listing grid.
 *
 * @param int $post_id Service post ID.
 */
function superb_get_service_card_excerpt( $post_id ) {
	$custom = function_exists( 'get_field' ) ? get_field( 'service_card_excerpt', $post_id ) : '';
	if ( ! superb_field_is_empty( $custom ) ) {
		return $custom;
	}
	$excerpt = get_post_field( 'post_excerpt', $post_id );
	if ( ! superb_field_is_empty( $excerpt ) ) {
		return $excerpt;
	}
	$intro = function_exists( 'get_field' ) ? get_field( 'service_intro', $post_id ) : '';
	if ( ! superb_field_is_empty( $intro ) ) {
		return wp_trim_words( wp_strip_all_tags( $intro ), 24, '…' );
	}
	return '';
}

/**
 * Service listing icon name (Lucide).
 *
 * @param int $post_id Service post ID.
 */
function superb_get_service_icon( $post_id ) {
	$icon = function_exists( 'get_field' ) ? get_field( 'service_icon', $post_id ) : '';
	if ( ! superb_field_is_empty( $icon ) ) {
		return sanitize_html_class( $icon );
	}
	$slug = get_post_field( 'post_name', $post_id );
	$map  = array(
		'interior-wall-painting'   => 'paint-roller',
		'kitchen-cabinet-painting' => 'layout-grid',
		'special-finishes'         => 'sparkles',
		'apartment-painting'       => 'building-2',
		'new-build-painting'       => 'hammer',
		'commercial-painting'      => 'briefcase',
	);
	return $map[ $slug ] ?? 'paint-roller';
}

/**
 * Hero image URL for a service post (featured image → theme pool).
 *
 * @param int $post_id Service post ID.
 */
function superb_service_hero_image_url_for_post( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		$url = get_the_post_thumbnail_url( $post_id, 'hero-bg' );
		if ( $url ) {
			return $url;
		}
	}
	$slug = get_post_field( 'post_name', $post_id );
	return superb_service_hero_image_url( $slug );
}

/**
 * Image URL from ACF image field (array or ID).
 *
 * @param mixed  $image  ACF image value.
 * @param string $size   Image size.
 */
function superb_acf_image_url( $image, $size = 'large' ) {
	if ( is_array( $image ) && ! empty( $image['url'] ) ) {
		if ( ! empty( $image['sizes'][ $size ] ) ) {
			return $image['sizes'][ $size ];
		}
		return $image['url'];
	}
	if ( is_numeric( $image ) ) {
		$url = wp_get_attachment_image_url( (int) $image, $size );
		return $url ? $url : '';
	}
	return '';
}

/**
 * Number of editable suburb gallery photo slots.
 */
function superb_suburb_gallery_photo_count() {
	return 6;
}

/**
 * ACF field name for a suburb gallery photo slot.
 *
 * @param int $index 1-based slot index.
 */
function superb_suburb_gallery_photo_field_name( $index ) {
	return 'suburb_gallery_photo_' . max( 1, (int) $index );
}

/**
 * Attachment ID from an ACF image value.
 *
 * @param mixed $image ACF image field value.
 */
function superb_acf_image_attachment_id( $image ) {
	if ( is_numeric( $image ) ) {
		return (int) $image;
	}
	if ( is_array( $image ) && ! empty( $image['ID'] ) ) {
		return (int) $image['ID'];
	}
	return 0;
}

/**
 * Whether a suburb has any gallery photos saved in ACF.
 *
 * @param int $post_id Suburb post ID.
 */
function superb_suburb_gallery_has_photos( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	for ( $i = 1; $i <= superb_suburb_gallery_photo_count(); $i++ ) {
		$image = get_field( superb_suburb_gallery_photo_field_name( $i ), $post_id );
		if ( ! superb_field_is_empty( $image ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Seed suburb gallery from theme pool when empty (editable in admin after).
 *
 * @param int    $post_id Post ID.
 * @param string $name    Suburb name.
 */
function superb_seed_suburb_gallery_if_empty( $post_id, $name ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}

	superb_migrate_suburb_gallery_field( $post_id );

	if ( superb_suburb_gallery_has_photos( $post_id ) ) {
		return;
	}

	for ( $i = 1; $i <= superb_suburb_gallery_photo_count(); $i++ ) {
		$url = superb_image_from_pool( 'gallery', $post_id . '-' . $i );
		if ( ! $url ) {
			continue;
		}
		$attach_id = superb_import_theme_image( $url, 'Painting work in ' . $name );
		if ( $attach_id ) {
			update_field( superb_suburb_gallery_photo_field_name( $i ), $attach_id, $post_id );
		}
	}
}

/**
 * Migrate legacy gallery/repeater data into individual photo fields.
 *
 * @param int $post_id Post ID.
 */
function superb_migrate_suburb_gallery_field( $post_id ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}

	if ( superb_suburb_gallery_has_photos( $post_id ) ) {
		return;
	}

	$attach_ids = array();

	$rows = get_field( 'suburb_gallery', $post_id );
	if ( ! superb_field_is_empty( $rows ) ) {
		foreach ( (array) $rows as $row ) {
			$attach_id = superb_acf_image_attachment_id( $row['gallery_image'] ?? null );
			if ( $attach_id ) {
				$attach_ids[] = $attach_id;
			}
		}
	}

	if ( ! $attach_ids ) {
		$legacy = get_field( 'suburb_images', $post_id );
		if ( ! superb_field_is_empty( $legacy ) ) {
			foreach ( (array) $legacy as $item ) {
				$attach_id = superb_acf_image_attachment_id( $item );
				if ( $attach_id ) {
					$attach_ids[] = $attach_id;
				}
			}
		}
	}

	if ( ! $attach_ids ) {
		return;
	}

	foreach ( $attach_ids as $index => $attach_id ) {
		if ( $index >= superb_suburb_gallery_photo_count() ) {
			break;
		}
		update_field( superb_suburb_gallery_photo_field_name( $index + 1 ), $attach_id, $post_id );
	}
}

/**
 * Normalized gallery rows for suburb template rendering.
 *
 * @param int $post_id Suburb post ID.
 * @return array<int, array{thumb:string,full:string,alt:string}>
 */
function superb_get_suburb_gallery_images( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}

	superb_migrate_suburb_gallery_field( $post_id );

	$out = array();
	for ( $i = 1; $i <= superb_suburb_gallery_photo_count(); $i++ ) {
		$image = get_field( superb_suburb_gallery_photo_field_name( $i ), $post_id );
		if ( superb_field_is_empty( $image ) ) {
			continue;
		}

		$attach_id = superb_acf_image_attachment_id( $image );
		if ( $attach_id ) {
			$thumb = wp_get_attachment_image_url( $attach_id, 'medium_large' );
			$full  = wp_get_attachment_image_url( $attach_id, 'large' );
			if ( ! $thumb ) {
				continue;
			}
			$out[] = array(
				'thumb' => $thumb,
				'full'  => $full ?: $thumb,
				'alt'   => (string) get_post_meta( $attach_id, '_wp_attachment_image_alt', true ),
			);
			continue;
		}

		if ( is_array( $image ) && ! empty( $image['url'] ) ) {
			$out[] = array(
				'thumb' => $image['sizes']['medium_large'] ?? $image['url'],
				'full'  => $image['url'],
				'alt'   => $image['alt'] ?? '',
			);
		}
	}

	return $out;
}

/**
 * Render suburb gallery grid markup.
 *
 * @param int $post_id Suburb post ID.
 */
function superb_render_suburb_gallery( $post_id ) {
	$images = superb_get_suburb_gallery_images( $post_id );
	$name   = get_the_title( $post_id );

	if ( ! $images ) {
		return;
	}

	echo '<div class="suburb-gallery-grid superb-suburb-lightbox">';
	foreach ( $images as $image ) {
		$alt = $image['alt'] ?: 'Work in ' . $name;
		printf(
			'<img src="%s" data-full="%s" alt="%s" loading="lazy">',
			esc_url( $image['thumb'] ),
			esc_url( $image['full'] ),
			esc_attr( $alt )
		);
	}
	echo '</div>';
}

/**
 * Render ACF WYSIWYG or plain-text field content.
 *
 * @param mixed $content Field value.
 */
function superb_render_wysiwyg_content( $content ) {
	if ( superb_field_is_empty( $content ) ) {
		return '';
	}
	$content = (string) $content;
	if ( false !== strpos( $content, '<' ) ) {
		return wp_kses_post( $content );
	}
	return wp_kses_post( wpautop( $content ) );
}

/**
 * Repeater rows as simple string list (legacy migration helper).
 *
 * @param array  $rows     Repeater rows.
 * @param string $sub_key  Sub-field key.
 */
function superb_repeater_to_strings( $rows, $sub_key ) {
	$out = array();
	if ( ! is_array( $rows ) ) {
		return $out;
	}
	foreach ( $rows as $row ) {
		if ( ! empty( $row[ $sub_key ] ) ) {
			$out[] = $row[ $sub_key ];
		}
	}
	return $out;
}

/**
 * Number of editable process step slots on service pages.
 */
function superb_service_step_count() {
	return 5;
}

/**
 * Number of editable FAQ slots on service pages.
 */
function superb_service_faq_count() {
	return 6;
}

/**
 * Split a textarea field into trimmed non-empty lines.
 *
 * @param mixed $text Field value.
 */
function superb_textarea_to_lines( $text ) {
	if ( superb_field_is_empty( $text ) ) {
		return array();
	}
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	$out   = array();
	foreach ( $lines as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$out[] = $line;
		}
	}
	return $out;
}

/**
 * Join lines for textarea ACF fields.
 *
 * @param array<int, string> $lines List items.
 */
function superb_lines_to_textarea( $lines ) {
	if ( ! is_array( $lines ) ) {
		return '';
	}
	return implode( "\n", array_filter( array_map( 'trim', $lines ) ) );
}

/**
 * Migrate legacy suburb_services repeater to textarea list.
 *
 * @param int $post_id Post ID.
 */
function superb_migrate_suburb_services_field( $post_id ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}
	if ( ! superb_field_is_empty( get_field( 'suburb_services_list', $post_id ) ) ) {
		return;
	}
	$rows = get_field( 'suburb_services', $post_id );
	if ( superb_field_is_empty( $rows ) ) {
		return;
	}
	$lines = superb_repeater_to_strings( $rows, 'service_item' );
	if ( $lines ) {
		update_field( 'suburb_services_list', superb_lines_to_textarea( $lines ), $post_id );
	}
}

/**
 * Services bullet list for a suburb (ACF only after migration).
 *
 * @param int $post_id Suburb post ID.
 */
function superb_get_suburb_services_list( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}
	superb_migrate_suburb_services_field( $post_id );
	return superb_textarea_to_lines( get_field( 'suburb_services_list', $post_id ) );
}

/**
 * Migrate legacy service_features repeater to textarea list.
 *
 * @param int $post_id Post ID.
 */
function superb_migrate_service_features_field( $post_id ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}
	if ( ! superb_field_is_empty( get_field( 'service_features_list', $post_id ) ) ) {
		return;
	}
	$rows = get_field( 'service_features', $post_id );
	if ( superb_field_is_empty( $rows ) ) {
		return;
	}
	$lines = superb_repeater_to_strings( $rows, 'feature_item' );
	if ( $lines ) {
		update_field( 'service_features_list', superb_lines_to_textarea( $lines ), $post_id );
	}
}

/**
 * Included features list for a service (ACF only after migration).
 *
 * @param int $post_id Service post ID.
 */
function superb_get_service_features_list( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}
	superb_migrate_service_features_field( $post_id );
	return superb_textarea_to_lines( get_field( 'service_features_list', $post_id ) );
}

/**
 * Migrate legacy service_steps repeater to fixed text fields.
 *
 * @param int $post_id Post ID.
 */
function superb_migrate_service_steps_field( $post_id ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}
	for ( $i = 1; $i <= superb_service_step_count(); $i++ ) {
		if ( ! superb_field_is_empty( get_field( 'service_step_' . $i . '_title', $post_id ) ) ) {
			return;
		}
	}
	$rows = get_field( 'service_steps', $post_id );
	if ( superb_field_is_empty( $rows ) ) {
		return;
	}
	foreach ( (array) $rows as $index => $row ) {
		if ( $index >= superb_service_step_count() ) {
			break;
		}
		$n = $index + 1;
		update_field( 'service_step_' . $n . '_title', $row['step_title'] ?? '', $post_id );
		update_field( 'service_step_' . $n . '_desc', $row['step_desc'] ?? '', $post_id );
	}
}

/**
 * Process steps for a service (ACF only after migration).
 *
 * @param int $post_id Service post ID.
 * @return array<int, array{step_title:string,step_desc:string}>
 */
function superb_get_service_steps( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}
	superb_migrate_service_steps_field( $post_id );
	$out = array();
	for ( $i = 1; $i <= superb_service_step_count(); $i++ ) {
		$title = get_field( 'service_step_' . $i . '_title', $post_id );
		$desc  = get_field( 'service_step_' . $i . '_desc', $post_id );
		if ( superb_field_is_empty( $title ) && superb_field_is_empty( $desc ) ) {
			continue;
		}
		$out[] = array(
			'step_title' => (string) $title,
			'step_desc'  => (string) $desc,
		);
	}
	return $out;
}

/**
 * Migrate legacy service_faq repeater to fixed text fields.
 *
 * @param int $post_id Post ID.
 */
function superb_migrate_service_faq_field( $post_id ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 'update_field' ) ) {
		return;
	}
	for ( $i = 1; $i <= superb_service_faq_count(); $i++ ) {
		if ( ! superb_field_is_empty( get_field( 'service_faq_' . $i . '_question', $post_id ) ) ) {
			return;
		}
	}
	$rows = get_field( 'service_faq', $post_id );
	if ( superb_field_is_empty( $rows ) ) {
		return;
	}
	foreach ( (array) $rows as $index => $row ) {
		if ( $index >= superb_service_faq_count() ) {
			break;
		}
		$n = $index + 1;
		update_field( 'service_faq_' . $n . '_question', $row['faq_question'] ?? '', $post_id );
		update_field( 'service_faq_' . $n . '_answer', $row['faq_answer'] ?? '', $post_id );
	}
}

/**
 * FAQ rows for a service (ACF only after migration).
 *
 * @param int $post_id Service post ID.
 * @return array<int, array{faq_question:string,faq_answer:string}>
 */
function superb_get_service_faq_rows( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}
	superb_migrate_service_faq_field( $post_id );
	$out = array();
	for ( $i = 1; $i <= superb_service_faq_count(); $i++ ) {
		$question = get_field( 'service_faq_' . $i . '_question', $post_id );
		$answer   = get_field( 'service_faq_' . $i . '_answer', $post_id );
		if ( superb_field_is_empty( $question ) && superb_field_is_empty( $answer ) ) {
			continue;
		}
		$out[] = array(
			'faq_question' => (string) $question,
			'faq_answer'   => (string) $answer,
		);
	}
	return $out;
}

/**
 * Run all legacy repeater → free-ACF field migrations for one post.
 *
 * @param int $post_id Post ID.
 */
function superb_migrate_cms_fields( $post_id ) {
	$type = get_post_type( $post_id );
	if ( 'suburb' === $type ) {
		superb_migrate_suburb_services_field( $post_id );
		superb_migrate_suburb_gallery_field( $post_id );
		if ( function_exists( 'get_field' ) && function_exists( 'update_field' ) ) {
			$name    = get_the_title( $post_id );
			$heading = get_field( 'suburb_gallery_heading', $post_id );
			if ( 'Our Work in Hawthorn' === $heading && 'Hawthorn' !== $name ) {
				update_field( 'suburb_gallery_heading', 'Our Work in ' . $name, $post_id );
			}
			if ( superb_field_is_empty( get_field( 'suburb_about_heading', $post_id ) ) ) {
				update_field( 'suburb_about_heading', 'About Our Work in ' . $name, $post_id );
			}
		}
		return;
	}
	if ( 'service' === $type ) {
		superb_migrate_service_features_field( $post_id );
		superb_migrate_service_steps_field( $post_id );
		superb_migrate_service_faq_field( $post_id );
		if ( function_exists( 'get_field' ) && ! has_post_thumbnail( $post_id ) ) {
			$hero = get_field( 'service_hero_image', $post_id );
			$attach_id = superb_acf_image_attachment_id( $hero );
			if ( $attach_id ) {
				set_post_thumbnail( $post_id, $attach_id );
			}
		}
	}
}

/**
 * Migrate all suburb and service CPT posts.
 */
function superb_migrate_all_cms_fields() {
	foreach ( array( 'suburb', 'service' ) as $post_type ) {
		$query = new WP_Query(
			array(
				'post_type'      => $post_type,
				'posts_per_page' => -1,
				'post_status'    => 'any',
				'fields'         => 'ids',
			)
		);
		foreach ( $query->posts as $post_id ) {
			superb_migrate_cms_fields( $post_id );
		}
		wp_reset_postdata();
	}
}

/**
 * Default bottom CTA copy for suburb singles (theme-managed).
 *
 * @param int $post_id Suburb post ID.
 * @return array{heading:string,text:string,button:string}
 */
function superb_get_suburb_cta_defaults( $post_id ) {
	$name = get_the_title( $post_id );
	return array(
		'heading' => 'Need a painter in ' . $name . '? Let\'s talk.',
		'text'    => 'Get a free, no-obligation quote from our local team. We respond within 24 hours.',
		'button'  => 'Get My Free Quote',
	);
}

/**
 * Default bottom CTA copy for service singles (theme-managed).
 *
 * @return array{heading:string,text:string,button:string}
 */
function superb_get_service_cta_defaults() {
	return array(
		'heading' => 'Ready to get started?',
		'text'    => 'Book your free on-site quote today. No obligation, no hidden costs.',
		'button'  => 'Get My Free Quote',
	);
}

/**
 * Render a grid of other service cards (excludes current post).
 *
 * @param int $current_post_id Current service post ID.
 * @param int $limit           Max cards to show.
 */
function superb_render_other_service_cards( $current_post_id, $limit = 5 ) {
	$query = new WP_Query(
		array(
			'post_type'      => 'service',
			'posts_per_page' => $limit,
			'post__not_in'   => array( (int) $current_post_id ),
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		)
	);

	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		return;
	}
	?>
	<section class="other-services superb-section superb-section--muted">
		<div class="superb-container">
			<h2 class="superb-section-title">Other Services We Provide</h2>
			<div class="services-grid__cards other-services__grid">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					if ( function_exists( 'superb_render_service_listing_card' ) ) {
						superb_render_service_listing_card( get_the_ID() );
					}
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
}
