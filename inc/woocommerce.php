<?php
/**
 * WooCommerce integration: cart fragments, related count, section renderers,
 * sticky mobile buy bar + cart toast.
 *
 * Loaded only when WooCommerce is active (see functions.php).
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep the header cart count in sync via WC AJAX fragments.
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'ameer_cart_count_fragment' );
function ameer_cart_count_fragment( $fragments ) {
	$fragments['span.cart-count'] = ameer_cart_count_html();
	return $fragments;
}

/**
 * Add a themed "Buy now" button inside the single-product cart form,
 * next to "Add to cart" (matches the static design).
 */
add_action( 'woocommerce_after_add_to_cart_button', 'ameer_buy_now_button' );
function ameer_buy_now_button() {
	global $product;
	if ( ! $product instanceof WC_Product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
		return;
	}
	printf(
		'<a href="%s" class="btn btn-secondary btn-large buy-now">%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_html__( 'Buy now', 'ameer' )
	);
}

/**
 * Hide WooCommerce's default archive page title — woocommerce.php renders its
 * own cream hero title instead.
 */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/**
 * Remove the "Default sorting" ordering dropdown from the shop/archive toolbar.
 * (WooCommerce hooks woocommerce_catalog_ordering at priority 30.)
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/**
 * Show 3 related products.
 */
add_filter( 'woocommerce_output_related_products_args', 'ameer_related_products_args' );
function ameer_related_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns']        = 3;
	return $args;
}

/**
 * The product "eyebrow": explicit meta, else primary product category name.
 *
 * @param int $product_id Product ID.
 * @return string
 */
function ameer_product_eyebrow( $product_id ) {
	$eyebrow = get_post_meta( $product_id, '_ameer_eyebrow', true );
	if ( $eyebrow ) {
		return $eyebrow;
	}
	$terms = get_the_terms( $product_id, 'product_cat' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		return $terms[0]->name;
	}
	return '';
}

/* --------------------------------------------------------- Section renderers */

/**
 * Benefits grid (woo: Description tab styling).
 *
 * @param int $product_id Product ID.
 */
