<?php
/**
 * Title: About Trust
 * Slug: superb-painting/about-trust
 * Categories: superb-painting
 */

$years = defined( 'SUPERB_YEARS_EXPERIENCE' ) ? SUPERB_YEARS_EXPERIENCE : '25';

?>
<!-- wp:html -->
<section class="about-trust">
<div class="about-trust__inner">
<div class="about-trust__intro fade-in-up">
<h2>Professional, insured &amp; accredited</h2>
<p>Every Superb Painting job is carried out by professional experienced painters — with credentials you can verify before we start.</p>
<ul class="about-trust__guarantees">
<li>[superb_icon name="check-circle" size="18"] Fixed-price written quotes</li>
<li>[superb_icon name="check-circle" size="18"] Clean, respectful on-site crews</li>
<li>[superb_icon name="check-circle" size="18"] Premium Dulux, Resene &amp; Porter's paints</li>
</ul>
</div>
<div class="about-trust__badges">
<div class="about-trust__badge about-trust__badge--logo fade-in-up">
<span class="about-trust__badge-icon"><?php echo superb_credential_logo_img( 'master-painters-mpa', array( 'class' => 'superb-credential-logo superb-credential-logo--mpa' ) ); ?></span>
<strong>Master Painters Association</strong>
<small>Member</small>
</div>
<div class="about-trust__badge about-trust__badge--logo fade-in-up">
<span class="about-trust__badge-icon"><?php echo superb_credential_logo_img( 'dulux-accredited', array( 'class' => 'superb-credential-logo superb-credential-logo--dulux' ) ); ?></span>
<strong>Dulux Accredited</strong>
<small>Premium applicator</small>
</div>
<div class="about-trust__badge fade-in-up">
<span class="about-trust__badge-icon">[superb_icon name="shield" size="28"]</span>
<strong>$20M Liability</strong>
<small>Fully insured</small>
</div>
<div class="about-trust__badge fade-in-up">
<span class="about-trust__badge-icon">[superb_icon name="timer" size="28"]</span>
<strong><?php echo esc_html( $years ); ?>+ Years</strong>
<small>ABN [superb_abn]</small>
</div>
</div>
</div>
</section>
<!-- /wp:html -->
