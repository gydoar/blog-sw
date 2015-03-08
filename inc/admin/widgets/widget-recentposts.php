<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: Custom Recent Posts Widget
	Plugin URI: http://www.themezilla.com
	Description: A widget that allows the display of blog posts.
	Version: 1.0
	Author: ThemeZilla
	Author URI: http://www.themezilla.com

-----------------------------------------------------------------------------------*/


/*---------------------------------------------------------------------------------*/
/*  Create the widget
/*---------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'zilla_recentposts_widget' );

function zilla_recentposts_widget() {
	register_widget( 'zilla_Recentposts_Widget' );
}


/*-----------------------------------------------------------------------------------*/
/*  Widget class
/*-----------------------------------------------------------------------------------*/
class zilla_recentposts_widget extends WP_Widget {

/*-----------------------------------------------------------------------------------*/
/*	Widget Default Settings
/*-----------------------------------------------------------------------------------*/

	private $defaults = array(
		'title' => 'Take a look behind the scenes.',
		'number' => 4
	);


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
	function __construct() {
		parent::__construct(
			'zilla_blog_widget',
			__( 'Custom Recent Posts Widget', 'zilla' ),
			array(
				'description' => __( 'A widget that displays your latest posts with a short excerpt.', 'zilla'),
				'classname' => 'zilla-blog-widget'
			)
		);
	}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/

	function widget( $args, $instance ) {
		extract( $args );
		if( empty($instance) ) $instance = wp_parse_args( (array) $instance, $this->defaults );

		/* Our variables from the widget settings. ----------------------------------*/
		$title = apply_filters('widget_title', $instance['title'] );
		$number = $instance['number'];

		/* Display Widget -----------------------------------------------------------*/
		echo $before_widget;

		if ( $title ) { echo $before_title . $title . $after_title; }
		?>

        <ul>

			<?php
            $query = new WP_Query();
            $query->query( array(
                'posts_per_page' => $number,
                'post_type' => 'post',
                'ignore_sticky_posts' => 1
            ));
            ?>
            <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
            <li id="post-<?php echo get_the_ID(); ?>" <?php post_class(); ?>>

                <div class="entry-meta">
                    <a href="<?php the_permalink(); ?>" title="<?php printf(__('Permanent link to %s', 'zilla'), get_the_title()); ?>" class="post-format"><span class="post-format"></span></a>
                </div>

                <div class="detail">
                    <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                    <?php the_excerpt(); ?>
                </div>

            </li>
            <?php endwhile; endif; ?>

            <?php wp_reset_query(); ?>

        </ul>

		<?php

		echo $after_widget;
	}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		/* Strip tags to remove HTML (important for text inputs). -------------------*/
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags( $new_instance['number'] );

		return $instance;
	}


/*-----------------------------------------------------------------------------------*/
/*	Widget Settings
/*-----------------------------------------------------------------------------------*/

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'zilla') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Amount to show:', 'zilla') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>

	<?php
	}
}
?>
