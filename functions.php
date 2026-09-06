<?php
/**
 * Kadence Child - Superb Painting
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

define( 'SUPERB_THEME_VERSION', '1.9.3' );
define( 'SUPERB_THEME_DIR', get_stylesheet_directory() );
define( 'SUPERB_THEME_URI', get_stylesheet_directory_uri() );
define( 'SUPERB_PHONE', '0400 164 985' );
define( 'SUPERB_PHONE_LINK', 'tel:+61400164985' );
define( 'SUPERB_EMAIL', 'info@superbpainting.com' );
define( 'SUPERB_ABN', '51 697 388 622' );
define( 'SUPERB_ABN_DISPLAY', '51697388622' );
define( 'SUPERB_YEARS_EXPERIENCE', '25' );
define( 'SUPERB_GOOGLE_REVIEWS_URL', 'https://www.google.com/search?q=Superb+Painting+Melbourne+reviews' );
define( 'SUPERB_TWOPAK_URL', 'https://superbtwopak.com.au' );
define( 'SUPERB_SUBURBS_URL', '/suburbs/' );
define( 'SUPERB_SERVICES_URL', '/services/' );

/**
 * Cache-busting version for theme assets.
 *
 * In local dev, uses filemtime so CSS/JS updates appear without bumping SUPERB_THEME_VERSION.
 *
 * @param string $relative_path Path relative to theme root, e.g. 'style.css'.
 * @return string
 */
function superb_asset_version( $relative_path = '' ) {
	if ( function_exists( 'wp_get_environment_type' ) && 'local' === wp_get_environment_type() && $relative_path ) {
		$file = SUPERB_THEME_DIR . '/' . ltrim( $relative_path, '/' );
		if ( is_readable( $file ) ) {
			return (string) filemtime( $file );
		}
	}

	return SUPERB_THEME_VERSION;
}

/**
 * Load inc/*.php helpers (skip CLI generators and data-only files).
 */
foreach ( glob( SUPERB_THEME_DIR . '/inc/*.php' ) as $superb_inc_file ) {
	$basename = basename( $superb_inc_file );
	if ( 0 === strpos( $basename, 'generate-' ) ) {
		continue;
	}
	if ( in_array( $basename, array( 'suburb-content.php', 'import-pages.php' ), true ) ) {
		continue;
	}
	require_once $superb_inc_file;
}

/**
 * Load theme text domain.
 */
function superb_load_textdomain() {
	load_child_theme_textdomain( 'kadence-child-superb', SUPERB_THEME_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'superb_load_textdomain', 1 );

/**
 * Enqueue styles and scripts.
 */
function superb_enqueue_assets() {
	wp_enqueue_style(
		'kadence-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'kadence' )->get( 'Version' )
	);

	wp_enqueue_style(
		'superb-google-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'superb-child-style',
		get_stylesheet_uri(),
		array( 'kadence-parent-style', 'superb-google-fonts' ),
		superb_asset_version( 'style.css' )
	);

	wp_enqueue_script(
		'superb-interactions',
		SUPERB_THEME_URI . '/js/interactions.js',
		array( 'contact-form-7' ),
		superb_asset_version( 'js/interactions.js' ),
		true
	);

	wp_enqueue_script(
		'superb-nav-dropdown',
		SUPERB_THEME_URI . '/js/nav-dropdown.js',
		array(),
		superb_asset_version( 'js/nav-dropdown.js' ),
		true
	);

	wp_enqueue_script(
		'superb-site-enhancements',
		SUPERB_THEME_URI . '/js/site-enhancements.js',
		array( 'contact-form-7' ),
		superb_asset_version( 'js/site-enhancements.js' ),
		true
	);

	if ( is_page_template( 'page-gallery.php' ) ) {
		wp_enqueue_script(
			'superb-gallery',
			SUPERB_THEME_URI . '/js/gallery.js',
			array( 'superb-interactions' ),
			superb_asset_version( 'js/gallery.js' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'superb_enqueue_assets', 20 );

/**
 * Theme supports — custom logo and image sizes.
 */
function superb_theme_setup() {
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 512,
			'width'       => 512,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_image_size( 'suburb-card', 800, 500, true );
	add_image_size( 'gallery-thumb', 600, 600, true );
	add_image_size( 'hero-bg', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'superb_theme_setup', 5 );

/**
 * Remove jQuery migrate on the front end.
 *
 * @param WP_Scripts $scripts Scripts registry.
 */
function superb_remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$script = $scripts->registered['jquery'];
		if ( $script->deps ) {
			$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
		}
	}
}
add_action( 'wp_default_scripts', 'superb_remove_jquery_migrate' );

/**
 * Disable WP emoji scripts and styles.
 */
function superb_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'superb_disable_emojis' );

/**
 * Register block pattern category (pattern PHP files live in /patterns/).
 */
function superb_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'superb-painting',
		array( 'label' => __( 'Superb Painting', 'kadence-child-superb' ) )
	);
}
add_action( 'init', 'superb_register_block_patterns' );

/**
 * Replace default Kadence footer with custom Superb footer.
 */
