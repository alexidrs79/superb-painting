<?php
/**
 * Contact Form 7 — form definitions and helpers.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/form-config.php';

define( 'SUPERB_CF7_FORMS_VERSION', '2.3.1' );

/**
 * CF7 form titles managed by the theme.
 *
 * @return string[]
 */
function superb_cf7_form_titles() {
	return array(
		'Superb Hero Quote',
		'Superb Quick Quote',
		'Superb Full Quote',
	);
}

/**
 * Resolve CF7 mail template name from the wpcf7_mail_components third argument.
 *
 * CF7 6.x passes a WPCF7_Mail instance, not the string "mail".
 *
 * @param mixed $mail_type Mail type argument from the filter.
 * @return string mail|mail_2|...
 */
function superb_cf7_get_mail_template_name( $mail_type ) {
	if ( is_string( $mail_type ) ) {
		return $mail_type;
	}

	if ( is_object( $mail_type ) && method_exists( $mail_type, 'get_template_name' ) ) {
		return (string) $mail_type->get_template_name();
	}

	return '';
}

/**
 *
 * @param string $existing_headers Existing headers from the mail template.
 * @return string
 */
function superb_cf7_mail_additional_headers( $existing_headers = '' ) {
	$headers = array();

	if ( is_string( $existing_headers ) && '' !== trim( $existing_headers ) ) {
		$headers = preg_split( '/\r\n|\r|\n/', $existing_headers );
	}

	$has_reply_to = false;
	$has_cc       = false;

	foreach ( $headers as $index => $header ) {
		$header = trim( (string) $header );
		if ( '' === $header ) {
			unset( $headers[ $index ] );
			continue;
		}

		if ( 0 === stripos( $header, 'reply-to:' ) ) {
			$has_reply_to     = true;
			$headers[ $index ] = 'Reply-To: [your-email]';
		}

		if ( 0 === stripos( $header, 'cc:' ) ) {
			$has_cc = true;
			unset( $headers[ $index ] );
		}
	}

	if ( ! $has_reply_to ) {
		$headers[] = 'Reply-To: [your-email]';
	}

	$cc_emails = superb_get_form_cc_emails();
	if ( $cc_emails ) {
		$headers[] = 'Cc: ' . implode( ', ', $cc_emails );
	}

	return implode( "\n", array_values( $headers ) );
}

/**
 * Replace Reply-To tag with the submitter's address at send time.
 *
 * @param string $header_string Additional mail headers.
 * @return string
 */
function superb_cf7_resolve_reply_to_in_headers( $header_string ) {
	$submission = WPCF7_Submission::get_instance();
	if ( ! $submission ) {
		return $header_string;
	}

	$posted = $submission->get_posted_data();
	$email  = isset( $posted['your-email'] ) ? sanitize_email( $posted['your-email'] ) : '';
	if ( ! $email || ! is_email( $email ) ) {
		return $header_string;
	}

	$name     = isset( $posted['your-name'] ) ? sanitize_text_field( wp_strip_all_tags( $posted['your-name'] ) ) : '';
	$reply_to = $name ? sprintf( '%s <%s>', $name, $email ) : $email;

	$lines = preg_split( '/\r\n|\r|\n/', (string) $header_string );
	$found = false;

	foreach ( $lines as $index => $line ) {
		$line = trim( (string) $line );
		if ( '' === $line ) {
			unset( $lines[ $index ] );
			continue;
		}

		if ( 0 === stripos( $line, 'reply-to:' ) ) {
			$lines[ $index ] = 'Reply-To: ' . $reply_to;
			$found           = true;
		}
	}

	if ( ! $found ) {
		$lines[] = 'Reply-To: ' . $reply_to;
	}

	return implode( "\n", array_values( $lines ) );
}

/**
 * User-facing CF7 status messages for quote forms.
 *
 * @return array<string, string>
 */
function superb_cf7_form_messages() {
	return array(
		'mail_sent_ok'     => 'Thank you! We\'ll be in touch within 24 hours with your free quote.',
		'mail_sent_ng'     => 'We couldn\'t send your quote request right now. Please try again in a moment or call us directly.',
		'validation_error' => 'Please check the highlighted fields and try again.',
		'spam'             => 'Your submission could not be sent. Please try again or contact us by phone.',
		'accept_terms'     => 'Please accept the terms before submitting.',
		'invalid_required' => 'This field is required.',
		'invalid_too_long' => 'This entry is too long.',
		'invalid_too_short' => 'This entry is too short.',
		'upload_failed'    => 'There was an error uploading your photo. Please try a smaller file.',
		'invalid_email'    => 'Please enter a valid email address.',
		'invalid_tel'      => 'Please enter a valid Australian phone number.',
	);
}

