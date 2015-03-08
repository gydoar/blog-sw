<?php

/*-----------------------------------------------------------------------------------

 	Plugin Name: Custom Tabbed Widget
 	Plugin URI: http://www.themezilla.com
 	Description: A widget that display popular posts, recent posts, recent comments and tags
 	Version: 1.0
 	Author: ThemeZilla
 	Author URI: http://www.themezilla.com

-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/*  Create the widget
/*-----------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'zilla_tab_widgets' );

function zilla_tab_widgets() {
	register_widget( 'zilla_Tab_Widget' );
}

/* Register and queue JS ------------------------------------------------------------*/
function zilla_tabbed_js(){
	if (!is_admin() && is_active_widget(false, false, 'zilla_tab_widget')) {
	    wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tabs');
	}
}

add_action('init', 'zilla_tabbed_js');

/*-----------------------------------------------------------------------------------*/
/*  Widget class
/*-----------------------------------------------------------------------------------*/
class zilla_tab_widget extends WP_Widget {

/*-----------------------------------------------------------------------------------*/
/*	Widget Default Settings
/*-----------------------------------------------------------------------------------*/

private $defaults = array(
    'title' => '',
    'tab1' => 'Popular',
    'tab2' => 'Recent',
    'tab3' => 'Comments',
    'tab4' => 'Tags'
);


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/

function __construct() {
    parent::__construct(
        'zilla_tab_widget',
        __( 'Custom Tabbed Widget', 'zilla' ),
        array(
            'description' => __( 'A tabbed widget that display popular posts, recent posts, comments and tags.', 'zilla'),
            'classname' => 'zilla-tab-widget'
        )
    );
}


/*-----------------------------------------------------------------------------------*/
/*	Display Widget
/*-----------------------------------------------------------------------------------*/

function widget( $args, $instance ) {
	global $wpdb;
	extract( $args );
    if( empty($instance) ) $instance = wp_parse_args( (array) $instance, $this->defaults );

	/* Our variables from the widget settings ---------------------------------------*/
	$title = apply_filters('widget_title', $instance['title'] );
	$tab1 = $instance['tab1'];
	$tab2 = $instance['tab2'];
	$tab3 = $instance['tab3'];
	$tab4 = $instance['tab4'];

	/* Display widget ---------------------------------------------------------------*/
	echo $before_widget;

	if ( $title ) { echo $before_title . $title . $after_title; }

	/* Display the tab navigation --------------------------------------------------*/
	?>

	<script type="text/javascript">
    	jQuery(document).ready(function() {
        	jQuery(".zilla_tabs").tabs({ fx: { opacity: 'show' } });
        });
    </script>

	<?php
	echo '<div class="zilla_tabs">';

		echo '<ul id="tab-items">';
			echo '<li><a href="#tabs-1"><span>'.$tab1.'</span></a></li>';
			echo '<li><a href="#tabs-2"><span>'.$tab2.'</span></a></li>';
			echo '<li><a href="#tabs-3"><span>'.$tab3.'</span></a></li>';
			echo '<li><a href="#tabs-4"><span>'.$tab4.'</span></a></li>';
		echo '</ul>';

		echo '<div class="tabs-inner">';

		/* Display popular posts tab -----------------------------------------------*/
		echo '<div id="tabs-1" class="tab tab-popular">';
			echo '<ul>';

			$popPosts = new WP_Query();
			$popPosts->query('ignore_sticky_posts=1&showposts=5&orderby=comment_count');
			while ($popPosts->have_posts()) : $popPosts->the_post(); ?>

				<li class="clearfix">
					<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>
					<div class="tab-thumb">
						<a href="<?php the_permalink();?>" class="thumb"><?php the_post_thumbnail(); ?></a>
					</div>
					<?php } ?>
					<h3 class="entry-title"><a href="<?php the_permalink(); ?>" class="title"><?php the_title();?></a></h3>
					<div class="entry-meta entry-header">
						<span class="published"><?php the_time( get_option('date_format') ); ?></span>
						<span class="meta-sep">&middot;</span>
						<span class="comment-count"><?php comments_popup_link(__('No Comments', 'zilla'), __('1 Comment', 'zilla'), __('% Comments', 'zilla')); ?></span>
					</div>
				</li>

