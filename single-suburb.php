<?php
/**
 * Single Suburb Template
 *
 * @package Kadence_Child_Superb
 */

get_header();

$post_id     = get_the_ID();
$suburb_slug = get_post_field( 'post_name', $post_id );
$suburb_name = get_the_title();

$suburb_area     = get_field( 'suburb_area' );
$about_heading   = get_field( 'suburb_about_heading' );
$description     = get_field( 'suburb_description' );
$work_summary    = get_field( 'work_summary' );
$gallery_heading = get_field( 'suburb_gallery_heading' );
$local           = get_field( 'suburb_local_copy' );
$job_count       = get_field( 'suburb_job_count' );
$satisfaction    = get_field( 'suburb_satisfaction' );
$response_time   = get_field( 'suburb_response_time' );
$content         = superb_get_suburb_content( $suburb_slug );
$services        = superb_get_suburb_services_list( $post_id );
$suburb_cta      = superb_get_suburb_cta_defaults( $post_id );

if ( ! $services ) {
	$services = ( $content && ! empty( $content['services'] ) ) ? $content['services'] : superb_suburb_services_list();
}

if ( superb_field_is_empty( $local ) && $content && ! empty( $content['local'] ) ) {
	$local = $content['local'];
}

$hero_url = superb_suburb_hero_image_url( $post_id );
?>

<section class="page-hero page-hero--cover page-hero--featured" style="--hero-bg-image: url('<?php echo esc_url( $hero_url ); ?>');">
	<div class="page-hero__overlay" aria-hidden="true"></div>
	<div class="page-hero__inner">
		<?php
		superb_breadcrumb(
			array(
				array( 'label' => 'Home', 'url' => home_url( '/' ) ),
				array( 'label' => 'Suburbs', 'url' => superb_suburbs_page_url() ),
				array( 'label' => $suburb_name, 'url' => '' ),
			)
		);
		?>
		<h1>Interior &amp; Exterior Painting in <?php echo esc_html( $suburb_name ); ?></h1>
		<?php if ( $suburb_area ) : ?>
			<p><?php echo esc_html( $suburb_area ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="superb-section superb-section--white superb-two-col-section">
	<div class="superb-container">
		<div class="superb-two-col superb-two-col--65-35">
			<main class="superb-content">
				<?php if ( $about_heading || $description ) : ?>
					<h2><?php echo esc_html( $about_heading ?: 'About Our Work in ' . $suburb_name ); ?></h2>
					<?php
					$about_html = superb_render_wysiwyg_content( $description );
					if ( $about_html ) {
						echo $about_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- kses in helper.
					}
					?>
				<?php endif; ?>

				<?php if ( $services ) : ?>
					<h2>Services We Offer in <?php echo esc_html( $suburb_name ); ?></h2>
					<ul class="superb-feature-list">
						<?php foreach ( $services as $service ) : ?>
							<li><?php echo esc_html( $service ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $local ) : ?>
					<h2>Why Choose a Local Painter?</h2>
					<p><?php echo esc_html( $local ); ?></p>
				<?php endif; ?>

				<?php if ( $gallery_heading || $work_summary || superb_suburb_gallery_has_photos( $post_id ) ) : ?>
					<h2><?php echo esc_html( $gallery_heading ?: 'Our Work in ' . $suburb_name ); ?></h2>
					<?php if ( $work_summary ) : ?>
						<?php echo wp_kses_post( wpautop( $work_summary ) ); ?>
					<?php endif; ?>
					<?php superb_render_suburb_gallery( $post_id ); ?>
				<?php endif; ?>
			</main>

			<aside class="superb-sidebar">
				<div class="superb-sidebar-card">
					<?php echo do_shortcode( '[superb_quote_form variant="sidebar" suburb="' . esc_attr( $suburb_name ) . '"]' ); ?>
				</div>
				<div class="superb-sidebar-card">
					<h3>Opening Hours</h3>
					<dl class="opening-hours">
						<dt>Monday – Sunday</dt>
						<dd>7:00am – 7:00pm</dd>
					</dl>
				</div>
				<div class="superb-sidebar-card">
					<a href="<?php echo esc_url( SUPERB_PHONE_LINK ); ?>" class="superb-btn superb-btn-orange superb-btn-full">
						Call <?php echo esc_html( SUPERB_PHONE ); ?>
					</a>
				</div>
			</aside>
		</div>
	</div>
</section>

<?php if ( $job_count || $satisfaction || $response_time ) : ?>
<section class="suburb-stats-strip">
	<div class="suburb-stats-strip__grid">
		<?php if ( $job_count ) : ?>
		<div>
			<div class="suburb-stats-strip__number"><?php echo esc_html( $job_count ); ?></div>
			<div class="suburb-stats-strip__label">Jobs in <?php echo esc_html( $suburb_name ); ?></div>
		</div>
		<?php endif; ?>
		<?php if ( $satisfaction ) : ?>
		<div>
			<div class="suburb-stats-strip__number"><?php echo esc_html( $satisfaction ); ?></div>
			<div class="suburb-stats-strip__label">Client Satisfaction</div>
		</div>
		<?php endif; ?>
		<?php if ( $response_time ) : ?>
		<div>
			<div class="suburb-stats-strip__number"><?php echo esc_html( $response_time ); ?></div>
			<div class="suburb-stats-strip__label">Average Response</div>
		</div>
		<?php endif; ?>
	</div>
</section>
<?php endif; ?>

<section class="suburbs-preview superb-section">
	<div class="superb-container">
		<h2 class="superb-section-title">Nearby Suburbs We Also Serve</h2>
		<?php superb_render_nearby_suburb_cards( $suburb_slug ); ?>
	</div>
</section>

<section class="cta-banner cta-banner--gold">
	<h2><?php echo esc_html( $suburb_cta['heading'] ); ?></h2>
	<p><?php echo esc_html( $suburb_cta['text'] ); ?></p>
	<a href="<?php echo esc_url( home_url( '/contact/#quote-form' ) ); ?>" class="superb-btn superb-btn-charcoal superb-quote-cta"><?php echo esc_html( $suburb_cta['button'] ); ?></a>
</section>

<?php
get_footer();
