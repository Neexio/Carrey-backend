<?php

use Elementor\Modules\Usage\Module as Usage_Module;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Image_Generator_Post_Handler {

	public static function init() {

		add_action( 'wp_footer', [ __CLASS__, 'render_image_generator_popup' ] );

		add_action( 'wp_ajax_sassly_get_render_images', [ __CLASS__, 'post_prepare_ajax' ] );
		add_action( 'wp_ajax_nopriv_sassly_get_render_images', [ __CLASS__, 'post_prepare_ajax' ] );
	}

	/**
	 * Post Tabs Ajax call
	 */
	public static function post_prepare_ajax() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'sassly--addons-frontend' ) ) {
			exit( 'No naughty business please' );
		}

		parse_str( isset( $_POST['data_terms'] ) ? $_POST['data_terms'] : '', $form_data );

		$query = [
			'posts_per_page' => - 1,
			's'              => esc_attr( $form_data['s'] ),
			'post_type'      => isset( $_POST['post_type'] ) ? explode( ' ', $_POST['post_type'] ) : 'post'
		];

		$query = new WP_Query( $query );

		ob_start();

		self::display_post_template( $query );

		$html = ob_get_contents();  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		ob_end_clean();

		wp_send_json(
			array(
				'html' => $html,
			)
		);

		die();
	}

	public static function render_image_generator_popup() {
		?>
        <div class="wcf-image-generator-popup">
            <div class="wcf-image-generator-popup-wrapper">
                <div class="image-generator-post-wrapper">

                </div>
            </div>
        </div>
		<?php
	}

	public static function create_meta_for_post_type( $options, $prefix = '' ) {

		// Control core classes for avoid errors

		if ( ! class_exists( 'CSF' ) ) {
			return;
		}

		if ( empty( $options ) || empty( $options['meta_box'] ) ) {
			return;
		}

		// Set a unique slug-like ID
		if ( empty( $prefix ) ) {
			$prefix = 'wcf_post_options';
		}

		// Create a metabox
		CSF::createMetabox( $prefix, $options['meta_box'] );


		// Create a section
		if ( ! empty( $options['sections'] ) ) {

			foreach ( $options['sections'] as $section ) {
				CSF::createSection( $prefix, $section );
			}
		}

	}

	/**
	 * Render the music post widget output.
	 *
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public static function display_post_template( $query = null ) {

		if ( is_null( $query ) ) {
			return;
		}

		if ( ! $query->found_posts ) {
			echo '<p class="no-post-found">' . esc_html__( 'No Post found' ) . '</p>';
		}

		while ( $query->have_posts() ) {
			$query->the_post();
			self::render_post();
		}
	}

	protected static function render_post() {
		if ( ! has_post_thumbnail( get_the_ID() ) ) {
			return;
		}
		?>
        <a href="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>"
           class="image-generator-post">
			<?php self::render_thumbnail(); ?>
        </a>
		<?php
	}

	protected static function render_thumbnail() {
		echo get_the_post_thumbnail( get_the_ID(), 'medium' );
	}

	protected static function render_title() {
		?>
        <h3 class="title">
            <a href="<?php echo esc_url( get_the_permalink() ); ?>">
				<?php the_title(); ?>
            </a>
        </h3>
		<?php
	}
}

Image_Generator_Post_Handler::init();
