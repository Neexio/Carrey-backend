<?php

namespace Wcf\TemplateLibrary\base;

use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\User;

/**
 *  Template Library.
 *
 * @since 1.0
 */
class Templates_Lib {

	public static $source = null;
	/**
	 * WCF library option key.
	 */
	const LIBRARY_OPTION_KEY = 'wcf_templates_library';

	/**
	 * API templates URL.
	 *
	 * Holds the URL of the templates API.
	 *
	 * @access public
	 * @static
	 *
	 * @var string API URL.
	 */
	//public static $api_url = AROLAX_ESSENTIAL_DEMO_BASE_PATH . 'list';
	public static $api_single_url = SASSLY_ESSENTIAL_DEMO_BASE_PATH . 'list';
	public static $api_url = SASSLY_ESSENTIAL_DEMO_BASE_PATH . 'theme-tpl-list?theme='.SASSLY_TPL_SLUG;

	/**
	 * Init.
	 *
	 * Initializes the hooks.
	 *
	 * @return void
	 * @since 1.0
	 * @access public
	 *
	 */
	public function register() {

		add_action( 'elementor/init', [ __CLASS__, 'register_source' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'enqueue_editor_scripts' ] );
		add_action( 'elementor/ajax/register_actions', [ __CLASS__, 'register_ajax_actions' ] );
		add_action( 'elementor/editor/footer', [ __CLASS__, 'render_template' ] );
		add_action( 'wp_ajax_eready_get_library_data', [ __CLASS__, 'custom_eready_get_library_data' ] );
		add_action( 'wp_ajax_eready_get_library_data_single', [ __CLASS__, 'eready_get_library_data_single' ] );	
		
	}

	/**
	 * Register source.
	 *
	 * Registers the library source.
	 *
	 * @return void
	 * @since 1.0
	 * @access public
	 *
	 */
	public static function register_source() {
		Plugin::$instance->templates_manager->register_source( __NAMESPACE__ . '\FE_Source' );
	}

	/**
	 * Enqueue Editor Scripts.
	 *
	 * Enqueues required scripts in Elementor edit mode.
	 *
	 * @return void
	 * @since 1.0
	 * @access public
	 *
	 */
	public static function enqueue_editor_scripts() {

		wp_enqueue_style( 'wcf-ready-templates-lib', esc_url( WCF_TEMPLATE_MODULE_URL . 'assets/css/editor.min.css' ), time(), true );
		wp_enqueue_script(
			'wcf-ready-templates-lib',
			WCF_TEMPLATE_MODULE_URL . 'assets/js/templates-lib.min.js',
			[
				'jquery',
				'backbone-marionette',
				'backbone-radio',
				'elementor-common-modules',
				'elementor-dialog',
				'masonry',
				'elementor-editor'
			],
			time(),
			true
		);
		
		$data = self::direct_eready_get_library_data();
         
		$_templates = $data['templates'];
		usort( $_templates, function ( $a, $b ) {
			$t1 = strtotime( $a['date'] );
			$t2 = strtotime( $b['date'] );

			return $t1 - $t2;
		} );
        $theme_data = wp_get_theme();
		wp_localize_script( 'wcf-ready-templates-lib', 'fe_templates_lib', array(
			'logoUrl'      => plugin_dir_url( __DIR__ ) . 'assets/logo-icon.svg',
			'ajax_nonce'   => wp_create_nonce( 'elementor_reset_library' ),
			'_templates'   => $_templates,			
			'theme_author' => $theme_data->get( 'Author' ),
			'categories'   => isset( $data['subtypes'] ) ? $data['subtypes'] : [],
			'theme_types'  => isset( $data['theme_types'] ) ? $data['theme_types'] : []
		) );
	}

	/**
	 * Init ajax calls.
	 *
	 * Initialize template library ajax calls for allowed ajax requests.
	 *
	 * @param Ajax $ajax Elementor's Ajax object.
	 *
	 * @return void
	 * @since 1.0
	 * @access public
	 *
	 */
	public static function register_ajax_actions( Ajax $ajax ) {

		$library_ajax_requests = [
			'eready_get_library_data',
		];

		foreach ( $library_ajax_requests as $ajax_request ) {
			$ajax->register_ajax_action( $ajax_request, function ( $data ) use ( $ajax_request ) {
				return self::handle_ajax_request( $ajax_request, $data );
			} );
		}
	}

