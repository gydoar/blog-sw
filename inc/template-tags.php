<?php
/**
 * Custom template tags for versed
 *
 * @package versed
 * @since 1.0
 */


if( ! function_exists( 'zilla_responsive_image' ) ) :
/**
 * Print the HTML for a responsive image
 * Used in Featured Image / Image Post Format / Gallery Post Format
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_responsive_image($thumb_data, $output = 'return', $size = 'container') {

	// Echo out <picture> element
	$img  = '<picture>';
	$img .= '<!--[if IE 9]><video style="display: none;"><![endif]-->'; // Fallback for IE9
	if ($size !== 'container') {
		$img .= '<source srcset="' . $thumb_data['thumb_xxl'] . '" media="(min-width: 1921px)">';
		$img .= '<source srcset="' . $thumb_data['thumb_xl'] . ' " media="(min-width: 1260px)">';
	}
	$img .= '<source srcset="' . $thumb_data['thumb_l'] . '  " media="(min-width: 960px)">';
	$img .= '<source srcset="' . $thumb_data['thumb_m'] . '  " media="(min-width: 400px)">';
	$img .= '<source srcset="' . $thumb_data['thumb_s'] . '  ">';
	$img .= '<!--[if IE 9]></video><![endif]-->'; // Fallback for IE9
	$img .= '<img srcset="' . $thumb_data['thumb_s'] . '" alt="' . $thumb_data['thumb_alt'] . '" data-title="' . $thumb_data['thumb_title'] . '" data-desc="' . $thumb_data['thumb_caption'] . '">';
	$img .= '</picture>';

	if ($output === 'return') {
		return $img;
	} elseif ($output === 'echo') {
		echo $img;
	}

}
endif;


if ( ! function_exists('zilla_post_thumbnail') ) :
/**
 *
 * Display the post thumbnail. Credit to @mor10 for the responsive technique.
 *
 * @package versed
 * @since versed 1.0
 *
 * Uses: zilla_set_image_transient() - theme-functions.php
 *		 zilla_responsive_image()
 *
 * @return void
 */
function zilla_post_thumbnail($post_id, $size = 'container') {

	// Check to see if there is a transient available. If there is, use it.
	if ( false === ( $thumb_data = get_transient( 'featured_image_' . $post_id ) ) ) {
		zilla_set_image_transient($post_id);
		$thumb_data = get_transient( 'featured_image_' . $post_id );
	}

	if ( post_password_required() || ! has_post_thumbnail($post_id) ) {
		return;
	}

	zilla_responsive_image($thumb_data, 'echo', $size);
}
endif;


if( ! function_exists( 'zilla_post_thumbnail_meta' ) ) :
/**
 * Display the post thumbnail meta
 * Uses zilla_get_post_thumbnail_data() - theme-functions.php
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_post_thumbnail_meta($id) {

	$meta = zilla_get_post_thumbnail_data( $id );

	if (isset($meta['title']))
		echo '<span class="title">' . $meta['title'] . '</span>';

	if (isset($meta['caption']))
		echo '<span class="description">' . $meta['caption'] . '</span>';
}
endif;


if ( !function_exists( 'zilla_post_gallery' ) ) :
/**
 * Print the HTML for galleries
 *
 * @since 1.0
 *
 * @param int $id ID of the post
 * @param string $imagesize Optional size of image
 * @param string $layout Optional layout format
 * @param int/string $imagesize the image size
 * @return void
 */
function zilla_post_gallery( $postid/* , $imagesize = '', $layout = 'slideshow' */, $size = 'container' ) {

	// Check to see if there is a transient available. If there is, use it.
	if ( false === ( $attachments = get_transient( 'post_attachments_' . $postid ) ) ) {
		zilla_set_attachment_transient($postid);
		$attachments = get_transient( 'post_attachments_' . $postid );
	}

	$transition = get_post_meta($postid, '_zilla_gallery_transition', true);
	$transition = $transition ? $transition : 'fade';

	$timeout = get_post_meta($postid, '_zilla_gallery_timeout', true);
	$timeout = $timeout ? $timeout : '0';

	if( !empty($attachments) ) {
		$gallery_container = '<div id="zilla-gallery-%1$s"
								class="zilla-gallery"
								data-gallery-fx="%2$s"
								data-gallery-timeout="%3$s">';

		printf($gallery_container, $postid, $transition, $timeout);

			echo '<div class="gallery-controls">';
				echo '<div class="cycle-prev"><i class="fa fa-angle-left"></i></div>';
				echo '<div class="cycle-pager"></div>';
				echo '<div class="cycle-next"><i class="fa fa-angle-right"></i></div>';
			echo '</div>';

			foreach ( $attachments as $attachment ) {
				zilla_responsive_image($attachment, 'echo', $size);
			}

		echo '</div>';

		echo '<div class="zilla-gallery-caption"><span class="title"></span><span class="description"></span></div>';

	}
}
endif;


