<?php
/**
 * Themed search form.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="ameer-search mini-newsletter" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="sr-only" for="ameer-s"><?php esc_html_e( 'Search', 'ameer' ); ?></label>
	<input type="search" id="ameer-s" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'ameer' ); ?>" required />
	<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search', 'ameer' ); ?></button>
</form>
