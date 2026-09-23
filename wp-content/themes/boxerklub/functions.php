<?php
/**
 * Boxerklub theme functions.
 * Child theme of Hello Elementor, built for use with Elementor v4.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOXERKLUB_VERSION', '1.2.0' );

/**
 * Enqueue parent (Hello Elementor) and child stylesheets.
 */
function boxerklub_enqueue_styles() {
	wp_enqueue_style(
		'hello-elementor-style',
		get_template_directory_uri() . '/style.css',
		[],
		BOXERKLUB_VERSION
	);

	wp_enqueue_style(
		'boxerklub-style',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'hello-elementor-style' ],
		BOXERKLUB_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'boxerklub_enqueue_styles' );

/**
 * Theme support declarations expected by Elementor.
 */
function boxerklub_setup() {
	add_theme_support( 'elementor' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

	register_nav_menus(
		[
			'primary' => __( 'Primary Menu', 'boxerklub' ),
		]
	);
}
add_action( 'after_setup_theme', 'boxerklub_setup' );

/**
 * Show an admin notice if Elementor isn't active, since this theme is built around it.
 */
function boxerklub_require_elementor_notice() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		echo '<div class="notice notice-warning"><p>';
		esc_html_e( 'The Boxerklub theme is designed to be used with the Elementor plugin. Please install and activate Elementor.', 'boxerklub' );
		echo '</p></div>';
	}
}
add_action( 'admin_notices', 'boxerklub_require_elementor_notice' );

/**
 * Seed the active Elementor Kit with the Boxerklub brand colors and
 * typography as Global Colors / Global Fonts.
 *
 * Elementor v4 automatically surfaces these as Variables in the editor's
 * Site Settings > Variables panel, so the design tokens only need to be
 * defined once, here, instead of by hand in the editor on every install.
 * Existing custom colors/typography (e.g. edited by a site owner) are left
 * untouched.
 */
function boxerklub_seed_elementor_kit_variables() {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}

	$kit_id = get_option( 'elementor_active_kit' );

	if ( ! $kit_id ) {
		return;
	}

	$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : [];
	$dirty    = false;

	if ( empty( $settings['custom_colors'] ) ) {
		$settings['custom_colors'] = [
			[
				'_id'   => 'boxerklub-primary',
				'title' => 'Boxerklub Primary',
				'color' => '#D91E2B',
			],
			[
				'_id'   => 'boxerklub-dark',
				'title' => 'Boxerklub Dark',
				'color' => '#111111',
			],
			[
				'_id'   => 'boxerklub-light',
				'title' => 'Boxerklub Light',
				'color' => '#F5F5F5',
			],
			[
				'_id'   => 'boxerklub-accent',
				'title' => 'Boxerklub Accent',
				'color' => '#C9A227',
			],
		];
		$dirty = true;
	}

	if ( empty( $settings['custom_typography'] ) ) {
		$settings['custom_typography'] = [
			[
				'_id'                    => 'boxerklub-heading',
				'title'                  => 'Boxerklub Heading',
				'typography_typography'  => 'custom',
				'typography_font_family' => 'Oswald',
				'typography_font_weight' => '700',
			],
			[
				'_id'                    => 'boxerklub-body',
				'title'                  => 'Boxerklub Body',
				'typography_typography'  => 'custom',
				'typography_font_family' => 'Roboto',
				'typography_font_weight' => '400',
			],
		];
		$dirty = true;
	}

	if ( ! $dirty ) {
		return;
	}

	update_post_meta( $kit_id, '_elementor_page_settings', $settings );

	if ( isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}
add_action( 'after_switch_theme', 'boxerklub_seed_elementor_kit_variables' );