if ( ! function_exists('zilla_post_title') ) :
/**
 * Display the post title
 *
 * @return void
 */
function zilla_post_title() {
	if( is_single() ) { ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
	<?php } else { ?>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	<?php }
}
endif;


if ( !function_exists( 'zilla_gallery' ) ) :
/**
 * Print the HTML for galleries
 *
 * @since versed 1.0
 *
 * @param int $id ID of the post
 * @param string $imagesize Optional size of image
 * @param string $layout Optional layout format
 * @param int/string $imagesize the image size
 * @return void
 */
function zilla_gallery( $postid, $imagesize = '', $layout = 'stacked' ) {

	$image_ids_raw = get_post_meta($postid, '_zilla_image_ids', true);

	if( $image_ids_raw != '' ) {
		// custom gallery created
		$image_ids = explode(',', $image_ids_raw);
		$orderby = 'post__in';
		$post_parent = null;
	} else {
		// pull all images attached to post
		$image_ids = '';
		$orderby = 'menu_order';
		$post_parent = $postid;
	}

	// get the gallery images
	$args = array(
		'include' => $image_ids,
		'numberposts' => -1,
		'orderby' => $orderby,
		'order' => 'ASC',
		'post_type' => 'attachment',
		'post_parent' => $post_parent,
		'post_mime_type' => 'image',
		'post_status' => 'null'
	);
	$attachments = get_posts($args);

	if( !empty($attachments) ) {
		echo "<!--BEGIN #zilla-gallery-$postid -->\n<ul id='zilla-gallery-$postid' class='zilla-gallery $layout'>";

		foreach( $attachments as $attachment ) {
			$src = wp_get_attachment_image_src( $attachment->ID, $imagesize );
			$caption = $attachment->post_excerpt;
			$caption = ($caption) ? "<span class='slide-caption'>$caption</span>" : '';
			$alt = ( !empty($attachment->post_content) ) ? $attachment->post_content : $attachment->post_title;
	            echo "<li><img height='$src[2]' width='$src[1]' src='$src[0]' alt='$alt' />$caption</li>";
		}

		echo '</ul>';

		if( $layout != 'stacked' ) {
			echo '<div class="zilla-slider-nav">';
				echo '<a href="#" id="zilla-slide-prev-'. $postid .'" class="zilla-slide-prev">' . __('Previous', 'zilla') . '</a>';
				echo '<a href="#" id="zilla-slide-next-'. $postid .'" class="zilla-slide-next">' . __('Next', 'zilla') . '</a>';
			echo '</div>';
		}
	}
}
endif;


if ( !function_exists('versed_print_video_html') ) :
/**
 * Prints the WP Vidio Shortcode to output the HTML for video
 * @param  int $postid The post ID
 * @return string         The "html" for printing video elements
 */
function versed_print_video_html($postid) {
	$output = '';

	$posttype = get_post_type($postid);

	$keys = array(
		'post' => array(
			'embed' => '_zilla_video_embed_code',
			'poster' => '_zilla_video_poster_url',
			'm4v' => '_zilla_video_m4v',
			'ogv' => '_zilla_video_ogv',
			'mp4' => 'a_field'
		),
		'portfolio' => array(
			'embed' => '_tzp_video_embed',
			'poster' => '_tzp_video_poster_url',
			'm4v' => '_tzp_video_file_m4v',
			'ogv' => '_tzp_video_file_ogv',
			'mp4' => '_tzp_video_file_mp4'
		)
	);

	$embed = get_post_meta( $postid, $keys[$posttype]['embed'], true);
	if( $embed ) {
		// Output the embed code if provided
		$output .= '<div class="wp-video">' . html_entity_decode( esc_html( $embed ) ) . '</div>';
	} else {
		// Build the video "shortcode"
		$poster = get_post_meta( $postid, $keys[$posttype]['poster'], true );
		$m4v = get_post_meta( $postid, $keys[$posttype]['m4v'], true );
		$ogv = get_post_meta( $postid, $keys[$posttype]['ogv'], true );
		$mp4 = get_post_meta( $postid, $keys[$posttype]['mp4'], true );

		$attr = array('width' => '2000');
		if( $poster ) $attr['poster'] = $poster;
		if( $m4v ) $attr['m4v'] = $m4v;
		if( $ogv ) $attr['ogv'] = $ogv;
		if( $mp4 ) $attr['mp4'] = $mp4;

		$output .= '<div class="wp-video">' . wp_video_shortcode( $attr ) . '</div>';
	}

	return $output;
}
endif;

