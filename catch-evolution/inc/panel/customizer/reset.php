<?php
/**
 * Reset Theme Options, Footer Options, Section Sorter Options, Font Family Options
 *
 * @package Vogue
 */

if ( ! class_exists( 'Catchevolution_Customizer_Reset' ) ) {
	/**
	 * Adds Reset button to customizer
	 */
	final class Catchevolution_Customizer_Reset {
		/**
		 * @var Catchevolution_Customizer_Reset
		 */
		private static $instance = null;

		/**
		 * @var WP_Customize_Manager
		 */
		private $wp_customize;

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		private function __construct() {
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_controls_print_scripts' ) );
			add_action( 'wp_ajax_customizer_reset', array( $this, 'ajax_customizer_reset' ) );
			add_action( 'customize_register', array( $this, 'customize_register' ) );
		}

		public function customize_controls_print_scripts() {

			wp_enqueue_script( 'catch-evolution-customizer-reset', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'js/customizer-reset.min.js', array( 'jquery' ), '20190207' );
			
			wp_localize_script( 'catch-evolution-customizer-reset', 'catchEvolutionCustomizerReset', array(
				'reset'          => esc_html__( 'Reset', 'catch-evolution' ),
				'confirm'        => esc_html__( "Caution: Reset all settings to default. Process is irreversible.", 'catch-evolution' ),
				'nonce'          => array(
					'reset' => wp_create_nonce( 'catch-evolution-customizer-reset' ),
				),
				'resetSection'   => esc_html__( 'Reset section', 'catch-evolution' ),
				'confirmSection' => esc_html__( "Caution: Reset section settings to default. Process is irreversible.", 'catch-evolution' ),
			) );
		}

		/**
		 * Store a reference to `WP_Customize_Manager` instance
		 *
		 * @param $wp_customize
		 */
		public function customize_register( $wp_customize ) {
			$this->wp_customize = $wp_customize;
		}

		public function ajax_customizer_reset() {
			if ( ! $this->wp_customize->is_preview() ) {
				wp_send_json_error( 'not_preview' );
			}

			if ( ! check_ajax_referer( 'catch-evolution-customizer-reset', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}

			if ( 'all' === $_POST['section'] ) {
				$this->reset_customizer();
			}

			if ( 'layout_options' === $_POST['section'] ) {
				remove_theme_mod( 'abletone_footer_content' );
			}

			$options = catchevolution_get_options();
			
			if ( 'layout_options' === $_POST['section'] ) {
				unset( $options['sidebar_layout'] );
				unset( $options['content_layout'] );
			}

			if ( 'excerpt_options' === $_POST['section'] ) {
				unset( $options['more_tag_text'] );
				unset( $options['excerpt_length'] );
			}

			update_option( 'catchevolution_options', $options );

			wp_send_json_success();
		}

		public function reset_customizer() {
			
			delete_option( 'catchevolution_options' );
			remove_theme_mods();
		}
	}
}

Catchevolution_Customizer_Reset::get_instance();
