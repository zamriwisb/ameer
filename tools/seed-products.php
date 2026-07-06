<?php
/**
 * One-time seeder for the three Ameer WooCommerce products.
 *
 * NOT loaded by the theme. Run it once, then it is safe to delete.
 *
 * Preferred (LocalWP "Open site shell"):
 *     wp eval-file wp-content/themes/ameer-homepage/tools/seed-products.php
 *
 * Browser fallback (must be logged in as an administrator):
 *     /wp-content/themes/ameer-homepage/tools/seed-products.php?run=1
 *
 * Idempotent: re-running updates the existing products (matched by slug) and
 * never re-imports an image it already imported (tracked via _ameer_seed_src).
 *
 * @package Ameer
 */

// ----- Bootstrap (browser fallback only; wp eval-file already has WP loaded).
if ( ! defined( 'ABSPATH' ) ) {
	$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php'; // themes/<t>/tools → public/.
	if ( ! file_exists( $wp_load ) ) {
		exit( "Could not locate wp-load.php\n" );
	}
	require_once $wp_load;

	$is_cli = ( 'cli' === php_sapi_name() );
	if ( ! ( defined( 'WP_CLI' ) && WP_CLI ) && ! $is_cli ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You must be logged in as an administrator to run the seeder.' );
		}
		if ( ! isset( $_GET['run'] ) ) {
			wp_die( 'Add ?run=1 to the URL to run the product seeder.' );
		}
	}
}

if ( ! class_exists( 'WooCommerce' ) ) {
	ameer_seed_log( 'WooCommerce is not active — aborting.' );
	return;
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

/**
 * Output a log line for both CLI and browser.
 */
function ameer_seed_log( $msg ) {
	$line = '[seed] ' . $msg;
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::log( $line );
	} else {
		echo esc_html( $line ) . "<br>\n";
	}
}

/**
 * Import a theme asset into the media library (once), return attachment ID.
 *
 * @param string $relative Path relative to the theme root, e.g. assets/products/x.webp.
 * @param int    $parent   Parent post ID.
 * @return int Attachment ID or 0.
 */
function ameer_seed_image( $relative, $parent = 0 ) {
	$src = get_template_directory() . '/' . ltrim( $relative, '/' );
	if ( ! file_exists( $src ) ) {
		ameer_seed_log( "Missing image: {$relative}" );
		return 0;
	}

	// Reuse if already imported.
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_ameer_seed_src',
			'meta_value'     => $relative,
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	$upload = wp_upload_bits( basename( $src ), null, file_get_contents( $src ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		ameer_seed_log( 'Upload error: ' . $upload['error'] );
		return 0;
	}

	$filetype = wp_check_filetype( $upload['file'], null );
	$attach_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => sanitize_file_name( pathinfo( $upload['file'], PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file'],
		$parent
	);
	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
		ameer_seed_log( "Failed attachment for {$relative}" );
		return 0;
	}
	wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $upload['file'] ) );
	update_post_meta( $attach_id, '_ameer_seed_src', $relative );
	return (int) $attach_id;
}

/**
 * Ensure a product_cat term exists; return term ID.
 */
function ameer_seed_term( $name ) {
	$term = term_exists( $name, 'product_cat' );
	if ( ! $term ) {
		$term = wp_insert_term( $name, 'product_cat' );
	}
	return is_wp_error( $term ) ? 0 : (int) $term['term_id'];
}

/* ----------------------------------------------------------------- Product data */

