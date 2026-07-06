<?php
/**
 * Archive (category / tag / date / author) — tip-card grid.
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
			<span class="eyebrow"><?php esc_html_e( 'Tips, recipes &amp; more', 'ameer' ); ?></span>
			<h1 class="section-title">
				<?php
				the_archive_title();
				?>
			</h1>
			<?php
			$desc = get_the_archive_description();
			if ( $desc ) :
				?>
				<p class="about-lead" style="text-align:center;max-width:640px;margin:0 auto 1.5rem;"><?php echo wp_kses_post( $desc ); ?></p>
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
