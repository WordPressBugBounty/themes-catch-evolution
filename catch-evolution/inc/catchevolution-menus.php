<?php
/**
 * The template for displaying custom menus
 *
 * @package Catch Evolution
 */


if ( ! function_exists( 'catchevolution_page_menu_args' ) ) :
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function catchevolution_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
endif; //catchevolution_page_menu_args

add_filter( 'wp_page_menu_args', 'catchevolution_page_menu_args' );


if ( ! function_exists( 'catchevolution_page_menu_active' ) ) :
/**
 * Replacing classed in default wp_page_menu
 *
 * REPLACE "current_page_item" WITH CLASS "current-menu-item"
 * REPLACE "current_page_ancestor" WITH CLASS "current-menu-ancestor"
 */
function catchevolution_page_menu_active( $text ){
    $replace = array(
        // List of classes to replace with "active"
        'current_page_item' => 'current-menu-item',
        'current_page_ancestor' => 'current-menu-ancestor',
    );
    $text = str_replace(array_keys($replace), $replace, $text);
        return $text;
}
endif; //catchevolution_page_menu_active

add_filter( 'wp_page_menu', 'catchevolution_page_menu_active' );


if ( ! function_exists( 'catchevolution_wp_page_menu' ) ) :
/**
 * Remove div from wp_page_menu() and replace with ul.
 * @uses wp_page_menu filter
 */
function catchevolution_wp_page_menu( $page_markup ) {
    preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
        $divclass = $matches[1];
        $replace = array('<div class="'.$divclass.'">', '</div>');
        $new_markup = str_replace($replace, '', $page_markup);
        $new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
        return $new_markup;
}
endif; //catchevolution_wp_page_menu

add_filter( 'wp_page_menu', 'catchevolution_wp_page_menu' );


if ( ! function_exists( 'catchevolution_header_menu' ) ) :
/**
 * Header Menu
 *
 * @Hooked in catchevolution_after_header
 */
function catchevolution_header_menu() {
    //Getting Ready to load options data
    $options = catchevolution_get_options();
    $header_menu = $options['disable_header_menu'];

    //Check Disable Primary and has Secondary menu
    if ( empty ( $header_menu ) || has_nav_menu( 'secondary' ) ) :

        $classes = "mobile-menu-anchor page-menu";

        // Header Left Mobile Menu Anchor
        if ( has_nav_menu( 'primary' ) ) {
            $classes = "mobile-menu-anchor primary-menu";
        }
        ?>

        <div id="header-menu">

            <?php if ( empty ( $header_menu ) ) : ?>
                <div id="access" class="menu-access-wrap clearfix">
                    <div id="mobile-primary-menu" class="<?php echo $classes; ?>">
                        <button id="menu-toggle-primary" class="genericon genericon-menu">
                            <span class="mobile-menu-text"><?php esc_html_e( 'Menu', 'catch-evolution' ); ?></span>
                        </button>
                    </div><!-- #mobile-primary-menu -->

                    <div id="site-header-menu-primary" class="site-menu">
                        <nav id="access-primary-menu" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'catch-evolution' ); ?>">
                            <h3 class="screen-reader-text"><?php _e( 'Primary menu', 'catch-evolution' ); ?></h3>
                            <?php
                                if ( has_nav_menu( 'primary', 'catch-evolution' ) ) {
                                    $args = array(
                                        'theme_location'    => 'primary',
                                        'container_class'   => 'menu-header-container wrapper',
                                        'items_wrap'        => '<ul class="menu">%3$s</ul>'
                                    );
                                    wp_nav_menu( $args );
                                }
                                else {
                                    echo '<div class="menu-header-container wrapper">';
                                        wp_page_menu( array( 'menu_class'  => 'menu' ) );
                                    echo '</div>';
                                }
                            ?>
                        </nav><!-- #access-primary-menu -->
                    </div><!-- #site-header-menu-primary -->
                </div><!-- #access -->
            <?php endif; ?>

            <?php if ( has_nav_menu( 'secondary' ) ) :

                $menuclass = "mobile-disable";
                if ( !empty ($options['enable_menus'] ) ) :
                    $menuclass = "mobile-enable";
                endif;

                ?>
                <div id="access-secondary" class="menu-access-wrap clearfix <?php echo $menuclass; ?>">
                    <?php
                    if ( !empty ($options['enable_menus'] ) ) : ?>
                        <div id="mobile-secondary-menu" class="mobile-menu-anchor secondary-menu">
                            <button id="menu-toggle-secondary" class="genericon genericon-menu">
                                <span class="mobile-menu-text"><?php esc_html_e( 'Menu', 'catch-evolution' ); ?></span>
                            </button>
                        </div><!-- #mobile-header-right-menu -->
                    <?php endif; ?>

                    <div id="site-header-menu-secondary" class="site-menu">
                        <nav id="access-secondary-menu" role="navigation" aria-label="<?php esc_attr_e( 'Secondary Menu', 'catch-evolution' ); ?>">
                            <h3 class="screen-reader-text"><?php _e( 'Secondary menu', 'catch-evolution' ); ?></h3>
                            <?php wp_nav_menu( array( 'theme_location'  => 'secondary', 'container_class' => 'menu-secondary-container wrapper' ) );  ?>
                        </nav><!-- #access-secondary-menu -->
                    </div><!-- #site-header-menu-secondary -->
                </div><!-- #access-secondary -->
            <?php endif; ?>

        </div><!-- #header-menu -->
    <?php
    endif;
}
endif; //catchevolution_header_menu

