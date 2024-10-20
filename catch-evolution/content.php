<?php
/**
 * The default template for displaying content
 *
 * @package Catch Evolution
 */
?>
<?php
    $options = catchevolution_get_options();
	$layout = $options['sidebar_layout'];
	if ( 'no-sidebar-full-width' == $layout ) {
		$imagesize = "featured-slider-larger";
	}
	else {
		$imagesize = "featured-slider";
	}
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'catch-evolution' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php if ( is_sticky() ) : ?>
				<h3 class="entry-format"><?php _e( 'Featured', 'catch-evolution' ); ?></h3>
			<?php endif; ?>

			<?php if ( 'post' == get_post_type() ) : ?>
                <div class="entry-meta">
                    <?php catchevolution_posted_on(); ?>
                    <?php if ( comments_open() && ! post_password_required() ) : ?>
                    	<span class="sep"> &mdash; </span>
                        <span class="comments-link">
                         	<?php comments_popup_link(__('Leave a reply', 'catch-evolution'), __('1 Comment &darr;', 'catch-evolution'), __('% Comments &darr;', 'catch-evolution')); ?>
                        </span>
                    <?php endif; ?>
                </div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		 <?php
    		$options = catchevolution_get_options();
			$current_content_layout = $options['content_layout'];
			$catchevolution_excerpt = get_the_excerpt();
		if ( is_search() ) : // Only display Excerpts for Search ?>
            <div class="entry-summary">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->
        <?php elseif ( post_password_required() ) : // Password Protected Post ?>
            <div class="entry-content">
                <?php the_content(); ?>
            </div><!-- .entry-summary -->
        <?php elseif ( $current_content_layout=='excerpt' && !empty( $catchevolution_excerpt ) ) : // Only display Featured Image and Excerpts if checked in Theme Option ?>
            <div class="entry-summary">
                <?php if ( has_post_thumbnail() ):?>
               		<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'catch-evolution' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
						<?php the_post_thumbnail( $imagesize ); ?>
                   	</a>
                <?php endif; ?>
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php
			$more_tag_text = $options['more_tag_text'];
			the_content( $more_tag_text ); ?>
			<?php wp_link_pages( array(
                'before'		=> '<div class="page-link"><span class="pages">' . __( 'Pages:', 'catch-evolution' ) . '</span>',
                'after'			=> '</div>',
                'link_before' 	=> '<span>',
                'link_after'   	=> '</span>',
            ) );
            ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-meta">
			<?php $show_sep = false; ?>
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'catch-evolution' ) );
				if ( $categories_list ):
			?>
			<span class="cat-links">
				<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'catch-evolution' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
				$show_sep = true; ?>
			</span>
			<?php endif; // End if categories ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'catch-evolution' ) );
				if ( $tags_list ):
				if ( $show_sep ) : ?>
			<span class="sep"> | </span>
				<?php endif; // End if $show_sep ?>
			<span class="tag-links">
				<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'catch-evolution' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
				$show_sep = true; ?>
			</span>
			<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == get_post_type() ?>

			<?php if ( comments_open() ) : ?>
			<?php if ( $show_sep ) : ?>
			<span class="sep"> | </span>
			<?php endif; // End if $show_sep ?>
			<span class="comments-link"><?php comments_popup_link(__('Leave a reply', 'catch-evolution'), __('1 Comment &darr;', 'catch-evolution'), __('% Comments &darr;', 'catch-evolution')); ?></span>
			<?php endif; // End if comments_open() ?>

			<?php edit_post_link( __( 'Edit', 'catch-evolution' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- #entry-meta -->
	</article><!-- #post-<?php the_ID(); ?> -->
