<?php
/**
 * Versed functions
 *
 * Sets up the theme and provides helper functions.
 *
 * It's safer to override theme functions via a child theme than to 
 * edit directly here.
 *
 * @package Versed
 * @since Versed 1.0
 */

$zilla_function_includes = array(
	'inc/setup.php',				// Theme settings, supports, sidebars, etc.
	'inc/enqueue.php',				// Enqueue scripts & styles
	'inc/theme-functions.php',		// Custom functions for our theme
	'inc/template-tags.php',		// Output theme's repetitively used HTML
	'/framework/init.php',			// Include Zilla Framework
	'/inc/admin/init.php'			// Set up the custom admin features
);

foreach ( $zilla_function_includes as $file ) {
	if ( ! $filepath = locate_template($file) ) {
		trigger_error(sprintf(__('Required file is missing. Could not find %s for inclusion', 'zilla'), $file), E_USER_ERROR);
	}
	
	require_once $filepath;
}
unset($file, $filepath);