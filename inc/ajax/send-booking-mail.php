<?php

use classes\BookingMail;
use classes\BookingOrder;

function send_booking_mail() {
    $data = sanitize_text_field($_POST['postID']);
	$booking = new BookingOrder($data);
	$mailID = match($booking->getStatus()){
		"Reservation Created" => 12725,
		"Reservation Confirmed" => 12707,
		"Reservation Cancelled" => 12726,
		"Reservation Deposit Payed" => 12727,
		"Reservation Payed" => 12728,
		"Owner Confirmed" => 13770,
		default => null
	};
	if($mailID === null){
		return;
	}
	$mail = new BookingMail($mailID, $booking);
	if($booking->getStatus() === "Reservation Created"){
		$mail->sendMail(["booking@elmirador.dk", "ms@elmirador.dk"]);
	}
	else if($booking->getStatus() === "Reservation Deposit Payed"){
		$mail->sendMail(["booking@elmirador.dk", "karen@greatlifeproject.co.uk"]);
	}
	else if($booking->getStatus() === "Owner Confirmed"){
		$mail->sendMail(["booking@elmirador.dk", "karen@greatlifeproject.co.uk"], true);
	}
	else if($booking->getStatus() === "Reservation Confirmed"){
		$mail->sendMail(["booking@elmirador.dk", "mr@elmirador.dk", "th@elmirador.dk"], true);
	}
	else{
		$mail->sendMail(["booking@elmirador.dk"]);
	}
    wp_send_json_success("Mail sent");
    wp_die();
}

add_action('wp_ajax_admin_send_booking_mail', 'send_booking_mail');
add_action('wp_ajax_nopriv_admin_send_booking_mail', 'send_booking_mail');