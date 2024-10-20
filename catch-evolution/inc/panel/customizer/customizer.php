<?php
/**
 * Catch Evolution Customizer/Theme Options
 *
 * @package Catch Evolution
 */

/**
 * Implements Catch Evolution theme options into Theme Customizer.
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 * @since Catch Evolution 2.6
 */
function catchevolution_customize_register( $wp_customize ) {
    $options = catchevolution_get_options();
	$defaults = catchevolution_get_defaults();

	//Custom Controls
	require trailingslashit( get_template_directory() ) . 'inc/panel/customizer/customizer-custom-controls.php';

	$theme_slug = 'catchevolution_';

	$settings_page_tabs = array(
		'theme_options' => array(
			'id' 			=> 'theme_options',
			'title' 		=> __( 'Theme Options', 'catch-evolution' ),
			'description' 	=> __( 'Basic theme Options', 'catch-evolution' ),
			'sections' 		=> array(
				'header_options' => array(
					'id' 			=> 'header_options',
					'title' 		=> __( 'Header Options', 'catch-evolution' ),
					'description' 	=> '',
				),
				'search_text_settings' => array(
					'id' 			=> 'search_text_settings',
					'title' 		=> __( 'Search Text Settings', 'catch-evolution' ),
					'description' 	=> '',
				),
				'layout_options' => array(
					'id' 			=> 'layout_options',
					'title' 		=> __( 'Layout Options', 'catch-evolution' ),
					'description' 	=> '',
				),
				'homepage_settings' => array(
					'id' 			=> 'homepage_settings',
					'title' 		=> __( 'Homepage / Frontpage Category Setting', 'catch-evolution' ),
					'description' 	=> '',
				),
				'excerpt_more_tag_settings' => array(
					'id' 			=> 'excerpt_more_tag_settings',
					'title' 		=> __( 'Excerpt / More Tag Settings', 'catch-evolution' ),
					'description' 	=> '',
				),
				'feed_url' => array(
					'id' 			=> 'feed_url',
					'title' 		=> __( 'Feed Redirect', 'catch-evolution' ),
					'description' 	=> '',
				),
				'custom_css' => array(
					'id' 			=> 'custom_css',
					'title' 		=> __( 'Custom CSS', 'catch-evolution' ),
					'description' 	=> '',
				),

			),
		),
		'featured_slider' => array(
			'id' 			=> 'featured_slider',
			'title' 		=> __( 'Featured Post Slider', 'catch-evolution' ),
			'description' 	=> __( 'Featured Post Slider', 'catch-evolution' ),
			'sections' 		=> array(
				'slider_options' => array(
					'id' 			=> 'slider_options',
					'title' 		=> __( 'Slider Options', 'catch-evolution' ),
					'description' 	=> '',
				),
			)
		),

		'social_links' => array(
			'id' 			=> 'social_links',
			'title' 		=> __( 'Social Links', 'catch-evolution' ),
			'description' 	=> __( 'Add your social links here', 'catch-evolution' ),
			'sections' 		=> array(
				'social_links' => array(
					'id' 			=> 'social_links',
					'title' 		=> __( 'Social Links', 'catch-evolution' ),
					'description' 	=> '',
				),
			),
		),
		'tools' => array(
			'id' 			=> 'tools',
			'title' 		=> __( 'Tools', 'catch-evolution' ),
			'description' 	=>  sprintf( __( 'Tools falls under Plugins Territory according to Theme Review Guidelines in WordPress.org. This feature will be depreciated in future versions from Catch Evolution free version. If you want this feature, then you can add <a target="_blank" href="%s">Catch Web Tools</a>  plugin.', 'catch-evolution' ), esc_url( 'https://wordpress.org/plugins/catch-web-tools/' ) ),
			'sections' 		=> array(
				'tools' => array(
					'id' 			=> 'tools',
					'title' 		=> __( 'Tools', 'catch-evolution' ),
					'description' 	=>  sprintf( __( 'Tools falls under Plugins Territory according to Theme Review Guidelines in WordPress.org. This feature will be depreciated in future versions from Catch Evolution free version. If you want this feature, then you can add <a target="_blank" href="%s">Catch Web Tools</a>  plugin.', 'catch-evolution' ), esc_url( 'https://wordpress.org/plugins/catch-web-tools/' ) ),
				),
			),
		),
	);

	//Add Panels and sections
	foreach ( $settings_page_tabs as $panel ) {
		$wp_customize->add_panel(
			$theme_slug . $panel['id'],
			array(
				'priority' 		=> 200,
				'capability' 	=> 'edit_theme_options',
				'title' 		=> $panel['title'],
				'description' 	=> $panel['description'],
			)
		);

		// Loop through tabs for sections
		foreach ( $panel['sections'] as $section ) {
			$params = array(
								'title'			=> $section['title'],
								'description'	=> $section['description'],
								'panel'			=> $theme_slug . $panel['id']
							);

			if ( isset( $section['active_callback'] ) ) {
				$params['active_callback'] = $section['active_callback'];
			}

			$wp_customize->add_section(
				// $id
				$theme_slug . $section['id'],
				// parameters
				$params

			);
		}
	}

	//Add Menu Options Section Without a panel
	$wp_customize->add_section(
		'catchevolution_menu_options',
		array(
			'description'	=> __( 'Extra Menu Options specific to this theme', 'catch-evolution' ),
			'priority' 		=> 105,
			'title'    		=> __( 'Menu Options', 'catch-evolution' ),
			)
		);

	$settings_parameters = array(
		//Color Scheme
		'color_scheme' => array(
			'id' 			=> 'color_scheme',
			'title' 		=> __( 'Default Color Scheme', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'radio',
			'sanitize' 		=> 'catchevolution_sanitize_select',
			'section' 		=> 'colors',
			'default' 		=> $defaults['color_scheme'],
			'choices'		=> catchevolution_color_schemes(),
		),

		//Disable Header Menu
		'disable_header_menu' => array(
			'id' 				=> 'disable_header_menu',
			'title' 			=> __( 'Check to Disable Primary Menu', 'catch-evolution' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'catchevolution_sanitize_checkbox',
			'panel' 			=> 'theme_options',
			'section' 			=> 'menu_options',
			'default' 			=> $defaults['disable_header_menu'],
		),

		'enable_menus' => array(
			'id' 				=> 'enable_menus',
			'title' 			=> __( 'Check to Enable Footer Menu in Mobile Devices', 'catch-evolution' ),
			'description'		=> '',
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'catchevolution_sanitize_checkbox',
			'panel' 			=> 'theme_options',
			'section' 			=> 'menu_options',
			'default' 			=> $defaults['enable_menus'],
		),

		//Header Options
		'disable_header' => array(
			'id' 			=> 'disable_header',
			'title' 		=> __( 'Check to Disable Header', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchevolution_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_options',
			'default' 		=> $defaults['disable_header']
		),
		'remove_site_title' => array(
			'id' 			=> 'remove_site_title',
			'title' 		=> __( 'Check to Disable Site Title', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchevolution_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_options',
			'default' 		=> $defaults['remove_site_title']
		),
		'remove_site_title' => array(
			'id' 			=> 'remove_site_title',
			'title' 		=> __( 'Check to Disable Site Title', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchevolution_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_options',
			'default' 		=> $defaults['remove_site_title']
		),
		'site_title_above' => array(
			'id' 			=> 'site_title_above',
			'title' 		=> __( 'Check to Move Site Title and Tagline before the logo', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchevolution_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_options',
			'default' 		=> $defaults['site_title_above']
		),
		'seperate_logo' => array(
			'id' 			=> 'seperate_logo',
			'title' 		=> __( 'Check to Separate Logo and Site Details', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchevolution_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_options',
			'default' 		=> $defaults['seperate_logo']
		),

		//Search Settings
		'search_display_text' => array(
			'id' 			=> 'search_display_text',
			'title' 		=> __( 'Default Display Text in Search', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'search_text_settings',
			'default' 		=> $defaults['search_display_text']
		),

		//Layout Options
		'sidebar_layout' => array(
			'id' 			=> 'sidebar_layout',
			'title' 		=> __( 'Sidebar Layout Options', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchevolution_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'layout_options',
			'default' 		=> $defaults['sidebar_layout'],
			'choices'		=> catchevolution_sidebar_layout_options(),
		),
		'content_layout' => array(
			'id' 			=> 'content_layout',
			'title' 		=> __( 'Full Content Display', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchevolution_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'layout_options',
			'default' 		=> $defaults['content_layout'],
			'choices'		=> catchevolution_content_layout_options(),
		),
		//Homepage/Frontpage Settings
		'front_page_category' => array(
			'id' 			=> 'front_page_category',
			'title' 		=> __( 'Front page posts categories:', 'catch-evolution' ),
			'description'	=> __( 'Only posts that belong to the categories selected here will be displayed on the front page', 'catch-evolution' ),
			'field_type' 	=> 'category-multiple',
			'sanitize' 		=> 'catchevolution_sanitize_category_list',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_settings',
			'default' 		=> $defaults['front_page_category']
		),

		//Excerpt More Settings
		'more_tag_text' => array(
			'id' 			=> 'more_tag_text',
			'title' 		=> __( 'More Tag Text', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'excerpt_more_tag_settings',
			'default' 		=> $defaults['more_tag_text']
		),
		'excerpt_length' => array(
			'id' 			=> 'excerpt_length',
			'title' 		=> __( 'Excerpt length(words)', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'number',
			'sanitize' 		=> 'catchevolution_sanitize_number_range',
			'panel' 		=> 'theme_options',
			'section' 		=> 'excerpt_more_tag_settings',
			'default' 		=> $defaults['excerpt_length'],
			'input_attrs' 	=> array(
					            'style' => 'width: 45px;',
					            'min'   => 0,
					            'max'   => 999999,
					            'step'  => 1,
					        	)
		),

		//Feed URL
		'feed_url' => array(
			'id' 				=> 'feed_url',
			'title' 			=> __( 'Feed Redirect url', 'catch-evolution' ),
			'description'	=> __( ' Add in the Feedburner URL', 'catch-evolution' ),
			'field_type' 		=> 'url',
			'sanitize' 			=> 'esc_url_raw',
			'panel' 			=> 'social_links',
			'section' 			=> 'feed_url',
			'default' 			=> '',
			'active_callback'	=> 'catchevolution_is_feed_url_present',
		),

		//Custom Css
		'custom_css' => array(
			'id' 			=> 'custom_css',
			'title' 		=> __( 'Enter your custom CSS styles', 'catch-evolution' ),
			'description' 	=> '',
			'field_type' 	=> 'textarea',
			'sanitize' 		=> 'catchevolution_sanitize_custom_css',
			'panel' 		=> 'theme_options',
			'section' 		=> 'custom_css',
			'default' 		=> $defaults['custom_css']
		),

		//Slider Options
		'enable_slider' => array(
			'id' 			=> 'enable_slider',
			'title' 		=> __( 'Enable Slider', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchevolution_sanitize_select',
			'panel' 		=> 'featured_slider',
			'section' 		=> 'slider_options',
			'default' 		=> $defaults['enable_slider'],
			'choices'		=> catchevolution_enable_slider_options(),
		),
		'slider_qty' => array(
			'id' 				=> 'slider_qty',
			'title' 			=> __( 'Number of Slides', 'catch-evolution' ),
			'description'		=> __( 'Customizer page needs to be refreshed after saving if number of slides is changed', 'catch-evolution' ),
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchevolution_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['slider_qty'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 20,
						            'step'  => 1,
						        	)
		),
		'transition_effect' => array(
			'id' 				=> 'transition_effect',
			'title' 			=> __( 'Transition Effect', 'catch-evolution' ),
			'description'		=> '',
			'field_type' 		=> 'select',
			'sanitize' 			=> 'catchevolution_sanitize_select',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['transition_effect'],
			'choices'			=> catchevolution_transition_effects(),
		),
		'transition_delay' => array(
			'id' 				=> 'transition_delay',
			'title' 			=> __( 'Transition Delay', 'catch-evolution' ),
			'description'		=> '',
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchevolution_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['transition_delay'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 999999999,
						            'step'  => 1,
						        	)
		),
		'transition_duration' => array(
			'id' 				=> 'transition_duration',
			'title' 			=> __( 'Transition Length', 'catch-evolution' ),
			'description'		=> '',
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchevolution_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['transition_duration'],
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 999999999,
						            'step'  => 1,
						        	)
		),

		//Featured Post Slider
		'exclude_slider_post' => array(
			'id' 				=> 'exclude_slider_post',
			'title' 			=> __( 'Check to Exclude Slider posts from Homepage posts', 'catch-evolution' ),
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'catchevolution_sanitize_checkbox',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['exclude_slider_post'],
		),

		//Social Links
		'disable_footer_social' => array(
			'id' 				=> 'disable_footer_social',
			'title' 			=> __( 'Check to Enable Social Icons in Footer', 'catch-evolution' ),
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'catchevolution_sanitize_checkbox',
			'panel' 			=> 'social_links',
			'section' 			=> 'social_links',
			'default' 			=> $defaults['disable_footer_social'],
		),
		'social_facebook' => array(
			'id' 			=> 'social_facebook',
			'title' 		=> __( 'Facebook', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_facebook']
		),
		'social_twitter' => array(
			'id' 			=> 'social_twitter',
			'title' 		=> __( 'Twitter', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_twitter']
		),
		'social_googleplus' => array(
			'id' 			=> 'social_googleplus',
			'title' 		=> __( 'Google+', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_googleplus']
		),
		'social_pinterest' => array(
			'id' 			=> 'social_pinterest',
			'title' 		=> __( 'Pinterest', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_pinterest']
		),
		'social_youtube' => array(
			'id' 			=> 'social_youtube',
			'title' 		=> __( 'Youtube', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_youtube']
		),
		'social_vimeo' => array(
			'id' 			=> 'social_vimeo',
			'title' 		=> __( 'Vimeo', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_vimeo']
		),
		'social_linkedin' => array(
			'id' 			=> 'social_linkedin',
			'title' 		=> __( 'LinkedIn', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_linkedin']
		),
		'social_aim' => array(
			'id' 			=> 'social_aim',
			'title' 		=> __( 'AIM', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_aim']
		),
		'social_myspace' => array(
			'id' 			=> 'social_myspace',
			'title' 		=> __( 'MySpace', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_myspace']
		),
		'social_flickr' => array(
			'id' 			=> 'social_flickr',
			'title' 		=> __( 'Flickr', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_flickr']
		),
		'social_tumblr' => array(
			'id' 			=> 'social_tumblr',
			'title' 		=> __( 'Tumblr', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_tumblr']
		),
		'social_deviantart' => array(
			'id' 			=> 'social_deviantart',
			'title' 		=> __( 'deviantART', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_deviantart']
		),
		'social_dribbble' => array(
			'id' 			=> 'social_dribbble',
			'title' 		=> __( 'Dribbble', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_dribbble']
		),
		'social_wordpress' => array(
			'id' 			=> 'social_wordpress',
			'title' 		=> __( 'WordPress', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_wordpress']
		),
		'social_rss' => array(
			'id' 			=> 'social_rss',
			'title' 		=> __( 'RSS', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_rss']
		),
		'social_slideshare' => array(
			'id' 			=> 'social_slideshare',
			'title' 		=> __( 'Slideshare', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_slideshare']
		),
		'social_instagram' => array(
			'id' 			=> 'social_instagram',
			'title' 		=> __( 'Instagram', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_instagram']
		),
		'social_skype' => array(
			'id' 			=> 'social_skype',
			'title' 		=> __( 'Skype', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_skype']
		),
		'social_soundcloud' => array(
			'id' 			=> 'social_soundcloud',
			'title' 		=> __( 'Soundcloud', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_soundcloud']
		),
		'social_email' => array(
			'id' 			=> 'social_email',
			'title' 		=> __( 'Email', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_email',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_email']
		),
		'social_contact' => array(
			'id' 			=> 'social_contact',
			'title' 		=> __( 'Contact', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_contact']
		),
		'social_xing' => array(
			'id' 			=> 'social_xing',
			'title' 		=> __( 'Xing', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_xing']
		),
		'social_meetup' => array(
			'id' 			=> 'social_meetup',
			'title' 		=> __( 'Meetup', 'catch-evolution' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'social_links',
			'default' 		=> $defaults['social_meetup']
		),

		//Webmaster Tools
		'analytic_header' => array(
			'id' 				=> 'analytic_header',
			'title' 			=> __( 'Code to display on Header', 'catch-evolution' ),
			'description' 		=> __( 'Here you can put scripts from Google, Facebook etc. which will load on Header', 'catch-evolution' ),
			'field_type' 		=> 'textarea',
			'sanitize' 			=> 'wp_kses_stripslashes',
			'panel' 			=> 'tools',
			'section' 			=> 'tools',
			'active_callback'	=> 'catchevolution_is_header_code_present',
			'default' 			=> ''
		),
		'analytic_footer' => array(
			'id' 				=> 'analytic_footer',
			'title' 			=> __( 'Code to display on Footer', 'catch-evolution' ),
			'description' 		=> __( 'Here you can put scripts from Google, Facebook etc. which will load on Footer', 'catch-evolution' ),
			'field_type' 		=> 'textarea',
			'sanitize' 			=> 'wp_kses_stripslashes',
			'panel' 			=> 'tools',
			'section' 			=> 'tools',
			'active_callback'	=> 'catchevolution_is_footer_code_present',
			'default' 			=> ''
		),
	);


	//@remove Remove if block when WordPress 4.8 is released
	if( !function_exists( 'has_custom_logo' ) ) {
		$settings_logo = array(
			'remove_header_logo' => array(
				'id' 			=> 'remove_header_logo',
				'title' 		=> __( 'Check to Disable Header Logo', 'catch-evolution' ),
				'description'	=> '',
				'field_type' 	=> 'checkbox',
				'sanitize' 		=> 'catchevolution_sanitize_checkbox',
				'panel' 		=> 'theme_options',
				'section' 		=> 'header_options',
				'default' 		=> $defaults['remove_header_logo']
			),
			'featured_logo_header' => array(
				'id' 			=> 'featured_logo_header',
				'title' 		=> __( 'Logo', 'catch-evolution' ),
				'description'	=> '',
				'field_type' 	=> 'image',
				'sanitize' 		=> 'catchevolution_sanitize_image',
				'panel' 		=> 'theme_options',
				'section' 		=> 'header_options',
				'default' 		=> $defaults['featured_logo_header']
			),
		);

		$settings_parameters = array_merge( $settings_parameters, $settings_logo);
	}

	//@remove Remove if block and custom_css from $settings_paramater when WordPress 5.0 is released
	if( function_exists( 'wp_update_custom_css_post' ) ) {
		unset( $settings_parameters['custom_css'] );
	}

	foreach ( $settings_parameters as $option ) {
		$transport = isset( $option['transport'] ) ? $option['transport'] : 'refresh';

		if ( 'image' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default']
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					array(
						'label'		=> $option['title'],
						'section'   => $theme_slug . $option['section'],
						'settings'  => $theme_slug . 'options[' . $option['id'] . ']',
					)
				)
			);
		} else if ('checkbox' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default'],
					'transport'			=> $transport,
				)
			);

			$params = array(
						'label'		=> $option['title'],
						'settings'  => $theme_slug . 'options[' . $option['id'] . ']',
						'name'  	=> $theme_slug . 'options[' . $option['id'] . ']',
					);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			if ( 'header_image' == $option['section'] ){
				$params['section'] = $option['section'];
			}
			else {
				$params['section']	= $theme_slug . $option['section'];
			}

			$wp_customize->add_control(
				new Catchevolution_Customize_Checkbox(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		} else if ('category-multiple' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default']
				)
			);

			$params = array(
						'label'			=> $option['title'],
						'section'		=> $theme_slug . $option['section'],
						'settings'		=> $theme_slug . 'options[' . $option['id'] . ']',
						'description'	=> $option['description'],
						'name'	 		=> $theme_slug . 'options[' . $option['id'] . ']',
					);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			$wp_customize->add_control(
				new Catchevolution_Customize_Dropdown_Categories_Control (
					$wp_customize,
					$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		} else {
			//Normal Loop
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'default'			=> $option['default'],
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'transport'			=> $transport,
				)
			);

			// Add setting control
			$params = array(
					'label'			=> $option['title'],
					'settings'		=> $theme_slug . 'options[' . $option['id'] . ']',
					'type'			=> $option['field_type'],
					'description'   => $option['description'],
				) ;

			if ( isset( $option['choices']  ) ){
				$params['choices'] = $option['choices'];
			}

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			if ( isset( $option['input_attrs']  ) ){
				$params['input_attrs'] = $option['input_attrs'];
			}

			if ( 'colors' == $option['section'] ){
				$params['section'] = $option['section'];
			}
			else {
				$params['section']	= $theme_slug . $option['section'];
			}

			$wp_customize->add_control(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				$params
			);
		}
	}

	//Add featured post elements with respect to no of featured sliders
	for ( $i = 1; $i <= $options['slider_qty']; $i++ ) {
		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[featured_slider][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'catchevolution_sanitize_post_id'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[featured_slider][' . $i . ']',
			array(
				'label'		=> sprintf( __( 'Featured Post Slider #%s', 'catch-evolution' ), $i ),
				'section'   => $theme_slug .'slider_options',
				'settings'  => $theme_slug . 'options[featured_slider][' . $i . ']',
				'type'		=> 'text',
					'input_attrs' => array(
	        		'style' => 'width: 100px;'
	    		),
			)
		);
	}

	//Important Links
	$wp_customize->add_section( 'important_links', array(
		'priority' 		=> 999,
		'title'   	 	=> __( 'Important Links', 'catch-evolution' ),
	) );

	/**
	 * Has dummy Sanitizaition function as it contains no value to be sanitized
	 */
	$wp_customize->add_setting( 'important_links', array(
		'sanitize_callback'	=> 'sanitize_text_field',
	) );

	$wp_customize->add_control( new Catchevolution_Important_Links( $wp_customize, 'important_links', array(
        'label'   	=> __( 'Important Links', 'catch-evolution' ),
        'section'  	=> 'important_links',
        'settings' 	=> 'important_links',
        'type'     	=> 'important_links',
    ) ) );
    //Important Links End
}
add_action( 'customize_register', 'catchevolution_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously for adventurous.
 * And flushes out all transient data on preview
 *
 * @since Catch Evolution 1.6.3
 */
function catchevolution_customize_preview() {
	//Remove transients on preview
	catchevolution_themeoption_invalidate_caches();
}
add_action( 'customize_preview_init', 'catchevolution_customize_preview' );
add_action( 'customize_save', 'catchevolution_customize_preview' );


/**
 * Custom scripts and styles on Customizer for Catch Evolution
 *
 * @since Catch Evolution 2.3
 */
function catchevolution_customize_scripts() {
    //Enqueue Customizer CSS
    wp_enqueue_style( 'catchevolution-custom-controls-css', trailingslashit( esc_url( get_template_directory_uri() ) ) . 'css/customizer.css' );
    wp_enqueue_script( 'catchevolution_customizer_custom', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'inc/panel/js/customizer-custom-scripts.js', array( 'jquery' ), '20140108', true );
}
add_action( 'customize_controls_enqueue_scripts', 'catchevolution_customize_scripts' );

//Active callbacks for customizer
require trailingslashit( get_template_directory() ) . 'inc/panel/customizer/customizer-active-callbacks.php';

//Sanitize functions for customizer
require trailingslashit( get_template_directory() ) . 'inc/panel/customizer/customizer-sanitize-functions.php';

// Add Upgrade to Pro Button.
require_once( trailingslashit( get_template_directory() ) . '/inc/panel/customizer/upgrade-button/class-customize.php' );

// Add Reset Button.
require_once( trailingslashit( get_template_directory() ) . '/inc/panel/customizer/reset.php' );
