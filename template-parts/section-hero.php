<?php
/**
 * Homepage hero. Copy + stats from the Customizer (Homepage panel).
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = get_theme_mod( 'ameer_hero_eyebrow', __( 'Kid-loved · Mom-approved', 'ameer' ) );
$title    = get_theme_mod( 'ameer_hero_title', '<span>Tiny</span> <span>tummies,</span><br/><span>big</span> <span>smiles.</span>' );
$subtitle = get_theme_mod( 'ameer_hero_subtitle', __( 'Kid-formulated nutrition your family can trust — calm sleep, strong immunity, happy tummies.', 'ameer' ) );

$cta1_label = get_theme_mod( 'ameer_hero_cta1_label', __( 'Shop Now', 'ameer' ) );
$cta1_url   = get_theme_mod( 'ameer_hero_cta1_url', '' );
$cta1_url   = $cta1_url ? $cta1_url : ameer_shop_url();
$cta2_label = get_theme_mod( 'ameer_hero_cta2_label', __( 'Learn More', 'ameer' ) );
$cta2_url   = get_theme_mod( 'ameer_hero_cta2_url', home_url( '/#about' ) );

$hero_img_id = get_theme_mod( 'ameer_hero_image', 0 );

// Three stats: each is value / suffix / prefix / divide / label.
$stats = array(
	array(
		'value'  => get_theme_mod( 'ameer_stat1_value', '30000' ),
		'suffix' => get_theme_mod( 'ameer_stat1_suffix', 'k+' ),
		'prefix' => get_theme_mod( 'ameer_stat1_prefix', '' ),
		'divide' => get_theme_mod( 'ameer_stat1_divide', '' ),
		'label'  => get_theme_mod( 'ameer_stat1_label', __( 'happy mommies', 'ameer' ) ),
		'zero'   => '0',
	),
	array(
		'value'  => get_theme_mod( 'ameer_stat2_value', '3' ),
		'suffix' => get_theme_mod( 'ameer_stat2_suffix', '' ),
		'prefix' => get_theme_mod( 'ameer_stat2_prefix', '' ),
		'divide' => get_theme_mod( 'ameer_stat2_divide', '' ),
		'label'  => get_theme_mod( 'ameer_stat2_label', __( 'kid-first formulas', 'ameer' ) ),
		'zero'   => '0',
	),
	array(
		'value'  => get_theme_mod( 'ameer_stat3_value', '49' ),
		'suffix' => get_theme_mod( 'ameer_stat3_suffix', '' ),
		'prefix' => get_theme_mod( 'ameer_stat3_prefix', '★ ' ),
		'divide' => get_theme_mod( 'ameer_stat3_divide', '10' ),
		'label'  => get_theme_mod( 'ameer_stat3_label', __( 'review average', 'ameer' ) ),
		'zero'   => '★ 0.0',
	),
);
?>
<section class="hero" id="home" aria-labelledby="heroTitle">
	<div class="hero-bg" aria-hidden="true">
		<svg class="hero-sun" viewBox="0 0 160 160" data-parallax="0.25"><use href="#i-sun"/></svg>
		<svg class="hero-cloud hero-cloud-1" viewBox="0 0 160 80" data-parallax="0.15"><use href="#i-cloud"/></svg>
		<svg class="hero-cloud hero-cloud-2" viewBox="0 0 160 80" data-parallax="0.1"><use href="#i-cloud"/></svg>
		<svg class="hero-cloud hero-cloud-3" viewBox="0 0 160 80" data-parallax="0.2"><use href="#i-cloud"/></svg>
		<svg class="hero-balloon" viewBox="0 0 80 110" data-parallax="0.4"><use href="#i-balloon"/></svg>
	</div>
	<div class="container hero-inner">
		<div class="hero-copy">
			<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
			<h1 id="heroTitle" class="hero-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array(), 'br' => array() ) ); ?></h1>
			<p class="hero-sub" data-anim="reveal-up" data-anim-delay="500"><?php echo esc_html( $subtitle ); ?></p>
			<div class="hero-cta" data-anim="reveal-up" data-anim-delay="700">
				<a href="<?php echo esc_url( $cta1_url ); ?>" class="btn btn-primary btn-large"><?php echo esc_html( $cta1_label ); ?></a>
				<a href="<?php echo esc_url( $cta2_url ); ?>" class="btn btn-ghost btn-large"><?php echo esc_html( $cta2_label ); ?></a>
			</div>
			<ul class="hero-stats" role="list" data-anim="reveal-up" data-anim-delay="900">
				<?php foreach ( $stats as $s ) : ?>
					<?php if ( '' === $s['value'] && '' === $s['label'] ) { continue; } ?>
					<li>
						<strong
							data-count="<?php echo esc_attr( $s['value'] ); ?>"
							<?php echo $s['suffix'] ? 'data-count-suffix="' . esc_attr( $s['suffix'] ) . '"' : ''; ?>
							<?php echo $s['prefix'] ? 'data-count-prefix="' . esc_attr( $s['prefix'] ) . '"' : ''; ?>
							<?php echo $s['divide'] ? 'data-count-divide="' . esc_attr( $s['divide'] ) . '"' : ''; ?>
						><?php echo esc_html( $s['zero'] ); ?></strong>
						<?php echo esc_html( $s['label'] ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="hero-visual" data-anim="pop-in" data-anim-delay="400">
			<div class="cloud-frame">
				<?php if ( $hero_img_id ) : ?>
					<?php echo wp_get_attachment_image( $hero_img_id, 'large', false, array( 'class' => 'hero-product-shot', 'alt' => esc_attr( get_bloginfo( 'name' ) ) ) ); ?>
				<?php else : ?>
					<img src="<?php echo esc_url( AMEER_ASSETS . '/hero-visual.webp' ); ?>" alt="<?php esc_attr_e( 'Ameer products: Magnesium+, Al Manna Prebiotic, and Tigermilk+', 'ameer' ); ?>" class="hero-product-shot" />
				<?php endif; ?>
				<svg class="cloud-frame-spark spark-1" viewBox="0 0 30 30" style="color:#FDCB46"><use href="#i-sparkle"/></svg>
				<svg class="cloud-frame-spark spark-2" viewBox="0 0 30 30" style="color:#FA6255"><use href="#i-sparkle"/></svg>
				<svg class="cloud-frame-spark spark-3" viewBox="0 0 30 30" style="color:#91BEF8"><use href="#i-sparkle"/></svg>
			</div>
		</div>
	</div>
	<!-- Hero bottom divider — green hills rising up (matches About color) -->
	<div class="divider divider-bottom" aria-hidden="true">
		<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
			<path d="M0,90 L1440,90 L1440,55 C1200,28 960,75 720,48 C480,20 240,65 0,55 Z" fill="#A6D17D"/>
		</svg>
		<svg class="divider-deco divider-tree" viewBox="0 0 60 90"><use href="#i-tree"/></svg>
	</div>
</section>