$products = array(
	array(
		'slug'          => 'magnesium-glycinate',
		'name'          => 'Magnesium Glycinate',
		'category'      => 'Calm & Relaxation',
		'eyebrow'       => 'Calm & Relaxation',
		'sku'           => 'AMR-MAG',
		'regular_price' => '99',
		'sale_price'    => '89',
		'price_note'    => '/ 60 servings',
		'featured_img'  => 'assets/products/product-hero-mag1.webp',
		'gallery'       => array( 'assets/hero-visual.webp' ),
		'short'         => '<p>Gentle magnesium glycinate that helps little minds settle and sleep through the night — sugar-free, berry-loved, and easy on tiny tummies.</p>',
		'benefit_tags'  => array( '😴 Calmer bedtimes', '💪 Relaxed muscles', '🍓 Berry taste kids love' ),
		'benefits'      => array(
			array( 'icon' => '😴', 'color' => '#91BEF8', 'title' => 'Calmer bedtimes', 'text' => 'Magnesium helps little minds wind down for deeper, longer sleep.' ),
			array( 'icon' => '💪', 'color' => '#A6D17D', 'title' => 'Relaxed muscles', 'text' => 'Eases growing-pain wriggles so they wake up rested and ready to play.' ),
			array( 'icon' => '😊', 'color' => '#FDCB46', 'title' => 'Happier moods', 'text' => 'Supports a calm, steady mood through busy school-day ups and downs.' ),
			array( 'icon' => '🦴', 'color' => '#FA6255', 'title' => 'Strong bones', 'text' => 'Works with calcium for healthy bones and teeth as they grow.' ),
		),
		'ingredients'   => array(
			array( 'emoji' => '🧪', 'title' => 'Magnesium Glycinate', 'text' => 'The gentlest, most absorbable form — kind to little tummies.' ),
			array( 'emoji' => '🍓', 'title' => 'Natural Berry', 'text' => 'Real fruit flavour, no artificial colours or sweeteners.' ),
			array( 'emoji' => '🚫', 'title' => 'No Added Sugar', 'text' => 'Zero added sugar, gluten-free and SSM registered.' ),
			array( 'emoji' => '🌙', 'title' => 'Halal Certified', 'text' => 'JAKIM-recognised, made in a certified facility.' ),
		),
		'dosage_steps'  => array(
			array( 'title' => 'Scoop', 'text' => 'One level scoop, straight from the tub.' ),
			array( 'title' => 'Mix', 'text' => 'Stir into water, milk or a favourite smoothie.' ),
			array( 'title' => 'Sip', 'text' => 'Once daily, about an hour before bedtime.' ),
		),
		'dosage_table'  => array(
			array( 'age' => '1 – 3 years', 'serving' => '½ scoop daily' ),
			array( 'age' => '4 – 8 years', 'serving' => '1 scoop daily' ),
			array( 'age' => '9 years +', 'serving' => '1 – 2 scoops daily' ),
		),
		'dosage_note'   => 'Always consult your paediatrician for children under 1. Store in a cool, dry place.',
		'faq'           => array(
			array( 'q' => 'Is Magnesium Glycinate halal certified?', 'a' => 'Yes — Magnesium Glycinate is JAKIM-recognised halal and made in a certified facility. SSM registered too.' ),
			array( 'q' => 'When is the best time to give it?', 'a' => 'About an hour before bedtime works best, since magnesium helps little ones wind down for sleep.' ),
			array( 'q' => 'Are there any side effects?', 'a' => 'We use magnesium glycinate — the gentlest form — so it&rsquo;s easy on tummies. Start with the lower serving for your child&rsquo;s age.' ),
			array( 'q' => 'Can I mix it into food or drinks?', 'a' => 'Absolutely. Stir a scoop into water, milk, yoghurt or a smoothie — it dissolves easily with a light berry taste.' ),
			array( 'q' => 'Do you offer a subscription?', 'a' => 'Yes! Subscribe &amp; save 15% with free monthly delivery. Pause or cancel anytime.' ),
		),
	),
	array(
		'slug'          => 'prebiotic-almanna',
		'name'          => 'Prebiotic Almanna',
		'category'      => 'Picky Eater',
		'eyebrow'       => 'Picky Eater',
		'sku'           => 'AMR-PRE',
		'regular_price' => '89',
		'sale_price'    => '79',
		'price_note'    => '/ 30 servings',
		'featured_img'  => 'assets/products/feature-prebiotic.webp',
		'gallery'       => array( 'assets/products/prebiotics.webp' ),
		'short'         => '<p>For picky eaters, from pickier moms. Al Manna fruit-derived prebiotic fiber feeds the good gut bacteria for smooth digestion, a happy tummy and a healthier appetite — no added sugar.</p>',
		'benefit_tags'  => array( '🌱 Gut-friendly fiber', '💩 Smooth digestion', '🍊 Fruit-derived' ),
		'benefits'      => array(
			array( 'icon' => '🌱', 'color' => '#A6D17D', 'title' => 'Smooth digestion', 'text' => 'Gentle fiber keeps things moving — bye-bye constipation and bloating.' ),
			array( 'icon' => '🍽️', 'color' => '#FDCB46', 'title' => 'Better appetite', 'text' => 'A balanced gut helps picky eaters feel hungry and enjoy mealtimes.' ),
			array( 'icon' => '🛡️', 'color' => '#91BEF8', 'title' => 'Stronger immunity', 'text' => 'Feeds the good bacteria where most of the immune system lives.' ),
			array( 'icon' => '😊', 'color' => '#FA6255', 'title' => 'Happier moods', 'text' => 'A calm, comfortable tummy means a brighter, more settled little one.' ),
		),
		'ingredients'   => array(
			array( 'emoji' => '🍊', 'title' => 'Fruit Fiber Extract', 'text' => 'Naturally fruit-derived prebiotic fiber — gentle and easy to digest.' ),
			array( 'emoji' => '🌱', 'title' => 'Prebiotic Inulin', 'text' => 'Food for the good gut bacteria that keep digestion running smoothly.' ),
			array( 'emoji' => '🚫', 'title' => 'No Added Sugar', 'text' => 'Zero added sugar, no artificial colours or sweeteners. SSM registered.' ),
			array( 'emoji' => '🌙', 'title' => 'Halal Certified', 'text' => 'JAKIM-recognised, made in a certified facility.' ),
		),
		'dosage_steps'  => array(
			array( 'title' => 'Scoop', 'text' => 'One level scoop, straight from the pack.' ),
			array( 'title' => 'Mix', 'text' => 'Stir into water, juice, milk or a favourite smoothie.' ),
			array( 'title' => 'Sip', 'text' => 'Once daily, any time — it&rsquo;s flavourless and dissolves clear.' ),
		),
		'dosage_table'  => array(
			array( 'age' => '1 – 3 years', 'serving' => '½ scoop daily' ),
			array( 'age' => '4 – 8 years', 'serving' => '1 scoop daily' ),
			array( 'age' => '9 years +', 'serving' => '1 – 2 scoops daily' ),
		),
		'dosage_note'   => 'Always consult your paediatrician for children under 1. Drink with plenty of water. Store in a cool, dry place.',
		'faq'           => array(
			array( 'q' => 'What&rsquo;s the difference between prebiotics and probiotics?', 'a' => 'Probiotics are the good bacteria themselves; prebiotics are the fiber that feeds them. Our prebiotic fiber helps your child&rsquo;s existing good bacteria flourish.' ),
			array( 'q' => 'Is it halal certified?', 'a' => 'Yes — our prebiotics are JAKIM-recognised halal, made in a certified facility and SSM registered.' ),
			array( 'q' => 'Will it help with constipation?', 'a' => 'Many parents see smoother, more regular digestion within a week. Start with the lower serving and make sure your little one drinks plenty of water.' ),
			array( 'q' => 'Does it have a taste?', 'a' => 'It&rsquo;s virtually flavourless and dissolves clear, so it&rsquo;s perfect for picky eaters — mix it into any drink without changing the taste.' ),
			array( 'q' => 'Do you offer a subscription?', 'a' => 'Yes! Subscribe &amp; save 15% with free monthly delivery. Pause or cancel anytime.' ),
		),
	),
	array(
		'slug'          => 'tigermilk-plus',
		'name'          => 'Tigermilk+',
		'category'      => 'Respiratory Support',
		'eyebrow'       => 'Respiratory Support',
		'sku'           => 'AMR-TIG',
		'regular_price' => '99',
		'sale_price'    => '89',
		'price_note'    => '/ 10 sachets',
		'featured_img'  => 'assets/products/feature-tiger-mushroom.webp',
		'gallery'       => array( 'assets/products/tigermilk-plus.webp' ),
		'short'         => '<p>Tiger Milk mushroom paired with prebiotic &amp; probiotic — gentle daily support for easier breathing, a stronger immune system and fewer sick days. No maltodextrin, no added sugar.</p>',
		'benefit_tags'  => array( '🫁 Easier breathing', '🛡️ Stronger immunity', '🍄 Tiger Milk mushroom' ),
		'benefits'      => array(
			array( 'icon' => '🫁', 'color' => '#91BEF8', 'title' => 'Easier breathing', 'text' => 'Tiger Milk mushroom traditionally supports clear airways and healthy lungs.' ),
			array( 'icon' => '🛡️', 'color' => '#A6D17D', 'title' => 'Stronger immunity', 'text' => 'Beta-glucans help train little immune systems to bounce back faster.' ),
			array( 'icon' => '🦠', 'color' => '#FDCB46', 'title' => 'Fewer sick days', 'text' => 'Prebiotic + probiotic team up to keep coughs and sniffles at bay.' ),
			array( 'icon' => '🌿', 'color' => '#FA6255', 'title' => 'Gut + immune balance', 'text' => 'A happy gut means a stronger defense — 70% of immunity starts there.' ),
		),
		'ingredients'   => array(
			array( 'emoji' => '🍄', 'title' => 'Tiger Milk Mushroom', 'text' => 'Lignosus rhinocerus — a treasured remedy for lungs and airways.' ),
			array( 'emoji' => '🌱', 'title' => 'Prebiotic Fiber', 'text' => 'Feeds the good gut bacteria that power a strong immune system.' ),
			array( 'emoji' => '🦠', 'title' => 'Probiotic Cultures', 'text' => 'Live-friendly strains for balanced tummies and steadier defense.' ),
			array( 'emoji' => '🚫', 'title' => 'No Maltodextrin', 'text' => 'No added sugar, no maltodextrin — JAKIM halal &amp; SSM registered.' ),
		),
		'dosage_steps'  => array(
			array( 'title' => 'Tear', 'text' => 'Open one sachet — fine, ready-to-mix powder inside.' ),
			array( 'title' => 'Mix', 'text' => 'Stir into warm water or milk until smooth.' ),
			array( 'title' => 'Sip', 'text' => 'Once daily, any time of day — great with breakfast.' ),
		),
		'dosage_table'  => array(
			array( 'age' => '2 – 3 years', 'serving' => '½ sachet daily' ),
			array( 'age' => '4 – 8 years', 'serving' => '1 sachet daily' ),
			array( 'age' => '9 years +', 'serving' => '1 – 2 sachets daily' ),
		),
		'dosage_note'   => 'Always consult your paediatrician for children under 2. Store in a cool, dry place.',
		'faq'           => array(
			array( 'q' => 'What is Tiger Milk mushroom?', 'a' => 'Lignosus rhinocerus, a prized South-East Asian mushroom traditionally used to support lungs, airways and immunity. We use a clean, kid-safe extract.' ),
			array( 'q' => 'Is Tigermilk+ halal certified?', 'a' => 'Yes — Tigermilk+ is JAKIM-recognised halal, made in a certified facility and SSM registered.' ),
			array( 'q' => 'Does it contain maltodextrin or sugar?', 'a' => 'No. Tigermilk+ has no maltodextrin and no added sugar — just the mushroom blend with prebiotic and probiotic.' ),
			array( 'q' => 'When should my child take it?', 'a' => 'Any time of day works — many families make it part of the breakfast routine for daily immune support.' ),
			array( 'q' => 'Do you offer a subscription?', 'a' => 'Yes! Subscribe &amp; save 15% with free monthly delivery. Pause or cancel anytime.' ),
		),
	),
);

