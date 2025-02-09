<?php
/**
 * Plugin Name: Custom Product Gallery Image
 * Plugin URI: https://wordpress.org/plugins/custom-product-gallery-image/
 * Description: Adds a custom image to WooCommerce product galleries with category and tag filtering.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * Text Domain: custom-product-gallery-image
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin path
define('CPGI_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CPGI_PLUGIN_URL', plugin_dir_url(__FILE__));

// Check for WooCommerce
function cpgi_check_woocommerce_active() {
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        add_action('admin_notices', 'cpgi_woocommerce_missing_notice');
        return false;
    }
    return true;
}

function cpgi_woocommerce_missing_notice() {
    echo '<div class="notice notice-error"><p>' . __('Custom Product Gallery Image requires WooCommerce to be installed and active.', 'custom-product-gallery-image') . '</p></div>';
}

add_action('plugins_loaded', 'cpgi_check_woocommerce_active', 10);



if (cpgi_check_woocommerce_active()) {
    // Include necessary files
    require_once CPGI_PLUGIN_PATH . 'includes/functions.php';

    // Admin settings
    if (is_admin()) {
        require_once CPGI_PLUGIN_PATH . 'admin/settings.php';
    }
}
