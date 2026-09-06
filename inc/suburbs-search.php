<?php
/**
 * Suburbs page search — shortcode (inputs cannot live in post content; WP strips them).
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render suburbs search UI.
 *
 * @return string
 */
function superb_suburbs_search_markup() {
	ob_start();
	?>
	<section class="suburbs-search superb-section">
		<div class="superb-container">
			<div class="suburbs-search__panel">
				<div class="suburbs-search__intro">
					<span class="suburbs-search__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
					</span>
					<div>
						<p class="suburbs-search__eyebrow">Melbourne service areas</p>
						<h2 class="suburbs-search__title">Find your suburb</h2>
					</div>
				</div>
				<div class="suburbs-search__field">
					<label for="suburbs-search" class="screen-reader-text"><?php esc_html_e( 'Search suburbs', 'kadence-child-superb' ); ?></label>
					<input type="text" id="suburbs-search" class="suburbs-search__input" role="searchbox" enterkeyhint="search" placeholder="<?php esc_attr_e( 'Search suburbs (e.g. Hawthorn, Richmond…)', 'kadence-child-superb' ); ?>" autocomplete="off">
					<button type="button" id="suburbs-search-clear" class="suburbs-search__clear" aria-label="<?php esc_attr_e( 'Clear search', 'kadence-child-superb' ); ?>" hidden>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
					</button>
				</div>
				<p class="suburbs-search__meta" id="suburbs-search-meta" aria-live="polite"></p>
				<p class="suburbs-search__empty" id="suburbs-search-empty" hidden><?php esc_html_e( 'No suburbs match your search.', 'kadence-child-superb' ); ?> <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact us', 'kadence-child-superb' ); ?></a> — <?php esc_html_e( 'we likely cover your area.', 'kadence-child-superb' ); ?></p>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode: [superb_suburbs_search]
 */
function superb_suburbs_search_shortcode() {
	return superb_suburbs_search_markup();
}
add_shortcode( 'superb_suburbs_search', 'superb_suburbs_search_shortcode' );
