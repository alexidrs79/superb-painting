<?php
/**
 * Title: Hero Split with Quote Form
 * Slug: superb-painting/hero-split
 * Categories: superb-painting
 */

$years = defined( 'SUPERB_YEARS_EXPERIENCE' ) ? SUPERB_YEARS_EXPERIENCE : '25';

?>
<!-- wp:html -->
<section class="hero-split">
<?php echo superb_hero_lcp_image_markup(); ?>
<div class="hero-split__inner">
<div class="hero-split__content">
<h1>Flawless Interior, Exterior Painting Across Melbourne</h1>
<p>Fixed-price quotes. On-time crews. A finish you'll notice every day — for homes, apartments and commercial spaces.</p>
<div class="hero-split__buttons">
<a href="#quote-form" class="superb-btn superb-btn-orange btn-quote-scroll">Get a Free Quote</a>
<a href="/gallery" class="superb-btn superb-btn-outline-white">View Our Work</a>
</div>
<div class="hero-split__badges">
<span class="hero-split__badge">[superb_icon name="award" size="16"] Master Painters Member</span>
<span class="hero-split__badge">[superb_icon name="palette" size="16"] Dulux Accredited</span>
<span class="hero-split__badge">[superb_icon name="timer" size="16"] <?php echo esc_html( $years ); ?>+ Years Experience</span>
</div>
</div>
<div class="hero-split__form-card">
<!-- /wp:html -->

<!-- wp:shortcode -->
[superb_quote_form variant="hero"]
<!-- /wp:shortcode -->

<!-- wp:html -->
</div>
</div>
</section>
<!-- /wp:html -->
