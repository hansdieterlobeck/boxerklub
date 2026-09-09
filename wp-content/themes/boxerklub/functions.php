<?php
/**
 * Boxerklub theme functions.
 * Child theme of Hello Elementor, built for use with the Elementor page builder.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BOXERKLUB_VERSION', '1.0.0' );

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