/**
 * Human-readable label for where a quote form was submitted.
 *
 * @param string $variant Shortcode variant: hero, contact, or sidebar.
 * @param string $suburb  Optional suburb name from shortcode attribute.
 * @return string
 */
function superb_resolve_form_source( $variant = 'hero', $suburb = '' ) {
	if ( 'hero' === $variant ) {
		return 'Homepage';
	}

	if ( 'contact' === $variant ) {
		return 'Contact Page';
	}

	$suburb = trim( (string) $suburb );
	if ( $suburb ) {
		return 'Suburbs Page: ' . $suburb;
	}

	if ( is_singular( 'suburb' ) ) {
		return 'Suburbs Page: ' . get_the_title();
	}

	if ( is_singular( 'service' ) ) {
		return 'Services Page: ' . get_the_title();
	}

	if ( is_page( 'suburbs' ) ) {
		return 'Suburbs Listing Page';
	}

	if ( is_page( 'services' ) ) {
		return 'Services Listing Page';
	}

	return 'Website';
}

/**
 * Derive a form-source label from a submitted page URL (server-side fallback).
 *
 * @param string $url Page URL or path from lead-page hidden field.
 * @return string
 */
function superb_cf7_form_source_from_url( $url ) {
	$path = wp_parse_url( $url, PHP_URL_PATH );
	if ( ! $path ) {
		$path = (string) $url;
	}

	$path = trim( $path, '/' );

	if ( '' === $path ) {
		return 'Homepage';
	}

	$segments = explode( '/', $path );

	if ( 'contact' === $path ) {
		return 'Contact Page';
	}

	if ( isset( $segments[0] ) && 'services' === $segments[0] ) {
		if ( count( $segments ) === 1 ) {
			return 'Services Listing Page';
		}
		return 'Services Page: ' . superb_cf7_slug_to_title( end( $segments ) );
	}

	if ( isset( $segments[0] ) && 'suburbs' === $segments[0] ) {
		if ( count( $segments ) === 1 ) {
			return 'Suburbs Listing Page';
		}
		return 'Suburbs Page: ' . superb_cf7_slug_to_title( end( $segments ) );
	}

	return 'Website: /' . $path;
}

/**
 * @param string $slug Post slug.
 * @return string
 */
function superb_cf7_slug_to_title( $slug ) {
	return ucwords( str_replace( '-', ' ', (string) $slug ) );
}

/**
 * Mail body for a quote form variant (only includes fields that exist on that form).
 *
 * @param string $form_key hero|quick|full.
 * @return string
 */
function superb_cf7_mail_body( $form_key = 'quick' ) {
	$body = "New quote request from Superb Painting\n\n"
		. "FORM SOURCE: [form-source]\n"
		. "Page URL: [lead-page]\n"
		. "Referrer: [lead-source]\n\n"
		. "--- Contact details ---\n"
		. "Full Name: [your-name]\n"
		. "Phone: [your-phone]\n"
		. "Email: [your-email]\n"
		. "Suburb: [your-suburb]\n\n"
		. "--- Project details ---\n";

	switch ( $form_key ) {
		case 'hero':
			$body .= "Job Type: [job-type]\n\n"
				. "Message:\n[your-message]\n\n";
			break;
		case 'full':
			$body .= "Number of Rooms: [your-rooms]\n"
				. "Property Type: [property-type]\n"
				. "Service Required: [your-service]\n\n"
				. "Message:\n[your-message]\n\n";
			break;
		case 'quick':
		default:
			$body .= "Job Type: [job-type]\n"
				. "Number of Rooms: [your-rooms]\n\n"
				. "Message:\n[your-message]\n\n";
			break;
	}

	$body .= '--' . "\n"
		. 'Sent from [_site_title] ([_site_url])';

	return $body;
}

/**
 * Mail template for a quote form variant.
 *
 * @param string $form_key hero|quick|full.
 * @return array<string, mixed>
 */
