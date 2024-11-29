<?php

use classes\Apartment;
use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function get_bookings_custom(): void {
	//$electricCar =  isset($_POST['electricCar']) && $_POST['electricCar'] === "true";

	$apartments = get_user_meta(get_current_user_id(), 'boliger', true);
	$apartmentsArray = array();
	foreach ($apartments as $apartment) {
		$apartment = new Apartment($apartment);
		$bookings = $apartment->getBookingsSortedByStartDate();
		if (empty($bookings)) {
			continue;
		}
		$bookingsArray = array();
		$total = 0;
		foreach ($bookings as $booking){
			$total += $booking->getBookingPriceForBookingTable();
			$bookingPrice = number_format($booking->getBookingPriceForBookingTable(), 2, ',', '.');
			$ownerShare = number_format($booking->getOwnerShare(), 2, ',', '.');
			if($booking->getStatus() === "Reservation Confirmed"){
				$bookingPrice = "Afventer betaling";
				$ownerShare = "Afventer betaling";
			}
			else{
				$bookingPrice = "€ ".$bookingPrice;
				$ownerShare = "€ ".$ownerShare;
			}
			$bookingsArray[] = array(
				"number" => $booking->getHusID(),
				"dates" => $booking->getDatesString(),
				"status" => $booking->getFormattedStatus(),
				"price" => $bookingPrice,
				"share" => $ownerShare,
				"creation" => $booking->getCreationDate(),
			);
		}
		$totalShare = $total * 0.76;
		$total = number_format($total, 2, ',', '.');
		$totalShare = number_format($totalShare, 2, ',', '.');
		$apartmentsArray[] = array(
			"bookings" => $bookingsArray,
			"total" => $total,
			"totalShare" => $totalShare,
		);
	}
	
    wp_send_json_success($apartmentsArray);
    wp_die();
}

add_action('wp_ajax_get_bookings_custom', 'get_bookings_custom');
add_action('wp_ajax_nopriv_get_bookings_custom', 'get_bookings_custom');