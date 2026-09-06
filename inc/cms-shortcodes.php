<?php
/**
 * Dynamic listing shortcodes for Services & Suburbs pages.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * [superb_suburbs_grid]
 */
function superb_shortcode_suburbs_grid() {
	$query = new WP_Query(
		array(
			'post_type'      => 'suburb',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	ob_start();
	echo '<section class="suburbs-preview superb-section superb-section--tight-top">';
	echo '<div class="superb-container">';
	echo '<div class="suburbs-preview__grid suburbs-filter-grid">';

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			superb_render_suburb_card( get_the_ID() );
		}
		wp_reset_postdata();
	} else {
		superb_render_placeholder_suburb_cards( 12 );
	}

	echo '</div></div></section>';
	return ob_get_clean();
}

/**
 * Render one suburb card.
 *
 * @param int $post_id Post ID.
 */
function superb_render_suburb_card( $post_id ) {
	$title   = get_the_title( $post_id );
	$excerpt = superb_get_suburb_card_excerpt( $post_id );
	$url     = get_permalink( $post_id );
	?>
	<article class="suburb-card fade-in-up">
		<a href="<?php echo esc_url( $url ); ?>">
			<?php if ( has_post_thumbnail( $post_id ) ) : ?>
				<?php echo get_the_post_thumbnail( $post_id, 'suburb-card', array( 'class' => 'suburb-card__img', 'loading' => 'lazy' ) ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( superb_suburb_card_image_url( $post_id ) ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="suburb-card__img" loading="lazy">
			<?php endif; ?>
			<h3 class="suburb-card__title"><?php echo esc_html( $title ); ?></h3>
			<p class="suburb-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
			<span class="suburb-card__link">View Our Work</span>
		</a>
	</article>
	<?php
}

/**
 * [superb_services_grid]
 */
function superb_shortcode_services_grid() {
	$query = new WP_Query(
		array(
			'post_type'      => 'service',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		)
	);

	ob_start();
	echo '<section class="services-grid services-grid--listing superb-section superb-section--muted">';
	echo '<div class="superb-container">';
	echo '<h2 class="superb-section-title">Choose your service</h2>';
	echo '<p class="superb-section-subtitle">Six specialist services — same crew, same standards, same care on every job.</p>';
	echo '<div class="services-grid__cards">';

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			superb_render_service_listing_card( get_the_ID() );
		}
		wp_reset_postdata();
	}

	echo '</div></div></section>';
	return ob_get_clean();
}

/**
 * Render service listing card.
 *
 * @param int $post_id Post ID.
 */
function superb_render_service_listing_card( $post_id ) {
	$icon    = superb_get_service_icon( $post_id );
	$excerpt = superb_get_service_card_excerpt( $post_id );
	$slug    = get_post_field( 'post_name', $post_id );
	$url     = function_exists( 'superb_get_service_link' ) ? superb_get_service_link( $slug ) : get_permalink( $post_id );
	$target  = ( function_exists( 'superb_get_service_external_url' ) && superb_get_service_external_url( $slug ) ) ? ' target="_blank" rel="noopener noreferrer"' : '';
	$title   = get_the_title( $post_id );
	?>
	<article class="service-card service-card--listing fade-in-up">
		<div class="service-card__icon"><?php echo superb_icon( $icon, array( 'size' => 28 ) ); ?></div>
		<h3><?php echo esc_html( $title ); ?></h3>
		<?php if ( $excerpt ) : ?>
			<p><?php echo esc_html( $excerpt ); ?></p>
		<?php endif; ?>
		<div class="service-card__actions">
			<a href="<?php echo esc_url( $url ); ?>" class="superb-link"<?php echo $target; ?>><?php echo esc_html( superb_service_link_label( $title ) ); ?></a>
			<a href="<?php echo esc_url( home_url( '/contact/#quote-form' ) ); ?>" class="superb-btn superb-btn-outline-navy btn-quote-scroll">Get a Quote</a>
		</div>
	</article>
	<?php
}

/**
 * [superb_services_showcase]
 */
function superb_shortcode_services_showcase() {
	$query = new WP_Query(
		array(
			'post_type'      => 'service',
			'posts_per_page' => 3,
			'meta_key'       => 'service_showcase_featured',
			'meta_value'     => '1',
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		)
	);

	if ( ! $query->have_posts() ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'service',
				'posts_per_page' => 3,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
			)
		);
	}

	ob_start();
	echo '<section class="service-showcase superb-section superb-section--white">';
	echo '<div class="superb-container">';
	echo '<div class="service-showcase__header">';
	echo '<p class="superb-eyebrow">Featured work</p>';
	echo '<h2 class="superb-section-title superb-section-title--left">See what we deliver</h2>';
	echo '<p class="service-showcase__intro">A closer look at three of our most popular services — real finishes, real prep, real results.</p>';
	echo '</div>';

	$i = 0;
	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id         = get_the_ID();
		$reverse         = ( 1 === $i % 2 ) ? ' service-showcase__row--reverse' : '';
		$hero            = superb_service_hero_image_url_for_post( $post_id );
		$intro           = function_exists( 'get_field' ) ? get_field( 'service_intro', $post_id ) : '';
		$feature_strings = superb_get_service_features_list( $post_id );
		if ( count( $feature_strings ) > 3 ) {
			$feature_strings = array_slice( $feature_strings, 0, 3 );
		}
		?>
		<div class="service-showcase__row<?php echo esc_attr( $reverse ); ?> fade-in-up">
			<div class="service-showcase__media">
				<img src="<?php echo esc_url( $hero ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" loading="lazy">
			</div>
			<div class="service-showcase__content">
				<h3><?php echo esc_html( get_the_title( $post_id ) ); ?></h3>
				<?php if ( $intro ) : ?>
					<p><?php echo esc_html( wp_strip_all_tags( $intro ) ); ?></p>
				<?php endif; ?>
				<?php if ( $feature_strings ) : ?>
					<ul class="superb-feature-list superb-feature-list--compact">
						<?php foreach ( $feature_strings as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php
				$slug    = get_post_field( 'post_name', $post_id );
				$url     = function_exists( 'superb_get_service_link' ) ? superb_get_service_link( $slug ) : get_permalink( $post_id );
				$target  = ( function_exists( 'superb_get_service_external_url' ) && superb_get_service_external_url( $slug ) ) ? ' target="_blank" rel="noopener noreferrer"' : '';
				?>
				<div class="service-card__actions">
					<a href="<?php echo esc_url( $url ); ?>" class="superb-link"<?php echo $target; ?>><?php echo esc_html( superb_service_link_label( get_the_title( $post_id ) ) ); ?></a>
					<a href="<?php echo esc_url( home_url( '/contact/#quote-form' ) ); ?>" class="superb-btn superb-btn-orange btn-quote-scroll">Get a Quote</a>
				</div>
			</div>
		</div>
		<?php
		++$i;
	}
	wp_reset_postdata();

	echo '</div></section>';
	return ob_get_clean();
}

add_action(
	'init',
	function () {
		add_shortcode( 'superb_suburbs_grid', 'superb_shortcode_suburbs_grid' );
		add_shortcode( 'superb_services_grid', 'superb_shortcode_services_grid' );
		add_shortcode( 'superb_services_showcase', 'superb_shortcode_services_showcase' );
	}
);
