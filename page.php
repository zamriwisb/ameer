<?php
/**
 * Generic page template.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// WooCommerce cart / checkout / account are regular pages (not is_woocommerce()),
// so they land here. They need the wide .container, not the narrow reading column.
$ameer_is_wc_page = function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() );

while ( have_posts() ) :
	the_post();
	?>
<main>
	<article class="article-hero" aria-labelledby="pageTitle">
		<div class="container">
			<h1 class="article-title" id="pageTitle"><?php the_title(); ?></h1>
		</div>
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<path d="M0,90 L1440,90 L1440,55 C1080,28 720,82 360,55 C180,42 90,58 0,55 Z" fill="#FFF9E6"/>
			</svg>
		</div>
	</article>

	<section class="article-body reveal">
		<?php if ( $ameer_is_wc_page ) : ?>
			<div class="container ameer-wc-page">
				<?php the_content(); ?>
			</div>
		<?php else : ?>
			<div class="article-prose">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large' );
				}
				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ameer' ),
						'after'  => '</div>',
					)
				);

				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		<?php endif; ?>
	</section>
</main>
	<?php
endwhile;

get_footer();
