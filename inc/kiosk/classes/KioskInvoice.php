<?php

namespace inc\kiosk\classes;
require_once INC_DIR.'/invoice/Invoice.php';
use inc\invoice\Invoice;

class KioskInvoice extends Invoice{
    /**
     * @param Order[] $orders
     */
    public function __construct(array $orders){
        $this->html = file_get_contents(INC_DIR."/invoice/templates/fakturaSkabelonKiosk.php");
        $this->path = "/kiosk-faktura";
        $this->kioskInvoice($orders);
        $this->generateInvoice();
        update_post_meta($this->invoiceID, "invoice", $this->url);
    }

    /**
     * @param Order[] $orders
     */
    private function kioskInvoice(array $orders): void {
        $user = $orders[0]->getUser();

        $today = date("d.m.Y");
        $lastPaymentDate = date('d.m.Y', strtotime($today. ' + 8 days'));
        $subtotal = 0;
        $highestOrderID = 0;

        foreach ($orders as $order) {
            $subtotal += $order->getTotal();
            foreach ($order->getProducts() as $product) {
                $this->insertNewLine($product->getQuantity(), $product->getName(), $product->getPrice());
            }
            if($order->getID() > $highestOrderID){
                $highestOrderID = $order->getID();
            }
        }

        $this->invoiceID = "kioskID".$highestOrderID;
        $total = $subtotal*1.21;
        $tax = $total-$subtotal;
        $subtotal = number_format($subtotal,2,".",",");
        $total = number_format($total,2,".",",");
        $tax = number_format($tax,2,".",",");
        $shortcodesPDF = 		[
            "[priceRowRepeater]", "[invoiceID]", "[invoiceDate]", "[lastPaymentDate]",
            "[totalPriceNoTax]", "[taxPrice]", "[totalPriceWithTax]",
            "[fName]", "[lName]", "[address]", "[postal]", "[city]", "[land]", "[landcode]", "[phone]"];
        $shortcodeValuesPDF = 	[
            $this->lines, $this->invoiceID, $today, $lastPaymentDate,
            $subtotal, $tax, $total,
            $user->first_name, $user->last_name,
            get_user_meta($user->ID, "address", true), get_user_meta($user->ID, "postal", true),
            get_user_meta($user->ID, "city", true), get_user_meta($user->ID, "land", true),
            get_user_meta($user->ID, "landcode", true), get_user_meta($user->ID, "phone", true)
        ];

        for($i=0; $i < count($shortcodesPDF); $i++){
            $this->html = str_replace($shortcodesPDF[$i], $shortcodeValuesPDF[$i], $this->html);
        }
    }
}