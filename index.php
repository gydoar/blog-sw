<?php 
/**
 * The main template file
 *
 * @package Versed
 * @since Versed
 */
get_header(); ?>
	
	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">			
	<?php if( have_posts() ) : ?>
	
		<?php while (have_posts()) : the_post(); ?>
		
			<?php get_template_part('content', get_post_format() ); ?>

		<?php endwhile; ?>

			<?php zilla_paging_nav(); ?>

	<?php else : ?>

		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>
	<!--END #primary .site-main-->
	</main>

<?php get_footer(); ?>