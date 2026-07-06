<?php
/**
 * Tip / recipe card. Use inside a post loop (after the_post()) or pass
 * $args['post_id']. The tip-img-N class cycles 1..3 for varied backgrounds.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
if ( ! $post_id ) {
	return;
}

static $counter = 0;
++$counter;
$variant = ( ( $counter - 1 ) % 3 ) + 1;

$cat       = '';
$cat_terms = get_the_category( $post_id );
if ( $cat_terms ) {
	$cat = $cat_terms[0]->name;
}

$permalink = get_permalink( $post_id );
$title     = get_the_title( $post_id );
$excerpt   = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 22 );
?>
<article class="tip-card">
	<div class="tip-img tip-img-<?php echo (int) $variant; ?>" role="img" aria-label="<?php echo esc_attr( $cat ? $cat : __( 'Article', 'ameer' ) ); ?>">
		<?php if ( has_post_thumbnail( $post_id ) ) : ?>
			<?php echo get_the_post_thumbnail( $post_id, 'ameer-tip', array( 'class' => 'tip-photo', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
	</div>
	<?php if ( $cat ) : ?>
		<span class="tip-tag"><?php echo esc_html( $cat ); ?></span>
	<?php endif; ?>
	<h3><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a></h3>
	<p><?php echo esc_html( $excerpt ); ?></p>
	<a href="<?php echo esc_url( $permalink ); ?>" class="link-arrow"><?php esc_html_e( 'Read more →', 'ameer' ); ?></a>
</article>
