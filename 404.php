<?php
/**
 * 404 — friendly not found.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main>
	<section class="hero" id="home" style="min-height:auto">
		<div class="hero-bg" aria-hidden="true">
			<svg class="hero-cloud hero-cloud-1" viewBox="0 0 160 80"><use href="#i-cloud"/></svg>
			<svg class="hero-balloon" viewBox="0 0 80 110"><use href="#i-balloon"/></svg>
		</div>
		<div class="container" style="text-align:center;position:relative;z-index:2">
			<h1 class="hero-title"><?php esc_html_e( 'Oops — page not found', 'ameer' ); ?></h1>
			<p class="hero-sub" style="margin-inline:auto"><?php esc_html_e( 'The page floated away like a balloon. Let&rsquo;s get you back to something tasty.', 'ameer' ); ?></p>
			<div class="hero-cta" style="justify-content:center">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary btn-large"><?php esc_html_e( 'Back home', 'ameer' ); ?></a>
				<a href="<?php echo esc_url( ameer_shop_url() ); ?>" class="btn btn-ghost btn-large"><?php esc_html_e( 'Shop products', 'ameer' ); ?></a>
			</div>
			<div style="max-width:420px;margin:2rem auto 0"><?php get_search_form(); ?></div>
		</div>
	</section>
</main>

<?php
get_footer();
