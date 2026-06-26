<?php
/**
 * Template Name: Ameer: Contact
 *
 * Interactive contact page: icon contact cards + message form (cream), then an
 * FAQ accordion (sky-blue), chained into the footer with homepage-style shaped
 * dividers. Contact details come from the Customizer; FAQs are filterable.
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

// Default FAQs — overridable via the `ameer_contact_faqs` filter. Each item is
// array( 'q' => question, 'a' => answer ).
$faqs = apply_filters(
	'ameer_contact_faqs',
	array(
		array(
			'q' => __( 'Are your products halal certified?', 'ameer' ),
			'a' => __( 'Yes — every Ameer formula is halal-certified and made in an SSM-registered facility, so you can feel confident about what your little ones are getting.', 'ameer' ),
		),
		array(
			'q' => __( 'How long does shipping take?', 'ameer' ),
			'a' => __( 'Orders placed before 2pm are dispatched the same working day. Peninsular Malaysia usually arrives in 1–3 days; East Malaysia in 3–6 days.', 'ameer' ),
		),
		array(
			'q' => __( 'What age are the formulas suitable for?', 'ameer' ),
			'a' => __( 'Each product lists its recommended age range on the product page. Most are designed for toddlers and growing kids — reach out if you would like a recommendation.', 'ameer' ),
		),
		array(
			'q' => __( 'Can I change or cancel my order?', 'ameer' ),
			'a' => __( 'If your order has not shipped yet, message us with your order number and we will sort it out. Once it is on the way we can help arrange a return.', 'ameer' ),
		),
	)
);

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

	<section class="contact-section reveal" id="contact-form" aria-labelledby="contactHeading">
		<svg class="contact-deco contact-deco-cloud" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>
		<div class="container">
			<span class="eyebrow" data-anim="reveal-up"><?php esc_html_e( 'Say hello', 'ameer' ); ?></span>
			<h2 id="contactHeading" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'We&rsquo;d <span>love</span> to hear from you', 'ameer' ), array( 'span' => array() ) ); ?></h2>

			<?php if ( get_the_content() ) : ?>
				<div class="contact-intro" data-anim="reveal-up"><?php the_content(); ?></div>
			<?php endif; ?>

			<div class="contact-grid">
				<div class="contact-cards" data-anim="stagger-up">
					<?php if ( $phone ) : ?>
						<a class="contact-card" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
							<span class="contact-card-ico" style="background:#FA6255">
								<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
							</span>
							<span class="contact-card-body">
								<span class="contact-card-label"><?php esc_html_e( 'Call us', 'ameer' ); ?></span>
								<span class="contact-card-value"><?php echo esc_html( $phone ); ?></span>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( $whatsapp ) : ?>
						<a class="contact-card" href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $whatsapp ) ); ?>" target="_blank" rel="noopener">
							<span class="contact-card-ico" style="background:#25D366">
								<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.11.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.24-8.23 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.82c0 4.54-3.69 8.23-8.23 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.51.11-.11.25-.29.37-.43.13-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.42-.14-.01-.31-.01-.48-.01-.17 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>
							</span>
							<span class="contact-card-body">
								<span class="contact-card-label"><?php esc_html_e( 'WhatsApp', 'ameer' ); ?></span>
								<span class="contact-card-value"><?php esc_html_e( 'Chat with us →', 'ameer' ); ?></span>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<a class="contact-card" href="mailto:<?php echo esc_attr( $email ); ?>">
							<span class="contact-card-ico" style="background:#91BEF8">
								<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg>
							</span>
							<span class="contact-card-body">
								<span class="contact-card-label"><?php esc_html_e( 'Email', 'ameer' ); ?></span>
								<span class="contact-card-value"><?php echo esc_html( $email ); ?></span>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( $address ) : ?>
						<div class="contact-card contact-card-static">
							<span class="contact-card-ico" style="background:#FDCB46;color:#3A3530">
								<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
							</span>
							<span class="contact-card-body">
								<span class="contact-card-label"><?php esc_html_e( 'Visit us', 'ameer' ); ?></span>
								<span class="contact-card-value"><?php echo nl2br( esc_html( $address ) ); ?></span>
							</span>
						</div>
					<?php endif; ?>

					<?php if ( $hours ) : ?>
						<div class="contact-card contact-card-static">
							<span class="contact-card-ico" style="background:#3A3530">
								<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
							</span>
							<span class="contact-card-body">
								<span class="contact-card-label"><?php esc_html_e( 'Opening hours', 'ameer' ); ?></span>
								<span class="contact-card-value"><?php echo nl2br( esc_html( $hours ) ); ?></span>
							</span>
						</div>
					<?php endif; ?>

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
					<h3 class="contact-form-title"><?php esc_html_e( 'Send us a message', 'ameer' ); ?></h3>
					<?php ameer_contact_form(); ?>
				</div>
			</div>

			<?php if ( $map ) : ?>
				<div class="contact-map" data-anim="reveal-up">
					<iframe src="<?php echo esc_url( $map ); ?>" width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'Map', 'ameer' ); ?>"></iframe>
				</div>
			<?php endif; ?>
		</div>

		<!-- Contact bottom divider — sky-blue puffs rising up (matches FAQ color) -->
		<div class="divider divider-bottom" aria-hidden="true">
			<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
				<g fill="#B5D2F7">
					<rect x="0" y="78" width="1440" height="12"/>
					<ellipse cx="130" cy="74" rx="120" ry="32"/>
					<ellipse cx="360" cy="70" rx="140" ry="38"/>
					<ellipse cx="600" cy="74" rx="120" ry="32"/>
					<ellipse cx="840" cy="70" rx="140" ry="38"/>
					<ellipse cx="1080" cy="74" rx="120" ry="32"/>
					<ellipse cx="1320" cy="70" rx="140" ry="38"/>
				</g>
			</svg>
			<svg class="divider-deco divider-cloud-mini" viewBox="0 0 80 40"><use href="#i-cloud-mini"/></svg>
		</div>
	</section>

	<?php if ( ! empty( $faqs ) ) : ?>
		<section class="faq reveal" id="faq" aria-labelledby="faqTitle">
			<svg class="faq-deco faq-deco-cloud" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-cloud"/></svg>
			<div class="container">
				<span class="eyebrow" data-anim="reveal-up"><?php esc_html_e( 'Good to know', 'ameer' ); ?></span>
				<h2 id="faqTitle" class="section-title" data-anim="word-reveal"><?php echo wp_kses( __( 'Mom <span>questions</span>, answered', 'ameer' ), array( 'span' => array() ) ); ?></h2>
				<div class="faq-list" data-anim="stagger-up">
					<?php
					$first = true;
					foreach ( $faqs as $f ) :
						if ( empty( $f['q'] ) ) {
							continue;
						}
						?>
						<details class="faq-item"<?php echo $first ? ' open' : ''; ?>>
							<summary><?php echo esc_html( $f['q'] ); ?></summary>
							<div class="faq-body"><?php echo wpautop( wp_kses_post( isset( $f['a'] ) ? $f['a'] : '' ) ); ?></div>
						</details>
						<?php
						$first = false;
					endforeach;
					?>
				</div>
			</div>
			<!-- FAQ bottom divider — solid yellow hill rising up (matches yellow Footer) -->
			<svg class="divider-lorry" viewBox="0 0 160 80" aria-hidden="true"><use href="#i-lorry"/></svg>
			<div class="divider divider-bottom" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
					<path d="M0,90 L1440,90 L1440,45 C1200,18 960,68 720,38 C480,12 240,55 0,45 Z" fill="#FDCB46"/>
				</svg>
				<svg class="divider-deco divider-spark-1" viewBox="0 0 30 30" style="color:#FA6255"><use href="#i-sparkle"/></svg>
				<svg class="divider-deco divider-spark-2" viewBox="0 0 30 30" style="color:#FFF9E6"><use href="#i-sparkle"/></svg>
			</div>
		</section>
	<?php endif; ?>
</main>
	<?php
endwhile;

get_footer();
