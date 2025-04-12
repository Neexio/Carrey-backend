<?php
/**
 * Loader Class
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class Carrey_Loader {
    private static $instance = null;
    private $actions;
    private $filters;
    private $shortcodes;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    public function run() {
        $this->load_dependencies();
        $this->register_hooks();
        $this->register_shortcodes();
    }

    private function load_dependencies() {
        // Load required files
        $files = array(
            'includes/class-carrey-api.php',
            'includes/class-carrey-cache.php',
            'includes/class-carrey-validator.php',
            'includes/class-carrey-logger.php',
            'includes/class-carrey-notifier.php'
        );

        foreach ($files as $file) {
            $file_path = CARREY_SEO_PLUGIN_DIR . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }

    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    private function register_hooks() {
        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                array($hook['component'], $hook['callback']),
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                array($hook['component'], $hook['callback']),
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }

    public function add_shortcode($tag, $component, $callback) {
        $this->shortcodes = $this->add_shortcode_to_list($this->shortcodes, $tag, $component, $callback);
    }

    private function add_shortcode_to_list($shortcodes, $tag, $component, $callback) {
        $shortcodes[] = array(
            'tag'       => $tag,
            'component' => $component,
            'callback'  => $callback
        );

        return $shortcodes;
    }

    private function register_shortcodes() {
        foreach ($this->shortcodes as $shortcode) {
            add_shortcode(
                $shortcode['tag'],
                array($shortcode['component'], $shortcode['callback'])
            );
        }
    }

    public function verify_plugin_integrity() {
        $required_files = array(
            'includes/class-carrey-api.php',
            'includes/class-carrey-cache.php',
            'includes/class-carrey-validator.php',
            'includes/class-carrey-logger.php',
            'includes/class-carrey-notifier.php'
        );

        $missing_files = array();
        foreach ($required_files as $file) {
            if (!file_exists(CARREY_SEO_PLUGIN_DIR . $file)) {
                $missing_files[] = $file;
            }
        }

        if (!empty($missing_files)) {
            $this->log_error('Missing files: ' . implode(', ', $missing_files));
            return false;
        }

        return true;
    }

    private function log_error($message) {
        if (function_exists('error_log')) {
            error_log('Carrey SEO Dashboard: ' . $message);
        }
    }
} 