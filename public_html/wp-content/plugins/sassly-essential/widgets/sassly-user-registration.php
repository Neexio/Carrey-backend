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

class Sassly_User_Registration extends \Elementor\Widget_Base {

	public function get_name() {
		return 'sassly--user-registration';
	}

	public function get_title() {
		return esc_html__( 'Sassly User Registration', 'sassly-essential' );
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
				'label' => esc_html__( 'Registration Form', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'name',
			[
				'label' => esc_html__( 'Name Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Type your name', 'sassly-essential' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'username',
			[
				'label' => esc_html__( 'Username Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'User name', 'sassly-essential' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'email',
			[
				'label' => esc_html__( 'Email Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Type Email', 'sassly-essential' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'password',
			[
				'label' => esc_html__( 'Password Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Type Password', 'sassly-essential' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'confirm_password',
			[
				'label' => esc_html__( 'Confirm Password Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Confirm Password', 'sassly-essential' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'checkbox_text',
			[
				'label' => esc_html__( 'Checkbox Text', 'sassly-essential' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Remember me', 'sassly-essential' ),
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
		$this->start_controls_section(
			'section_msg_content',
			[
				'label' => esc_html__( 'White Label Message', 'sassly-essential' ),				
			]
		);
			
			$this->add_control(
				'user_registed_success_wlabel',
				[
					'label' => esc_html__('Registration complete','sassly-essential'),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__('Registration complete. Please check your email.','sassly-essential'),
					'label_block' => true,
				]
			);
		
		
			$this->add_control(
				'required_fields_wlabel',
				[
					'label' => esc_html__( 'Fill up all required fields', 'sassly-essential' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Fill up all required fields', 'sassly-essential' ),
					'label_block' => true,
				]
			);
			
			$this->add_control(
				'username_length_wlabel',
				[
					'label' => esc_html__( 'Username Must be greater than 4', 'sassly-essential' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Username Must be greater than 4', 'sassly-essential' ),
					'label_block' => true,
				]
			);
			
			$this->add_control(
				'email_already_exist_wlabel',
				[
					'label' => esc_html__( 'Email Already Exist', 'sassly-essential' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Email Already Exist', 'sassly-essential' ),
					'label_block' => true,
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
				'label' => esc_html__( 'Color', 'sassly-essential' ),
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
				'label' => esc_html__( 'Link Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .checkbox-input a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_h_color',
			[
				'label' => esc_html__( 'Link Hover Color', 'sassly-essential' ),
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'msg_typo',
				'selector' => '{{WRAPPER}} .sassly--userjxform-msg, {{WRAPPER}} .sassly-error',
			]
		);

		$this->add_responsive_control(
			'msg_padding',
			[
				'label' => esc_html__( 'Message Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .sassly--userjxform-msg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'msg_align',
			[
				'label' => esc_html__( 'Alignment', 'sassly-essential' ),
				'type' => Controls_Manager::CHOOSE,
				'separator' => 'after',
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
					'{{WRAPPER}} .sassly--userjxform-msg, {{WRAPPER}} .sassly-error' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'success_msg_color',
			[
				'label' => esc_html__( 'Success Message Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sassly--userjxform-msg' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'error_msg_color',
			[
				'label' => esc_html__( 'Error Message Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sassly-error' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'user_msg_color',
			[
				'label' => esc_html__( 'User Message Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .user-msg' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'user_msg_typo',
				'selector' => '{{WRAPPER}} .user-msg',
			]
		);

		$this->add_control(
			'error_input_bg',
			[
				'label' => esc_html__( 'Invalid Input Background', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .single-input.error input' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
            $msg = [
                  'user_registed_success_wlabel' => $settings['user_registed_success_wlabel'],
                  'required_fields_wlabel'       => $settings['required_fields_wlabel'],
                  'username_length_wlabel'       => $settings['username_length_wlabel'],
                  'email_already_exist_wlabel'   => $settings['email_already_exist_wlabel'],
            ];

						$re_url = isset($settings[ 'redirect_url' ]['url']) ? $settings[ 'redirect_url' ]['url'] : '#';
		?>
		<style>
			.sassly--user-form .single-input.error input{
				background-color : #ff690010 !important; 
			}
			.sassly--userjxform-msg {
				padding-bottom: 15px;
			}

			.sassly-error{
				color:#ff6900;
			}
		</style>


		
		<div class="sassly--user-form" >
			<div class="sassly--userjxform-msg"></div>	
			<div hidden style="display:none" class="sassly-msg-labels"><?php echo json_encode($msg); ?></div>
			<form action="#" method="POST" class="sassly--signupform--wrapper" data-redirect="<?php echo esc_url( $re_url ); ?>" data-redirectenable="<?php echo $settings[ 'enable_redirect' ]; ?>">
				<div class="single-input">
					<input type="text" class="sassly-uform-fullname" name="name" placeholder="<?php echo esc_html( $settings['name'] ); ?>">
				</div>
				<div class="single-input">
					<input type="text" class="sassly-uform-username" name="username" placeholder="<?php echo esc_html( $settings['username'] ); ?>">
					<span class="user-msg"></span>
				</div>
				<div class="single-input">
					<input type="email" class="sassly-uform-email" name="email" placeholder="<?php echo esc_html( $settings['email'] ); ?>">
				</div>
				<div class="single-input">
					<input type="password" class="sassly-uform-password" name="password" placeholder="<?php echo esc_html( $settings['password'] ); ?>">
				</div>
				<div class="single-input">
					<input type="password" class="sassly-uform-cpassword" name="cpass" placeholder="<?php echo esc_html( $settings['confirm_password'] ); ?>">
				</div>
				<div class="checkbox-input">
					<div class="checkbox">
						<input type="checkbox" class="sassly-uform-terms" name="terms" id="sass-terms">
						<label for="sass-terms"><?php echo wp_kses_post( $settings['checkbox_text'] ); ?></label>
					</div>
				</div>
				<div class="submit-input sassly-submit-js">
					<input type="submit" class="submit" value="<?php echo esc_html( $settings['submit_text'] ); ?>">
				</div>
			</form>
		</div>
		<?php
	}

}