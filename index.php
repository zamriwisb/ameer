<?php
/**
 * Fallback template. Also serves the blog index when home.php is absent.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main>
	<section class="tips reveal" id="recipes">
		<div class="container">
			<?php if ( is_home() && ! is_front_page() ) : ?>
				<span class="eyebrow"><?php esc_html_e( 'Tips, recipes &amp; more', 'ameer' ); ?></span>
				<h1 class="section-title"><?php single_post_title(); ?></h1>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>
				<div class="tip-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/card', 'tip' );
					endwhile;
					?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 1,
						'prev_text' => __( '&larr; Newer', 'ameer' ),
						'next_text' => __( 'Older &rarr;', 'ameer' ),
					)
				);
				?>
			<?php else : ?>
				<p class="center-cta"><?php esc_html_e( 'Nothing here yet — check back soon!', 'ameer' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
