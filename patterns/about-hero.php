<?php
/**
 * Title: About Hero
 * Slug: superb-painting/about-hero
 * Categories: superb-painting
 */

$years = defined( 'SUPERB_YEARS_EXPERIENCE' ) ? SUPERB_YEARS_EXPERIENCE : '25';

?>
<!-- wp:html -->
<section class="about-hero about-hero--cover">
<div class="about-hero__overlay" aria-hidden="true"></div>
<div class="about-hero__inner">
<nav class="superb-breadcrumb" aria-label="Breadcrumb"><ol><li><a href="/">Home</a></li><li class="superb-breadcrumb__sep">›</li><li><span aria-current="page">About Us</span></li></ol></nav>
<p class="superb-eyebrow">Melbourne painters since 2000</p>
<h1>Local painters.<br>Real craftsmanship.</h1>
<p class="about-hero__lead">We're a Melbourne crew — not a franchise — built on showing up on time, prepping properly, and finishes homeowners are proud to live with.</p>
<div class="about-hero__badges">
<span class="hero-split__badge">[superb_icon name="award" size="16"] Master Painters Member</span>
<span class="hero-split__badge">[superb_icon name="palette" size="16"] Dulux Accredited</span>
<span class="hero-split__badge">[superb_icon name="timer" size="16"] <?php echo esc_html( $years ); ?>+ Years Experience</span>
</div>
</div>
</section>
<!-- /wp:html -->
