<?php
add_action( 'jet-form-builder/custom-action/send-booking-mail', function( $request) {
	update_post_meta($request["inserted_post_id"], "status", "Reservation Created");
	$booking = new BookingOrder(intval($request["inserted_post_id"]));
	$mail = new BookingMail(12725, $booking);
	$mail->sendMail(["booking@elmirador.dk"]);
}, 10, 2 );