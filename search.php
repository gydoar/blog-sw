<?php 
/**
 * The template for displaying search results
 *
 * @package versed
 * @since versed 1.0
 */
get_header(); ?>
			
	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">
	<?php if (have_posts()) : ?>

		<header class="archive-header">
			<h1 class="archive-title"><?php _e('Search Results for', 'zilla') ?> &#8220;<?php the_search_query(); ?>&#8221;</h1>			
		</header>

		<?php while (have_posts()) : the_post(); ?>
        
			<?php get_template_part('content', get_post_format()); ?>

		<?php endwhile; ?>

		<?php zilla_paging_nav(); ?>

	<?php else : ?>
		
		<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>
	<!--END #primary .site-main-->
	</main>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>