if ( !function_exists('versed_print_audio_html') ) :
/**
 * Prints the WP Audio Shortcode to output the HTML for audio
 * @param  int $postid The post ID
 * @return string         The "hmtl" for printing audio elements
 */
function versed_print_audio_html($postid) {
	$output = '<div class="post-audio">';

	$posttype = get_post_type($postid);

	$keys = array(
		'post' => array(
			'mp3' => '_zilla_audio_mp3',
			'ogg' => '_zilla_audio_ogg'
		),
		'portfolio' => array(
			'mp3' => '_tzp_audio_file_mp3',
			'ogg' => '_tzp_audio_file_ogg'
		)
	);

	// Print an image if needed
	if( $posttype == 'portfolio' ) {
		$img = get_post_meta( $postid, '_tzp_audio_poster_url', true );
		if( $img ) {
			$output .= '<img src="' . esc_url_raw($img) . '" alt="' . esc_attr( get_the_title($postid) ) . '" />';
		}
	} elseif( has_post_thumbnail($postid) ) {
		if ( (is_singular() || is_blog() || is_search() ) && ! is_page_template('template-home.php') ) {
			$output .= get_the_post_thumbnail($postid, 'l-thumb');
		} elseif (is_page_template('template-home.php')) {
			$output .= '<a class="post-thumb-link" href="' . get_the_permalink() . '">';
			$output .= get_the_post_thumbnail($postid, 'article-full');
			$output .= '</a>';
		} else {
			$output .= get_the_post_thumbnail($postid, 'article-full');
		}

	}

	// Build the "shortcode"
	$mp3 = get_post_meta( $postid, $keys[$posttype]['mp3'], true );
	$ogg = get_post_meta( $postid, $keys[$posttype]['ogg'], true );
	$attr = array();
	if( $mp3 ) $attr['mp3'] = $mp3;
	if( $ogg) $attr['ogg'] = $ogg;

	$output .= wp_audio_shortcode($attr);
	$output .= '</div>';

	return $output;
}
endif;


if ( !function_exists( 'zilla_comment' ) ) :
/**
 * Custom comment HTML output
 *
 * @since 1.0
 *
 * @param $comment
 * @param $args
 * @param $depth
 * @return void
 */
function zilla_comment($comment, $args, $depth) {

	$isByAuthor = false;

	if($comment->comment_author_email == get_the_author_meta('email')) {
		$isByAuthor = true;
	}

	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

		<?php echo get_avatar($comment,$size='60'); ?>

		<div id="comment-<?php comment_ID(); ?>" class="comment-inner">

			<header class="comment-header">
				<div class="comment-meta commentmetadata">
					<span class="comment-author vcard">
						<?php printf(__('<cite class="fn">%s</cite> ', 'zilla'), get_comment_author_link()) ?>
						<?php if($isByAuthor) { ?><span class="author-tag"><?php _e('(Author)', 'zilla') ?></span><?php } ?>
					</span> at
					<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s %2$s', 'zilla'), get_comment_time('g:ia'), get_comment_date('M d Y')) ?></a>
					<?php edit_comment_link(__('(Edit)', 'zilla'),'  ','') ?>
				</div>
			</header>

			<div class="comment-content">
				<?php comment_text() ?>
			</div>

			<footer class="comment-footer">
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</footer>

			<?php if ($comment->comment_approved == '0') : ?>
				<em class="moderation"><?php _e('Your comment is awaiting moderation.', 'zilla') ?></em><br />
			<?php endif; ?>

		</div>
<?php
}
endif;


if ( !function_exists( 'zilla_list_pings' ) ) :
/**
 * Separate pings from comments
 *
 * @since versed 1.0
 *
 * @param $comment
 * @param $args
 * @param $depth
 * @return void
 */
function zilla_list_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
	<?php
}
endif;


if( ! function_exists( 'zilla_paging_nav' ) ) :
/**
 * Display navigation to next/prev if needed
 *
 * @since versed 1.0
 *
 * @return void
 */
