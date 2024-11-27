<?php

use booking\classes\BookingOrder;

/**
 * @param DateTime $time1
 * @param DateTime $time2
 * @return int
 */
function compareByDateTime(DateTime $time1, DateTime $time2): int {
    if ($time1->getTimestamp() > $time2->getTimestamp()) {
        return 1;
    }
    elseif ($time1->getTimestamp() < $time2->getTimestamp()) {
        return -1;
    }
    else {
        return 0;
    }
}

add_shortcode('bookingTable', 'bookingTable');
function bookingTable(): void {
    global $wpdb;
    $apartments = get_user_meta(get_current_user_id(), 'boliger', true);
    $apartmentsArray = array();
    foreach ($apartments as $apartment) {
        $table = $wpdb->prefix . "jet_apartment_bookings";
        $results = $wpdb->get_results("SELECT * FROM $table WHERE apartment_id = $apartment");
        $bookingOrders = array();
        foreach ($results as $result) {
            $bookingOrders[] = array("order" => new BookingOrder($result->order_id), "booking" => $result);
        }
        usort($bookingOrders, function($a, $b) {
            return compareByDateTime($a["order"]->getStartDate(), $b["order"]->getStartDate());
        });
        $apartmentsArray[] = array("apartment" => $apartment, "bookings" => $bookingOrders);
    }
    $multiple = count($apartmentsArray) > 1 ? "multiple" : "";
    foreach ($apartmentsArray as $apartment) {
        if($apartment["bookings"] == null) {
            continue;
        }
        echo "<div class='apartmentTable ".$multiple."'>";
        echo "<div class='headerElm'>Husnr.</div>";
        echo "<div class='headerElm'>Bookingdato fra/til</div>";
        echo "<div class='headerElm'>Status</div>";
        echo "<div class='headerElm'>Beløb</div>";
        echo "<div class='headerElm'>Omsætning</div>";
        echo "<div class='headerElm'>Booking oprettet</div>";
        $odd = false;
        $total = 0;
        $numberOfBookings = count($apartment["bookings"]);
        /**
         * @var $order BookingOrder
         */
        foreach ($apartment["bookings"] as $bookingOrder) {
            $order = $bookingOrder["order"];
            $odd = !$odd;
            $total += $order->getBookingPriceForBookingTable();
            $bookingPrice = number_format($order->getBookingPriceForBookingTable(), 2, ',', '.');
            $ownerShare = number_format($order->getOwnerShare(), 2, ',', '.');
            if($order->getStatus() == "Reservation Confirmed"){
                $bookingPrice = "Afventer betaling";
                $ownerShare = "Afventer betaling";
            }
            else{
                $bookingPrice = "€ ".$bookingPrice;
                $ownerShare = "€ ".$ownerShare;
            }
            if ($odd) {
                echo "<div class='bodyElm odd house'>{$order->getHusID()}</div>";
                echo "<div class='bodyElm odd bookingDate'>{$order->getDatesString()}</div>";
                echo "<div class='bodyElm odd status'>{$order->getFormattedStatus()}</div>";
                echo "<div class='bodyElm odd total'>{$bookingPrice}</div>";
                echo "<div class='bodyElm odd share'>{$ownerShare}</div>";
                echo "<div class='bodyElm odd date'>{$order->getCreationDate()}</div>";
            }
            else {
                echo "<div class='bodyElm house'>{$order->getHusID()}</div>";
                echo "<div class='bodyElm bookingDate'>{$order->getDatesString()}</div>";
                echo "<div class='bodyElm status'>{$order->getFormattedStatus()}</div>";
                echo "<div class='bodyElm total'>{$bookingPrice}</div>";
                echo "<div class='bodyElm share'>{$ownerShare}</div>";
                echo "<div class='bodyElm date'>{$order->getCreationDate()}</div>";
            }
        }
        $totalShare = $total * 0.76;
        $total = number_format($total, 2, ',', '.');
        $totalShare = number_format($totalShare, 2, ',', '.');
        echo "<div class='footerElm'><div>Antal bookings: $numberOfBookings</div><div>Total: € $total</div><div>Total omsætning: € $totalShare</div></div>";
        echo "</div>";
    }
}