<?php
/*
Plugin Name: Jovi Feedback Form
Plugin URI: 
Description: A simple feedback form plugin for WordPress
Version: 1.1
Author: Sandeep Dahiya
Author URI: sandeep.codes
License: GPL v2 or later
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('JOVI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('JOVI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('JOVI_VERSION', '1.1');

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'JOVI_';
    $base_dir = JOVI_PLUGIN_DIR . 'includes/';

    // Check if the class uses our prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Handle special cases for admin classes
    if (strpos($relative_class, 'Admin_') === 0) {
        $file = $base_dir . 'admin/class-' . str_replace('_', '-', strtolower($relative_class)) . '.php';
    } else {
        // For other classes
        $file = $base_dir . 'class-' . str_replace('_', '-', strtolower($relative_class)) . '.php';
    }

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
class WP_Feedback_Form {
    /**
     * Initialize the plugin
     */
    public static function init() {
        // Initialize database
        register_activation_hook(__FILE__, array('JOVI_Database', 'create_tables'));
        add_action('plugins_loaded', array('JOVI_Database', 'maybe_upgrade'));

        // Initialize admin
        if (is_admin()) {
            JOVI_Admin_Page::init();
        }

        // Initialize form handler
        JOVI_Form_Handler::init();

        // Register shortcode
        add_shortcode('feedback_form', array('JOVI_Form_Handler', 'render_form'));
    }
}

// Start the plugin
WP_Feedback_Form::init(); 