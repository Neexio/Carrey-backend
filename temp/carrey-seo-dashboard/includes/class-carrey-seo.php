<?php
if (!defined('ABSPATH')) {
    exit;
}

class Carrey_SEO {
    private static $instance = null;
    private $api_key;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->api_key = get_option('carrey_api_key');
        add_action('wp_ajax_carrey_analyze_website', array($this, 'analyze_website'));
        add_action('wp_ajax_carrey_track_keyword', array($this, 'track_keyword'));
        add_action('wp_ajax_carrey_get_keyword_positions', array($this, 'get_keyword_positions'));
    }

    public function analyze_website($url) {
        // Simulate API call to analyze website
        $analysis = array(
            'seo_score' => rand(60, 95),
            'issues' => array(
                array(
                    'title' => 'Missing Meta Description',
                    'description' => 'Add meta description to improve click-through rate',
                    'severity' => 'medium'
                ),
                array(
                    'title' => 'Slow Page Load',
                    'description' => 'Optimize images and enable caching',
                    'severity' => 'high'
                ),
                array(
                    'title' => 'Missing Alt Tags',
                    'description' => 'Add alt tags to images for better accessibility',
                    'severity' => 'low'
                )
            ),
            'recommendations' => array(
                array(
                    'title' => 'Optimize Content',
                    'description' => 'Update content based on keyword analysis',
                    'action' => 'optimize_content'
                ),
                array(
                    'title' => 'Fix Technical Issues',
                    'description' => 'Address identified technical SEO problems',
                    'action' => 'fix_technical'
                )
            )
        );

        return $analysis;
    }

    public function track_keyword($website_id, $keyword) {
        $user_id = get_current_user_id();
        $keywords = get_user_meta($user_id, 'carrey_keywords', true) ?: array();
        
        if (!isset($keywords[$website_id])) {
            $keywords[$website_id] = array();
        }

        $keywords[$website_id][] = array(
            'keyword' => $keyword,
            'position' => rand(1, 100),
            'change' => rand(-5, 5),
            'volume' => rand(100, 10000),
            'difficulty' => rand(1, 100)
        );

        update_user_meta($user_id, 'carrey_keywords', $keywords);
        return true;
    }

    public function get_keyword_positions($website_id) {
        $user_id = get_current_user_id();
        $keywords = get_user_meta($user_id, 'carrey_keywords', true) ?: array();
        
        if (!isset($keywords[$website_id])) {
            return array();
        }

        return $keywords[$website_id];
    }

    public function get_seo_score($website_id) {
        $user_id = get_current_user_id();
        $seo_stats = get_user_meta($user_id, 'carrey_seo_stats', true) ?: array();
        
        if (!isset($seo_stats[$website_id])) {
            return 0;
        }

        return $seo_stats[$website_id]['seo_score'];
    }

    public function get_issues($website_id) {
        $user_id = get_current_user_id();
        $seo_stats = get_user_meta($user_id, 'carrey_seo_stats', true) ?: array();
        
        if (!isset($seo_stats[$website_id])) {
            return array();
        }

        return $seo_stats[$website_id]['issues'];
    }

    public function get_recommendations($website_id) {
        $user_id = get_current_user_id();
        $seo_stats = get_user_meta($user_id, 'carrey_seo_stats', true) ?: array();
        
        if (!isset($seo_stats[$website_id])) {
            return array();
        }

        return $seo_stats[$website_id]['recommendations'];
    }

    public function update_seo_stats($website_id, $stats) {
        $user_id = get_current_user_id();
        $seo_stats = get_user_meta($user_id, 'carrey_seo_stats', true) ?: array();
        $seo_stats[$website_id] = $stats;
        update_user_meta($user_id, 'carrey_seo_stats', $seo_stats);
    }
} 