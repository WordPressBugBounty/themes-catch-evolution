<?php

/**
 * Register jquery scripts
 *
 * @register jquery cycle and custom-script
 * hooks action wp_enqueue_scripts
 */
function catchevolution_scripts_method() {
	    global $post, $wp_query;
   	$options = catchevolution_get_options();

	// Get value from Theme Options panel
	$enableslider = $options['enable_slider'];

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	// Enqueue catchevolution Sytlesheet
	wp_enqueue_style( 'catch-evolution-style', get_stylesheet_uri(), null, date( 'Ymd-Gis', filemtime( get_template_directory() . '/style.css' ) ) );

	// Theme block stylesheet.
	wp_enqueue_style( 'catch-evolution-block-style', get_theme_file_uri( '/css/blocks.css' ), array( 'catch-evolution-style' ), '1.0' );

	//For genericons
	wp_enqueue_style( 'genericons', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'genericons/genericons.css', false, '3.4.1' );

	// Register JQuery cycle all and JQuery set up as dependent on Jquery-cycle
	wp_register_script( 'jquery-cycle', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/jquery.cycle.all.min.js', array( 'jquery' ), '2.9999.5', true );

	// Slider JS load loop
	if ( ( 'enable-slider-allpage' == $enableslider ) || ( ( is_front_page() || ( is_home() && $page_id != $page_for_posts ) ) && 'enable-slider-homepage' == $enableslider ) ) {
		wp_enqueue_script( 'catchevolution-slider', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/catchevolution.slider.js', array( 'jquery-cycle' ), '1.0.1', true );
	}

	//Responsive
	wp_enqueue_style( 'catchevolution-responsive', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'css/responsive.css' );

	wp_enqueue_script( 'catchevolution-menu', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/catchevolution-menu.min.js', array('jquery'), '20171025', false );

	wp_localize_script( 'catchevolution-menu', 'screenReaderText', array(
		'expand'   => esc_html__( 'expand child menu', 'catch-evolution' ),
		'collapse' => esc_html__( 'collapse child menu', 'catch-evolution' ),
	) );

	wp_enqueue_script( 'jquery-fitvids', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/catchevolution-fitvids.min.js', array( 'jquery' ), '20130324', true );

	/**
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

} // catchevolution_scripts_method
add_action( 'wp_enqueue_scripts', 'catchevolution_scripts_method' );


/**
 * Enqueue editor styles for Gutenberg
 */
function catchevolution_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'catchevolution-block-editor-style', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'css/editor-blocks.css' );
}
add_action( 'enqueue_block_editor_assets', 'catchevolution_block_editor_styles' );


/**
 * Register script for admin section
 *
 * No scripts should be enqueued within this function.
 * jquery cookie used for remembering admin tabs, and potential future features... so let's register it early
 * @uses wp_register_script
 * @action admin_enqueue_scripts
 */
function catchevolution_register_js() {
	//jQuery Cookie
	wp_register_script( 'jquery-cookie', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/jquery.cookie.min.js', array( 'jquery' ), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'catchevolution_register_js' );


/**
 * Responsive Layout
 *
 * @get the data value of responsive layout from theme options
 * @display responsive meta tag
 * @action wp_head
 */
function catchevolution_responsive() {

	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

} // catchevolution_responsive
add_filter( 'wp_head', 'catchevolution_responsive', 1 );


/**
 * Enqueue the styles for the current color scheme.
 *
 * @since Catch Evolution 1.0
 */
function catchevolution_enqueue_color_scheme() {
    $options = catchevolution_get_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme )
		wp_enqueue_style( 'dark', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'colors/dark.css', array(), null );

	do_action( 'catchevolution_enqueue_color_scheme', $color_scheme );
}
add_action( 'wp_enqueue_scripts', 'catchevolution_enqueue_color_scheme' );


/**
 * Hooks the Custom Inline CSS to head section
 *
 * @since Catch Evolution Pro 1.0
 */
function catchevolution_inline_css() {
	//delete_transient( 'catchevolution_inline_css' );

    $options = catchevolution_get_options();

	if ( ( !$output = get_transient( 'catchevolution_inline_css' ) ) && ( !empty( $options['disable_header'] ) || !empty( $options['custom_css'] ) ) ) {
		echo '<!-- refreshing cache -->' . "\n";

		$output = '<!-- '.get_bloginfo('name').' inline CSS Styles -->' . "\n";
		$output	.= '<style type="text/css" media="screen">' . "\n";

		//Disable Header
		if ( !empty( $options['disable_header'] ) ) {
			$output	.=  "#branding { display: none; }" . "\n";
		}

		//Custom CSS Option
		if ( !empty( $options['custom_css'] ) ) {
			$output	.=  $options['custom_css'] . "\n";
		}

		$output	.= '</style>' . "\n";

		set_transient( 'catchevolution_inline_css', $output, 86940 );
	}
	echo $output;
}
add_action('wp_head', 'catchevolution_inline_css');


/**
 * Sets the post excerpt length.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function catchevolution_excerpt_length( $length ) {
	$options = catchevolution_get_options();

	if ( empty( $options['excerpt_length'] ) )
		$options = catchevolution_get_defaults();

	$length = $options['excerpt_length'];
	return $length;
}
add_filter( 'excerpt_length', 'catchevolution_excerpt_length' );


/**
 * Returns a "Continue Reading" link for excerpts
 */
function catchevolution_continue_reading_link() {
    $options = catchevolution_get_options();
	$more_tag_text = $options['more_tag_text'];

	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' . $more_tag_text . '</a>';
}


/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and catchevolution_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function catchevolution_auto_excerpt_more( $more ) {
	return catchevolution_continue_reading_link();
}
add_filter( 'excerpt_more', 'catchevolution_auto_excerpt_more' );


/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function catchevolution_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= catchevolution_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'catchevolution_custom_excerpt_more' );


if ( ! function_exists( 'catchevolution_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function catchevolution_content_nav( $nav_id ) {
	global $wp_query;

	/**
	 * Check Jetpack Infinite Scroll
	 * if it's active then disable pagination
	 */
	if ( class_exists( 'Jetpack', false ) ) {
		$jetpack_active_modules = get_option('jetpack_active_modules');
		if ( $jetpack_active_modules && in_array( 'infinite-scroll', $jetpack_active_modules ) ) {
			return false;
		}
	}

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	if ( $wp_query->max_num_pages > 1 ) { ?>
        <nav role="navigation" id="<?php echo $nav_id; ?>">
        	<h3 class="assistive-text"><?php _e( 'Post navigation', 'catch-evolution' ); ?></h3>
			<?php if ( function_exists('wp_pagenavi' ) )  {
                wp_pagenavi();
            }
            elseif ( function_exists('wp_page_numbers' ) ) {
                wp_page_numbers();
            }
            else { ?>
                <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'catch-evolution' ) ); ?></div>
                <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'catch-evolution' ) ); ?></div>
            <?php
            } ?>
        </nav><!-- #nav -->
	<?php
	}
}
endif; // catchevolution_content_nav


