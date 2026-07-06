<?php
/**
 * Search results — tip-card grid, mirrors archive.php with a search heading.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

global $wp_query;
$ameer_found = (int) $wp_query->found_posts;
?>

<main>
	<section class="tips reveal" id="recipes">
		<div class="container">
			<span class="eyebrow"><?php esc_html_e( 'Search', 'ameer' ); ?></span>
			<h1 class="section-title">
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Results for &ldquo;%s&rdquo;', 'ameer' ), esc_html( get_search_query() ) );
				?>
			</h1>
			<p class="about-lead" style="text-align:center;max-width:640px;margin:0 auto 1.5rem;">
				<?php
				printf(
					/* translators: %s: number of results found. */
					esc_html( _n( '%s result found.', '%s results found.', $ameer_found, 'ameer' ) ),
					esc_html( number_format_i18n( $ameer_found ) )
				);
				?>
			</p>

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
				<p class="center-cta"><?php esc_html_e( 'Nothing matched your search. Try another keyword:', 'ameer' ); ?></p>
				<div style="max-width:480px;margin:1.5rem auto 0;">
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
