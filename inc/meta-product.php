<?php
/**
 * WooCommerce product meta: Ameer-specific structured content (no ACF).
 *
 * Scalars are stored as plain meta; each repeater is stored as ONE serialized
 * array under a single meta key (atomic, no index drift).
 *
 * Meta keys:
 *   _ameer_eyebrow       (string)  category-style label above the title
 *   _ameer_price_note    (string)  e.g. "/ 60 servings"
 *   _ameer_dosage_note   (textarea) the .dosage-fine line
 *   _ameer_benefit_tags  (string[]) short inline tags under the short description
 *   _ameer_benefits      ([{icon,color,title,text}])
 *   _ameer_ingredients   ([{emoji,title,text}])
 *   _ameer_dosage_steps  ([{title,text}])
 *   _ameer_dosage_table  ([{age,serving}])
 *   _ameer_faq           ([{q,a}])
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Repeater field definitions, shared by render + save.
 *
 * @return array
 */
function ameer_product_repeaters() {
	return array(
		'_ameer_benefits'     => array(
			'title'  => __( 'Benefits', 'ameer' ),
			'fields' => array(
				'icon'  => array( 'label' => __( 'Icon (emoji)', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field', 'size' => 4 ),
				'color' => array( 'label' => __( 'Color (hex)', 'ameer' ), 'type' => 'color', 'sanitize' => 'sanitize_hex_color' ),
				'title' => array( 'label' => __( 'Title', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
				'text'  => array( 'label' => __( 'Text', 'ameer' ), 'type' => 'textarea', 'sanitize' => 'wp_kses_post' ),
			),
		),
		'_ameer_ingredients'  => array(
			'title'  => __( 'Ingredients', 'ameer' ),
			'fields' => array(
				'emoji' => array( 'label' => __( 'Emoji', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field', 'size' => 4 ),
				'title' => array( 'label' => __( 'Title', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
				'text'  => array( 'label' => __( 'Text', 'ameer' ), 'type' => 'textarea', 'sanitize' => 'wp_kses_post' ),
			),
		),
		'_ameer_dosage_steps' => array(
			'title'  => __( 'How to use — steps', 'ameer' ),
			'fields' => array(
				'title' => array( 'label' => __( 'Step title', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
				'text'  => array( 'label' => __( 'Step text', 'ameer' ), 'type' => 'textarea', 'sanitize' => 'wp_kses_post' ),
			),
		),
		'_ameer_dosage_table' => array(
			'title'  => __( 'Serving by age (table)', 'ameer' ),
			'fields' => array(
				'age'     => array( 'label' => __( 'Age', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
				'serving' => array( 'label' => __( 'Serving', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
			),
		),
		'_ameer_faq'          => array(
			'title'  => __( 'FAQ', 'ameer' ),
			'fields' => array(
				'q' => array( 'label' => __( 'Question', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
				'a' => array( 'label' => __( 'Answer', 'ameer' ), 'type' => 'textarea', 'sanitize' => 'wp_kses_post' ),
			),
		),
		'_ameer_reviews'      => array(
			'title'  => __( 'Featured reviews (cards)', 'ameer' ),
			'fields' => array(
				'quote'  => array( 'label' => __( 'Quote', 'ameer' ), 'type' => 'textarea', 'sanitize' => 'wp_kses_post' ),
				'name'   => array( 'label' => __( 'Name', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
				'city'   => array( 'label' => __( 'City', 'ameer' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
				'rating' => array( 'label' => __( 'Stars 1-5', 'ameer' ), 'type' => 'text', 'sanitize' => 'absint', 'size' => 4 ),
			),
		),
	);
}

add_action( 'add_meta_boxes', 'ameer_product_meta_box' );
function ameer_product_meta_box() {
	add_meta_box(
		'ameer_product_details',
		__( 'Ameer Product Details', 'ameer' ),
		'ameer_product_meta_cb',
		'product',
		'normal',
		'high'
	);
}

function ameer_product_meta_cb( $post ) {
	wp_nonce_field( 'ameer_product_save', 'ameer_product_nonce' );

	$eyebrow     = get_post_meta( $post->ID, '_ameer_eyebrow', true );
	$price_note  = get_post_meta( $post->ID, '_ameer_price_note', true );
	$dosage_note = get_post_meta( $post->ID, '_ameer_dosage_note', true );
	$tags        = (array) get_post_meta( $post->ID, '_ameer_benefit_tags', true );
	$tags        = array_filter( $tags );
	$badge       = get_post_meta( $post->ID, '_ameer_badge', true );
	?>
	<style>
		.ameer-meta .ameer-field{margin:0 0 1rem}
		.ameer-meta label{font-weight:600;display:block;margin-bottom:.25rem}
		.ameer-rep-row{display:flex;gap:.5rem;align-items:flex-start;margin-bottom:.5rem;padding:.5rem;background:#f6f7f7;border-radius:6px}
		.ameer-rep-row .f{flex:1}
		.ameer-rep-row .f.small{flex:0 0 90px}
		.ameer-rep-row textarea{min-height:48px}
		.ameer-rep-remove{color:#b32d2e;border-color:#b32d2e}
		.ameer-rep h4{margin:1.25rem 0 .25rem}
	</style>
	<div class="ameer-meta">
		<div class="ameer-field">
			<label for="ameer_eyebrow"><?php esc_html_e( 'Eyebrow / tag (blank = product category)', 'ameer' ); ?></label>
			<input type="text" id="ameer_eyebrow" name="_ameer_eyebrow" value="<?php echo esc_attr( $eyebrow ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Calm &amp; Relaxation', 'ameer' ); ?>" />
		</div>
		<div class="ameer-field">
			<label for="ameer_price_note"><?php esc_html_e( 'Price note', 'ameer' ); ?></label>
			<input type="text" id="ameer_price_note" name="_ameer_price_note" value="<?php echo esc_attr( $price_note ); ?>" class="widefat" placeholder="<?php esc_attr_e( '/ 60 servings', 'ameer' ); ?>" />
		</div>
		<div class="ameer-field">
			<label for="ameer_badge"><?php esc_html_e( 'Gallery badge (e.g. Best seller)', 'ameer' ); ?></label>
			<input type="text" id="ameer_badge" name="_ameer_badge" value="<?php echo esc_attr( $badge ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'Best seller', 'ameer' ); ?>" />
		</div>
		<div class="ameer-field">
			<label><?php esc_html_e( 'Benefit tags (short list under the short description)', 'ameer' ); ?></label>
			<?php ameer_render_tag_repeater( '_ameer_benefit_tags', $tags ); ?>
		</div>

		<?php
		$repeaters = ameer_product_repeaters();
		foreach ( $repeaters as $key => $def ) {
			$rows = (array) get_post_meta( $post->ID, $key, true );
			echo '<div class="ameer-rep"><h4>' . esc_html( $def['title'] ) . '</h4>';
			ameer_render_repeater( $key, $def['fields'], $rows );
			echo '</div>';
		}
		?>

		<div class="ameer-field" style="margin-top:1rem">
			<label for="ameer_dosage_note"><?php esc_html_e( 'Dosage fine print', 'ameer' ); ?></label>
			<textarea id="ameer_dosage_note" name="_ameer_dosage_note" class="widefat" rows="2"><?php echo esc_textarea( $dosage_note ); ?></textarea>
		</div>
	</div>
	<?php
}

/**
 * Render one repeater (multi-field rows).
 *
 * @param string $key    Meta key.
 * @param array  $fields Field definitions.
 * @param array  $rows   Saved rows.
 */
function ameer_render_repeater( $key, $fields, $rows ) {
	$rows = is_array( $rows ) ? $rows : array();
	echo '<div class="ameer-rep-wrap" data-key="' . esc_attr( $key ) . '">';
	echo '<div class="ameer-rep-rows">';
	if ( $rows ) {
		foreach ( $rows as $row ) {
			ameer_render_repeater_row( $key, $fields, $row );
		}
	}
	echo '</div>';
	// Template row for JS cloning.
	echo '<script type="text/html" class="ameer-rep-tpl">';
	ameer_render_repeater_row( $key, $fields, array(), true );
	echo '</script>';
	echo '<button type="button" class="button ameer-rep-add">' . esc_html__( '+ Add row', 'ameer' ) . '</button>';
	echo '</div>';
}

/**
 * Render a single repeater row.
 *
 * @param string $key      Meta key.
 * @param array  $fields   Field definitions.
 * @param array  $row      Row values.
 * @param bool   $template Whether this is the JS template (uses __i__ index).
 */
function ameer_render_repeater_row( $key, $fields, $row = array(), $template = false ) {
	$i = $template ? '__i__' : (string) wp_rand( 100000, 999999 );
	echo '<div class="ameer-rep-row">';
	foreach ( $fields as $fname => $fdef ) {
		$name  = esc_attr( $key . '[' . $i . '][' . $fname . ']' );
		$value = isset( $row[ $fname ] ) ? $row[ $fname ] : '';
		$small = ( in_array( $fdef['type'], array( 'color' ), true ) || ! empty( $fdef['size'] ) ) ? ' small' : '';
		echo '<div class="f' . esc_attr( $small ) . '">';
		echo '<label>' . esc_html( $fdef['label'] ) . '</label>';
		if ( 'textarea' === $fdef['type'] ) {
			echo '<textarea name="' . $name . '" class="widefat">' . esc_textarea( $value ) . '</textarea>';
		} elseif ( 'color' === $fdef['type'] ) {
			echo '<input type="text" name="' . $name . '" value="' . esc_attr( $value ) . '" class="widefat" placeholder="#FA6255" />';
		} else {
			echo '<input type="text" name="' . $name . '" value="' . esc_attr( $value ) . '" class="widefat" />';
		}
		echo '</div>';
	}
	echo '<button type="button" class="button ameer-rep-remove" aria-label="' . esc_attr__( 'Remove row', 'ameer' ) . '">&times;</button>';
	echo '</div>';
}

/**
 * Simple single-value tag repeater.
 *
 * @param string $key  Meta key.
 * @param array  $rows Saved values.
 */
function ameer_render_tag_repeater( $key, $rows ) {
	$rows = is_array( $rows ) ? array_values( $rows ) : array();
	echo '<div class="ameer-rep-wrap" data-key="' . esc_attr( $key ) . '">';
	echo '<div class="ameer-rep-rows">';
	foreach ( $rows as $val ) {
		ameer_render_tag_row( $key, $val );
	}
	echo '</div>';
	echo '<script type="text/html" class="ameer-rep-tpl">';
	ameer_render_tag_row( $key, '', true );
	echo '</script>';
	echo '<button type="button" class="button ameer-rep-add">' . esc_html__( '+ Add tag', 'ameer' ) . '</button>';
	echo '</div>';
}

function ameer_render_tag_row( $key, $value = '', $template = false ) {
	$i    = $template ? '__i__' : (string) wp_rand( 100000, 999999 );
	$name = esc_attr( $key . '[' . $i . ']' );
	echo '<div class="ameer-rep-row">';
	echo '<div class="f"><input type="text" name="' . $name . '" value="' . esc_attr( $value ) . '" class="widefat" placeholder="😴 Calmer bedtimes" /></div>';
	echo '<button type="button" class="button ameer-rep-remove" aria-label="' . esc_attr__( 'Remove', 'ameer' ) . '">&times;</button>';
	echo '</div>';
}

add_action( 'save_post_product', 'ameer_product_save' );
function ameer_product_save( $post_id ) {
	if ( ! ameer_can_save_meta( $post_id, 'ameer_product_nonce', 'ameer_product_save' ) ) {
		return;
	}

	// Scalars.
	update_post_meta( $post_id, '_ameer_eyebrow', isset( $_POST['_ameer_eyebrow'] ) ? sanitize_text_field( wp_unslash( $_POST['_ameer_eyebrow'] ) ) : '' );
	update_post_meta( $post_id, '_ameer_price_note', isset( $_POST['_ameer_price_note'] ) ? sanitize_text_field( wp_unslash( $_POST['_ameer_price_note'] ) ) : '' );
	update_post_meta( $post_id, '_ameer_dosage_note', isset( $_POST['_ameer_dosage_note'] ) ? sanitize_textarea_field( wp_unslash( $_POST['_ameer_dosage_note'] ) ) : '' );
	update_post_meta( $post_id, '_ameer_badge', isset( $_POST['_ameer_badge'] ) ? sanitize_text_field( wp_unslash( $_POST['_ameer_badge'] ) ) : '' );

	// Benefit tags (single-value rows).
	$tags = array();
	if ( isset( $_POST['_ameer_benefit_tags'] ) && is_array( $_POST['_ameer_benefit_tags'] ) ) {
		foreach ( wp_unslash( $_POST['_ameer_benefit_tags'] ) as $val ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below.
			$val = sanitize_text_field( $val );
			if ( '' !== $val ) {
				$tags[] = $val;
			}
		}
	}
	update_post_meta( $post_id, '_ameer_benefit_tags', $tags );

	// Multi-field repeaters.
	$repeaters = ameer_product_repeaters();
	foreach ( $repeaters as $key => $def ) {
		$clean = array();
		if ( isset( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) ) {
			$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per-field below.
			foreach ( $raw as $row ) {
				if ( ! is_array( $row ) ) {
					continue;
				}
				$crow  = array();
				$empty = true;
				foreach ( $def['fields'] as $fname => $fdef ) {
					$val      = isset( $row[ $fname ] ) ? $row[ $fname ] : '';
					$sanitize = $fdef['sanitize'];
					$val      = is_callable( $sanitize ) ? call_user_func( $sanitize, $val ) : sanitize_text_field( $val );
					$crow[ $fname ] = $val;
					if ( '' !== trim( (string) $val ) ) {
						$empty = false;
					}
				}
				if ( ! $empty ) {
					$clean[] = $crow;
				}
			}
		}
		update_post_meta( $post_id, $key, $clean );
	}
}
