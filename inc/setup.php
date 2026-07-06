<?php
/**
 * Theme setup: supports, menus, image sizes, body classes.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'ameer_setup' );
function ameer_setup() {
	load_theme_textdomain( 'ameer', AMEER_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets', 'comment-list', 'comment-form' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 200,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// WooCommerce. We deliberately do NOT declare the wc-product-gallery-*
	// features: the design uses a custom gallery (big framed image + thumbnail
	// switcher, see content-single-product.php + product-page.js), so WC's
	// flexslider/zoom/photoswipe must not load and fight the layout.
	add_theme_support( 'woocommerce' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'ameer' ),
			'footer'  => __( 'Footer Quick Links', 'ameer' ),
		)
	);

	add_image_size( 'ameer-product-card', 600, 600, true );
	add_image_size( 'ameer-article-hero', 1200, 700, true );
	add_image_size( 'ameer-tip', 600, 420, true );
}

/**
 * The CSS and JS in css/sunny-v2.css + js/script-v2.js are scoped to
 * `.theme-sunny .theme-v2`. The original markup put these on <body>, so we
 * mirror that here.
 */
add_filter( 'body_class', 'ameer_body_class' );
function ameer_body_class( $classes ) {
	$classes[] = 'theme-sunny';
	$classes[] = 'theme-v2';
	return $classes;
}

/**
 * Content width for embeds/oembeds.
 */
add_action( 'after_setup_theme', 'ameer_content_width', 0 );
function ameer_content_width() {
	$GLOBALS['content_width'] = 1180;
}
