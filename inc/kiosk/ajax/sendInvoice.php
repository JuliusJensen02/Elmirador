<?php

use inc\kiosk\classes\KioskInvoice;
use inc\kiosk\classes\Order;
add_action('wp_ajax_send_invoice', 'sendInvoice');
function sendInvoice() :void{
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
        $order->setStatus("invoice_sent");
    }
    ob_start();
    ?>
    <p>Faktura for ordre #<?= $invoice->getInvoiceID() ?> på El Mirador</p>
    <p>Hej <?= $orders[0]->getUser()->getFirstname() ?></p>
    <p>Hermed faktura for dine bestillinger på El Mirador Spa & Resort. Se vedhæftet faktura.</p>
    <p>Du bedes betale hele beløbet indenfor 8 dage fra dags dato.</p>
    <br>
    <p>-----English version below-----</p>
    <br>
    <p>Invoice for ordre #<?= $invoice->getInvoiceID() ?> at El Mirador</p>
    <p>Hello <?= $orders[0]->getUser()->getFirstname() ?></p>
    <p>Here is the invoice for your orders at El Mirador Spa & Resort. Please see the attached invoice.</p>
    <p>You are requested to pay the full amount within 8 days from today's date.</p>
    <p>Med venlig hilsen / Best Regards,</p>
    <p>El Mirador Spa & Resort</p>
    <?php
    $message = ob_get_clean();
    wp_send_json_success($invoice->send("booking@elmirador.dk", "Elmirador faktura / Elmirador invoice", $message, $orders[0]->getUser()->getEmail()));
    die();
}