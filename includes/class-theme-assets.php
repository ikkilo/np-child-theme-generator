<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class NPCTG_Theme_Assets {
    public function __construct(private WP_Theme $theme) {}

    public function create_child_theme(): void {
        try {
            $child_theme_slug = $this->theme->get_stylesheet() . '-child';
            $child_theme_dir = get_theme_root() . '/' . $child_theme_slug;

            if (file_exists($child_theme_dir)) {
                throw new Exception('Child theme already exists.');
            }

            if (!wp_mkdir_p($child_theme_dir)) {
                throw new Exception('Failed to create child theme directory.');
            }

            // Create and copy necessary files
            $this->copy_screenshot($child_theme_dir);
            $this->create_files($child_theme_dir);

            // Activate the child theme
            switch_theme($child_theme_slug);
            wp_redirect(admin_url('themes.php?child_theme_created=true'));
            exit;
        } catch (Exception $e) {
            wp_die('Error creating the child theme: ' . $e->getMessage());
        }
    }

    private function copy_screenshot(string $child_theme_dir): void {
        $screenshot_path = $this->theme->get_screenshot();
        if ($screenshot_path && !copy($screenshot_path, $child_theme_dir . '/screenshot.png')) {
            throw new Exception('Failed to copy screenshot.');
        }
    }

    private function create_files(string $child_theme_dir): void {
        $style_content = "/*
        Theme Name: {$this->theme->get('Name')} Child
        Template: {$this->theme->get_stylesheet()}
        Version: 1.0
        */";

        if (file_put_contents($child_theme_dir . '/style.css', $style_content) === false) {
            throw new Exception('Failed to create style.css.');
        }

        $functions_content = "<?php
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
            wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/assets/css/style.css');
            wp_enqueue_script('child-script', get_stylesheet_directory_uri() . '/assets/js/script.js', [], false, true);
        });";

        if (file_put_contents($child_theme_dir . '/functions.php', $functions_content) === false) {
            throw new Exception('Failed to create functions.php.');
        }
    }
}

