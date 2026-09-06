<?php
/**
 * ACF field groups for editable Services & Suburbs CPT content.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Individual image fields for suburb gallery (compatible with free ACF).
 *
 * @return array<int, array<string, mixed>>
 */
function superb_acf_suburb_gallery_photo_fields() {
	$fields = array();
	$count  = function_exists( 'superb_suburb_gallery_photo_count' ) ? superb_suburb_gallery_photo_count() : 6;

	for ( $i = 1; $i <= $count; $i++ ) {
		$fields[] = array(
			'key'           => 'field_suburb_gallery_photo_' . $i,
			'label'         => 'Gallery Photo ' . $i,
			'name'          => 'suburb_gallery_photo_' . $i,
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'library'       => 'all',
			'instructions'  => 1 === $i
				? 'Upload up to 6 photos. Leave a slot empty to hide it on the front end. Photos appear left-to-right in order.'
				: '',
		);
	}

	return $fields;
}

/**
 * Process step fields for service pages (free ACF compatible).
 *
 * @return array<int, array<string, mixed>>
 */
function superb_acf_service_step_fields() {
	$fields = array();
	for ( $i = 1; $i <= ( function_exists( 'superb_service_step_count' ) ? superb_service_step_count() : 5 ); $i++ ) {
		$fields[] = array(
			'key'          => 'field_service_step_' . $i . '_title',
			'label'        => 'Step ' . $i . ' — Title',
			'name'         => 'service_step_' . $i . '_title',
			'type'         => 'text',
			'instructions' => 1 === $i ? 'Shown under the “Our Process” heading. Leave both title and description empty to hide a step.' : '',
		);
		$fields[] = array(
			'key'   => 'field_service_step_' . $i . '_desc',
			'label' => 'Step ' . $i . ' — Description',
			'name'  => 'service_step_' . $i . '_desc',
			'type'  => 'textarea',
			'rows'  => 2,
		);
	}
	return $fields;
}

/**
 * FAQ fields for service pages (free ACF compatible).
 *
 * @return array<int, array<string, mixed>>
 */
function superb_acf_service_faq_fields() {
	$fields = array();
	for ( $i = 1; $i <= ( function_exists( 'superb_service_faq_count' ) ? superb_service_faq_count() : 6 ); $i++ ) {
		$fields[] = array(
			'key'          => 'field_service_faq_' . $i . '_question',
			'label'        => 'FAQ ' . $i . ' — Question',
			'name'         => 'service_faq_' . $i . '_question',
			'type'         => 'text',
			'instructions' => 1 === $i ? 'Shown under “Frequently Asked Questions”. Leave both fields empty to hide an item.' : '',
		);
		$fields[] = array(
			'key'   => 'field_service_faq_' . $i . '_answer',
			'label' => 'FAQ ' . $i . ' — Answer',
			'name'  => 'service_faq_' . $i . '_answer',
			'type'  => 'textarea',
			'rows'  => 3,
		);
	}
	return $fields;
}

/**
 * Register local ACF field groups.
 */