function ameer_render_benefits( $product_id ) {
	$rows = (array) get_post_meta( $product_id, '_ameer_benefits', true );
	$rows = array_filter( $rows );
	if ( ! $rows ) {
		return;
	}
	?>
	<section class="benefits reveal woocommerce-Tabs-panel--description" id="tab-description" aria-labelledby="benefitsTitle">
		<div class="container">
			<span class="eyebrow" data-anim="reveal-up"><?php esc_html_e( 'Why kids love it', 'ameer' ); ?></span>
			<h2 id="benefitsTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'Big benefits, <span>tiny</span> scoop', 'ameer' ), array( 'span' => array() ) ); ?></h2>
			<div class="benefit-grid" data-anim="stagger-up">
				<?php foreach ( $rows as $b ) : ?>
					<article class="benefit-card">
						<span class="benefit-ico"<?php echo ! empty( $b['color'] ) ? ' style="background:' . esc_attr( $b['color'] ) . '"' : ''; ?>><?php echo esc_html( isset( $b['icon'] ) ? $b['icon'] : '' ); ?></span>
						<h3><?php echo esc_html( isset( $b['title'] ) ? $b['title'] : '' ); ?></h3>
						<p><?php echo wp_kses_post( isset( $b['text'] ) ? $b['text'] : '' ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
		<?php ameer_divider( 'yellow-puffs' ); ?>
	</section>
	<?php
}

/**
 * Ingredients grid.
 *
 * @param int $product_id Product ID.
 */
function ameer_render_ingredients( $product_id ) {
	$rows = (array) get_post_meta( $product_id, '_ameer_ingredients', true );
	$rows = array_filter( $rows );
	if ( ! $rows ) {
		return;
	}
	?>
	<section class="ingredients reveal" id="ingredients" aria-labelledby="ingredientsTitle">
		<svg class="ingredients-deco ingredients-deco-cloud" viewBox="0 0 160 80" aria-hidden="true" style="opacity:0.5"><use href="#i-cloud"/></svg>
		<div class="container">
			<h2 id="ingredientsTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'What&rsquo;s <span>inside</span>', 'ameer' ), array( 'span' => array() ) ); ?></h2>
			<div class="ingredient-grid" data-anim="stagger-scale">
				<?php foreach ( $rows as $ing ) : ?>
					<article class="ingredient-card">
						<span class="ingredient-emoji"><?php echo esc_html( isset( $ing['emoji'] ) ? $ing['emoji'] : '' ); ?></span>
						<h3><?php echo esc_html( isset( $ing['title'] ) ? $ing['title'] : '' ); ?></h3>
						<p><?php echo wp_kses_post( isset( $ing['text'] ) ? $ing['text'] : '' ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
		<?php ameer_divider( 'cream-wave-boat' ); ?>
	</section>
	<?php
}

/**
 * Dosage / how-to-use section (steps + age table).
 *
 * @param int $product_id Product ID.
 */
function ameer_render_dosage( $product_id ) {
	$steps = array_filter( (array) get_post_meta( $product_id, '_ameer_dosage_steps', true ) );
	$table = array_filter( (array) get_post_meta( $product_id, '_ameer_dosage_table', true ) );
	$note  = get_post_meta( $product_id, '_ameer_dosage_note', true );
	if ( ! $steps && ! $table ) {
		return;
	}
	?>
	<section class="dosage reveal woocommerce-Tabs-panel--additional_information" id="tab-additional_information" aria-labelledby="dosageTitle">
		<div class="container">
			<span class="eyebrow" data-anim="reveal-up"><?php esc_html_e( 'Easy as 1-2-3', 'ameer' ); ?></span>
			<h2 id="dosageTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'How to <span>use</span> it', 'ameer' ), array( 'span' => array() ) ); ?></h2>
			<div class="dosage-wrap">
				<?php if ( $steps ) : ?>
					<ol class="step-list" data-anim="stagger-up">
						<?php $n = 0; foreach ( $steps as $step ) : $n++; ?>
							<li class="step-card"><span class="step-num"><?php echo (int) $n; ?></span><div><h3><?php echo esc_html( isset( $step['title'] ) ? $step['title'] : '' ); ?></h3><p><?php echo wp_kses_post( isset( $step['text'] ) ? $step['text'] : '' ); ?></p></div></li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>
				<?php if ( $table ) : ?>
					<div class="dosage-table-card" data-anim="reveal-up">
						<h3><?php esc_html_e( 'Serving by age', 'ameer' ); ?></h3>
						<table class="dosage-table">
							<thead><tr><th scope="col"><?php esc_html_e( 'Age', 'ameer' ); ?></th><th scope="col"><?php esc_html_e( 'Serving', 'ameer' ); ?></th></tr></thead>
							<tbody>
								<?php foreach ( $table as $tr ) : ?>
									<tr><td><?php echo esc_html( isset( $tr['age'] ) ? $tr['age'] : '' ); ?></td><td><?php echo esc_html( isset( $tr['serving'] ) ? $tr['serving'] : '' ); ?></td></tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php if ( $note ) : ?><p class="dosage-fine"><?php echo esc_html( $note ); ?></p><?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php ameer_divider( 'river-wave-cloud' ); ?>
	</section>
	<?php
}

/**
 * Reviews section — matches the static design: big score + 5 rating bars +
 * featured review cards. The score, count and bar percentages are computed from
 * real approved WooCommerce reviews. Featured cards remain editable via product
 * meta (no ACF):
 *   _ameer_reviews       repeater [{quote, name, city, rating, color}]
 *
 * @param WC_Product $product Product.
 */
function ameer_render_reviews( $product ) {
	$cards = array_filter( (array) get_post_meta( $product->get_id(), '_ameer_reviews', true ) );

	// Score, count and bar percentages are derived from real approved reviews so
	// they move as customers submit and admins approve — no hardcoded figures.
	$avg    = (float) $product->get_average_rating();
	$count  = (int) $product->get_rating_count();
	$counts = $product->get_rating_counts();
	$bars   = array();
	for ( $star = 5; $star >= 1; $star-- ) {
		$qty    = isset( $counts[ $star ] ) ? (int) $counts[ $star ] : 0;
		$bars[] = $count ? round( ( $qty / $count ) * 100 ) : 0;
	}

	// Nothing to show at all (no real reviews and no featured cards) → skip.
	if ( ! $count && ! $cards ) {
		return;
	}

	$colors = array( '#FA6255', '#A6D17D', '#FDCB46', '#91BEF8', '#FA6255' );
	?>
	<section class="reviews reveal" id="reviews" aria-labelledby="reviewsTitle">
		<svg class="reviews-deco reviews-deco-cloud-1" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>
		<svg class="reviews-deco reviews-deco-cloud-2" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>
		<div class="container">
			<span class="eyebrow" data-anim="reveal-up" style="color:#FFF9E6"><?php esc_html_e( 'Real mom reviews', 'ameer' ); ?></span>
			<h2 id="reviewsTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'Loved by <span>mommies</span>', 'ameer' ), array( 'span' => array() ) ); ?></h2>

			<?php if ( $count > 0 ) : ?>
				<div class="review-summary" data-anim="reveal-up">
					<div class="review-score">
						<strong><?php echo esc_html( number_format_i18n( $avg, 1 ) ); ?></strong>
						<span class="star-rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'ameer' ), number_format_i18n( $avg, 1 ) ) ); ?>"><?php echo esc_html( ameer_stars( max( 1, (int) round( $avg ) ) ) ); ?></span>
						<small><?php printf( esc_html( _n( 'based on %s review', 'based on %s reviews', $count, 'ameer' ) ), esc_html( number_format_i18n( $count ) ) ); ?></small>
					</div>
					<ul class="review-bars" role="list">
						<?php
						$labels = array( 5, 4, 3, 2, 1 );
						foreach ( $labels as $li => $star ) :
							$pct = isset( $bars[ $li ] ) ? (int) $bars[ $li ] : 0;
							?>
							<li><span><?php echo (int) $star; ?>&#x2605;&#xFE0E;</span><span class="bar"><span style="width:<?php echo (int) $pct; ?>%"></span></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $cards ) : ?>
				<ul class="review-grid" role="list" data-anim="stagger-up">
					<?php foreach ( $cards as $ci => $c ) :
						$color  = ! empty( $c['color'] ) ? $c['color'] : $colors[ $ci % count( $colors ) ];
						$rating = ! empty( $c['rating'] ) ? min( 5, max( 1, (int) $c['rating'] ) ) : 5;
						?>
						<li class="testimonial">
							<svg class="quote-mark" viewBox="0 0 40 36" aria-hidden="true" style="color:<?php echo esc_attr( $color ); ?>"><use href="#i-quote"/></svg>
							<div class="stars" aria-label="<?php echo esc_attr( sprintf( _n( '%d star', '%d stars', $rating, 'ameer' ), $rating ) ); ?>"><?php echo esc_html( ameer_stars( $rating ) ); ?></div>
							<p><?php echo esc_html( isset( $c['quote'] ) ? $c['quote'] : '' ); ?></p>
							<div class="who">
								<div><cite><?php echo esc_html( isset( $c['name'] ) ? $c['name'] : '' ); ?></cite><?php if ( ! empty( $c['city'] ) ) : ?><small><?php echo esc_html( $c['city'] ); ?></small><?php endif; ?></div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="center-cta" data-anim="reveal-up">
					<a href="#review-form" class="btn btn-secondary" data-review-toggle><?php esc_html_e( 'Write a review', 'ameer' ); ?></a>
				</div>
			<?php endif; ?>

			<?php ameer_render_review_io( $product ); ?>
		</div>
		<?php ameer_divider( 'blue-puffs-kite' ); ?>
	</section>
	<?php
}

/**
 * Real, working customer reviews: a list of approved reviews plus a submission
 * form for logged-in users. Submissions go through WooCommerce's native comment
 * pipeline — WC stores the rating as comment meta and forces comment_type
 * 'review' for comments on products (see WC_Comments), and WordPress holds the
 * comment for moderation. Once an admin approves it, it appears here
 * automatically. Rendered below the curated marketing summary/cards.
 *
 * @param WC_Product $product Product.
 */
function ameer_render_review_io( $product ) {
	$product_id = $product->get_id();

	// Respect the product's "Enable reviews" setting.
	if ( ! comments_open( $product_id ) ) {
		return;
	}

	$reviews = get_comments(
		array(
			'post_id' => $product_id,
			'status'  => 'approve',
			'type'    => 'review',
			'parent'  => 0,
			'order'   => 'DESC',
		)
	);

	$colors = array( '#FA6255', '#A6D17D', '#FDCB46', '#91BEF8' );
	?>
	<div class="review-io" id="customer-reviews" data-anim="reveal-up">
		<h3 class="review-io-title">
			<?php
			if ( $reviews ) {
				printf(
					esc_html( _n( '%s customer review', '%s customer reviews', count( $reviews ), 'ameer' ) ),
					esc_html( number_format_i18n( count( $reviews ) ) )
				);
			} else {
				esc_html_e( 'Customer reviews', 'ameer' );
			}
			?>
		</h3>

		<?php if ( $reviews ) : ?>
			<ul class="review-list" role="list">
				<?php
				foreach ( $reviews as $ci => $review ) :
					$rating = (int) get_comment_meta( $review->comment_ID, 'rating', true );
					$color  = $colors[ $ci % count( $colors ) ];
					?>
					<li class="testimonial review-item">
						<svg class="quote-mark" viewBox="0 0 40 36" aria-hidden="true" style="color:<?php echo esc_attr( $color ); ?>"><use href="#i-quote"/></svg>
						<?php if ( $rating ) : ?>
							<div class="stars" aria-label="<?php echo esc_attr( sprintf( _n( '%d star', '%d stars', $rating, 'ameer' ), $rating ) ); ?>"><?php echo esc_html( ameer_stars( $rating ) ); ?></div>
						<?php endif; ?>
						<p><?php echo esc_html( get_comment_text( $review ) ); ?></p>
						<div class="who">
							<div>
								<cite><?php echo esc_html( get_comment_author( $review ) ); ?></cite>
								<small><?php echo esc_html( get_comment_date( '', $review ) ); ?></small>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p class="review-empty"><?php esc_html_e( 'No reviews yet — be the first to share what your little one thinks!', 'ameer' ); ?></p>
		<?php endif; ?>

		<?php if ( is_user_logged_in() ) : ?>
			<div class="review-cta" id="review-cta">
				<button type="button" class="btn btn-primary" data-review-toggle aria-expanded="false" aria-controls="review-form"><?php esc_html_e( 'Write a review', 'ameer' ); ?></button>
			</div>
			<div class="review-form-wrap is-collapsed" id="review-form">
				<?php
				$rating_field = '';
				if ( function_exists( 'wc_review_ratings_enabled' ) && wc_review_ratings_enabled() ) {
					$rating_labels = array(
						5 => __( 'Perfect', 'ameer' ),
						4 => __( 'Good', 'ameer' ),
						3 => __( 'Average', 'ameer' ),
						2 => __( 'Not that bad', 'ameer' ),
						1 => __( 'Very poor', 'ameer' ),
					);
					$required      = function_exists( 'wc_review_ratings_required' ) && wc_review_ratings_required() ? ' required' : '';
					$rating_field  = '<p class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating', 'ameer' ) . '</label>';
					$rating_field .= '<select name="rating" id="rating"' . $required . '>';
					$rating_field .= '<option value="">' . esc_html__( 'Rate…', 'ameer' ) . '</option>';
					foreach ( $rating_labels as $val => $label ) {
						$rating_field .= '<option value="' . esc_attr( $val ) . '">' . esc_html( $label ) . '</option>';
					}
					$rating_field .= '</select></p>';
				}

				comment_form(
					array(
						'title_reply'          => esc_html__( 'Write a review', 'ameer' ),
						'title_reply_before'   => '<h3 id="reply-title" class="review-form-title">',
						'title_reply_after'    => '</h3>',
						'label_submit'         => esc_html__( 'Submit review', 'ameer' ),
						'class_submit'         => 'btn btn-primary',
						'comment_notes_before' => '',
						'comment_notes_after'  => '',
						'logged_in_as'         => '',
						'comment_field'        => $rating_field
							. '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'ameer' ) . '</label>'
							. '<textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
					),
					$product_id
				);
				?>
			</div>
		<?php else : ?>
			<div class="review-form-wrap" id="review-form">
				<p class="review-login">
					<?php
					printf(
						/* translators: %s: login URL */
						wp_kses( __( 'Please <a href="%s">log in</a> to write a review.', 'ameer' ), array( 'a' => array( 'href' => array() ) ) ),
						esc_url( wp_login_url( get_permalink( $product_id ) ) )
					);
					?>
				</p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * FAQ accordion.
 *
 * @param int $product_id Product ID.
 */
function ameer_render_faq( $product_id ) {
	$rows = array_filter( (array) get_post_meta( $product_id, '_ameer_faq', true ) );
	if ( ! $rows ) {
		return;
	}
	?>
	<section class="faq reveal" id="faq" aria-labelledby="faqTitle">
		<div class="container">
			<span class="eyebrow" data-anim="reveal-up"><?php esc_html_e( 'Good to know', 'ameer' ); ?></span>
			<h2 id="faqTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'Mom <span>questions</span>, answered', 'ameer' ), array( 'span' => array() ) ); ?></h2>
			<div class="faq-list" data-anim="stagger-up">
				<?php $first = true; foreach ( $rows as $f ) : ?>
					<details class="faq-item"<?php echo $first ? ' open' : ''; ?>>
						<summary><?php echo esc_html( isset( $f['q'] ) ? $f['q'] : '' ); ?></summary>
						<div class="faq-body"><?php echo wpautop( wp_kses_post( isset( $f['a'] ) ? $f['a'] : '' ) ); ?></div>
					</details>
				<?php $first = false; endforeach; ?>
			</div>
		</div>
		<?php ameer_divider( 'cream-hill-tree' ); ?>
	</section>
	<?php
}

/**
 * Output one of the named full-bleed SVG section dividers.
 *
 * @param string $type Divider preset.
 */
function ameer_divider( $type ) {
	switch ( $type ) {
		case 'yellow-puffs':
			?>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
					<g fill="#FDCB46"><rect x="0" y="78" width="1440" height="12"/><ellipse cx="130" cy="74" rx="120" ry="32"/><ellipse cx="360" cy="70" rx="140" ry="38"/><ellipse cx="600" cy="74" rx="120" ry="32"/><ellipse cx="840" cy="70" rx="140" ry="38"/><ellipse cx="1080" cy="74" rx="120" ry="32"/><ellipse cx="1320" cy="70" rx="140" ry="38"/></g>
				</svg>
			</div>
			<?php
			break;
		case 'cream-wave-boat':
			?>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none"><path d="M0,90 L1440,90 L1440,55 C1080,28 720,82 360,55 C180,42 90,58 0,55 Z" fill="#FFF9E6"/></svg>
				<svg class="divider-deco divider-boat" viewBox="0 0 80 70"><use href="#i-boat"/></svg>
			</div>
			<?php
			break;
		case 'river-wave-cloud':
			?>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none"><path d="M0,90 L1440,90 L1440,50 C1080,80 720,20 360,50 C180,65 90,42 0,50 Z" fill="#91BEF8"/></svg>
				<svg class="divider-deco divider-cloud-mini" viewBox="0 0 80 40"><use href="#i-cloud-mini"/></svg>
			</div>
			<?php
			break;
		case 'blue-puffs-kite':
			?>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
					<g fill="#B5D2F7"><rect x="0" y="78" width="1440" height="12"/><ellipse cx="110" cy="74" rx="100" ry="28"/><ellipse cx="310" cy="72" rx="120" ry="34"/><ellipse cx="520" cy="74" rx="100" ry="28"/><ellipse cx="720" cy="72" rx="120" ry="34"/><ellipse cx="920" cy="74" rx="100" ry="28"/><ellipse cx="1130" cy="72" rx="120" ry="34"/><ellipse cx="1340" cy="74" rx="100" ry="28"/></g>
				</svg>
				<svg class="divider-deco divider-kite" viewBox="0 0 50 90"><use href="#i-kite"/></svg>
			</div>
			<?php
			break;
		case 'cream-hill-tree':
			?>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none"><path d="M0,90 L1440,90 L1440,52 C1200,22 960,72 720,45 C480,18 240,62 0,52 Z" fill="#FFF9E6"/></svg>
				<svg class="divider-deco divider-tree-2" viewBox="0 0 60 90"><use href="#i-tree"/></svg>
			</div>
			<?php
			break;
		case 'green-hills-tree':
			?>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none"><path d="M0,90 L1440,90 L1440,55 C1200,28 960,75 720,48 C480,20 240,65 0,55 Z" fill="#A6D17D"/></svg>
				<svg class="divider-deco divider-tree" viewBox="0 0 60 90"><use href="#i-tree"/></svg>
			</div>
			<?php
			break;
		case 'yellow-hill-spark':
			?>
			<svg class="divider-lorry" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-lorry"/></svg>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none"><path d="M0,90 L1440,90 L1440,45 C1200,18 960,68 720,38 C480,12 240,55 0,45 Z" fill="#FDCB46"/></svg>
				<svg class="divider-deco divider-spark-1" viewBox="0 0 30 30" style="color:#FA6255"><use href="#i-sparkle"/></svg>
				<svg class="divider-deco divider-spark-2" viewBox="0 0 30 30" style="color:#FFF9E6"><use href="#i-sparkle"/></svg>
			</div>
			<?php
			break;
	}
}

/* ----------------------------------------- Sticky mobile buy bar + cart toast */

add_action( 'wp_footer', 'ameer_product_footer_widgets' );
function ameer_product_footer_widgets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	global $product;
	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	?>
	<div class="sticky-buy" id="stickyBuy" aria-hidden="true">
		<div class="container sticky-buy-inner">
			<?php echo $product->get_image( array( 64, 64 ), array( 'class' => 'sticky-buy-img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="sticky-buy-info">
				<strong><?php echo esc_html( $product->get_name() ); ?></strong>
				<span><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			</div>
			<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn btn-primary" data-sticky-add><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
		</div>
	</div>

	<div class="cart-toast" id="cartToast" role="status" aria-live="polite" hidden>
		<span><?php esc_html_e( '🎉 Added to cart!', 'ameer' ); ?></span>
	</div>
	<?php
}
