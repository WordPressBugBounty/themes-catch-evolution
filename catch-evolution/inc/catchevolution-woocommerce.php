<?php
/**
 * Adding support for WooCommerce Plugin
 * 
 * uses remove_action to remove the WooCommerce Wrapper and add_action to add Simple Catch Wrapper
 *
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if ( ! function_exists( 'catchevolution_woocommerce_start' ) ) :
function catchevolution_woocommerce_start() {
	echo '<div id="catchevolution-woocommerce" class="hentry">';
}
endif; //catchevolution_woocommerce_start
add_action('woocommerce_before_main_content', 'catchevolution_woocommerce_start', 15);

if ( ! function_exists( 'catchevolution_woocommerce_end' ) ) :
function catchevolution_woocommerce_end() {
	echo '</div></div></div>';
}
endif; //catchevolution_woocommerce_end
add_action('woocommerce_after_main_content', 'catchevolution_woocommerce_end', 15);