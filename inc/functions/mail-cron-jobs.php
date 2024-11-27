<?php

use classes\BookingMail;
use classes\BookingOrder;

if ( ! wp_next_scheduled( 'hourly_booking_mails' ) ) {
	wp_schedule_event( time(), 'hourly', 'hourly_booking_mails' );
}

add_action( 'hourly_booking_mails', 'hourly_booking_mails' );
function hourly_booking_mails() : void {
	$args = array(
		'post_type' => 'booking-orders',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'status',
				'value' => 'test',
				'compare' => '='
			)
		)
	);
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$bookingOrder = new BookingOrder(get_the_ID());
			if($bookingOrder->getStatus() !== "Owner Confirmed" && $bookingOrder->getStatus() !== "Reservation Created"){
				/*$bookingMail = new BookingMail(19048, $bookingOrder);
				if ($bookingOrder->getStatus() === "Reservation Deposit Payed" && !$bookingMail->isSent()) {
					$bookingMail->sendMail();
				}
				$bookingMailSevenDays = new BookingMail(18938, $bookingOrder);
				if ($bookingOrder->getStatus() === "Reservation Payed" && $bookingOrder->daysUntilStartDate() <= 7 && $bookingOrder->daysUntilStartDate() >= 0 && !$bookingMailSevenDays->isSent()) {
					$bookingMail->sendMail();
				}*/
				if(!$bookingOrder->isStartDateXDaysOrLessFromCreationDate(21)){
					if($bookingOrder->differenceBetweenCreationAndStartDate() <= 63){
						$reservationFullMail = new BookingMail(18968, $bookingOrder);
						if($bookingOrder->getStatus() === "Reservation Confirmed" && !$reservationFullMail->isSent()){
							if($bookingOrder->differenceBetweenCreationAndStartDate() > 42 && $bookingOrder->daysUntilStartDate() === 28){
								$reservationFullMail->sendMail(["ms@elmirador.dk"]);
							}
							else if($bookingOrder->getDaysSinceCreation() === 14){
								$reservationFullMail->sendMail(["ms@elmirador.dk"]);
							}
						}
					}
					else{
						$reservationDepositMail = new BookingMail(18966, $bookingOrder);
						$reservationRestMail = new BookingMail(18967, $bookingOrder);
						if($bookingOrder->getStatus() === "Reservation Confirmed" && $bookingOrder->getDaysSinceCreation() === 21 && !$reservationDepositMail->isSent()){
							$reservationDepositMail->sendMail(["ms@elmirador.dk"]);
						}
						if($bookingOrder->getStatus() === "Reservation Deposit Payed" && !$reservationRestMail->isSent()){
							if($bookingOrder->daysUntilStartDate() <= 28){
								$reservationRestMail->sendMail(["ms@elmirador.dk"]);
							}
						}
					}
				}
			}
		}
	}
}