<?php
/**
 * Meta boxes for Events (tag, date, link) and Social Tiles (outbound URL).
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ----------------------------------------------------------------------- Events */

add_action( 'add_meta_boxes', 'ameer_event_meta_box' );
function ameer_event_meta_box() {
	add_meta_box(
		'ameer_event_details',
		__( 'Event Details', 'ameer' ),
		'ameer_event_meta_cb',
		'event',
		'side',
		'default'
	);
}

function ameer_event_meta_cb( $post ) {
	wp_nonce_field( 'ameer_event_save', 'ameer_event_nonce' );
	$tag  = get_post_meta( $post->ID, '_ameer_event_tag', true );
	$date = get_post_meta( $post->ID, '_ameer_event_date', true );
	$url  = get_post_meta( $post->ID, '_ameer_event_url', true );
	?>
	<p>
		<label for="ameer_event_tag"><strong><?php esc_html_e( 'Tag / label', 'ameer' ); ?></strong></label><br/>
		<input type="text" id="ameer_event_tag" name="ameer_event_tag" value="<?php echo esc_attr( $tag ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Pocket Talk', 'ameer' ); ?>" />
	</p>
	<p>
		<label for="ameer_event_date"><strong><?php esc_html_e( 'Date', 'ameer' ); ?></strong></label><br/>
		<input type="date" id="ameer_event_date" name="ameer_event_date" value="<?php echo esc_attr( $date ); ?>" class="widefat" />
	</p>
	<p>
		<label for="ameer_event_url"><strong><?php esc_html_e( 'Link (optional)', 'ameer' ); ?></strong></label><br/>
		<input type="url" id="ameer_event_url" name="ameer_event_url" value="<?php echo esc_url( $url ); ?>" class="widefat" placeholder="https://" />
	</p>
	<p class="description"><?php esc_html_e( 'Description goes in the main content box; image is the featured image.', 'ameer' ); ?></p>
	<?php
}

add_action( 'save_post_event', 'ameer_event_save' );
function ameer_event_save( $post_id ) {
	if ( ! ameer_can_save_meta( $post_id, 'ameer_event_nonce', 'ameer_event_save' ) ) {
		return;
	}
	update_post_meta( $post_id, '_ameer_event_tag', isset( $_POST['ameer_event_tag'] ) ? sanitize_text_field( wp_unslash( $_POST['ameer_event_tag'] ) ) : '' );
	update_post_meta( $post_id, '_ameer_event_date', isset( $_POST['ameer_event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['ameer_event_date'] ) ) : '' );
	update_post_meta( $post_id, '_ameer_event_url', isset( $_POST['ameer_event_url'] ) ? esc_url_raw( wp_unslash( $_POST['ameer_event_url'] ) ) : '' );
}

/* ------------------------------------------------------------------ Social tiles */

add_action( 'add_meta_boxes', 'ameer_social_meta_box' );
function ameer_social_meta_box() {
	add_meta_box(
		'ameer_social_details',
		__( 'Social Tile', 'ameer' ),
		'ameer_social_meta_cb',
		'social_tile',
		'side',
		'default'
	);
}

function ameer_social_meta_cb( $post ) {
	wp_nonce_field( 'ameer_social_save', 'ameer_social_nonce' );
	$url = get_post_meta( $post->ID, '_ameer_url', true );
	?>
	<p>
		<label for="ameer_social_url"><strong><?php esc_html_e( 'Outbound link', 'ameer' ); ?></strong></label><br/>
		<input type="url" id="ameer_social_url" name="ameer_social_url" value="<?php echo esc_url( $url ); ?>" class="widefat" placeholder="https://instagram.com/p/..." />
	</p>
	<p class="description"><?php esc_html_e( 'The tile image is the featured image. The title is used as the accessible label.', 'ameer' ); ?></p>
	<?php
}

add_action( 'save_post_social_tile', 'ameer_social_save' );
function ameer_social_save( $post_id ) {
	if ( ! ameer_can_save_meta( $post_id, 'ameer_social_nonce', 'ameer_social_save' ) ) {
		return;
	}
	update_post_meta( $post_id, '_ameer_url', isset( $_POST['ameer_social_url'] ) ? esc_url_raw( wp_unslash( $_POST['ameer_social_url'] ) ) : '' );
}
