<?php
/**
 * Wrapper link extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class WCF_Wrapper_link {

	public static function init() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_horizontal_scroll_controls'
		] );
		add_action( 'elementor/frontend/before_render', [ __CLASS__, 'before_render' ], 1 );
	}

	public static function enqueue_scripts() {

	}

	public static function register_horizontal_scroll_controls( $element ) {

		$element->start_controls_section(
			'_section_wcf_wrapper_link_area',
			[
				'label' =>  sprintf('%s <i class="wcf-logo"></i>', __('Wrapper Link', 'wcf-addons-pro')),
				'tab' => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'wcf_enable_wrapper_link',
			[
				'label' => __( 'Enable', 'wcf-addons-pro' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

		$element->add_control(
			'wcf_wrapper_link',
			[
				'label'       => esc_html__( 'Link', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'condition' => [
					'wcf_enable_wrapper_link!' => ''
				]
			]
		);

		$element->end_controls_section();
	}

	public static function before_render( $element ) {
		if ( empty( $element->get_settings( 'wcf_enable_wrapper_link' ) ) ) {
			return;
		}

		$wrapper_link = $element->get_settings( 'wcf_wrapper_link' );
		$attributes = [];

		if ( ! empty( $wrapper_link['url'] ) ) {
			$allowed_protocols = array_merge( wp_allowed_protocols(), [ 'skype', 'viber' ] );

			$attributes['href'] = esc_url( $wrapper_link['url'], $allowed_protocols );
		}

		if ( ! empty( $wrapper_link['is_external'] ) ) {
			$attributes['target'] = '_blank';
		}

		if ( ! empty( $wrapper_link['nofollow'] ) ) {
			$attributes['rel'] = 'nofollow';
		}

		$element->add_render_attribute( '_wrapper',
			'data-wcf-wrapper-link',
			wp_json_encode( $attributes )
		);

	}
}

WCF_Wrapper_link::init();
