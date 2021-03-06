<?php

/*-------------------------------------------------------------------------------------

	Plugin Name: Custom 125x125 Ad Unit
	Plugin URI: http://www.themezilla.com
	Description: A widget that allows the selection and configuration of 6 125x125 ad units
	Version: 1.0
	Author: ThemeZilla
	Author URI: http://www.themezilla.com

-------------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/*  Create the widget
/*-----------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'zilla_ads_widgets' );

function zilla_ads_widgets() {
	register_widget( 'zilla_Ad_Widget' );
}

/*-----------------------------------------------------------------------------------*/
/*  Widget class
/*-----------------------------------------------------------------------------------*/
class zilla_ad_widget extends WP_Widget {

/*-----------------------------------------------------------------------------------*/
/*	Widget Default Settings
/*-----------------------------------------------------------------------------------*/

private $defaults = array(
	'title' => 'Our Sponsors',
	'ad1' => 'http://placehold.it/125x125',
	'link1' => 'http://www.themezilla.com',
	'ad2' => 'http://placehold.it/125x125',
	'link2' => 'http://www.themezilla.com',
	'ad3' => '',
	'link3' => '',
	'ad4' => '',
	'link4' => '',
	'ad5' => '',
	'link5' => '',
	'ad6' => '',
	'link6' => '',
	'random' => false
);


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/

function __construct() {
	parent::__construct(
		'zilla_ad_widget',
		__( 'Custom 125x125 Ads', 'zilla' ),
		array(
			'description' => __( 'A widget that allows the display and configuration of 6 125x125 ads blocks.', 'zilla'),
			'classname' => 'zilla-ad-widget'
		)
	);
}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/

function widget( $args, $instance ) {
	extract( $args );
	if( empty($instance) ) $instance = wp_parse_args( (array) $instance, $this->defaults );

	/* Our variables from the widget settings --------------------------------------*/
	$title = apply_filters('widget_title', $instance['title'] );

	/* Build ads array -------------------------------------------------------------*/
	for( $i = 1; $i <= 6; $i++ ) {
		$ads[$i]['img'] = $instance["ad$i"];
		$ads[$i]['link'] = $instance["link$i"];
	}

	$randomize = $instance['random'];
	if( $randomize ) {
		shuffle($ads);
	}

	/* Build ads output in a new array ---------------------------------------------*/
	$output = '';
	foreach( $ads as $ad ) {
		if( !empty($ad['link']) && !empty($ad['img']) ) {
			$output .= '<a href="' . $ad['link'] . '"><img src="' . $ad['img'] . '" width="125" height="125" alt="" /></a>';
		} elseif( !empty($ad['img']) ) {
			$output .= '<img src="' . $ad['img'] . '" widht="125" height="125" alt="" />';
		}

	}

    /* Display widget -------------------------------------------------------------*/
	echo $before_widget;

	if ( $title ) { echo $before_title . $title . $after_title; }

	if( !empty( $output ) ) {
		echo '<div class="ads-125 clearfix">';
			echo $output;
		echo '</div>';
	}

	echo $after_widget;
}


/*--------------------------------------------------------------------------------*/
/*	Update Widget
/*--------------------------------------------------------------------------------*/

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	/* Strip tags to remove HTML (important for text inputs) ---------------------*/
	$instance['title'] = strip_tags( $new_instance['title'] );

	/* No need to strip tags -----------------------------------------------------*/
	$instance['ad1'] = $new_instance['ad1'];
	$instance['ad2'] = $new_instance['ad2'];
	$instance['ad3'] = $new_instance['ad3'];
	$instance['ad4'] = $new_instance['ad4'];
	$instance['ad5'] = $new_instance['ad5'];
	$instance['ad6'] = $new_instance['ad6'];
	$instance['link1'] = $new_instance['link1'];
	$instance['link2'] = $new_instance['link2'];
	$instance['link3'] = $new_instance['link3'];
	$instance['link4'] = $new_instance['link4'];
	$instance['link5'] = $new_instance['link5'];
	$instance['link6'] = $new_instance['link6'];
	$instance['random'] = $new_instance['random'];

	return $instance;
}


/*------------------------------------------------------------------------------*/
/*	Widget Settings (Displays the widget settings controls on the widget panel)
/*------------------------------------------------------------------------------*/

function form( $instance ) {

	$instance = wp_parse_args( (array) $instance, $this->defaults );

	/* Build our form fields --------------------------------------------------*/
	?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'ad1' ); ?>"><?php _e('Ad 1 image url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad1' ); ?>" name="<?php echo $this->get_field_name( 'ad1' ); ?>" value="<?php echo $instance['ad1']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link1' ); ?>"><?php _e('Ad 1 link url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link1' ); ?>" name="<?php echo $this->get_field_name( 'link1' ); ?>" value="<?php echo $instance['link1']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'ad2' ); ?>"><?php _e('Ad 2 image url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad2' ); ?>" name="<?php echo $this->get_field_name( 'ad2' ); ?>" value="<?php echo $instance['ad2']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link2' ); ?>"><?php _e('Ad 2 link url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link2' ); ?>" name="<?php echo $this->get_field_name( 'link2' ); ?>" value="<?php echo $instance['link2']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'ad3' ); ?>"><?php _e('Ad 3 image url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad3' ); ?>" name="<?php echo $this->get_field_name( 'ad3' ); ?>" value="<?php echo $instance['ad3']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link3' ); ?>"><?php _e('Ad 3 link url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link3' ); ?>" name="<?php echo $this->get_field_name( 'link3' ); ?>" value="<?php echo $instance['link3']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'ad4' ); ?>"><?php _e('Ad 4 image url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad4' ); ?>" name="<?php echo $this->get_field_name( 'ad4' ); ?>" value="<?php echo $instance['ad4']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link4' ); ?>"><?php _e('Ad 4 link url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link4' ); ?>" name="<?php echo $this->get_field_name( 'link4' ); ?>" value="<?php echo $instance['link4']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'ad5' ); ?>"><?php _e('Ad 5 image url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad5' ); ?>" name="<?php echo $this->get_field_name( 'ad5' ); ?>" value="<?php echo $instance['ad5']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link5' ); ?>"><?php _e('Ad 5 link url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link5' ); ?>" name="<?php echo $this->get_field_name( 'link5' ); ?>" value="<?php echo $instance['link5']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'ad6' ); ?>"><?php _e('Ad 6 image url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'ad6' ); ?>" name="<?php echo $this->get_field_name( 'ad6' ); ?>" value="<?php echo $instance['ad6']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link6' ); ?>"><?php _e('Ad 6 link url:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'link6' ); ?>" name="<?php echo $this->get_field_name( 'link6' ); ?>" value="<?php echo $instance['link6']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'random' ); ?>"><?php _e('Randomize ads order?', 'zilla') ?></label>
		<?php if ($instance['random']){ ?>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'random' ); ?>" name="<?php echo $this->get_field_name( 'random' ); ?>" checked="checked" />
		<?php } else { ?>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'random' ); ?>" name="<?php echo $this->get_field_name( 'random' ); ?>"  />
		<?php } ?>
	</p>

	<?php
	}
}
?>
