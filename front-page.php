<?php
/**
 * Homepage. Decorative markup lives in the section partials; content comes from
 * the Customizer (singletons) and CPT/WooCommerce queries (lists), each with a
 * sensible demo fallback so a fresh install still looks complete.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main>
	<?php
	get_template_part( 'template-parts/section', 'hero' );
	get_template_part( 'template-parts/section', 'about' );
	get_template_part( 'template-parts/section', 'products' );
	get_template_part( 'template-parts/section', 'tips' );
	get_template_part( 'template-parts/section', 'feedback' );
	get_template_part( 'template-parts/section', 'social' );
	get_template_part( 'template-parts/section', 'events' );
	?>
</main>

<?php
get_footer();
