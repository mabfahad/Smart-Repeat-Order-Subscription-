<?php
/**
 *
 * Plugin Name: Smart Repeat Order for WooCommerce
 * Plugin URI: https://example.com/smart-repeat-order
 * Description: Smart Repeat Order is a simple WooCommerce extension that allows customers to easily schedule repeat orders for their favorite products. Customers can set up their orders to repeat on a weekly, bi-weekly, or monthly basis, making it easier for them to reorder frequently purchased items. Admins can also manage repeat orders and send email reminders.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: smart-repeat-order
 * Domain Path: /languages
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Tested up to: 6.2
 * WC tested up to: 7.5
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-sro-repeat-order.php';

function sro_init() {
    new SRO_Repeat_Order();
}
add_action('plugins_loaded', 'sro_init');