function superb_cf7_mail_template( $form_key = 'quick' ) {
	$subjects = array(
		'hero'  => '[Superb Painting] Homepage — Quote from [your-name]',
		'quick' => '[Superb Painting] [form-source] — Quote from [your-name]',
		'full'  => '[Superb Painting] Contact Page — Quote from [your-name]',
	);

	$subject = $subjects[ $form_key ] ?? '[Superb Painting] Quote from [your-name]';

	return array(
		'active'             => true,
		'subject'            => $subject,
		'sender'             => 'Superb Painting <wordpress@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
		'recipient'          => SUPERB_EMAIL,
		'body'               => superb_cf7_mail_body( $form_key ),
		'additional_headers' => superb_cf7_mail_additional_headers( 'Reply-To: [your-email]' ),
		'attachments'        => in_array( $form_key, array( 'quick', 'full' ), true ) ? 'your-photos' : '',
		'use_html'           => false,
		'exclude_blank'      => true,
	);
}

/**
 * Hero quote form — minimal fields for homepage above the fold.
 */
function superb_cf7_hero_form_markup() {
	return '
[hidden form-source id:form-source][hidden lead-source id:lead-source][hidden lead-page id:lead-page]
<div class="superb-cf7-grid superb-cf7-grid--hero">
<p class="superb-cf7-field"><label for="your-name">Full Name *</label>[text* your-name id:your-name autocomplete:name placeholder "Your full name"]</p>
<p class="superb-cf7-row"><span class="superb-cf7-field"><label for="your-phone">Phone *</label>[tel* your-phone id:your-phone autocomplete:tel placeholder "04XX XXX XXX"]</span><span class="superb-cf7-field"><label for="your-email">Email *</label>[email* your-email id:your-email autocomplete:email placeholder "you@email.com"]</span></p>
<p class="superb-cf7-row"><span class="superb-cf7-field"><label for="your-suburb">Suburb *</label>[text* your-suburb id:your-suburb placeholder "e.g. Hawthorn"]</span><span class="superb-cf7-field"><label for="job-type">Type of Job</label>[select job-type id:job-type "Select..." "Interior & Exterior Painting" "Kitchen Cabinet & TwoPak" "Special Finishes" "Apartment" "New Build" "Commercial" "Other"]</span></p>
<p class="superb-cf7-field"><label for="your-message">About Your Project</label>[textarea your-message id:your-message rows:2 placeholder "Tell us about your project (optional)"]</p>
<p class="superb-cf7-submit">[submit class:superb-btn-orange "Get My Free Quote"]</p>
</div>';
}

/**
 * Quick quote form markup (sidebar).
 */
function superb_cf7_quick_form_markup() {
	return '
[hidden form-source id:form-source][hidden lead-source id:lead-source][hidden lead-page id:lead-page]
<div class="superb-cf7-grid">
<p class="superb-cf7-field"><label for="your-name">Full Name *</label>[text* your-name id:your-name autocomplete:name placeholder "Your full name"]</p>
<p class="superb-cf7-row"><span class="superb-cf7-field"><label for="your-phone">Phone Number *</label>[tel* your-phone id:your-phone autocomplete:tel placeholder "04XX XXX XXX"]</span><span class="superb-cf7-field"><label for="your-email">Email Address *</label>[email* your-email id:your-email autocomplete:email placeholder "you@email.com"]</span></p>
<p class="superb-cf7-field"><label for="your-suburb">Suburb *</label>[text* your-suburb id:your-suburb placeholder "e.g. Hawthorn"]</p>
<p class="superb-cf7-row"><span class="superb-cf7-field"><label for="job-type">Type of Job</label>[select job-type id:job-type "Select..." "Interior & Exterior Painting" "Kitchen Cabinet & TwoPak" "Special Finishes" "Apartment" "New Build" "Commercial" "Other"]</span><span class="superb-cf7-field"><label for="your-rooms">Number of Rooms</label>[select your-rooms id:your-rooms "Select..." "1" "2" "3" "4" "5+"]</span></p>
<p class="superb-cf7-field"><label for="your-message">Brief Description</label>[textarea your-message id:your-message placeholder "Tell us about your project..."]</p>
<p class="superb-cf7-field superb-cf7-file"><label for="your-photos">Upload Photos of Your Space</label><span class="superb-cf7-file-drop"><span class="superb-cf7-file-hint">Drag &amp; drop or click to browse<br>JPG, PNG or PDF — max 10MB</span>[file your-photos id:your-photos limit:10mb filetypes:jpg|jpeg|png|pdf]</span></p>
<p class="superb-cf7-submit">[submit class:superb-btn-orange "Get My Free Quote"]</p>
</div>';
}

/**
 * Full contact page form markup.
 */