function zilla_paging_nav() {
	global $wp_query;

	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;
	?>

	<!--BEGIN .navigation .page-navigation -->
	<div class="navigation page-navigation block" role="navigation">
	<?php if( get_next_posts_link() ) { ?>
		<div class="nav-previous"><?php next_posts_link(__('&larr; Older Entries', 'zilla')) ?></div>
	<?php } ?>

	<?php if( get_previous_posts_link() ) { ?>
		<div class="nav-next"><?php previous_posts_link(__('Newer Entries &rarr;', 'zilla')) ?></div>
	<?php } ?>
	<!--END .navigation .page-navigation -->
	</div>

	<?php
}
endif;


if( !function_exists('zilla_post_meta_header') ) :
/**
 * Print HTML meta information for current post
 *
 * @since versed 1.0
 *
 * @return void
 */
function zilla_post_meta_header() {
?>
	<!--BEGIN .entry-meta-->
	<div class="entry-meta">
		<span class="author-avatar">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), 30 ); ?>
		</span><!-- .author-avatar -->
		<?php
		printf( '<span class="author">%1$s <a class="accent-color" href="%2$s" title="%3$s" rel="author">%4$s</a></span>',
			'',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __('View all posts by %s', 'zilla' ), get_the_author() ) ),
			get_the_author()
		);
		?>

		<?php if (! is_page()) : ?>
			<span class="entry-categories"><?php _e('in', 'zilla') ?> <?php the_category(', ') ?></span>
		<?php endif; ?>

		<?php edit_post_link( __('Edit', 'zilla'), '<span class="edit-post">', '</span>' ); ?>
	<!--END .entry-meta -->
	</div>
<?php
}
endif;

if( !function_exists('zilla_post_meta_footer') ) :
/**
 * Print HTML meta information for current post
 *
 * @since versed 1.0
 *
 * @return void
 */
function zilla_post_meta_footer() {
?>
	<!--BEGIN .entry-meta-->
	<div class="entry-meta">
		<?php
		printf( '<span class="author">%1$s <a href="%2$s" title="%3$s" rel="author">%4$s</a></span>',
			__('Written by', 'zilla' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __('View all posts by %s', 'zilla' ), get_the_author() ) ),
			get_the_author()
		);
		?>
		<span class="entry-categories"><?php _e('in', 'zilla') ?> <?php the_category(', ') ?></span>
		<span class="entry-date">on <a href="<?php the_permalink(); ?>"><?php the_date('M d, Y'); ?></a></span>
	<!--END .entry-meta-->
	</div>

	<span class="entry-tags"><?php the_tags(_n('Tagged', 'Tagged', '', 'zilla').' ', ', ', ''); ?>.</span>
<?php
}
endif;


if( ! function_exists( 'zilla_author_bio' ) ) :
/**
 * Display the author bio
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_author_bio() {
?>
	<!--BEGIN .author-bio-->
	<div class="author-bio">
		<?php echo get_avatar( get_the_author_meta('email'), '90' ); ?>
	<!--END .author-bio-->
	</div>
<?php
}
endif;


if( ! function_exists( 'zilla_share_links' ) ) :
/**
 * Prints the share links
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_share_links() {

	$image_id = get_post_thumbnail_id();
	$image_url = wp_get_attachment_image_src($image_id,'article-full', true);
?>
	<!--BEGIN .share-bar-->
	<div class="share-bar" data-url="<?php the_permalink(); ?>" data-media="<?php echo $image_url[0]; ?>" data-description="<?php the_title(); ?>">
		<span class="share-links">

		</span>
		<a class="fa fa-envelope-o share-link" href="mailto:?subject=<?php the_title(); ?>&body=<?php the_permalink(); ?>"></a>
	<!--END .share-bar-->
	</div>

<?php }
endif;


if( ! function_exists( 'zilla_comments_link' ) ) :
/**
 * Prints the comments links
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_comments_link($post_ID) {
?>
	<!--BEGIN .comments-links-->
	<div class="comments-link">
		<?php if (! zilla_get_mod('read_time', false) || zilla_get_mod('read_time', false) == true): ?>
		<span class="read-time"><span data-word-count="<?php echo zilla_word_count(); ?>" class="time"></span> <?php _e('min read', 'zilla') ?></span>
		<?php endif; ?>

		<a href="<?php echo get_permalink($post_ID); ?>/#comments"><?php echo get_comments_number($post_ID); ?></a>
	<!--END .comments-links-->
	</div>

<?php }
endif;


if( !function_exists('zilla_link_format') ) :
/**
 * Print HTML for the link post format
 *
 * @since versed 1.0
 *
 * @return void
 */
