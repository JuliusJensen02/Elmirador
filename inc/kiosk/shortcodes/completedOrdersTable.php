<?php

use inc\kiosk\classes\Order;
use inc\kiosk\classes\User;
add_shortcode('completedKioskOrdersTable', 'completedKioskOrdersTable');
function completedKioskOrdersTable(): void {
    global $title;
    echo "<h1>$title</h1>";

    global $wpdb;
    $table = $wpdb->prefix . "jet_cct_ordre";
    $currentUserID = get_current_user_id();
    $user = new User($currentUserID);
    $resultsCompleted = $wpdb->get_results("SELECT * FROM $table WHERE status = 'paid' AND user = $currentUserID");
    $productsCompleted = array();

    foreach ($resultsCompleted as $result) {
        $order = new Order($result->_ID);
        foreach ($order->getProducts() as $product) {
            $productsCompleted[] = array("product" => $product, "order" => $order);
        }
    }

    echo "<div id='completedOrdersTable'>";
    echo "<div class='headerElm'></div>";
    echo "<div class='headerElm'>Produkt</div>";
    echo "<div class='headerElm'>Antal</div>";
    echo "<div class='headerElm'>Totalpris</div>";
    echo "<div class='headerElm'>Dato bestilt</div>";
    echo "<div class='headerElm'>Navn</div>";
    $odd = false;
    $total = 0;
    foreach ($productsCompleted as $product){
        $odd = !$odd;
        $total += $product["product"]->getTotal();
        if($odd){
            echo "<div class='bodyElm odd'><img src='{$product["product"]->getImageUrl()}'></div>";
            echo "<div class='bodyElm odd productName'>{$product["product"]->getName()}</div>";
            echo "<div class='bodyElm odd qty'>{$product["product"]->getQuantity()}</div>";
            echo "<div class='bodyElm odd total'>€{$product["product"]->getTotal()}</div>";
            echo "<div class='bodyElm odd date'>{$product["order"]->getCreationDate()}</div>";
            echo "<div class='bodyElm odd userName'>{$user->getFullName()}</div>";
        }
        else {
            echo "<div class='bodyElm'><img src='{$product["product"]->getImageUrl()}'></div>";
            echo "<div class='bodyElm productName'>{$product["product"]->getName()}</div>";
            echo "<div class='bodyElm qty'>{$product["product"]->getQuantity()}</div>";
            echo "<div class='bodyElm total'>€{$product["product"]->getTotal()}</div>";
            echo "<div class='bodyElm date'>{$product["order"]->getCreationDate()}</div>";
            echo "<div class='bodyElm userName'>{$user->getFullName()}</div>";
        }
    }
    echo "<div class='footerElm'>Total: €$total (Inkl. moms)</div>";
    echo "</div>";
}