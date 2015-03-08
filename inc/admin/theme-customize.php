<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since Versed 1.0
 */

add_action('customize_register', 'zilla_customize_register');
function zilla_customize_register($wp_customize) {

  class Zilla_Customize_Textarea_Control extends WP_Customize_Control {
    public $type = 'textarea';

    public function render_content() {
      ?>
      <label>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <textarea style="width:100%" rows="8" <?php $this->link(); ?>><<?php echo esc_textarea( $this->value() ); ?></textarea>
      </label>
      <?php
    }
  }

//=====================================================
//! General Controls
//=====================================================
  $wp_customize->add_section(
    'zilla_general_options',
     array(
        'title' => __( 'General Options', 'zilla' ),
        'priority' => 10,
        'capability' => 'edit_theme_options',
        'description' => __('Control and configure the general setup of your theme. Upload your preferred logo, setup your feeds and insert your analytics tracking code.', 'zilla')
     )
  );

  $wp_customize->add_setting(
    'zilla_theme_options[general_text_logo]',
    array( 'default' => '0', 'sanitize_callback' => 'versed_sanitize_checkbox' )
  );

  $wp_customize->add_control( 'zilla_general_text_logo', array(
    'label' => __( 'Plain Text Logo', 'zilla' ),
    'section' => 'zilla_general_options',
    'settings' => 'zilla_theme_options[general_text_logo]',
    'type' => 'checkbox'
  ));

  $wp_customize->add_setting(
    'zilla_theme_options[general_custom_logo]',
    array(
      'default' => get_template_directory_uri() . '/assets/img/logo.png',
      'transport' => 'postMessage',
      'sanitize_callback' => 'esc_url_raw'
    )
  );

  $wp_customize->add_control( new WP_Customize_Image_Control(
    $wp_customize,
    'zilla_general_custom_logo',
    array(
      'label' => __( 'Logo Upload (optimal height is 60px; 120px for retina).', 'zilla' ),
      'section' => 'zilla_general_options',
      'settings' => 'zilla_theme_options[general_custom_logo]'
    )
  ));
  
  $wp_customize->add_setting(
    'zilla_theme_options[general_custom_logo_light]',
    array(
      'default' => get_template_directory_uri() . '/assets/img/logo.png',
      'transport' => 'postMessage',
      'sanitize_callback' => 'esc_url_raw'
    )
  );

  $wp_customize->add_control( new WP_Customize_Image_Control(
    $wp_customize,
    'zilla_general_custom_logo_light',
    array(
      'label' => __( 'Logo Upload - light version (optimal height is 60px; 120px for retina).', 'zilla' ),
      'section' => 'zilla_general_options',
      'settings' => 'zilla_theme_options[general_custom_logo_light]'
    )
  ));
  
  $wp_customize->add_setting(
    'zilla_theme_options[retina_logo]',
    array( 
    	'default' => false,
    	'sanitize_callback' => 'versed_sanitize_checkbox'
    )
  );

  $wp_customize->add_control( 'zilla_retina_logo', array(
    'label' => __( 'Retina Logo (image @2x)', 'zilla' ),
    'section' => 'zilla_general_options',
    'settings' => 'zilla_theme_options[retina_logo]',
    'type' => 'checkbox'
  ));

  $wp_customize->add_setting(
    'zilla_theme_options[general_custom_favicon]',
     array( 
     	'default' => '',
     	'sanitize_callback' => 'esc_url_raw'
     )
  );

  $wp_customize->add_control( new WP_Customize_Image_Control(
    $wp_customize,
    'zilla_general_custom_favicon',
    array(
      'label' => __( 'Favicon Upload (16x16 image file)', 'zilla' ),
      'section' => 'zilla_general_options',
      'settings' => 'zilla_theme_options[general_custom_favicon]'
    )
  ));

  $wp_customize->add_setting(
    'zilla_theme_options[general_contact_email]',
    array( 
    	'type' => 'option',
		'sanitize_callback' => 'versed_sanitize_text' 
	)
  );

  $wp_customize->add_control( new WP_Customize_Control(
    $wp_customize,
    'zilla_general_contact_email',
    array(
      'label' => __( 'Contact Form Email Address', 'zilla' ),
      'section' => 'zilla_general_options',
      'settings' => 'zilla_theme_options[general_contact_email]'
    )
  ));
  
  $wp_customize->add_setting(
    'zilla_theme_options[offcanvas_icon]',
    array( 
    	'default' => 'fa-bars',
    	'sanitize_callback' => 'versed_sanitize_text'
    )
  );

  $wp_customize->add_control( new WP_Customize_Control(
    $wp_customize,
    'zilla_offcanvas_icon',
    array(
      'label' => __( 'Offcanvas panel toggle icon. Use any Font Awesome icon by entering the icon class code found at http://fortawesome.github.io/Font-Awesome/icons/', 'zilla' ),
      'section' => 'zilla_general_options',
      'settings' => 'zilla_theme_options[offcanvas_icon]'
    )
  ));
  
  $wp_customize->add_setting(
    'zilla_theme_options[offcanvas_background]',
    array(
      'default' => get_template_directory_uri() . '/assets/img/bg-nav.jpg',
      'transport' => 'postMessage',
      'sanitize_callback' => 'esc_url_raw'
    )
  );

  $wp_customize->add_control( new WP_Customize_Image_Control(
    $wp_customize,
    'zilla_offcanvas_background',
    array(
      'label' => __( 'Off-canvas Panel Background Image', 'zilla' ),
      'section' => 'zilla_general_options',
      'settings' => 'zilla_theme_options[offcanvas_background]'
    )
  ));
  
  $wp_customize->add_setting( 'zilla_theme_options[nav_position]',
     array(
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
        'default' => 'nav-top',
        'sanitize_callback' => 'versed_sanitize_nav_position'
     )
  );

  $wp_customize->add_control( new WP_Customize_Control(
    $wp_customize,
    'zilla_nav_position',
    array(
      'label' => __( 'Choose where the main menu should be displayed.', 'zilla' ),
      'section' => 'zilla_general_options',
      'settings' => 'zilla_theme_options[nav_position]',
      'type' => 'radio',
      'choices' => array(
	      'nav-top' => 'Top bar for larger screens and off-canvas panel for smaller screens',
	      'nav-side' => 'Off-canvas panel for all screens',
      )
    )
  ));
  
  $wp_customize->add_setting( 'zilla_theme_options[blog_layout]',
     array(
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
        'default' => 'blog-list-standard',
        'sanitize_callback' => 'versed_sanitize_blog_layout'
     )
  );

  $wp_customize->add_control( new WP_Customize_Control(
    $wp_customize,
    'zilla_blog_layout',
    array(
      'label' => __( 'Choose the layout for the blog page.', 'zilla' ),
      'section' => 'zilla_general_options',
      'settings' => 'zilla_theme_options[blog_layout]',
      'type' => 'radio',
      'choices' => array(
	      'blog-list-standard' => 'Standard list',
	      'blog-list-compressed' => 'Compressed list',
      )
    )
  ));
  
  $wp_customize->add_setting(
    'zilla_theme_options[read_time]',
    array( 
    	'default' => true,
    	'sanitize_callback' => 'versed_sanitize_checkbox'
    )
  );

  $wp_customize->add_control( 'zilla_read_time', array(
    'label' => __( 'Show the estimated read time for posts.', 'zilla' ),
    'section' => 'zilla_general_options',
    'settings' => 'zilla_theme_options[read_time]',
    'type' => 'checkbox'
  ));

  /* Style Options --- */
  $wp_customize->add_section(
    'zilla_style_options',
    array(
      'title' => __( 'Style Options', 'zilla' ),
      'priority' => 10,
      'capability' => 'edit_theme_options',
      'description' => __('Give your site a custom coat of paint by updating the style options.', 'zilla')
    )
  );

  $wp_customize->add_setting(
    'zilla_theme_options[style_accent_color]',
    array(
      'default' => '#0074d9',
      'transport' => 'postMessage',
      'sanitize_callback' => 'versed_sanitize_text'
    )
  );

  $wp_customize->add_control( new WP_Customize_Color_Control(
    $wp_customize,
    'zilla_style_accent_color',
    array(
      'label' => __( 'Accent Color', 'zilla' ),
      'section' => 'zilla_style_options',
      'settings' => 'zilla_theme_options[style_accent_color]'
    )
  ));

  $wp_customize->add_setting( 
  	'zilla_theme_options[style_custom_css]', 
  	array(
  		'default' => '',
  		'sanitize_callback' => 'wp_filter_nohtml_kses'
  	)
  );

  $wp_customize->add_control( new Zilla_Customize_Textarea_Control(
    $wp_customize,
    'zilla_style_custom_css',
    array(
      'label' => __( 'Custom CSS', 'zilla' ),
      'section' => 'zilla_style_options',
      'settings' => 'zilla_theme_options[style_custom_css]',
      )
    ));

//=====================================================
//! Homepage Controls
//=====================================================
  $wp_customize->add_section(
    'zilla_homepage_options',
     array(
        'title' => __( 'Homepage Options', 'zilla' ),
        'priority' => 10,
        'capability' => 'edit_theme_options',
        'description' => __('Customize the homepage layout.', 'zilla')
     )
  );

  $wp_customize->add_setting( 'zilla_theme_options[homepage_layout]',
     array(
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'default' => 'one',
        'sanitize_callback' => 'versed_sanitize_home_layout'
     )
  );

  $wp_customize->add_control( new WP_Customize_Control(
    $wp_customize,
    'zilla_homepage_layout',
    array(
      'label' => __( 'Layout for the featured posts section of the homepage.', 'zilla' ),
      'section' => 'zilla_homepage_options',
      'settings' => 'zilla_theme_options[homepage_layout]',
      'type' => 'radio',
      'choices' => array(
	      'one' => 'One post',
	      'two' => 'Two posts',
	      'three_equal' => 'Three posts, bottom two equal widths',
	      'three_wide_left' => 'Three posts, bottom left is wider',
	      'three_wide_right' => 'Three posts, bottom right is wider',
	      'four' => 'Four posts, bottom three are equal'
      )
    )
  ));
  
  $wp_customize->add_setting(
    'zilla_theme_options[homepage_number_recent_posts]',
	array( 
		'default' => '12',
		'sanitize_callback' => 'absint'
	)
  );

  $wp_customize->add_control( new WP_Customize_Control(
    $wp_customize,
    'zilla_homepage_recent_posts',
    array(
      'label' => __( 'Number of posts to feature on the "recent posts" section of the homepage', 'zilla' ),
      'section' => 'zilla_homepage_options',
      'settings' => 'zilla_theme_options[homepage_number_recent_posts]'
    )
  ));
  
  $wp_customize->add_setting( 'zilla_theme_options[homepage_recent_layout]',
     array(
        'capability' => 'edit_theme_options',
        'transport' => 'postMessage',
        'default' => 'grid',
        'sanitize_callback' => 'versed_sanitize_home_recent_layout'
     )
  );

  $wp_customize->add_control( new WP_Customize_Control(
    $wp_customize,
    'zilla_homepage_recent_layout',
    array(
      'label' => __( 'Layout for the recent posts section of the homepage.', 'zilla' ),
      'section' => 'zilla_homepage_options',
      'settings' => 'zilla_theme_options[homepage_recent_layout]',
      'type' => 'radio',
      'choices' => array(
	      'grid' => 'Grid',
	      'list' => 'List'
      )
    )
  ));
  

  if( $wp_customize->is_preview() && ! is_admin() )
    add_action('wp_footer', 'zilla_live_preview', 21);
}

