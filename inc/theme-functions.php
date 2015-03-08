<?php
/**
 * Versed custom functions
 *
 *
 * @package Versed
 * @since Versed 1.0
 */

//=====================
//! Update Body Class
//=====================

function versed_body_classes($classes) {
        if ( '' != get_the_post_thumbnail() && (is_single() || is_page()) ) {
		    $classes[] = 'has-post-thumbnail';
		}
		
		// Featured image darken
		$darken = get_post_meta( get_the_ID(), '_zilla_quote_image_darken', true );
		if ($darken == 'on') {
			$classes[] = 'darken-feat-img';
		}
		
		// Blog Layout
		if (is_blog()) {
			$classes[] = zilla_get_mod_state('blog_layout') ? zilla_get_mod('blog_layout', false) : 'blog-list-standard';
		}
		
		// Add the nav position
		$classes[] = zilla_get_mod_state('nav_position') ? zilla_get_mod('nav_position', false) : 'nav-top';

        return $classes;
}
 // Apply filter
add_filter('body_class', 'versed_body_classes');


//=====================
//! Update Post Class
//=====================
function versed_post_classes( $classes ) {
	global $post;
	
	$classes[] = (get_post_meta($post->ID, '_zilla_featured_post', true) == 'on') ? 'featured-post' : '';
	
	if (has_excerpt()) {
		$classes[] = 'has-excerpt';
	}
	
	return $classes;
}
add_filter( 'post_class', 'versed_post_classes' );


