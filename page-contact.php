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
