<?php
/**
 * Header layout — top utility bar + main navigation row.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slim top bar: trust signals + contact details (desktop/tablet).
 */
function superb_header_top_bar() {
	?>
	<div class="superb-header-topbar" aria-label="<?php esc_attr_e( 'Contact and credentials', 'kadence-child-superb' ); ?>">
		<div class="superb-header-topbar__inner">
			<div class="superb-header-topbar__trust">
				<?php echo superb_icon_label( 'award', 'Master Painters Member', 14 ); ?>
				<span class="superb-header-topbar__sep" aria-hidden="true"></span>
				<?php echo superb_icon_label( 'shield', '$20M Insured', 14 ); ?>
				<span class="superb-header-topbar__sep" aria-hidden="true"></span>
				<?php
				$years = defined( 'SUPERB_YEARS_EXPERIENCE' ) ? SUPERB_YEARS_EXPERIENCE : '25';
				echo superb_icon_label( 'timer', $years . '+ Years Experience', 14 );
				?>
			</div>
			<div class="superb-header-topbar__contact">
				<a href="<?php echo esc_url( SUPERB_PHONE_LINK ); ?>" class="superb-header-topbar__link">
					<?php echo superb_icon( 'phone', array( 'size' => 14 ) ); ?>
					<span><?php echo esc_html( SUPERB_PHONE ); ?></span>
				</a>
				<a href="mailto:<?php echo esc_attr( SUPERB_EMAIL ); ?>" class="superb-header-topbar__link">
					<?php echo superb_icon( 'mail', array( 'size' => 14 ) ); ?>
					<span><?php echo esc_html( SUPERB_EMAIL ); ?></span>
				</a>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'kadence_top_header', 'superb_header_top_bar', 5 );

/**
 * Main header actions — quote CTA (desktop header bar only).
 */
function superb_header_actions() {
	?>
	<div class="superb-header-actions">
		<?php superb_gtranslate_switcher( 'header' ); ?>
		<a href="<?php echo esc_url( home_url( '/contact/#quote-form' ) ); ?>" class="superb-btn superb-btn-orange superb-quote-cta">
			Get a Free Quote
		</a>
	</div>
	<?php
}

/**
 * Quote CTA inside the mobile drawer, below navigation links.
 */
function superb_mobile_drawer_translate() {
	if ( ! superb_gtranslate_is_active() ) {
		return;
	}
	?>
	<div class="superb-mobile-drawer-translate">
		<?php superb_gtranslate_switcher( 'mobile' ); ?>
	</div>
	<?php
}
add_action( 'kadence_after_mobile_navigation_popup', 'superb_mobile_drawer_translate', 5 );

/**
 * Quote CTA inside the mobile drawer, below navigation links.
 */
function superb_mobile_drawer_cta() {
	?>
	<div class="superb-mobile-drawer-cta">
		<a href="<?php echo esc_url( home_url( '/contact/#quote-form' ) ); ?>" class="superb-btn superb-btn-orange superb-btn-full btn-quote-scroll">
			Get a Free Quote
		</a>
	</div>
	<?php
}
add_action( 'kadence_after_mobile_navigation_popup', 'superb_mobile_drawer_cta', 10 );

/**
 * Phone strip for mobile (below main mobile header row).
 */
function superb_header_mobile_contact_strip() {
	?>
	<div class="superb-header-mobile-strip">
		<a href="<?php echo esc_url( SUPERB_PHONE_LINK ); ?>" class="superb-header-mobile-strip__link">
			<?php echo superb_icon( 'phone', array( 'size' => 16 ) ); ?>
			<span><?php echo esc_html( SUPERB_PHONE ); ?></span>
		</a>
		<a href="mailto:<?php echo esc_attr( SUPERB_EMAIL ); ?>" class="superb-header-mobile-strip__link">
			<?php echo superb_icon( 'mail', array( 'size' => 16 ) ); ?>
			<span><?php echo esc_html( SUPERB_EMAIL ); ?></span>
		</a>
	</div>
	<?php
}
add_action( 'kadence_mobile_bottom_header', 'superb_header_mobile_contact_strip', 15 );

/**
 * Floating mobile CTA bar — Call | Free Quote.
 */
function superb_mobile_floating_cta() {
	?>
	<nav class="superb-mobile-cta" aria-label="<?php esc_attr_e( 'Quick contact', 'kadence-child-superb' ); ?>">
		<a href="<?php echo esc_url( SUPERB_PHONE_LINK ); ?>" class="superb-mobile-cta__btn superb-mobile-cta__btn--call">
			<?php echo superb_icon( 'phone', array( 'size' => 18 ) ); ?>
			<span><?php esc_html_e( 'Call', 'kadence-child-superb' ); ?></span>
		</a>
		<a href="<?php echo esc_url( home_url( '/contact/#quote-form' ) ); ?>" class="superb-mobile-cta__btn superb-mobile-cta__btn--quote">
			<span><?php esc_html_e( 'Free Quote', 'kadence-child-superb' ); ?></span>
		</a>
	</nav>
	<?php
}
add_action( 'wp_footer', 'superb_mobile_floating_cta', 20 );

/**
 * Global interaction UI — scroll progress, back to top.
 */
function superb_interaction_ui() {
	?>
	<div class="superb-scroll-progress-wrap" aria-hidden="true">
		<div class="superb-scroll-progress"></div>
	</div>
	<button type="button" class="superb-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'kadence-child-superb' ); ?>">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
	</button>
	<?php
}
add_action( 'wp_footer', 'superb_interaction_ui', 15 );
