<?php
/**
 * One-time seeder for sample blog articles (so single.php / archives can be
 * checked against the static article-*.html design). Safe to delete after use.
 *
 *   wp eval-file wp-content/themes/ameer-homepage/tools/seed-articles.php
 * or (admin) /wp-content/themes/ameer-homepage/tools/seed-articles.php?run=1
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';
	if ( ! file_exists( $wp_load ) ) {
		exit( "Could not locate wp-load.php\n" );
	}
	require_once $wp_load;
	$is_cli = ( 'cli' === php_sapi_name() );
	if ( ! ( defined( 'WP_CLI' ) && WP_CLI ) && ! $is_cli ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Administrators only.' );
		}
		if ( ! isset( $_GET['run'] ) ) {
			wp_die( 'Add ?run=1 to run the article seeder.' );
		}
	}
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

function ameer_art_log( $m ) {
	$line = '[seed-articles] ' . $m;
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		WP_CLI::log( $line );
	} else {
		echo esc_html( $line ) . "<br>\n";
	}
}

/** Import a theme asset once; return attachment ID. */
function ameer_art_image( $relative, $parent = 0 ) {
	$src = get_template_directory() . '/' . ltrim( $relative, '/' );
	if ( ! file_exists( $src ) ) {
		return 0;
	}
	$found = get_posts(
		array(
			'post_type'   => 'attachment',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_ameer_seed_src',
			'meta_value'  => $relative,
		)
	);
	if ( $found ) {
		return (int) $found[0];
	}
	$upload = wp_upload_bits( basename( $src ), null, file_get_contents( $src ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$type = wp_check_filetype( $upload['file'], null );
	$id   = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'],
			'post_title'     => sanitize_file_name( pathinfo( $upload['file'], PATHINFO_FILENAME ) ),
			'post_status'    => 'inherit',
		),
		$upload['file'],
		$parent
	);
	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_ameer_seed_src', $relative );
	return (int) $id;
}

/** Ensure a category exists; return term_id. */
function ameer_art_cat( $name, $slug ) {
	$t = term_exists( $slug, 'category' );
	if ( ! $t ) {
		$t = wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
	}
	return is_wp_error( $t ) ? 0 : (int) $t['term_id'];
}

$picky_body = <<<'HTML'
<p>If dinner has turned into a nightly negotiation, you&rsquo;re not alone. Picky eating is a completely normal phase for toddlers and young kids — it&rsquo;s how they assert a little independence. The good news? A few small tweaks can turn the table around. Here are the five that real Ameer moms swear by.</p>

<h2>1. Serve it deconstructed</h2>
<p>Many kids reject mixed dishes simply because they can&rsquo;t see what&rsquo;s inside. Instead of a loaded pasta bake, lay the components out side by side — plain pasta, a little sauce, some cheese, a few veggies. Letting them &ldquo;build their own&rdquo; gives them control and lowers their guard.</p>

<div class="article-keycard"><span class="kc-num">2</span><div><h3>Offer two yeses, never a no</h3><p>Rather than asking &ldquo;Do you want broccoli?&rdquo; (an easy no), offer a choice between two good options: &ldquo;Carrots or cucumber today?&rdquo; They feel in charge, and either answer is a win for you.</p></div></div>

<h2>3. Make it tiny and fun</h2>
<p>Small portions feel achievable, not overwhelming. Cut food into bite-size shapes, use a cookie cutter on sandwiches, or arrange a colourful &ldquo;rainbow plate.&rdquo; A playful presentation can do more than any lecture about vitamins.</p>

<blockquote><p>&ldquo;My son refused every vegetable for months. The day I started calling broccoli &lsquo;little trees&rsquo; and let him dip them, he ate five. Sometimes it&rsquo;s all in the framing.&rdquo; — Aisyah, KL</p></blockquote>

<h2>4. Eat together, model the bite</h2>
<p>Children learn by watching you. When they see the whole family enjoying the same food calmly — without pressure or comment — they&rsquo;re far more likely to try it themselves. Skip the running commentary on how much they&rsquo;ve eaten.</p>

<h2>5. Support the gut behind the scenes</h2>
<p>Appetite and digestion go hand in hand. A balanced gut helps little ones feel genuinely hungry at mealtimes, which makes everything easier. A daily prebiotic fiber can gently support smooth digestion and a healthier appetite — especially handy for the pickiest of eaters.</p>