	/**
	 * Handle ajax request.
	 *
	 * Fire authenticated ajax actions for any given ajax request.
	 *
	 * @param string $ajax_request Ajax request.
	 * @param array $data Elementor data.
	 *
	 * @return mixed
	 * @throws \Exception Throws error message.
	 * @since 1.0
	 * @access private
	 *
	 */
	private static function handle_ajax_request( $ajax_request, array $data ) {

		if ( ! User::is_current_user_can_edit_post_type( Source_Local::CPT ) ) {
			throw new \Exception( 'Access Denied' );
		}

		if ( ! empty( $data['editor_post_id'] ) ) {
			$editor_post_id = absint( $data['editor_post_id'] );
			if ( ! get_post( $editor_post_id ) ) {
				throw new \Exception( esc_html__( 'Post not found.', 'arolax-essential' ) );
			}
			Plugin::$instance->db->switch_to_post( $editor_post_id );
		}

		$result = call_user_func( [ __CLASS__, $ajax_request ], $data );

		if ( is_wp_error( $result ) ) {
			throw new \Exception( $result->get_error_message() );
		}

		return $result;
	}

	public static function get_source() {

		if ( is_null( self::$source ) ) {
			self::$source = new FE_Source();
		}

		return self::$source;
	}

	/**
	 * Get library data.
	 *
	 * Get data for template library.
	 *
	 * @param array $args Arguments.
	 *
	 * @return array Collection of templates data.
	 * @since 1.0
	 * @access public
	 *
	 */
	public static function eready_get_library_data( array $args ) {

		$library_data = self::get_library_data( ! empty( $args['sync'] ) );
		// Ensure all document are registered.
		Plugin::$instance->documents->get_document_types();

		return [
			'templates' => self::get_templates(),
			'subtypes'    => $library_data['subtypes'],
			'config'    => $library_data['subtypes'],
			'theme_types' => isset( $library_data['theme_types'] ) ? $library_data['theme_types'] : [],
		];
	}

	public static function custom_eready_get_library_data() {

		$library_data = self::get_library_data( ! empty( sanitize_text_field( $_GET['sync'] ) ) );
		// Ensure all document are registered.
		Plugin::$instance->documents->get_document_types();

		wp_send_json( [
			'templates' => self::get_templates(),
			'config'    => $library_data['types_data'],
			'subtypes'    => $library_data['subtypes'],
			'theme_types' => isset( $library_data['theme_types'] ) ? $library_data['theme_types'] : [],
		] );

	}

	public static function direct_eready_get_library_data() {

		$library_data = self::get_library_data();
	
		// Ensure all document are registered.
		Plugin::$instance->documents->get_document_types();
           
		return [
			'templates'   => self::get_templates(),
			'config'      => $library_data['subtypes'],
			'subtypes'      => $library_data['subtypes'],
			'theme_types' => isset( $library_data['theme_types'] ) ? $library_data['theme_types'] : [],
		];
	}

	/**
	 * Get templates.
	 *
	 * Retrieve all the templates from all the registered sources.
	 *
	 * @return array Templates array.
	 * @since 1.16.0
	 * @access public
	 *
	 */
	public static function get_templates() {
		$source = Plugin::$instance->templates_manager->get_source( 'wcf-info' );
		return $source->get_items();
	}

	/**
	 * Ajax reset API data.
	 *
	 * Reset Elementor library API data using an ajax call.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static function ajax_reset_api_data() {

		check_ajax_referer( 'elementor_reset_library', '_nonce' );
		self::get_templates_data( true );
		wp_send_json_success();
	}

	/**
	 * Get templates data.
	 *
	 * This function the templates data.
	 *
	 * @param bool $force_update Optional. Whether to force the data retrieval or * not. Default is false.
	 *
	 * @return array|false Templates data, or false.
	 * @since 1.0
	 * @access private
	 * @static
	 */
	private static function get_templates_data( $force_update = false ) {
       
		$cache_key      = 'fe_templates_data_' . 3.0;
		$templates_data = get_transient( $cache_key );
		if ( $force_update || false === $templates_data ) {
			$timeout = ( $force_update ) ? 90 : 80;

			$response = wp_remote_get( esc_url_raw( self::$api_url ), [
				'timeout' => $timeout,
				'sslverify' => false,
				'body'    => [
					// Which API version is used.
					'api_version' => 1.2,
					// Which language to return.
					'site_lang'   => get_bloginfo( 'language' ),
				],
			] );
      
			if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
				set_transient( $cache_key, [], 1 * HOUR_IN_SECONDS );
				return false;
			}

			$templates_data = json_decode( wp_remote_retrieve_body( $response ), true );		

			if ( empty( $templates_data ) || ! is_array( $templates_data ) ) {
				set_transient( $cache_key, [], 1 * HOUR_IN_SECONDS );

				return false;
			}

			if ( isset( $templates_data['templates'] ) ) {
				update_option( self::LIBRARY_OPTION_KEY, $templates_data, 'no' );		
				set_transient( $cache_key, $templates_data, 12 * HOUR_IN_SECONDS );
			}
			
		}
    