function zilla_link_format($post_ID) {
	$link = get_post_meta( $post_ID, '_zilla_link_url', true );
	$title = get_post_meta( $post_ID, '_zilla_link_title', true );
	//if there's no title, use the url
	$title = $title ? $title : $link;
	if( $link ) { ?>
		<div class="entry-link">
			<i class="fa fa-link fa-flip-horizontal accent-color"></i><a href="<?php echo esc_url( $link ); ?>"><?php echo $title; ?></a>
		</div>
	<?php }
}
endif;


if( !function_exists('zilla_quote_format') ) :
/**
 * Print HTML for the quote post format
 *
 * @since versed 1.0
 *
 * @return void
 */
function zilla_quote_format($post_ID) {
	$quote = get_post_meta( $post_ID, '_zilla_quote_quote', true );
	$author = get_post_meta( $post_ID, '_zilla_quote_author', true );

	if( $quote ) {
		?>
			<blockquote class="entry-quote">
				<?php echo $quote; ?>
				<?php if( $author ) { ?><cite><?php echo $author; ?></cite><?php } ?>
			</blockquote>
		<?php
	}
}
endif;


if( !function_exists('zilla_post_format_icon') ) :
/**
 * Print HTML for the post format icon
 *
 * @since versed 1.0
 *
 * @return void
 */
function zilla_post_format_icon() {
	$format = get_post_format();

	$formats = array(
		'audio' => 'fa-volume-up',
		'link' => 'fa-link',
		'quote' => 'fa-quote-right',
		'video' => 'fa-play-circle-o'
	);

	echo '<i class="fa ' . $formats[$format] . '"></i>';
}
endif;


