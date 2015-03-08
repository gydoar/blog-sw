<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: Custom 300x250 Ad Unit
	Plugin URI: http://www.themezilla.com
	Description: A widget that allows the selection and configuration of a single 300x250 Banner
	Version: 1.0
	Author: ThemeZilla
	Author URI: http://www.themezilla.com

-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/*  Create the widget
/*-----------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'zilla_ad300_widgets' );

function zilla_ad300_widgets() {
	register_widget( 'zilla_Ad300_Widget' );
}


/*-----------------------------------------------------------------------------------*/
/*  Widget class
/*-----------------------------------------------------------------------------------*/
class zilla_ad300_widget extends WP_Widget {

/*-----------------------------------------------------------------------------------*/
/*	Widget Default Settings
/*-----------------------------------------------------------------------------------*/

private $defaults = array(
	'title' => '',
	'ad' => 'http://placehold.it/300x250',
	'link' => 'http://www.premiumpixels.com'
);


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/

function __construct() {
	parent::__construct(
		'zilla_ad300_widget',
		__( 'Custom 300x250 Ad', 'zilla' ),
		array(
			'description' => __( 'A widget that allows the display and configuration of of a single 300x250 Banner.', 'zilla'),
			'classname' => 'zilla-ad300-widget'
		)
	);
}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/

function widget( $args, $instance ) {
	extract( $args );
	if( empty($instance) ) $instance = wp_parse_args( (array) $instance, $this->defaults );

	/* Our variables from the widget settings ---------------------------------------*/
	$title = apply_filters('widget_title', $instance['title'] );
	$ad = $instance['ad'];
	$link = $instance['link'];

	/* Build the widget -------------------------------------------------------------*/
	echo $before_widget;

	if ( $title ) { echo $before_title . $title . $after_title; }

	echo '<div class="ads-300">';

	if ( $link ) {
		echo '<a href="' . $link . '"><img src="' . $ad . '" width="300" height="250" alt="" /></a>';
	} elseif ( $ad ) {
	 	echo '<img src="' . $ad . '" width="300" height="250" alt="" />';
	}
	echo '</div>';

	echo $after_widget;
}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	/* Strip tags to remove HTML (important for text inputs) ------------------------*/
	$instance['title'] = strip_tags( $new_instance['title'] );

	/* No need to strip tags --------------------------------------------------------*/
	$instance['ad'] = $new_instance['ad'];
	$instance['link'] = $new_instance['link'];

	return $instance;
}


/*-----------------------------------------------------------------------------------*/
/*	Widget Settings (Displays the widget settings controls on the widget panel)
/*-----------------------------------------------------------------------------------*/

function form( $instance ) {

	$instance = wp_parse_args( (array) $instance, $this->defaults );

	/* Build our form fields --------------------------------------------------------*/
	?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'ad' ); ?>"><?php _e('Ad image url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad' ); ?>" name="<?php echo $this->get_field_name( 'ad' ); ?>" value="<?php echo $instance['ad']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e('Ad link url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" />
	</p>

	<?php
	}
}
?>
