# Contact & About Pages Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add Contact and About custom page templates to the `ameer-homepage` theme, with a contact form that stores submissions as a private CPT and emails the admin.

**Architecture:** Two assignable page templates styled like the homepage (cream hero + SVG divider, `reveal` animations, `.btn` system). A new `inc/contact.php` module mirrors `inc/newsletter.php`: registers a read-only `ameer_message` CPT, renders the form, and handles the `admin-post.php` submission with nonce + honeypot + sanitization. New Customizer fields supply contact details; the About story body comes from the page editor.

**Tech Stack:** WordPress 6.9 (core only, no ACF), PHP, WooCommerce-aware theme. No automated PHP test framework exists in this theme, so each task is verified with `php -l` (syntax) plus explicit manual browser/admin checks.

## Global Constraints

- WordPress core only — no ACF, no new plugins. Honeypot for spam (no reCAPTCHA).
- `functions.php` stays a thin bootstrap; all logic lives in `inc/*` modules.
- Escape all output (`esc_html`/`esc_url`/`esc_attr`/`wp_kses`); sanitize all input.
- Forms POST to `admin-post.php` and redirect back with a status query arg, exactly like `ameer_handle_subscribe()` in `inc/newsletter.php`.
- CSS/JS are scoped under `.theme-v2` / `.theme-sunny` (the body classes) and use the existing CSS custom properties (`--coral`, `--cream`, `--ink`, `--sun`, `--ink-soft`, etc. from `css/sunny-v2.css`).
- Template menu labels: `Ameer: Contact` and `Ameer: About`.
- Contact emails go to `get_option( 'admin_email' )`.
- Versioned assets via `ameer_asset()` + `ameer_ver()`; reuse `ameer_current_url()` from `inc/newsletter.php` for redirect-back.
- `<head>` header is `position:fixed` (~83px); top content needs clearance — reuse the `.article-hero` pattern from `page.php` which already handles this (`css/article-page.css` loads on `is_page()`).

---

### Task 1: Contact module — CPT, form renderer, submission handler

**Files:**
- Create: `inc/contact.php`
- Modify: `functions.php` (add the `ameer_require` line)

**Interfaces:**
- Consumes: `ameer_current_url()` (defined in `inc/newsletter.php`, loaded before this module).
- Produces:
  - `ameer_register_message_cpt()` — registers CPT `ameer_message`.
  - `ameer_contact_form()` — echoes the contact `<form>`.
  - `ameer_handle_contact()` — hooked to `admin_post_ameer_contact` / `admin_post_nopriv_ameer_contact`.

- [ ] **Step 1: Create `inc/contact.php`**

