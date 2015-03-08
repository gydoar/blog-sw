<?php
/**
 * Versed enqueue scripts & styles
 *
 * @package Versed
 * @since Versed 1.0
 */
 
 
if ( !function_exists( 'zilla_enqueue_scripts' ) ) :
/**
 * Enqueues scripts and styles for front end
 *
 * @since Versed 1.0
 *
 * @return void
 */
function zilla_enqueue_scripts() {

	/* Register our scripts -----------------------------------------------------*/
	/* Google CDN version of jQuery with local fallback */
	if ( ! is_admin() ) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', array(), null, false);
		add_filter('script_loader_src', 'zilla_local_jquery', 10, 2);
	}	
	wp_register_script('modernizr', get_template_directory_uri() . '/assets/vendor/modernizr-2.8.3.min.js', '', '2.8.3', false);
	wp_register_script('validation', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js', 'jquery', '1.9', true);
	wp_register_script('fitVids', get_template_directory_uri() . '/assets/vendor/jquery.fitvids.min.js', 'jquery', '1.1', true);
	wp_register_script('jPlayer', get_template_directory_uri() . '/assets/vendor/jquery.jplayer.min.js', 'jquery', '2.7.1', true);
	wp_register_script('bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap.min.js', 'jquery', '3.2.0', true);
	wp_register_script('sharrre', get_template_directory_uri() . '/assets/vendor/jquery.sharrre.min.js', 'jquery', '1.3.5', true);
	wp_register_script('picturefill', get_template_directory_uri() . '/assets/vendor/picturefill.min.js', 'jquery', '2.1.0', true);
	wp_register_script('waypoints', get_template_directory_uri() . '/assets/vendor/waypoints.min.js', 'jquery', '2.0.5', true);
	wp_register_script('cycle2', get_template_directory_uri() . '/assets/vendor/jquery.cycle2.min.js', 'jquery', '2.1.6', true);
	wp_register_script('mouseDelay', get_template_directory_uri() . '/assets/vendor/mouseDelay.min.js', 'jquery', '0.1.0', true);
	/* If you want to edit the custom JS, the non-minified version is also included. Just remove '.min' from the file path below and edit away! */
	wp_register_script('zilla-custom', get_template_directory_uri() . '/assets/js/jquery.custom.js', array('jquery', 'picturefill', 'cycle2', 'bootstrap', 'sharrre', 'fitVids', 'jPlayer', 'waypoints', 'mouseDelay'), '', true);
	
	/* Enqueue our scripts ------------------------------------------------------*/
	wp_enqueue_script('modernizr');
	wp_enqueue_script('jquery');
	wp_enqueue_script('zilla-custom');
	
	/* loads the javascript required for threaded comments ----------------------*/
	if( is_singular() && comments_open() && get_option( 'thread_comments') ) 
		wp_enqueue_script( 'comment-reply' ); 

	if( is_page_template('template-contact.php') ) 
		wp_enqueue_script('validation');
		
	/* Register Fonts ----------------------*/	
	wp_register_style('roboto', 'http://fonts.googleapis.com/css?family=Roboto:900,500,400,300');
	wp_register_style('merriweather', 'http://fonts.googleapis.com/css?family=Merriweather:300italic,300,700,900');

	/* Load our stylesheet --- */
	$zilla_options = get_option('zilla_framework_options');
	wp_enqueue_style( $zilla_options['theme_name'], get_stylesheet_uri(), array('roboto', 'merriweather') );

	/* Conditionally load IE stylesheet --- */
    wp_enqueue_style( 'ie-style', get_template_directory_uri() . '/css/ie.css' );
	global $wp_styles;
	$wp_styles->add_data( 'ie-style', 'conditional', 'lte IE 9' );
	
	/* Add localization to use theme vars in our JS --- */
	wp_localize_script('zilla-custom', 'zillaVersed', array(
		'vendorFolder' => get_template_directory_uri() . '/assets/vendor',
		'sharesText' => 'Shares',
		'oneShareText' => 'Share',
		'noSharesText' => 'Share This Post'
	));
}
endif;
add_action('wp_enqueue_scripts', 'zilla_enqueue_scripts');


if ( !function_exists( 'zilla_enqueue_admin_scripts' ) ) :
/**
 * Enqueues scripts for back end
 *
 * @since Versed 1.0
 *
 * @return void
 */
function zilla_enqueue_admin_scripts() {
	wp_register_script( 'zilla-admin', get_template_directory_uri() . '/inc/admin/js/jquery.custom.admin.js', 'jquery' );
	wp_enqueue_script( 'zilla-admin' );
	
	//add styles
	wp_enqueue_style('zilla-admin-styles', get_template_directory_uri() . '/inc/admin/css/admin.css');
}
endif;
add_action( 'admin_enqueue_scripts', 'zilla_enqueue_admin_scripts' );


//==================================
//! Fallback version of jQuery
//==================================
// http://wordpress.stackexchange.com/a/12450
function zilla_local_jquery($src, $handle = null) {
  static $add_jquery_fallback = false;

  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/vendor/jquery.min.js"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }

  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }

  return $src;
}
add_action('wp_head', 'zilla_local_jquery');