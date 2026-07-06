<?php
/**
 * Small reusable helpers.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Versioned asset URL: appends filemtime() so changes bust the cache.
 *
 * @param string $relative Path relative to the theme root, e.g. "css/base.css".
 * @return string Full URL.
 */
function ameer_asset( $relative ) {
	return AMEER_URI . '/' . ltrim( $relative, '/' );
}

/**
 * A run of star glyphs forced to TEXT presentation via the U+FE0E variation
 * selector. Bare "★" renders as a color emoji on many platforms, which sits low
 * in its glyph box (misaligning it with adjacent text); the selector makes it a
 * flat, baseline-aligned font glyph that also honours the CSS `color`.
 *
 * @param int $count Number of stars.
 * @return string e.g. "★★★★★" (each followed by U+FE0E).
 */
function ameer_stars( $count ) {
	return str_repeat( "\u{2605}\u{FE0E}", max( 0, (int) $count ) );
}

/**
 * filemtime() based version string for a theme-relative file.
 *
 * @param string $relative Path relative to the theme root.
 * @return string|null Version or null when the file is missing.
 */
function ameer_ver( $relative ) {
	$path = AMEER_DIR . '/' . ltrim( $relative, '/' );
	return file_exists( $path ) ? (string) filemtime( $path ) : AMEER_VERSION;
}

/**
 * Estimated reading time for a piece of content.
 *
 * @param string $content Raw post content. Defaults to the current post.
 * @return string e.g. "4 min read".
 */
function ameer_reading_time( $content = '' ) {
	if ( '' === $content ) {
		$content = get_post_field( 'post_content', get_the_ID() );
	}
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	/* translators: %d: number of minutes. */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'ameer' ), $minutes );
}

/**
 * Render an SVG sprite symbol via <use>.
 *
 * @param string $id      Symbol id without the leading "i-", e.g. "balloon".
 * @param string $class   Optional CSS class for the <svg>.
 * @param string $viewbox Optional viewBox; sensible defaults per symbol when omitted.
 * @param array  $attrs   Extra attributes (e.g. ['style' => 'color:#FA6255']).
 */
function ameer_icon( $id, $class = '', $viewbox = '', $attrs = array() ) {
	$defaults = array(
		'balloon'    => '0 0 80 110',
		'cloud'      => '0 0 160 80',
		'sun'        => '0 0 160 160',
		'sparkle'    => '0 0 30 30',
		'quote'      => '0 0 40 36',
		'tree'       => '0 0 60 90',
		'boat'       => '0 0 80 70',
		'bird'       => '0 0 60 28',
		'kite'       => '0 0 50 90',
		'cloud-mini' => '0 0 80 40',
		'lorry'      => '0 0 160 80',
	);
	if ( '' === $viewbox && isset( $defaults[ $id ] ) ) {
		$viewbox = $defaults[ $id ];
	}

	$attr_str = '';
	foreach ( $attrs as $k => $v ) {
		$attr_str .= sprintf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) );
	}

	printf(
		'<svg%s viewBox="%s" aria-hidden="true"%s><use href="#i-%s"/></svg>',
		$class ? ' class="' . esc_attr( $class ) . '"' : '',
		esc_attr( $viewbox ),
		$attr_str, // already escaped above.
		esc_attr( $id )
	);
}

/**
 * Shop URL — WooCommerce shop page when available, else the homepage #shop anchor.
 *
 * @return string
 */
function ameer_shop_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$url = wc_get_page_permalink( 'shop' );
		if ( $url ) {
			return $url;
		}
	}
	return home_url( '/#shop' );
}

/**
 * Cart URL — WooCommerce cart when available, else the shop fallback.
 *
 * @return string
 */
function ameer_cart_url() {
	if ( function_exists( 'wc_get_cart_url' ) ) {
		return wc_get_cart_url();
	}
	return ameer_shop_url();
}

