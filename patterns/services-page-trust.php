<?php
/**
 * Title: Services Page Trust
 * Slug: superb-painting/services-page-trust
 * Categories: superb-painting
 */

$years = defined( 'SUPERB_YEARS_EXPERIENCE' ) ? SUPERB_YEARS_EXPERIENCE : '25';

?>
<!-- wp:html -->
<section class="services-page-trust" aria-label="Trust and credentials">
<div class="trust-badges trust-badges--logos">
<div class="trust-badge trust-badge--logo fade-in-up">
<div class="trust-badge__icon"><?php echo superb_credential_logo_img( 'master-painters-mpa', array( 'class' => 'superb-credential-logo superb-credential-logo--mpa' ) ); ?></div>
<p>Master Painters Member</p>
</div>
<div class="trust-badge trust-badge--logo fade-in-up">
<div class="trust-badge__icon"><?php echo superb_credential_logo_img( 'dulux-accredited', array( 'class' => 'superb-credential-logo superb-credential-logo--dulux' ) ); ?></div>
<p>Dulux Accredited</p>
</div>
<div class="trust-badge fade-in-up">
<div class="trust-badge__icon">[superb_icon name="shield" size="32"]</div>
<p>$20M Insured</p>
</div>
<div class="trust-badge fade-in-up">
<div class="trust-badge__icon">[superb_icon name="timer" size="32"]</div>
<p><?php echo esc_html( $years ); ?>+ Years Experience</p>
</div>
</div>
</section>
<!-- /wp:html -->
