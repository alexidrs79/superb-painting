<?php
/**
 * CLI script to generate suburb example page HTML files.
 * Run: php inc/generate-suburbs.php
 */

define( 'SUPERB_THEME_CLI', true );
define( 'SUPERB_THEME_DIR', dirname( __DIR__ ) );
define( 'SUPERB_THEME_URI', '/wp-content/themes/kadence-child-superb' );
require_once SUPERB_THEME_DIR . '/inc/images.php';

function superb_suburb_page_markup( $data ) {
	$hero_url = superb_suburb_card_image_url( $data['slug'] ?? $data['name'] );
	ob_start();
	?>
<!-- wp:html -->
<section class="page-hero page-hero--cover page-hero--featured" style="--hero-bg-image: url('<?php echo esc_url( $hero_url ); ?>');">
<div class="page-hero__overlay" aria-hidden="true"></div>
<div class="page-hero__inner">
<nav class="superb-breadcrumb" aria-label="Breadcrumb"><ol><li><a href="/">Home</a></li><li class="superb-breadcrumb__sep">›</li><li><a href="/suburbs">Suburbs</a></li><li class="superb-breadcrumb__sep">›</li><li><span aria-current="page"><?php echo esc_html( $data['name'] ); ?></span></li></ol></nav>
<h1>Interior & Exterior Painting in <?php echo esc_html( $data['name'] ); ?></h1>
<p><?php echo esc_html( $data['area'] ); ?></p>
</div>
</section>
<!-- /wp:html -->

<!-- wp:html -->
<section class="superb-section superb-section--white superb-two-col-section">
<div class="superb-container">
<div class="superb-two-col superb-two-col--65-35">
<main class="superb-content">
<h2>About Our Work in <?php echo esc_html( $data['name'] ); ?></h2>
<?php foreach ( $data['paragraphs'] as $p ) : ?>
<p><?php echo esc_html( $p ); ?></p>
<?php endforeach; ?>

<h2>Services We Offer in <?php echo esc_html( $data['name'] ); ?></h2>
<ul class="superb-feature-list">
<?php foreach ( $data['services'] as $s ) : ?>
<li><?php echo esc_html( $s ); ?></li>
<?php endforeach; ?>
</ul>

<h2>Why Choose a Local Painter?</h2>
<p><?php echo esc_html( $data['local'] ); ?></p>

<h2>Our Work in <?php echo esc_html( $data['name'] ); ?></h2>
<div class="suburb-gallery-grid superb-suburb-lightbox">
<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
<img src="<?php echo esc_url( superb_image_from_pool( 'gallery', $data['random'] + $i ) ); ?>" alt="Painting work in <?php echo esc_attr( $data['name'] ); ?>" loading="lazy">
<?php endfor; ?>
</div>
</main>
<aside class="superb-sidebar">
<div class="superb-sidebar-card">
<!-- /wp:html -->

<!-- wp:shortcode -->
[superb_quote_form variant="sidebar" suburb="<?php echo esc_html( $data['name'] ); ?>"]
<!-- /wp:shortcode -->

<!-- wp:html -->
</div>
<div class="superb-sidebar-card">
<h3>Opening Hours</h3>
<dl class="opening-hours"><dt>Mon–Fri</dt><dd>7:00am – 6:00pm</dd><dt>Saturday</dt><dd>8:00am – 2:00pm</dd></dl>
<a href="<?php echo esc_url( SUPERB_PHONE_LINK ); ?>" class="superb-btn superb-btn-orange superb-btn-full">Call <?php echo esc_html( SUPERB_PHONE ); ?></a>
</div>
</aside>
</div>
</div>
</section>
<!-- /wp:html -->

<!-- wp:html -->
<section class="suburbs-preview superb-section">
<div class="superb-container">
<h2 class="superb-section-title">Nearby Suburbs We Also Serve</h2>
<div class="suburbs-preview__grid">
<?php foreach ( $data['nearby'] as $i => $nearby ) : ?>
<article class="suburb-card"><a href="/suburbs/<?php echo esc_attr( sanitize_title( $nearby ) ); ?>"><img src="<?php echo esc_url( superb_suburb_card_image_url( sanitize_title( $nearby ) ) ); ?>" alt="<?php echo esc_attr( $nearby ); ?>" class="suburb-card__img" loading="lazy"><h3 class="suburb-card__title"><?php echo esc_html( $nearby ); ?></h3><span class="suburb-card__link">View Project</span></a></article>
<?php endforeach; ?>
</div>
</div>
</section>
<section class="cta-banner cta-banner--gold">
<h2>Need a painter in <?php echo esc_html( $data['name'] ); ?>? Let's talk.</h2>
<p>Get a free, no-obligation quote from our local team. We respond within 24 hours.</p>
<a href="/contact/#quote-form" class="superb-btn superb-btn-charcoal btn-quote-scroll">Get My Free Quote</a>
</section>
<!-- /wp:html -->
	<?php
	return ob_get_clean();
}

