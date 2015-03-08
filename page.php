<?php 
/**
 * The template for displaying default layout pages
 *
 * @package versed
 * @since versed 1.0
 */
get_header(); ?>

	<?php if ( has_post_thumbnail() ): ?>
	<figure class="entry-thumbnail">
		<?php zilla_post_thumbnail( get_the_ID(), 'fullscreen' ); ?>
	</figure>
	<?php endif; ?>

	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">
	<?php while (have_posts()) : the_post(); ?>

		<?php zilla_page_before(); ?>
		<!--BEGIN .page-->
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<?php zilla_page_start(); ?>
			
			<div class="post-container">
				<div class="post-row">
					<div class="page-inner">
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>
						</header>
			
						<!--BEGIN .entry-content -->
						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:', 'zilla').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
						<!--END .entry-content -->
						</div>
		            </div>
				</div>
			</div>
            
		<?php zilla_page_end(); ?>
		<!--END .page-->
		</article>
		<?php zilla_page_after(); ?>
		
		<?php 
			zilla_comments_before();
			comments_template('', true); 
			zilla_comments_after();
		?>

	<?php endwhile; ?>
	
	<!--END #primary .site-main-->
	</main>
	
<?php get_footer(); ?>