function superb_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'    => 'group_superb_suburb',
			'title'  => 'Suburb Page Content',
			'fields' => array_merge(
				array(
				array( 'key' => 'field_suburb_tab_hero', 'label' => 'Hero', 'name' => '', 'type' => 'tab' ),
				array(
					'key'          => 'field_suburb_area',
					'label'        => 'Hero Subtitle',
					'name'         => 'suburb_area',
					'type'         => 'text',
					'instructions' => 'Shown under the hero title. Post title = H1; Featured Image = hero background.',
					'placeholder'  => 'e.g. Inner East Melbourne',
				),
				array( 'key' => 'field_suburb_tab_about', 'label' => 'About & Services', 'name' => '', 'type' => 'tab' ),
				array(
					'key'          => 'field_suburb_about_heading',
					'label'        => 'About Our Work — Heading',
					'name'         => 'suburb_about_heading',
					'type'         => 'text',
					'instructions' => 'Section heading on the live page. Leave blank to use “About Our Work in [Post Title]”.',
					'placeholder'  => 'About Our Work in Toorak',
				),
				array(
					'key'          => 'field_suburb_description',
					'label'        => 'About Our Work — Body Copy',
					'name'         => 'suburb_description',
					'type'         => 'textarea',
					'rows'         => 10,
					'instructions' => 'Paragraphs shown under the About heading. Separate paragraphs with a blank line.',
				),
				array(
					'key'          => 'field_suburb_services_list',
					'label'        => 'Services List',
					'name'         => 'suburb_services_list',
					'type'         => 'textarea',
					'rows'         => 8,
					'instructions' => 'One service per line. Shown as bullet points under “Services We Offer in [Post Title]”.',
					'placeholder'  => "Interior wall & ceiling painting\nKitchen cabinet painting\nFeature walls",
				),
				array(
					'key'          => 'field_suburb_local_copy',
					'label'        => 'Why Choose a Local Painter',
					'name'         => 'suburb_local_copy',
					'type'         => 'textarea',
					'rows'         => 4,
					'instructions' => 'Shown under the “Why Choose a Local Painter?” heading on the live page.',
				),
				array( 'key' => 'field_suburb_tab_work', 'label' => 'Our Work', 'name' => '', 'type' => 'tab' ),
				array(
					'key'          => 'field_work_summary',
					'label'        => 'Work Summary',
					'name'         => 'work_summary',
					'type'         => 'textarea',
					'rows'         => 4,
					'instructions' => 'Intro text shown above the photo gallery on the live page.',
				),
				array( 'key' => 'field_suburb_tab_gallery', 'label' => 'Gallery', 'name' => '', 'type' => 'tab' ),
				array(
					'key'          => 'field_suburb_gallery_heading',
					'label'        => 'Gallery Section Heading',
					'name'         => 'suburb_gallery_heading',
					'type'         => 'text',
					'instructions' => 'Optional. Leave blank to use “Our Work in [Post Title]” on the live page.',
					'placeholder'  => 'Our Work in Toorak',
				),
				),
				superb_acf_suburb_gallery_photo_fields(),
				array(
				array( 'key' => 'field_suburb_tab_stats', 'label' => 'Stats', 'name' => '', 'type' => 'tab' ),
				array( 'key' => 'field_suburb_job_count', 'label' => 'Jobs Completed', 'name' => 'suburb_job_count', 'type' => 'text', 'default_value' => '50+', 'instructions' => 'First number in the stats strip on the live page.' ),
				array( 'key' => 'field_suburb_satisfaction', 'label' => 'Satisfaction Rate', 'name' => 'suburb_satisfaction', 'type' => 'text', 'default_value' => '100%', 'instructions' => 'Second number in the stats strip on the live page.' ),
				array( 'key' => 'field_suburb_response_time', 'label' => 'Response Time', 'name' => 'suburb_response_time', 'type' => 'text', 'default_value' => 'Under 2 hours', 'instructions' => 'Third number in the stats strip on the live page.' ),
				)
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'suburb',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'    => 'group_superb_service',
			'title'  => 'Service Page Content',
			'fields' => array_merge(
				array(
				array( 'key' => 'field_service_tab_hero', 'label' => 'Hero', 'name' => '', 'type' => 'tab' ),
				array(
					'key'          => 'field_service_tagline',
					'label'        => 'Hero Tagline',
					'name'         => 'service_tagline',
					'type'         => 'text',
					'instructions' => 'Shown under the service title on the live hero. Hero background uses Featured Image (sidebar).',
				),
				array( 'key' => 'field_service_tab_intro', 'label' => 'Intro', 'name' => '', 'type' => 'tab' ),
				array(
					'key'          => 'field_service_intro',
					'label'        => 'Introduction',
					'name'         => 'service_intro',
					'type'         => 'textarea',
					'rows'         => 8,
					'instructions' => 'Shown at the top of the main content. Separate paragraphs with a blank line.',
				),
				array( 'key' => 'field_service_tab_features', 'label' => "What's Included", 'name' => '', 'type' => 'tab' ),
				array(
					'key'          => 'field_service_features_list',
					'label'        => 'Included Features',
					'name'         => 'service_features_list',
					'type'         => 'textarea',
					'rows'         => 10,
					'instructions' => 'One feature per line. Shown under “What\'s Included” on the live page.',
					'placeholder'  => "Full surface preparation\nPremium low-VOC paint\nTwo coats minimum",
				),
				array( 'key' => 'field_service_tab_process', 'label' => 'Process', 'name' => '', 'type' => 'tab' ),
				),
				superb_acf_service_step_fields(),
				array(
				array( 'key' => 'field_service_tab_before_after', 'label' => 'Before & After', 'name' => '', 'type' => 'tab' ),
				array( 'key' => 'field_service_before_image', 'label' => 'Before Image', 'name' => 'service_before_image', 'type' => 'image', 'return_format' => 'array', 'instructions' => 'Left image under “Before & After” on the live page.' ),
				array( 'key' => 'field_service_after_image', 'label' => 'After Image', 'name' => 'service_after_image', 'type' => 'image', 'return_format' => 'array', 'instructions' => 'Right image under “Before & After” on the live page.' ),
				array( 'key' => 'field_service_tab_faq', 'label' => 'FAQ', 'name' => '', 'type' => 'tab' ),
				),
				superb_acf_service_faq_fields()
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'    => 'group_superb_page_lock',
			'title'  => 'Page Sync',
			'fields' => array(
				array(
					'key'          => 'field_superb_page_locked',
					'label'        => 'Lock page content',
					'name'         => '_superb_page_locked',
					'type'         => 'true_false',
					'ui'           => 1,
					'instructions' => 'When enabled, theme updates will not overwrite this page block content.',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'page-templates/template-superb-fullwidth.php',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'superb_register_acf_fields' );

/**
 * Featured image labels for suburb/service CPTs.
 *
 * @param string $translated Translated text.
 * @param string $text       Original text.
 * @param string $domain     Text domain.
 */
function superb_filter_featured_image_label( $translated, $text, $domain ) {
	if ( 'default' !== $domain && 'post' !== $domain ) {
		return $translated;
	}
	$labels = array(
		'Featured image'        => 'Hero / Card Image',
		'Set featured image'    => 'Set hero / card image',
		'Remove featured image' => 'Remove hero / card image',
		'Use as featured image' => 'Use as hero / card image',
	);
	if ( ! isset( $labels[ $text ] ) ) {
		return $translated;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && in_array( $screen->post_type, array( 'suburb', 'service' ), true ) ) {
		return $labels[ $text ];
	}
	return $translated;
}
add_filter( 'gettext', 'superb_filter_featured_image_label', 10, 3 );

/**
 * Persist page lock meta when saved via ACF.
 *
 * @param int $post_id Post ID.
 */
function superb_sync_page_lock_meta( $post_id ) {
	if ( ! function_exists( 'get_field' ) || 'page' !== get_post_type( $post_id ) ) {
		return;
	}
	$locked = get_field( '_superb_page_locked', $post_id );
	if ( $locked ) {
		update_post_meta( $post_id, '_superb_page_locked', '1' );
	} else {
		delete_post_meta( $post_id, '_superb_page_locked' );
	}
}
add_action( 'acf/save_post', 'superb_sync_page_lock_meta', 20 );
