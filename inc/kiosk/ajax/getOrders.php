<?php

use inc\kiosk\classes\Order;
add_action('wp_ajax_get_orders', 'get_orders');
add_action('wp_ajax_nopriv_get_orders', 'get_orders');
function get_orders() :void{
    $products = array();
    $orderIDs = json_decode(stripslashes(sanitize_text_field($_POST['orderIDs'])), true);
    foreach ($orderIDs as $orderID){
        $orderID = intval($orderID);
        $order = new Order($orderID);
        foreach ($order->getProducts() as $product){
            $products[] = array(
                "id" => $product->getId(),
                "name" => $product->getName(),
                "price" => $product->getPrice(),
                "quantity" => $product->getQuantity(),
                "total" => $product->getPrice() * $product->getQuantity()
            );
        }

    }
    wp_send_json_success($products);
    wp_die();
}
