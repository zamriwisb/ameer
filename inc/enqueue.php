<?php
/**
 * Front-end and admin asset enqueuing.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Preconnect hints for Google Fonts.
 */
add_filter( 'wp_resource_hints', 'ameer_resource_hints', 10, 2 );
function ameer_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = 'https://fonts.googleapis.com';
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}

add_action( 'wp_enqueue_scripts', 'ameer_enqueue_assets' );
function ameer_enqueue_assets() {
	// article-page.css styles the .article-hero / .article-prose used by single
	// posts, page.php (generic + WC cart/checkout/account pages) AND the shop /
	// product-taxonomy archives (which get the same cream hero). Load it there
	// too, else the page title collides with the fixed header.
	$is_shop_archive = function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );
	$is_article      = is_singular( 'post' ) || is_page() || $is_shop_archive;
	$is_product      = function_exists( 'is_product' ) && is_product();

	// Fonts.
	wp_enqueue_style(
		'ameer-fonts',
		'https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Caveat+Brush&family=Delius&display=swap',
		array(),
		null
	);

	// Core stylesheets (global), in dependency order.
	wp_enqueue_style( 'ameer-base', ameer_asset( 'css/base.css' ), array( 'ameer-fonts' ), ameer_ver( 'css/base.css' ) );
	wp_enqueue_style( 'ameer-sunny', ameer_asset( 'css/sunny-v2.css' ), array( 'ameer-base' ), ameer_ver( 'css/sunny-v2.css' ) );
	wp_enqueue_style( 'ameer-wp-compat', ameer_asset( 'css/wp-compat.css' ), array( 'ameer-sunny' ), ameer_ver( 'css/wp-compat.css' ) );

	if ( $is_article ) {
		wp_enqueue_style( 'ameer-article', ameer_asset( 'css/article-page.css' ), array( 'ameer-sunny' ), ameer_ver( 'css/article-page.css' ) );
	}
	if ( $is_product ) {
		wp_enqueue_style( 'ameer-product', ameer_asset( 'css/product-page.css' ), array( 'ameer-sunny' ), ameer_ver( 'css/product-page.css' ) );
	}
	if ( is_page_template( array( 'page-contact.php', 'page-about.php' ) ) ) {
		wp_enqueue_style( 'ameer-page-templates', ameer_asset( 'css/page-templates.css' ), array( 'ameer-sunny' ), ameer_ver( 'css/page-templates.css' ) );
	}

	// WooCommerce theming (buttons, cart, checkout, account) on any WC page.
	$is_wc_page = function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
	if ( $is_wc_page ) {
		wp_enqueue_style( 'ameer-woocommerce', ameer_asset( 'css/woocommerce.css' ), array( 'ameer-wp-compat' ), ameer_ver( 'css/woocommerce.css' ) );
	}

	// Main script (global), deferred + footer.
	wp_enqueue_script(
		'ameer-main',
		ameer_asset( 'js/script-v2.js' ),
		array(),
		ameer_ver( 'js/script-v2.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	if ( $is_product ) {
		wp_enqueue_script(
			'ameer-product',
			ameer_asset( 'js/product-page.js' ),
			array(),
			ameer_ver( 'js/product-page.js' ),
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
	}

	if ( class_exists( 'WooCommerce' ) ) {
		wp_localize_script(
			'ameer-main',
			'AmeerWC',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'cartUrl' => wc_get_cart_url(),
			)
		);
	}
}

/**
 * Repeatable meta-box helper script — only on the product edit screen.
 */
add_action( 'admin_enqueue_scripts', 'ameer_admin_enqueue' );
function ameer_admin_enqueue( $hook ) {
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'product' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_script(
		'ameer-admin-repeater',
		ameer_asset( 'inc/js/admin-repeater.js' ),
		array(),
		ameer_ver( 'inc/js/admin-repeater.js' ),
		true
	);
}
