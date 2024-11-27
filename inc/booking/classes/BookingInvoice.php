<?php

namespace booking\classes;
require_once INC_DIR.'/invoice/Invoice.php';
use inc\invoice\Invoice;

class BookingInvoice extends Invoice {
    private BookingOrder $booking;
    public function __construct(BookingOrder $booking){
        $this->invoiceID = $booking->getID();
        $this->booking = $booking;
        $this->path = "/booking-faktura";
        if($booking->getStatus() == "Owner Confirmed"){
            $this->html = file_get_contents(INC_DIR."/invoice/templates/fakturaSkabelonOwner.php");
            $this->bookingInvoice();
        }
        else{
            $this->html = file_get_contents(INC_DIR."/invoice/templates/fakturaSkabelon.php");
            $this->regularInvoice();
        }

        $this->generateInvoice();
        update_post_meta($booking->getID(), "invoice", $this->url);
    }

    private function regularInvoice(): void {
		$discount = 0;
		$discountText = "";
        foreach($this->booking->getAdvPrices() as $advPrice){
            $this->insertNewBookingLine($advPrice['total']);
			if($this->booking->getNights() >= 7 && $this->booking->getNights() < 14){
				$discount = $advPrice['total']*0.2;
				$discountText = __("Rabat (-20%)", "Hefa_theme");
			}
			else if($this->booking->getNights() >= 14){
				$discount = $advPrice['total']*0.25;
				$discountText = __("Rabat (-25%)", "Hefa_theme");
			}
        }
	    $this->insertNewBulkDiscountLine(1, $discountText, round($discount));
        $this->bookingInvoice();
    }

    private function bookingInvoice(): void {
        foreach($this->booking->getExtras() as $extra){
            $count = get_post_meta($extra, "dag", true) == "true" ? $this->booking->getNights() : 1;
            $name = get_the_title($extra);
            $price = get_post_meta($extra, "pris", true);
            $this->insertNewLine($count, $name, $price);
        }

        foreach($this->booking->getManualExtras() as $extra){
            $count = $extra["antal"];
            $heading = $extra["overskrift"];
            $price = $extra["pris"];
            $this->insertNewLine($count, $heading, $price);
        }

        if(!empty($this->booking->getDiscount()) && isset($this->booking->getDiscountCode["rabat"])){
            $this->insertNewDiscountLine();
        }

        $this->replaceShortcode("[totalPriceNoTax]", number_format($this->booking->getTotal(),2,".",","));
        $this->replaceShortcode("[taxPrice]", number_format($this->booking->getTotal()-($this->booking->getTotal()/1.21),2,".",","));
        $this->replaceShortcode("[total]", number_format($this->booking->getTotal(),2,".",","));
        $this->replaceShortcode("[priceRowRepeater]", $this->lines);
        $this->replaceShortcode("[reservationID]", $this->booking->getID());
        $this->replaceShortcode("[invoiceDate]", get_the_date("d.m.Y", $this->booking->getID()));
        $this->replaceShortcode("[lastPaymentDate]", $this->booking->getLastPaymentDate());
        $this->replaceShortcode("[totalPriceReservationFee]", number_format($this->booking->getTotal()*0.25,2,".",","));
        $this->replaceShortcode("[totalPriceRemaining]", number_format($this->booking->getTotal()*0.75,2,".",","));
        $this->replaceShortcode("[fName]", get_post_meta($this->booking->getID(), "fornavn", true));
        $this->replaceShortcode("[lName]", get_post_meta($this->booking->getID(), "efternavn", true));
        $this->replaceShortcode("[address]", get_post_meta($this->booking->getID(), "adresse", true));
        $this->replaceShortcode("[postal]", get_post_meta($this->booking->getID(), "postnr", true));
        $this->replaceShortcode("[city]", get_post_meta($this->booking->getID(), "by", true));
        $this->replaceShortcode("[land]", get_post_meta($this->booking->getID(), "land", true));
        $this->replaceShortcode("[landcode]", get_post_meta($this->booking->getID(), "landekode", true));
        $this->replaceShortcode("[phone]", get_post_meta($this->booking->getID(), "telefon", true));
    }

    public function insertNewBookingLine(string $price): void {
        $this->lines .= "<tr>";
        $this->lines .= "<td>1</td>";
        $this->lines .= "<td>".$this->booking->getHusID().", ".$this->booking->getDatesString()."</td>";
        $this->lines .= "<td class='unit-price'></td>";
        $this->lines .= "<td>€ ".number_format($price,2,".",",")."</td>";
        $this->lines .= "</tr>";
    }

    public function insertNewDiscountLine(): string {
        $discountLine = "<tr>";
        $discountLine .= "<td>1</td>";
        $discountLine .= "<td>Discount code: ".$this->booking->getDiscount()."</td>";
        $discountLine .= "<td class='unit-price'></td>";
        if($this->booking->getDiscountCode()["type"] == "Pris"){
            $discountLine .= "<td>€ -".number_format($this->booking->getDiscountCode()["rabat"],2,".",",")."</td>";
        }
        else if($this->booking->getDiscountCode()["type"] == "Procent"){
            $discountLine .= "<td>€ -".number_format(round($this->booking->getTotal()*($this->booking->getDiscountCode()["rabat"]/100)),2,".",",")."</td>";
        }
        $discountLine .= "</tr>";
        return $discountLine;
    }

	public function insertNewBulkDiscountLine(string $count, string $name, string $price): void {
		$this->lines .= "<tr>";
		$this->lines .= "<td>".$count."</td>";
		$this->lines .= "<td>".$name."</td>";
		$this->lines .= "<td></td>";
		$this->lines .= "<td>- € ".number_format(($price*$count),2,".",",")."</td>";
		$this->lines .= "</tr>";
	}

    public function insertNewLine(string $count, string $name, string $price): void {
        $this->lines .= "<tr>";
        $this->lines .= "<td>".$count."</td>";
        $this->lines .= "<td>".$name."</td>";
        $this->lines .= "<td>€ ".number_format($price,2,".",",")."</td>";
        $this->lines .= "<td>€ ".number_format(($price*$count),2,".",",")."</td>";
        $this->lines .= "</tr>";
    }
}