// Load Header Menu in  catchevolution_after_header hook
add_action( 'catchevolution_after_header', 'catchevolution_header_menu', 15 );


if ( ! function_exists( 'catchevolution_header_top_menu' ) ) :
/**
 * Header Top Menu
 *
 * @Hooked in catchevolution_before_header
 */
function catchevolution_header_top_menu() {
    // Getting data from Theme Options
    $options = catchevolution_get_options();

    $classes = 'full-menu';
    $headerimage = '';
    ?>

    <?php if ( has_nav_menu( 'top', 'catch-evolution' ) ) : ?>
        <div id="fixed-header-top" class="<?php echo $classes; ?>">
            <div class="wrapper">
                <?php echo $headerimage; ?>

                <div id="access-top" class="menu-access-wrap clearfix">
                    <div id="mobile-top-menu" class="mobile-menu-anchor top-menu">
                        <button id="menu-toggle-top" class="genericon genericon-menu">
                            <span class="mobile-menu-text"><?php esc_html_e( 'Menu', 'catch-evolution' ) ?></span>
                        </button>
                    </div><!-- #mobile-top-menu -->

                    <div id="site-top-menu" class="site-menu">
                        <nav id="access-top-menu" class="top-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'catch-evolution' ); ?>">
                                <h3 class="screen-reader-text"><?php _e( 'Top Menu', 'catch-evolution' ); ?></h3>
                                <?php wp_nav_menu( array( 'theme_location'  => 'top', 'container' => 'false', 'items_wrap' => '<ul id="top-nav" class="menu">%3$s</ul>' ) ); ?>
                        </nav><!-- #access -->
                    </div><!-- #site-top-menu -->

                </div><!-- #access-top -->
            </div><!-- .wrapper -->
        </div><!-- #fixed-header-top -->
    <?php
    endif;

} // catchevolution_header_top_menu
endif;

add_action( 'catchevolution_before_header', 'catchevolution_header_top_menu', 10 );


if ( ! function_exists( 'catchevolution_footer_menu' ) ) :
/**
 * Footer Menu
*
 * @Hooked in catchevolution_after_footer_sidebar
 */
function catchevolution_footer_menu() {
    if ( has_nav_menu( 'footer' ) ) {
        // Check is footer menu is enable or not
        $options = catchevolution_get_options();

        $menuclass = "mobile-disable";

        if ( !empty ($options['enable_menus'] ) ) :
            $menuclass = "mobile-enable";
        endif;
        ?>

        <div id="access-footer" class="<?php echo $menuclass; ?>">
            <?php
            if ( empty ($options['disable_responsive'] ) && !empty ($options['enable_menus'] ) ) {
                    ?>
                <div id="mobile-footer-menu" class="menu-access-wrap clearfix">

                    <?php
                    $hide_mobile_menu_labels = isset( $options['hide_mobile_menu_labels'] ) ? $options['hide_mobile_menu_labels'] : 0;

                    $label = isset( $options['footer_mobile_menu_label'] ) ? $options['footer_mobile_menu_label'] : esc_html__( 'Menu', 'catch-evolution' );

                    $labelclass = "mobile-menu-text";

                    if ( !empty ( $hide_mobile_menu_labels ) ) {
                        $labelclass = "screen-reader-text";
                    }
                    ?>
                    <div class="mobile-menu-anchor">
                        <button id="menu-toggle-footer" class="genericon genericon-menu">
                            <span class="<?php echo esc_attr( $labelclass ); ?>"><?php echo esc_html( $label ); ?></span>
                        </button>
                    </div><!-- .mobile-menu-anchor -->
                </div><!-- #mobile-footer-menu -->
                <?php
            }
            ?>

            <div id="site-footer-menu" class="site-menu">
                <nav id="access-footer-menu" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'catch-evolution' ); ?>">
                    <h3 class="screen-reader-text"><?php _e( 'Footer menu', 'catch-evolution' ); ?></h3>
                    <?php wp_nav_menu( array( 'theme_location'  => 'footer', 'container_class' => 'menu-footer-container wrapper', 'depth' => 1 ) );  ?>
                </nav><!-- #access-footer -->
            </div><!-- .site-footer-menu -->

        </div><!-- #access-footer -->
        <?php
    }
}
endif; //catchevolution_footer_menu
add_action( 'catchevolution_after_footer_sidebar', 'catchevolution_footer_menu', 10 );
