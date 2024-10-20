<?php
/**
 * Catch Evolution Pro Custom meta box
 *
 * @package Catch Evolution Pro
 */

 // Add the Meta Box
function catchevolution_add_custom_box() {
	add_meta_box(
		'catch-evolution-option',                                   //Unique ID
		esc_html__( 'Catch Evolution Options', 'catch-evolution' ), //Title
		'catchevolution_sidebar_layout',                    //Callback function
		array( 'page', 'post' ),                                            //show metabox in page
		'side'                                          //show metabox in pages
	);
}
add_action( 'add_meta_boxes', 'catchevolution_add_custom_box' );

/**
 * @renders metabox to for sidebar layout
 */
function catchevolution_sidebar_layout() {
	global $post;

	$options = array(
		'catchevolution-sidebarlayout' => array(
			'label'   => esc_html__( 'Sidabar Layout', 'catch-evolution'),
			'options' => array(
				'default'       => esc_html__( 'Default Layout Set in Customize Options', 'catch-evolution' ),
				'right-sidebar' => esc_html__( 'Right sidebar', 'catch-evolution' ),
				'left-sidebar'  => esc_html__( 'Left sidebar', 'catch-evolution' ),
				'no-sidebar'    => esc_html__( 'No sidebar', 'catch-evolution' ),
				'three-columns' => esc_html__( 'Three Columns', 'catch-evolution' ),
				),
			),
	);

	// Use nonce for verification
	wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' );
	
	foreach ( $options as $key => $value ) : ?>
		<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="<?php echo esc_attr( $key ); ?>"><?php echo $value['label']; ?></label></p>
		<select class="widefat" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>">
			 <?php
				$meta_value = get_post_meta( $post->ID, $key, true );
				
				if ( empty( $meta_value ) ){
					$meta_value = 'default';
				}
				
				foreach ( $value['options'] as $field => $label ) {  
				?>
					<option value="<?php echo esc_attr( $field ); ?>" <?php selected( $meta_value, $field ); ?>><?php echo esc_html( $label ); ?></option>
				<?php
				} // end foreach
			?>
		</select>
	<?php
	endforeach;
}

/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function catchevolution_save_custom_meta( $post_id ) {
	global $post;

	// Verify the nonce before proceeding.
	if ( !isset( $_POST[ 'custom_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ 'custom_meta_box_nonce' ], basename( __FILE__ ) ) )
		return;

	// Stop WP from clearing custom fields on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
		return;

	if ('page' == $_POST['post_type']) {
		if (!current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} elseif (!current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
	}

	if ( ! update_post_meta ( $post_id, 'catchevolution-sidebarlayout', sanitize_key( $_POST['catchevolution-sidebarlayout'] ) ) ) {
		add_post_meta( $post_id, 'catchevolution-sidebarlayout', sanitize_key( $_POST['catchevolution-sidebarlayout'] ), true );
	}
}
add_action('save_post', 'catchevolution_save_custom_meta');
