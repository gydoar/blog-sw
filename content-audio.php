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

	<?php if (zilla_get_mod('blog_layout', false) === 'blog-list-compressed' && is_blog()): ?>
		<figure class="entry-thumbnail">
			<a class="post-thumb-link accent-color" href="<?php the_permalink() ?>">
				<?php 
				if (has_post_thumbnail()):
					the_post_thumbnail( 's-thumb' );
				else: 
					zilla_post_format_icon();
				endif;
				?>
			</a>
		</figure>
	<?php 
	else:
		echo versed_print_audio_html($post->ID);
	endif;	
	?>
	
	<?php get_template_part('content', 'post-body'); ?>

<?php zilla_post_end(); ?>
<!--END .post-->
</article>
<?php zilla_post_after(); ?>