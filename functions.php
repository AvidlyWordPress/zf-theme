<?php
/**
 * ZF Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ZF_Theme
 */

/**
 * BASE SETUP
 *
 * Set up textdomain, theme-supports, nav menus
 */
require get_template_directory() . '/functions/theme-setup.php';

/**
 * SCRIPTS AND STYLES
 *
 * Enqueue scripts and styles needed by this theme
 */
require get_template_directory() . '/functions/scripts-and-styles.php';

/**
 * FOUNDATION COMPATIBILITY
 */
require get_template_directory() . '/functions/foundation.php';

/**
 * WIDGETS
 *
 * Define widget areas and other widget-related code
 */
require get_template_directory() . '/functions/widgets.php';

/**
 * CUSTOM FIELDS
 *
 * Define custom meta boxes and fields (CMB2)
 */
require get_template_directory() . '/functions/custom-fields.php';

/**
 * CUSTOM HEADER
 *
 * Implement the Custom Header feature. Remove if your theme doesn't need one.
 */
require get_template_directory() . '/functions/custom-header.php';

/**
 * TEMPLATE TAGS
 *
 * Custom template tags for this theme. Any helper functions that output
 * HTML in templates should be in here.
 */
require get_template_directory() . '/functions/template-tags.php';

/**
 * EXTRA FILTERS AND TWEAKS
 *
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/functions/extras.php';

/**
 * CUSTOMIZER
 *
 * Customizer additions.
 */
require get_template_directory() . '/functions/customizer.php';

/**
 * JETPACK
 *
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/functions/jetpack.php';
