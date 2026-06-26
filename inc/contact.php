<?php
/**
 * Contact: message CPT, form renderer, admin-post handler.
 *
 * Submissions are stored in WordPress (Messages list in wp-admin) AND emailed
 * to the site admin. Mirrors inc/newsletter.php. Core-only, no plugin.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the (private, read-only) message post type.
 */
add_action( 'init', 'ameer_register_message_cpt' );
function ameer_register_message_cpt() {
	register_post_type(
		'ameer_message',
		array(
			'labels'          => array(
				'name'          => __( 'Messages', 'ameer' ),
				'singular_name' => __( 'Message', 'ameer' ),
				'menu_name'     => __( 'Messages', 'ameer' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 27,
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
			'supports'        => array( 'title', 'editor' ),
		)
	);
}

/**
 * Render the contact form.
 */
function ameer_contact_form() {
	$sent = isset( $_GET['sent'] ) ? sanitize_key( wp_unslash( $_GET['sent'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="ameer_contact" />
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( ameer_current_url() ); ?>" />
		<?php wp_nonce_field( 'ameer_contact', 'ameer_contact_nonce' ); ?>

		<p class="form-row">
			<label for="ameer-name"><?php esc_html_e( 'Your name', 'ameer' ); ?></label>
			<input type="text" id="ameer-name" name="ameer_name" required />
		</p>
		<p class="form-row">
			<label for="ameer-email"><?php esc_html_e( 'Email', 'ameer' ); ?></label>
			<input type="email" id="ameer-email" name="ameer_email" required />
		</p>
		<p class="form-row">
			<label for="ameer-phone"><?php esc_html_e( 'Phone (optional)', 'ameer' ); ?></label>
			<input type="tel" id="ameer-phone" name="ameer_phone" />
		</p>
		<p class="form-row">
			<label for="ameer-message"><?php esc_html_e( 'Message', 'ameer' ); ?></label>
			<textarea id="ameer-message" name="ameer_message" rows="5" required></textarea>
		</p>

		<?php // Honeypot — hidden from users, catches bots. ?>
		<p class="contact-hp" aria-hidden="true">
			<label for="ameer-website"><?php esc_html_e( 'Leave this field empty', 'ameer' ); ?></label>
			<input type="text" id="ameer-website" name="ameer_website" tabindex="-1" autocomplete="off" />
		</p>

		<button type="submit" class="btn btn-primary btn-large"><?php esc_html_e( 'Send Message', 'ameer' ); ?></button>
	</form>
	<?php if ( '1' === $sent ) : ?>
		<p class="contact-notice contact-notice-ok"><?php esc_html_e( 'Thank you — we’ve received your message and will reply soon! 🎉', 'ameer' ); ?></p>
	<?php elseif ( 'invalid' === $sent ) : ?>
		<p class="contact-notice contact-notice-err"><?php esc_html_e( 'Please fill in your name, a valid email, and a message.', 'ameer' ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Handle the submission (logged in + logged out).
 */
add_action( 'admin_post_ameer_contact', 'ameer_handle_contact' );
add_action( 'admin_post_nopriv_ameer_contact', 'ameer_handle_contact' );
function ameer_handle_contact() {
	$redirect = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/' );
	$redirect = remove_query_arg( 'sent', $redirect );

	// Nonce.
	if ( ! isset( $_POST['ameer_contact_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ameer_contact_nonce'] ) ), 'ameer_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'invalid', $redirect ) . '#contact-form' );
		exit;
	}

	// Honeypot — silently accept (no store, no email) so bots think they won.
	$hp = isset( $_POST['ameer_website'] ) ? trim( (string) wp_unslash( $_POST['ameer_website'] ) ) : '';
	if ( '' !== $hp ) {
		wp_safe_redirect( add_query_arg( 'sent', '1', $redirect ) . '#contact-form' );
		exit;
	}

	$name    = isset( $_POST['ameer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ameer_name'] ) ) : '';
	$email   = isset( $_POST['ameer_email'] ) ? sanitize_email( wp_unslash( $_POST['ameer_email'] ) ) : '';
	$phone   = isset( $_POST['ameer_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['ameer_phone'] ) ) : '';
	$message = isset( $_POST['ameer_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ameer_message'] ) ) : '';

	if ( '' === $name || ! $email || ! is_email( $email ) || '' === $message ) {
		wp_safe_redirect( add_query_arg( 'sent', 'invalid', $redirect ) . '#contact-form' );
		exit;
	}

	// Store the message (never lost, even if email fails).
	$post_id = wp_insert_post(
		array(
			'post_type'    => 'ameer_message',
			'post_title'   => $name,
			'post_content' => $message,
			'post_status'  => 'publish',
		)
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_ameer_msg_email', $email );
		if ( '' !== $phone ) {
			update_post_meta( $post_id, '_ameer_msg_phone', $phone );
		}
	}

	// Email the admin (best-effort).
	$admin   = get_option( 'admin_email' );
	$subject = sprintf(
		/* translators: %s: sender name. */
		__( 'New contact message from %s', 'ameer' ),
		$name
	);
	$body = sprintf(
		"Name: %s\nEmail: %s\nPhone: %s\n\n%s\n",
		$name,
		$email,
		'' !== $phone ? $phone : '—',
		$message
	);
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	wp_mail( $admin, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'sent', '1', $redirect ) . '#contact-form' );
	exit;
}
