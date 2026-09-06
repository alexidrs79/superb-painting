<?php
/**
 * Title: Suburbs Preview
 * Slug: superb-painting/suburbs-preview
 * Categories: superb-painting
 */

?>
<!-- wp:html -->
<section class="suburbs-preview superb-section">
<div class="superb-container">
<h2 class="superb-section-title">Proudly Painting Across Melbourne</h2>
<p class="superb-section-subtitle">Local knowledge. Professional results. Serving 50+ suburbs across Greater Melbourne.</p>
<div class="suburbs-preview__grid">
<?php
$preview_suburbs = array(
	'Hawthorn'       => 'hawthorn',
	'Richmond'       => 'richmond',
	'Glen Waverley'  => 'glen-waverley',
	'Camberwell'     => 'camberwell',
	'Box Hill'       => 'box-hill',
	'Doncaster'      => 'doncaster',
);
foreach ( $preview_suburbs as $name => $slug ) :
	?>
<article class="suburb-card fade-in-up"><a href="/suburbs/<?php echo esc_attr( $slug ); ?>"><div class="suburb-card__media"><img src="<?php echo esc_url( superb_suburb_card_image_url( $slug ) ); ?>" alt="<?php echo esc_attr( $name ); ?>" class="suburb-card__img" loading="lazy"><span class="suburb-card__overlay">View suburb</span></div><h3 class="suburb-card__title"><?php echo esc_html( $name ); ?></h3><p class="suburb-card__excerpt">Interior, exterior &amp; cabinet painting in <?php echo esc_html( $name ); ?></p><span class="suburb-card__link">View Project</span></a></article>
<?php endforeach; ?>
</div>
<div class="suburbs-preview__cta"><a href="/suburbs" class="superb-btn superb-btn-outline-navy">See All Suburbs We Cover</a></div>
</div>
</section>
<!-- /wp:html -->
