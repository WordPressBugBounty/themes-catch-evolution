<?php
/**
 * The Footer widget areas.
 *
 * @package Catch Evolution
 */


/**
 * catchevolution_before_third hook
 */
do_action( 'catchevolution_before_third' );

	$catchevolution_layout = catchevolution_get_theme_layout();

if ( 'three-columns' == $catchevolution_layout || is_page_template( 'page-three-columns.php' ) || 'three-columns-sidebar' == $catchevolution_layout || is_page_template( 'page-three-columns-sidebar.php' ) ) : ?>
    <div id="third" class="widget-area sidebar-three-columns" role="complementary">
		<?php
		/**
		 * catchevolution_before_third hook
		 */
		do_action( 'catchevolution_before_third' );

		if ( is_active_sidebar( 'catchevolution_third' ) ) :
			dynamic_sidebar( 'catchevolution_third' );
		endif;

		/**
		 * catchevolution_after_third hook
		 */
		do_action( 'catchevolution_after_third' ); ?>

    </div><!-- #sidebar-third-column .widget-area -->

<?php endif;

/**
 * catchevolution_after_third hook
 */
do_action( 'catchevolution_after_third' );