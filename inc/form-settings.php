<?php
/**
 * Admin settings — quote form CC notification emails.
 *
 * @package Kadence_Child_Superb
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register settings page under Settings.
 */
function superb_form_settings_menu() {
	add_options_page(
		__( 'Quote Form Emails', 'kadence-child-superb' ),
		__( 'Quote Form Emails', 'kadence-child-superb' ),
		'manage_options',
		'superb-form-emails',
		'superb_form_settings_page'
	);
}
add_action( 'admin_menu', 'superb_form_settings_menu' );

/**
 * Seed the option with defaults on first admin load.
 */
function superb_form_settings_maybe_seed_defaults() {
	if ( false !== get_option( SUPERB_FORM_CC_OPTION, false ) ) {
		return;
	}

	update_option( SUPERB_FORM_CC_OPTION, superb_get_form_cc_email_defaults() );
}
add_action( 'admin_init', 'superb_form_settings_maybe_seed_defaults', 5 );

/**
 * Handle save / add / remove actions.
 */
function superb_form_settings_handle_post() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( empty( $_POST['superb_form_emails_action'] ) ) {
		return;
	}

	if ( superb_form_cc_emails_are_locked() ) {
		return;
	}

	check_admin_referer( 'superb_form_emails_save', 'superb_form_emails_nonce' );

	$action = sanitize_key( wp_unslash( $_POST['superb_form_emails_action'] ) );
	$emails = superb_get_form_cc_emails();

	if ( 'save' === $action ) {
		$raw = isset( $_POST['superb_form_cc_emails'] ) ? (array) wp_unslash( $_POST['superb_form_cc_emails'] ) : array();
		$emails = array();

		foreach ( $raw as $email ) {
			$email = sanitize_email( trim( (string) $email ) );
			if ( $email && is_email( $email ) ) {
				$emails[] = $email;
			}
		}

		$emails = array_values( array_unique( $emails ) );
		update_option( SUPERB_FORM_CC_OPTION, $emails );

		add_settings_error(
			'superb_form_emails',
			'superb_form_emails_saved',
			__( 'CC email list updated.', 'kadence-child-superb' ),
			'updated'
		);
		return;
	}

	if ( 'add' === $action ) {
		$new_email = isset( $_POST['superb_form_new_email'] ) ? sanitize_email( wp_unslash( $_POST['superb_form_new_email'] ) ) : '';

		if ( ! $new_email || ! is_email( $new_email ) ) {
			add_settings_error(
				'superb_form_emails',
				'superb_form_emails_invalid',
				__( 'Please enter a valid email address.', 'kadence-child-superb' ),
				'error'
			);
			return;
		}

		if ( ! in_array( $new_email, $emails, true ) ) {
			$emails[] = $new_email;
			update_option( SUPERB_FORM_CC_OPTION, $emails );
		}

		add_settings_error(
			'superb_form_emails',
			'superb_form_emails_added',
			__( 'Email added.', 'kadence-child-superb' ),
			'updated'
		);
		return;
	}

	if ( 'remove' === $action ) {
		$remove = isset( $_POST['superb_form_remove_email'] ) ? sanitize_email( wp_unslash( $_POST['superb_form_remove_email'] ) ) : '';

		if ( $remove ) {
			$emails = array_values(
				array_filter(
					$emails,
					static function ( $email ) use ( $remove ) {
						return $email !== $remove;
					}
				)
			);
			update_option( SUPERB_FORM_CC_OPTION, $emails );
		}

		add_settings_error(
			'superb_form_emails',
			'superb_form_emails_removed',
			__( 'Email removed.', 'kadence-child-superb' ),
			'updated'
		);
	}
}
add_action( 'admin_init', 'superb_form_settings_handle_post', 9 );

/**
 * Settings page markup.
 */