```php
<?php
/**
 * Contact: message CPT, form renderer, admin-post handler.
 *
 * Submissions are stored in WordPress (Messages list in wp-admin) AND emailed
 * to the site admin. Mirrors inc/newsletter.php. Core-only, no plugin.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the (private, read-only) message post type.
 */
add_action( 'init', 'ameer_register_message_cpt' );
function ameer_register_message_cpt() {
	register_post_type(
		'ameer_message',
		array(
			'labels'          => array(
				'name'          => __( 'Messages', 'ameer' ),
				'singular_name' => __( 'Message', 'ameer' ),
				'menu_name'     => __( 'Messages', 'ameer' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 27,
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
			'supports'        => array( 'title', 'editor' ),
		)
	);
}

/**
 * Render the contact form.
 */
function ameer_contact_form() {
	$sent = isset( $_GET['sent'] ) ? sanitize_key( wp_unslash( $_GET['sent'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="ameer_contact" />
		<input type="hidden" name="redirect_to" value="<?php echo esc_url( ameer_current_url() ); ?>" />
		<?php wp_nonce_field( 'ameer_contact', 'ameer_contact_nonce' ); ?>

		<p class="form-row">
			<label for="ameer-name"><?php esc_html_e( 'Your name', 'ameer' ); ?></label>
			<input type="text" id="ameer-name" name="ameer_name" required />
		</p>
		<p class="form-row">
			<label for="ameer-email"><?php esc_html_e( 'Email', 'ameer' ); ?></label>
			<input type="email" id="ameer-email" name="ameer_email" required />
		</p>
		<p class="form-row">
			<label for="ameer-phone"><?php esc_html_e( 'Phone (optional)', 'ameer' ); ?></label>
			<input type="tel" id="ameer-phone" name="ameer_phone" />
		</p>
		<p class="form-row">
			<label for="ameer-message"><?php esc_html_e( 'Message', 'ameer' ); ?></label>
			<textarea id="ameer-message" name="ameer_message" rows="5" required></textarea>
		</p>

		<?php // Honeypot — hidden from users, catches bots. ?>
		<p class="contact-hp" aria-hidden="true">
			<label for="ameer-website"><?php esc_html_e( 'Leave this field empty', 'ameer' ); ?></label>
			<input type="text" id="ameer-website" name="ameer_website" tabindex="-1" autocomplete="off" />
		</p>

		<button type="submit" class="btn btn-primary btn-large"><?php esc_html_e( 'Send Message', 'ameer' ); ?></button>
	</form>
	<?php if ( '1' === $sent ) : ?>
		<p class="contact-notice contact-notice-ok"><?php esc_html_e( 'Thank you — we&rsquo;ve received your message and will reply soon! 🎉', 'ameer' ); ?></p>
	<?php elseif ( 'invalid' === $sent ) : ?>
		<p class="contact-notice contact-notice-err"><?php esc_html_e( 'Please fill in your name, a valid email, and a message.', 'ameer' ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Handle the submission (logged in + logged out).
 */
add_action( 'admin_post_ameer_contact', 'ameer_handle_contact' );
add_action( 'admin_post_nopriv_ameer_contact', 'ameer_handle_contact' );
function ameer_handle_contact() {
	$redirect = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/' );
	$redirect = remove_query_arg( 'sent', $redirect );

	// Nonce.
	if ( ! isset( $_POST['ameer_contact_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ameer_contact_nonce'] ) ), 'ameer_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'invalid', $redirect ) . '#contact-form' );
		exit;
	}

	// Honeypot — silently accept (no store, no email) so bots think they won.
	$hp = isset( $_POST['ameer_website'] ) ? trim( (string) wp_unslash( $_POST['ameer_website'] ) ) : '';
	if ( '' !== $hp ) {
		wp_safe_redirect( add_query_arg( 'sent', '1', $redirect ) . '#contact-form' );
		exit;
	}

	$name    = isset( $_POST['ameer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['ameer_name'] ) ) : '';
	$email   = isset( $_POST['ameer_email'] ) ? sanitize_email( wp_unslash( $_POST['ameer_email'] ) ) : '';
	$phone   = isset( $_POST['ameer_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['ameer_phone'] ) ) : '';
	$message = isset( $_POST['ameer_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['ameer_message'] ) ) : '';

	if ( '' === $name || ! $email || ! is_email( $email ) || '' === $message ) {
		wp_safe_redirect( add_query_arg( 'sent', 'invalid', $redirect ) . '#contact-form' );
		exit;
	}

	// Store the message (never lost, even if email fails).
	$post_id = wp_insert_post(
		array(
			'post_type'    => 'ameer_message',
			'post_title'   => $name,
			'post_content' => $message,
			'post_status'  => 'publish',
		)
	);
	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_ameer_msg_email', $email );
		if ( '' !== $phone ) {
			update_post_meta( $post_id, '_ameer_msg_phone', $phone );
		}
	}

	// Email the admin (best-effort).
	$admin   = get_option( 'admin_email' );
	$subject = sprintf(
		/* translators: %s: sender name. */
		__( 'New contact message from %s', 'ameer' ),
		$name
	);
	$body = sprintf(
		"Name: %s\nEmail: %s\nPhone: %s\n\n%s\n",
		$name,
		$email,
		'' !== $phone ? $phone : '—',
		$message
	);
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
	wp_mail( $admin, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'sent', '1', $redirect ) . '#contact-form' );
	exit;
}
```