//===========================================================
//! Set featured image transient
//===========================================================
if( ! function_exists( 'zilla_set_image_transient' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * Create image transient to avoid looping through multiple image queries every time a post loads.
 * Called any time a post is saved or updated right after existing transient is flushed.
 * Called by zilla_post_thumbnail when no transient is set.
 *
 * - Get the featured image ID
 * - Get the alt text (if no alt text is defined, set the alt text to the post title)
 * - Build an array with each of the available image sizes + the alt text
 * - Set a transient with the label "featured_image_[post_id] that expires in 12 months
 */
function zilla_set_image_transient($post_id) {
	$attachment_id = get_post_thumbnail_id($post_id);
	$alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

	//add a default alt tag
	if ( !$alt_text ) { $alt_text = esc_html( get_the_title($post_id) ); }
	
	$title = get_the_title($attachment_id);
	$caption = get_the_excerpt($attachment_id);

	$thumb_original = wp_get_attachment_image_src($attachment_id, 'full');
	$thumb_xxl 		= wp_get_attachment_image_src($attachment_id, 'xxl-thumb');   // 2560
	$thumb_xl  		= wp_get_attachment_image_src($attachment_id, 'xl-thumb');	  // 1920
	$thumb_l   		= wp_get_attachment_image_src($attachment_id, 'l-thumb');	  // 1260
	$thumb_m   		= wp_get_attachment_image_src($attachment_id, 'm-thumb');	  // 960
	$thumb_s  		= wp_get_attachment_image_src($attachment_id, 's-thumb');	  // 400


	$thumb_data = array(
		'thumb_original' => $thumb_original[0],
		'thumb_xxl'  	 => $thumb_xxl[0],
		'thumb_xl'   	 => $thumb_xl[0],
		'thumb_l'   	 => $thumb_l[0],
		'thumb_m'   	 => $thumb_m[0],
		'thumb_s'   	 => $thumb_s[0],
		'thumb_alt'   	 => $alt_text,
		'thumb_title'	 => $title,
		'thumb_caption'  => $caption
	);

	set_transient( 'featured_image_' . $post_id, $thumb_data, 52 * WEEK_IN_SECONDS );
}
endif;


//===========================================================
//! Set gallery transient
//===========================================================
if( ! function_exists( 'zilla_set_attachment_transient' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * Create image transient to avoid looping through multiple image queries every time a post loads.
 * Called any time a post is saved or updated right after existing transient is flushed.
 * Called by zilla_post_thumbnail when no transient is set.
 *
 * - Get the attachment image ID
 * - Get the alt text (if no alt text is defined, set the alt text to the post title)
 * - Build an array with each of the available image sizes + the alt text
 * - Set a transient with the label "featured_image_[post_id] that expires in 12 months
 */
function zilla_set_attachment_transient($postid) {
	$image_ids_raw = get_post_meta($postid, '_zilla_image_ids', true);

	if( $image_ids_raw != '' ) {
		// custom gallery created
		$image_ids = explode(',', $image_ids_raw);
		$orderby = 'post__in';
		$post_parent = null;
	} else {
		// pull all images attached to post
		$image_ids = '';
		$orderby = 'menu_order';
		$post_parent = $postid;
	}

	// get the gallery images
	$args = array(
		'include' => $image_ids,
		'numberposts' => -1,
		'orderby' => $orderby,
		'order' => 'ASC',
		'post_type' => 'attachment',
		'post_parent' => $post_parent,
		'post_mime_type' => 'image',
		'post_status' => 'null'
	);
	$attachments = get_posts($args);
	$attachment_data = array();

	foreach( $attachments as $attachment ) {
		// fetch each attachment's meta data
		$alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
		// default alt tag
		if ( !$alt_text ) { $alt_text = esc_html( get_the_title($postid) ); }
		
		$title = $attachment->post_title;
		$caption = $attachment->post_excerpt;

		$thumb_original = wp_get_attachment_image_src($attachment->ID, 'full');
		$thumb_xxl 		= wp_get_attachment_image_src($attachment->ID, 'xxl-thumb');   // 2560
		$thumb_xl  		= wp_get_attachment_image_src($attachment->ID, 'xl-thumb');	  // 1920
		$thumb_l   		= wp_get_attachment_image_src($attachment->ID, 'l-thumb');	  // 1260
		$thumb_m   		= wp_get_attachment_image_src($attachment->ID, 'm-thumb');	  // 960
		$thumb_s  		= wp_get_attachment_image_src($attachment->ID, 's-thumb');	  // 400

		// create an array of the meta data
		$thumb_data = array(
			'thumb_original' => $thumb_original[0],
			'thumb_xxl'  	 => $thumb_xxl[0],
			'thumb_xl'   	 => $thumb_xl[0],
			'thumb_l'   	 => $thumb_l[0],
			'thumb_m'   	 => $thumb_m[0],
			'thumb_s'   	 => $thumb_s[0],
			'thumb_alt'   	 => $alt_text,
			'thumb_title'	 => $title,
			'thumb_caption'  => $caption
		);

		// push this attachment's meta data array to the array of all attachments on the page
		$attachment_data[] = $thumb_data;
	}

	set_transient( 'post_attachments_' . $postid, $attachment_data, 52 * WEEK_IN_SECONDS );
}
endif;


//===========================================================
//! Set featured posts check transient
//===========================================================
if( ! function_exists( 'zilla_set_featured_posts_transient' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * Save the posts that should be featured on the home page.
 *
 */
function zilla_set_featured_posts_transient() {
	$args = array(
		'posts_per_page'   		=> zilla_get_featured_number(),
		'orderby'          		=> 'post_date',
		'order'            		=> 'DESC',
		'meta_key'         		=> '_zilla_featured_post',
		'meta_value'       		=> 'on',
		'post_type'        		=> 'post',
		'ignore_sticky_posts'	=> true
	);
	
	$the_query = new WP_Query( $args );
	$featured_post = array();
	
	// If there's featured posts, mark as true and save the query to the transient.
	if ( $the_query->have_posts() ) {
		
		$featured_post['has_featured_post'] = true;
		$featured_post['post'] = $the_query;

	// If there's no featured posts, mark as false, and save the query to the transient.
	} else {
		
		$featured_post['has_featured_post'] = false;
		
		// Remove the featured post condition from the query.
		unset( $args['meta_value'], $args['meta_key']);
		
		$the_query = new WP_Query($args);
		
		$featured_post['post'] = $the_query;
	}

	set_transient( 'zilla_featured_posts_query', $featured_post, 52 * WEEK_IN_SECONDS );
}
endif;


//===========================================================
//! Reset featured image transient when the post is updated
//===========================================================
function zilla_reset_attachment_transient( $post_id ) {
	delete_transient( 'post_attachments_' . $post_id );
	$attachments = get_children( array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' => 'image') );

	if ( $attachments ) {

	 zilla_set_attachment_transient($post_id);

	}
}
add_action('save_post', 'zilla_reset_attachment_transient');
add_action('wp_ajax_zilla_save_images', 'zilla_reset_attachment_transient');

//===========================================================
//! Reset featured image transient when the post is updated
//===========================================================
function zilla_reset_thumb_data_transient( $post_id ) {
	delete_transient( 'featured_image_' . $post_id );
	if ( has_post_thumbnail($post_id) ) {
		zilla_set_image_transient($post_id);
	}
}
add_action('save_post', 'zilla_reset_thumb_data_transient');


//===========================================================
//! Reset featured posts transient when the post is updated
//===========================================================
function zilla_reset_featured_posts_transient() {
	//delete the existing transient on post save, so that it is set when the page loads.
	delete_transient( 'zilla_featured_posts_query' );
}
add_action('save_post', 'zilla_reset_featured_posts_transient');
add_action('customize_save_after', 'zilla_reset_featured_posts_transient');


//===========================================================
//! Check if there are any featured posts
//===========================================================
if( ! function_exists( 'zilla_has_featured_posts' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * @access public
 * @return mixed
 */
function zilla_has_featured_posts($return = 'boolean') {
	//Check if there is a featured posts transient. If not, set one.
	if ( false === ( $featured_post = get_transient( 'zilla_featured_posts_query' ) ) ) {
		zilla_set_featured_posts_transient();
		$featured_post = get_transient( 'zilla_featured_posts_query' );
	}
	
	if ($return === 'boolean') {
		return $featured_post['has_featured_post'];
	} elseif ($return === 'post_query') {
		return $featured_post['post'];
	}
}
endif;


//========================================
//! Get the featured image meta data
//========================================
if( ! function_exists( 'zilla_get_post_thumbnail_data' ) ) :
/**
 * zilla_get_post_thumbnail_data function.
 *
 * @package versed
 * @since versed 1.0
 *
 * @access public
 * @param mixed $id
 * @return string[]
 */
function zilla_get_post_thumbnail_data($id) {
	//get the thumbnail and add its meta to array
	$img = get_post($id);
	$img_meta = array(
		'title' => $img->post_title,
		'caption' => $img->post_excerpt,
		'description' => $img->post_content
	);
	// trim whitespace
    $img_meta = array_map( 'trim', $img_meta );
    // remove empty elements from array
    $img_meta = array_filter( $img_meta );
    // escape the elements
    $img_meta = array_map( 'esc_html', $img_meta );

    return $img_meta;
}
endif;


//========================================
//! Get theme mod state
//========================================
if( ! function_exists( 'zilla_get_mod_state' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * @access public
 * @return bool
 */
function zilla_get_mod_state($setting) {
	$theme_options = get_theme_mod('zilla_theme_options');
	if ( isset($theme_options[$setting]) && $theme_options[$setting] ) {
		return true;
	} else {
		return false;
	}
}
endif;


//========================================
//! Print theme mod
//========================================
if( ! function_exists( 'zilla_get_mod' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * @access public
 */
function zilla_get_mod($setting, $echo = true) {
	$theme_options = get_theme_mod('zilla_theme_options');
	
	if (zilla_get_mod_state($setting)) {
		if ($echo === true) {
			echo $theme_options[$setting];
		} else {
			return $theme_options[$setting];
		}
	} else {
		return false;
	}
	
}
endif;


//========================================
//! Get number of featured posts to return for homepage
//========================================
if( ! function_exists( 'zilla_get_featured_number' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * @access public
 * @return int
 */
function zilla_get_featured_number() {
	$layout = zilla_get_mod('homepage_layout', false);
	switch ($layout) {
		case 'one':
			return 1;
			break;
		case 'two':
			return 2;
			break;
		case 'three_equal':
			return 3;
			break;
		case 'three_wide_left':
			return 3;
			break;
		case 'three_wide_right':
			return 3;
			break;
		case 'four':
			return 4;
			break;
		default:
			return 1;
	}
}
endif;


//========================================
//! Get number of featured posts actually returned for homepage
//========================================
if( ! function_exists( 'zilla_get_actual_featured_number' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * @access public
 * @return int
 */
function zilla_get_actual_featured_number() {
	$featured = zilla_has_featured_posts( 'post_query' );
	return $featured->post_count;
}
endif;


//========================================
//! Get the wordcount of a post
//========================================
if( ! function_exists( 'zilla_word_count' ) ) :
/**
 *
 * @package versed
 * @since versed 1.0
 *
 * @access public
 * @return int
 */
function zilla_word_count($target_post = null) {
    if ($target_post) { 
	    $post = $target_post; 
	} else {
	    global $post;
	}
    $content = get_post_field( 'post_content', $post->ID );
    $word_count = str_word_count( strip_tags( $content ) );
    return $word_count;
}
endif;


if( ! function_exists( 'is_blog' ) ) :
function is_blog () {
	global  $post;
	$posttype = get_post_type($post);
	return ( ((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_tag())) && ( $posttype == 'post')  ) ? true : false ;
}
endif;