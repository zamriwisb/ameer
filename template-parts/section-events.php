<?php
/**
 * Homepage events. CPT "event" (limit 2), demo fallback.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title = get_theme_mod( 'ameer_events_title', 'Find <span>Us</span> At' );

$events = new WP_Query(
	array(
		'post_type'           => 'event',
		'posts_per_page'      => 2,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
	)
);
?>
<section class="events reveal" aria-labelledby="eventsTitle">
	<div class="container">
		<h2 id="eventsTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array() ) ); ?></h2>
		<div class="event-grid" data-anim="stagger-up">
			<?php if ( $events->have_posts() ) : ?>
				<?php
				while ( $events->have_posts() ) :
					$events->the_post();
					$tag = get_post_meta( get_the_ID(), '_ameer_event_tag', true );
					$url = get_post_meta( get_the_ID(), '_ameer_event_url', true );
					$url = $url ? $url : '#';
					?>
					<a href="<?php echo esc_url( $url ); ?>" class="event-card"<?php echo ( '#' !== $url ) ? ' target="_blank" rel="noopener"' : ''; ?>>
						<div class="event-img">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
							<?php endif; ?>
						</div>
						<div class="event-body">
							<?php if ( $tag ) : ?><span class="event-tag"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
							<h3><?php the_title(); ?></h3>
							<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 22 ) ); ?></p>
						</div>
					</a>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			<?php else : ?>
				<a href="#" class="event-card">
					<div class="event-img"><img src="<?php echo esc_url( AMEER_ASSETS . '/event/expo-1.webp' ); ?>" alt="World Expo event day 1" /></div>
					<div class="event-body">
						<span class="event-tag">Pocket Talk</span>
						<h3>World Expo · Day 1</h3>
						<p><?php esc_html_e( 'Free samples and meet-the-team. Drop by our booth.', 'ameer' ); ?></p>
					</div>
				</a>
				<a href="#" class="event-card">
					<div class="event-img"><img src="<?php echo esc_url( AMEER_ASSETS . '/event/expo-2.webp' ); ?>" alt="World Expo event day 2" /></div>
					<div class="event-body">
						<span class="event-tag">Pocket Talk</span>
						<h3>World Expo · Day 2</h3>
						<p><?php esc_html_e( 'Kid-nutrition Q&amp;A with our in-house expert.', 'ameer' ); ?></p>
					</div>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<!-- Events bottom divider — solid yellow hill rising up (matches yellow Footer) -->
	<svg class="divider-lorry" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-lorry"/></svg>
	<div class="divider divider-bottom" aria-hidden="true">
		<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
			<path d="M0,90 L1440,90 L1440,45 C1200,18 960,68 720,38 C480,12 240,55 0,45 Z" fill="#FDCB46"/>
		</svg>
		<svg class="divider-deco divider-spark-1" viewBox="0 0 30 30" style="color:#FA6255"><use href="#i-sparkle"/></svg>
		<svg class="divider-deco divider-spark-2" viewBox="0 0 30 30" style="color:#FFF9E6"><use href="#i-sparkle"/></svg>
	</div>
</section>
