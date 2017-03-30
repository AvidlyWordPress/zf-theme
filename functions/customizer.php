<?php
/**
 * ZF Theme Customizer
 *
 * @package ZF_Theme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function zf_theme_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'zf_theme_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function zf_theme_customize_preview_js() {
	wp_enqueue_script( 'zf_theme_customizer', get_template_directory_uri() . '/dist/assets/wp-js/customizer.js', array( 'customize-preview' ), '20170330', true );
}
add_action( 'customize_preview_init', 'zf_theme_customize_preview_js' );
