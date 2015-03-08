<?php

$incdir = get_template_directory() . '/inc/admin/';

/*-----------------------------------------------------------------------------------*/
/*	Load Theme Specific Components
/*-----------------------------------------------------------------------------------*/

require_once($incdir .'theme-customize.php');
require_once($incdir .'meta/post-meta.php');
require_once($incdir .'meta/seo-meta.php');

/*-----------------------------------------------------------------------------------*/
/*	Load Widgets
/*-----------------------------------------------------------------------------------*/

require_once($incdir .'widgets/widget-ad125.php');
require_once($incdir .'widgets/widget-ad300x250.php');
require_once($incdir .'widgets/widget-flickr.php');
require_once($incdir .'widgets/widget-video.php');
require_once($incdir .'widgets/widget-tabbed.php');
require_once($incdir .'widgets/widget-recentposts.php');