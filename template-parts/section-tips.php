<?php
/**
 * Homepage Tips / Recipes grid. Latest posts in the "recipes" category (limit 3),
 * with a demo fallback before any posts exist.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = get_theme_mod( 'ameer_tips_eyebrow', __( 'Tips, recipes &amp; more', 'ameer' ) );
$title   = get_theme_mod( 'ameer_tips_title', 'From our <span>kitchen</span> to yours' );

// Prefer the "recipes" category; fall back to any recent posts.
$tips = new WP_Query(
	array(
		'category_name'          => 'recipes',
		'posts_per_page'         => 3,
		'no_found_rows'          => true,
		'ignore_sticky_posts'    => true,
	)
);
if ( ! $tips->have_posts() ) {
	wp_reset_postdata();
	$tips = new WP_Query(
		array(
			'posts_per_page'      => 3,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);
}

$all_tips_url = home_url( '/' );
$recipes_cat  = get_category_by_slug( 'recipes' );
if ( $recipes_cat ) {
	$all_tips_url = get_category_link( $recipes_cat->term_id );
} else {
	$blog_page = get_option( 'page_for_posts' );
	if ( $blog_page ) {
		$all_tips_url = get_permalink( $blog_page );
	}
}
?>
<section class="tips reveal" id="recipes" aria-labelledby="tipsTitle">
	<div class="container">
		<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
		<h2 id="tipsTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array() ) ); ?></h2>
		<div class="tip-grid" data-anim="stagger-up">
			<?php if ( $tips->have_posts() ) : ?>
				<?php
				while ( $tips->have_posts() ) :
					$tips->the_post();
					get_template_part( 'template-parts/card', 'tip' );
				endwhile;
				wp_reset_postdata();
				?>
			<?php else : ?>
				<?php /* Demo fallback. */ ?>
				<article class="tip-card">
					<div class="tip-img tip-img-1" role="img" aria-label="Parenting tip"><img src="<?php echo esc_url( AMEER_ASSETS . '/blog/tip-1.webp' ); ?>" alt="" class="tip-photo" /></div>
					<span class="tip-tag">Parenting tip</span>
					<h3>Picky eater? 5 quick wins.</h3>
					<p><?php esc_html_e( 'Simple strategies real moms use to get nutrition into stubborn little ones.', 'ameer' ); ?></p>
				</article>
				<article class="tip-card">
					<div class="tip-img tip-img-2" role="img" aria-label="Recipe"><img src="<?php echo esc_url( AMEER_ASSETS . '/blog/tip-2.webp' ); ?>" alt="" class="tip-photo" /></div>
					<span class="tip-tag">Recipe</span>
					<h3>Banana-oat breakfast bowls.</h3>
					<p><?php esc_html_e( '10-minute breakfasts kids will actually finish — packed with goodness.', 'ameer' ); ?></p>
				</article>
				<article class="tip-card">
					<div class="tip-img tip-img-3" role="img" aria-label="Meal plan"><img src="<?php echo esc_url( AMEER_ASSETS . '/blog/tip-3.webp' ); ?>" alt="" class="tip-photo" /></div>
					<span class="tip-tag">Meal plan</span>
					<h3>Balanced meals, busy week.</h3>
					<p><?php esc_html_e( 'A 7-day plan that takes the guesswork out of school-week lunches.', 'ameer' ); ?></p>
				</article>
			<?php endif; ?>
		</div>
		<div class="center-cta">
			<a href="<?php echo esc_url( $all_tips_url ); ?>" class="btn btn-secondary"><?php esc_html_e( 'View all tips', 'ameer' ); ?></a>
		</div>
	</div>
	<!-- Tips bottom divider — river blue wave rising up (matches Feedback color) -->
	<div class="divider divider-bottom" aria-hidden="true">
		<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
			<path d="M0,90 L1440,90 L1440,50 C1080,80 720,20 360,50 C180,65 90,42 0,50 Z" fill="#91BEF8"/>
		</svg>
		<svg class="divider-deco divider-cloud-mini" viewBox="0 0 80 40"><use href="#i-cloud-mini"/></svg>
	</div>
</section>
