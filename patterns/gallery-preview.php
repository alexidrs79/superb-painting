<?php
/**
 * Title: Gallery Preview
 * Slug: superb-painting/gallery-preview
 * Categories: superb-painting
 */

?>
<!-- wp:html -->
<section class="gallery-preview">
<div class="superb-container">
<h2 class="superb-section-title">Our Recent Work</h2>
<p class="superb-section-subtitle">Every project we take on gets the same level of care and attention to detail.</p>
<div class="gallery-preview__grid">
<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
<div class="gallery-preview__item"><img src="<?php echo esc_url( superb_image_from_pool( 'gallery', $i ) ); ?>" alt="Recent painting project <?php echo (int) $i; ?>" loading="lazy"><div class="gallery-preview__overlay"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/><path d="M11 8v6M8 11h6"/></svg></div></div>
<?php endfor; ?>
</div>
<div class="gallery-preview__cta"><a href="/gallery" class="superb-btn superb-btn-outline-white">View Full Gallery</a></div>
</div>
</section>
<!-- /wp:html -->
