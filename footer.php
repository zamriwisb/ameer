<?php
/**
 * Site footer.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ameer_phone = get_theme_mod( 'ameer_contact_phone', '014-3377432' );
$ameer_email = get_theme_mod( 'ameer_contact_email', 'nutritionameer@gmail.com' );
$ameer_ssm   = get_theme_mod( 'ameer_ssm', 'SSM 201701005157' );
$ameer_tagln = get_theme_mod( 'ameer_footer_tagline', __( 'Kid-first nutrition, made with love.', 'ameer' ) );
$ameer_tiktok = get_theme_mod( 'ameer_social_tiktok', '#' );
$ameer_ig     = get_theme_mod( 'ameer_social_instagram', '#' );
$ameer_fb     = get_theme_mod( 'ameer_social_facebook', '#' );
?>

	<footer class="site-footer" id="contact">
		<?php
		// The footer's own yellow hill is skipped on pages that already end with a
		// yellow divider of their own (front page → Events; Contact/About templates →
		// their final section), so we never stack two hills.
		$ameer_skip_footer_divider = is_front_page() || ( function_exists( 'is_page_template' ) && is_page_template( array( 'page-contact.php', 'page-about.php' ) ) );
		?>
		<?php if ( ! $ameer_skip_footer_divider ) : ?>
			<!-- Footer top divider — yellow hill rising up, matching the homepage section shapes.
			     Skipped on the front page, where the Events section already supplies this wave. -->
			<div class="divider divider-top" aria-hidden="true">
				<svg class="divider-shape" viewBox="0 0 1440 90" preserveAspectRatio="none">
					<path d="M0,90 L1440,90 L1440,45 C1200,18 960,68 720,38 C480,12 240,55 0,45 Z" fill="#FDCB46"/>
				</svg>
				<svg class="divider-deco divider-cloud-mini" viewBox="0 0 80 40"><use href="#i-cloud-mini"/></svg>
			</div>
		<?php endif; ?>
		<div class="container footer-inner">
			<div class="footer-brand">
				<img src="<?php echo esc_url( AMEER_ASSETS . '/ameer-logo.webp' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
				<p><?php echo esc_html( $ameer_tagln ); ?></p>
			</div>

			<div class="footer-col">
				<h4><?php esc_html_e( 'Contact', 'ameer' ); ?></h4>
				<?php if ( $ameer_phone ) : ?>
					<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $ameer_phone ) ); ?>"><?php echo esc_html( $ameer_phone ); ?></a></p>
				<?php endif; ?>
				<?php if ( $ameer_email ) : ?>
					<p><a href="mailto:<?php echo esc_attr( $ameer_email ); ?>"><?php echo esc_html( $ameer_email ); ?></a></p>
				<?php endif; ?>
			</div>

			<div class="footer-col">
				<h4><?php esc_html_e( 'Quick Links', 'ameer' ); ?></h4>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
						)
					);
				} else {
					?>
					<a href="<?php echo esc_url( ameer_shop_url() ); ?>"><?php esc_html_e( 'Shop', 'ameer' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/#about' ) ); ?>"><?php esc_html_e( 'About', 'ameer' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/#recipes' ) ); ?>"><?php esc_html_e( 'Recipes', 'ameer' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'ameer' ); ?></a>
					<?php
				}
				?>
			</div>

			<div class="footer-col">
				<h4><?php esc_html_e( 'Follow Us', 'ameer' ); ?></h4>
				<div class="social-icons">
					<a href="<?php echo esc_url( $ameer_tiktok ); ?>" class="soc" aria-label="TikTok">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5.8 20.1a6.34 6.34 0 0 0 10.86-4.43V9a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.84-.43z"/></svg>
					</a>
					<a href="<?php echo esc_url( $ameer_ig ); ?>" class="soc" aria-label="Instagram">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
					</a>
					<a href="<?php echo esc_url( $ameer_fb ); ?>" class="soc" aria-label="Facebook">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46H15.2c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>
					</a>
				</div>
			</div>

			<div class="footer-col footer-newsletter">
				<h4><?php esc_html_e( 'Get 10% Off', 'ameer' ); ?></h4>
				<?php ameer_newsletter_form( 'mini-newsletter' ); ?>
			</div>
		</div>
		<div class="footer-bottom container">
			<small><?php printf( esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'ameer' ), esc_html( wp_date( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) ); ?></small>
			<?php if ( $ameer_ssm ) : ?>
				<small class="ssm"><?php printf( esc_html__( 'AMEER AL MANNA SDN BHD (%s)', 'ameer' ), esc_html( $ameer_ssm ) ); ?></small>
			<?php endif; ?>
			<small class="managed-by">
				<?php
				printf(
					/* translators: %s: Lamanweb link. */
					esc_html__( 'Managed Service by %s', 'ameer' ),
					'<a href="https://lamanweb.my" target="_blank" rel="noopener">Lamanweb</a>'
				);
				?>
			</small>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
