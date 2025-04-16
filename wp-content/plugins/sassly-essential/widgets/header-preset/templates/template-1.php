<?php
/**
 * Template Style
 *
 * @package WCFAddons
 */

use Elementor\Group_Control_Image_Size;
use SasslyEssentialApp\Inc\WCF_Walker_Elementor_Nav;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * @var array $settings
 */
$menu_selected = $settings['menu_selected'];

$nav_walker_default = [
	'custom_icon'             => $settings['custom_direction'],
	'menu_down_icon'          => $settings['menu_down_icon'],
	'menu_right_icon'         => $settings['menu_right_icon'],
	'has_dropdown_arrow_icon' => ( isset( $settings['menu_down_icon']['value'] ) && $settings['menu_down_icon']['value'] != '' ) || ( isset( $settings['menu_down_icon']['value'] ) && is_array( $settings['menu_down_icon']['value'] ) ) ? true : false,
	'has_right_arrow_icon'    => ( isset( $settings['menu_right_icon']['value'] ) && $settings['menu_right_icon']['value'] != '' ) || ( isset( $settings['menu_right_icon']['value'] ) && is_array( $settings['menu_right_icon']['value'] ) ) ? true : false
];

$args = [
	'menu'            => $menu_selected,
	'container'       => 'nav',
	'container_class' => 'main-menu',
	'menu_class'      => 'wcf--elementor--menu',
	'walker'          => new WCF_Walker_Elementor_Nav( $nav_walker_default )
];

$tpl_data = [
	'target' => 'offcanvasOne'
];

$bar = SASSLY_ESSENTIAL_ASSETS_URL ."images/bars/".$settings['bar'];

if ( $settings['custom_bar'] == 'yes' && isset( $settings['custom_bar_image']['url'] ) ) {
	$bar = $settings['custom_bar_image']['url'];
}
?>


<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
	<div class="header__inner">

		<div class="header__logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumbnail', 'header_logo' ); ?>
				<?php echo sassly_get_attachment_image_html( $settings, 'thumbnail', 'sticky_logo', ['class' => 'wcf-sticky-logo'] ); ?>				
			</a>
		</div>

		<div class="header__nav">
			<?php wp_nav_menu($args); ?>
		</div>

		<div class="header__others">
			<div class="more-actions">
				<?php foreach ( $settings['action'] as $key => $item ) { ?>
					<div class="item <?php echo $item['separator'] === 'yes'? 'wcf-separator' : ''; ?> <?php echo $item['action_type']; ?>">
						<?php $this->render_action( $item, $key, $settings ); ?>
					</div>
				<?php }	?>
			</div>

			<?php if ( 'yes' === $settings['show_language'] ): ?>
				<div class="header__lang">
					<select id="lang">
						<?php foreach($settings['language_list'] as $option){ ?>
							<option value="<?php echo $option['language'] ?>"><?php echo $option['language'] ?></option>
						<?php } ?>
					</select>
				</div>
			<?php endif; ?>

			<button class="wcf-header--offcanvas--icon" data-bs-toggle="offcanvas" data-bs-target="#<?php echo esc_attr($tpl_data['target']); ?>">
				<?php if( $bar !='' ){ ?>
					<img src="<?php echo esc_url( $bar ); ?>" />
					<?php echo sassly_get_attachment_image_html( $settings, 'thumbnail', 'custom_sticky_bar', ['class' => 'wcf-sticky-bar'] ); ?>
				<?php }else{ ?>
					<span class="menu-icon"><span></span></span>
				<?php } ?>
			</button>

		</div>
	</div>
</div>
