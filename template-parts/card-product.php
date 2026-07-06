<?php
/**
 * Product card (homepage products grid + WooCommerce related/loop fallback).
 *
 * Expects $args['product'] = WC_Product. When absent, falls back to the current
 * global $product (WooCommerce loop context).
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$product = isset( $args['product'] ) ? $args['product'] : ( isset( $GLOBALS['product'] ) ? $GLOBALS['product'] : null );
if ( ! $product instanceof WC_Product ) {
	$maybe = wc_get_product( get_the_ID() );
	if ( $maybe ) {
		$product = $maybe;
	}
}
if ( ! $product instanceof WC_Product ) {
	return;
}

$permalink = get_permalink( $product->get_id() );
$name      = $product->get_name();

// Eyebrow/tag: explicit meta, else primary category name.
$tag = get_post_meta( $product->get_id(), '_ameer_eyebrow', true );
if ( ! $tag ) {
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		$tag = $terms[0]->name;
	}
}

$benefit = $product->get_short_description();
$benefit = $benefit ? wp_strip_all_tags( $benefit ) : '';
?>
<article class="product-card">
	<?php if ( $tag ) : ?>
		<span class="product-tag"><?php echo esc_html( $tag ); ?></span>
	<?php endif; ?>
	<a href="<?php echo esc_url( $permalink ); ?>" class="product-img-link" tabindex="-1" aria-hidden="true">
		<?php
		echo $product->get_image( 'ameer-product-card', array( 'class' => 'product-img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC-escaped <img>.
		?>
	</a>
	<h3 class="product-name"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $name ); ?></a></h3>
	<?php if ( $benefit ) : ?>
		<p class="product-benefit"><?php echo esc_html( wp_trim_words( $benefit, 22 ) ); ?></p>
	<?php endif; ?>
	<a href="<?php echo esc_url( $permalink ); ?>" class="btn btn-secondary"><?php esc_html_e( 'View Product →', 'ameer' ); ?></a>
</article>
