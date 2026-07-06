<?php
/**
 * Themed related-products section ("Pairs well with" / "More little heroes").
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Fall back to other published products when there are no category/tag matches
// (each Ameer product sits in its own category, so WC finds no "related").
if ( empty( $related_products ) && function_exists( 'wc_get_products' ) ) {
	$current_id        = get_the_ID();
	$related_products  = wc_get_products(
		array(
			'limit'   => 3,
			'status'  => 'publish',
			'exclude' => array( $current_id ),
			'orderby' => 'date',
			'order'   => 'DESC',
		)
	);
}

if ( empty( $related_products ) ) {
	return;
}
?>
<section class="related related-products reveal" id="related" aria-labelledby="relatedTitle">
	<svg class="related-deco related-deco-spark-1" viewBox="0 0 30 30" style="color:#FA6255" aria-hidden="true"><use href="#i-sparkle"/></svg>
	<svg class="related-deco related-deco-spark-2" viewBox="0 0 30 30" style="color:#FDCB46" aria-hidden="true"><use href="#i-sparkle"/></svg>
	<div class="container">
		<span class="eyebrow" data-anim="reveal-up"><?php esc_html_e( 'Pairs well with', 'ameer' ); ?></span>
		<h2 id="relatedTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'More little <span>heroes</span>', 'ameer' ), array( 'span' => array() ) ); ?></h2>
		<div class="product-grid" data-anim="stagger-scale">
			<?php foreach ( $related_products as $related_product ) : ?>
				<?php get_template_part( 'template-parts/card', 'product', array( 'product' => $related_product ) ); ?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php ameer_divider( 'yellow-hill-spark' ); ?>
</section>
<?php
wp_reset_postdata();