- [ ] **Step 2: Lint the new module**

Run: `php -l inc/contact.php`
Expected: `No syntax errors detected in inc/contact.php`

- [ ] **Step 3: Wire the module into `functions.php`**

In `functions.php`, after the line `ameer_require( 'newsletter.php' );`, add:

```php
ameer_require( 'contact.php' );
```

- [ ] **Step 4: Lint `functions.php`**

Run: `php -l functions.php`
Expected: `No syntax errors detected in functions.php`

- [ ] **Step 5: Verify the CPT registered**

Load wp-admin. Expected: a **Messages** menu item (envelope icon) appears in the sidebar with no "Add New" button (read-only).

- [ ] **Step 6: Commit**

```bash
git add inc/contact.php functions.php
git commit -m "feat: add contact message CPT, form renderer and admin-post handler"
```

---

### Task 2: Customizer fields for contact details

**Files:**
- Modify: `inc/customizer.php` (inside `ameer_customize_register()`, the `/* ---- Contact / footer ---- */` block)

**Interfaces:**
- Consumes: existing helpers `ameer_cz_text()`, `ameer_cz_url()` in `inc/customizer.php`.
- Produces: theme mods `ameer_contact_whatsapp`, `ameer_contact_address`, `ameer_contact_hours`, `ameer_map_embed` (all read by Task 3).

- [ ] **Step 1: Add the four fields**

In `inc/customizer.php`, immediately after the line:

```php
	ameer_cz_text( $wp_customize, 'ameer_ssm', __( 'SSM registration', 'ameer' ), 'ameer_contact', 'SSM 201701005157' );
```

insert:

```php
	ameer_cz_text( $wp_customize, 'ameer_contact_whatsapp', __( 'WhatsApp number (digits, e.g. 60143377432)', 'ameer' ), 'ameer_contact', '' );
	ameer_cz_text( $wp_customize, 'ameer_contact_address', __( 'Address', 'ameer' ), 'ameer_contact', '', 'textarea' );
	ameer_cz_text( $wp_customize, 'ameer_contact_hours', __( 'Business hours', 'ameer' ), 'ameer_contact', '', 'textarea' );
	ameer_cz_url( $wp_customize, 'ameer_map_embed', __( 'Google Maps embed URL (iframe src)', 'ameer' ), 'ameer_contact' );
```

- [ ] **Step 2: Lint**

Run: `php -l inc/customizer.php`
Expected: `No syntax errors detected in inc/customizer.php`

- [ ] **Step 3: Verify in the Customizer**

Open **Appearance → Customize → Ameer: Homepage → Contact & Footer**. Expected: four new controls (WhatsApp, Address, Business hours, Google Maps embed URL) appear below "SSM registration". Set the WhatsApp field to a test value and Publish.

- [ ] **Step 4: Commit**

```bash
git add inc/customizer.php
git commit -m "feat: add WhatsApp, address, hours and map Customizer fields"
```

---

### Task 3: Contact page template

**Files:**
- Create: `page-contact.php`

**Interfaces:**
- Consumes: `ameer_contact_form()` (Task 1); theme mods from Task 2 plus existing `ameer_contact_phone`, `ameer_contact_email`, `ameer_ssm`, `ameer_social_*`; helpers `get_header()`/`get_footer()`.
- Produces: a page template selectable as "Ameer: Contact".

- [ ] **Step 1: Create `page-contact.php`**

