<?php if( is_active_sidebar( 'sidebar' ) ): ?>

	<!--BEGIN #sidebar .sidebar-->
	<div id="sidebar" class="site-secondary sidebar" role="complementary">

		<?php

		/* Widgetised Area */
		if( is_active_sidebar( 'sidebar' ) )
			dynamic_sidebar( 'sidebar' );

		?>

	<!--END #sidebar-home-->
	</div>

<?php endif; ?>