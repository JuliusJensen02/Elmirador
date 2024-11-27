<?php
/*Delete booking when post is deleted*/
function deleteBooking($postid): void {
    global $wpdb;
    $wpdb->delete("jet_apartment_bookings", array( 'order_id' => $postid ));
}
add_action('before_delete_post', 'deleteBooking', 20, 2);