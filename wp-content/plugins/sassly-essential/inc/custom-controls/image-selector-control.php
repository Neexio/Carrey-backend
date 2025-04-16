<?php
namespace SasslyEssentialApp\CustomControl;

use \Elementor\Base_Data_Control;

class ImageSelector_Control extends Base_Data_Control {

	const ImageSelector = 'image_selector';
	/**
	 * Set control type.
	 */
	public function get_type() {
		return self::ImageSelector;
	}

	/**
	 * Enqueue control scripts and styles.
	 */
	public function enqueue() {
		$url = SASSLY_ESSENTIAL_URL.'/inc/custom-controls/css/';
		// Styles
		wp_enqueue_style('wcf-image-selector', $url.'image-selector.css', array(), '');
	}

	/**
	 * Set default settings
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'bgcolor' => '#fff',
			'col' => 2,
			'toggle' => true,
			'options' => [],
		];
	}
	
	/**
	 * control field markup
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid('{{ value }}');
		?>
		<div class="elementor-control-field">
		   	<label class="elementor-control-title">{{{ data.label }}}</label>
				<div class="elementor-control-image-selector-wrapper col-{{{ data.col }}}" style="background: {{{ data.bgcolor }}}">
					<# _.each( data.options, function( options, value ) { 					
					 #>
					 <div class="elementor-wcf-col">
						<input id="<?php echo $control_uid; ?>" type="radio" name="elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
						<label class="elementor-image-selector-label tooltip-target" for="<?php echo $control_uid; ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
							<img src="{{ options.url }}" alt="{{ options.title }}">
							<span class="elementor-screen-only">{{{ options.title }}}</span>
							<# if( options.preview_url ) { #>
							<img class="wcf-element-hover-preview" src="{{ options.preview_url }}" >
							<# } #>
						</label>
					</div>
					<# } ); #>
				</div>
			
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

}