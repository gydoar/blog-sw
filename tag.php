<?php
/**
 * The template for displaying Category archives
 *
 * @package versed
 * @since versed 1.0
 */

get_header(); ?>

	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">
	
	<?php if (have_posts()) : ?>			

		<!--BEGIN .archive-header-->
		<header class="archive-header">
			<h1 class="archive-title">	<?php printf( __('Tag Archives: %s', 'zilla'), single_tag_title( '', false ) ); ?></h1>
			<?php if ( tag_description() ) { ?>
				<div class="archive-meta"><?php echo tag_description(); ?></div>
			<?php } ?>
		<!--END .archive-header-->
		</header>

		<?php while (have_posts()) : the_post(); ?>
	    
			<?php get_template_part('content', get_post_format() ); ?>

		<?php endwhile; ?>

		<?php zilla_paging_nav(); ?>
	
	<?php else : ?>

			<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>
	
	<!--END #primary .site-main-->
	</main>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>