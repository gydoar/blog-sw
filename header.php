<?php
/**
 * The Header template for our theme
 *
 * @package Versed
 * @since Versed 1.0
 */
?>
<!DOCTYPE html>
<!--[if IE 9]>
<html class="ie ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE9) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<!-- A ThemeZilla design (http://www.themezilla.com) - Proudly powered by WordPress (http://wordpress.org) -->

<!-- BEGIN head -->
<head>
	<!-- Meta Tags -->
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<?php zilla_meta_head(); ?>
	
	<!-- Title -->
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
	<?php zilla_head(); ?>
    
<!-- END head -->
</head>

<!-- BEGIN body -->
<body <?php body_class(); ?>>
<?php zilla_body_start(); ?>

	<!-- BEGIN #container -->
	<div id="container" class="hfeed site">
	
		<?php zilla_nav_before(); ?>
		<!-- BEGIN #primary-navigation -->
		<aside id="primary-navigation" class="site-navigation" role="navigation">
			<div class="bg" <?php if (zilla_get_mod_state('offcanvas_background')): ?> style="background-image: url(<?php zilla_get_mod('offcanvas_background'); ?>);" <?php endif; ?>></div>
			<div class="nav-inner">				
				<header class="site-name">
					<a class="logo-default" href="<?php echo home_url(); ?>">
						<?php bloginfo( 'name' ); ?>
					</a>
				</header>
				
				<div class="container-menu">
					<nav class="nav-off-canvas" id="nav-off-canvas">
						<?php
						wp_nav_menu( array( 
							'theme_location' => 'primary-menu',
						) ); 
						?>
					</nav>	
					<?php get_sidebar(); ?>
				</div>
				
				<footer class="site-meta">
					<?php 
						wp_nav_menu( array( 
							'theme_location' => 'social-menu',
							'menu' => 'Social Network Menu',
							'fallback_cb' => false
						) ); 
					?>
					<p class="site-tagline"><?php bloginfo( 'description' ); ?></p>
				</footer>
			</div>
		<!-- END #primary-navigation -->
		</aside>
		<?php zilla_nav_after(); ?>
	
		<?php zilla_header_before(); ?>
		<!-- BEGIN #masthead .site-header -->
		<header id="masthead" class="site-header" role="banner">
		<?php zilla_header_start(); ?>
			
			<?php get_search_form(); ?>
			
			<button id="nav-toggle" class="btn btn-default nav-toggle">
				<i class="fa <?php echo zilla_get_mod_state('offcanvas_icon') ? zilla_get_mod('offcanvas_icon', false) : 'fa-bars'; ?>"></i>
				<span class="sr-only">toggle navigation</span>
			</button>
			
			<!-- BEGIN #logo .site-logo-->
			<div id="logo" class="site-logo">					
				<?php /*
				If "plain text logo" is set in theme options then use text
				if a logo url has been set in theme options then use that
				if none of the above then use the default logo.png */
				if ( zilla_get_mod_state('general_text_logo') ) { ?>
					<a class="logo-default" href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>
				<?php } elseif ( zilla_get_mod_state('general_custom_logo') || zilla_get_mod_state('general_custom_logo_light') ) { ?>
					<a href="<?php echo home_url(); ?>">
						<?php $retina = zilla_get_mod_state('retina_logo') ? 'retina-img' : ''; ?>
						
						<?php if ( zilla_get_mod_state('general_custom_logo')): ?>
							<img class="logo-dark <?php echo $retina; ?>" src="<?php zilla_get_mod('general_custom_logo'); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
						<?php endif; ?>
						
						<?php if ( zilla_get_mod_state('general_custom_logo_light')): ?>
							<img class="logo-light <?php echo $retina; ?>" src="<?php zilla_get_mod('general_custom_logo_light'); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
						<?php endif; ?>
					</a>
				<?php } else { ?>
					<a class="logo-default" href="<?php echo home_url(); ?>">V</a>
				<?php } ?>
			<!-- END #logo .site-logo-->
			</div>
			
			<nav class="nav-bar" id="nav-bar">
				<?php
				wp_nav_menu( array( 
					'theme_location' => 'primary-menu',
				) ); 
				?>
			</nav>
    		
		<?php zilla_header_end(); ?>	
		<!--END #masthead .site-header-->
		</header>
		<?php zilla_header_after(); ?>
		
		<!-- BEGIN .site-pusher -->
		<div class="site-pusher">
						
			<!--BEGIN #content .site-content-->
			<div id="content" class="site-content">
			<?php zilla_content_start(); ?>