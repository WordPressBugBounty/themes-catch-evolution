<?php
/**
 * @package Catch Evolution
 */

/**
 * Set the default values for all the settings. If no user-defined values
 * is available for any setting, these defaults will be used.
 */
function catchevolution_get_defaults( $parameter = null ) {
	$defaults = array(
		'featured_logo_header'				=> esc_url( get_template_directory_uri() ).'/images/logo.png',
		'disable_header'					=> '0',
		'enable_menus'                      => '0',
	 	'remove_header_logo'				=> '1',
	 	'remove_site_title'					=> '0',
	 	'remove_site_description'			=> '0',
		'site_title_above'					=> '0',
		'seperate_logo'						=> '0',
		'disable_header_menu'				=> '0',
		'search_display_text'				=> 'Search',
		'color_scheme'						=> 'light',
		'sidebar_layout'					=> 'right-sidebar',
		'content_layout'					=> 'excerpt',
	 	'front_page_category'				=> '0',
		'exclude_slider_post'				=> '0',
	 	'slider_qty'						=> 4,
		'enable_slider'						=> 'enable-slider-homepage',
		'featured_slider'					=> array(),
	 	'transition_effect'					=> 'fade',
	 	'transition_delay'					=> 4,
	 	'transition_duration'				=> 1,
		'disable_footer_social'				=> 0,
	 	'social_facebook'					=> '',
	 	'social_twitter'					=> '',
	 	'social_googleplus'					=> '',
	 	'social_pinterest'					=> '',
	 	'social_youtube'					=> '',
	 	'social_vimeo'						=> '',
	 	'social_linkedin'					=> '',
	 	'social_aim'						=> '',
	 	'social_myspace'					=> '',
	 	'social_flickr'						=> '',
	 	'social_tumblr'						=> '',
	 	'social_deviantart'					=> '',
	 	'social_dribbble'					=> '',
	 	'social_wordpress'					=> '',
	 	'social_rss'						=> '',
		'social_slideshare'					=> '',
	 	'social_instagram'					=> '',
	 	'social_skype'						=> '',
		'social_soundcloud'					=> '',
		'social_email'						=> '',
		'social_contact'					=> '',
		'social_xing'						=> '',
		'social_meetup'						=> '',
	 	'custom_css'						=> '',
	 	'analytic_header'					=> '',
	 	'analytic_footer'					=> '',
	 	'sidebar_layout'					=> 'right-sidebar',
	 	'more_tag_text'						=> 'Continue Reading &rarr;',
	 	'excerpt_length'					=> 30,
		'feed_url'							=> '',
	);

	if ( null !== $parameter ) {
		return $defaults[ $parameter ];
	}

	return $defaults;

}

/*
 * Return arrray of theme options merged with default valuse if it does not exist
 */
function catchevolution_get_options() {
	return wp_parse_args( ( array ) get_option( 'catchevolution_options' ) , catchevolution_get_defaults() );
}

/**
 * Returns an array of color schemes registered for adventurous.
 *
 * @since Catch Evolution 2.6
 */
function catchevolution_color_schemes() {
	$options = array(
		'light' 		=> __( 'Light', 'catch-evolution' ),
		'dark'			=> __( 'Dark', 'catch-evolution' ),
	);

	return apply_filters( 'catchevolution_color_schemes', $options );
}


/**
 * Returns an array of sidebar layout options
 *
 * @since Catch Evolution 2.6
 */
function catchevolution_sidebar_layout_options() {
	$options = array(
		'right-sidebar' => __( 'Right Sidebar', 'catch-evolution' ),
		'left-sidebar' 	=> __( 'Left Sidebar', 'catch-evolution' ),
		'no-sidebar'	=> __( 'No Sidebar', 'catch-evolution' ),
		'three-columns'	=> __( 'Three Columns', 'catch-evolution' ),
	);

	return apply_filters( 'catchevolution_sidebar_layout_options', $options );
}


/**
 * Returns an array of content layout options
 *
 * @since Catch Evolution 2.6
 */
function catchevolution_content_layout_options() {
	$options = array(
		'full' 		=> __( 'Full Content Display', 'catch-evolution' ),
		'excerpt' 	=> __( 'Excerpt/Blog Display', 'catch-evolution' ),
	);

	return apply_filters( 'catchevolution_content_layout_options', $options );
}

/**
 * Returns an array of slider enable options
 *
 * @since Catch Evolution 2.6
 */
function catchevolution_enable_slider_options() {
	$options = array(
		'enable-slider-homepage'=> __( 'Homepage', 'catch-evolution' ),
		'enable-slider-allpage' => __( 'Entire Site', 'catch-evolution' ),
		'disable-slider' 		=> __( 'Disable', 'catch-evolution' ),
	);

	return apply_filters( 'catchevolution_enable_slider_options', $options );
}


/**
 * Returns an array of slider transition effects
 *
 * @since Catch Evolution 2.6
 */
