<?php
namespace WCFAddonsPro\Widgets\Skin;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Portfolio_Three extends Skin_Portfolio_Base {

	/**
	 * Get skin ID.
	 *
	 * Retrieve the skin ID.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_id() {
		return 'skin-portfolio-three';
	}

	/**
	 * Get skin title.
	 *
	 * Retrieve the skin title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_title() {
		return __( 'Portfolio Three', 'wcf-addons-pro' );
	}

	/**
	 * Register skin controls actions.
	 *
	 * Run on init and used to register new skins to be injected to the widget.
	 * This method is used to register new actions that specify the location of
	 * the skin in the widget.
	 *
	 * Example usage:
	 * `add_action( 'elementor/element/{widget_id}/{section_id}/before_section_end', [ $this, 'register_controls' ] );`
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/wcf--a-portfolio/section_layout/before_section_end', [ $this, 'inject_controls' ] );
	}

	public function inject_controls() {
		$this->parent->start_injection( [
			'at' => 'after',
			'of' => 'title_tag',
		] );

		$this->add_control(
			'section_title',
			[
				'label'     => esc_html__( 'Section Title', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
				'default'   => esc_html__( 'WORK', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'section_title_tag',
			[
				'label' => esc_html__( 'Section Title Tag', 'wcf-addons-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => esc_html__( 'H1', 'wcf-addons-pro' ),
					'h2' => esc_html__( 'H2', 'wcf-addons-pro' ),
					'h3' => esc_html__( 'H3', 'wcf-addons-pro' ),
					'h4' => esc_html__( 'H4', 'wcf-addons-pro' ),
					'h5' => esc_html__( 'H5', 'wcf-addons-pro' ),
					'h6' => esc_html__( 'H6', 'wcf-addons-pro' ),
				],
			]
		);

		$this->parent->end_injection();
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_section_animation_controls();

		$this->register_layout_style_controls();

		$this->register_section_title_style_controls();

		$this->register_content_style_controls();

		// Date
		$this->register_date_controls();
	}

	// Register Layout Controls
	protected function register_layout_style_controls() {

		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'wcf-addons-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'layout_column_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .posts-list' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'layout_row_gap',
			[
				'label'      => esc_html__( 'Row Gap', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .posts-list' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Register content style Controls
	protected function register_content_style_controls() {

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'wcf-addons-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .content',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label'      => esc_html__( 'Width', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Register section title style Controls
	protected function register_section_title_style_controls() {

		$this->start_controls_section(
			'section_section_title_style',
			[
				'label' => esc_html__( 'Section Title', 'wcf-addons-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_title_typography',
				'selector' => '{{WRAPPER}} .section-title',
			]
		);

		$this->add_control(
			'section_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'section_title_margin',
			[
				'label'      => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_title_align',
			[
				'label' => esc_html__( 'Alignment', 'wcf-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .section-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Register section title style Controls
	protected function register_section_animation_controls() {

		$this->start_controls_section(
			'section_portfolio_animation',
			[
				'label' => esc_html__( 'Animations', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'enable_apf_animation',
			[
				'label'              => esc_html__( 'Enable', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'render_type'        => 'template',
				'return_value'       => 'yes',
			]
		);

		$this->add_control(
			'enable_editor_apf_animation',
			[
				'label'              => esc_html__( 'Enable Editor Mode', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'condition'          => [
					$this->get_control_id( 'enable_apf_animation!' ) => ''
				],
			]
		);

		$this->add_control(
			'wcf_apf_pin_area_start',
			[
				'label' => esc_html__( 'Title Pin Area Start', 'wcf-addons-pro' ),
				'description'        => esc_html__( 'First value is element position, Second value is display position', 'wcf-addons-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'top top', 'wcf-addons-pro' ),
				'placeholder' => esc_html__( 'top top', 'wcf-addons-pro' ),
				'condition'          => [
					$this->get_control_id( 'enable_apf_animation!' ) => ''
				],
			]
		);

		$this->add_control(
			'wcf_apf_pin_area_end',
			[
				'label' => esc_html__( 'Title Pin Area End', 'wcf-addons-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'bottom bottom', 'wcf-addons-pro' ),
				'placeholder' => esc_html__( 'bottom bottom', 'wcf-addons-pro' ),
				'condition'          => [
					$this->get_control_id( 'enable_apf_animation!' ) => ''
				],
			]
		);

		$dropdown_options = [
			'' => esc_html__( 'None', 'wcf-addons-pro' ),
		];

		$excluded_breakpoints = [
			'laptop',
			'tablet_extra',
			'widescreen',
		];

		foreach ( Plugin::$instance->breakpoints->get_active_breakpoints() as $breakpoint_key => $breakpoint_instance ) {
			// Exclude the larger breakpoints from the dropdown selector.
			if ( in_array( $breakpoint_key, $excluded_breakpoints, true ) ) {
				continue;
			}

			$dropdown_options[ $breakpoint_key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value. */
				esc_html__( '%1$s (%2$s %3$dpx)', 'wcf-addons-pro' ),
				$breakpoint_instance->get_label(),
				'>',
				$breakpoint_instance->get_value()
			);
		}

		$this->add_control(
			'wcf_apf_breakpoint',
			[
				'label'              => esc_html__( 'Breakpoint', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => esc_html__( 'Note: Choose at which breakpoint Pin element will work.', 'wcf-addons-pro' ),
				'options'            => $dropdown_options,
				'default'            => 'mobile',
				'condition'          => [
					$this->get_control_id( 'enable_apf_animation!' ) => ''
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_animation_settings() {
		return [
			'enable'         => $this->get_instance_value( 'enable_apf_animation' ),
			'enable_editor'  => $this->get_instance_value( 'enable_editor_apf_animation' ),
			'pin_area_start' => $this->get_instance_value( 'wcf_apf_pin_area_start' ),
			'pin_area_end'   => $this->get_instance_value( 'wcf_apf_pin_area_end' ),
			'breakpoint'     => $this->get_instance_value( 'wcf_apf_breakpoint' ),
			'skin'           => $this->get_id(),
		];
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render() {
		$this->parent->add_render_attribute(
			'wrapper',
			[
				'class' => [ 'wcf--advance-portfolio ' . $this->get_id() ],
				'data-animation-settings' => json_encode($this->get_animation_settings()),
			]
		);
		?>
        <div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
			<?php $this->render_section_title(); ?>
            <div class="posts-list">
				<?php $this->render_posts(); ?>
            </div>
        </div>
		<?php
	}

	public function render_post() {
		?>
        <article class="item">
            <div class="thumb">
				<?php $this->render_thumb(); ?>
            </div>
            <div class="content">
		        <?php
		        $this->render_title();
		        $this->render_date();
		        ?>
            </div>
        </article>
		<?php
	}

	protected function render_section_title() {
		if ( empty( $this->get_instance_value( 'section_title' ) ) ) {
			return;
		}

		$title_tag = $this->get_instance_value( 'section_title_tag' );
		?>
        <<?php Utils::print_validated_html_tag( $title_tag ); ?> class="section-title">
		<?php echo esc_html( $this->get_instance_value( 'section_title' ) ) ?>
        </<?php Utils::print_validated_html_tag( $title_tag ); ?>>
		<?php
	}
}
