<?php
/**
 * Site header: <head>, opening <body>, SVG sprite, scroll progress, site header bar.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php if ( ! has_site_icon() ) : ?>
		<link rel="icon" href="<?php echo esc_url( AMEER_ASSETS . '/ameer-logo.webp' ); ?>" />
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	<!-- Scroll progress bar -->
	<div class="scroll-progress" aria-hidden="true"><div class="scroll-progress-bar" id="scrollProgressBar"></div></div>

	<?php get_template_part( 'template-parts/svg-sprite' ); ?>

	<header class="site-header" id="siteHeader">
		<div class="container header-inner">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand">
					<img src="<?php echo esc_url( AMEER_ASSETS . '/ameer-logo.webp' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="brand-logo" />
				</a>
			<?php endif; ?>

			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_id'        => 'primaryNav',
					'menu_class'     => 'site-nav',
					'fallback_cb'    => 'ameer_primary_menu_fallback',
					'depth'          => 1,
				)
			);
			?>

			<div class="header-actions">
				<a class="icon-btn" href="<?php echo esc_url( ameer_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'ameer' ); ?>">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
					<?php echo ameer_cart_count_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- safe markup. ?>
				</a>
				<a href="<?php echo esc_url( ameer_shop_url() ); ?>" class="btn btn-primary"><?php esc_html_e( 'Shop Now', 'ameer' ); ?></a>
				<button class="menu-toggle" type="button" aria-controls="primaryNav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'ameer' ); ?>">
					<span class="menu-toggle-bar" aria-hidden="true"></span>
					<span class="menu-toggle-bar" aria-hidden="true"></span>
					<span class="menu-toggle-bar" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</header>