```php
<?php
/**
 * Template Name: Ameer: Contact
 *
 * Contact details (left) + message form (right), styled like the homepage.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$phone    = get_theme_mod( 'ameer_contact_phone', '014-3377432' );
$email    = get_theme_mod( 'ameer_contact_email', 'nutritionameer@gmail.com' );
$whatsapp = get_theme_mod( 'ameer_contact_whatsapp', '' );
$address  = get_theme_mod( 'ameer_contact_address', '' );
$hours    = get_theme_mod( 'ameer_contact_hours', '' );
$ssm      = get_theme_mod( 'ameer_ssm', 'SSM 201701005157' );
$map      = get_theme_mod( 'ameer_map_embed', '' );
$tiktok   = get_theme_mod( 'ameer_social_tiktok', '' );
$ig       = get_theme_mod( 'ameer_social_instagram', '' );
$fb       = get_theme_mod( 'ameer_social_facebook', '' );

while ( have_posts() ) :
	the_post();
	?>
<main>
	<article class="article-hero" aria-labelledby="pageTitle">
		<div class="container">
			<h1 class="article-title" id="pageTitle"><?php the_title(); ?></h1>
		</div>
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<path d="M0,90 L1440,90 L1440,55 C1080,28 720,82 360,55 C180,42 90,58 0,55 Z" fill="#FFF9E6"/>
			</svg>
		</div>
	</article>

	<section class="contact-section reveal" id="contact-form">
		<div class="container contact-grid">
			<div class="contact-info" data-anim="reveal-up">
				<?php if ( get_the_content() ) : ?>
					<div class="contact-intro"><?php the_content(); ?></div>
				<?php endif; ?>

				<ul class="contact-list" role="list">
					<?php if ( $phone ) : ?>
						<li>
							<span class="contact-label"><?php esc_html_e( 'Phone', 'ameer' ); ?></span>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<li>
							<span class="contact-label"><?php esc_html_e( 'Email', 'ameer' ); ?></span>
							<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $whatsapp ) : ?>
						<li>
							<span class="contact-label"><?php esc_html_e( 'WhatsApp', 'ameer' ); ?></span>
							<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $whatsapp ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Chat with us →', 'ameer' ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $address ) : ?>
						<li>
							<span class="contact-label"><?php esc_html_e( 'Address', 'ameer' ); ?></span>
							<span><?php echo nl2br( esc_html( $address ) ); ?></span>
						</li>
					<?php endif; ?>
					<?php if ( $hours ) : ?>
						<li>
							<span class="contact-label"><?php esc_html_e( 'Hours', 'ameer' ); ?></span>
							<span><?php echo nl2br( esc_html( $hours ) ); ?></span>
						</li>
					<?php endif; ?>
				</ul>

				<?php if ( $tiktok || $ig || $fb ) : ?>
					<div class="contact-social social-icons">
						<?php if ( $tiktok ) : ?>
							<a href="<?php echo esc_url( $tiktok ); ?>" class="soc" aria-label="TikTok" target="_blank" rel="noopener">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43V9a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.84-.43z"/></svg>
							</a>
						<?php endif; ?>
						<?php if ( $ig ) : ?>
							<a href="<?php echo esc_url( $ig ); ?>" class="soc" aria-label="Instagram" target="_blank" rel="noopener">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
							</a>
						<?php endif; ?>
						<?php if ( $fb ) : ?>
							<a href="<?php echo esc_url( $fb ); ?>" class="soc" aria-label="Facebook" target="_blank" rel="noopener">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46H15.2c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( $ssm ) : ?>
					<p class="contact-ssm"><?php printf( esc_html__( 'AMEER AL MANNA SDN BHD (%s)', 'ameer' ), esc_html( $ssm ) ); ?></p>
				<?php endif; ?>
			</div>

			<div class="contact-form-wrap" data-anim="reveal-up" data-anim-delay="200">
				<h2 class="contact-form-title"><?php esc_html_e( 'Send us a message', 'ameer' ); ?></h2>
				<?php ameer_contact_form(); ?>
			</div>
		</div>

		<?php if ( $map ) : ?>
			<div class="container contact-map">
				<iframe src="<?php echo esc_url( $map ); ?>" width="100%" height="360" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'Map', 'ameer' ); ?>"></iframe>
			</div>
		<?php endif; ?>
	</section>
</main>
	<?php
endwhile;

get_footer();
```

- [ ] **Step 2: Lint**

Run: `php -l page-contact.php`
Expected: `No syntax errors detected in page-contact.php`

- [ ] **Step 3: Create the WP page and verify rendering**