/**
 * Cart item-count badge markup. Empty string when cart is empty or WC absent.
 * Kept in sync live via the woocommerce_add_to_cart_fragments filter (inc/woocommerce.php).
 *
 * @return string
 */
function ameer_cart_count_html() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return '';
	}
	$count = WC()->cart->get_cart_contents_count();
	return sprintf(
		'<span class="cart-count"%s>%s</span>',
		$count ? '' : ' hidden',
		esc_html( $count )
	);
}

/**
 * Render the "More from our kitchen" related-articles section for a post.
 * Same-category posts (excluding current), limit 3.
 *
 * @param int $post_id Current post id.
 */
function ameer_related_articles( $post_id ) {
	$cats     = wp_get_post_categories( $post_id );
	$args     = array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post__not_in'        => array( $post_id ),
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	);
	if ( $cats ) {
		$args['category__in'] = $cats;
	}
	$related = new WP_Query( $args );

	if ( ! $related->have_posts() ) {
		// Top up with any recent posts when the category is thin.
		$related = new WP_Query(
			array(
				'post_type'           => 'post',
				'posts_per_page'      => 3,
				'post__not_in'        => array( $post_id ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			)
		);
	}

	if ( ! $related->have_posts() ) {
		wp_reset_postdata();
		return;
	}
	?>
	<section class="related-articles reveal" aria-labelledby="relatedTitle">
		<div class="container">
			<span class="eyebrow" data-anim="reveal-up"><?php esc_html_e( 'Keep reading', 'ameer' ); ?></span>
			<h2 id="relatedTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'More from our <span>kitchen</span>', 'ameer' ), array( 'span' => array() ) ); ?></h2>
			<div class="tip-grid" data-anim="stagger-up">
				<?php
				while ( $related->have_posts() ) :
					$related->the_post();
					get_template_part( 'template-parts/card', 'tip' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
		<!-- divider → footer (yellow) -->
		<svg class="divider-lorry" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-lorry"/></svg>
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<path d="M0,90 L1440,90 L1440,45 C1200,18 960,68 720,38 C480,12 240,55 0,45 Z" fill="#FDCB46"/>
			</svg>
			<svg class="divider-deco divider-spark-1" viewBox="0 0 30 30" style="color:#FA6255"><use href="#i-sparkle"/></svg>
			<svg class="divider-deco divider-spark-2" viewBox="0 0 30 30" style="color:#FFF9E6"><use href="#i-sparkle"/></svg>
		</div>
	</section>
	<?php
}

/**
 * Shared guard for meta-box save handlers: skips autosave/AJAX/bulk, verifies
 * the nonce, and checks edit capability.
 *
 * @param int    $post_id     Post being saved.
 * @param string $nonce_field $_POST key holding the nonce.
 * @param string $nonce_action Nonce action.
 * @return bool True when it is safe to persist meta.
 */
function ameer_can_save_meta( $post_id, $nonce_field, $nonce_action ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return false;
	}
	if ( ! isset( $_POST[ $nonce_field ] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ $nonce_field ] ) ), $nonce_action ) ) {
		return false;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}
	return true;
}

/**
 * Fallback primary menu — the original five links, so a fresh install looks right
 * before an admin assigns a menu. Anchors use absolute home URLs so they work
 * from any page; "Shop" points at the real shop archive.
 */
function ameer_primary_menu_fallback() {
	$items = array(
		array( 'label' => __( 'Home', 'ameer' ), 'url' => home_url( '/#home' ) ),
		array( 'label' => __( 'Shop', 'ameer' ), 'url' => ameer_shop_url() ),
		array( 'label' => __( 'About', 'ameer' ), 'url' => home_url( '/#about' ) ),
		array( 'label' => __( 'Recipes', 'ameer' ), 'url' => home_url( '/#recipes' ) ),
		array( 'label' => __( 'Contact', 'ameer' ), 'url' => home_url( '/#contact' ) ),
	);
	echo '<ul id="primaryNav" class="site-nav">';
	foreach ( $items as $item ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}
