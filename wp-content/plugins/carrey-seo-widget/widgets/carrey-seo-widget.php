<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Carrey_SEO_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'carrey_seo_widget';
    }

    public function get_title() {
        return esc_html__('Carrey SEO Widget', 'carrey-seo-widget');
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['carrey-seo-widget-script'];
    }

    public function get_style_depends() {
        return ['carrey-seo-widget-style'];
    }

    protected function register_controls() {
        // No controls needed for this widget
    }

    protected function render() {
        // Output the widget HTML
        ?>
        <div class="carrey-seo-widget" style="padding: 30px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); max-width: 800px; margin: 0 auto;">
            <div class="carrey-seo-form" style="display: flex; gap: 15px; margin-bottom: 25px;">
                <input type="text" class="carrey-seo-input" placeholder="Enter website URL" style="flex: 1; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;">
                <button class="carrey-seo-button" style="padding: 12px 25px; background: #2ecc71; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 500; transition: background-color 0.3s;">Analyze</button>
            </div>
            <div class="carrey-seo-results" style="display: none; margin-top: 25px;">
                <div class="carrey-seo-loading" style="text-align: center; padding: 25px; color: #7f8c8d; font-size: 16px;">
                    <div style="margin-bottom: 10px;">Analyzing website...</div>
                    <div class="loading-spinner" style="display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f3f3; border-top: 3px solid #2ecc71; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                </div>
                <div class="carrey-seo-error" style="display: none; color: #e74c3c; padding: 15px; background: #fde8e8; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #e74c3c;"></div>
                <div class="carrey-seo-content" style="display: none; background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0;"></div>
            </div>
        </div>

        <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .carrey-seo-input:focus {
            border-color: #2ecc71 !important;
            outline: none;
        }

        .carrey-seo-button:hover {
            background-color: #27ae60 !important;
        }

        .seo-score {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .seo-item {
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .seo-item.good {
            border-left: 4px solid #2ecc71;
        }

        .seo-item.warning {
            border-left: 4px solid #f1c40f;
        }

        .seo-item.bad {
            border-left: 4px solid #e74c3c;
        }

        .seo-item-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .seo-item-content {
            color: #4a5568;
            font-size: 14px;
        }

        .seo-item-details {
            margin-top: 8px;
            font-size: 13px;
            color: #718096;
        }
        </style>
        <?php
    }
} 