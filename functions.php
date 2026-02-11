<?php
/**
 * Mecon Theme - Child Theme von Twenty Twenty-Five
 *
 * @package Mecon_Theme
 */

// Parent-Theme Styles laden
add_action( 'wp_enqueue_scripts', 'mecon_theme_enqueue_styles' );

function mecon_theme_enqueue_styles() {
	wp_enqueue_style(
		'twentytwentyfive-style',
		get_template_directory_uri() . '/style.css'
	);

	wp_enqueue_style(
		'mecon-theme-style',
		get_stylesheet_uri(),
		array( 'twentytwentyfive-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
