<?php

/*-----------------------------------------------------------------------------------

 	Plugin Name: Custom Recent Work Widget
 	Plugin URI: http://www.themezilla.com
 	Description: A widget that displays your latest work
 	Version: 1.0
 	Author: ThemeZilla
 	Author URI: http://www.themezilla.com

-----------------------------------------------------------------------------------*/


/*---------------------------------------------------------------------------------*/
/*  Create the widget
/*---------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'zilla_recentwork_widgets' );

function zilla_recentwork_widgets() {
	register_widget( 'zilla_Recentwork_Widget' );
}


/*-----------------------------------------------------------------------------------*/
/*  Widget class
/*-----------------------------------------------------------------------------------*/
class zilla_recentwork_widget extends WP_Widget {

/*-----------------------------------------------------------------------------------*/
/*	Widget Default Settings
/*-----------------------------------------------------------------------------------*/

private $defaults = array(
    'title' => 'My Recent Work',
    'number' => 1,
    'desc' => 'This is my latest work, pretty cool huh!'
);


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
function __construct() {
    parent::__construct(
        'zilla_recentwork_widget',
        __( 'Custom Recent Work Widget', 'zilla' ),
        array(
            'description' => __( 'A widget that displays your recent work.', 'zilla'),
            'classname' => 'zilla-recentwork-widget'
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
	$number = ( $instance['number'] ) ? $instance['number'] : 1;
	$desc = $instance['desc'];

	/* Display Widget -----------------------------------------------------------*/
	echo $before_widget;

	if ( $title ) {	echo $before_title . $title . $after_title; }

	$query = new WP_Query(array(
				'post_type' => 'portfolio',
				'posts_per_page' => $number
			)
	    );
	?>

	<ul class="grid clearfix">

		<?php if($desc != '') : ?>
		<li><p><?php echo $desc; ?></p></li>
		<?php endif; ?>

 	<?php

	if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

	$thumb = get_post_meta(get_the_ID(), 'zilla_portfolio_thumb', TRUE);
	$type = get_post_meta(get_the_ID(), 'zilla_switch', TRUE);

	if($thumb == '')
		$thumb = FALSE;
	?>
		<li <?php post_class(); ?> id="zilla-recentwork-post-<?php the_ID(); ?>">

			<div class="post-thumb">

				<a href="<?php the_permalink(); ?>" title="<?php printf(__('Permanent Link to %s', 'zilla'), get_the_title()); ?>">
					<span class="post-thumb-overlay">
						<span class="<?php echo $type; ?>-icon icon"></span>
					</span>

					<?php if($thumb) : ?>
                    <img width="204" height="124" src="<?php echo $thumb; ?>" alt="<?php the_title(); ?>" />
                    <?php else: ?>
                    <?php the_post_thumbnail('thumbnail-preview'); ?>
                    <?php endif; ?>
				</a>

			</div>

			<h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'zilla'), get_the_title()); ?>"> <?php the_title(); ?></a></h3>

			<div class="entry-excerpt"><?php the_excerpt(); ?></div>

					</li>

	<?php endwhile; endif; ?>

	</ul>

	<?php

	echo $after_widget;
}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	/* Strip tags to remove HTML (important for text inputs) ------------------------*/
	$instance['title'] = strip_tags( $new_instance['title'] );

	/* Stripslashes for html inputs -------------------------------------------------*/
	$instance['desc'] = stripslashes( $new_instance['desc']);
	$instance['number'] = stripslashes( $new_instance['number']);

	return $instance;
}


/*-----------------------------------------------------------------------------------*/
/*	Widget Settings (Displays the widget settings controls on the widget panel)
/*-----------------------------------------------------------------------------------*/

function form( $instance ) {

	$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'desc' ); ?>"><?php _e('Short Description:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>" value="<?php echo stripslashes(htmlspecialchars(( $instance['desc'] ), ENT_QUOTES)); ?>" />
	</p>

	<?php
	}
}
?>