function superb_cf7_full_form_markup() {
	return '
[hidden form-source id:form-source][hidden lead-source id:lead-source][hidden lead-page id:lead-page]
<div class="superb-cf7-grid">
<p class="superb-cf7-field"><label for="your-name">Full Name *</label>[text* your-name id:your-name autocomplete:name placeholder "Your full name"]</p>
<p class="superb-cf7-row"><span class="superb-cf7-field"><label for="your-phone">Phone Number *</label>[tel* your-phone id:your-phone autocomplete:tel placeholder "04XX XXX XXX"]</span><span class="superb-cf7-field"><label for="your-email">Email Address *</label>[email* your-email id:your-email autocomplete:email placeholder "you@email.com"]</span></p>
<p class="superb-cf7-field"><label for="your-suburb">Property Suburb *</label>[text* your-suburb id:your-suburb placeholder "e.g. Hawthorn"]</p>
<p class="superb-cf7-row"><span class="superb-cf7-field"><label for="your-rooms">Number of Rooms</label>[select your-rooms id:your-rooms "Select..." "1" "2" "3" "4" "5" "6+"]</span><span class="superb-cf7-field"><label for="property-type">Property Type</label>[select property-type id:property-type "Select..." "House" "Apartment" "Townhouse" "Commercial" "Other"]</span></p>
<p class="superb-cf7-field"><label for="your-service">Service Required</label>[select your-service id:your-service "Select..." "Interior & Exterior Painting" "Kitchen Cabinet & TwoPak" "Special Finishes" "Apartment Painting" "New Build" "Commercial" "Not Sure"]</p>
<p class="superb-cf7-field"><label for="your-message">Tell Us About Your Project</label>[textarea your-message id:your-message placeholder "Describe the rooms, current paint condition, any specific requirements..."]</p>
<p class="superb-cf7-field superb-cf7-file"><label for="your-photos">Upload Photos of Your Space</label><span class="superb-cf7-file-drop"><span class="superb-cf7-file-hint">Drag &amp; drop or click to browse<br>JPG, PNG or PDF — max 10MB</span>[file your-photos id:your-photos limit:10mb filetypes:jpg|jpeg|png|pdf]</span></p>
<p class="superb-cf7-submit">[submit class:superb-btn-orange "Submit My Quote Request"]</p>
</div>';
}

/**
 * Create or update a CF7 form by title.
 *
 * @param string $title    Form title.
 * @param string $form     Form markup.
 * @param string $form_key hero|quick|full — selects mail subject and attachments.
 * @return int Form post ID.
 */
function superb_cf7_upsert_form( $title, $form, $form_key = 'quick' ) {
	if ( ! function_exists( 'wpcf7_save_contact_form' ) ) {
		return 0;
	}

	$existing_id = 0;
	$all_forms   = get_posts(
		array(
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => -1,
			'post_status'    => 'any',
		)
	);
	foreach ( $all_forms as $form_post ) {
		if ( $form_post->post_title === $title ) {
			$existing_id = $form_post->ID;
			break;
		}
	}

	$data = array(
		'id'       => $existing_id ? $existing_id : -1,
		'title'    => $title,
		'form'     => $form,
		'mail'     => superb_cf7_mail_template( $form_key ),
		'messages' => superb_cf7_form_messages(),
	);

	$saved = wpcf7_save_contact_form( $data, 'save' );
	return $saved ? $saved->id() : 0;
}

/**
 * Ensure CF7 quote forms exist; store IDs in options.
 */
function superb_cf7_create_forms() {
	$hero_id  = superb_cf7_upsert_form( 'Superb Hero Quote', superb_cf7_hero_form_markup(), 'hero' );
	$quick_id = superb_cf7_upsert_form( 'Superb Quick Quote', superb_cf7_quick_form_markup(), 'quick' );
	$full_id  = superb_cf7_upsert_form( 'Superb Full Quote', superb_cf7_full_form_markup(), 'full' );

	if ( $hero_id ) {
		update_option( 'superb_cf7_hero_id', $hero_id );
	}
	if ( $quick_id ) {
		update_option( 'superb_cf7_quick_id', $quick_id );
	}
	if ( $full_id ) {
		update_option( 'superb_cf7_full_id', $full_id );
	}

	return array(
		'hero'  => $hero_id,
		'quick' => $quick_id,
		'full'  => $full_id,
	);
}

/**
 * Style CF7 submit buttons.
 *
 * @param string|array $class Class attribute value.
 * @return string
 */