In wp-admin: **Pages → Add New**, title "Contact", set **Page Attributes → Template = Ameer: Contact**, optionally add intro text in the editor, Publish. Visit the page.
Expected: cream hero with "Contact" title clear of the fixed header; left column shows phone/email (and WhatsApp if set in Task 2); right column shows the form.

- [ ] **Step 4: Verify a valid submission end-to-end**

Fill name/email/message, submit.
Expected: redirected back to the page with the green "Thank you" notice; a new entry appears under **Messages** in wp-admin (title = the name, body = the message; email/phone in custom fields); the admin email address receives the message.

- [ ] **Step 5: Verify validation + honeypot**

(a) Submit with an empty Message → red "Please fill in…" notice, no new Messages entry.
(b) Using browser devtools, set the hidden `ameer_website` input's value and submit → green success notice, but NO new Messages entry and no email (silent spam drop).

- [ ] **Step 6: Commit**

```bash
git add page-contact.php
git commit -m "feat: add Ameer Contact page template"
```

---

### Task 4: About page template

**Files:**
- Create: `page-about.php`

**Interfaces:**
- Consumes: existing `ameer_about_*` theme mods; `ameer_shop_url()` (helpers); `get_header()`/`get_footer()`.
- Produces: a page template selectable as "Ameer: About".

- [ ] **Step 1: Create `page-about.php`**

```php
<?php
/**
 * Template Name: Ameer: About
 *
 * Intro from the About Customizer fields + story body from the page editor.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$eyebrow = get_theme_mod( 'ameer_about_eyebrow', __( 'Our Promise', 'ameer' ) );
$title   = get_theme_mod( 'ameer_about_title', 'Kid-first, <span>mom-trusted</span>' );
$lead    = get_theme_mod( 'ameer_about_lead', __( 'We make nutrition that real moms actually feel good about. Carefully formulated, halal-certified, and free from added sugar — because the little ones deserve the best start.', 'ameer' ) );

while ( have_posts() ) :
	the_post();
	?>
<main>
	<article class="article-hero" aria-labelledby="pageTitle">
		<div class="container">
			<h1 class="article-title" id="pageTitle"><?php the_title(); ?></h1>
		</div>
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<path d="M0,90 L1440,90 L1440,55 C1080,28 720,82 360,55 C180,42 90,58 0,55 Z" fill="#FFF9E6"/>
			</svg>
		</div>
	</article>

	<section class="about-page reveal">
		<div class="container">
			<div class="about-page-intro">
				<span class="eyebrow" data-anim="reveal-up"><?php echo esc_html( $eyebrow ); ?></span>
				<h2 class="section-title" data-anim="word-reveal"><?php echo wp_kses( $title, array( 'span' => array() ) ); ?></h2>
				<?php if ( $lead ) : ?>
					<p class="about-lead" data-anim="reveal-up" data-anim-delay="200"><?php echo esc_html( $lead ); ?></p>
				<?php endif; ?>
			</div>

			<ul class="badge-row" role="list" data-anim="stagger-up">
				<li class="badge"><span class="badge-ico" style="background:#FA6255;color:white">✓</span><?php esc_html_e( 'Halal Certified', 'ameer' ); ?></li>
				<li class="badge"><span class="badge-ico" style="background:#FDCB46;color:#3A3530">✓</span><?php esc_html_e( 'No Added Sugar', 'ameer' ); ?></li>
				<li class="badge"><span class="badge-ico" style="background:#91BEF8;color:white">✓</span><?php esc_html_e( 'Kids-Formulated', 'ameer' ); ?></li>
				<li class="badge"><span class="badge-ico" style="background:#3A3530;color:white">✓</span><?php esc_html_e( 'SSM Registered', 'ameer' ); ?></li>
			</ul>

			<?php if ( get_the_content() ) : ?>
				<div class="article-prose about-page-body" data-anim="reveal-up">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<div class="about-page-cta" data-anim="reveal-up">
				<a href="<?php echo esc_url( ameer_shop_url() ); ?>" class="btn btn-primary btn-large"><?php esc_html_e( 'Shop Now', 'ameer' ); ?></a>
			</div>
		</div>
	</section>
</main>
	<?php
endwhile;

get_footer();
```

