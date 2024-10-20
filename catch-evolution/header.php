<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Catch Evolution
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'wp_body_open' );  ?>

<?php
/**
 * catchevolution_before hook
 */
do_action( 'catchevolution_before' ); ?>

<div id="page" class="hfeed site">

	<?php
    /**
     * catchevolution_before_header hook
     */
    do_action( 'catchevolution_before_header' ); ?>

	<header id="branding" role="banner">
    	<?php
		/**
		 * catchevolution_before_headercontent hook
		 *
		 * @hooked catchevolution_header_topsidebar - 10
		 */
		do_action( 'catchevolution_before_headercontent' ); ?>

    	<div id="header-content" class="clearfix">

        	<div class="wrapper">
				<?php
                /**
                 * catchevolution_headercontent hook
                 *
                 * @hooked catchevolution_headerdetails - 10
                 * @hooked catchevolution_header_rightpsidebar - 15
                 */
                do_action( 'catchevolution_headercontent' ); ?>
            </div><!-- .wrapper -->

      	</div><!-- #header-content -->

    	<?php
		/**
		 * catchevolution_after_headercontent hook
		 *
         * @hooked catchevolution_header_menu - 10
		 */
		do_action( 'catchevolution_after_headercontent' ); ?>

	</header><!-- #branding -->

        <?php
		/**
		 * catchevolution_after_header hook
		 *
		 * @hooked catchevolution_featured_header - 10
         * @hooked catchevolution_header_menu - 15
		 */
		 do_action( 'catchevolution_after_header' ); ?>

	<?php
    /**
     * catchevolution_before_main hook
     */
    do_action( 'catchevolution_before_main' );
    ?>

	<div id="main" class="clearfix">
    	<div class="wrapper">

 			<?php
			/**
			 * catchevolution_before_contentsidebarwrap hook
			 */
			do_action( 'catchevolution_before_contentsidebarwrap' );
			?>

            <div class="content-sidebar-wrap">

				<?php
                /**
                 * catchevolution_before_primary hook
                 *
                 * @hooked catchevolution_slider_display - 10 if full width image slide is selected
                 */
                do_action( 'catchevolution_before_primary' ); ?>

                <div id="primary">

                    <?php do_action( 'catchevolution_before_content' ); ?>

                    <div id="content" role="main">
                        <?php
                        /**
                         * catchevolution_content hook
                         *
                         * @hooked catchevolution_slider_display - 10 if full width image slide is not selected
                         */
                        do_action( 'catchevolution_content' );