function catchevolution_transition_effects() {
	$options = array(
		'fade'			=> __( 'fade', 'catch-evolution' ),
		'wipe' 			=> __( 'wipe', 'catch-evolution' ),
		'scrollUp' 		=> __( 'scrollUp', 'catch-evolution' ),
		'scrollDown'	=> __( 'scrollDown', 'catch-evolution' ),
		'scrollUp' 		=> __( 'scrollUp', 'catch-evolution' ),
		'scrollLeft'	=> __( 'scrollLeft', 'catch-evolution' ),
		'scrollRight'	=> __( 'scrollRight', 'catch-evolution' ),
		'blindX' 		=> __( 'blindX', 'catch-evolution' ),
		'blindY' 		=> __( 'blindY', 'catch-evolution' ),
		'blindZ' 		=> __( 'blindZ', 'catch-evolution' ),
		'cover' 		=> __( 'cover', 'catch-evolution' ),
		'shuffle' 		=> __( 'shuffle', 'catch-evolution' ),
	);

	return apply_filters( 'catchevolution_transition_effects', $options );
}

/**
 * Function to display the current year.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function catchevolution_the_year() {
    return esc_attr( date_i18n( __( 'Y', 'catch-evolution' ) ) );
}


/**
 * Function to display a link back to the site.
 *
 * @uses get_bloginfo() Gets the site link
 * @return string
 */
function catchevolution_site_link() {
    return '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" ><span>' . get_bloginfo( 'name', 'display' ) . '</span></a>';
}


/**
 * Function to display a link to WordPress.org.
 *
 * @return string
 */
function catchevolution_theme_name() {
    return '<span class="theme-name">' . __( 'Theme: Catch Evolution by ', 'catch-evolution' ) . '</span>';
}


/**
 * Function to display a link to Theme Link.
 *
 * @return string
 */
function catchevolution_theme_author() {

    return '<span class="theme-author"><a href="' . esc_url( 'https://catchthemes.com/' ) . '" target="_blank" title="' . esc_attr__( 'Catch Themes', 'catch-evolution' ) . '">' . __( 'Catch Themes', 'catch-evolution' ) . '</a></span>';

}

/**
 * Function to display Catch Evolution Assets
 *
 * @return string
 */
function catchevolution_assets(){
    $catchevolution_content = '<div class="copyright">'. esc_attr__( 'Copyright', 'catch-evolution' ) . ' &copy; '. catchevolution_the_year() . ' ' . catchevolution_site_link() . ' ' . esc_attr__( 'All Rights Reserved.', 'catch-evolution' ) . ' ' . '</div><div class="powered">'. catchevolution_theme_name() . catchevolution_theme_author() . '</div>';

    if ( function_exists( 'get_the_privacy_policy_link' ) ) {
    	$catchevolution_content = '<div class="copyright">'. esc_attr__( 'Copyright', 'catch-evolution' ) . ' &copy; '. catchevolution_the_year() . ' ' . catchevolution_site_link() . ' ' . esc_attr__( 'All Rights Reserved.', 'catch-evolution' ) . ' ' . get_the_privacy_policy_link() . '</div><div class="powered">'. catchevolution_theme_name() . catchevolution_theme_author() . '</div>';
    }
    return $catchevolution_content;
}

/*
 * Clearing the cache if any changes in Admin Theme Option
 */
function catchevolution_themeoption_invalidate_caches(){
    delete_transient( 'catchevolution_responsive' ); // Disable responsive layout
    delete_transient( 'catchevolution_inline_css' ); // Custom Inline CSS and color options
    delete_transient( 'catchevolution_sliders' ); // featured post slider
    delete_transient( 'catchevolution_page_sliders' ); // featured page slider
    delete_transient( 'catchevolution_category_sliders' ); // featured category slider
    delete_transient( 'catchevolution_imagesliders' ); // featured image slider
    delete_transient( 'catchevolution_social_networks' );  // Social links on header
    delete_transient( 'catchevolution_social_search' );  // Social links with search  on header
    delete_transient( 'catchevolution_site_verification' ); // scripts which loads on header
    delete_transient( 'catchevolution_footercode' ); // scripts which loads on footer
    delete_transient( 'catchevolution_footer_content_new' ); // Footer content
    delete_transient( 'catchevolution_logo'); // Header logo
}

/*
 * Clearing the cache if any changes in post or page
 */
function catchevolution_post_invalidate_caches(){
    delete_transient( 'catchevolution_sliders' ); // featured post slider
    delete_transient( 'catchevolution_page_sliders' ); // featured page slider
    delete_transient( 'catchevolution_category_sliders' ); // featured category slider
}
//Add action hook here save post
add_action( 'save_post', 'catchevolution_post_invalidate_caches' );


/*
 * Clearing the cache if any changes in Custom Header
 */
function catchevolution_customheader_invalidate_caches(){
    delete_transient( 'catchevolution_logo'); // Header logo
}
//Add action hook here save post
add_action( 'custom_header_options', 'catchevolution_customheader_invalidate_caches' );


/**
 * Function to display a link to WordPress.org.
 *
 * @return string
 */
function catchevolution_wp_link() {
    return '<a href="http://wordpress.org" target="_blank" title="' . esc_attr__( 'WordPress', 'catch-evolution' ) . '"><span>' . __( 'WordPress', 'catch-evolution' ) . '</span></a>';
}


/**
 * Function to display a link to Theme Link.
 *
 * @return string
 */
function catchevolution_theme_link() {
    $theme_link = 'https://catchthemes.com/themes/catch-evolution-pro';
    return '<a href="' . $theme_link . '" target="_blank" title="' . esc_attr__( 'Catch Themes', 'catch-evolution' ) . '"><span>' . __( 'Catch Evolution Pro', 'catch-evolution' ) . '</span></a>';
}
