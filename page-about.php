<?php
/**
 * Template Name: Ameer: About
 *
 * Intro from the About Customizer fields + story body from the page editor.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$eyebrow = get_theme_mod( 'ameer_about_eyebrow', __( 'Our Promise', 'ameer' ) );
$title   = get_theme_mod( 'ameer_about_title', 'Kid-first, <span>mom-trusted</span>' );
$lead    = get_theme_mod( 'ameer_about_lead', __( 'We make nutrition that real moms actually feel good about. Carefully formulated, halal-certified, and free from added sugar — because the little ones deserve the best start.', 'ameer' ) );

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

	<section class="about-page reveal">
		<div class="container">
			<div class="about-page-intro">
				<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
				<h2 class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array() ) ); ?></h2>
				<?php if ( $lead ) : ?>
					<p class="about-lead" data-anim="reveal-up" data-anim-delay="200"><?php echo esc_html( $lead ); ?></p>
				<?php endif; ?>
			</div>

			<ul class="badge-row" role="list" data-anim="stagger-up">
				<li class="badge"><span class="badge-ico" style="background:#FA6255;color:white">✓</span><?php esc_html_e( 'Halal Certified', 'ameer' ); ?></li>
				<li class="badge"><span class="badge-ico" style="background:#FDCB46;color:#3A3530">✓</span><?php esc_html_e( 'No Added Sugar', 'ameer' ); ?></li>
				<li class="badge"><span class="badge-ico" style="background:#91BEF8;color:white">✓</span><?php esc_html_e( 'Kids-Formulated', 'ameer' ); ?></li>
				<li class="badge"><span class="badge-ico" style="background:#3A3530;color:white">✓</span><?php esc_html_e( 'SSM Registered', 'ameer' ); ?></li>
			</ul>

			<?php if ( get_the_content() ) : ?>
				<div class="article-prose about-page-body" data-anim="reveal-up">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<div class="about-page-cta" data-anim="reveal-up">
				<a href="<?php echo esc_url( ameer_shop_url() ); ?>" class="btn btn-primary btn-large"><?php esc_html_e( 'Shop Now', 'ameer' ); ?></a>
			</div>
		</div>
	</section>
</main>
	<?php
endwhile;

get_footer();