if( ! function_exists( 'zilla_home_featured_post' ) ) :
/**
 * Prints the featured post style
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_home_featured_post($post_ID, $responsive = true) {
	$format = get_post_format();

	if ( has_post_thumbnail() ) {
		echo '<a class="post-thumb-link" href="' . get_permalink() . '">';
		if ($responsive === true) {
			echo '<figure class="entry-thumbnail">';
				zilla_post_thumbnail( get_the_ID() );
			echo '</figure>';
		} else {
			$image_id = get_post_thumbnail_id($post_ID);
			$image_url = wp_get_attachment_image_src($image_id,'article-full', true);

			echo '<figure class="entry-thumbnail" style="background-image: url(' . $image_url[0] . ');"></figure>';
		}
		echo '</a>';

		if ($format === 'audio') {
			echo versed_print_audio_html($post_ID);
		}
	} else {
		if ($format === 'video') {
			echo versed_print_video_html($post_ID);
		}
	}
	?>

	<?php
		if ($format === 'link') {
			zilla_link_format(get_the_ID());
		} else if ($format === 'quote') {
			zilla_quote_format(get_the_ID());
		}
	?>

	<!--BEGIN .entry-header-->
	<header class="entry-header">

		<?php zilla_post_title(); ?>

		<div class="entry-summary <?php if (has_excerpt()) { echo 'excerpt'; } ?>"><?php the_excerpt(); ?></div>

		<div class="entry-info">
			<?php zilla_post_meta_header(); ?>

			<?php zilla_comments_link($post_ID); ?>
		</div>

	<!--END .entry-header-->
	</header>

	<?php
}
endif;


if( ! function_exists( 'zilla_home_recent_post' ) ) :
/**
 * Prints the recent post style
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_home_recent_post($post_ID) {
	$format = get_post_format();

	zilla_comments_link($post_ID);

	echo '<span class="entry-categories">';
		the_category(', ');
	echo '</span>';

	if ( has_post_thumbnail() ) {
		if ($format === 'audio' && zilla_get_mod('homepage_recent_layout', false) === 'grid') {
			echo versed_print_audio_html($post_ID);
		} else {
			echo '<a class="post-thumb-link" href="' . get_permalink() . '">';
				echo '<figure class="entry-thumbnail">';
					the_post_thumbnail( 'article-full' );
				echo '</figure>';
			echo '</a>';
		}
	} else {
		if ( zilla_get_mod('homepage_recent_layout', false) === 'list' ) {
			$formats = array('audio', 'link', 'quote', 'video');
			if (in_array($format, $formats)) {
				echo '<a class="post-thumb-link accent-color" href="' . get_permalink() . '">';
					zilla_post_format_icon();
				echo '</a>';
			}
		} else {
			if ($format === 'video') {
				echo versed_print_video_html($post_ID);
			}
		}
	}
	?>

	<!--BEGIN .entry-header-->
	<header class="entry-header">

		<?php zilla_post_title(); ?>

		<?php if ( has_excerpt() && !has_post_thumbnail() && zilla_get_mod('homepage_recent_layout', false) === 'grid' ) : ?>
			<div class="entry-summary excerpt"><?php the_excerpt(); ?></div>
		<?php endif; ?>

		<div class="excerpt-meta">
		<?php
		printf( '<span class="entry-author">%1$s <a class="accent-color" href="%2$s" title="%3$s" rel="author">%4$s</a></span>',
			__('by', 'zilla' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __('View all posts by %s', 'zilla' ), get_the_author() ) ),
			get_the_author()
		);

		echo '<span class="entry-categories">' . '&nbsp;' . __('in', 'zilla') . ' ';
			the_category(', ');
		echo '</span>';
		?>
		</div>

		<div class="entry-summary <?php if (!has_excerpt() && !has_post_thumbnail()) { echo 'excerpt'; } ?>">
			<?php
				if ($format === 'link') {
					zilla_link_format($post_ID);
				} else if ($format === 'quote') {
					zilla_quote_format($post_ID);
				}

				if ( zilla_get_mod('homepage_recent_layout', false) === 'grid' || ! zilla_get_mod_state('homepage_recent_layout') ) {
					echo wp_trim_excerpt();
				} elseif (has_excerpt()) {
					the_excerpt();
				}

			?>
		</div>

	<!--END .entry-header-->
	</header>
<?php
}
endif;


if( ! function_exists( 'zilla_print_home_featured_post' ) ) :
/**
 * Prints the featured post on the homepage
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_print_home_featured_post() {
	// Get the post for the featured spot from the transient.
	$the_query = zilla_has_featured_posts( 'post_query' );

	// The Loop
	if ( $the_query->have_posts() ) {
		$i = 1;
		$layout = zilla_get_mod('homepage_layout', false);

		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$classes = array();
			array_push($classes, 'featured-post', 'featured-post-' . $i);

			switch ($layout) {
				case 'one':
					$classes[] = 'featured-layout-one';
					break;
				case 'two':
					$classes[] = 'featured-layout-two';
					break;
				case 'three_equal':
					$classes[] = 'featured-layout-three-equal';
					break;
				case 'three_wide_left':
					$classes[] = 'featured-layout-three-wide-left';
					break;
				case 'three_wide_right':
					$classes[] = 'featured-layout-three-wide-right';
					break;
				case 'four':
					$classes[] = 'featured-layout-four';
					break;
				default:
					$classes[] = 'featured-layout-one';
			}

			if ($i !== 1) { $classes[] = 'featured-post-secondary'; }

			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

			<?php zilla_home_featured_post(get_the_ID()); ?>

			<?php if ($i === 1) { zilla_share_links(); } ?>

			</article>
			<?php

			$i++;
		}
	}

	wp_reset_postdata();
}
endif;


if( ! function_exists( 'zilla_print_home_recent_posts' ) ) :
/**
 * Prints the recent posts on the homepage
 *
 * @package versed
 * @since versed 1.0
 *
 * @return void
 */
function zilla_print_home_recent_posts() {
	$num_posts = zilla_get_mod('homepage_number_recent_posts', false) ? zilla_get_mod('homepage_number_recent_posts', false) : 12;
	$args = array(
		'posts_per_page'   => $num_posts,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'ignore_sticky_posts'	=> true
	);

	// If there's no featured posts, start the recent posts after the offset,
	// since we are going to put the most recent posts in the featured spots
	if ( ! zilla_has_featured_posts() ) {
		$args['offset'] = zilla_get_featured_number();

	// If there are featured posts, make sure the one that's in the featured spot doesn't appear in the recent list.
	} else {
		$the_query = zilla_has_featured_posts( 'post_query' );
		$exclude = array();
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$exclude[] = get_the_ID();
		}
		$args['post__not_in'] = $exclude;
	}

	// The Query
	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) {

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php zilla_home_recent_post(get_the_ID()); ?>

			</article>

			<?php
		}
	}

	wp_reset_postdata();
}
endif;