function superb_setup_footer() {
	remove_action( 'kadence_footer', 'Kadence\footer_markup', 10 );
	add_action( 'kadence_footer', 'superb_footer_markup', 10 );
}
add_action( 'after_setup_theme', 'superb_setup_footer', 20 );

/**
 * Custom footer markup.
 */
function superb_footer_markup() {
	?>
	<footer class="superb-footer">
		<div class="superb-footer__grid">
			<div class="superb-footer__col superb-footer__col--brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="superb-footer__logo-link" aria-label="<?php echo esc_attr( sprintf( __( '%s home', 'kadence-child-superb' ), get_bloginfo( 'name', 'display' ) ) ); ?>">
					<?php echo superb_logo_markup( array( 'class' => 'superb-footer__logo', 'width' => 140, 'height' => 140 ) ); ?>
				</a>
				<p class="superb-footer__tagline">Melbourne's trusted interior and exterior painters. Quality finishes, honest quotes, guaranteed results.</p>
				<?php echo superb_social_links_markup(); ?>
				<?php superb_gtranslate_switcher( 'footer' ); ?>
			</div>
			<div class="superb-footer__col">
				<h4>Services</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/services/interior-wall-painting/' ) ); ?>">Interior &amp; Exterior Painting</a></li>
					<li><a href="<?php echo esc_url( SUPERB_TWOPAK_URL ); ?>" target="_blank" rel="noopener noreferrer">Kitchen Cabinet &amp; TwoPak</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/special-finishes/' ) ); ?>">Special Finishes</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/apartment-painting/' ) ); ?>">Apartment Painting</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/new-build-painting/' ) ); ?>">New Build Painting</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/commercial-painting/' ) ); ?>">Commercial Painting</a></li>
				</ul>
			</div>
			<div class="superb-footer__col">
				<h4>Quick Links</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About Us</a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li>
					<li><a href="<?php echo esc_url( home_url( '/suburbs/' ) ); ?>">Suburbs</a></li>
					<li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a></li>
					<li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact Us</a></li>
				</ul>
			</div>
			<div class="superb-footer__col superb-footer__contact">
				<h4>Contact</h4>
				<?php echo superb_contact_details_markup(); ?>
			</div>
		</div>
		<div class="superb-footer__bar">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Superb Painting. ABN <?php echo esc_html( SUPERB_ABN ); ?> | All rights reserved.
		</div>
	</footer>
	<?php
}

/**
 * Header CTA slot — quote button in Kadence header bar.
 */
function superb_header_cta() {
	superb_header_actions();
}
add_action( 'kadence_header_button', 'superb_header_cta', 5 );

/**
 * Site-wide body class.
 *
 * @param array $classes Body classes.
 * @return array
 */
function superb_body_class( $classes ) {
	$classes[] = 'superb-painting';
	return $classes;
}
add_filter( 'body_class', 'superb_body_class' );

/**
 * Quote form shortcode — wraps Contact Form 7 with theme styling.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function superb_quote_form_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'variant' => 'hero',
			'suburb'  => '',
		),
		$atts,
		'superb_quote_form'
	);

	$is_contact = ( 'contact' === $atts['variant'] );
	$is_hero    = ( 'hero' === $atts['variant'] );

	if ( $is_contact ) {
		$option_key = 'superb_cf7_full_id';
	} elseif ( $is_hero ) {
		$option_key = 'superb_cf7_hero_id';
	} else {
		$option_key = 'superb_cf7_quick_id';
	}
	$form_id = (int) get_option( $option_key );

	if ( ! $form_id && function_exists( 'superb_cf7_create_forms' ) ) {
		$forms   = superb_cf7_create_forms();
		$form_id = $is_contact ? (int) $forms['full'] : ( $is_hero ? (int) $forms['hero'] : (int) $forms['quick'] );
	}

	$form_source = function_exists( 'superb_resolve_form_source' )
		? superb_resolve_form_source( $atts['variant'], $atts['suburb'] )
		: 'Website';

	ob_start();
	?>
	<div id="quote-form" class="superb-quote-form superb-quote-form--<?php echo esc_attr( $atts['variant'] ); ?>" data-form-source="<?php echo esc_attr( $form_source ); ?>" <?php echo $atts['suburb'] ? 'data-suburb="' . esc_attr( $atts['suburb'] ) . '"' : ''; ?>>
		<?php if ( 'hero' === $atts['variant'] ) : ?>
			<h3 class="superb-form-title">Get Your Free Quote</h3>
		<?php elseif ( 'sidebar' === $atts['variant'] && $atts['suburb'] ) : ?>
			<h3 class="superb-form-title">Get a Free Quote for <?php echo esc_html( $atts['suburb'] ); ?></h3>
		<?php else : ?>
			<h3 class="superb-form-title">Request a Free Quote</h3>
		<?php endif; ?>

		<?php
		if ( $form_id && function_exists( 'wpcf7_contact_form' ) ) {
			echo do_shortcode( '[contact-form-7 id="' . $form_id . '"]' );
		} else {
			echo '<p class="superb-form-note">Please install and activate Contact Form 7.</p>';
		}
		?>

		<?php if ( ! $is_contact ) : ?>
			<p class="superb-form-note superb-form-note--below"><?php echo $is_hero ? 'Free quote within 24 hours. No obligation.' : 'We respond within 24 hours. No obligation whatsoever.'; ?></p>
		<?php else : ?>
			<p class="superb-form-note superb-form-note--below"><?php echo superb_icon( 'lock', array( 'size' => 14, 'class' => 'superb-icon-inline' ) ); ?> Your information is 100% private. We never share your details.</p>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'superb_quote_form', 'superb_quote_form_shortcode' );

/**
 * Breadcrumb helper.
 *
 * @param array<int, array{label:string,url:string}> $items Breadcrumb items.
 */
