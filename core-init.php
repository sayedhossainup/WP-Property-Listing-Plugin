<?php

/*
 *
 * 	***** WP Property for sale *****
 *
 * 	This file initializes all WPFS Core components
 * 	
 */
// If this file is called directly, abort. //
if (!defined('WPINC')) {
    die;
} // end if
// Define Our Constants
define('WPFS_CORE_INC', dirname(__FILE__) . '/assets/inc/');
define('WPFS_CORE_IMG', plugins_url('assets/img/', __FILE__));
define('WPFS_CORE_CSS', plugins_url('assets/css/', __FILE__));
define('WPFS_CORE_JS', plugins_url('assets/js/', __FILE__));
/*
 *
 *  Register CSS
 *
 */

function wpfs_register_core_css() {
    wp_enqueue_style('wpfs-core', WPFS_CORE_CSS . 'wpfs-core.css', null, time(), 'all');
}
add_action('wp_enqueue_scripts', 'wpfs_register_core_css');
/*
 *
 *  Register JS/Jquery Ready
 *
 */

function wpfs_register_core_js() {
// Register Core Plugin JS	
    wp_enqueue_script('wpfs-core', WPFS_CORE_JS . 'wpfs-core.js', 'jquery', time(), true);
}
add_action('wp_enqueue_scripts', 'wpfs_register_core_js');
/*
 *
 *  Includes
 *
 */
// Load the Functions
if (file_exists(WPFS_CORE_INC . 'wpfs-core-functions.php')) {
    require_once WPFS_CORE_INC . 'wpfs-core-functions.php';
}
// Load the ajax Request
if (file_exists(WPFS_CORE_INC . 'wpfs-ajax-request.php')) {
    require_once WPFS_CORE_INC . 'wpfs-ajax-request.php';
}
// Load the Shortcodes
if (file_exists(WPFS_CORE_INC . 'wpfs-shortcodes.php')) {
    require_once WPFS_CORE_INC . 'wpfs-shortcodes.php';
}
// Load the Carbonfield
if (file_exists(WPFS_CORE_INC . '/vendor/autoload.php')) {
    require_once WPFS_CORE_INC . '/vendor/autoload.php';
}
// Load the Settings
if (file_exists(WPFS_CORE_INC . 'wpfs-setting-function.php')) {
    require_once WPFS_CORE_INC . 'wpfs-setting-function.php';
}

function load_single_property_template($template) {
    if (is_singular('property')) {
        // Point to the plugin directory for the custom single template
        $plugin_template = plugin_dir_path(__FILE__) . 'single-property.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_single_property_template');




// Admin CSS


