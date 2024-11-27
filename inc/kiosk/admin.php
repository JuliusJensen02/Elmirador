<?php

add_action('admin_menu', 'manglendeBetalingerMenu');

use inc\kiosk\classes\Order;
use inc\kiosk\classes\User;

function manglendeBetalingerMenu() {
    add_menu_page(
        'Fakturering',     // page title
        'Fakturering',     // menu title
        'manage_options',   // capability
        'fakturering',     // menu slug
        'FaktureringsSide' // callback function
    );
}
function FaktureringsSide() {
    global $title;
    echo "<h1>$title</h1>";

    global $wpdb;
    $table = $wpdb->prefix . "jet_cct_ordre";
    $results = $wpdb->get_results("SELECT * FROM $table WHERE status = 'created'");
    $users = array();
    foreach ($results as $result) {
        $userID = $result->user;
        $order = new Order($result->_ID);
        if(array_key_exists($userID, $users)){
            $users[$userID]["total"] += $order->getTotal();
            $users[$userID]["orders"][] = $order;
            $users[$userID]["orderIDs"][] = intval($result->_ID);
        }
        else{
            $users[$userID] = array("user" => $userID, "total" => $order->getTotal(), "orders" => array($order), "orderIDs" => array(intval($result->_ID)));
        }
    }

    echo "<table id='manglende-betalinger' class='wp-list-table widefat fixed striped table-view-list posts'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Bruger</th>";
    echo "<th>Products</th>";
    echo "<th>Total</th>";
    echo "<th>Actions</th>";
    echo "</tr>";
    echo "</thead>";
    foreach ($users as $userData) {
        $user = new User($userData["user"]);
        $userLink = get_edit_user_link($userData["user"]);
        echo "<tr>";
        echo "<td><a href='$userLink'>{$user->getFullName()}</a></td>";
        echo "<td>";
        foreach ($userData["orders"] as $order){
            echo "<ul>";
            foreach ($order->getProducts() as $product){
                echo "<li>{$product->getName()} - {$product->getQuantity()} stk. - {$product->getPrice()}€</li>";
            }
            echo "</ul>";
        }
        echo "</td>";
        echo "<td>{$userData["total"]}€</td>";
        $orderIDs = json_encode($userData["orderIDs"]);
        echo "<td>";
        echo "<button class='createInvoice' value='{$orderIDs}'>Opret / se faktura</button>";
        if($userData["orders"][0]->getInvoice() != ""){
            echo "<button class='sendInvoice' value='{$orderIDs}'>Send samlet faktura</button>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}




