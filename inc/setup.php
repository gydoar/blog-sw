<?php
/**
 * Versed setup functions
 *
 * Sets up the theme / theme support features
 *
 * @package Versed
 * @since Versed 1.0
 */

/**
 * Set Max Content Width
 *
 * @since Versed 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 960;


if ( !function_exists( 'zilla_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers various features supported
 * by Versed
 *
 * @uses load_theme_textdoman() For translation support
 * @uses register_nav_menu() To add support for navigation menu
 * @uses add_theme_support() To add support for post-thumbnails and post-formats
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size
 * @uses add_image_size() To add additional image sizes
 *
 * @since Versed 1.0
 *
 * @return void
 */
function zilla_theme_setup() {

	/* Load translation domain --------------------------------------------------*/
	load_theme_textdomain( 'zilla', get_template_directory() . '/languages' );

	/* Register WP 3.0+ Menus ---------------------------------------------------*/
	register_nav_menu( 'primary-menu', __('Primary Menu', 'zilla') );
	register_nav_menu( 'social-menu', __('Social Network Menu', 'zilla') );

	/* Configure WP 2.9+ Thumbnails ---------------------------------------------*/
	add_theme_support( 'post-thumbnails' );
	// set_post_thumbnail_size( 480, 9999 ); // Normal post thumbnails
	add_image_size( 'xxl-thumb', 2650, 9999 );
	add_image_size( 'xl-thumb', 1920, 9999 );
	add_image_size( 'l-thumb', 1260, 9999 );
	add_image_size( 'm-thumb', 960, 9999 );
	add_image_size( 'article-full', 787, 9999 );
	add_image_size( 's-thumb', 400, 9999 );

	/* Add support for post formats ---------------------------------------------*/
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'gallery', 'image', 'link', 'quote', 'video' ) );

	/* Adds RSS feed links to <head> for posts and comments -----------------------*/
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'html5', array(
		'search-form', 'comment-form'
	) );
	
	// Add support for Jetpack infinite scroll
	// Add support for infinite scroll
    add_theme_support( 'infinite-scroll', array(
        'type' => 'click',
        'container' => 'primary',
        'footer' => false,
        'wrapper' => false,
   		'footer_widgets' => 'sidebar-footer'
	) );
	
	// Add Editor Font Styles
    $roboto = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Roboto:900,500,400,300' );
    $merriweather = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Merriweather:300italic,300,700,900' );
    add_editor_style( $roboto );
    add_editor_style( $merriweather );
  
}
endif;
add_action( 'after_setup_theme', 'zilla_theme_setup' );

if ( ! function_exists( 'zilla_add_editor_styles' ) ) :
/**
 * Add custom styles to editor
 */
function zilla_add_editor_styles() {
    add_editor_style( '/inc/admin/css/editor-style.css' );
}
add_action( 'admin_init', 'zilla_add_editor_styles' );
endif;


if ( ! function_exists( 'zilla_style_select' ) ) :
/**
 * Add style select to TinyMCE
 */
