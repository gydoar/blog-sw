<div class="btn-top-wrap"><a href="#" id="btn-top" class="btn-scroll"><i class="fa fa-angle-up"></i>Top</a></div>

<?php if( is_active_sidebar( 'sidebar-footer' ) || is_active_sidebar( 'sidebar-footer-2' ) || is_active_sidebar( 'sidebar-footer-3' ) ): ?>

	<?php zilla_sidebar_before(); ?>
	<!--BEGIN #sidebar .sidebar-->
	<aside id="sidebar-footer" class="sidebar-footer" role="complementary">

		<div class="container">
			<div class="row row-centered">
				<?php
			    zilla_sidebar_start();
					
				for ($i = 1; $i < 4; $i++) {
					$c = $i == 1 ? null : '-' . $i;
					
					if( is_active_sidebar( 'sidebar-footer' . $c ) ):
					
						echo '<div class="col-sm-12 col-md-4 col-centered widget-col' . $c . '">';
						
							dynamic_sidebar( 'sidebar-footer' . $c );
						
						echo '</div>';
					
					endif;
				}

				zilla_sidebar_end();
				?>
			</div>
		</div>

	<!--END #sidebar .site-secondary .sidebar-->
	</aside>
	<?php zilla_sidebar_after(); ?>

<?php endif; ?>