function superb_form_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$locked   = superb_form_cc_emails_are_locked();
	$cc_list  = superb_get_form_cc_emails();
	$primary  = defined( 'SUPERB_EMAIL' ) ? SUPERB_EMAIL : 'info@superbpainting.com';
	$form_names = function_exists( 'superb_cf7_form_titles' ) ? superb_cf7_form_titles() : array(
		'Superb Hero Quote',
		'Superb Quick Quote',
		'Superb Full Quote',
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Quote Form Emails', 'kadence-child-superb' ); ?></h1>

		<?php settings_errors( 'superb_form_emails' ); ?>

		<p>
			<?php esc_html_e( 'Configure who receives quote form submissions from the website.', 'kadence-child-superb' ); ?>
		</p>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php esc_html_e( 'Primary recipient (To)', 'kadence-child-superb' ); ?></th>
				<td>
					<code><?php echo esc_html( $primary ); ?></code>
					<p class="description">
						<?php esc_html_e( 'All quote forms always send here first. This is set in the theme code (SUPERB_EMAIL).', 'kadence-child-superb' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Forms covered', 'kadence-child-superb' ); ?></th>
				<td>
					<ul style="margin:0;">
						<?php foreach ( $form_names as $name ) : ?>
							<li><?php echo esc_html( $name ); ?></li>
						<?php endforeach; ?>
					</ul>
				</td>
			</tr>
		</table>

		<?php if ( $locked ) : ?>
			<div class="notice notice-warning inline">
				<p>
					<?php esc_html_e( 'CC emails are defined in functions.php (SUPERB_FORM_CC_EMAILS) and cannot be changed here.', 'kadence-child-superb' ); ?>
				</p>
			</div>
			<h2><?php esc_html_e( 'CC recipients (read-only)', 'kadence-child-superb' ); ?></h2>
			<?php if ( $cc_list ) : ?>
				<ul>
					<?php foreach ( $cc_list as $email ) : ?>
						<li><code><?php echo esc_html( $email ); ?></code></li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p><?php esc_html_e( 'No CC addresses configured.', 'kadence-child-superb' ); ?></p>
			<?php endif; ?>
		<?php else : ?>
			<h2><?php esc_html_e( 'CC recipients', 'kadence-child-superb' ); ?></h2>
			<p class="description" style="margin-bottom:12px;">
				<?php esc_html_e( 'These addresses receive a copy of every quote form submission (homepage, contact, services, and suburbs forms).', 'kadence-child-superb' ); ?>
			</p>

			<form method="post" action="">
				<?php wp_nonce_field( 'superb_form_emails_save', 'superb_form_emails_nonce' ); ?>
				<input type="hidden" name="superb_form_emails_action" value="save">

				<table class="widefat striped" style="max-width:640px;">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Email address', 'kadence-child-superb' ); ?></th>
						</tr>
					</thead>
					<tbody id="superb-cc-email-rows">
						<?php
						$rows = $cc_list;
						$rows[] = '';
						foreach ( $rows as $email ) :
							?>
							<tr>
								<td>
									<input
										type="email"
										name="superb_form_cc_emails[]"
										value="<?php echo esc_attr( $email ); ?>"
										class="regular-text"
										placeholder="<?php esc_attr_e( 'name@example.com', 'kadence-child-superb' ); ?>"
									>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<p class="submit" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
					<?php submit_button( __( 'Save CC emails', 'kadence-child-superb' ), 'primary', 'submit', false ); ?>
					<button type="button" class="button" id="superb-add-cc-row">
						<?php esc_html_e( '+ Add another row', 'kadence-child-superb' ); ?>
					</button>
				</p>
			</form>

			<?php if ( $cc_list ) : ?>
				<h2><?php esc_html_e( 'Quick remove', 'kadence-child-superb' ); ?></h2>
				<ul style="list-style:none;margin:0;padding:0;max-width:640px;">
					<?php foreach ( $cc_list as $email ) : ?>
						<li style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid #dcdcde;">
							<code><?php echo esc_html( $email ); ?></code>
							<form method="post" action="" style="margin:0;">
								<?php wp_nonce_field( 'superb_form_emails_save', 'superb_form_emails_nonce' ); ?>
								<input type="hidden" name="superb_form_emails_action" value="remove">
								<input type="hidden" name="superb_form_remove_email" value="<?php echo esc_attr( $email ); ?>">
								<button type="submit" class="button button-link-delete" onclick="return confirm('<?php echo esc_js( __( 'Remove this email from CC notifications?', 'kadence-child-superb' ) ); ?>');">
									<?php esc_html_e( 'Remove', 'kadence-child-superb' ); ?>
								</button>
							</form>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<script>
			(function () {
				var btn = document.getElementById('superb-add-cc-row');
				var tbody = document.getElementById('superb-cc-email-rows');
				if (!btn || !tbody) return;
				btn.addEventListener('click', function () {
					var row = document.createElement('tr');
					row.innerHTML = '<td><input type="email" name="superb_form_cc_emails[]" value="" class="regular-text" placeholder="<?php echo esc_js( __( 'name@example.com', 'kadence-child-superb' ) ); ?>"></td>';
					tbody.appendChild(row);
					var input = row.querySelector('input');
					if (input) input.focus();
				});
			}());
			</script>
		<?php endif; ?>
	</div>
	<?php
}
