<?php
/**
 * Theme Customizer: homepage singletons (hero/about/stats), contact details,
 * social links.
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'customize_register', 'ameer_customize_register' );
function ameer_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'ameer_homepage',
		array(
			'title'    => __( 'Ameer: Homepage', 'ameer' ),
			'priority' => 30,
		)
	);

	/* ---- Hero ---- */
	$wp_customize->add_section( 'ameer_hero', array( 'title' => __( 'Hero', 'ameer' ), 'panel' => 'ameer_homepage' ) );
	ameer_cz_text( $wp_customize, 'ameer_hero_eyebrow', __( 'Eyebrow', 'ameer' ), 'ameer_hero', 'Kid-loved · Mom-approved' );
	ameer_cz_html( $wp_customize, 'ameer_hero_title', __( 'Title (HTML: span, br allowed)', 'ameer' ), 'ameer_hero', '<span>Tiny</span> <span>tummies,</span><br/><span>big</span> <span>smiles.</span>', 'textarea' );
	ameer_cz_text( $wp_customize, 'ameer_hero_subtitle', __( 'Subtitle', 'ameer' ), 'ameer_hero', '', 'textarea' );
	ameer_cz_text( $wp_customize, 'ameer_hero_cta1_label', __( 'Primary button label', 'ameer' ), 'ameer_hero', 'Shop Now' );
	ameer_cz_url( $wp_customize, 'ameer_hero_cta1_url', __( 'Primary button URL (blank = Shop)', 'ameer' ), 'ameer_hero' );
	ameer_cz_text( $wp_customize, 'ameer_hero_cta2_label', __( 'Secondary button label', 'ameer' ), 'ameer_hero', 'Learn More' );
	ameer_cz_url( $wp_customize, 'ameer_hero_cta2_url', __( 'Secondary button URL', 'ameer' ), 'ameer_hero' );
	$wp_customize->add_setting( 'ameer_hero_image', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'ameer_hero_image',
			array(
				'label'     => __( 'Hero product image', 'ameer' ),
				'section'   => 'ameer_hero',
				'mime_type' => 'image',
			)
		)
	);

	/* ---- Stats (3) ---- */
	$wp_customize->add_section( 'ameer_stats', array( 'title' => __( 'Hero Stats', 'ameer' ), 'panel' => 'ameer_homepage' ) );
	$stat_defaults = array(
		1 => array( '30000', 'k+', '', '', 'happy mommies' ),
		2 => array( '3', '', '', '', 'kid-first formulas' ),
		3 => array( '49', '', '★ ', '10', 'review average' ),
	);
	foreach ( $stat_defaults as $n => $d ) {
		ameer_cz_text( $wp_customize, "ameer_stat{$n}_value", sprintf( __( 'Stat %d — number', 'ameer' ), $n ), 'ameer_stats', $d[0] );
		ameer_cz_text( $wp_customize, "ameer_stat{$n}_suffix", sprintf( __( 'Stat %d — suffix', 'ameer' ), $n ), 'ameer_stats', $d[1] );
		ameer_cz_text( $wp_customize, "ameer_stat{$n}_prefix", sprintf( __( 'Stat %d — prefix', 'ameer' ), $n ), 'ameer_stats', $d[2] );
		ameer_cz_text( $wp_customize, "ameer_stat{$n}_divide", sprintf( __( 'Stat %d — divide by (e.g. 10 → 4.9)', 'ameer' ), $n ), 'ameer_stats', $d[3] );
		ameer_cz_text( $wp_customize, "ameer_stat{$n}_label", sprintf( __( 'Stat %d — label', 'ameer' ), $n ), 'ameer_stats', $d[4] );
	}

	/* ---- About ---- */
	$wp_customize->add_section( 'ameer_about', array( 'title' => __( 'About', 'ameer' ), 'panel' => 'ameer_homepage' ) );
	ameer_cz_text( $wp_customize, 'ameer_about_eyebrow', __( 'Eyebrow', 'ameer' ), 'ameer_about', 'Our Promise' );
	ameer_cz_html( $wp_customize, 'ameer_about_title', __( 'Title (span allowed)', 'ameer' ), 'ameer_about', 'Kid-first, <span>mom-trusted</span>' );
	ameer_cz_text( $wp_customize, 'ameer_about_lead', __( 'Lead paragraph', 'ameer' ), 'ameer_about', '', 'textarea' );
	ameer_cz_text( $wp_customize, 'ameer_about_story_label', __( 'Story link label', 'ameer' ), 'ameer_about', 'Read our full story →' );
	ameer_cz_url( $wp_customize, 'ameer_about_story_url', __( 'Story link URL', 'ameer' ), 'ameer_about' );

	/* ---- Section headings ---- */
	$wp_customize->add_section( 'ameer_sections', array( 'title' => __( 'Section Headings', 'ameer' ), 'panel' => 'ameer_homepage' ) );
	ameer_cz_text( $wp_customize, 'ameer_products_eyebrow', __( 'Products — eyebrow', 'ameer' ), 'ameer_sections', 'Our little heroes' );
	ameer_cz_html( $wp_customize, 'ameer_products_title', __( 'Products — title', 'ameer' ), 'ameer_sections', 'Three formulas, <span>endless</span> goodness' );
	ameer_cz_text( $wp_customize, 'ameer_tips_eyebrow', __( 'Tips — eyebrow', 'ameer' ), 'ameer_sections', 'Tips, recipes &amp; more' );
	ameer_cz_html( $wp_customize, 'ameer_tips_title', __( 'Tips — title', 'ameer' ), 'ameer_sections', 'From our <span>kitchen</span> to yours' );
	ameer_cz_html( $wp_customize, 'ameer_feedback_title', __( 'Feedback — title', 'ameer' ), 'ameer_sections', 'What <span style="color:#3A3530">Moms</span> Say' );
	ameer_cz_text( $wp_customize, 'ameer_social_eyebrow', __( 'Social — eyebrow', 'ameer' ), 'ameer_sections', '@ameernutrition' );
	ameer_cz_html( $wp_customize, 'ameer_social_title', __( 'Social — title', 'ameer' ), 'ameer_sections', 'Follow our <span>little</span> moments' );
	ameer_cz_text( $wp_customize, 'ameer_social_sub', __( 'Social — subtitle', 'ameer' ), 'ameer_sections', 'Tips, parenting wins, and giveaways on TikTok &amp; Instagram.', 'textarea' );
	ameer_cz_html( $wp_customize, 'ameer_events_title', __( 'Events — title', 'ameer' ), 'ameer_sections', 'Find <span>Us</span> At' );

	/* ---- Contact / footer ---- */
	$wp_customize->add_section( 'ameer_contact', array( 'title' => __( 'Contact & Footer', 'ameer' ), 'panel' => 'ameer_homepage' ) );
	ameer_cz_text( $wp_customize, 'ameer_footer_tagline', __( 'Footer tagline', 'ameer' ), 'ameer_contact', 'Kid-first nutrition, made with love.' );
	ameer_cz_text( $wp_customize, 'ameer_contact_phone', __( 'Phone', 'ameer' ), 'ameer_contact', '014-3377432' );
	ameer_cz_text( $wp_customize, 'ameer_contact_email', __( 'Email', 'ameer' ), 'ameer_contact', 'nutritionameer@gmail.com' );
	ameer_cz_text( $wp_customize, 'ameer_ssm', __( 'SSM registration', 'ameer' ), 'ameer_contact', 'SSM 201701005157' );
	ameer_cz_text( $wp_customize, 'ameer_contact_whatsapp', __( 'WhatsApp number (digits, e.g. 60143377432)', 'ameer' ), 'ameer_contact', '' );
	ameer_cz_text( $wp_customize, 'ameer_contact_address', __( 'Address', 'ameer' ), 'ameer_contact', '', 'textarea' );
	ameer_cz_text( $wp_customize, 'ameer_contact_hours', __( 'Business hours', 'ameer' ), 'ameer_contact', '', 'textarea' );
	ameer_cz_url( $wp_customize, 'ameer_map_embed', __( 'Google Maps embed URL (iframe src)', 'ameer' ), 'ameer_contact' );
	ameer_cz_url( $wp_customize, 'ameer_social_tiktok', __( 'TikTok URL', 'ameer' ), 'ameer_contact' );
	ameer_cz_url( $wp_customize, 'ameer_social_instagram', __( 'Instagram URL', 'ameer' ), 'ameer_contact' );
	ameer_cz_url( $wp_customize, 'ameer_social_facebook', __( 'Facebook URL', 'ameer' ), 'ameer_contact' );
}

/* ----------------------------------------------------- Small registration helpers */

function ameer_cz_text( $wp, $id, $label, $section, $default = '', $type = 'text' ) {
	$wp->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => $type ) );
}

function ameer_cz_html( $wp, $id, $label, $section, $default = '', $type = 'text' ) {
	$wp->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'ameer_sanitize_inline_html' ) );
	$wp->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => $type ) );
}

function ameer_cz_url( $wp, $id, $label, $section, $default = '' ) {
	$wp->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'esc_url_raw' ) );
	$wp->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'url' ) );
}

/**
 * Allow only a tiny set of inline tags in heading fields.
 *
 * @param string $value Raw value.
 * @return string
 */
function ameer_sanitize_inline_html( $value ) {
	return wp_kses(
		$value,
		array(
			'span' => array( 'style' => array(), 'class' => array() ),
			'br'   => array(),
			'em'   => array(),
			'strong' => array(),
		)
	);
}
