<?php

/**
 * Create the Post meta boxes
 */

add_action('add_meta_boxes', 'zilla_metabox_posts');
function zilla_metabox_posts() {

	/* Create a gallery metabox -----------------------------------------------------*/
	$meta_box = array(
		'id' => 'zilla-metabox-post-gallery',
		'title' =>  __('Gallery Settings', 'zilla'),
		'description' => __('Set up your gallery.', 'zilla'),
		'page' => 'post',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
			   'name' => __('Gallery Transition', 'zilla'),
			   'desc' => __('Set the transition for the gallery images.', 'zilla'),
			   'id' => '_zilla_gallery_transition',
			   'type' => 'select',
			   'std' => '',
			   'options' => array(
			   	'fade' => __('Fade', 'zilla'),
			   	'fadeout' => __('Fadeout', 'zilla'),
			   	'scrollHorz' => __('Slide', 'zilla')
			   )
			),
			array(
				'name' => __('Gallery Timeout', 'zilla'),
				'desc' => __('Set the time between slides.', 'zilla'),
				'id' => '_zilla_gallery_timeout',
				'type' => 'select',
				'std' => '',
				'options' => array(
					'0' => __('0 Seconds (manual transition)', 'zilla'),
					'2000' => __('2 Seconds', 'zilla'),
					'4000' => __('4 Seconds', 'zilla'),
					'6000' => __('6 Seconds', 'zilla'),
					'8000' => __('8 Seconds', 'zilla'),
					'10000' => __('10 Seconds', 'zilla')
				)	
			),
			array(
				'name' =>  __('Upload Images', 'zilla'),
				'desc' => __('Click to upload images.', 'zilla'),
				'id' => '_zilla_gallery_upload',
				'type' => 'images',
				'std' => __('Upload Images', 'zilla')
			)
		)
	);
	zilla_add_meta_box( $meta_box );

	/* Create a quote metabox -----------------------------------------------------*/
	$meta_box = array(
		'id' => 'zilla-metabox-post-quote',
		'title' =>  __('Quote Settings', 'zilla'),
		'description' => __('Input your quote.', 'zilla'),
		'page' => 'post',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' =>  __('The Quote', 'zilla'),
				'desc' => __('Input your quote.', 'zilla'),
				'id' => '_zilla_quote_quote',
				'type' => 'textarea',
				'std' => ''
			),
			array(
				'name' =>  __('The Author', 'zilla'),
				'desc' => __('Input the quote\'s author.', 'zilla'),
				'id' => '_zilla_quote_author',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' =>  __('Darken Featured Image', 'zilla'),
				'desc' => __('Darken the featured image to make the text easier to read.', 'zilla'),
				'id' => '_zilla_quote_image_darken',
				'type' => 'checkbox',
				'std' => ''
			)
		)
	);
	zilla_add_meta_box( $meta_box );

	/* Create a link metabox ----------------------------------------------------*/
	$meta_box = array(
		'id' => 'zilla-metabox-post-link',
		'title' =>  __('Link Settings', 'zilla'),
		'description' => __('Input your link', 'zilla'),
		'page' => 'post',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' =>  __('The Link Title', 'zilla'),
				'desc' => __('Input your link title.', 'zilla'),
				'id' => '_zilla_link_title',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' =>  __('The Link', 'zilla'),
				'desc' => __('Input your link URL.', 'zilla'),
				'id' => '_zilla_link_url',
				'type' => 'text',
				'std' => ''
			)
		)
	);
	zilla_add_meta_box( $meta_box );

	/* Create a video metabox -------------------------------------------------------*/
	$meta_box = array(
		'id' => 'zilla-metabox-post-video',
		'title' => __('Video Settings', 'zilla'),
		'description' => __('These settings enable you to embed videos into your posts.', 'zilla'),
		'page' => 'post',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => __('M4V File URL', 'zilla'),
				'desc' => __('The URL to the .m4v video file', 'zilla'),
				'id' => '_zilla_video_m4v',
				'type' => 'file',
				'std' => ''
			),
			array(
				'name' => __('OGV File URL', 'zilla'),
				'desc' => __('The URL to the .ogv video file', 'zilla'),
				'id' => '_zilla_video_ogv',
				'type' => 'file',
				'std' => ''
			),
			array(
				'name' => __('Poster Image', 'zilla'),
				'desc' => __('The preview image.', 'zilla'),
				'id' => '_zilla_video_poster_url',
				'type' => 'file',
				'std' => ''
			),
			array(
				'name' => __('Embedded Code', 'zilla'),
				'desc' => __('If you are using something other than self hosted video such as Youtube or Vimeo, paste the embed code here. Width is best at 600px with any height.<br><br> This field will override the above.', 'zilla'),
				'id' => '_zilla_video_embed_code',
				'type' => 'textarea',
				'std' => ''
			)
		)
	);
	zilla_add_meta_box( $meta_box );

	/* Create an audio metabox ------------------------------------------------------*/
	$meta_box = array(
		'id' => 'zilla-metabox-post-audio',
		'title' =>  __('Audio Settings', 'zilla'),
		'description' => __('These settings enable you to embed audio into your posts.', 'zilla'),
		'page' => 'post',
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
					'name' => __('MP3 File URL', 'zilla'),
					'desc' => __('The URL to the .mp3 audio file', 'zilla'),
					'id' => '_zilla_audio_mp3',
					'type' => 'file',
					'std' => ''
				),
			array(
				'name' => __('OGA File URL', 'zilla'),
				'desc' => __('The URL to the .oga, .ogg audio file', 'zilla'),
				'id' => '_zilla_audio_ogg',
				'type' => 'file',
				'std' => ''
			)
		)
	);
	zilla_add_meta_box( $meta_box );
	
	$meta_box = array(
		'id' => 'zilla-metabox-featured-post',
		'title' =>  __('Featured Post', 'zilla'),
		'description' => __('A featured post will appear in the top section of the homepage and have a wide image in the standard blog layout.', 'zilla'),
		'page' => 'post',
		'context' => 'side',
		'priority' => 'high',
		'fields' => array(
			array(
					'name' => __('Feature this post', 'zilla'),
					'desc' => __('', 'zilla'),
					'id' => '_zilla_featured_post',
					'type' => 'checkbox',
					'std' => ''
				)
		)
	);
	
	zilla_add_meta_box( $meta_box );
	
}