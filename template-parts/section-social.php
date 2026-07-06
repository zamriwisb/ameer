<?php
/**
 * Homepage social grid. CPT "social_tile" (up to 8), demo fallback.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = get_theme_mod( 'ameer_social_eyebrow', '@ameernutrition' );
$title   = get_theme_mod( 'ameer_social_title', 'Follow our <span>little</span> moments' );
$sub     = get_theme_mod( 'ameer_social_sub', __( 'Tips, parenting wins, and giveaways on TikTok &amp; Instagram.', 'ameer' ) );

$tiles = new WP_Query(
	array(
		'post_type'           => 'social_tile',
		'posts_per_page'      => 8,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	)
);
?>
<section class="social reveal" aria-labelledby="socialTitle">
	<div class="container">
		<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
		<h2 id="socialTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array() ) ); ?></h2>
		<p class="social-sub" data-anim="reveal-up" data-anim-delay="300"><?php echo esc_html( $sub ); ?></p>
		<div class="social-grid" data-anim="stagger-scale">
			<?php if ( $tiles->have_posts() ) : ?>
				<?php
				$i = 0;
				while ( $tiles->have_posts() ) :
					$tiles->the_post();
					++$i;
					$url = get_post_meta( get_the_ID(), '_ameer_url', true );
					$url = $url ? $url : '#';
					?>
					<a href="<?php echo esc_url( $url ); ?>" class="social-tile social-tile-<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>"<?php echo ( '#' !== $url ) ? ' target="_blank" rel="noopener"' : ''; ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium', array( 'class' => 'social-photo', 'alt' => '' ) ); ?>
						<?php endif; ?>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			<?php else : ?>
				<?php
				$demo = array( 'social-1.webp', 'social-2.webp', 'social-3.webp', 'social-4.webp', 'social-5.webp', 'social-6.webp', 'social-1.webp', 'social-2.webp' );
				foreach ( $demo as $i => $img ) :
					?>
					<a href="#" class="social-tile social-tile-<?php echo (int) ( $i + 1 ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Latest post %d', 'ameer' ), $i + 1 ) ); ?>"><img src="<?php echo esc_url( AMEER_ASSETS . '/blog/' . $img ); ?>" alt="" class="social-photo" /></a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<!-- Social bottom divider — cream rolling hill rising up (matches Events color) -->
	<div class="divider divider-bottom" aria-hidden="true">
		<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
			<path d="M0,90 L1440,90 L1440,52 C1200,22 960,72 720,45 C480,18 240,62 0,52 Z" fill="#FFF9E6"/>
		</svg>
		<svg class="divider-deco divider-tree-2" viewBox="0 0 60 90"><use href="#i-tree"/></svg>
	</div>
</section>
