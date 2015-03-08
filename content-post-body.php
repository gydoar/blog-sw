<div class="post-container">
	<div class="post-row">
		<div id="post-inner" class="post-inner">
			<?php
			$format = get_post_format();
			if ($format === 'link') {
				zilla_link_format($post->ID);
			} else if ($format === 'quote') {
				zilla_quote_format($post->ID);
			}
			?>
			
			<!--BEGIN .entry-header-->
			<header class="entry-header">
				<?php zilla_post_title(); ?>
				<?php zilla_post_meta_header(); ?>
				
				<?php 
					if( is_single() || ((is_blog() || is_search()) && zilla_get_mod('blog_layout', false) !== 'blog-list-compressed') ) :
						zilla_share_links();
					endif;
				?>
			<!--END .entry-header-->
			</header>

			<?php if( is_search() || ((is_blog() || is_search()) && zilla_get_mod('blog_layout', false) === 'blog-list-compressed') ) : ?>
				<!--BEGIN .entry-summary -->
				<div class="entry-summary">
					<?php if (is_search() || has_excerpt()): ?>
						<?php the_excerpt(); ?>
					<?php endif; ?>
				<!--END .entry-summary -->
				</div>
			<?php else : ?>

				<!--BEGIN .entry-content -->
				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages: ', 'zilla').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<!--END .entry-content -->
				</div>

			<?php endif; ?>

			<?php if( is_single() || ((is_blog() || is_search()) && zilla_get_mod('blog_layout', false) !== 'blog-list-compressed') ) : ?>
			<!--BEGIN .entry-header-->
			<footer class="entry-footer">
				<?php if (is_single()) {
					zilla_author_bio();
				} ?>
				<?php zilla_post_meta_footer(); ?>
			<!--END .entry-header-->
			</footer>
			<?php endif; ?>
		</div>
	</div>
</div>