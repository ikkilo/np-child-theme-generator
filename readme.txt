
=== NP Child Theme Generator ===
Contributors: Nikhil Prabha
Tags: child theme, theme customization, WordPress
Requires at least: 5.0
Tested up to: 6.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to generate a child theme for your active theme with a simple button click. The button is dynamically displayed in the WordPress admin themes section.

== Description ==

NP Child Theme Generator dynamically adds a "Generate Child Theme" button for the active theme in the WordPress themes section. It checks if a child theme already exists and prevents generating multiple child themes for the same theme.

== Installation ==

1. Upload the `np-child-theme-generator` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to the 'Appearance' -> 'Themes' section in your WordPress admin.
4. The "Generate Child Theme" button will appear under the active theme's screenshot.

== How to Use ==

1. Navigate to 'Appearance' -> 'Themes' in your WordPress admin panel.
2. The "Generate Child Theme" button will automatically appear for the active theme.
3. Click the button to generate a child theme.
4. If a child theme already exists, the button will not appear.
5. The plugin uses a MutationObserver to watch for changes in the theme selection and dynamically add the button as needed.
6. Enjoy easy child theme generation!

== Changelog ==

= 1.0.0 =
* Initial release.

== License ==

This plugin is licensed under the GPLv2 or later license. See https://www.gnu.org/licenses/gpl-2.0.html for more details.
