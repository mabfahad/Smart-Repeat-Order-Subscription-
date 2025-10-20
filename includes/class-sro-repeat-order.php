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
        $actions['repeat_order'] = [
            'url' => add_query_arg([
                'sro_repeat_order' => $order->get_id()
            ]),
            'name' => __('Repeat Order', 'smart-repeat-order-subscription'),
        ];
        return $actions;
    }

    public function handle_repeat_order()
    {
        if (!isset($_GET['sro_repeat_order'])) {
            return;
        }

        $order_id = absint($_GET['sro_repeat_order']);
        $order = wc_get_order($order_id);

        if (!$order) {
            return;
        }

        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $quantity = $item->get_quantity();
            $product = wc_get_product($product_id);

            // Check if product exists and is purchasable
            if ($product && $product->is_purchasable() && $product->is_in_stock()) {
                WC()->cart->add_to_cart($product_id, $quantity);
            }
        }

        wp_safe_redirect(wc_get_cart_url());
        exit;
    }

}
