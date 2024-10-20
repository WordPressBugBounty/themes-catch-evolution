<?php
/**
 * Catch Evolution functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, catchevolution_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'catchevolution_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Catch Evolution
 */


if ( ! function_exists( 'catchevolution_content_width' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function catchevolution_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'catchevolution_content_width', 678 );
	}
endif;
add_action( 'after_setup_theme', 'catchevolution_content_width', 0 );


if ( ! function_exists( 'catchevolution_template_redirect' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet for different value other than the default one
	 *
	 * @global int $content_width
	 */
	function catchevolution_template_redirect() {
	    $layout = catchevolution_get_theme_layout();

	    if ( 'three-columns' == $layout ) {
			$GLOBALS['content_width'] = 454; /* pixels */
		}
	}
endif;
add_action( 'template_redirect', 'catchevolution_template_redirect' );


/**
 * Tell WordPress to run catchevolution_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'catchevolution_setup' );


if ( ! function_exists( 'catchevolution_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override catchevolution_setup() in a child theme, add your own catchevolution_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,custom headers and backgrounds.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Catch Evolution 1.0
 */
function catchevolution_setup() {
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Catch Evolution, use a find and replace
	 * to change 'catch-evolution' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'catch-evolution', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	/**
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image', 'chat' ) );

	// Load up theme options defaults
	require( get_template_directory() . '/inc/panel/catchevolution-themeoptions-defaults.php' );

	// Register Sidebar and Widget.
	require( get_template_directory() . '/inc/catchevolution-widgets.php' );

	// Load up our Catch Evolution Pro's Functions
	require( get_template_directory() . '/inc/catchevolution-functions.php' );

	// Load up our Catch Evolution Pro's metabox
	require( get_template_directory() . '/inc/catchevolution-metabox.php' );

	/**
     * This feature enables Jetpack plugin Infinite Scroll
     */
    add_theme_support( 'infinite-scroll', array(
		'type'           => 'click',
        'container'      => 'content',
        'footer_widgets' => array( 'sidebar-2', 'sidebar-3', 'sidebar-4' ),
        'footer'         => 'page',
    ) );

	/**
     * This feature enables custom-menus support for a theme.
     * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
     */
	register_nav_menus(array(
		'top' 		=> __( 'Fixed Header Top Menu', 'catch-evolution' ),
		'primary' 	=> __( 'Primary Menu', 'catch-evolution' ),
	   	'secondary'	=> __( 'Secondary Menu', 'catch-evolution' ),
		'footer'	=> __( 'Footer Menu', 'catch-evolution' )
	) );

	// Add support for custom backgrounds
	add_theme_support( 'custom-background' );

	/**
     * This feature enables post-thumbnail support for a theme.
     * @see http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
	add_theme_support( 'post-thumbnails' );

	//Featued Posts for Normal Width
	add_image_size( 'featured-slider', 754, 400, true ); // Used for featured posts if a large-feature doesn't exist

	//Featured Posts for Full Width
	add_image_size( 'featured-slider-larger', 1190, 500, true ); // Used for featured posts if a large-feature doesn't exist

	//Featured Posts for Header Width
	add_image_size( 'featured-slider-header', 1920, 480, true ); // Used for featured posts for header image size

	//Plugin Support for WooCommerce
	catchevolution_woocommerce_activated();

	//@remove Remove check when WordPress 4.8 is released
	if ( function_exists( 'has_custom_logo' ) ) {
		/**
		* Setup Custom Logo Support for theme
		* Supported from WordPress version 4.5 onwards
		* More Info: https://make.wordpress.org/core/2016/03/10/custom-logo/
		*/
		add_theme_support( 'custom-logo' );
	}

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	/**
     * Add callback for custom TinyMCE editor stylesheets. (editor-style.css)
     * @see http://codex.wordpress.org/Function_Reference/add_editor_style
     */
	add_editor_style( 'css/editor-style.css' );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add custom editor font sizes.
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => __( 'Small', 'catch-evolution' ),
				'shortName' => __( 'S', 'catch-evolution' ),
				'size'      => 13,
				'slug'      => 'small',
			),
			array(
				'name'      => __( 'Normal', 'catch-evolution' ),
				'shortName' => __( 'M', 'catch-evolution' ),
				'size'      => 16,
				'slug'      => 'normal',
			),
			array(
				'name'      => __( 'Large', 'catch-evolution' ),
				'shortName' => __( 'L', 'catch-evolution' ),
				'size'      => 28,
				'slug'      => 'large',
			),
			array(
				'name'      => __( 'Huge', 'catch-evolution' ),
				'shortName' => __( 'XL', 'catch-evolution' ),
				'size'      => 36,
				'slug'      => 'huge',
			),
		)
	);
}
endif; // catchevolution_setup


