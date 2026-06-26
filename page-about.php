<?php
/**
 * Template Name: Ameer: About
 *
 * Interactive about page: intro + badges + story (cream), animated stats (green),
 * and an "our journey" timeline (cream), chained into the footer with the same
 * shaped dividers the homepage uses. Stats reuse the Hero Customizer values;
 * milestones are filterable.
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

// Stats reuse the Hero Customizer values so the two pages always agree.
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

// "Our journey" milestones — overridable via the `ameer_about_milestones` filter.
$milestones = apply_filters(
	'ameer_about_milestones',
	array(
		array(
			'year' => __( '2017', 'ameer' ),
			'title' => __( 'A mom&rsquo;s mission', 'ameer' ),
			'text' => __( 'Ameer began with one simple wish — nutrition a mom could trust for her own kids, with nothing to hide on the label.', 'ameer' ),
		),
		array(
			'year' => __( '2019', 'ameer' ),
			'title' => __( 'Halal certified', 'ameer' ),
			'text' => __( 'Our formulas earned halal certification and moved into an SSM-registered facility, holding every batch to the same standard.', 'ameer' ),
		),
		array(
			'year' => __( '2022', 'ameer' ),
			'title' => __( 'Three loved formulas', 'ameer' ),
			'text' => __( 'We grew to three kid-first formulas, each shaped by feedback from the mommies who use them every day.', 'ameer' ),
		),
		array(
			'year' => __( 'Today', 'ameer' ),
			'title' => __( '30,000+ happy families', 'ameer' ),
			'text' => __( 'Tens of thousands of families later, the promise is unchanged: kid-first, mom-trusted, made with love.', 'ameer' ),
		),
	)
);

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

	<section class="about-page reveal" aria-labelledby="aboutHeading">
		<svg class="about-deco about-deco-cloud" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>
		<div class="container">
			<div class="about-page-intro">
				<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
				<h2 id="aboutHeading" class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array() ) ); ?></h2>
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
		</div>

		<!-- About bottom divider — green hills rising up (matches Stats color) -->
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<path d="M0,90 L1440,90 L1440,55 C1200,28 960,75 720,48 C480,20 240,65 0,55 Z" fill="#A6D17D"/>
			</svg>
			<svg class="divider-deco divider-tree" viewBox="0 0 60 90"><use href="#i-tree"/></svg>
		</div>
	</section>

	<section class="about-stats reveal" aria-labelledby="statsHeading">
		<div class="container">
			<h2 id="statsHeading" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'Growing <span>together</span>', 'ameer' ), array( 'span' => array() ) ); ?></h2>
			<ul class="about-stat-row" role="list" data-anim="stagger-up">
				<?php foreach ( $stats as $s ) : ?>
					<?php if ( '' === $s['value'] && '' === $s['label'] ) { continue; } ?>
					<li class="about-stat">
						<strong
							data-count="<?php echo esc_attr( $s['value'] ); ?>"
							<?php echo $s['suffix'] ? 'data-count-suffix="' . esc_attr( $s['suffix'] ) . '"' : ''; ?>
							<?php echo $s['prefix'] ? 'data-count-prefix="' . esc_attr( $s['prefix'] ) . '"' : ''; ?>
							<?php echo $s['divide'] ? 'data-count-divide="' . esc_attr( $s['divide'] ) . '"' : ''; ?>
						><?php echo esc_html( $s['zero'] ); ?></strong>
						<span class="about-stat-label"><?php echo esc_html( $s['label'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<!-- Stats bottom divider — cream wave rising up (matches Timeline color) -->
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<path d="M0,90 L1440,90 L1440,55 C1080,28 720,82 360,55 C180,42 90,58 0,55 Z" fill="#FFF9E6"/>
			</svg>
			<svg class="divider-deco divider-boat" viewBox="0 0 80 70"><use href="#i-boat"/></svg>
		</div>
	</section>

	<?php if ( ! empty( $milestones ) ) : ?>
		<section class="about-timeline reveal" aria-labelledby="journeyHeading">
			<svg class="timeline-deco timeline-deco-cloud" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>
			<div class="container">
				<span class="eyebrow" data-anim="reveal-up"><?php esc_html_e( 'Our story', 'ameer' ); ?></span>
				<h2 id="journeyHeading" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'The Ameer <span>journey</span>', 'ameer' ), array( 'span' => array() ) ); ?></h2>
				<ol class="timeline" data-anim="stagger-up">
					<?php foreach ( $milestones as $m ) : ?>
						<li class="timeline-item">
							<span class="timeline-dot" aria-hidden="true"></span>
							<span class="timeline-year"><?php echo esc_html( isset( $m['year'] ) ? $m['year'] : '' ); ?></span>
							<h3 class="timeline-title"><?php echo wp_kses( isset( $m['title'] ) ? $m['title'] : '', array( 'span' => array() ) ); ?></h3>
							<p class="timeline-text"><?php echo esc_html( isset( $m['text'] ) ? $m['text'] : '' ); ?></p>
						</li>
					<?php endforeach; ?>
				</ol>

				<div class="about-page-cta" data-anim="reveal-up">
					<a href="<?php echo esc_url( ameer_shop_url() ); ?>" class="btn btn-primary btn-large"><?php esc_html_e( 'Shop Now', 'ameer' ); ?></a>
				</div>
			</div>

			<!-- Timeline bottom divider — solid yellow hill rising up (matches yellow Footer) -->
			<svg class="divider-lorry" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-lorry"/></svg>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
					<path d="M0,90 L1440,90 L1440,45 C1200,18 960,68 720,38 C480,12 240,55 0,45 Z" fill="#FDCB46"/>
				</svg>
				<svg class="divider-deco divider-spark-1" viewBox="0 0 30 30" style="color:#FA6255"><use href="#i-sparkle"/></svg>
				<svg class="divider-deco divider-spark-2" viewBox="0 0 30 30" style="color:#FFF9E6"><use href="#i-sparkle"/></svg>
			</div>
		</section>
	<?php endif; ?>
</main>
	<?php
endwhile;

get_footer();
