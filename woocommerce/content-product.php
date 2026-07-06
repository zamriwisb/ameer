<?php
/**
 * Shop/catalog loop item — themed Ameer product card.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'ameer-product-li', $product ); ?>>
	<?php get_template_part( 'template-parts/card', 'product', array( 'product' => $product ) ); ?>
</li>
