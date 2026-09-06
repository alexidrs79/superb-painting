<?php
/**
 * Title: Stats Row
 * Slug: superb-painting/stats-row
 * Categories: superb-painting
 */

$years = defined( 'SUPERB_YEARS_EXPERIENCE' ) ? SUPERB_YEARS_EXPERIENCE : '25';

?>
<!-- wp:html -->
<section class="stats-row">
<div class="stats-row__grid">
<div><div class="stats-row__number" data-count="5000" data-suffix="+">0</div><div class="stats-row__label">Projects Completed</div></div>
<div><div class="stats-row__number" data-count="<?php echo esc_attr( $years ); ?>" data-suffix="+">0</div><div class="stats-row__label">Years Experience</div></div>
<div><div class="stats-row__number" data-count="200" data-suffix="+">0</div><div class="stats-row__label">Five-Star Reviews</div></div>
<div><div class="stats-row__number" data-count="100" data-suffix="%">0</div><div class="stats-row__label">Satisfaction Rate</div></div>
</div>
</section>
<!-- /wp:html -->
