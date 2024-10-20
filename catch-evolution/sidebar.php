<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package Catch Evolution
 */

/**
 * catchevolution_before_secondary hook
 */
do_action( 'catchevolution_before_secondary' );

$catchevolution_layout = catchevolution_get_theme_layout();

// WooCommerce Settings
if ( !is_active_sidebar( 'catchevolution_woocommerce_sidebar' ) && ( class_exists( 'Woocommerce' ) && is_woocommerce() ) ) {
	$catchevolution_layout = 'no-sidebar';
}


if ( 'left-sidebar' == $catchevolution_layout || 'right-sidebar' == $catchevolution_layout || 'three-columns' == $catchevolution_layout ) {
	?>
    <div id="secondary" class="widget-area" role="complementary">
		<?php
		/**
		 * catchevolution_before_widget hook
		 */
		do_action( 'catchevolution_before_widget' );

		if ( is_active_sidebar( 'catchevolution_woocommerce_sidebar' ) && ( class_exists( 'Woocommerce' ) && is_woocommerce() ) ) :
			dynamic_sidebar( 'catchevolution_woocommerce_sidebar' );
      	elseif ( is_active_sidebar( 'sidebar-1' ) ) :
			dynamic_sidebar( 'sidebar-1' );
		else :
			echo '<!-- No Widget in Sidebar -->';
		endif; ?>

			<?php
		/**
		 * catchevolution_after_widget hook
		 */
		do_action( 'catchevolution_after_widget' ); ?>

    </div><!-- #secondary .widget-area -->
<?php
}

/**
 * catchevolution_after_secondary hook
 */
do_action( 'catchevolution_after_secondary' );