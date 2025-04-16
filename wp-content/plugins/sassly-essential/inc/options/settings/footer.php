<?php

// Footer
CSF::createSection( SASSLY_OPTION_KEY, array(
	'id'    => 'footer_tab',
	'title' => esc_html__( 'Footer', 'sassly-essential' ),
	'icon'  => 'fa fa-cog',
	'fields' => array(
		array(
			'type'     => 'callback',
			'function' => 'sassly_footer_style',
		),
	)
) );


// Callback function
function sassly_footer_style() {

	if ( ! class_exists( 'WCF_ADDONS_Plugin' ) ) {
		?>
		<?php esc_html_e( "To Customize Footer, Please Install animation addons plugin", 'sassly-essential' ); ?>
		<?php
		return;
	}
	?>
	<style>
        .wcf-hf-btn {
            padding: 18px 24px;
            font-weight: 500;
            font-size: 16px;
            color: #fff;
            background: #121212;
            transition: all 0.3s;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .wcf-hf-btn:hover {
            color: #fff;
            background: #FC5A11;
        }
	</style>
	<a class="wcf-hf-btn" href="<?php echo admin_url( 'edit.php?post_type=wcf-addons-template&template_type=footer' ); ?>">
		<i class="csf-tab-icon fa fa-cog"></i>
		<?php esc_html_e( " Customize Footer", 'sassly-essential' ); ?>
	</a>
	<?php
}