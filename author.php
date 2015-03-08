<?php
/**
 * The template for displaying Author archive pages
 *
 * @package Versed
 * @since Versed
 */

get_header(); ?>

	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">
		
	<?php if ( have_posts() ) : ?>

		<?php the_post(); ?>

		<header class="archive-header">
			<h1 class="archive-title"><?php printf( __('All posts by %s', 'zilla'), get_the_author() ); ?></h1>
		<!--END .archive-header-->
		</header>

		<?php rewind_posts(); ?>

		<?php if (get_the_author_meta('description') ) {
			zilla_author_bio(); 
		} ?>

		<?php while( have_posts() ) : the_post(); ?>

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