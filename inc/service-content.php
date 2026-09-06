<?php
/**
 * Service page content helper — generates Gutenberg markup for service child pages.
 *
 * @package Kadence_Child_Superb
 */

/**
 * Generate service page block markup.
 *
 * @param array $data Service page data.
 */
function superb_get_service_page_markup( $data ) {
	$defaults = array(
		'slug'        => '',
		'title'       => '',
		'tagline'     => '',
		'included'    => array(),
		'steps'       => array(),
		'faq'         => array(),
		'testimonial' => array(),
		'random'      => 11,
	);

	$data       = array_merge( $defaults, $data );
	$hero_class = superb_service_hero_css_class( $data['slug'] );
	$ba         = superb_before_after_images( $data['random'] );
	ob_start();
	?>
<!-- wp:html -->
<section class="page-hero page-hero--image <?php echo esc_attr( $hero_class ); ?>">
<div class="page-hero__inner">
<h1><?php echo esc_html( $data['title'] ); ?></h1>
<p><?php echo esc_html( $data['tagline'] ); ?></p>
</div>
</section>
<!-- /wp:html -->

<!-- wp:html -->
<section class="superb-section superb-section--white superb-two-col-section">
<div class="superb-container">
<div class="superb-two-col superb-two-col--65-35">
<main class="superb-content">
<h2>What's Included</h2>
<ul class="superb-feature-list">
<?php foreach ( $data['included'] as $item ) : ?>
<li><?php echo esc_html( $item ); ?></li>
<?php endforeach; ?>
</ul>

<h2>Our Process</h2>
<div class="our-process__steps">
<?php foreach ( $data['steps'] as $i => $step ) : ?>
<div class="process-step fade-in-up"><div class="process-step__number"><?php echo esc_html( $i + 1 ); ?></div><h3><?php echo esc_html( $step['title'] ); ?></h3><p><?php echo esc_html( $step['desc'] ); ?></p></div>
<?php endforeach; ?>
</div>

<h2>Before &amp; After</h2>
<div class="before-after">
<div><img src="<?php echo esc_url( $ba['before'] ); ?>" alt="Before" loading="lazy"><span>Before</span></div>
<div><img src="<?php echo esc_url( $ba['after'] ); ?>" alt="After" loading="lazy"><span>After</span></div>
</div>

<h2>Frequently Asked Questions</h2>
<?php foreach ( $data['faq'] as $qa ) : ?>
<div class="faq-item"><h4><?php echo esc_html( $qa['q'] ); ?></h4><p><?php echo esc_html( $qa['a'] ); ?></p></div>
<?php endforeach; ?>

</main>
<aside class="superb-sidebar">
<div class="superb-sidebar-card">
<!-- /wp:html -->

<!-- wp:shortcode -->
[superb_quote_form variant="sidebar"]
<!-- /wp:shortcode -->

<!-- wp:html -->
</div>
</aside>
</div>
</div>
</section>
<!-- /wp:html -->

<!-- wp:html -->
<section class="suburbs-preview superb-section">
<div class="superb-container">
<h2 class="superb-section-title">Areas We Serve</h2>
<div class="suburbs-preview__grid">
<?php
$preview_suburbs = array(
	'Hawthorn'      => 'hawthorn',
	'Richmond'      => 'richmond',
	'Glen Waverley' => 'glen-waverley',
	'Camberwell'    => 'camberwell',
	'Box Hill'      => 'box-hill',
	'Doncaster'     => 'doncaster',
);
foreach ( $preview_suburbs as $name => $slug ) :
	?>
<article class="suburb-card"><a href="/suburbs/<?php echo esc_attr( $slug ); ?>"><img src="<?php echo esc_url( superb_suburb_card_image_url( $slug ) ); ?>" alt="<?php echo esc_attr( $name ); ?>" class="suburb-card__img" loading="lazy"><h3 class="suburb-card__title"><?php echo esc_html( $name ); ?></h3><span class="suburb-card__link">View Project</span></a></article>
<?php endforeach; ?>
</div>
</div>
</section>
<section class="cta-banner cta-banner--gold">
<h2>Ready to get started with <?php echo esc_html( $data['title'] ); ?>?</h2>
<p>Book your free on-site quote today. No obligation, no hidden costs.</p>
<a href="/contact/#quote-form" class="superb-btn superb-btn-charcoal btn-quote-scroll">Get My Free Quote</a>
</section>
<!-- /wp:html -->
	<?php
	return ob_get_clean();
}
