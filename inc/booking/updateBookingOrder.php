<?php

use booking\classes\BookingInvoice;
use booking\classes\BookingOrder;

function updateBookingOrder($postid): void {
    if(get_post_status($postid) != 'trash'){
        if(get_post_type($postid) == "booking-orders" && is_admin()){
            //Update prices etc.
            $bookingPrice = get_post_meta($postid, "pris-for-leje", true);
            $extras = get_post_meta($postid, "extra-services", true);
            $manualExtras = get_post_meta($postid, "manual-extras", true);
            $discountCode = get_post_meta($postid, "rabatkode", true);
            $totalExtraPrice = 0;
            $totalManualExtraPrice = 0;

            global $wpdb;
            $table_name = $wpdb->prefix . "jet_cct_booking_rabatkoder";
            $discount = $wpdb->get_row("SELECT * FROM $table_name WHERE (discount_code='$discountCode')", ARRAY_A);

            $dates = explode(" - ", get_post_meta($postid, "ankomst-udtjekning", true));
            $date1 = DateTime::createFromFormat('d.m.Y', $dates[0]);
            $date2 = DateTime::createFromFormat('d.m.Y', $dates[1]);
            $interval = $date1->diff($date2);
            $night = $interval->days;

            foreach($extras as $extra){
                $count = get_post_meta($extra, "dag", true) == "true" ? $night : 1;
                $totalExtraPrice += intval(get_post_meta($extra, "pris", true))*$count;
            }

            foreach($manualExtras as $maextra){
                $totalManualExtraPrice += $maextra["pris"]*$maextra["antal"];
            }

            $subtotal = $bookingPrice+$totalExtraPrice+$totalManualExtraPrice;
            if($discount != null){
                if($discount["type"] == "Pris"){
                    $total = $subtotal-$discount["rabat"];
                }
                else if($discount["type"] == "Procent"){
                    $total = round($subtotal-($subtotal*($discount["rabat"]/100)));
                }
            }
            else{
                $total = $subtotal;
            }

            update_post_meta($postid, "pris-extra-services", $totalExtraPrice);
            update_post_meta($postid, "subtotal", $subtotal);
            update_post_meta($postid, "pris-i-alt", $total);
            $bookingOrder = new BookingOrder($postid);
            new BookingInvoice($bookingOrder);

            //Update booking status
            $status = get_post_meta($postid, "status", true);
            if($status == "Reservation Confirmed" || $status == "Reservation Deposit Payed" || $status == "Reservation Payed" || $status == "Owner Confirmed"){
                global $wpdb;
                $table = 'clkd2380b86dc_jet_apartment_bookings';
                $data = array('status'=>"completed");
                $where = array('order_id'=>$postid);
                $wpdb->update( $table, $data, $where);
            }
            else if($status == "Reservation Cancelled"){
                global $wpdb;
                $table = 'clkd2380b86dc_jet_apartment_bookings';
                $data = array('status'=>"cancelled");
                $where = array('order_id'=>$postid);
                $wpdb->update( $table, $data, $where);
            }
        }
    }
}

add_action('save_post', 'updateBookingOrder', 20, 2);