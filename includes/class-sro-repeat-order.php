<?php


class SRO_Repeat_Order
{
    public function __construct()
    {
        add_filter('woocommerce_my_account_my_orders_actions', [$this, 'add_repeat_order_button'], 10, 2);
        add_action('init', [$this, 'handle_repeat_order']);
    }

    public function add_repeat_order_button($actions, $order)
    {
        $nonce = wp_create_nonce('sro_repeat_order_' . $order->get_id());

        $actions['repeat_order'] = [
            'url' => add_query_arg([
                'sro_repeat_order' => $order->get_id(),
                'sro_nonce' => $nonce,
            ]),
            'name' => __('Repeat Order', 'smart-repeat-order-subscription'),
        ];

        return $actions;
    }


    public function handle_repeat_order()
    {
        if (!isset($_GET['sro_repeat_order'], $_GET['sro_nonce'])) {
            return;
        }

        $order_id = absint($_GET['sro_repeat_order']);
        $nonce = isset($_GET['sro_nonce']) ? sanitize_text_field(wp_unslash($_GET['sro_nonce'])) : '';

        if (!wp_verify_nonce($nonce, 'sro_repeat_order_' . $order_id)) {
            return;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $quantity = $item->get_quantity();
            $product = wc_get_product($product_id);

            if ($product && $product->is_purchasable() && $product->is_in_stock()) {
                WC()->cart->add_to_cart($product_id, $quantity);
            }
        }

        wp_safe_redirect(wc_get_cart_url());
        exit;
    }


}
