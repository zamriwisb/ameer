<?php
/**
 * Newsletter: subscriber CPT, form renderer, admin-post handler.
 *
 * Signups are stored in WordPress (Subscribers list in wp-admin). This is the
 * integration point for Mailchimp/MailerLite later — swap the handler body for
 * an API call.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the (private) subscriber post type.
 */
add_action( 'init', 'ameer_register_subscriber_cpt' );
function ameer_register_subscriber_cpt() {
	register_post_type(
		'ameer_subscriber',
		array(
			'labels'          => array(
				'name'          => __( 'Subscribers', 'ameer' ),
				'singular_name' => __( 'Subscriber', 'ameer' ),
				'menu_name'     => __( 'Subscribers', 'ameer' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-email',
			'menu_position'   => 26,
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
			'supports'        => array( 'title' ),
		)
	);
}

/**
 * Render a newsletter signup form.
 *
 * @param string $form_class Wrapper class (e.g. "mini-newsletter").
 * @param string $button     Button label.
 */
function ameer_newsletter_form( $form_class = 'mini-newsletter', $button = '' ) {
	if ( '' === $button ) {
		$button = __( 'Join', 'ameer' );
	}
	$subscribed = isset( $_GET['subscribed'] ) ? sanitize_key( wp_unslash( $_GET['subscribed'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<form class="<?php echo esc_attr( $form_class ); ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="ameer_subscribe" />
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( ameer_current_url() ); ?>" />
		<?php wp_nonce_field( 'ameer_subscribe', 'ameer_subscribe_nonce' ); ?>
		<input type="email" name="ameer_email" placeholder="your@email.com" required aria-label="<?php esc_attr_e( 'Email address', 'ameer' ); ?>" />
		<button type="submit" class="btn btn-primary"><?php echo esc_html( $button ); ?></button>
	</form>
	<?php if ( '1' === $subscribed ) : ?>
		<small class="newsletter-thanks"><?php esc_html_e( 'Thank you — you&rsquo;re on the list! 🎉', 'ameer' ); ?></small>
	<?php elseif ( 'invalid' === $subscribed ) : ?>
		<small class="newsletter-thanks"><?php esc_html_e( 'Please enter a valid email address.', 'ameer' ); ?></small>
	<?php endif; ?>
	<?php
}

/**
 * Best-effort current URL for redirect-back.
 *
 * @return string
 */
function ameer_current_url() {
	$req = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	return home_url( $req );
}

/**
 * Handle the signup (logged in + logged out).
 */
add_action( 'admin_post_ameer_subscribe', 'ameer_handle_subscribe' );
add_action( 'admin_post_nopriv_ameer_subscribe', 'ameer_handle_subscribe' );
function ameer_handle_subscribe() {
	$redirect = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/' );

	if ( ! isset( $_POST['ameer_subscribe_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ameer_subscribe_nonce'] ) ), 'ameer_subscribe' ) ) {
		wp_safe_redirect( add_query_arg( 'subscribed', 'invalid', $redirect ) );
		exit;
	}

	$email = isset( $_POST['ameer_email'] ) ? sanitize_email( wp_unslash( $_POST['ameer_email'] ) ) : '';
	if ( ! $email || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'subscribed', 'invalid', $redirect ) );
		exit;
	}

	// Dedupe by title.
	$existing = get_posts(
		array(
			'post_type'              => 'ameer_subscriber',
			'title'                  => $email,
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	if ( empty( $existing ) ) {
		wp_insert_post(
			array(
				'post_type'   => 'ameer_subscriber',
				'post_title'  => $email,
				'post_status' => 'publish',
			)
		);
	}

	wp_safe_redirect( add_query_arg( 'subscribed', '1', remove_query_arg( 'subscribed', $redirect ) ) . '#contact' );
	exit;
}