function superb_cf7_form_class_attr( $class ) {
	if ( is_array( $class ) ) {
		$class[] = 'superb-cf7-form';
		return $class;
	}

	return trim( (string) $class . ' superb-cf7-form' );
}
add_filter( 'wpcf7_form_class_attr', 'superb_cf7_form_class_attr' );

/**
 * Disable CF7 default CSS — theme styles forms.
 */
add_filter( 'wpcf7_load_css', '__return_false' );

/**
 * Prevent CF7 from wrapping fields in extra paragraph tags.
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Whether theme CF7 markup needs re-syncing into the database.
 *
 * @return bool
 */
function superb_cf7_forms_need_sync() {
	return get_option( 'superb_cf7_forms_version' ) !== SUPERB_CF7_FORMS_VERSION;
}

/**
 * Keep CF7 form posts aligned with inc/cf7.php markup (local + after theme updates).
 */
function superb_maybe_sync_cf7_forms() {
	if ( ! function_exists( 'superb_cf7_create_forms' ) ) {
		return;
	}
	if ( ! superb_cf7_forms_need_sync() ) {
		return;
	}
	superb_cf7_create_forms();
	update_option( 'superb_cf7_forms_version', SUPERB_CF7_FORMS_VERSION );
}
add_action( 'init', 'superb_maybe_sync_cf7_forms', 2 );

/**
 * Ensure form-source is set even if JavaScript is disabled.
 *
 * @param array<string, string> $posted Posted field values.
 * @return array<string, string>
 */
function superb_cf7_enrich_posted_data( $posted ) {
	if ( empty( $posted['form-source'] ) && ! empty( $posted['lead-page'] ) ) {
		$posted['form-source'] = superb_cf7_form_source_from_url( $posted['lead-page'] );
	}

	return $posted;
}
add_filter( 'wpcf7_posted_data', 'superb_cf7_enrich_posted_data' );

/**
 * Always deliver theme quote forms to the business inbox with CC copies.
 *
 * @param array<string, string> $components Mail components.
 * @param WPCF7_ContactForm       $contact_form Form instance.
 * @param string                  $mail_type mail or mail_2.
 * @return array<string, string>
 */
function superb_cf7_mail_components( $components, $contact_form, $mail_type ) {
	if ( 'mail' !== superb_cf7_get_mail_template_name( $mail_type ) ) {
		return $components;
	}

	if ( ! in_array( $contact_form->title(), superb_cf7_form_titles(), true ) ) {
		return $components;
	}

	$components['recipient']          = SUPERB_EMAIL;
	$components['additional_headers'] = superb_cf7_resolve_reply_to_in_headers(
		superb_cf7_mail_additional_headers(
			$components['additional_headers'] ?? 'Reply-To: [your-email]'
		)
	);

	$attachment_forms = array(
		'Superb Quick Quote' => true,
		'Superb Full Quote'  => true,
	);
	if ( isset( $attachment_forms[ $contact_form->title() ] ) ) {
		$components['attachments'] = 'your-photos';
	}

	return $components;
}
add_filter( 'wpcf7_mail_components', 'superb_cf7_mail_components', 10, 3 );

/**
 * Ensure CC recipients are on the PHPMailer instance (FluentSMTP reads these directly).
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer Mailer instance.
 */
function superb_cf7_phpmailer_add_cc( $phpmailer ) {
	if ( ! class_exists( 'WPCF7_Submission' ) ) {
		return;
	}

	$submission = WPCF7_Submission::get_instance();
	if ( ! $submission ) {
		return;
	}

	$contact_form = $submission->get_contact_form();
	if ( ! $contact_form || ! in_array( $contact_form->title(), superb_cf7_form_titles(), true ) ) {
		return;
	}

	$cc_emails = superb_get_form_cc_emails();
	if ( ! $cc_emails ) {
		return;
	}

	$existing = array();
	if ( method_exists( $phpmailer, 'getCcAddresses' ) ) {
		foreach ( $phpmailer->getCcAddresses() as $address ) {
			$existing[] = strtolower( $address[0] );
		}
	}

	foreach ( $cc_emails as $cc_email ) {
		$cc_email = sanitize_email( $cc_email );
		if ( ! $cc_email || ! is_email( $cc_email ) ) {
			continue;
		}
		if ( in_array( strtolower( $cc_email ), $existing, true ) ) {
			continue;
		}
		try {
			$phpmailer->addCc( $cc_email );
			$existing[] = strtolower( $cc_email );
		} catch ( Exception $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			continue;
		}
	}
}
add_action( 'phpmailer_init', 'superb_cf7_phpmailer_add_cc', 20 );
