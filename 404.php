<?php 
/**
 * The template for displaying the 404 page
 *
 * @package Versed
 * @since Versed
 */

get_header(); ?>

	<!--BEGIN #primary .site-main-->
	<main id="primary" class="site-main" role="main">
	
		<?php get_template_part('content', 'none'); ?>
		
	<!--END #primary .site-main-->
	</main>

	<?php get_sidebar(); ?>

<?php get_footer(); ?>