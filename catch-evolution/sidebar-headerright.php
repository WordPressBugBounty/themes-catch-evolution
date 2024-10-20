<?php
/**
 * The Footer widget areas.
 *
 * @package Catch Evolution
 */
?>

<?php
/** 
 *The header right widget area is triggered if any of the areas
 */
if ( is_active_sidebar( 'catchevolution_header_right_sidebar' ) ) : ?>
<div id="sidebar-header-right" class="widget-area sidebar-top clearfix">
	<?php dynamic_sidebar( 'catchevolution_header_right_sidebar' ); ?>
</div> <!-- #sidebar-top -->
<?php endif;