<?php
/**
 * Template Name: Archives
 *
 * Custom template for display summary of archives
 *
 * @package versed
 * @since versed 1.0
 */
get_header(); ?>

	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<?php zilla_page_before(); ?>
		<!--BEGIN .page -->
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<?php zilla_page_start(); ?>
		
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			
			<!--BEGIN .entry-content -->
			<div class="entry-content">
				<?php the_content(__('Read more...', 'zilla')); ?>
				<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:', 'zilla').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			<!--END .entry-content -->
			</div>
			
			<!--BEGIN .archive-lists -->
			<div class="archive-lists">
				
				<h4><?php _e('Last 30 Posts', 'zilla') ?></h4>
				
				<ul>
				<?php $archive_30 = get_posts('numberposts=30');
				foreach($archive_30 as $post) : ?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
				<?php endforeach; ?>
				</ul>
				
				<h4><?php _e('Archives by Month:', 'zilla') ?></h4>
				
				<ul>
					<?php wp_get_archives('type=monthly'); ?>
				</ul>
	
				<h4><?php _e('Archives by Subject:', 'zilla') ?></h4>
				
				<ul>
			 		<?php wp_list_categories( 'title_li=' ); ?>
				</ul>
			
			<!--END .archive-lists -->
			</div>
            
            <?php zilla_page_end(); ?>
            <!--END .page-->  
		</article>
		<?php zilla_page_after(); ?>
		
		<?php endwhile; else: ?>

			<?php get_template_part('content', 'none'); ?>

	<?php endif; ?>
	<!--END #primary .site-main-->
	</main>

<?php get_footer(); ?>