/* ----------------------------------------------- Reviews / rating (by slug) */

$review_data = array(
	'magnesium-glycinate' => array(
		'badge'   => 'Best seller',
		'rating'  => '4.9',
		'count'   => 128,
		'bars'    => array( 92, 6, 2, 0, 0 ),
		'reviews' => array(
			array( 'quote' => 'My toddler actually sleeps through the night now. Magnesium Glycinate has been a game changer for our whole family.', 'name' => 'Aisyah', 'city' => 'Kuala Lumpur', 'rating' => 5 ),
			array( 'quote' => 'No more bedtime battles. He asks for his "berry drink" himself now — and no sugar guilt for me.', 'name' => 'Nadia', 'city' => 'Selangor', 'rating' => 5 ),
			array( 'quote' => 'Gentle on her tummy and she loves the taste. Finally a magnesium my picky one will take.', 'name' => 'Faridah', 'city' => 'Penang', 'rating' => 5 ),
		),
	),
	'prebiotic-almanna'   => array(
		'badge'   => 'Mom favourite',
		'rating'  => '4.9',
		'count'   => 142,
		'bars'    => array( 93, 5, 2, 0, 0 ),
		'reviews' => array(
			array( 'quote' => 'My daughter struggled with constipation for months. A week on these prebiotics and she\'s finally regular — such a relief.', 'name' => 'Nadia', 'city' => 'Selangor', 'rating' => 5 ),
			array( 'quote' => 'My pickiest eater actually asks for seconds now. It\'s flavourless so I just mix it into her juice — no fuss.', 'name' => 'Faridah', 'city' => 'Penang', 'rating' => 5 ),
			array( 'quote' => 'No added sugar and it dissolves clear — exactly what this picky mom was looking for. Tummies are happy here.', 'name' => 'Aisyah', 'city' => 'Kuala Lumpur', 'rating' => 5 ),
		),
	),
	'tigermilk-plus'      => array(
		'badge'   => 'Best seller',
		'rating'  => '4.8',
		'count'   => 96,
		'bars'    => array( 88, 9, 2, 1, 0 ),
		'reviews' => array(
			array( 'quote' => 'My son used to catch every bug at kindy. Since Tigermilk+, the coughs barely stick around — and he loves the taste.', 'name' => 'Hana', 'city' => 'Shah Alam', 'rating' => 5 ),
			array( 'quote' => 'My asthmatic girl breathes so much easier now. We mix a sachet into her morning milk and she\'s good to go.', 'name' => 'Suraya', 'city' => 'Johor Bahru', 'rating' => 5 ),
			array( 'quote' => 'Finally a mushroom drink with no maltodextrin. Clean ingredients I can trust and zero sugar guilt.', 'name' => 'Aisyah', 'city' => 'Kuala Lumpur', 'rating' => 5 ),
		),
	),
);