if ( ! function_exists( 'catchevolution_get_theme_layout' ) ) :
	/**
	 * Returns Theme Layout prioritizing the meta box layouts
	 *
	 * @uses  get_options
	 *
	 * @action wp_head
	 *
	 * @since Catch Evolution Pro 3.5
	 */
	function catchevolution_get_theme_layout() {
		$id = '';

		global $post, $wp_query;

	    // Front page displays in Reading Settings
		$page_on_front  = get_option('page_on_front') ;
		$page_for_posts = get_option('page_for_posts');

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();

		// Blog Page or Front Page setting in Reading Settings
		if ( $page_id == $page_for_posts || $page_id == $page_on_front ) {
	        $id = $page_id;
	    }
	    elseif ( is_singular() ) {
	 		if ( is_attachment() ) {
				$id = $post->post_parent;
			}
			else {
				$id = $post->ID;
			}
		}

		//Get appropriate metabox value of layout
		if ( '' != $id ) {
			$layout = get_post_meta( $id, 'catchevolution-sidebarlayout', true );
		}
		else {
			$layout = 'default';
		}

		//Load options data
   		$options = catchevolution_get_options();

   		//check empty and load default
		if ( empty( $layout ) || 'default' == $layout ) {
			$layout = $options['sidebar_layout'];
		}

		return $layout;
	}
endif; //catchevolution_get_theme_layout


/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Custom Menus
 */
require trailingslashit( get_template_directory() ) . 'inc/catchevolution-menus.php';


if ( ! function_exists( 'catchevolution_woocommerce_activated' ) ) :
/**
 * Add Suport for WooCommerce Plugin
 */
function catchevolution_woocommerce_activated() {
	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
	    require( get_template_directory() . '/inc/catchevolution-woocommerce.php' );
	}
}
endif; // catchevolution_woocommerce_activated


/**
  * Filters the_category() to output html 5 valid rel tag
  *
  * @param string $text
  * @return string
  */
function catchevolution_html_validate( $text ) {
	$string = 'rel="tag"';
	$replace = 'rel="category"';
	$text = str_replace( $replace, $string, $text );

	return $text;
}
add_filter( 'the_category', 'catchevolution_html_validate' );
add_filter( 'wp_list_categories', 'catchevolution_html_validate' );


/**
 * Migrate Logo to New WordPress core Custom Logo
 *
 * Runs if version number saved in theme_mod "logo_version" doesn't match current theme version.
 */
function catchevolution_logo_migrate() {
	$ver = get_theme_mod( 'logo_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '3.2' ) >= 0 ) {
		return;
	}

	/**
	 * Get Theme Options Values
	 */
   	$options = catchevolution_get_options();

   	// If a logo has been set previously, update to use logo feature introduced in WordPress 4.5
	if ( function_exists( 'the_custom_logo' ) ) {
		if ( isset( $options['featured_logo_header'] ) && '' != $options['featured_logo_header'] ) {
			// Since previous logo was stored a URL, convert it to an attachment ID
			$logo = attachment_url_to_postid( $options['featured_logo_header'] );

			if ( is_int( $logo ) ) {
				set_theme_mod( 'custom_logo', $logo );
			}
		}

		// Delete transients after migration
		delete_transient( 'catchevolution_logo' );

  		// Update to match logo_version so that script is not executed continously
		set_theme_mod( 'logo_version', '3.6' );
	}
}
add_action( 'after_setup_theme', 'catchevolution_logo_migrate' );


/**
 * Migrate Custom CSS to WordPress core Custom CSS
 *
 * Runs if version number saved in theme_mod "custom_css_version" doesn't match current theme version.
 */
function catchevolution_custom_css_migrate(){
	$ver = get_theme_mod( 'custom_css_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '4.7' ) >= 0 ) {
		return;
	}

	if ( function_exists( 'wp_update_custom_css_post' ) ) {
	    // Migrate any existing theme CSS to the core option added in WordPress 4.7.

	    /**
		 * Get Theme Options Values
		 */
	   	$options = catchevolution_get_options();

	    if ( '' != $options['custom_css'] ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return   = wp_update_custom_css_post( $core_css . $options['custom_css'] );

	        if ( ! is_wp_error( $return ) ) {
	            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
	            unset( $options['custom_css'] );
	            update_option( 'catchevolution_options', $options );

	            // Update to match custom_css_version so that script is not executed continously
				set_theme_mod( 'custom_css_version', '4.7' );
	        }
	    }
	}
}
add_action( 'after_setup_theme', 'catchevolution_custom_css_migrate' );

// Load up our Catch Evolution customizer
require trailingslashit( get_template_directory() ) . '/inc/panel/customizer/customizer.php';
