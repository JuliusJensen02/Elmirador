<?php

use inc\kiosk\classes\ProductOrder;
add_action('wp_ajax_get_products', 'get_products');
add_action('wp_ajax_nopriv_get_products', 'get_products');
function get_products() :void{
    $products = array();
    $productsData = json_decode(stripslashes(sanitize_text_field($_POST['products'])), true);
    foreach ($productsData as $productData){
        if(!isset($productData["quantity"])){
            $productData["quantity"] = 0;
        }
        $product = new ProductOrder($productData["product"], $productData["quantity"]);
        $products[] = array(
            "id" => $product->getId(),
            "name" => $product->getName(),
            "price" => $product->getPrice(),
            "quantity" => $product->getQuantity(),
            "total" => $product->getTotal(),
            "totalNoVat" => $product->getPriceWithoutTax(),
            "img" => $product->getImageUrl(),
            "unit" => $product->getUnit()
        );
    }
    wp_send_json_success($products);
    wp_die();
}