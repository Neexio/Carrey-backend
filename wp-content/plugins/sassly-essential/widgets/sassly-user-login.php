<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Background;
use Elementor\Utils;
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
use Elementor\Icons_Manager;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class Sassly_User_Login extends \Elementor\Widget_Base {

	public function get_name() {
		return 'sassly--user-login';
	}

	public function get_title() {
		return esc_html__( 'Sassly User Login', 'sassly-essential' );
	}

	public function get_icon() {
		return 'wcf eicon-button';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_style_depends() {
		wp_register_style( 'sassly-login-register', SASSLY_ESSENTIAL_ASSETS_URL . 'css/login-register.css' );
		return [ 'sassly-login-register' ];
	}

	public function get_script_depends() {		
		return [ 'sassly-usersign-up-in' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Login Form', 'sassly-essential' ),
			]
		);
		
		$this->add_control(
			'show_form_if_login',
			[
				'label' => esc_html__( 'Show Form If login', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'email',
			[
				'label' => esc_html__( 'Email Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Enter your email', 'sassly-essential' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'password',
			[
				'label' => esc_html__( 'Password Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Enter password', 'sassly-essential' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'checkbox_text',
			[
				'label' => esc_html__( 'Checkbox Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Remember me', 'sassly-essential' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'forgot_text',
			[
				'label' => esc_html__( 'Forgot Password Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Forgot your password?', 'sassly-essential' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'submit_text',
			[
				'label' => esc_html__( 'Submit Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Login', 'sassly-essential' ),
				'label_block' => true,
			]
		);
		

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_redirect_content',
			[
				'label' => esc_html__( 'Redirect', 'sassly-essential' ),				
			]
		);
								
			$this->add_control(
				'enable_redirect',
				[
					'label' => esc_html__( 'Enable Redirect', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
					'label_off' => esc_html__( 'No', 'sassly-essential' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);		
		
			$this->add_control(
				'redirect_url',
				[
					'label' => esc_html__( 'Redirect Url', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::URL,
					'label_block' => true,
					'condition' => [
						'enable_redirect' => ['yes']
					]
				]
			);
			
		
		$this->end_controls_section();

		// Input Style
		$this->start_controls_section(
			'sec_style_input',
			[
				'label' => esc_html__( 'Input', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-input input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'input_typo',
				'selector' => '{{WRAPPER}} .single-input input',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'selector' => '{{WRAPPER}} .single-input input',
			]
		);

		$this->add_responsive_control(
			'input_b_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .single-input input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .single-input input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_margin',
			[
				'label' => esc_html__( 'Margin', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .single-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'input_style_tabs'
		);
		
		// Normal
		$this->start_controls_tab(
			'input_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'input_pl_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-input input::placeholder' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();

		// Focus
		$this->start_controls_tab(
			'input_focus_tab',
			[
				'label' => esc_html__( 'Focus', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'input_pl_h_color',
			[
				'label' => esc_html__( 'Placeholder Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-input input:focus::placeholder' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_h_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-input input:focus' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// Checkbox Style
		$this->start_controls_section(
			'sec_style_checkbox',
			[
				'label' => esc_html__( 'Checkbox', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'checkbox_color',
			[
				'label' => esc_html__( 'Color', 'textdomain' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .checkbox-input input, {{WRAPPER}} .checkbox-input label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'checkbox_typo',
				'selector' => '{{WRAPPER}} .checkbox-input input, {{WRAPPER}} .checkbox-input label',
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => esc_html__( 'Link Color', 'textdomain' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .checkbox-input a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_h_color',
			[
				'label' => esc_html__( 'Link Hover Color', 'textdomain' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .checkbox-input a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typo',
				'selector' => '{{WRAPPER}} .checkbox-input a',
			]
		);

		$this->add_responsive_control(
			'checkbox_margin',
			[
				'label' => esc_html__( 'Margin', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .checkbox-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Submit Style
		$this->start_controls_section(
			'sec_style_submit',
			[
				'label' => esc_html__( 'Submit Button', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_typo',
				'selector' => '{{WRAPPER}} .submit-input .submit',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'submit_bg',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .submit-input .submit',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'submit_border',
				'selector' => '{{WRAPPER}} .submit-input .submit',
			]
		);

		$this->add_responsive_control(
			'submit_b_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .submit-input .submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'submit_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .submit-input .submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'submit_style_tabs'
		);
		
		// Normal
		$this->start_controls_tab(
			'submit_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'submit_btn_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .submit-input .submit' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'submit_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'submit_pl_h_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .submit-input .submit:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'submit_h_b_color',
			[
				'label' => esc_html__( 'Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .submit-input .submit:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'submit_h_bg',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => ['image'],
				'selector' => '{{WRAPPER}} .submit-input .submit:hover',
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		// Messages
		$this->start_controls_section(
			'section_msg_style',
			[
				'label' => esc_html__( 'Message & Error', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'error_msg_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sassly-login-msg' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'msg_typo',
				'selector' => '{{WRAPPER}} .sassly-login-msg',
			]
		);

		$this->add_responsive_control(
			'msg_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .sassly-login-msg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'msg_align',
			[
				'label' => esc_html__( 'Alignment', 'sassly-essential' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sassly-essential' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sassly-essential' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sassly-essential' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .sassly-login-msg' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$redirect = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		global $sassly_login_errors;
		?>

		<div class="sassly--user-form">
			<?php if( $sassly_login_errors ) { ?>
			<div class="sassly-login-msg">
				<?php echo wp_kses_post($sassly_login_errors); ?>
			</div>
			<?php } ?>
			<?php echo sprintf(
			'<form name="%1$s" id="%1$s" action="%2$s" method="post">',
			'sassy-logform',
			esc_url( $redirect )
			); ?>

			<style>
				.sassly-login-msg {
					padding-bottom: 15px;
				}
			</style>
			
				<div class="single-input">
					<input type="text" name="log" placeholder="<?php echo esc_html( $settings['email'] ); ?>">
				</div>
				<div class="single-input">
					<input type="password" name="pwd" placeholder="<?php echo esc_html( $settings['password'] ); ?>">
				</div>
				<div class="checkbox-input">
					<div class="checkbox">
						<input type="checkbox" name="rememberme" id="remember">
						<label for="remember"><?php echo esc_html( $settings['checkbox_text'] ); ?></label>
					</div>
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php echo esc_html( $settings['forgot_text'] ); ?></a>
				</div>
				<div class="submit-input">
					<input type="submit" name="sassly-submit" class="submit" value="<?php echo esc_html( $settings['submit_text'] ); ?>">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect); ?>" />
					<input type="hidden" name="sassly_sucess_enable_redirect" value="<?php echo esc_attr($settings['enable_redirect']); ?>" />
					<input type="hidden" name="sassly_sucess_redirect" value="<?php echo esc_url($settings['redirect_url']['url']); ?>" />
				</div>
			</form>
		</div>		
		<?php
	}
	
	

}