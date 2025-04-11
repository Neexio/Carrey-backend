<?php
if (!defined('ABSPATH')) {
    exit;
}

class Carrey_SEO_Audit_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'carrey_seo_audit';
    }

    public function get_title() {
        return __('SEO Analyse', 'carrey-seo-widget');
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Innstillinger', 'carrey-seo-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Tittel', 'carrey-seo-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('SEO Analyse', 'carrey-seo-widget'),
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label' => __('Plassholder tekst', 'carrey-seo-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Skriv inn nettstedets URL', 'carrey-seo-widget'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="carrey-seo-widget">
            <h2><?php echo esc_html($settings['title']); ?></h2>
            <div class="carrey-seo-form">
                <input type="text" class="carrey-seo-input" placeholder="<?php echo esc_attr($settings['placeholder']); ?>">
                <button class="carrey-seo-button"><?php _e('Analyser', 'carrey-seo-widget'); ?></button>
            </div>
            <div class="carrey-seo-results" style="display: none;">
                <div class="carrey-seo-loading"><?php _e('Analyserer...', 'carrey-seo-widget'); ?></div>
                <div class="carrey-seo-error" style="display: none;"></div>
                <div class="carrey-seo-content" style="display: none;"></div>
            </div>
        </div>
        <?php
    }
} 