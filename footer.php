<?php
/**
 * The template for showing our footer
 *
 * @package Versed
 * @since Versed 1.0
 */
?>

			<?php get_sidebar( 'footer' ); ?>

			<?php zilla_content_end(); ?>
			<!-- END #content .site-content-->
			</div>

			<?php if (is_single()):  ?>
			<div class="footer-bar">
				<div class="container">
					<div class="row">
						<div class="post-info col-md-8 col-md-offset-2 clearfix">
							<!-- container for the post title and shares to be appended to -->
							<?php zilla_post_title(); ?>
							<?php zilla_share_links(); ?>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php zilla_footer_before(); ?>
			<!-- BEGIN #footer -->
			<footer id="footer" class="site-footer" role="contentinfo">
			<?php zilla_footer_start(); ?>

				<p class="copyright">&copy; <?php echo date( 'Y' ); ?> <a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>.</p>

				<p class="credit"><?php _e('Powered by', 'zilla') ?> <a href="//wordpress.org/">WordPress</a> | <a href="//demo.themezilla.com/versed">Versed</a> by <a href="//www.themezilla.com/">ThemeZilla</a></p>

			<?php zilla_footer_end(); ?>
			<!-- END #footer -->
			</footer>
			<?php zilla_footer_after(); ?>

		<!-- END .site-pusher -->
		</div>

	<!-- END #container .hfeed .site -->
	</div>

	<!-- Theme Hook -->
	<?php wp_footer(); ?>
	<?php zilla_body_end(); ?>

	<!-- <?php echo 'Ran '. $wpdb->num_queries .' queries '. timer_stop(0, 2) .' seconds'; ?> -->
<!--END body-->
</body>
<!--END html-->
</html>