function zilla_style_select( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
endif;
add_filter( 'mce_buttons_2', 'zilla_style_select' );


if ( ! function_exists( 'zilla_styles_dropdown' ) ) :
/**
 * Add p.lead to styles drop down
 * @param  array $settings
 * @return array
 */
function zilla_styles_dropdown( $settings ) {

	$new_styles = array(
		array(
			'title'	=> __( 'Custom Styles', 'zilla' ),
			'items'	=> array(
				array(
					'title'		=> __('Lead Paragraph','zilla'),
					'selector'	=> 'p',
					'classes'	=> 'lead'
				),
				array(
					'title'		=> __('Pull Quote Left','zilla'),
					'selector'	=> 'blockquote',
					'classes'	=> 'pull-quote-left'
				),
				array(
					'title'		=> __('Pull Quote Right','zilla'),
					'selector'	=> 'blockquote',
					'classes'	=> 'pull-quote-right'
				)
			)
		)
	);

	// Merge old & new styles
	$settings['style_formats_merge'] = true;

	// Add new styles
	$settings['style_formats'] = json_encode( $new_styles );

	// Return New Settings
	return $settings;
}
endif;
add_filter( 'tiny_mce_before_init', 'zilla_styles_dropdown' );


//========================================
//! Add image sizes to the media library
//========================================
function zilla_media_image_sizes($sizes) {
	$addsizes = array(
		'm-thumb' => __( 'Article Wide', 'zilla'),
		'article-full' => __( 'Article Full', 'zilla'),
	);
	$newsizes = array_merge($sizes, $addsizes);
	return $newsizes;
}
add_filter('image_size_names_choose', 'zilla_media_image_sizes');


if ( !function_exists( 'zilla_sidebars_init' ) ) :
/**
 * Register the sidebars for the theme
 *
 * @since Versed 1.0
 *
 * @uses register_sidebar() To add sidebar areas
 * @return void
 */
function zilla_sidebars_init() {
	register_sidebar(array(
		'name' => __('Main Sidebar', 'versed'),
		'id' => 'sidebar',
		'description' => __('Off-canvas panel widget area.', 'zilla'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
	register_sidebars(3, array(
		'name' => __('Footer Column %d', 'versed'),
		'id' => 'sidebar-footer',
		'description' => __('Footer widget area.', 'zilla'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
}
endif;
add_action( 'widgets_init', 'zilla_sidebars_init' );


if ( !function_exists( 'zilla_excerpt_length' ) ) :
/**
 * Sets a custom excerpt length for portfolios
 *
 * @since Versed 1.0
 *
 * @param int $length Excerpt length
 * @return int New excerpt length
 */
function zilla_excerpt_length($length) {
	if( get_post_type() == 'portfolio' ):
		return 15;
	else:
		return 75;
	endif;
}
endif;
add_filter('excerpt_length', 'zilla_excerpt_length');


if ( !function_exists( 'zilla_excerpt_more' ) ) :
/**
 * Replaces [...] in excerpt string
 *
 * @since Versed 1.0
 *
 * @param string $more Existing excerpt
 * @return string
 */
function zilla_excerpt_more( $more ) {
	return '&#8230;';
}
endif;
add_filter('excerpt_more', 'zilla_excerpt_more');


if ( !function_exists( 'zilla_read_more_link' ) ) :
/**
 * Replaces [...] in readmore string
 *
 * @since Versed 1.0
 *
 * @return string
 */
function zilla_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">...</a>';
}
add_filter( 'the_content_more_link', 'zilla_read_more_link' );
endif;

if ( !function_exists( 'zilla_wp_title' ) ) :
/**
 * Creates formatted and more specific title element for output based
 * on current view
 *
 * @since Versed 1.0
 *
 * @param string $title Default title text
 * @param string $sep Optional separator
 * @return string Formatted title
 */
function zilla_wp_title( $title, $sep ) {
	if( !zilla_is_third_party_seo() ){
		global $paged, $page;

		if( is_feed() )
			return $title;

		$title .= get_bloginfo( 'name' );

		$site_description = get_bloginfo( 'description', 'display' );
		if( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		if( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __('Page %s', 'zilla'), max( $paged, $page ) );
	}
	return $title;
}
endif;
add_filter('wp_title', 'zilla_wp_title', 10, 2);


if ( !function_exists( 'zilla_add_portfolio_to_rss' ) ) :
/**
 * Adds portfolios to RSS feed
 *
 * @since Versed 1.0
 *
 * @param obj $request
 * @return obj Updated request
 */
function zilla_add_portfolio_to_rss( $request ) {
	if (isset($request['feed']) && !isset($request['post_type']))
		$request['post_type'] = array('post', 'portfolio');

	return $request;
}
endif;
add_filter('request', 'zilla_add_portfolio_to_rss');