- [ ] **Step 2: Lint**

Run: `php -l page-about.php`
Expected: `No syntax errors detected in page-about.php`

- [ ] **Step 3: Create the WP page and verify rendering**

In wp-admin: **Pages → Add New**, title "About Us", set **Template = Ameer: About**, add a few paragraphs of story content in the editor, Publish. Visit the page.
Expected: cream hero with title clear of the header; intro eyebrow/title/lead from the Customizer; the four badges; the editor story rendered in the readable `.article-prose` column; a "Shop Now" button at the bottom.

- [ ] **Step 4: Commit**

```bash
git add page-about.php
git commit -m "feat: add Ameer About page template"
```

---

### Task 5: Page-template styling + conditional enqueue

**Files:**
- Create: `css/page-templates.css`
- Modify: `inc/enqueue.php` (inside `ameer_enqueue_assets()`)

**Interfaces:**
- Consumes: existing CSS variables from `css/sunny-v2.css`; helpers `ameer_asset()`, `ameer_ver()`.
- Produces: stylesheet handle `ameer-page-templates`.

- [ ] **Step 1: Create `css/page-templates.css`**

```css
/* Contact + About custom page templates. Scoped to the theme body classes. */

/* ---- Contact ---- */
.theme-v2 .contact-section { padding: 3rem 0 4rem; }
.theme-v2 .contact-grid {
	display: grid;
	grid-template-columns: 1fr 1.2fr;
	gap: 2.5rem;
	align-items: start;
}
.theme-v2 .contact-intro { margin-bottom: 1.5rem; color: var(--ink-soft); line-height: 1.6; }
.theme-v2 .contact-list { list-style: none; margin: 0 0 1.5rem; padding: 0; display: grid; gap: 1rem; }
.theme-v2 .contact-list li { display: grid; gap: 0.15rem; }
.theme-v2 .contact-label {
	font-family: 'Fredoka', system-ui, sans-serif;
	font-weight: 700;
	color: var(--coral);
	font-size: 0.95rem;
}
.theme-v2 .contact-list a { color: var(--ink); font-weight: 600; }
.theme-v2 .contact-list a:hover { color: var(--coral); }
.theme-v2 .contact-social { display: flex; gap: 0.6rem; margin-bottom: 1rem; }
.theme-v2 .contact-ssm { color: var(--ink-soft); font-size: 0.85rem; }

.theme-v2 .contact-form-wrap {
	background: #fff;
	border: 3px solid var(--ink);
	border-radius: 22px;
	padding: 1.75rem;
	box-shadow: 0 10px 0 rgba(45,38,32,0.08);
}
.theme-v2 .contact-form-title { font-family: 'Fredoka', system-ui, sans-serif; margin: 0 0 1rem; color: var(--ink); }
.theme-v2 .contact-form .form-row { display: grid; gap: 0.35rem; margin-bottom: 1rem; }
.theme-v2 .contact-form label { font-weight: 700; color: var(--ink); font-size: 0.95rem; }
.theme-v2 .contact-form input,
.theme-v2 .contact-form textarea {
	width: 100%;
	padding: 0.7rem 0.9rem;
	border: 2px solid #E7DFC9;
	border-radius: 12px;
	font: inherit;
	background: var(--cream);
	color: var(--ink);
}
.theme-v2 .contact-form input:focus,
.theme-v2 .contact-form textarea:focus {
	outline: none;
	border-color: var(--coral);
	background: #fff;
}
.theme-v2 .contact-form textarea { resize: vertical; }

/* Honeypot — visually hidden but present for bots. */
.theme-v2 .contact-hp {
	position: absolute;
	left: -9999px;
	width: 1px;
	height: 1px;
	overflow: hidden;
}

.theme-v2 .contact-notice { margin-top: 1rem; padding: 0.75rem 1rem; border-radius: 12px; font-weight: 600; }
.theme-v2 .contact-notice-ok { background: rgba(166,209,125,0.25); color: var(--meadow-deep); }
.theme-v2 .contact-notice-err { background: rgba(250,98,85,0.15); color: var(--coral-deep); }

.theme-v2 .contact-map { margin-top: 2.5rem; border-radius: 22px; overflow: hidden; border: 3px solid var(--ink); }
.theme-v2 .contact-map iframe { display: block; }

/* ---- About ---- */
.theme-v2 .about-page { padding: 2rem 0 4rem; }
.theme-v2 .about-page-intro { text-align: center; max-width: 60ch; margin: 0 auto 1.5rem; }
.theme-v2 .about-page-intro .about-lead { margin-inline: auto; }
.theme-v2 .about-page-body { margin-top: 2.5rem; }
.theme-v2 .about-page-cta { text-align: center; margin-top: 2.5rem; }

@media (max-width: 820px) {
	.theme-v2 .contact-grid { grid-template-columns: 1fr; }
}
```

