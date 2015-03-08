<?php
/**
 * The template to display post content
 *
 * @package Versed
 * @since Versed 1.0
 */

zilla_post_before(); ?>

<!--BEGIN .post -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php zilla_post_start(); ?>

	<?php 	
	$format = get_post_format();
	$formats = array('link', 'quote');
	
	if ( has_post_thumbnail() ):
		echo '<figure class="entry-thumbnail">';
			if (zilla_get_mod('blog_layout', false) === 'blog-list-compressed' && is_blog()):
				echo '<a class="post-thumb-link" href="' . get_permalink() . '">';
					the_post_thumbnail( 's-thumb' );
				echo '</a>';
			else:						
				$size = (is_single() || get_post_meta(get_the_ID(), '_zilla_featured_post', true) === 'on') ? 'fullscreen' : 'container';
				zilla_post_thumbnail( get_the_ID(), $size );
			endif; 
		echo '</figure>';
	else: 
		if (zilla_get_mod('blog_layout', false) === 'blog-list-compressed' && is_blog()):
			if (in_array($format, $formats)):
				echo '<figure class="entry-thumbnail">';
					echo '<a class="post-thumb-link accent-color" href="' . get_permalink() . '">';
						zilla_post_format_icon();
					echo '</a>';
				echo '</figure>';
			endif;	
		endif;		
	endif; 
	?>

	<?php get_template_part('content', 'post-body'); ?>

<?php zilla_post_end(); ?>
<!--END .post-->
</article>
<?php zilla_post_after(); ?>