<?php
/**
 * Homepage testimonials slider. CPT "testimonial" (limit 5), demo fallback.
 * script-v2.js turns .feedback [data-track] into an infinite marquee.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title  = get_theme_mod( 'ameer_feedback_title', 'What <span style="color:#3A3530">Moms</span> Say' );
$colors = array( '#FA6255', '#A6D17D', '#FDCB46', '#91BEF8', '#FA6255' );

$items = new WP_Query(
	array(
		'post_type'           => 'testimonial',
		'posts_per_page'      => 5,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	)
);
?>
<section class="feedback reveal" aria-labelledby="feedbackTitle">
	<svg class="feedback-deco feedback-deco-cloud-1" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>
	<svg class="feedback-deco feedback-deco-cloud-2" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>
	<div class="container">
		<h2 id="feedbackTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array( 'style' => array() ) ) ); ?></h2>
		<div class="slider" data-slider>
			<button class="slider-arrow slider-arrow-prev" data-prev aria-label="<?php esc_attr_e( 'Previous', 'ameer' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
			</button>
			<ul class="slider-track" data-track role="list">
				<?php if ( $items->have_posts() ) : ?>
					<?php
					$i = 0;
					while ( $items->have_posts() ) :
						$items->the_post();
						$color  = $colors[ $i % count( $colors ) ];
						$rating = (int) get_post_meta( get_the_ID(), '_ameer_rating', true );
						$rating = $rating ? min( 5, max( 1, $rating ) ) : 5;
						$city   = get_post_meta( get_the_ID(), '_ameer_city', true );
						++$i;
						?>
						<li class="testimonial">
							<svg class="quote-mark" viewBox="0 0 40 36" aria-hidden="true" style="color:<?php echo esc_attr( $color ); ?>"><use href="#i-quote"/></svg>
							<div class="stars" aria-label="<?php echo esc_attr( sprintf( _n( '%d star', '%d stars', $rating, 'ameer' ), $rating ) ); ?>"><?php echo esc_html( str_repeat( '★', $rating ) ); ?></div>
							<p><?php echo esc_html( wp_strip_all_tags( get_the_content() ) ); ?></p>
							<div class="who">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( array( 64, 64 ), array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
								<?php endif; ?>
								<div>
									<cite><?php the_title(); ?></cite>
									<?php if ( $city ) : ?><small><?php echo esc_html( $city ); ?></small><?php endif; ?>
								</div>
							</div>
						</li>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				<?php else : ?>
					<?php
					$demo = array(
						array( 'q' => 'My toddler actually sleeps through the night now. Magnesium Glycinate has been a game changer for our whole family.', 'n' => 'Aisyah', 'c' => 'Kuala Lumpur', 'img' => 'feedback-1.webp' ),
						array( 'q' => 'Less colds, less drama. Tigermilk+ is now part of our morning routine and I won\'t go back.', 'n' => 'Nadia', 'c' => 'Selangor', 'img' => 'feedback-2.webp' ),
						array( 'q' => 'Finally a kids\' supplement my child takes happily. Tummy issues are completely gone.', 'n' => 'Faridah', 'c' => 'Penang', 'img' => 'feedback-3.webp' ),
						array( 'q' => 'Halal-certified and clean ingredients. That\'s all this mom needed to know.', 'n' => 'Hana', 'c' => 'Johor', 'img' => 'feedback-4.webp' ),
						array( 'q' => 'My boys love the taste. I love the science behind it. Win-win for everyone.', 'n' => 'Sarah', 'c' => 'Putrajaya', 'img' => 'feedback-5.webp' ),
					);
					foreach ( $demo as $i => $d ) :
						$color = $colors[ $i % count( $colors ) ];
						?>
						<li class="testimonial">
							<svg class="quote-mark" viewBox="0 0 40 36" aria-hidden="true" style="color:<?php echo esc_attr( $color ); ?>"><use href="#i-quote"/></svg>
							<div class="stars" aria-label="5 stars">★★★★★</div>
							<p>"<?php echo esc_html( $d['q'] ); ?>"</p>
							<div class="who">
								<img src="<?php echo esc_url( AMEER_ASSETS . '/feedback/' . $d['img'] ); ?>" alt="<?php echo esc_attr( $d['n'] ); ?>" />
								<div><cite><?php echo esc_html( $d['n'] ); ?></cite><small><?php echo esc_html( $d['c'] ); ?></small></div>
							</div>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			<button class="slider-arrow slider-arrow-next" data-next aria-label="<?php esc_attr_e( 'Next', 'ameer' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
			</button>
			<div class="slider-dots" data-dots aria-hidden="true"></div>
		</div>
	</div>
	<!-- Feedback bottom divider — sky blue cloud puffs rising up (matches Social color) -->
	<div class="divider divider-bottom" aria-hidden="true">
		<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
			<g fill="#B5D2F7">
				<rect x="0" y="78" width="1440" height="12"/>
				<ellipse cx="110" cy="74" rx="100" ry="28"/>
				<ellipse cx="310" cy="72" rx="120" ry="34"/>
				<ellipse cx="520" cy="74" rx="100" ry="28"/>
				<ellipse cx="720" cy="72" rx="120" ry="34"/>
				<ellipse cx="920" cy="74" rx="100" ry="28"/>
				<ellipse cx="1130" cy="72" rx="120" ry="34"/>
				<ellipse cx="1340" cy="74" rx="100" ry="28"/>
			</g>
		</svg>
		<svg class="divider-deco divider-kite" viewBox="0 0 50 90"><use href="#i-kite"/></svg>
	</div>
</section>