/**
* This outputs the javascript needed to automate the live settings preview.
*
*/
function zilla_live_preview() {
  ?>
    <script type="text/javascript">
    ( function( $ ) {

      wp.customize( 'zilla_theme_options[general_custom_logo]', function( value ) {
        value.bind( function( newval ) {
          console.log(newval);
          $('#logo img').attr('src', newval);
        });
      });

      wp.customize( 'zilla_theme_options[style_accent_color]', function( value ) {
        value.bind( function( newval ) {
          $('.accent-color').css('color', newval );
        } );
      } );

    } )( jQuery );
  </script>
  <?php
}

/**
* This will output the custom WordPress settings to the live theme's WP head.
*
*/
function header_output() {

  $theme_options = get_theme_mod('zilla_theme_options');

  /* Output the favicon */
  if( !empty($theme_options) && array_key_exists( 'general_custom_favicon', $theme_options ) && $theme_options['general_custom_favicon'] != '' ) {
    echo '<link rel="shortcut icon" href="'. $theme_options['general_custom_favicon'] .'" />' . "\n";
  }
  
	if( zilla_get_mod_state('style_accent_color') && $theme_options['style_accent_color'] != '#0074d9' ) {
		$color = array('.accent-color', 'a.accent-color:hover', '.comments-link a:hover');
		$border = array('.comments-link a:hover');
		$background = array('.post-preview .comments-link a:hover', 'html body .site a.zilla-button.accent');
		
		echo "<!-- Custom Accent Color -->\n<style type='text/css'>\n";
		
		foreach($color as $c) {
			generate_css($c, 'color', 'style_accent_color');
		}
		
		foreach($border as $bc) {
			generate_css($bc, 'border-color', 'style_accent_color');
		}
		
		foreach($background as $bg) {
			generate_css($bg, 'background-color', 'style_accent_color');
		}
						
		echo '.comments-link a:hover:before { border-color: ' . zilla_get_mod('style_accent_color', false) . ' transparent transparent transparent; }';
		echo '.post-preview .comments-link a:hover:before { border-color: ' . zilla_get_mod('style_accent_color', false) . ' transparent transparent transparent; }';
		
		echo 'body a.zilla-button.border { border-color: ' . zilla_get_mod('style_accent_color', false) . ' !important; }';
		echo 'body a.zilla-button.border { color: ' . zilla_get_mod('style_accent_color', false) . ' !important; }';
		
		echo "</style>\n<!-- /Custom Accent Color -->\n";
	}
}

