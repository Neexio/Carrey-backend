<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class Blog_Post_Tags extends \Elementor\Widget_Base {

	public function get_name() {
		return 'wcf--blog--post--tags';
	}

	public function get_title() {
		return wcf_elementor_widget_concat_prefix( 'Post Tags' );
	}

	public function get_icon() {
		return 'wcf eicon-tags';
	}

	public function get_categories() {
		return [ 'wcf-blog-single' ];
	}

	public function get_keywords() {
		return [ 'tags', 'post tags' ];
	}

	public function get_style_depends() {
		wp_register_style( 'meta-info', SASSLY_ESSENTIAL_ASSETS_URL . 'css/meta-info.css' );

		return [ 'wcf--button', 'meta-info' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'prefix',
			[
				'label'   => esc_html__( 'Prefix', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'suffix',
			[
				'label'   => esc_html__( 'Suffix', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'tag_separator',
			[
				'label'       => esc_html__( 'Separator', 'sassly-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( ',', 'sassly-essential' ),
				'placeholder' => esc_html__( 'Enter separator', 'sassly-essential' ),
			]
		);

		$this->add_responsive_control(
			'tags_limit',
			[
				'label' => esc_html__( 'Limit', 'sassly-essential' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
				'max'   => 20,
				'step'  => 1,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'SASSLY_ESSENTIAL' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'   => [
						'title' => esc_html__( 'Left', 'SASSLY_ESSENTIAL' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'SASSLY_ESSENTIAL' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'  => [
						'title' => esc_html__( 'Right', 'SASSLY_ESSENTIAL' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wcf--post-tags' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();


		// Style Tab
		$this->start_controls_section(
			'section_styles',
			[
				'label' => esc_html__( 'Style', 'SASSLY_ESSENTIAL' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'col_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'SASSLY_ESSENTIAL' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--post-tags' => 'column-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wcf--post-tags li::after' => 'margin-inline-start: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'      => esc_html__( 'Row Gap', 'SASSLY_ESSENTIAL' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--post-tags' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'separator_heading',
			[
				'label' => esc_html__( 'Separator', 'SASSLY_ESSENTIAL' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Text Color', 'SASSLY_ESSENTIAL' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--post-tags li::after' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'separator_position',
			[
				'label'      => esc_html__( 'Position Horizontal', 'SASSLY_ESSENTIAL' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--post-tags li::after' => 'inset-inline-end: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'separator_position_2',
			[
				'label'      => esc_html__( 'Position Vertical', 'SASSLY_ESSENTIAL' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--post-tags li::after' => 'top: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'after',
			]
		);

		$this->add_control(
			'hover_list',
			[
				'label'   => esc_html__( 'Hover Style', 'SASSLY_ESSENTIAL' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hover-none',
				'options' => [
					'hover-none'      => esc_html__( 'None', 'SASSLY_ESSENTIAL' ),
					'hover-divide'    => esc_html__( 'Divided', 'SASSLY_ESSENTIAL' ),
					'hover-cross'     => esc_html__( 'Cross', 'SASSLY_ESSENTIAL' ),
					'hover-cropping'  => esc_html__( 'Cropping', 'SASSLY_ESSENTIAL' ),
					'rollover-top'    => esc_html__( 'Rollover Top', 'SASSLY_ESSENTIAL' ),
					'rollover-left'   => esc_html__( 'Rollover Left', 'SASSLY_ESSENTIAL' ),
					'parallal-border' => esc_html__( 'Parallel Border', 'SASSLY_ESSENTIAL' ),
					'rollover-cross'  => esc_html__( 'Rollover Cross', 'SASSLY_ESSENTIAL' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tag_typo',
				'selector' => '{{WRAPPER}} .wcf--post-tags a',
			]
		);

		$this->add_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'SASSLY_ESSENTIAL' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--post-tags a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'category_border',
				'selector'  => '{{WRAPPER}} a, {{WRAPPER}} a.btn-parallal-border:before, {{WRAPPER}} a.btn-parallal-border:after, {{WRAPPER}} a.btn-rollover-cross:before, {{WRAPPER}} a.btn-rollover-cross:after',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'SASSLY_ESSENTIAL' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--post-tags a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'transition',
			[
				'label'      => esc_html__( 'Transition', 'SASSLY_ESSENTIAL' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => .1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--post-tags a' => 'transition: all {{SIZE}}s;',
				],
			]
		);

		$this->start_controls_tabs(
			'tag_tabs'
		);

		$this->start_controls_tab(
			'normal_tab',
			[
				'label' => esc_html__( 'Normal', 'SASSLY_ESSENTIAL' ),
			]
		);

		$this->add_control(
			'color',
			[
				'label'     => esc_html__( 'Color', 'SASSLY_ESSENTIAL' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--post-tags a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'tag_bg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} a:not(.wcf-btn-ellipse), {{WRAPPER}} a.wcf-btn-mask:after, {{WRAPPER}} a.wcf-btn-ellipse:before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'box_shadow',
				'selector'  => '{{WRAPPER}} .wcf--post-tags a',
			]
		);

		$this->end_controls_tab();


		$this->start_controls_tab(
			'hover_tab',
			[
				'label' => esc_html__( 'Hover', 'SASSLY_ESSENTIAL' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label'     => esc_html__( 'Color', 'SASSLY_ESSENTIAL' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--post-tags a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'hover_bg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} a:not(.btn-item, .btn-parallal-border, .btn-rollover-cross, .wcf-btn-ellipse):after, {{WRAPPER}} .btn-hover-bgchange span, {{WRAPPER}} .btn-rollover-cross:hover, {{WRAPPER}} .btn-parallal-border:hover, {{WRAPPER}} a.wcf-btn-ellipse:hover:before,{{WRAPPER}} a.btn-hover-none:hover',
			]
		);

		$this->add_control(
			'hover_border',
			[
				'label'     => esc_html__( 'Border Color', 'SASSLY_ESSENTIAL' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--post-tags a:hover, {{WRAPPER}} a:hover, {{WRAPPER}} a:focus, {{WRAPPER}} a:hover.btn-parallal-border:before, {{WRAPPER}} a:hover.btn-parallal-border:after, {{WRAPPER}} a:hover.btn-rollover-cross:before, {{WRAPPER}} a:hover.btn-rollover-cross:after, {{WRAPPER}} a.btn-hover-none:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'hover_box_shadow',
				'selector'  => '{{WRAPPER}} .wcf--post-tags a:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {

		$settings  = $this->get_settings_for_display();
		$post_id   = get_the_id();
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() || ( isset( $_GET['preview_id'] ) && isset( $_GET['preview_nonce'] ) );

		if ( $is_editor ) {

			$recent_posts = wp_get_recent_posts( array(
				'numberposts' => 1,
				'post_status' => 'publish'
			) );

			if ( isset( $recent_posts[0] ) ) {
				$post_id = $recent_posts[0]['ID'];

			}

		}

		$tags = wp_get_post_tags( $post_id ); //this is the adjustment, all the rest is bhlarsen

		?>
        <div class="wcf--post-tags">
			<?php
			foreach ( $tags as $key => $tag ) {
				$tag_link = get_tag_link( $tag->term_id );
				?>
                <li data-separator="<?php echo esc_attr( $settings['tag_separator'] ); ?>">
                    <a href="<?php echo $tag_link; ?>" title="<?php echo $tag->name; ?> tag"
                       class="wcf-btn-default btn-<?php echo esc_html($settings['hover_list']); ?>">
						<?php
						if ( ! empty( $settings['prefix'] ) ) {
							echo esc_html( $settings['prefix'] );
						}
						echo $tag->name;
						if ( ! empty( $settings['suffix'] ) ) {
							echo esc_html( $settings['suffix'] );
						}
						?>
                    </a>
                </li>
				<?php
				if ( isset( $settings['tags_limit'] ) && is_numeric( $settings['tags_limit'] ) ) {
					if ( $settings['tags_limit'] == $key + 1 ) {
						break;
					}
				}
			} ?>
        </div>
		<?php
	}
}