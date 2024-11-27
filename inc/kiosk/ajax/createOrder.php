<?php

use inc\kiosk\classes\ProductOrder;
use inc\kiosk\classes\User;
add_action('wp_ajax_create_order', 'create_order');
add_action('wp_ajax_nopriv_create_order', 'create_order');
function create_order() :void{
    require_once get_stylesheet_directory().'/kiosk/classes/User.php';
    require_once get_stylesheet_directory().'/kiosk/classes/Product.php';
    require_once get_stylesheet_directory().'/kiosk/classes/ProductOrder.php';
    require_once get_stylesheet_directory().'/kiosk/classes/Order.php';
    global $wpdb;
    $table = $wpdb->prefix."jet_cct_ordre";
    $user = new User(wp_get_current_user()->ID);
    $productsData = json_decode(stripslashes(sanitize_text_field($_POST['order_products'])), true);
    $fastDelivery = json_decode(stripslashes(sanitize_text_field($_POST['delivery'])), true);
    $location = json_decode(stripslashes(sanitize_text_field($_POST['location'])), true);
    $note = json_decode(stripslashes(sanitize_text_field($_POST['note'])), true);

    /*DateTime start*/
    $dateTime = new DateTime("now", new DateTimeZone("Europe/Madrid"));

    if(!$fastDelivery) {
        $now = new DateTime("now", new DateTimeZone("Europe/Madrid"));
        try {
            $dateTime = new DateTime(json_decode(stripslashes(sanitize_text_field($_POST['dateTime'])), true), new DateTimeZone("Europe/Madrid"));
        } catch (Exception $e) {
            wp_send_json_error("Fejl i tidsformatet for leveringstidspunkt. Genindlæs siden og prøv igen.");
            wp_die();
        }
        $timeDiff = $now->diff($dateTime);
        if($timeDiff->invert == 1 || ($timeDiff->days == 0 && $timeDiff->h == 0 && $timeDiff->i < 30)){
            wp_send_json_error("Du skal vælge et tidspunkt mindst 30 minutter fra nu. Vælg et nyt tidspunkt og prøv igen.");
            wp_die();
        }
    }
    /*DateTime end*/

    $products = array();
    foreach($productsData as $product) {
        $product_order = new ProductOrder($product["product"], $product["quantity"]);
        $products[$product["product"]] = $product_order;
        if(isset($product["price"])){
            $product_order->setPrice($product["price"]);
        }
    }

    $productsForDB = array();
    foreach ($products as $product) {
        $productsForDB[] = array(
            'product' => $product->getId(),
            'quantity' => $product->getQuantity(),
            'price' => $product->getPrice()
        );
    }
    $productsForDB = serialize($productsForDB);

    $data = array(
        'cct_status' => 'publish',
        'status' => "created",
        'user' => $user->ID,
        'products' => $productsForDB,
        'fastdelivery' => $fastDelivery,
        'dateTime' => $dateTime->format("Y-m-d H:i"),
        'comment' => $note,
        'location' => $location
    );

    $result = $wpdb->insert($table, $data);
    if(!$result){
        wp_send_json_error($wpdb->last_error);
        wp_die();
    }
    wp_send_json_success($wpdb->insert_id);
    wp_die();
}