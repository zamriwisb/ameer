<?php
/**
 * Ameer Homepage theme bootstrap.
 *
 * Keeps this file thin — every concern lives in its own module under inc/.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AMEER_VERSION', '1.0.0' );
define( 'AMEER_DIR', get_template_directory() );
define( 'AMEER_URI', get_template_directory_uri() );
define( 'AMEER_ASSETS', AMEER_URI . '/assets' );

/**
 * Require a theme module from inc/, failing loudly in debug mode only.
 */
function ameer_require( $relative ) {
	$path = AMEER_DIR . '/inc/' . $relative;
	if ( file_exists( $path ) ) {
		require_once $path;
	} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		trigger_error( esc_html( "Ameer: missing module inc/{$relative}" ), E_USER_WARNING );
	}
}

ameer_require( 'helpers.php' );
ameer_require( 'setup.php' );
ameer_require( 'enqueue.php' );
ameer_require( 'cpt.php' );
ameer_require( 'customizer.php' );
ameer_require( 'meta-product.php' );
ameer_require( 'meta-testimonial.php' );
ameer_require( 'meta-event.php' );
ameer_require( 'newsletter.php' );
ameer_require( 'contact.php' );

// WooCommerce integration only when the plugin is active.
if ( class_exists( 'WooCommerce' ) ) {
	ameer_require( 'woocommerce.php' );
}
