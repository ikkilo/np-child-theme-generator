<?php
/**
 * Plugin Name: NP Child Theme Generator
 * Description: Automatically generates a child theme with assets for the active theme.
 * Version: 1.2
 * Author: Nikhil Prabha
 * Author URI: https://www.linkedin.com/in/nikhil-p-5a6295119/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin path and URL
define('NPCTG_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('NPCTG_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoload required files and handle any errors if files are missing
try {
    if (file_exists(NPCTG_PLUGIN_PATH . 'includes/class-theme-generator.php')) {
        require_once NPCTG_PLUGIN_PATH . 'includes/class-theme-generator.php';
    } else {
        throw new Exception('Class file for Theme Generator is missing.');
    }

    if (file_exists(NPCTG_PLUGIN_PATH . 'includes/class-theme-assets.php')) {
        require_once NPCTG_PLUGIN_PATH . 'includes/class-theme-assets.php';
    } else {
        throw new Exception('Class file for Theme Assets is missing.');
    }
} catch (Exception $e) {
    error_log('Error loading plugin: ' . $e->getMessage());
    wp_die('Error loading the plugin: ' . $e->getMessage());
}

// Enqueue script only on the theme page (themes.php)
function npctg_enqueue_admin_script($hook) {
    if ($hook === 'themes.php') { // Check if on the themes page
        wp_enqueue_script('np-child-theme-generator', NPCTG_PLUGIN_URL . 'assets/js/child-theme-button.js', ['jquery'], '1.2', true);

        // Pass the admin URL and nonce to JavaScript
        wp_localize_script('np-child-theme-generator', 'npctg_data', [
            'ajax_url' => admin_url('themes.php'),
            'nonce' => wp_create_nonce('npctg_create_child_theme_nonce')
        ]);
    }
}

add_action('admin_enqueue_scripts', 'npctg_enqueue_admin_script');

// Initialize the main theme generator class
function npctg_initialize_plugin(): void {
    try {
        $theme_generator = new NPCTG_Theme_Generator();
        $theme_generator->init();
    } catch (Exception $e) {
        error_log('Error initializing plugin: ' . $e->getMessage());
        wp_die('Error initializing the theme generator: ' . $e->getMessage());
    }
}

add_action('plugins_loaded', 'npctg_initialize_plugin');
