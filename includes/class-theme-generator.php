<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class NPCTG_Theme_Generator {

    public function __construct() {
        // Constructor logic here
    }

    public function init(): void {
        try {
            // Add button to themes page
            add_action('theme_action_links', [$this, 'add_create_child_button'], 10, 3);
            
            // Handle theme creation
            add_action('admin_init', [$this, 'handle_create_child_theme']);

            // Enqueue necessary assets
            add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        } catch (Exception $e) {
            error_log('Error during theme generator initialization: ' . $e->getMessage());
        }
    }

    public function enqueue_assets(): void {
        if (is_admin()) {
            wp_enqueue_style('np-child-theme-generator-admin', NPCTG_PLUGIN_URL . 'assets/css/admin.css', [], '1.0');
        }
    }

    public function handle_create_child_theme(): void {
        // Check if 'action' exists in the $_GET array and if it's the expected value
        if (isset($_GET['action']) && $_GET['action'] === 'npctg_create_child_theme') {
            try {
                $nonce = $_GET['nonce'] ?? '';
                if (!wp_verify_nonce($nonce, 'npctg_create_child_theme_nonce')) {
                    throw new Exception('Nonce verification failed.');
                }

                // Get current user
                $current_user = wp_get_current_user();
                if (!$current_user->exists()) {
                    throw new Exception('Failed to retrieve user data.');
                }

                $author_name = $current_user->first_name . ' ' . $current_user->last_name;

                // Sanitize theme slug
                $theme_slug = sanitize_text_field($_GET['theme']);
                $active_theme = wp_get_theme($theme_slug);

                if (!$active_theme->exists()) {
                    throw new Exception('The specified theme does not exist.');
                }

                if ($active_theme->parent()) {
                    throw new Exception('Cannot create a child theme for another child theme.');
                }

                // Proceed to create the child theme
                $assets_manager = new NPCTG_Theme_Assets($active_theme, $author_name);
                $assets_manager->create_child_theme();

            } catch (Exception $e) {
                error_log('Error creating child theme: ' . $e->getMessage());
                wp_die('Error creating the child theme: ' . $e->getMessage());
            }
        }
    }
}
