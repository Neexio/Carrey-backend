<?php
namespace WCFAddonsPro\Widgets\Skin;

use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Portfolio_Six extends Skin_Portfolio_Base {

	protected $content_slides = [];

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
		return 'skin-portfolio-six';
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
		return __( 'Portfolio Six', 'wcf-addons-pro' );
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

		add_action( 'elementor/element/wcf--a-portfolio/section_title_style/before_section_end', [ $this, 'inject_controls' ] );
	}

	public function inject_controls() {
		$this->parent->start_injection( [
			'at' => 'after',
			'of' => 'title_color',
		] );

		$this->add_control(
			'active_slide_heading',
			[
				'label' => esc_html__( 'Active Slide Title', 'wcf-addons-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'active_slide_title_typography',
				'selector' => '{{WRAPPER}} .swiper-slide-active .title',
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

		// Slider Controls
		$default = [
			'slides_to_show'       => '6',
			'navigation'           => '',
			'pagination'           => '',
		];
		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => __( 'Slider', 'wcf-addons-pro' ),
			]
		);
		$this->register_slider_controls( $default );
		$this->end_controls_section();

		// Layout Style Controls
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'wcf-addons-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slider_max_height',
			[
				'label'      => esc_html__( 'Height', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .skin-portfolio-six' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Slider Navigation Style
		$this->start_controls_section(
			'section_navigation_style',
			[
				'label' => esc_html__( 'Slider Navigation', 'wcf-addons-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'navigation' ) => 'yes'
				]
			]
		);
		$this->register_slider_navigation_style_controls();
		$this->end_controls_section();

		// Slider Pagination Style
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label'     => esc_html__( 'Slider Pagination', 'wcf-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'pagination' ) => 'yes'
				]
			]
		);
		$this->register_slider_pagination_style_controls();
		$this->add_responsive_control(
			'pagination_gap',
			[
				'label' => esc_html__( 'Spacing', 'wcf-addons-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => -300,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => -20,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Content
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'wcf-addons-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Alignment', 'wcf-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'right',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .wcf__thumb_slider .swiper-slide' => 'text-align: {{VALUE}}; justify-content: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Date
		$this->register_date_controls();
	}

	/**
	 * get thumb slider settings.
	 *
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_thumb_slider_attributes( ) {

		//slider settings
		$slider_settings = [
			'loop'           => 'true' === $this->get_instance_value('loop'),
			'speed'          => $this->get_instance_value('speed'),
			'parallax'            => true,
			'allowTouchMove' => 'true' === $this->get_instance_value('allow_touch_move'),
			'grabCursor'          => true,
			'effect'              => "fade",
			'watchSlidesProgress' => true,
			'direction'           => 'vertical',
			'loopAdditionalSlides'=> $this->get_instance_value('slides_to_show'),
		];

		if ( ! empty( $this->get_instance_value('mousewheel') ) ) {
			$slider_settings['mousewheel'] = [
				'releaseOnEdges' => true,
			];
		}

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$this->parent->add_render_attribute(
			'thumb-carousel-wrapper',
			[
				'class'        => 'wcf__thumb_slider ' . $swiper_class,
				'thumbsSlider' => " ",
				'style'        => 'position: static',
			]
		);

		return $slider_settings;

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

		$slider_settings = $this->get_slider_attributes();
		$slider_settings['direction'] = 'vertical';
		$slider_settings['centeredSlides'] = true;

		$thumb_slider_settings = $this->get_thumb_slider_attributes();

		$this->parent->add_render_attribute(
			'wrapper',
			[
				'class'         => [ 'wcf__slider-wrapper wcf--advance-portfolio ' . $this->get_id() ],
				'data-settings' => json_encode( $thumb_slider_settings ), //phpcs:ignore
			]
		);

		$this->parent->add_render_attribute(
			'thumb-wrapper',
			[
				'class'         => [ 'wcf__thumb-slider-wrapper ' ],
				'data-settings' => json_encode( $slider_settings ), //phpcs:ignore
			]
		);

		?>
        <div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="main-slider">
            <div <?php $this->parent->print_render_attribute_string( 'carousel-wrapper' ) ?>>
                <div class="swiper-wrapper">
					<?php $this->render_posts(); ?>
                </div>
            </div>

            <!--navigation -->
			<?php $this->render_slider_navigation(); ?>

            <!--pagination -->
			<?php $this->render_slider_pagination(); ?>
            </div>
            <!--thumb slider-->
            <div <?php $this->parent->print_render_attribute_string( 'thumb-wrapper' ); ?>>
                <div <?php $this->parent->print_render_attribute_string( 'thumb-carousel-wrapper' ) ?>>
                    <div class="swiper-wrapper">
	                    <?php echo implode( '', $this->content_slides ); //phpcs:ignore ?>
                    </div>
                </div>
            </div>
        </div>

		<?php
	}

	public function render_post() {
		$this->slider_content();
		?>
        <div class="swiper-slide">
            <div class="thumb"><?php $this->render_thumb(); ?></div>
        </div>
		<?php
	}

	public function slider_content() {

		$title = $this->get_post_title();
		$date = $this->get_post_date();

		$slide_html = '<div  class="swiper-slide"><div class="content">' . $title . $date. '</div></div>';

		$this->content_slides[] = $slide_html;

		return $this->content_slides;
	}

	protected function get_post_title() {
		$title_tag  = $this->parent->get_settings( 'title_tag' );

		$title_html = '<' . $title_tag . ' class="title">';
		$title_html .= '<a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title();
		$title_html .= '</a></' . $title_tag . '>';

		return $title_html;
	}

	protected function get_post_date() {
		return '<div class="date">' . esc_html( get_the_date() ) . '</div>';
	}
}
