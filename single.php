<?php
/**
 * Single blog post = article layout.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$cats      = get_the_category();
	$cat_name  = $cats ? $cats[0]->name : '';
	$recipes   = get_category_by_slug( 'recipes' );
	$tips_url  = $recipes ? get_category_link( $recipes->term_id ) : home_url( '/' );
	$permalink = get_permalink();
	$share_t   = rawurlencode( get_the_title() );
	$share_u   = rawurlencode( $permalink );
	$ig_url    = get_theme_mod( 'ameer_social_instagram', '' );
	?>

<main>
	<article class="article-hero" aria-labelledby="articleTitle">
		<div class="container">
			<nav class="woocommerce-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ameer' ); ?>" data-anim="reveal-up">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'ameer' ); ?></a> <span>/</span>
				<a href="<?php echo esc_url( $tips_url ); ?>"><?php esc_html_e( 'Tips &amp; Recipes', 'ameer' ); ?></a> <span>/</span>
				<span aria-current="page"><?php the_title(); ?></span>
			</nav>

			<?php if ( $cat_name ) : ?>
				<span class="article-tag" data-anim="reveal-up"><?php echo esc_html( $cat_name ); ?></span>
			<?php endif; ?>
			<h1 class="article-title" id="articleTitle" data-anim="reveal-up" data-anim-delay="100"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="article-lead" data-anim="reveal-up" data-anim-delay="200"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>

			<div class="article-meta" data-anim="reveal-up" data-anim-delay="300">
				<span class="meta-author"><?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', get_the_author() ); ?><cite><?php the_author(); ?></cite></span>
				<span class="meta-dot" aria-hidden="true"></span>
				<span class="meta-item"><?php printf( esc_html__( 'Published %s', 'ameer' ), esc_html( get_the_date( 'j F Y' ) ) ); ?></span>
				<span class="meta-dot" aria-hidden="true"></span>
				<span class="meta-item"><?php echo esc_html( ameer_reading_time() ); ?></span>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="article-figure" data-anim="reveal-up" data-anim-delay="400">
					<?php the_post_thumbnail( 'ameer-article-hero', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
				</figure>
			<?php endif; ?>
		</div>

		<!-- divider → body (cream) -->
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<path d="M0,90 L1440,90 L1440,55 C1080,28 720,82 360,55 C180,42 90,58 0,55 Z" fill="#FFF9E6"/>
			</svg>
			<svg class="divider-deco divider-tree" viewBox="0 0 60 90"><use href="#i-tree"/></svg>
		</div>
	</article>

	<section class="article-body reveal">
		<div class="article-prose">
			<?php the_content(); ?>
			<?php
			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ameer' ),
					'after'  => '</div>',
				)
			);
			?>
		</div>

		<div class="article-share">
			<span><?php esc_html_e( 'Share:', 'ameer' ); ?></span>
			<a href="<?php echo esc_url( "https://www.facebook.com/sharer/sharer.php?u={$share_u}" ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on Facebook', 'ameer' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46H15.2c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg></a>
			<a href="<?php echo esc_url( "https://wa.me/?text={$share_t}%20{$share_u}" ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on WhatsApp', 'ameer' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.8 4.9-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.9.8.8-2.8-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.2 0-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3c-.3.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.4 3.9 1.6.7 2.2.7 3 .6.5-.1 1.4-.6 1.6-1.1.2-.6.2-1 .1-1.1z"/></svg></a>
			<?php if ( $ig_url ) : ?>
				<a href="<?php echo esc_url( $ig_url ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Instagram', 'ameer' ); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
			<?php endif; ?>
			<a href="<?php echo esc_url( $tips_url ); ?>" class="back-link"><?php esc_html_e( '← Back to all tips', 'ameer' ); ?></a>
		</div>

		<!-- divider → related (river blue) -->
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<path d="M0,90 L1440,90 L1440,50 C1080,80 720,20 360,50 C180,65 90,42 0,50 Z" fill="#91BEF8"/>
			</svg>
			<svg class="divider-deco divider-cloud-mini" viewBox="0 0 80 40"><use href="#i-cloud-mini"/></svg>
		</div>
	</section>

	<?php ameer_related_articles( get_the_ID() ); ?>
</main>

	<?php
endwhile;

get_footer();