- [ ] **Step 2: Enqueue it conditionally**

In `inc/enqueue.php`, inside `ameer_enqueue_assets()`, after the `if ( $is_product ) { ... }` style block (the one enqueuing `ameer-product`, around line 55), add:

```php
	if ( is_page_template( array( 'page-contact.php', 'page-about.php' ) ) ) {
		wp_enqueue_style( 'ameer-page-templates', ameer_asset( 'css/page-templates.css' ), array( 'ameer-sunny' ), ameer_ver( 'css/page-templates.css' ) );
	}
```

- [ ] **Step 3: Lint**

Run: `php -l inc/enqueue.php`
Expected: `No syntax errors detected in inc/enqueue.php`

- [ ] **Step 4: Verify styling loads**

Hard-reload the Contact page. Expected: two-column layout, framed white form card, styled inputs; the green/red notices are styled; the honeypot row is not visible. Reload the About page: centered intro, badge row, readable story column, centered "Shop Now". On a narrow viewport (<820px) the contact grid collapses to one column. Confirm `page-templates.css` appears in the page source `<head>`.

- [ ] **Step 5: Commit**

```bash
git add css/page-templates.css inc/enqueue.php
git commit -m "feat: style Contact/About templates and enqueue conditionally"
```

---

## Self-Review

**Spec coverage:**
- Contact form stored in WP + emailed → Task 1 (CPT, handler, `wp_mail`). ✓
- Custom assignable page templates → Tasks 3, 4 (`Template Name` headers). ✓
- Customizer fields (WhatsApp/address/hours/map) → Task 2; consumed in Task 3. ✓
- Honeypot spam protection → Task 1 (form field) + Task 1 handler + Task 5 CSS. ✓
- Read-only Messages admin list → Task 1 (`create_posts => do_not_allow`). ✓
- About intro reuses `ameer_about_*` + editor body → Task 4. ✓
- Conditional CSS via `is_page_template()` + fixed-header clearance via `.article-hero` → Task 5 / Tasks 3-4. ✓
- Not wiring nav/footer; not auto-creating pages → respected (manual page creation steps). ✓

**Placeholder scan:** No TBD/TODO; every code step shows complete code; commands have expected output. ✓

**Type/name consistency:** `ameer_contact_form()`, `ameer_handle_contact()`, `ameer_register_message_cpt()`, action `ameer_contact`, nonce action/field `ameer_contact`/`ameer_contact_nonce`, honeypot `ameer_website`, status arg `sent`, meta keys `_ameer_msg_email`/`_ameer_msg_phone`, theme mods `ameer_contact_whatsapp`/`ameer_contact_address`/`ameer_contact_hours`/`ameer_map_embed`, CSS handle `ameer-page-templates` — used consistently across tasks. ✓

**Note on testing:** This theme ships no PHP unit-test harness, so the standard TDD code-test cycle is replaced by `php -l` syntax checks plus explicit manual admin/browser verification in each task. This is the honest verification path for this codebase.
