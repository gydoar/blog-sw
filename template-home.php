<?php
/**
 * Template Name: Home Page
 *
 * A template for the home page
 *
 * @package versed
 * @since versed 1.0
 */

get_header(); ?>

	<hgroup class="page-header">
		<h1><?php bloginfo( 'name' ); ?></h1>
		<h2><?php bloginfo( 'description' ); ?></h2>
	</hgroup>
	
	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<?php if( '' !== $post->post_content ): ?>
				
				<?php zilla_page_before(); ?>
				<!--BEGIN .page-->
				<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<?php zilla_page_start(); ?>
		
					<!--BEGIN .entry-content -->
					<div class="entry-content">
						<?php the_content(__('Read more...', 'zilla')); ?>
						<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:', 'zilla').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					<!--END .entry-content -->
					</div>
		
				<?php zilla_page_end(); ?>
				<!--END .page-->
				</article>
				<?php zilla_page_after(); ?>
			
			<?php endif; ?>
	
		<?php endwhile; endif; ?>
		
		<section class="featured content">
			<?php zilla_print_home_featured_post(); ?>
		</section>
		
		<section class="recent content <?php echo zilla_get_mod_state('homepage_recent_layout') ? zilla_get_mod('homepage_recent_layout') : 'grid'; ?>">
			<?php zilla_print_home_recent_posts(); ?>
		</section>
	
	<!--END #primary .site-main-->
	</main>

<?php get_footer(); ?>