<?php

use inc\kiosk\classes\KioskInvoice;
use inc\kiosk\classes\Order;
add_action('wp_ajax_create_invoice', 'createInvoice');
function createInvoice() :void{
    $orderIDs = json_decode(stripslashes($_POST["order_ids"]), true);
    /**
     * @var Order[] $orders
     */
    $orders = array();
    foreach ($orderIDs as $orderID) {
        $order = new Order($orderID);
        $orders[] = $order;
    }

    $invoice = new KioskInvoice($orders);
    foreach ($orders as $order) {
        $order->setInvoice($invoice->getURL());
    }

    wp_send_json_success($invoice->getURL());
    die();
}