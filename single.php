<?php
/**
 * The template for showing the single post view
 * 
 * @package versed
 * @since versed 1.0
 */
get_header(); ?>

	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">

		<?php while (have_posts()) : the_post(); ?>
		
			<?php get_template_part('content', get_post_format() ); ?>
			
				<?php $prev_post = get_adjacent_post( false, '', true ); ?>
				<?php if ( !empty( $prev_post ) ): ?>
					<?php 
						$prev_ID = $prev_post->ID;
						$prev_thumbnail = get_the_post_thumbnail($prev_ID); 
					?>
					<div class="post-preview <?php if ($prev_thumbnail) { echo 'has-post-thumbnail'; } ?> <?php if (has_excerpt($prev_ID)) { echo 'has-excerpt'; } ?>">
						<div class="preview-wrap">
							
							<?php zilla_post_thumbnail( $prev_ID, 'fullscreen' ); ?>
							
							<div class="post-container">
								<div class="post-row">
									<div class="preview-inner">
										<h2 class="title-next"><?php  _e('Next Article', 'zilla' ); ?></h2>
										<h3 class="title-next-post"><a href="<?php echo get_permalink($prev_ID); ?>"><?php echo $prev_post->post_title; ?></a></h3>

										<?php if (has_excerpt($prev_ID)): ?>
										<div class="entry-content">
											<?php echo $prev_post->post_excerpt; ?>
										</div>
										<?php endif; ?>
										
										<?php zilla_comments_link($prev_ID); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
		
			<?php 
			    zilla_comments_before();
			    comments_template('', true); 
			    zilla_comments_after();
			?>

		<?php endwhile; ?>

	<!--END #primary .site-main-->
	</main>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>