/**
 * Return the URL for the first link found in the post content.
 *
 * @since Catch Evolution 1.0
 * @return string|bool URL or false when no link is present.
 */
function catchevolution_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}


if ( ! function_exists( 'catchevolution_footer_sidebar_class' ) ) :
/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function catchevolution_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}
endif; // catchevolution_footer_sidebar_class


if ( ! function_exists( 'catchevolution_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own catchevolution_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Catch Evolution 1.0
 */
function catchevolution_comment( $comment, $args, $depth ) {
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'catch-evolution' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'catch-evolution' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'catch-evolution' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'catch-evolution' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'catch-evolution' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'catch-evolution' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'catch-evolution' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for catchevolution_comment()


if ( ! function_exists( 'catchevolution_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own catchevolution_posted_on to override in a child theme
 *
 * @since Catch Evolution 1.0
 */
function catchevolution_posted_on() {
	/* Check Author URL to Support Google Authorship
	*
	* By deault the author will link to author archieve page
	* But if the author have added their Website in Profile page then it will link to author website
	*/
	if ( get_the_author_meta( 'user_url' ) != '' ) {
		$catchevolution_author_url = 	esc_url( get_the_author_meta( 'user_url' ) );
	}
	else {
		$catchevolution_author_url = esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) );
	}
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date updated" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'catch-evolution' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		$catchevolution_author_url,
		esc_attr( sprintf( __( 'View all posts by %s', 'catch-evolution' ), get_the_author() ) ),
		get_the_author()
	);
}
endif;


/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Catch Evolution 1.0
 */
function catchevolution_body_classes( $classes ) {
	if ( !is_active_sidebar( 'catchevolution_woocommerce_sidebar' ) && ( class_exists( 'Woocommerce' ) && is_woocommerce() ) ) {
		$classes[] = 'woocommerce-nosidebar';
	}

	if ( has_nav_menu( 'top', 'catch-evolution' ) && !empty ( $header_logo ) ) {
		$classes[] = 'has-header-top menu-logo';
	}
	elseif ( has_nav_menu( 'top', 'catch-evolution' ) && empty ( $header_logo ) ) {
		$classes[] = 'has-header-top';
	}

	if ( !empty( $options['disable_header'] ) ) {
		$classes[] = 'disable-header';
	}

	if ( !empty( $options['enable_menus'] ) ) {
		$classes[] = 'enable-menu';
	}

	$layout = catchevolution_get_theme_layout();

	if ( 'three-columns' == $layout || is_page_template( 'page-three-columns.php' ) ) {
		$classes[] = 'three-columns';
	}
	elseif ( 'no-sidebar' == $layout || is_page_template( 'page-disable-sidebar.php' ) ) {
		$classes[] = 'no-sidebar';
	}
	elseif ( 'no-sidebar-one-column' == $layout || is_page_template( 'page-onecolumn.php' ) ) {
		$classes[] = 'no-sidebar one-column';
	}
	elseif ( 'no-sidebar-full-width' == $layout || is_page_template( 'page-fullwidth.php' ) ) {
		$classes[] = 'no-sidebar full-width';
	}
	elseif ( 'left-sidebar' == $layout ) {
		$classes[] = 'left-sidebar';
	}
	elseif ( 'right-sidebar' == $layout ) {
		$classes[] = 'right-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'catchevolution_body_classes' );


/**
 * Adds in post and Page ID when viewing lists of posts and pages
 * This will help the admin to add the post ID in featured slider
 *
 * @param mixed $post_columns
 * @return post columns
 */
function catchevolution_post_id_column( $post_columns ) {
	$beginning = array_slice( $post_columns, 0 ,1 );
	$beginning[ 'postid' ] = __( 'ID', 'catch-evolution'  );
	$ending = array_slice( $post_columns, 1 );
	$post_columns = array_merge( $beginning, $ending );
	return $post_columns;
}
add_filter( 'manage_posts_columns', 'catchevolution_post_id_column' );


function catchevolution_posts_id_column( $col, $val ) {
	if ( 'postid' == $col ) echo $val;
}
add_action( 'manage_posts_custom_column', 'catchevolution_posts_id_column', 10, 2 );


function catchevolution_posts_id_column_css() {
	echo '
	<style type="text/css">
	    #postid { width: 80px; }
	    @media screen and (max-width: 782px) {
	        .wp-list-table #postid, .wp-list-table #the-list .postid { display: none; }
	        .wp-list-table #the-list .is-expanded .postid {
	            padding-left: 30px;
	        }
	    }
    </style>';
}
add_action( 'admin_head-edit.php', 'catchevolution_posts_id_column_css' );


/**
 * Function to pass the variables for php to js file.
 * This funcition passes the slider effect variables.
 */

function catchevolution_pass_slider_value() {

    $options = catchevolution_get_options();

	$transition_effect = $options['transition_effect'];

	$transition_delay = $options['transition_delay'] * 1000;

	$transition_duration = $options['transition_duration'] * 1000;

	wp_localize_script(
		'catchevolution-slider',
		'js_value',
		array(
			'transition_effect' => $transition_effect,
			'transition_delay' => $transition_delay,
			'transition_duration' => $transition_duration
		)

	);

}//catchevolution_pass_slider_value
add_action( 'wp_enqueue_scripts', 'catchevolution_pass_slider_value' );


if ( ! function_exists( 'catchevolution_sliders' ) ) :
/**
 * This function to display featured posts slider
 *
 * @get the data value from theme options
 * @displays on the index
 *
 * @useage Featured Image, Title and Excerpt of Post
 *
 * @uses set_transient and delete_transient
 */
function catchevolution_sliders() {

    $options = catchevolution_get_options();
	$postperpage = $options['slider_qty'];
	$layout = $options['sidebar_layout'];

	//delete_transient( 'catchevolution_sliders' );

	// This function passes the value of slider effect to js file
    if ( function_exists( 'catchevolution_pass_slider_value' ) ) {
      	catchevolution_pass_slider_value();
  	}

	if ( ( !$catchevolution_sliders = get_transient( 'catchevolution_sliders' ) ) && !empty( $options['featured_slider'] ) ) {
		echo '<!-- refreshing cache -->';

		$catchevolution_sliders = '
		<div id="slider" class="post-slider">
			<section id="slider-wrap">';
			$loop = new WP_Query( array(
				'posts_per_page' => $postperpage,
				'post__in'		 => $options['featured_slider'],
				'orderby' 		 => 'post__in',
				'ignore_sticky_posts' => 1 // ignore sticky posts
			));

			$i=0; while ( $loop->have_posts()) : $loop->the_post(); $i++;
				$title_attribute = esc_attr( apply_filters( 'the_title', get_the_title() ) );

				if ( $i == 1 ) { $classes = "slides displayblock"; } else { $classes = "slides displaynone"; }

				$catchevolution_sliders .= '
				<div class="'.$classes.'">
					<a href="' . esc_url( get_permalink() ) . '" title="'.sprintf( esc_attr__( 'Permalink to %s', 'catch-evolution' ), the_title_attribute( 'echo=0' ) ).'" rel="bookmark">
						'.get_the_post_thumbnail().'
					</a>
					<div class="featured-text">
						<div class="featured-text-wrap">'
							.the_title( '<span class="slider-title">','</span>', false ).' <span class="sep">:</span>
							<span class="slider-excerpt">'.get_the_excerpt().'</span>
						</div>
					</div><!-- .featured-text -->
				</div> <!-- .slides -->';
			endwhile; wp_reset_postdata();
		$catchevolution_sliders .= '
			</section> <!-- .slider-wrap -->
			<div id="controllers">
			</div><!-- #controllers -->
		</div> <!-- #featured-slider -->';
		set_transient( 'catchevolution_sliders', $catchevolution_sliders, 86940 );
	}
	echo $catchevolution_sliders;
}
endif; //catchevolution_sliders

if ( ! function_exists( 'catchevolution_slider_display' ) ) :
/**
 * Display slider
 */
function catchevolution_slider_display() {
	global $post, $wp_query;
   	$options = catchevolution_get_options();
	$enableslider = $options['enable_slider'];
	$featuredslider = $options['featured_slider'];

	// Front page displays in Reading Settings
	$page_on_front = get_option('page_on_front') ;
	$page_for_posts = get_option('page_for_posts');

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	if ( ( 'enable-slider-allpage' == $enableslider ) || ( ( is_front_page() || ( is_home() && $page_id != $page_for_posts ) ) && 'enable-slider-homepage' == $enableslider ) ) :

		// Select Slider
		if ( !empty( $featuredslider ) ) {
			catchevolution_sliders();
		}

	endif;

}
endif; //catchevolution_slider_display

add_action( 'catchevolution_content', 'catchevolution_slider_display', 10 );


/**
 * Alter the query for the main loop in home page
 * @uses pre_get_posts hook
 */
function catchevolution_alter_home( $query ){
	if ( $query->is_main_query() && $query->is_home() ) {

	    $options = catchevolution_get_options();
		$cats = $options['front_page_category'];

	    if ( $options['exclude_slider_post'] != "0" && !empty( $options['featured_slider'] ) ) {
			$query->query_vars['post__not_in'] = $options['featured_slider'];
		}
		if ( is_array( $cats ) && !in_array( '0', $cats ) ) {
			$query->query_vars['category__in'] = $cats;
		}
	}
}
add_action( 'pre_get_posts','catchevolution_alter_home' );


/**
 * Replacing classed in default wp_page_menu
 *
 * REPLACE "current_page_item" WITH CLASS "current-menu-item"
 * REPLACE "current_page_ancestor" WITH CLASS "current-menu-ancestor"
 */
function current_to_active($text){
	$replace = array(
		// List of classes to replace with "active"
		'current_page_item' => 'current-menu-item',
		'current_page_ancestor' => 'current-menu-ancestor',
	);
	$text = str_replace(array_keys($replace), $replace, $text);
		return $text;
	}
add_filter( 'wp_page_menu', 'current_to_active' );


if ( ! function_exists( 'catchevolution_comment_form_fields' ) ) :
/**
 * Altering Comment Form Fields
 * @uses comment_form_default_fields filter
 */
function catchevolution_comment_form_fields( $fields ) {
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$commenter = wp_get_current_commenter();
    $fields['author'] = '<p class="comment-form-author"><label for="author">' . esc_attr__( 'Name', 'catch-evolution' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
    $fields['email'] = '<p class="comment-form-email"><label for="email">' . esc_attr__( 'Email', 'catch-evolution' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
    return $fields;

}
endif; //catchevolution_comment_form_fields

add_filter( 'comment_form_default_fields', 'catchevolution_comment_form_fields' );


/**
 * Redirect WordPress Feeds To FeedBurner
 */
function catchevolution_rss_redirect() {

    $options = catchevolution_get_options();

    if ($options['feed_url']) {
		$url = 'Location: '.$options['feed_url'];
		if ( is_feed() && !preg_match('/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT']))
		{
			header($url);
			header('HTTP/1.1 302 Temporary Redirect');
		}
	}
}
add_action('template_redirect', 'catchevolution_rss_redirect');


/**
 * shows footer content
 */
function catchevolution_footer_content() {
	//delete_transient( 'catchevolution_footer_content_new' );

	if ( ( !$catchevolution_footer_content = get_transient( 'catchevolution_footer_content_new' ) ) ) {
		echo '<!-- refreshing cache -->';

		$catchevolution_footer_content = catchevolution_assets();

    	set_transient( 'catchevolution_footer_content_new', $catchevolution_footer_content, 86940 );
    }
	echo $catchevolution_footer_content;
}
add_action( 'catchevolution_site_generator', 'catchevolution_footer_content', 15 );


if ( ! function_exists( 'catchevolution_social_networks' ) ) :
/**
 * This function for social links display
 *
 * @fetch links through Theme Options
 * @use in widget
 * @social links, Facebook, Twitter and RSS
  */
function catchevolution_social_networks() {
	//delete_transient( 'catchevolution_social_networks' );

	// get the data value from theme options
	$options = catchevolution_get_options();

    $elements = array();

	$elements = array( 	$options['social_facebook'],
						$options['social_twitter'],
						$options['social_x'],
						$options['social_googleplus'],
						$options['social_linkedin'],
						$options['social_pinterest'],
						$options['social_youtube'],
						$options['social_vimeo'],
						$options['social_aim'],
						$options['social_myspace'],
						$options['social_flickr'],
						$options['social_tumblr'],
						$options['social_deviantart'],
						$options['social_dribbble'],
						$options['social_myspace'],
						$options['social_wordpress'],
						$options['social_rss'],
						$options['social_slideshare'],
						$options['social_instagram'],
						$options['social_skype'],
						$options['social_soundcloud'],
						$options['social_email'],
						$options['social_contact'],
						$options['social_xing'],
						$options['social_meetup'],
						$options['social_goodreads'],
						$options['social_github'],
						$options['social_vk'],
						$options['social_spotify'],
						$options['social_tiktok'],
						$options['social_bluesky'],
						$options['social_threads']
					);
	$flag = 0;
	if ( !empty( $elements ) ) {
		foreach( $elements as $option) {
			if ( !empty( $option ) ) {
				$flag = 1;
			}
			else {
				$flag = 0;
			}
			if ( $flag == 1 ) {
				break;
			}
		}
	}

	if ( ( !$catchevolution_social_networks = get_transient( 'catchevolution_social_networks' ) ) && ( $flag == 1 ) )  {
		echo '<!-- refreshing cache -->';

		$catchevolution_social_networks .='
		<div class="social-profile"><ul>';

			//facebook
			if ( !empty( $options['social_facebook'] ) ) {
				$catchevolution_social_networks .=
					'<li class="facebook"><a href="'.esc_url( $options['social_facebook'] ).'" title="'. esc_attr__( 'Facebook', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Facebook', 'catch-evolution' ).'</a></li>';
			}
			//Twitter
			if ( !empty( $options['social_twitter'] ) ) {
				$catchevolution_social_networks .=
					'<li class="twitter"><a href="'.esc_url( $options['social_twitter'] ).'" title="'. esc_attr__( 'Twitter', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Twitter', 'catch-evolution' ).'</a></li>';
			}
			//X Twitter
			if (!empty($options['social_x'])) {
				$catchevolution_social_networks .=
					'<li class="x"><a href="' . esc_url($options['social_x']) . '" title="' . esc_attr__('X Twitter', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('X Twitter', 'catch-evolution') . '</a></li>';
			}
			//Google+
			if ( !empty( $options['social_googleplus'] ) ) {
				$catchevolution_social_networks .=
					'<li class="google-plus"><a href="'.esc_url( $options['social_googleplus'] ).'" title="'. esc_attr__( 'Google+', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Google+', 'catch-evolution' ).'</a></li>';
			}
			//Linkedin
			if ( !empty( $options['social_linkedin'] ) ) {
				$catchevolution_social_networks .=
					'<li class="linkedin"><a href="'.esc_url( $options['social_linkedin'] ).'" title="'. esc_attr__( 'Linkedin', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Linkedin', 'catch-evolution' ).'</a></li>';
			}
			//Pinterest
			if ( !empty( $options['social_pinterest'] ) ) {
				$catchevolution_social_networks .=
					'<li class="pinterest"><a href="'.esc_url( $options['social_pinterest'] ).'" title="'. esc_attr__( 'Pinterest', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Pinterest', 'catch-evolution' ).'</a></li>';
			}
			//Youtube
			if ( !empty( $options['social_youtube'] ) ) {
				$catchevolution_social_networks .=
					'<li class="you-tube"><a href="'.esc_url( $options['social_youtube'] ).'" title="'. esc_attr__( 'YouTube', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'YouTube', 'catch-evolution' ).'</a></li>';
			}
			//Vimeo
			if ( !empty( $options['social_vimeo'] ) ) {
				$catchevolution_social_networks .=
					'<li class="viemo"><a href="'.esc_url( $options['social_vimeo'] ).'" title="'. esc_attr__( 'Vimeo', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Vimeo', 'catch-evolution' ).'</a></li>';
			}
			//Slideshare
			if ( !empty( $options['social_aim'] ) ) {
				$catchevolution_social_networks .=
					'<li class="aim"><a href="'.esc_url( $options['social_aim'] ).'" title="'. esc_attr__( 'AIM', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'AIM', 'catch-evolution' ).'</a></li>';
			}
			//MySpace
			if ( !empty( $options['social_myspace'] ) ) {
				$catchevolution_social_networks .=
					'<li class="myspace"><a href="'.esc_url( $options['social_myspace'] ).'" title="'. esc_attr__( 'MySpace', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'MySpace', 'catch-evolution' ).'</a></li>';
			}
			//Flickr
			if ( !empty( $options['social_flickr'] ) ) {
				$catchevolution_social_networks .=
					'<li class="flickr"><a href="'.esc_url( $options['social_flickr'] ).'" title="'. esc_attr__( 'Flickr', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Flickr', 'catch-evolution' ).'</a></li>';
			}
			//Tumblr
			if ( !empty( $options['social_tumblr'] ) ) {
				$catchevolution_social_networks .=
					'<li class="tumblr"><a href="'.esc_url( $options['social_tumblr'] ).'" title="'. esc_attr__( 'Tumblr', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Tumblr', 'catch-evolution' ).'</a></li>';
			}
			//deviantART
			if ( !empty( $options['social_deviantart'] ) ) {
				$catchevolution_social_networks .=
					'<li class="deviantart"><a href="'.esc_url( $options['social_deviantart'] ).'" title="'. esc_attr__( 'deviantART', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'deviantART', 'catch-evolution' ).'</a></li>';
			}
			//Dribbble
			if ( !empty( $options['social_dribbble'] ) ) {
				$catchevolution_social_networks .=
					'<li class="dribbble"><a href="'.esc_url( $options['social_dribbble'] ).'" title="'. esc_attr__( 'Dribbble', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Dribbble', 'catch-evolution' ).'</a></li>';
			}
			//WordPress
			if ( !empty( $options['social_wordpress'] ) ) {
				$catchevolution_social_networks .=
					'<li class="wordpress"><a href="'.esc_url( $options['social_wordpress'] ).'" title="'. esc_attr__( 'WordPress', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'WordPress', 'catch-evolution' ).'</a></li>';
			}
			//RSS
			if ( !empty( $options['social_rss'] ) ) {
				$catchevolution_social_networks .=
					'<li class="rss"><a href="'.esc_url( $options['social_rss'] ).'" title="'. esc_attr__( 'RSS', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'RSS', 'catch-evolution' ).'</a></li>';
			}
			//Slideshare
			if ( !empty( $options['social_slideshare'] ) ) {
				$catchevolution_social_networks .=
					'<li class="slideshare"><a href="'.esc_url( $options['social_slideshare'] ).'" title="'. esc_attr__( 'Slideshare', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Slideshare', 'catch-evolution' ).'</a></li>';
			}
			//Instagram
			if ( !empty( $options['social_instagram'] ) ) {
				$catchevolution_social_networks .=
					'<li class="instagram"><a href="'.esc_url( $options['social_instagram'] ).'" title="'. esc_attr__( 'Instagram', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Instagram', 'catch-evolution' ).'</a></li>';
			}
			//Skype
			if ( !empty( $options['social_skype'] ) ) {
				$catchevolution_social_networks .=
					'<li class="skype"><a href="'.esc_attr( $options['social_skype'] ).'" title="'. esc_attr__( 'Skype', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Skype', 'catch-evolution' ).'</a></li>';
			}
			//Soundcloud
			if ( !empty( $options['social_soundcloud'] ) ) {
				$catchevolution_social_networks .=
					'<li class="soundcloud"><a href="'.esc_url( $options['social_soundcloud'] ).'" title="'. esc_attr__( 'Soundcloud', 'catch-evolution' ) .'" target="_blank">'. esc_attr__( 'Soundcloud', 'catch-evolution' ) .'</a></li>';
			}
			//Email
			if ( !empty( $options['social_email'] )  && is_email( $options['social_email'] ) ) {
				$catchevolution_social_networks .=
					'<li class="email"><a href="mailto:'.sanitize_email( $options['social_email'] ).'" title="'. esc_attr__( 'Email', 'catch-evolution' ) .'" target="_blank">'. esc_attr__( 'Email', 'catch-evolution' ) .'</a></li>';
			}
			//Contact
			if ( !empty( $options['social_contact'] ) ) {
				$catchevolution_social_networks .=
					'<li class="contactus"><a href="'.esc_url( $options['social_contact'] ).'" title="'. esc_attr__( 'Contact', 'catch-evolution' ) .'">'. esc_attr__( 'Contact', 'catch-evolution' ) .'</a></li>';
			}
			//Xing
			if ( !empty( $options['social_xing'] ) ) {
				$catchevolution_social_networks .=
					'<li class="xing"><a href="'.esc_url( $options['social_xing'] ).'" title="'. esc_attr__( 'Xing', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Xing', 'catch-evolution' ).'</a></li>';
			}
			//Meetup
			if ( !empty( $options['social_meetup'] ) ) {
				$catchevolution_social_networks .=
					'<li class="meetup"><a href="'.esc_url( $options['social_meetup'] ).'" title="'. esc_attr__( 'Meetup', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Meetup', 'catch-evolution' ).'</a></li>';
			}
			//Goodreads
			if (!empty($options['social_goodreads'])) {
				$catchevolution_social_networks .=
					'<li class="goodreads"><a href="' . esc_url($options['social_goodreads']) . '" title="' . esc_attr__('Goodreads', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Goodreads', 'catch-evolution') . '</a></li>';
			}

			//Github
			if (!empty($options['social_github'])) {
				$catchevolution_social_networks .=
					'<li class="github"><a href="' . esc_url($options['social_github']) . '" title="' . esc_attr__('Github', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Github', 'catch-evolution') . '</a></li>';
			}

			//VK
			if (!empty($options['social_vk'])) {
				$catchevolution_social_networks .=
					'<li class="vk"><a href="' . esc_url($options['social_vk']) . '" title="' . esc_attr__('VK', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('VK', 'catch-evolution') . '</a></li>';
			}
			//Spotify
			if (!empty($options['social_spotify'])) {
				$catchevolution_social_networks .=
					'<li class="spotify"><a href="' . esc_url($options['social_spotify']) . '" title="' . esc_attr__('Spotify', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Spotify', 'catch-evolution') . '</a></li>';
			}
			//Tiktok
			if (!empty($options['social_tiktok'])) {
				$catchevolution_social_networks .=
					'<li class="tiktok"><a href="' . esc_url($options['social_tiktok']) . '" title="' . esc_attr__('Tiktok', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Tiktok', 'catch-evolution') . '</a></li>';
			}
			//Bluesky
			if (!empty($options['social_bluesky'])) {
				$catchevolution_social_networks .=
					'<li class="bluesky"><a href="' . esc_url($options['social_bluesky']) . '" title="' . esc_attr__('Bluesky', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Bluesky', 'catch-evolution') . '</a></li>';
			}
			//Threads
			if (!empty($options['social_threads'])) {
				$catchevolution_social_networks .=
					'<li class="threads"><a href="' . esc_url($options['social_threads']) . '" title="' . esc_attr__('Threads', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Threads', 'catch-evolution') . '</a></li>';
			}

			$catchevolution_social_networks .='
		</ul></div>';

		set_transient( 'catchevolution_social_networks', $catchevolution_social_networks, 86940 );
	}
	echo $catchevolution_social_networks;
}
endif; //catchevolution_social_networks


/**
 * Footer Social Icons
 *
 */
function catchevolution_footer_social() {
	$options = catchevolution_get_options();

	if ( !empty( $options['disable_footer_social'] ) ) :
		return catchevolution_social_networks();
	endif;
}
add_action( 'catchevolution_site_generator', 'catchevolution_footer_social', 10 );


if ( ! function_exists( 'catchevolution_social_search' ) ) :
/**
 * This function for social links display
 *
 * @fetch links through Theme Options
 * @use in widget
 * @social links, Facebook, Twitter and RSS
  */
function catchevolution_social_search() {
	//delete_transient( 'catchevolution_social_search' );

	// get the data value from theme options
	$options = catchevolution_get_options();

    $elements = array();

	$elements = array( 	$options['social_facebook'],
						$options['social_twitter'],
						$options['social_x'],
						$options['social_googleplus'],
						$options['social_linkedin'],
						$options['social_pinterest'],
						$options['social_youtube'],
						$options['social_vimeo'],
						$options['social_aim'],
						$options['social_myspace'],
						$options['social_flickr'],
						$options['social_tumblr'],
						$options['social_deviantart'],
						$options['social_dribbble'],
						$options['social_myspace'],
						$options['social_wordpress'],
						$options['social_rss'],
						$options['social_slideshare'],
						$options['social_instagram'],
						$options['social_skype'],
						$options['social_soundcloud'],
						$options['social_email'],
						$options['social_contact'],
						$options['social_xing'],
						$options['social_meetup'],
						$options['social_goodreads'],
						$options['social_github'],
						$options['social_vk'],
						$options['social_spotify'],
						$options['social_tiktok'],
						$options['social_bluesky'],
						$options['social_threads']
					);
	$flag = 0;
	if ( !empty( $elements ) ) {
		foreach( $elements as $option) {
			if ( !empty( $option ) ) {
				$flag = 1;
			}
			else {
				$flag = 0;
			}
			if ( $flag == 1 ) {
				break;
			}
		}
	}

	if ( ( !$catchevolution_social_search = get_transient( 'catchevolution_social_search' ) ) && ( $flag == 1 ) )  {
		echo '<!-- refreshing cache -->';

		$catchevolution_social_search .='
		<div class="social-profile"><ul>';

			if ( !empty( $options['social_facebook'] ) ) {
				$catchevolution_social_search .=
					'<li class="facebook"><a href="'.esc_url( $options['social_facebook'] ).'" title="'. esc_attr__( 'Facebook', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Facebook', 'catch-evolution' ).'</a></li>';
			}
			//Twitter
			if ( !empty( $options['social_twitter'] ) ) {
				$catchevolution_social_search .=
					'<li class="twitter"><a href="'.esc_url( $options['social_twitter'] ).'" title="'. esc_attr__( 'Twitter', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Twitter', 'catch-evolution' ).'</a></li>';
			}
			//X Twitter
			if (!empty($options['social_x'])) {
				$catchevolution_social_search .=
					'<li class="x"><a href="' . esc_url($options['social_x']) . '" title="' . esc_attr__('X Twitter', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('X Twitter', 'catch-evolution') . '</a></li>';
			}
			//Google+
			if ( !empty( $options['social_googleplus'] ) ) {
				$catchevolution_social_search .=
					'<li class="google-plus"><a href="'.esc_url( $options['social_googleplus'] ).'" title="'. esc_attr__( 'Google+', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Google+', 'catch-evolution' ).'</a></li>';
			}
			//Linkedin
			if ( !empty( $options['social_linkedin'] ) ) {
				$catchevolution_social_search .=
					'<li class="linkedin"><a href="'.esc_url( $options['social_linkedin'] ).'" title="'. esc_attr__( 'Linkedin', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Linkedin', 'catch-evolution' ).'</a></li>';
			}
			//Pinterest
			if ( !empty( $options['social_pinterest'] ) ) {
				$catchevolution_social_search .=
					'<li class="pinterest"><a href="'.esc_url( $options['social_pinterest'] ).'" title="'. esc_attr__( 'Pinterest', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Pinterest', 'catch-evolution' ).'</a></li>';
			}
			//Youtube
			if ( !empty( $options['social_youtube'] ) ) {
				$catchevolution_social_search .=
					'<li class="you-tube"><a href="'.esc_url( $options['social_youtube'] ).'" title="'. esc_attr__( 'YouTube', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'YouTube', 'catch-evolution' ).'</a></li>';
			}
			//Vimeo
			if ( !empty( $options['social_vimeo'] ) ) {
				$catchevolution_social_search .=
					'<li class="viemo"><a href="'.esc_url( $options['social_vimeo'] ).'" title="'. esc_attr__( 'Vimeo', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Vimeo', 'catch-evolution' ).'</a></li>';
			}
			//Slideshare
			if ( !empty( $options['social_aim'] ) ) {
				$catchevolution_social_search .=
					'<li class="aim"><a href="'.esc_url( $options['social_aim'] ).'" title="'. esc_attr__( 'AIM', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'AIM', 'catch-evolution' ).'</a></li>';
			}
			//MySpace
			if ( !empty( $options['social_myspace'] ) ) {
				$catchevolution_social_search .=
					'<li class="myspace"><a href="'.esc_url( $options['social_myspace'] ).'" title="'. esc_attr__( 'MySpace', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'MySpace', 'catch-evolution' ).'</a></li>';
			}
			//Flickr
			if ( !empty( $options['social_flickr'] ) ) {
				$catchevolution_social_search .=
					'<li class="flickr"><a href="'.esc_url( $options['social_flickr'] ).'" title="'. esc_attr__( 'Flickr', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Flickr', 'catch-evolution' ).'</a></li>';
			}
			//Tumblr
			if ( !empty( $options['social_tumblr'] ) ) {
				$catchevolution_social_search .=
					'<li class="tumblr"><a href="'.esc_url( $options['social_tumblr'] ).'" title="'. esc_attr__( 'Tumblr', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Tumblr', 'catch-evolution' ).'</a></li>';
			}
			//deviantART
			if ( !empty( $options['social_deviantart'] ) ) {
				$catchevolution_social_search .=
					'<li class="deviantart"><a href="'.esc_url( $options['social_deviantart'] ).'" title="'. esc_attr__( 'deviantART', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'deviantART', 'catch-evolution' ).'</a></li>';
			}
			//Dribbble
			if ( !empty( $options['social_dribbble'] ) ) {
				$catchevolution_social_search .=
					'<li class="dribbble"><a href="'.esc_url( $options['social_dribbble'] ).'" title="'. esc_attr__( 'Dribbble', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Dribbble', 'catch-evolution' ).'</a></li>';
			}
			//WordPress
			if ( !empty( $options['social_wordpress'] ) ) {
				$catchevolution_social_search .=
					'<li class="wordpress"><a href="'.esc_url( $options['social_wordpress'] ).'" title="'. esc_attr__( 'WordPress', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'WordPress', 'catch-evolution' ).'</a></li>';
			}
			//RSS
			if ( !empty( $options['social_rss'] ) ) {
				$catchevolution_social_search .=
					'<li class="rss"><a href="'.esc_url( $options['social_rss'] ).'" title="'. esc_attr__( 'RSS', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'RSS', 'catch-evolution' ).'</a></li>';
			}
			//Slideshare
			if ( !empty( $options['social_slideshare'] ) ) {
				$catchevolution_social_search .=
					'<li class="slideshare"><a href="'.esc_url( $options['social_slideshare'] ).'" title="'. esc_attr__( 'Slideshare', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Slideshare', 'catch-evolution' ).'</a></li>';
			}
			//Instagram
			if ( !empty( $options['social_instagram'] ) ) {
				$catchevolution_social_search .=
					'<li class="instagram"><a href="'.esc_url( $options['social_instagram'] ).'" title="'. esc_attr__( 'Instagram', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Instagram', 'catch-evolution' ).'</a></li>';
			}
			//Skype
			if ( !empty( $options['social_skype'] ) ) {
				$catchevolution_social_search .=
					'<li class="skype"><a href="'.esc_attr( $options['social_skype'] ).'" title="'. esc_attr__( 'Skype', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Skype', 'catch-evolution' ).'</a></li>';
			}
			//Soundcloud
			if ( !empty( $options['social_soundcloud'] ) ) {
				$catchevolution_social_search .=
					'<li class="soundcloud"><a href="'.esc_url( $options['social_soundcloud'] ).'" title="'. esc_attr__( 'Soundcloud', 'catch-evolution' ) .'" target="_blank">'. esc_attr__( 'Soundcloud', 'catch-evolution' ) .'</a></li>';
			}
			//Email
			if ( !empty( $options['social_email'] )  && is_email( $options['social_email'] ) ) {
				$catchevolution_social_search .=
					'<li class="email"><a href="mailto:'.sanitize_email( $options['social_email'] ).'" title="'. esc_attr__( 'Email', 'catch-evolution' ) .'" target="_blank">'. esc_attr__( 'Email', 'catch-evolution' ) .'</a></li>';
			}
			//Contact
			if ( !empty( $options['social_contact'] ) ) {
				$catchevolution_social_search .=
					'<li class="contactus"><a href="'.esc_url( $options['social_contact'] ).'" title="'. esc_attr__( 'Contact', 'catch-evolution' ) .'">'. esc_attr__( 'Contact', 'catch-evolution' ) .'</a></li>';
			}
			//Xing
			if ( !empty( $options['social_xing'] ) ) {
				$catchevolution_social_search .=
					'<li class="xing"><a href="'.esc_url( $options['social_xing'] ).'" title="'. esc_attr__( 'Xing', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Xing', 'catch-evolution' ).'</a></li>';
			}
			//Meetup
			if ( !empty( $options['social_meetup'] ) ) {
				$catchevolution_social_search .=
					'<li class="meetup"><a href="'.esc_url( $options['social_meetup'] ).'" title="'. esc_attr__( 'Meetup', 'catch-evolution' ) .'" target="_blank">'.esc_attr__( 'Meetup', 'catch-evolution' ).'</a></li>';
			}
			//Goodreads
			if (!empty($options['social_goodreads'])) {
				$catchevolution_social_search .=
					'<li class="goodreads"><a href="' . esc_url($options['social_goodreads']) . '" title="' . esc_attr__('Goodreads', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Goodreads', 'catch-evolution') . '</a></li>';
			}

			//Github
			if (!empty($options['social_github'])) {
				$catchevolution_social_search .=
					'<li class="github"><a href="' . esc_url($options['social_github']) . '" title="' . esc_attr__('Github', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Github', 'catch-evolution') . '</a></li>';
			}

			//VK
			if (!empty($options['social_vk'])) {
				$catchevolution_social_search .=
					'<li class="vk"><a href="' . esc_url($options['social_vk']) . '" title="' . esc_attr__('VK', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('VK', 'catch-evolution') . '</a></li>';
			}
			//Spotify
			if (!empty($options['social_spotify'])) {
				$catchevolution_social_search .=
					'<li class="spotify"><a href="' . esc_url($options['social_spotify']) . '" title="' . esc_attr__('Spotify', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Spotify', 'catch-evolution') . '</a></li>';
			}
			//Tiktok
			if (!empty($options['social_tiktok'])) {
				$catchevolution_social_search .=
					'<li class="tiktok"><a href="' . esc_url($options['social_tiktok']) . '" title="' . esc_attr__('Tiktok', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Tiktok', 'catch-evolution') . '</a></li>';
			}
			//Bluesky
			if (!empty($options['social_bluesky'])) {
				$catchevolution_social_search .=
					'<li class="bluesky"><a href="' . esc_url($options['social_bluesky']) . '" title="' . esc_attr__('Bluesky', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Bluesky', 'catch-evolution') . '</a></li>';
			}
			//Threads
			if (!empty($options['social_threads'])) {
				$catchevolution_social_search .=
					'<li class="threads"><a href="' . esc_url($options['social_threads']) . '" title="' . esc_attr__('Threads', 'catch-evolution') . '" target="_blank" rel="nofollow">' . esc_attr__('Threads', 'catch-evolution') . '</a></li>';
			}
			//Search Icon
			$catchevolution_social_search .= '<li class="social-search">' . get_search_form( false ) . '</li>';

			$catchevolution_social_search .='
		</ul></div>';

		set_transient( 'catchevolution_social_search', $catchevolution_social_search, 86940 );
	}
	echo $catchevolution_social_search;
}
endif; //catchevolution_social_search


/**
 * Site Verification  and Webmaster Tools
 *
 * If user sets the code we're going to display meta verification
 * @get the data value from theme options
 * @uses wp_head action to add the code in the header
 * @uses set_transient and delete_transient API for cache
 */
function catchevolution_site_verification() {
	//delete_transient( 'catchevolution_site_verification' );

	if ( ( !$catchevolution_site_verification = get_transient( 'catchevolution_site_verification' ) ) )  {

		// get the data value from theme options
		$options = catchevolution_get_options();
		echo '<!-- refreshing cache -->';

		$catchevolution_site_verification = '';

		//site stats, analytics header code
		if ( !empty( $options['analytic_header'] ) ) {
			$catchevolution_site_verification .=  $options['analytic_header'] ;
		}

		set_transient( 'catchevolution_site_verification', $catchevolution_site_verification, 86940 );
	}
	echo $catchevolution_site_verification;
}
add_action('wp_head', 'catchevolution_site_verification');


/**
 * This function loads the Footer Code such as Add this code from the Theme Option
 *
 * @get the data value from theme options
 * @load on the footer ONLY
 * @uses wp_footer action to add the code in the footer
 * @uses set_transient and delete_transient
 */
function catchevolution_footercode() {
	//delete_transient( 'catchevolution_footercode' );

	if ( ( !$catchevolution_footercode = get_transient( 'catchevolution_footercode' ) ) ) {

		// get the data value from theme options
   	 	$options = catchevolution_get_options();
		echo '<!-- refreshing cache -->';

		//site stats, analytics header code
		if ( !empty( $options['analytic_footer'] ) ) {
			$catchevolution_footercode =  $options['analytic_footer'] ;
		}

		set_transient( 'catchevolution_footercode', $catchevolution_footercode, 86940 );
	}
	echo $catchevolution_footercode;
}
add_action('wp_footer', 'catchevolution_footercode');


/**
 * Third Sidebar
 *
 * @Hooked in catchevolution_before_primary
 * @since Catch Evolution 1.1
 */

function catchevolution_third_sidebar() {
	get_sidebar( 'third' );
}
add_action( 'catchevolution_after_contentsidebarwrap', 'catchevolution_third_sidebar', 10 );
