<?php
/**
 * Title: Google Reviews
 * Slug: superb-painting/google-reviews
 * Categories: superb-painting
 */

$reviews_url = defined( 'SUPERB_GOOGLE_REVIEWS_URL' ) ? SUPERB_GOOGLE_REVIEWS_URL : 'https://www.google.com/search?q=Superb+Painting+Melbourne+reviews';

?>
<!-- wp:html -->
<section class="google-reviews superb-section superb-section--muted" aria-label="Google reviews">
<div class="superb-container">
<div class="google-reviews__header fade-in-up">
<p class="superb-eyebrow">Trusted by Melbourne homeowners</p>
<h2 class="superb-section-title">See what our clients say on Google</h2>
<p class="superb-section-subtitle">Real reviews from homeowners across Melbourne — interior, exterior, apartments and commercial projects.</p>
</div>
<div class="google-reviews__cta fade-in-up">
<a href="<?php echo esc_url( $reviews_url ); ?>" class="superb-btn superb-btn-orange" target="_blank" rel="noopener noreferrer">Read Our Google Reviews</a>
</div>
</div>
</section>
<!-- /wp:html -->
