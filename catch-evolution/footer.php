<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Catch Evolution
 */
?>
		</div><!-- #content-sidebar-wrap -->
        <?php 
		/** 
		 * catchevolution_after_contentsidebarwrap hook
		 *
         * @hooked catchevolution_third_sidebar - 10
		 */
		do_action( 'catchevolution_after_contentsidebarwrap' ); 
		?>   
	</div><!-- .wrapper -->
</div><!-- #main -->

<?php 
/** 
 * catchevolution_after_main hook
 */
do_action( 'catchevolution_after_main' ); 
?>    

<footer id="colophon" role="contentinfo">
	<?php
	/** 
	 * catchevolution_before_footer_menu hook
	 */
	do_action( 'catchevolution_before_footer_sidebar' ); 
		
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with three columns of widgets.
	 */
	get_sidebar( 'footer' );
		
	/** 
	 * catchevolution_before_footer_menu hook
	 */
	do_action( 'catchevolution_after_footer_sidebar' ); 		
    ?>
    
	<div id="site-generator">	
    	<div class="wrapper">	
			<?php 
            /** 
             * catchevolution_site_generator hook
             *
             * @hooked catchevolution_footer_social - 10
             * @hooked catchevolution_footer_content - 15
             */
            do_action( 'catchevolution_site_generator' ); ?> 
       	</div><!-- .wrapper -->
    </div><!-- #site-generator -->
       
</footer><!-- #colophon -->

</div><!-- #page -->

<?php 
/** 
 * catchevolution_after hook
 */
do_action( 'catchevolution_after' );
?>

<?php wp_footer(); ?>

</body>
</html>