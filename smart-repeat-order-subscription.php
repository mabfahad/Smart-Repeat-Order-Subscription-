<?php
/**
 *
 * Plugin Name: Smart Repeat Order for WooCommerce
 * Plugin URI: https://abfahad.me/smart-repeat-order-subscription
 * Description: Smart Repeat Order is a simple WooCommerce extension that allows customers to easily schedule repeat orders for their favorite products. Customers can set up their orders to repeat on a weekly, bi-weekly, or monthly basis, making it easier for them to reorder frequently purchased items. Admins can also manage repeat orders and send email reminders.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://abfahad.me
 * Text Domain: smart-repeat-order-subscription
 * Domain Path: /languages
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Tested up to: 6.8
 * WC requires at least: 7.0
 * WC tested up to: 7.5
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-sro-repeat-order.php';

// Check if WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    add_action( 'admin_notices', function() {
        echo '<div class="notice notice-error"><p><strong>Smart Repeat Order</strong> requires <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a></p></div>';
    } );
    return; // Stop loading the plugin
}

function sro_init() {
    new SRO_Repeat_Order();
}
add_action('plugins_loaded', 'sro_init');