		return $templates_data;
	}

	/**
	 * Get templates data.
	 *
	 * Retrieve the templates data from a remote server.
	 *
	 * @param bool $force_update Optional. Whether to force the data update or
	 *                                     not. Default is false.
	 *
	 * @return array The templates data.
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_library_data( $force_update = false ) {
		//self::get_templates_data( $force_update );
		self::get_templates_data( $force_update );

		$library_data = get_option( self::LIBRARY_OPTION_KEY );

		if ( empty( $library_data ) ) {
			return [];
		}
	
		return $library_data;
	}

	/**
	 * Get template content.
	 *
	 * Retrieve the templates content received from a remote server.
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return object|\WP_Error The template content.
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_template_content( $template_id ) {

		$url = self::$api_single_url . '/' . $template_id;

		$_key = apply_filters( 'wcf_theme_product', '*' );

		if ( empty( $_key ) ) {
			return new \WP_Error( 'no_license', esc_html__( 'Product is not active.', 'arolax-essential' ) );
		}

		$body = self::_request( $url );

		if ( false === $body ) {
			return new \WP_Error( 422, esc_html__( 'Wrong Server Response', 'arolax-essential' ) );
		}

		return $body;
	}

	public static function eready_get_library_data_single() {

		$source = self::get_source();
		$data   = self::get_template_content( sanitize_text_field( $_REQUEST['tpl_id'] ) );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$data = (array) $data;

		wp_send_json_success( [
			'cus' => $source->get_custom_data( $data, sanitize_text_field( $_REQUEST['editor_post_id'] ) )
		] );

	}

	public static function _request( $url ) {

		$response = wp_remote_get( $url, [
			'timeout' => 600,
			'body'    => [
				// Which API version is used.
				'api_version' => 1.1,
				'is_pro'      => false,
				// Which language to return.
				'site_lang'   => get_bloginfo( 'language' ),
			],
		] );

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Render template.
	 *
	 * Library modal template.
	 *
	 * @return void
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 */
	public static function render_template() {

		?>
        <style>
            .wcf-template-lib-modal-overlay {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                height: 100%;
                width: 100%;
                overflow: auto;
                background-color: #fff;
            }

            .er-template-lib-modal-content {
                background-color: #fff;
                width: 100%;
                height: 100%;
            }

            .er-template-lib-modal-header {
                background: #ffffffd9;
                padding: 9px 50px;
                color: #191111;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                height: 80px;
                border-bottom: 1px solid rgba(28, 29, 32, 0.06);
            }

            .wcf-templates-list-renderer {
                background: #F8F8FA;
                padding: 20px;
                border-radius: 8px;
            }

            #wcf-ready-template-close-icon {
                cursor: pointer;
                font-size: 20px;
            }

            .wcf-template-lib-modal-body {
                padding: 20px 50px;
                position: relative;
                width: 100%;
            }

            .wcf-info-ready-template-block-category-left {
                width: 20%;
                max-width: 120px;
            }

            .wcf-template-category-section .wcf-templates-category {
                width: 270px;
                background: #F8F8FA;
            }

            .wcf-template-category-section {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 20px;
                margin-bottom: 20px;
            }

            .wcf-category-wrapper {
                display: flex;
                align-items: center;
                gap: 11px;
                width: 250px;
            }

            .wcf-tpl-category-label {
                font-weight: 500;
                font-size: 18px;
            }

            .wcf-templates-category option {
                padding: 5px;
            }

            .wcf-template-category-section select {
                appearance      : none;
                outline         : 0;
                height          : 100%;
                box-shadow      : none;
                border          : 0 !important;
                background-image: none;
                flex            : 1;
                padding         : 15px 20px;
                color           : #000;
                cursor          : pointer;
                font-size       : 16px;
                background      : #F8F8FA !important;
            }

            .wcf-template-category-section select::-ms-expand {
                display: none;
            }

            .wcf-template-category-section .wcf-category-wrapper {
                position     : relative;
                display      : flex;
                width        : 270px;
                height       : 55px;
                line-height  : 3;
                overflow     : hidden;
                border-radius: 10px;
                gap          : 15px;
                color        : #fff;
            }

            .wcf-category-wrapper::after {
                content: '\25BC';
                position: absolute;
                top: 10px;
                right: 0;
                padding: 0 1em;
                cursor: pointer;
                pointer-events: none;
                transition: .25s all ease;
                color: #B4B4B6;
            }

            .wcf-info-ready-temlpates-sorts-button-group button {
                padding: 10px;
                background: #FFFFFF;
                border-radius: 5px;
                border: 0;
                cursor: pointer;
            }

            .wcf-info-ready-temlpates-sorts-button-group {
                display: flex;
                gap: 10px;
            }
            
            .wcf--left-filter-wrapper{
                display:flex;
                gap: 25px;
            }

            .wcf-ready-tpl-sort-filter-wrapper {
                display: flex;
                gap: 20px;
                align-items: center;
            }

            .wcf-ready--tpl-search {
                position: relative;
                color: #aaa;
                font-size: 16px;
            }

            .wcf-ready--tpl-search {
                display: inline-block;
            }

            .wcf-ready--tpl-search input {
                width: 550px;
                background: #F8F8FA;
                border-radius: 10px;
                padding: 19px 32px 18px;
                font-size: 16px;
                border: none;
            }

            .wcf-ready--tpl-search input::placeholder {
                background: #F8F8FA;
                opacity: 0.5;
            }

            .wcf-info-ready--tpl-tag-filter {
                display: flex;
                gap: 30px;
                align-items: center;
                cursor: pointer;
            }

            .wcf-info-ready--tpl-tag-filter > div {
                padding: 10px;
                color: #1C1D20;
                font-weight: 500;
                text-transform: uppercase;
                font-size: 14px;
            }

            .er-filter-wrapper .wcf-info-ready-active-tags {
                background: #8b5bff;
            }

            .wcf-ready--tpl-search input {
                text-indent: 10px;
            }

            .wcf-ready--tpl-search .eicon-search {
                position: absolute;
                top: 19px;
                left: 15px;
                font-size: 18px;
            }

            .wcfready-header-left {
                display: flex;
                align-items: center;
                gap: 8px;

            }

            .wcfready-header-left img {
                max-height: 45px;
            }

            .eready-header-right {
                display: flex;
                align-items: center;
            }

            /* Single Item */

            .wcf-row {
                display: flex;
                width: 100%;
                gap: 20px;
            }

            .wcf-template-list-column,
            .wcf-column {
                gap: 20px;
                display: flex;
                flex-direction: column;
                flex-basis: 25%;
            }

            .wcf-item-templates-l,
            .wcf-item {
                outline: none;
                position: relative;
                min-height: 130px;
                display: flex;
                align-items: center;
                background: #ffffff;
                padding: 10px;
            }

            .wcf-item-templates-l img,
            .wcf-item img {
                width: 100%;
            }

            .wcf-template-inner-section .action-wrapper {
                position: absolute;
                top: 50%;
                left: 50%;
                display: flex;
                gap: 10px;
                opacity: 0;
                visibility: hidden;
                transition: 0.5s;
                z-index: 3;
                transform: translate(-50%, -50%);
            }

            .wcf-item:hover .action-wrapper {
                opacity: 1;
                visibility: visible;
            }

            .wcf-item-templates-l:hover .wcf-template-inner-section .action-wrapper,
            .wcf-item:hover .wcf-template-inner-section .action-wrapper {
                opacity: 1;
                visibility: visible;
            }

            .wcf-template-inner-section .action-wrapper a {
                background: #FFF;
                padding: 10px 12px;
                color: #000;
                border: 1px solid #e1e1e1;
            }

            .wcf-template-inner-section .action-wrapper .er-template-go-details {
                background: #fff;
                color: #1C1D20;
                font-size: 15px;
                transition: 0.3s;
                border-radius: 30px;
                padding: 13px 20px 10px;
                border: none;
                font-weight: 400;
            }

            .wcf-template-inner-section .action-wrapper .er-template-go-details:hover {
                color: #fff;
                background: #1C1D20;
            }

            .wcf-info-ready-tpl-title {
                position: absolute;
                top: 20px;
                left: 22px;
                background: ghostwhite;
                padding: 9px;
                opacity: 0;
                visibility: hidden;
                transition: 0.8s;
            }

            .wcf-item-templates-l:hover .wcf-info-ready-tpl-title {
                opacity: 1;
                visibility: visible;
            }

            .wcf--general-tpls-button {
                background: #40CF79;
                color: #fff;
                padding: 10px 20px 11px;
                border-radius: 30px;
                cursor: pointer;
                transition: 0.3s;
                font-size: 15px;
                border: none;
            }

            .wcf--general-tpls-button:hover {
                background: #1C1D20;
            }

            .er-template-import {
                background: #40CF79;
            }

            .wcf-templates-list-renderer iframe {
                width: 100%;
                height: 100vh;
            }

            .body-import-active-overlay {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }

            .wcf-templates-list-renderer.importing iframe {
                display: none;
            }

            .wcf-templates-pagination {
                padding-bottom: 30px;
                padding-top: 30px;
            }

            .wcf-templates-pagination ul {
                display: flex;
                gap: 80px;
                justify-content: center;
            }

            .wcf-info-ready--tpl-tag-filter .active {
                color: #40CF79;
            }

            .wcf-template-list-column .wcf-template-type .img-wrapper {
                width: 100%;
            }

            .wcf-template-list-column .wcf-template-type.page .img-wrapper img {
                width: 100%;
                object-position: top;
                object-fit: cover;
                height: 410px;
                transition: all 1s;
            }
            .wcf-template-list-column .wcf-template-type.page:hover .img-wrapper img {
                object-position: bottom;
            }

            .wcf-template-list-column .wcf-template-type {
                position: relative;
                min-height: 150px;
                width: 100%;
                display: flex;
                align-items: center;
            }

            .wcf-template-list-column .wcf-template-type::after {
                position: absolute;
                width: 100%;
                height: 100%;
                content: "";
                background: rgba(22, 33, 50, 0.4);
                left: 0;
                top: 0;
                opacity: 0;
                z-index: 0;
                visibility: hidden;
                transition: all 0.5s;
            }

            .wcf-template-list-column .wcf-item:hover .wcf-template-type::after {
                opacity: 1;
                visibility: visible;
            }

            .wcf-tpl-not-found {
                font-size: 80px;
                text-align: center;
                padding-top: 50px;
                padding-bottom: 50px;
                height: 40vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .wcf-tpl-not-found + .wcf-templates-pagination {
                display:none;
            }


            @media (max-width: 1400px) {
                .wcf-template-list-column,
                .wcf-column,
                .wcf-row {
                    gap: 15px;
                }

                .wcf-template-lib-modal-body,
                .er-template-lib-modal-header {
                    padding: 9px 30px;
                }

                .wcf-template-list-column .wcf-template-type.page .img-wrapper img {
                    height: 360px;
                }

            }

            /* Large Tablet */
            @media (max-width: 1365px) {
                .wcf-ready--tpl-search input {
                    width: 400px;
                }

                .wcf-template-inner-section .action-wrapper,
                .wcf-template-list-column .wcf-template-type::after {
                    opacity: 1;
                    visibility: visible;
                }
                .wcf-item-templates-l, .wcf-item {
                    padding: 5px;
                }

                .wcf-template-list-column, .wcf-column, .wcf-row {
                    gap: 10px;
                }
                .wcf-tpl-not-found {
                    font-size: 50px;
                }
            }


        /*  Mobile  */
            @media (max-width: 767px) {
                .wcf-template-category-section {
                    flex-wrap: wrap;
                }
                .wcf-ready--tpl-search input {
                    width: 300px;
                }
                .wcf-info-ready--tpl-tag-filter {
                    gap: 0;
                }
                .wcfready-header-left {
                    gap: 0;
                }
                .wcf-template-lib-modal-body, .er-template-lib-modal-header {
                    padding: 10px 0px;
                }
                .wcf-templates-list-renderer {
                    padding: 20px 5px;
                }
                .wcf-tpl-not-found {
                    font-size: 40px;
                }
            }

        </style>

		<?php
		include_once( 'view.php' );
	}
}




