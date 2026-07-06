<?php
/**
 * Wrapper for ALL WooCommerce pages.
 *
 * NOTE: when this file exists, WooCommerce uses it for every WC page and ignores
 * single-product.php / archive-product.php. So we branch here:
 *  - Single products render full-bleed (content-single-product.php supplies its
 *    own full-width sections, containers and dividers — matching the design).
 *  - Everything else (shop archive, cart, checkout, account) gets the standard
 *    centered container.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main>
	<?php if ( is_product() ) : ?>
		<?php woocommerce_content(); ?>
	<?php else : ?>
		<article class="article-hero" aria-labelledby="shopTitle">
			<div class="container">
				<h1 class="article-title" id="shopTitle"><?php echo esc_html( wp_strip_all_tags( woocommerce_page_title( false ) ) ); ?></h1>
				<?php if ( is_product_taxonomy() && term_description() ) : ?>
					<p class="article-lead"><?php echo wp_kses_post( term_description() ); ?></p>
				<?php endif; ?>
			</div>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
					<path d="M0,90 L1440,90 L1440,55 C1080,28 720,82 360,55 C180,42 90,58 0,55 Z" fill="#FFF9E6"/>
				</svg>
			</div>
		</article>

		<section class="article-body reveal">
			<div class="container ameer-wc-page ameer-wc-archive">
				<?php woocommerce_content(); ?>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
