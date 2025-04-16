<?php

namespace WCFAddonsPro\Settings\Tabs;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Group_Control_Background;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Preloader extends Tab_Base {

	public function get_id() {
		return 'settings-wcf-preloader';
	}

	public function get_title() {
		return esc_html__( 'WCF Preloader', 'wcf-addons-pro' );
	}

	public function get_group() {
		return 'theme-style';
	}

	public function get_icon() {
		return 'wcf eicon-loading';
	}

	protected function register_tab_controls() {
		$this->start_controls_section(
			'section_' . $this->get_id(),
			[
				'label' => $this->get_title(),
				'tab'   => $this->get_id(),
			]
		);

		$this->add_control(
			'wcf_enable_preloader',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Preloader', 'wcf-addons-pro' ),
				'default'            => '',
				'label_on'           => esc_html__( 'Show', 'wcf-addons-pro' ),
				'label_off'          => esc_html__( 'Hide', 'wcf-addons-pro' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'wcf_preloader_layout',
			[
				'label'       => esc_html__( 'Layout', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'whirlpool',
				'label_block' => false,
				'options'     => [
					'whirlpool'          => esc_html__( 'Whirlpool', 'wcf-addons-pro' ),
					'floating-circles'   => esc_html__( 'Floating Circle', 'wcf-addons-pro' ),
					'eight-spinning'     => esc_html__( 'Eight Spinning', 'wcf-addons-pro' ),
					'double-torus'       => esc_html__( 'Double Torus', 'wcf-addons-pro' ),
					'tube-tunnel'        => esc_html__( 'Tube Tunnel', 'wcf-addons-pro' ),
					'speeding-wheel'     => esc_html__( 'Speeding Wheel', 'wcf-addons-pro' ),
					'loading'            => esc_html__( 'Loading', 'wcf-addons-pro' ),
					'dot-loading'        => esc_html__( 'Dot Loading', 'wcf-addons-pro' ),
					'fountainTextG'      => esc_html__( 'FountainTextG', 'wcf-addons-pro' ),
					'circle-loading'     => esc_html__( 'Circle Loading', 'wcf-addons-pro' ),
					'dot-circle-rotator' => esc_html__( 'Dot Circle Rotator', 'wcf-addons-pro' ),
					'bubblingG'          => esc_html__( 'BubblingG', 'wcf-addons-pro' ),
					'coffee'          => esc_html__( 'Coffee', 'wcf-addons-pro' ),
					'orbit-loading'          => esc_html__( 'Orbit Loading', 'wcf-addons-pro' ),
					'battery'          => esc_html__( 'Battery', 'wcf-addons-pro' ),
					'equalizer'          => esc_html__( 'Equalizer', 'wcf-addons-pro' ),
					'square-swapping'          => esc_html__( 'Square Swapping', 'wcf-addons-pro' ),
					'jackhammer'          => esc_html__( 'Jackhammer', 'wcf-addons-pro' ),

				],
				'separator'   => 'before',
				'condition'   => [
					'wcf_enable_preloader!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_preloader_background',
			[
				'label' => esc_html__( 'Background Color', 'wcf-addons-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'wcf_preloader_color',
			[
				'label' => esc_html__( 'Primary Color', 'wcf-addons-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'wcf_preloader_color2',
			[
				'label' => esc_html__( 'Secondary Color', 'wcf-addons-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->end_controls_section();
	}
}
