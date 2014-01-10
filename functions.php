<?php
/*
	This file serves as a demonstration for theme developers for
	how to build their own child themes on top of Bizznis theme.
	Some of the most common functions are included, however
	you can edit partically every aspect of the parent theme.
*/

//* This file calls the init.php file inside parent Bizznis theme (do not remove)
load_template( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Bizznis Child Theme', 'bizznis-child' ) );
define( 'CHILD_THEME_URL', 'https://github.com/bizzthemes/bizznis-child/' );
define( 'CHILD_THEME_VERSION', '1.0' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'bizznis-child', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'bizznis-child' ) );

//* Add a custom post type example
add_action( 'init', 'portfolio_post_type' );
function portfolio_post_type() {
  // Portfolio custom post type
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolio', 'bizznis-child' ),
				'singular_name' => __( 'Portfolio', 'bizznis-child' ),
			),
			'has_archive' => true,
			'public' => true,
			'show_ui' => true, // defaults to true so don't have to include
			'show_in_menu' => true, // defaults to true so don't have to include
			'rewrite' => array( 'slug' => 'portfolio' ),
			'supports' => array( 'title', 'editor', 'thumbnail', 'bizznis-seo', 'bizznis-scripts', 'bizznis-layouts' ),
		)
	);
}

//* Add support for additional color style options
add_theme_support( 'bizznis-style-selector', array(
	'bizznis-child-green'	=>	__( 'Bizznis Child Green Style', 'bizznis-child' ),
) );

//* Unregister layout settings
bizznis_unregister_layout( 'content-sidebar-sidebar' );
bizznis_unregister_layout( 'sidebar-content-sidebar' );
bizznis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Add support for footer widgets
add_theme_support( 'bizznis-footer-widgets', 4 );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'					=> 320,
	'flex-width'			=> true,
	'height'				=> 80,
	'flex-height'			=> true,
	'default-text-color'	=> '333333',
	'header-selector'		=> '.title-area',
) );

//* Add support for custom background
add_theme_support( 'custom-background', array(
	'default-color' => 'f9f8f2',
	'default-image' => CHILD_URL . '/images/background.png',
) );

//* Add custom settings inside Theme Settings
add_filter( 'bizznis_theme_settings_defaults', 'bizznis_social_defaults' );
function bizznis_social_defaults( $defaults ) {
	$defaults['twitter_url'] = 'http://twitter.com/BizzThemes';
	$defaults['facebook_url'] = '';
	return $defaults;
}

//* Sanitize custom settings
add_action( 'bizznis_settings_sanitizer_init', 'bizznis_register_social_sanitization_filters' );
function bizznis_register_social_sanitization_filters() {
	bizznis_add_option_filter( 'url', BIZZNIS_SETTINGS_FIELD,
		array(
			'twitter_url',
			'facebook_url',
		) );
}

//* Register custom settings
add_action( 'admin_init', 'bizznis_initialize_theme_options' );  
function bizznis_initialize_theme_options() {  
    add_settings_section(  
        'general_settings_section',         	// ID used to identify this section and with which to register options  
        'Sandbox Options',                  	// Title to be displayed on the administration page  
        'bizznis_general_options_callback', 	// Callback used to render the description of the section  
        'bizznis'                         		// Page ID on which to add this section of options  
    );  
}

//* Custom settings option fields
function bizznis_general_options_callback() {  
    ?>
	<p>Twitter URL:<br />
	<input type="text" name="<?php echo BIZZNIS_SETTINGS_FIELD; ?>[twitter_url]" value="<?php echo esc_attr( bizznis_get_option('twitter_url') ); ?>" size="50" /> </p>
	<p>Facebook URL:<br />
	<input type="text" name="<?php echo BIZZNIS_SETTINGS_FIELD; ?>[facebook_url]" value="<?php echo esc_attr( bizznis_get_option('facebook_url') ); ?>" size="50" /> </p>
	<?php  
}

//* Display custom settings inside theme Header
//* Look for structural hooks inside 'bizznis/lib/theme/structure.php' and all other hook inside 'bizznis/lib/theme/' folder
add_action('bizznis_header_aside', 'bizznis_display_social_options');
function bizznis_display_social_options() {  
    $twitter = bizznis_get_option('twitter_url');
	$facebook = bizznis_get_option('facebook_url');
	if ( $twitter ) {
		?>
		<a href="<?php echo $twitter; ?>">My Twitter Page</a>
		<?php
	}
	if ( $facebook ) {
		?>
		<a href="<?php echo $facebook; ?>">My Facebook Page</a>
		<?php
	}
}