/* ----------------------------------------------------------------- Seed loop */

$menu_order = 0;
foreach ( $products as $data ) {
	$menu_order++;

	$existing = get_posts(
		array(
			'post_type'      => 'product',
			'name'           => $data['slug'],
			'post_status'    => array( 'publish', 'draft', 'pending' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	$product = $existing ? wc_get_product( $existing[0] ) : new WC_Product_Simple();

	$product->set_name( $data['name'] );
	$product->set_slug( $data['slug'] );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_featured( true );
	$product->set_menu_order( $menu_order );
	$product->set_sku( $data['sku'] );
	$product->set_regular_price( $data['regular_price'] );
	$product->set_sale_price( $data['sale_price'] );
	$product->set_short_description( $data['short'] );
	$product->set_reviews_allowed( true );

	$cat_id = ameer_seed_term( $data['category'] );
	if ( $cat_id ) {
		$product->set_category_ids( array( $cat_id ) );
	}

	$product_id = $product->save();

	// Images.
	$feat = ameer_seed_image( $data['featured_img'], $product_id );
	if ( $feat ) {
		$product->set_image_id( $feat );
	}
	$gallery_ids = array();
	foreach ( $data['gallery'] as $g ) {
		$gid = ameer_seed_image( $g, $product_id );
		if ( $gid ) {
			$gallery_ids[] = $gid;
		}
	}
	if ( $gallery_ids ) {
		$product->set_gallery_image_ids( $gallery_ids );
	}
	$product_id = $product->save();

	// Ameer meta.
	update_post_meta( $product_id, '_ameer_eyebrow', $data['eyebrow'] );
	update_post_meta( $product_id, '_ameer_price_note', $data['price_note'] );
	update_post_meta( $product_id, '_ameer_dosage_note', $data['dosage_note'] );
	update_post_meta( $product_id, '_ameer_benefit_tags', $data['benefit_tags'] );
	update_post_meta( $product_id, '_ameer_benefits', $data['benefits'] );
	update_post_meta( $product_id, '_ameer_ingredients', $data['ingredients'] );
	update_post_meta( $product_id, '_ameer_dosage_steps', $data['dosage_steps'] );
	update_post_meta( $product_id, '_ameer_dosage_table', $data['dosage_table'] );
	update_post_meta( $product_id, '_ameer_faq', $data['faq'] );

	// Reviews / rating display.
	if ( isset( $review_data[ $data['slug'] ] ) ) {
		$rd = $review_data[ $data['slug'] ];
		update_post_meta( $product_id, '_ameer_badge', $rd['badge'] );
		update_post_meta( $product_id, '_ameer_rating', $rd['rating'] );
		update_post_meta( $product_id, '_ameer_review_count', $rd['count'] );
		update_post_meta( $product_id, '_ameer_review_bars', $rd['bars'] );
		update_post_meta( $product_id, '_ameer_reviews', $rd['reviews'] );
	}

	ameer_seed_log( "Seeded: {$data['name']} (#{$product_id})" );
}

ameer_seed_log( 'Done. You can delete tools/seed-products.php now.' );
