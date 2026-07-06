<?php
/**
 * Homepage products grid. Featured WooCommerce products (limit 3), with a demo
 * fallback so the section is never empty before products exist.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = get_theme_mod( 'ameer_products_eyebrow', __( 'Our little heroes', 'ameer' ) );
$title   = get_theme_mod( 'ameer_products_title', 'Three formulas, <span>endless</span> goodness' );

$products = array();
if ( function_exists( 'wc_get_products' ) ) {
	$products = wc_get_products(
		array(
			'featured' => true,
			'limit'    => 3,
			'status'   => 'publish',
			'orderby'  => 'menu_order',
			'order'    => 'ASC',
		)
	);
	// Fallback: if nothing is flagged featured, just show the latest 3.
	if ( empty( $products ) ) {
		$products = wc_get_products(
			array(
				'limit'   => 3,
				'status'  => 'publish',
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
	}
}
?>
<section class="products reveal" id="shop" aria-labelledby="productsTitle">
	<svg class="products-deco products-deco-cloud" viewBox="0 0 160 80" aria-hidden="true" style="opacity:0.5"><use href="#i-cloud"/></svg>
	<div class="container">
		<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
		<h2 id="productsTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array() ) ); ?></h2>
		<div class="product-grid" data-anim="stagger-scale">
			<?php if ( ! empty( $products ) ) : ?>
				<?php foreach ( $products as $product ) : ?>
					<?php get_template_part( 'template-parts/card', 'product', array( 'product' => $product ) ); ?>
				<?php endforeach; ?>
			<?php else : ?>
				<?php /* Demo fallback (no WooCommerce products yet). */ ?>
				<article class="product-card">
					<span class="product-tag"><?php esc_html_e( 'Calm &amp; Relaxation', 'ameer' ); ?></span>
					<img src="<?php echo esc_url( AMEER_ASSETS . '/products/mag-plus.webp' ); ?>" alt="Magnesium Glycinate" class="product-img" />
					<h3 class="product-name">Magnesium Glycinate</h3>
					<p class="product-benefit"><?php esc_html_e( 'Magnesium glycinate that helps little minds settle and sleep through the night.', 'ameer' ); ?></p>
					<a href="<?php echo esc_url( ameer_shop_url() ); ?>" class="btn btn-secondary"><?php esc_html_e( 'View Product →', 'ameer' ); ?></a>
				</article>
				<article class="product-card">
					<span class="product-tag"><?php esc_html_e( 'Picky Eater', 'ameer' ); ?></span>
					<img src="<?php echo esc_url( AMEER_ASSETS . '/products/prebiotics.webp' ); ?>" alt="Prebiotic Almanna" class="product-img" />
					<h3 class="product-name">Prebiotic Almanna</h3>
					<p class="product-benefit"><?php esc_html_e( 'Daily gut-health support that keeps tummies happy and immunity humming.', 'ameer' ); ?></p>
					<a href="<?php echo esc_url( ameer_shop_url() ); ?>" class="btn btn-secondary"><?php esc_html_e( 'View Product →', 'ameer' ); ?></a>
				</article>
				<article class="product-card">
					<span class="product-tag"><?php esc_html_e( 'Respiratory Support', 'ameer' ); ?></span>
					<img src="<?php echo esc_url( AMEER_ASSETS . '/products/tigermilk-plus.webp' ); ?>" alt="Tigermilk+" class="product-img" />
					<h3 class="product-name">Tigermilk+</h3>
					<p class="product-benefit"><?php esc_html_e( 'Mushroom-powered immunity for strong lungs and fewer sick days.', 'ameer' ); ?></p>
					<a href="<?php echo esc_url( ameer_shop_url() ); ?>" class="btn btn-secondary"><?php esc_html_e( 'View Product →', 'ameer' ); ?></a>
				</article>
			<?php endif; ?>
		</div>
	</div>
	<!-- Products bottom divider — cream wave rising up (matches Tips color) -->
	<div class="divider divider-bottom" aria-hidden="true">
		<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
			<path d="M0,90 L1440,90 L1440,55 C1080,28 720,82 360,55 C180,42 90,58 0,55 Z" fill="#FFF9E6"/>
		</svg>
		<svg class="divider-deco divider-boat" viewBox="0 0 80 70"><use href="#i-boat"/></svg>
	</div>
</section>
