<?php
/**
 * Template Name: Gallery Page
 *
 * @package Kadence_Child_Superb
 */

get_header();

$gallery_items = array(
	array( 'cat' => 'interior', 'seed' => 1, 'suburb' => 'Hawthorn', 'type' => 'Interior & Exterior' ),
	array( 'cat' => 'interior', 'seed' => 2, 'suburb' => 'Richmond', 'type' => 'Interior & Exterior' ),
	array( 'cat' => 'repaints', 'seed' => 3, 'suburb' => 'Camberwell', 'type' => 'Repaint' ),
	array( 'cat' => 'special-finishes', 'seed' => 4, 'suburb' => 'Brighton', 'type' => 'Special Finishes' ),
	array( 'cat' => 'interior', 'seed' => 5, 'suburb' => 'Malvern', 'type' => 'Interior & Exterior' ),
	array( 'cat' => 'commercial', 'seed' => 6, 'suburb' => 'South Yarra', 'type' => 'Commercial' ),
	array( 'cat' => 'interior', 'seed' => 7, 'suburb' => 'Toorak', 'type' => 'Interior & Exterior' ),
	array( 'cat' => 'special-finishes', 'seed' => 8, 'suburb' => 'St Kilda', 'type' => 'Special Finishes' ),
	array( 'cat' => 'repaints', 'seed' => 9, 'suburb' => 'Kew', 'type' => 'Repaint' ),
	array( 'cat' => 'interior', 'seed' => 10, 'suburb' => 'Prahran', 'type' => 'Interior & Exterior' ),
	array( 'cat' => 'commercial', 'seed' => 11, 'suburb' => 'Essendon', 'type' => 'Commercial' ),
	array( 'cat' => 'repaints', 'seed' => 12, 'suburb' => 'Balwyn', 'type' => 'Repaint' ),
);
?>

<section class="page-hero page-hero--cover">
	<div class="page-hero__overlay" aria-hidden="true"></div>
	<div class="page-hero__inner">
		<?php
		superb_breadcrumb(
			array(
				array( 'label' => 'Home', 'url' => home_url( '/' ) ),
				array( 'label' => 'Gallery', 'url' => '' ),
			)
		);
		?>
		<h1>Our Work</h1>
		<p>Every project we take on gets the same level of care and attention to detail.</p>
	</div>
</section>

<div class="gallery-page superb-section">
	<div class="gallery-filters">
		<button type="button" class="gallery-filter-tab active" data-filter="all">All</button>
		<button type="button" class="gallery-filter-tab" data-filter="interior">Interior &amp; Exterior</button>
		<button type="button" class="gallery-filter-tab" data-filter="special-finishes">Special Finishes</button>
		<button type="button" class="gallery-filter-tab" data-filter="repaints">Repaints</button>
		<button type="button" class="gallery-filter-tab" data-filter="commercial">Commercial</button>
	</div>

	<div class="gallery-masonry">
		<?php foreach ( $gallery_items as $item ) : ?>
			<div class="gallery-item" data-category="<?php echo esc_attr( $item['cat'] ); ?>">
				<img src="<?php echo esc_url( superb_image_from_pool( 'gallery', $item['seed'] ) ); ?>" alt="<?php echo esc_attr( $item['type'] . ' in ' . $item['suburb'] ); ?>" loading="lazy">
				<div class="gallery-item-overlay">
					<span class="gallery-item-caption"><?php echo esc_html( $item['suburb'] ); ?> — <?php echo esc_html( $item['type'] ); ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<section class="cta-banner cta-banner--gold">
	<h2>Love what you see?</h2>
	<p>Get a free quote and let us transform your space too.</p>
	<a href="<?php echo esc_url( home_url( '/contact/#quote-form' ) ); ?>" class="superb-btn superb-btn-charcoal superb-quote-cta">Get My Free Quote</a>
</section>

<?php get_footer(); ?>
