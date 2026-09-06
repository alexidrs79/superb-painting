<?php
/**
 * Single Service Template
 *
 * @package Kadence_Child_Superb
 */

get_header();

$post_id    = get_the_ID();
$title      = get_the_title();
$service_cta = superb_get_service_cta_defaults();

$tagline      = get_field( 'service_tagline' );
$intro        = get_field( 'service_intro' );
$included     = superb_get_service_features_list( $post_id );
$steps        = superb_get_service_steps( $post_id );
$faq_rows     = superb_get_service_faq_rows( $post_id );
$before_image = get_field( 'service_before_image' );
$after_image  = get_field( 'service_after_image' );
$before_url   = superb_acf_image_url( $before_image, 'large' );
$after_url    = superb_acf_image_url( $after_image, 'large' );
$hero_url     = superb_service_hero_image_url_for_post( $post_id );
?>

<section class="page-hero page-hero--cover page-hero--featured" style="--hero-bg-image: url('<?php echo esc_url( $hero_url ); ?>');">
	<div class="page-hero__overlay" aria-hidden="true"></div>
	<div class="page-hero__inner">
		<?php
		superb_breadcrumb(
			array(
				array( 'label' => 'Home', 'url' => home_url( '/' ) ),
				array( 'label' => 'Services', 'url' => superb_services_page_url() ),
				array( 'label' => $title, 'url' => '' ),
			)
		);
		?>
		<h1><?php echo esc_html( $title ); ?></h1>
		<?php if ( $tagline ) : ?>
			<p><?php echo esc_html( $tagline ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="superb-section superb-section--white superb-two-col-section">
	<div class="superb-container">
		<div class="superb-two-col superb-two-col--65-35">
			<main class="superb-content">
				<?php
				$intro_html = superb_render_wysiwyg_content( $intro );
				if ( $intro_html ) :
					?>
					<div class="service-intro"><?php echo $intro_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- kses in helper. ?></div>
				<?php endif; ?>

				<?php if ( $included ) : ?>
					<h2>What's Included</h2>
					<ul class="superb-feature-list">
						<?php foreach ( $included as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $steps ) : ?>
					<h2>Our Process</h2>
					<div class="our-process__steps">
						<?php foreach ( $steps as $i => $step ) : ?>
							<div class="process-step fade-in-up">
								<div class="process-step__number"><?php echo (int) ( $i + 1 ); ?></div>
								<h3><?php echo esc_html( $step['step_title'] ?? '' ); ?></h3>
								<p><?php echo esc_html( $step['step_desc'] ?? '' ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $before_url && $after_url ) : ?>
					<h2>Before &amp; After</h2>
					<div class="before-after">
						<div>
							<img src="<?php echo esc_url( $before_url ); ?>" alt="Before — <?php echo esc_attr( $title ); ?>" loading="lazy">
							<span>Before</span>
						</div>
						<div>
							<img src="<?php echo esc_url( $after_url ); ?>" alt="After — <?php echo esc_attr( $title ); ?>" loading="lazy">
							<span>After</span>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $faq_rows ) : ?>
					<h2>Frequently Asked Questions</h2>
					<?php foreach ( $faq_rows as $faq ) : ?>
						<div class="faq-item">
							<h4><?php echo esc_html( $faq['faq_question'] ?? '' ); ?></h4>
							<p><?php echo esc_html( $faq['faq_answer'] ?? '' ); ?></p>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</main>

			<aside class="superb-sidebar">
				<div class="superb-sidebar-card">
					<?php echo do_shortcode( '[superb_quote_form variant="sidebar"]' ); ?>
				</div>
			</aside>
		</div>
	</div>
</section>

<section class="trust-badges trust-badges--logos">
	<div class="trust-badge trust-badge--logo">
		<div class="trust-badge__icon"><?php echo superb_credential_logo_img( 'master-painters-mpa', array( 'class' => 'superb-credential-logo superb-credential-logo--mpa' ) ); ?></div>
		<p>Master Painters Member</p>
	</div>
	<div class="trust-badge trust-badge--logo">
		<div class="trust-badge__icon"><?php echo superb_credential_logo_img( 'dulux-accredited', array( 'class' => 'superb-credential-logo superb-credential-logo--dulux' ) ); ?></div>
		<p>Dulux Accredited</p>
	</div>
	<div class="trust-badge">
		<div class="trust-badge__icon"><?php echo superb_trust_icon( 'insured' ); ?></div>
		<p>$20M Insured</p>
	</div>
</section>

<?php superb_render_other_service_cards( $post_id, 5 ); ?>

<section class="cta-banner cta-banner--gold">
	<h2><?php echo esc_html( $service_cta['heading'] ); ?></h2>
	<p><?php echo esc_html( $service_cta['text'] ); ?></p>
	<a href="<?php echo esc_url( home_url( '/contact/#quote-form' ) ); ?>" class="superb-btn superb-btn-charcoal superb-quote-cta"><?php echo esc_html( $service_cta['button'] ); ?></a>
</section>

<?php
get_footer();