function superb_breadcrumb( $items ) {
	if ( empty( $items ) ) {
		return;
	}
	echo '<nav class="superb-breadcrumb" aria-label="Breadcrumb"><ol>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		echo '<li>';
		if ( $i < $last && ! empty( $item['url'] ) ) {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $item['label'] ) . '</span>';
		}
		echo '</li>';
		if ( $i < $last ) {
			echo '<li class="superb-breadcrumb__sep" aria-hidden="true">›</li>';
		}
	}
	echo '</ol></nav>';
}

/**
 * Render nearby suburb cards from ACF or theme defaults.
 *
 * @param string $slug Current suburb slug.
 */
function superb_render_nearby_suburb_cards( $slug ) {
	$current    = get_page_by_path( $slug, OBJECT, 'suburb' );
	$nearby_ids = array();

	if ( $current && function_exists( 'get_field' ) ) {
		$acf_nearby = get_field( 'suburb_nearby', $current->ID );
		if ( ! superb_field_is_empty( $acf_nearby ) ) {
			foreach ( (array) $acf_nearby as $id ) {
				$id = is_object( $id ) ? $id->ID : (int) $id;
				if ( $id && $id !== $current->ID ) {
					$nearby_ids[] = $id;
				}
			}
		}
	}

	echo '<div class="suburbs-preview__grid suburb-cards-grid">';

	if ( $nearby_ids ) {
		foreach ( $nearby_ids as $nearby_id ) {
			superb_render_suburb_card( $nearby_id );
		}
		echo '</div>';
		return;
	}

	$content = superb_get_suburb_content( $slug );
	if ( $content && ! empty( $content['nearby'] ) ) {
		foreach ( $content['nearby'] as $nearby_slug ) {
			if ( $nearby_slug === $slug ) {
				continue;
			}
			$nearby_post = get_page_by_path( $nearby_slug, OBJECT, 'suburb' );
			if ( $nearby_post ) {
				superb_render_suburb_card( $nearby_post->ID );
			}
		}
		echo '</div>';
		return;
	}

	foreach ( superb_suburb_nearby( $slug ) as $name ) {
		$nearby_post = get_posts(
			array(
				'post_type'      => 'suburb',
				'title'          => $name,
				'posts_per_page' => 1,
				'post_status'    => 'publish',
			)
		);
		if ( $nearby_post ) {
			superb_render_suburb_card( $nearby_post[0]->ID );
		}
	}

	echo '</div>';
}

/**
 * Placeholder suburb cards when CPT is empty.
 *
 * @param int $count Number of cards.
 */
function superb_render_placeholder_suburb_cards( $count = 6 ) {
	$suburbs = array( 'Hawthorn', 'Richmond', 'Glen Waverley', 'Camberwell', 'Box Hill', 'Doncaster', 'Toorak', 'South Yarra', 'Prahran', 'Armadale', 'Kew', 'Balwyn' );
	echo '<div class="suburbs-preview__grid suburb-cards-grid">';
	for ( $i = 0; $i < $count; $i++ ) {
		$name = $suburbs[ $i % count( $suburbs ) ];
		?>
		<article class="suburb-card fade-in-up">
			<a href="<?php echo esc_url( home_url( '/suburbs/' . sanitize_title( $name ) . '/' ) ); ?>">
				<img src="<?php echo esc_url( superb_suburb_card_image_url( sanitize_title( $name ) ) ); ?>" alt="<?php echo esc_attr( $name ); ?>" class="suburb-card__img" loading="lazy">
				<h3 class="suburb-card__title"><?php echo esc_html( $name ); ?></h3>
				<p class="suburb-card__excerpt">Interior, exterior &amp; cabinet painting in <?php echo esc_html( $name ); ?></p>
				<span class="suburb-card__link">View Project</span>
			</a>
		</article>
		<?php
	}
	echo '</div>';
}

/**
 * Business credential shortcodes for content pages.
 */
function superb_vba_reg_shortcode() {
	return '';
}
add_shortcode( 'superb_vba_reg', 'superb_vba_reg_shortcode' );

function superb_abn_shortcode() {
	return esc_html( defined( 'SUPERB_ABN_DISPLAY' ) ? SUPERB_ABN_DISPLAY : SUPERB_ABN );
}
add_shortcode( 'superb_abn', 'superb_abn_shortcode' );
