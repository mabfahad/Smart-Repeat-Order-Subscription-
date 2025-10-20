<?php

if (!defined('ABSPATH')) exit;

class SRO_Repeat_Order {

    public function __construct() {
        // Add "Repeat Order" button in My Account > Orders
        add_filter('woocommerce_my_account_my_orders_actions', [$this, 'add_repeat_order_button'], 10, 2);

        // Handle repeat order safely
        add_action('template_redirect', [$this, 'handle_repeat_order']);
    }

    /**
     * Add "Repeat Order" button
     */
    public function add_repeat_order_button($actions, $order) {
        // Only show for completed orders (optional)
        if ($order->get_status() !== 'completed') {
            return $actions;
        }

        $nonce = wp_create_nonce('sro_repeat_order_' . $order->get_id());

        $actions['repeat_order'] = [
            'url'  => add_query_arg([
                'sro_repeat_order' => $order->get_id(),
                'sro_nonce'        => $nonce,
            ], wc_get_account_endpoint_url('orders')),
            'name' => __('Repeat Order', 'smart-repeat-order-subscription'),
        ];

        return $actions;
    }

    /**
     * Handle repeat order action safely
     */
    public function handle_repeat_order() {
        if (!isset($_GET['sro_repeat_order'], $_GET['sro_nonce'])) {
            return;
        }

        $order_id = absint($_GET['sro_repeat_order']);
        $nonce    = sanitize_text_field(wp_unslash($_GET['sro_nonce']));

        // Verify nonce for security
        if (!wp_verify_nonce($nonce, 'sro_repeat_order_' . $order_id)) {
            wc_add_notice(__('Invalid request.', 'smart-repeat-order-subscription'), 'error');
            wp_safe_redirect(wc_get_account_endpoint_url('orders'));
            exit;
        }

        // Get order safely (HPOS compatible)
        $order = wc_get_order($order_id);
        if (!$order) {
            wc_add_notice(__('Order not found.', 'smart-repeat-order-subscription'), 'error');
            wp_safe_redirect(wc_get_account_endpoint_url('orders'));
            exit;
        }

        // Add products to cart safely
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $quantity   = $item->get_quantity();
            $product    = wc_get_product($product_id);

            if ($product && $product->is_purchasable() && $product->is_in_stock()) {
                WC()->cart->add_to_cart($product_id, $quantity);
            }
        }

        // Redirect to cart
        wp_safe_redirect(wc_get_cart_url());
        exit;
    }
}
