<?php
/**
 * Testimonial meta box: reviewer city + star rating.
 * (Quote = post content, name = post title, photo = featured image.)
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'add_meta_boxes', 'ameer_testimonial_meta_box' );
function ameer_testimonial_meta_box() {
	add_meta_box(
		'ameer_testimonial_details',
		__( 'Testimonial Details', 'ameer' ),
		'ameer_testimonial_meta_cb',
		'testimonial',
		'side',
		'default'
	);
}

function ameer_testimonial_meta_cb( $post ) {
	wp_nonce_field( 'ameer_testimonial_save', 'ameer_testimonial_nonce' );
	$city   = get_post_meta( $post->ID, '_ameer_city', true );
	$rating = (int) get_post_meta( $post->ID, '_ameer_rating', true );
	$rating = $rating ? $rating : 5;
	?>
	<p>
		<label for="ameer_city"><strong><?php esc_html_e( 'City', 'ameer' ); ?></strong></label><br/>
		<input type="text" id="ameer_city" name="ameer_city" value="<?php echo esc_attr( $city ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Kuala Lumpur', 'ameer' ); ?>" />
	</p>
	<p>
		<label for="ameer_rating"><strong><?php esc_html_e( 'Star rating', 'ameer' ); ?></strong></label><br/>
		<select id="ameer_rating" name="ameer_rating" class="widefat">
			<?php for ( $r = 5; $r >= 1; $r-- ) : ?>
				<option value="<?php echo esc_attr( $r ); ?>" <?php selected( $rating, $r ); ?>><?php echo esc_html( str_repeat( '★', $r ) . ' (' . $r . ')' ); ?></option>
			<?php endfor; ?>
		</select>
	</p>
	<p class="description"><?php esc_html_e( 'Tip: the quote goes in the main content box; the reviewer name is the title; the photo is the featured image.', 'ameer' ); ?></p>
	<?php
}

add_action( 'save_post_testimonial', 'ameer_testimonial_save' );
function ameer_testimonial_save( $post_id ) {
	if ( ! ameer_can_save_meta( $post_id, 'ameer_testimonial_nonce', 'ameer_testimonial_save' ) ) {
		return;
	}
	$city = isset( $_POST['ameer_city'] ) ? sanitize_text_field( wp_unslash( $_POST['ameer_city'] ) ) : '';
	update_post_meta( $post_id, '_ameer_city', $city );

	$rating = isset( $_POST['ameer_rating'] ) ? (int) $_POST['ameer_rating'] : 5;
	$rating = min( 5, max( 1, $rating ) );
	update_post_meta( $post_id, '_ameer_rating', $rating );
}
