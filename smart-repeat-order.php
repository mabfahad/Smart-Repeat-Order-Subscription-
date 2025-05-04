<?php
/**
 * Plugin Name: Smart Repeat Order
 * Description: Allows customers to repeat previous orders or set them to auto-repeat.
 * Version: 1.0
 * Author: Md Abdullah Al Fahad
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-sro-repeat-order.php';

function sro_init() {
    new SRO_Repeat_Order();
}
add_action('plugins_loaded', 'sro_init');