function sanitize_title( $title ) {
	return strtolower( preg_replace( '/[^a-z0-9]+/i', '-', trim( $title ) ) );
}

function esc_html( $text ) {
	return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
}

function esc_attr( $text ) {
	return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
}

function esc_url( $url ) {
	return filter_var( $url, FILTER_SANITIZE_URL );
}

$suburbs = array(
	'hawthorn' => array(
		'name' => 'Hawthorn',
		'area' => 'Inner East Melbourne',
		'random' => 201,
		'paragraphs' => array(
			'Hawthorn is one of Melbourne\'s most prestigious inner-east suburbs, known for its beautiful period homes, tree-lined streets and vibrant Glenferrie Road precinct. Superb Painting has been serving Hawthorn homeowners for over a decade, delivering flawless interior and exterior finishes that complement the suburb\'s heritage character.',
			'From Victorian terrace houses to modern apartments near Swinburne University, we understand the diverse property types in Hawthorn and tailor our approach accordingly. Our local team knows the area well and can typically schedule a free quote visit within 24 hours.',
		),
		'services' => array( 'Interior wall and ceiling painting', 'Heritage home restoration painting', 'Kitchen cabinet spray painting', 'Feature wall installation', 'Apartment interior and exterior painting', 'Pre-sale property makeovers' ),
		'local' => 'Choosing a local painter means faster response times, knowledge of Hawthorn\'s building styles, and a team that takes pride in working in their own community. We\'re not driving in from the outer suburbs — we\'re your neighbours.',
		'testimonial' => array( 'quote' => 'Absolutely outstanding work. The team painted our entire 4-bedroom Hawthorn home in just 3 days and the finish is immaculate.', 'author' => 'Sarah M.' ),
		'nearby' => array( 'Camberwell', 'Kew', 'Richmond', 'Toorak', 'Balwyn', 'Box Hill' ),
	),
	'richmond' => array(
		'name' => 'Richmond',
		'area' => 'Inner City Melbourne',
		'random' => 211,
		'paragraphs' => array(
			'Richmond\'s eclectic mix of converted warehouses, Victorian workers\' cottages and modern apartments makes it one of Melbourne\'s most dynamic suburbs. Superb Painting has completed hundreds of projects across Richmond, from compact studio apartments to full terrace house renovations.',
			'We\'re experienced working in Richmond\'s unique properties — narrow laneway access, heritage overlays, and the fast-paced renovation market along Bridge Road and Swan Street. Our team delivers quality results on tight turnarounds.',
		),
		'services' => array( 'Apartment and studio painting', 'Terrace house interior and exterior painting', 'Kitchen cabinet transformations', 'Commercial shopfront painting', 'Feature walls and accent colours', 'Rental property refresh painting' ),
		'local' => 'Richmond moves fast, and so do we. Our local team understands the suburb\'s rental market, renovation cycles and the need for quick turnarounds without compromising on quality.',
		'testimonial' => array( 'quote' => 'The kitchen cabinet painting completely transformed our Richmond terrace. Guests think we had a full renovation!', 'author' => 'Michael T.' ),
		'nearby' => array( 'South Yarra', 'Prahran', 'Collingwood', 'Hawthorn', 'Toorak', 'Fitzroy' ),
	),
	'glen-waverley' => array(
		'name' => 'Glen Waverley',
		'area' => 'South-East Melbourne',
		'random' => 221,
		'paragraphs' => array(
			'Glen Waverley is a thriving south-east suburb popular with families, boasting excellent schools, shopping at The Glen, and a mix of established homes and new developments. Superb Painting has been the go-to interior painter for Glen Waverley families for over 20 years.',
			'Whether you\'re refreshing a 1970s brick veneer, updating a modern townhouse, or putting finishing touches on a new build near Kingsway, our team delivers consistent, high-quality results that Glen Waverley homeowners trust.',
		),
		'services' => array( 'Full home interior repainting', 'Family home room-by-room updates', 'New build finishing', 'Kitchen cabinet painting', 'Feature walls for kids\' rooms', 'Pre-sale home styling paint' ),
		'local' => 'As south-east Melbourne locals, we understand Glen Waverley\'s family-focused community. We work around school schedules, keep sites safe for children, and always leave your home clean and ready to live in.',
		'testimonial' => array( 'quote' => 'Best painters we\'ve ever used. Punctual, incredibly tidy, and the colour consultation saved us from a big mistake.', 'author' => 'Linda & James K.' ),
		'nearby' => array( 'Wheelers Hill', 'Mount Waverley', 'Mulgrave', 'Clayton', 'Oakleigh', 'Chadstone' ),
	),
);

foreach ( $suburbs as $slug => $data ) {
	$data['slug'] = $slug;
	$markup = superb_suburb_page_markup( $data );
	file_put_contents( dirname( __DIR__ ) . '/content/suburbs/' . $slug . '.html', $markup );
	echo "Created suburbs/{$slug}.html\n";
}