			<?php endwhile;
			wp_reset_query();

			echo '</ul>';
		echo '</div><!-- #tabs-1 -->';

		/* Display recent posts tab -----------------------------------------------*/
		echo '<div id="tabs-2" class="tab tab-recent">';
			echo '<ul>';

			$recentPosts = new WP_Query();
			$recentPosts->query('ignore_sticky_posts=1&showposts=5');
			while ($recentPosts->have_posts()) : $recentPosts->the_post(); ?>

				<li class="clearfix">
					<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>
					<div class="tab-thumb">
						<a href="<?php the_permalink();?>" class="thumb"><?php the_post_thumbnail(); ?></a>
					</div>
					<?php } ?>
					<h3 class="entry-title"><a href="<?php the_permalink(); ?>" class="title"><?php the_title();?></a></h3>
					<div class="entry-meta entry-header">
						<span class="published"><?php the_time( get_option('date_format') ); ?></span>
						<span class="meta-sep">&middot;</span>
						<span class="comment-count"><?php comments_popup_link(__('No Comments', 'zilla'), __('1 Comment', 'zilla'), __('% Comments', 'zilla')); ?></span>
				</div>
				</li>

			<?php endwhile;

			echo '</ul>';
		echo '</div><!-- #tabs-2 -->';

		/* Display comments tab --------------------------------------------------*/
		echo '<div id="tabs-3" class="tab tab-comments">';

			$comments = get_comments(array(
				'status' => 'approve',
				'number' => '5'
			));

			echo '<ul>';

			foreach ($comments as $comment) { ?>

				<li class="clearfix">

				    <a href="<?php echo get_permalink($comment->comment_ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php echo strip_tags($comment->comment_author); ?> <?php _e('on ', 'zilla'); ?><?php echo $comment->post_title; ?>"><?php echo get_avatar( $comment->comment_author_email, '50' ); ?></a>

					<h3><a href="<?php echo get_permalink($comment->comment_ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php echo strip_tags($comment->comment_author); ?> <?php _e('on ', 'zilla'); ?><?php echo $comment->post_title; ?>"><?php echo strip_tags($comment->comment_author); ?>: <?php echo strip_tags(comment_excerpt($comment->comment_ID)); ?>...</a></h3>

				</li>
				<?php }

			echo '</ul>';
		echo '</div><!-- #tabs-3 -->';

		/* Display tags tab -------------------------------------------------------*/
		echo '<div id="tabs-4" class="tab tab-tags clearfix">';
		wp_tag_cloud('largest=12&smallest=12&unit=px');
		echo '</div><!-- #tabs-4 -->';

	echo '</div><!-- .tabs-inner -->';
	echo '</div><!-- #tabs -->';

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
	$instance['tab1'] = $new_instance['tab1'];
	$instance['tab2'] = $new_instance['tab2'];
	$instance['tab3'] = $new_instance['tab3'];
	$instance['tab4'] = $new_instance['tab4'];

	return $instance;
}


/*-----------------------------------------------------------------------------------*/
/*	Widget Settings (Displays the widget settings controls on the widget panel)
/*-----------------------------------------------------------------------------------*/

function form( $instance ) {

	$instance = wp_parse_args( (array) $instance, $this->defaults );

	/* Build our form ---------------------------------------------------------------*/
	?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'tab1' ); ?>"><?php _e('Tab 1 Title:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'tab1' ); ?>" name="<?php echo $this->get_field_name( 'tab1' ); ?>" value="<?php echo $instance['tab1']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link1' ); ?>"><?php _e('Tab 2 Title:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'tab2' ); ?>" name="<?php echo $this->get_field_name( 'tab2' ); ?>" value="<?php echo $instance['tab2']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'tab2' ); ?>"><?php _e('Tab 3 Title:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'tab3' ); ?>" name="<?php echo $this->get_field_name( 'tab3' ); ?>" value="<?php echo $instance['tab3']; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'link2' ); ?>"><?php _e('Tab 4 Title:', 'zilla') ?></label>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'tab4' ); ?>" name="<?php echo $this->get_field_name( 'tab4' ); ?>" value="<?php echo $instance['tab4']; ?>" />
	</p>

	<?php
	}
}
?>
