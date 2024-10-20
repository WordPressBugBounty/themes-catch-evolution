<?php
/**
 * Template Name: Full Width Disable Sidebar Template
 * Description: A Page Template that disables a sidebar to pages
 *
 * @package Catch Evolution
 */

get_header(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>