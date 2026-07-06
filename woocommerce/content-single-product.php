<?php
/**
 * The Ameer single-product body. Reproduces the custom sectioned layout while
 * keeping real WooCommerce machinery (gallery, add-to-cart, reviews, related).
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

/**
 * Required hook — without it WooCommerce notices / structured data break.
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$product_id = $product->get_id();
$eyebrow    = ameer_product_eyebrow( $product_id );
$price_note = get_post_meta( $product_id, '_ameer_price_note', true );
$tags       = array_filter( (array) get_post_meta( $product_id, '_ameer_benefit_tags', true ) );
?>

<section class="product-main" id="buy" aria-labelledby="productTitle">
	<svg class="pm-deco pm-deco-cloud-1" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>

	<div class="container">
		<?php woocommerce_breadcrumb(); ?>

		<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
			<?php
			/**
			 * Custom gallery (matches the static design): big framed main image +
			 * thumbnail switcher driven by product-page.js. Main image = featured
			 * image; thumbnails = featured + gallery images.
			 */
			$ameer_main_id   = $product->get_image_id();
			$ameer_thumb_ids = array_values( array_filter( array_merge( array( $ameer_main_id ), $product->get_gallery_image_ids() ) ) );
			$ameer_main_url  = $ameer_main_id ? wp_get_attachment_image_url( $ameer_main_id, 'large' ) : wc_placeholder_img_src( 'large' );
			$ameer_badge     = get_post_meta( $product_id, '_ameer_badge', true );
			if ( '' === $ameer_badge && $product->is_on_sale() ) {
				$ameer_badge = __( 'Best seller', 'ameer' );
			}
			?>
			<div class="woocommerce-product-gallery" data-anim="reveal-up">
				<figure class="woocommerce-product-gallery__wrapper">
					<?php if ( $ameer_badge ) : ?>
						<span class="onsale"><?php echo esc_html( $ameer_badge ); ?></span>
					<?php endif; ?>
					<div class="woocommerce-product-gallery__image">
						<img src="<?php echo esc_url( $ameer_main_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" class="wp-post-image" id="galleryMain" />
					</div>
					<svg class="gallery-spark gallery-spark-1" viewBox="0 0 30 30" style="color:#FDCB46"><use href="#i-sparkle"/></svg>
					<svg class="gallery-spark gallery-spark-2" viewBox="0 0 30 30" style="color:#FA6255"><use href="#i-sparkle"/></svg>
				</figure>
				<?php if ( count( $ameer_thumb_ids ) > 1 ) : ?>
					<ul class="product-thumbnails flex-control-thumbs" role="list">
						<?php foreach ( $ameer_thumb_ids as $idx => $tid ) : ?>
							<li>
								<button type="button" class="thumb<?php echo 0 === $idx ? ' is-active' : ''; ?>" data-full="<?php echo esc_url( wp_get_attachment_image_url( $tid, 'large' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View image %d', 'ameer' ), $idx + 1 ) ); ?>">
									<?php echo wp_get_attachment_image( $tid, 'thumbnail', false, array( 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</button>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="summary entry-summary">
				<?php if ( $eyebrow ) : ?>
					<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>

				<h1 class="product_title entry-title" id="productTitle" data-anim="reveal-up" data-anim-delay="100"><?php the_title(); ?></h1>

				<?php
				// Rating reflects real approved reviews (updates as reviews are
				// submitted and approved); hidden until the product has any.
				$ameer_rating = (float) $product->get_average_rating();
				$ameer_rcount = (int) $product->get_review_count();
				if ( $ameer_rcount > 0 ) :
					?>
					<div class="woocommerce-product-rating" data-anim="reveal-up" data-anim-delay="200">
						<span class="star-rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'ameer' ), number_format_i18n( $ameer_rating, 1 ) ) ); ?>"><?php echo esc_html( ameer_stars( max( 1, (int) round( $ameer_rating ) ) ) ); ?></span>
						<a href="#reviews" class="woocommerce-review-link"><?php echo esc_html( number_format_i18n( $ameer_rating, 1 ) ); ?> · <span class="count"><?php echo esc_html( number_format_i18n( $ameer_rcount ) ); ?></span> <?php esc_html_e( 'reviews', 'ameer' ); ?></a>
					</div>
				<?php endif; ?>

				<p class="price" data-anim="reveal-up" data-anim-delay="300">
					<?php echo wp_kses_post( $product->get_price_html() ); ?>
					<?php if ( $price_note ) : ?><small class="price-note"><?php echo esc_html( $price_note ); ?></small><?php endif; ?>
				</p>

				<div class="woocommerce-product-details__short-description" data-anim="reveal-up" data-anim-delay="400">
					<?php echo wp_kses_post( wpautop( $product->get_short_description() ) ); ?>
					<?php if ( $tags ) : ?>
						<ul class="benefit-tags" role="list">
							<?php foreach ( $tags as $tag ) : ?>
								<li><?php echo esc_html( $tag ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="ameer-add-to-cart" data-anim="reveal-up" data-anim-delay="500">
					<?php
					/**
					 * Real WooCommerce add-to-cart form (quantity + add to cart button).
					 * product-page.js injects the themed +/- stepper into the .quantity box.
					 */
					woocommerce_template_single_add_to_cart();
					?>
				</div>

				<ul class="product-trust badge-row" role="list" data-anim="reveal-up" data-anim-delay="600">
					<li class="badge"><span class="badge-ico" style="background:#FA6255;color:white">✓</span><?php esc_html_e( 'Halal Certified', 'ameer' ); ?></li>
					<li class="badge"><span class="badge-ico" style="background:#FDCB46;color:#3A3530">✓</span><?php esc_html_e( 'No Added Sugar', 'ameer' ); ?></li>
					<li class="badge"><span class="badge-ico" style="background:#91BEF8;color:white">✓</span><?php esc_html_e( 'Kids-Formulated', 'ameer' ); ?></li>
				</ul>

			</div>
		</div>
	</div>

	<?php ameer_divider( 'green-hills-tree' ); ?>
</section>

<?php
ameer_render_benefits( $product_id );
ameer_render_ingredients( $product_id );
ameer_render_dosage( $product_id );
ameer_render_reviews( $product );
ameer_render_faq( $product_id );

/**
 * Related products (themed via woocommerce/single-product/related.php
 * + woocommerce/content-product.php).
 */
woocommerce_output_related_products();

do_action( 'woocommerce_after_single_product' );