/**
 * This will generate a line of CSS for use in header output. If the setting
 * ($mod_name) has no defined value, the CSS will not be output.
 *
 * @uses get_theme_mod()
 * @param string $selector CSS selector
 * @param string $style The name of the CSS *property* to modify
 * @param string $mod_name The name of the 'theme_mod' option to fetch
 * @param string $prefix Optional. Anything that needs to be output before the CSS property
 * @param string $postfix Optional. Anything that needs to be output after the CSS property
 * @param bool $echo Optional. Whether to print directly to the page (default: true).
 * @return string Returns a single line of CSS with selectors and a property.
 * @since MyTheme 1.0
 */
function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
  $return = '';
  $mods = get_theme_mod('zilla_theme_options');
  $mod = $mods[$mod_name];
  if ( ! empty( $mod ) ) {
     $return = sprintf('%s { %s:%s; }',
        $selector,
        $style,
        $prefix.$mod.$postfix
     );
     if ( $echo ) {
        echo $return;
     }
  }
  return $return;
}
// Output custom CSS to live site
add_action( 'wp_head' , 'header_output' );


function versed_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}

function versed_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

function versed_sanitize_home_layout( $input ) {
	$valid = array(
		'one' => 'One post',
		'two' => 'Two posts',
		'three_equal' => 'Three posts, bottom two equal widths',
		'three_wide_left' => 'Three posts, bottom left is wider',
		'three_wide_right' => 'Three posts, bottom right is wider',
		'four' => 'Four posts, bottom three are equal'
	);
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

function versed_sanitize_home_recent_layout( $input ) {
	$valid = array(
		'grid' => 'Grid',
		'list' => 'List'
	);
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}


function versed_sanitize_nav_position( $input ) {
	$valid = array(
      'nav-top' => 'Top bar for larger screens and off-canvas panel for smaller screens',
      'nav-side' => 'Off-canvas panel for all screens',
	);
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

function versed_sanitize_blog_layout( $input ) {
	$valid = array(
		'blog-list-standard' => 'Standard list',
		'blog-list-compressed' => 'Compressed list',
	);
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}