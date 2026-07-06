<?php
/**
 * Custom post types for homepage content: testimonials, events, social tiles.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'ameer_register_cpts' );
function ameer_register_cpts() {

	register_post_type(
		'testimonial',
		array(
			'labels'        => ameer_cpt_labels( __( 'Testimonial', 'ameer' ), __( 'Testimonials', 'ameer' ) ),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-format-quote',
			'menu_position' => 22,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'has_archive'   => false,
			'rewrite'       => false,
		)
	);

	register_post_type(
		'event',
		array(
			'labels'        => ameer_cpt_labels( __( 'Event', 'ameer' ), __( 'Events', 'ameer' ) ),
			'public'        => true,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 23,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'has_archive'   => false,
			'rewrite'       => array( 'slug' => 'events' ),
		)
	);

	register_post_type(
		'social_tile',
		array(
			'labels'        => ameer_cpt_labels( __( 'Social Tile', 'ameer' ), __( 'Social Tiles', 'ameer' ) ),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-instagram',
			'menu_position' => 24,
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'   => false,
			'rewrite'       => false,
		)
	);
}

/**
 * Build a standard labels array for a CPT.
 *
 * @param string $singular Singular label.
 * @param string $plural   Plural label.
 * @return array
 */
function ameer_cpt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		'add_new'            => __( 'Add New', 'ameer' ),
		/* translators: %s: singular post type name. */
		'add_new_item'       => sprintf( __( 'Add New %s', 'ameer' ), $singular ),
		/* translators: %s: singular post type name. */
		'edit_item'          => sprintf( __( 'Edit %s', 'ameer' ), $singular ),
		/* translators: %s: singular post type name. */
		'new_item'           => sprintf( __( 'New %s', 'ameer' ), $singular ),
		/* translators: %s: singular post type name. */
		'view_item'          => sprintf( __( 'View %s', 'ameer' ), $singular ),
		/* translators: %s: plural post type name. */
		'search_items'       => sprintf( __( 'Search %s', 'ameer' ), $plural ),
		/* translators: %s: plural post type name. */
		'not_found'          => sprintf( __( 'No %s found', 'ameer' ), strtolower( $plural ) ),
		'all_items'          => $plural,
	);
}

/**
 * Ensure the "recipes" category exists (used by the homepage Tips section).
 */
add_action( 'init', 'ameer_ensure_recipes_category' );
function ameer_ensure_recipes_category() {
	if ( ! term_exists( 'recipes', 'category' ) ) {
		wp_insert_term( __( 'Recipes', 'ameer' ), 'category', array( 'slug' => 'recipes' ) );
	}
}