<h2>The bottom line</h2>
<p>Be patient and keep it pressure-free. It can take ten or more exposures before a child accepts a new food, so keep offering without forcing. Small, consistent wins add up — and one day you&rsquo;ll blink and they&rsquo;ll be asking for seconds.</p>
HTML;

$banana_body = <<<'HTML'
<p>Mornings are hectic, but breakfast doesn&rsquo;t have to be. These banana-oat bowls come together in about ten minutes and are packed with slow-release energy to power little ones through to lunch.</p>
<h2>What you&rsquo;ll need</h2>
<ul><li>1 ripe banana</li><li>½ cup rolled oats</li><li>1 cup milk of choice</li><li>A handful of berries</li></ul>
<h2>Method</h2>
<p>Mash the banana, stir through the oats and milk, and let it sit for five minutes. Top with berries and a sprinkle of seeds. Done!</p>
<blockquote><p>&ldquo;My kids actually ask for seconds. It&rsquo;s the only breakfast that survives a school morning here.&rdquo; — Nadia, Selangor</p></blockquote>
HTML;

$meal_body = <<<'HTML'
<p>A little planning takes the guesswork out of the school week. Here&rsquo;s a simple, balanced seven-day framework you can mix and match around what your family already loves.</p>
<h2>The week at a glance</h2>
<table class="dosage-table"><thead><tr><th>Day</th><th>Lunch idea</th></tr></thead><tbody><tr><td>Monday</td><td>Chicken rice bowl + cucumber</td></tr><tr><td>Tuesday</td><td>Wholemeal sandwich + fruit</td></tr><tr><td>Wednesday</td><td>Pasta + hidden-veg sauce</td></tr></tbody></table>
<p>Keep it flexible — the goal is balance across the week, not perfection at every meal.</p>
HTML;

$articles = array(
	array( 'slug' => 'picky-eater-5-quick-wins', 'title' => 'Picky eater? 5 quick wins', 'cat' => array( 'Parenting tip', 'parenting-tip' ), 'img' => 'assets/blog/tip-1.webp', 'excerpt' => 'Mealtimes shouldn&rsquo;t feel like a standoff. These five simple, mom-tested moves help you sneak real nutrition into even the most stubborn little eater — no bribery required.', 'body' => $picky_body, 'date' => '2026-03-14 09:00:00' ),
	array( 'slug' => 'banana-oat-breakfast-bowls', 'title' => 'Banana-oat breakfast bowls', 'cat' => array( 'Recipe', 'recipe' ), 'img' => 'assets/blog/tip-2.webp', 'excerpt' => '10-minute breakfasts kids will actually finish — packed with goodness.', 'body' => $banana_body, 'date' => '2026-03-28 09:00:00' ),
	array( 'slug' => 'balanced-meals-busy-week', 'title' => 'Balanced meals, busy week', 'cat' => array( 'Meal plan', 'meal-plan' ), 'img' => 'assets/blog/tip-3.webp', 'excerpt' => 'A 7-day plan that takes the guesswork out of school-week lunches.', 'body' => $meal_body, 'date' => '2026-04-11 09:00:00' ),
);

// Make sure the homepage "recipes" category exists too.
$recipes_cat = ameer_art_cat( 'Recipes', 'recipes' );

foreach ( $articles as $a ) {
	$existing = get_posts(
		array(
			'post_type'   => 'post',
			'name'        => $a['slug'],
			'post_status' => array( 'publish', 'draft' ),
			'numberposts' => 1,
			'fields'      => 'ids',
		)
	);
	$cat_id  = ameer_art_cat( $a['cat'][0], $a['cat'][1] );
	$cat_ids = array_values( array_filter( array( $cat_id, $recipes_cat ) ) );

	$postarr = array(
		'post_type'     => 'post',
		'post_status'   => 'publish',
		'post_title'    => $a['title'],
		'post_name'     => $a['slug'],
		'post_excerpt'  => $a['excerpt'],
		'post_content'  => $a['body'],
		'post_date'     => $a['date'],
		'post_category' => $cat_ids,
	);
	if ( $existing ) {
		$postarr['ID'] = $existing[0];
	}
	$post_id = wp_insert_post( $postarr );
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		ameer_art_log( 'Failed: ' . $a['title'] );
		continue;
	}
	$img = ameer_art_image( $a['img'], $post_id );
	if ( $img ) {
		set_post_thumbnail( $post_id, $img );
	}
	ameer_art_log( "Seeded: {$a['title']} (#{$post_id})" );
}

ameer_art_log( 'Done. Delete tools/seed-articles.php when finished.' );
