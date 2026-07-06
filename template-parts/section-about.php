<?php
/**
 * Homepage About / Our Promise. Copy from the Customizer.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow    = get_theme_mod( 'ameer_about_eyebrow', __( 'Our Promise', 'ameer' ) );
$title      = get_theme_mod( 'ameer_about_title', 'Kid-first, <span>mom-trusted</span>' );
$lead       = get_theme_mod( 'ameer_about_lead', __( 'We make nutrition that real moms actually feel good about. Carefully formulated, halal-certified, and free from added sugar — because the little ones deserve the best start.', 'ameer' ) );
$story_label = get_theme_mod( 'ameer_about_story_label', __( 'Read our full story →', 'ameer' ) );
$story_url   = get_theme_mod( 'ameer_about_story_url', home_url( '/#story' ) );
?>
<section class="about reveal" id="about" aria-labelledby="aboutTitle">
	<div class="container about-inner">
		<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
		<h2 id="aboutTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array() ) ); ?></h2>
		<p class="about-lead" data-anim="reveal-up" data-anim-delay="300"><?php echo esc_html( $lead ); ?></p>
		<ul class="badge-row" role="list" data-anim="stagger-up">
			<li class="badge"><span class="badge-ico" style="background:#FA6255;color:white">✓</span><?php esc_html_e( 'Halal Certified', 'ameer' ); ?></li>
			<li class="badge"><span class="badge-ico" style="background:#FDCB46;color:#3A3530">✓</span><?php esc_html_e( 'No Added Sugar', 'ameer' ); ?></li>
			<li class="badge"><span class="badge-ico" style="background:#91BEF8;color:white">✓</span><?php esc_html_e( 'Kids-Formulated', 'ameer' ); ?></li>
			<li class="badge"><span class="badge-ico" style="background:#3A3530;color:white">✓</span><?php esc_html_e( 'SSM Registered', 'ameer' ); ?></li>
		</ul>
		<?php if ( $story_label ) : ?>
			<a href="<?php echo esc_url( $story_url ); ?>" class="link-arrow" data-anim="reveal-up" data-anim-delay="600"><?php echo esc_html( $story_label ); ?></a>
		<?php endif; ?>
	</div>
	<!-- About bottom divider — yellow cloud puffs rising up (matches Products color) -->
	<div class="divider divider-bottom" aria-hidden="true">
		<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
			<g fill="#FDCB46">
				<rect x="0" y="78" width="1440" height="12"/>
				<ellipse cx="130" cy="74" rx="120" ry="32"/>
				<ellipse cx="360" cy="70" rx="140" ry="38"/>
				<ellipse cx="600" cy="74" rx="120" ry="32"/>
				<ellipse cx="840" cy="70" rx="140" ry="38"/>
				<ellipse cx="1080" cy="74" rx="120" ry="32"/>
				<ellipse cx="1320" cy="70" rx="140" ry="38"/>
			</g>
		</svg>
	</div>
</section>
