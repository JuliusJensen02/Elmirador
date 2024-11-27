<?php

use classes\BookingMail;
use classes\BookingOrder;

define('INC_DIR', get_stylesheet_directory() . '/inc');
require_once INC_DIR . '/user/user.php';
//require_once INC_DIR . '/booking/booking.php';
//require_once INC_DIR . '/admin/admin-loader.php';
//require_once INC_DIR . '/apartments/apartments.php';
//require_once INC_DIR . '/shortcodes/season-popup.php';

/*
 * Load classes
 */

require_once INC_DIR . '/classes/Invoice.php';
require_once INC_DIR . '/classes/Apartment.php';
require_once INC_DIR . '/classes/BookingDiscount.php';
require_once INC_DIR . '/classes/BookingInvoice.php';
require_once INC_DIR . '/classes/BookingMail.php';
require_once INC_DIR . '/classes/BookingOrder.php';


/*
 * Load shortcodes
 */

require_once INC_DIR . '/shortcodes/apartment-archive.php';
require_once INC_DIR . '/shortcodes/bolig-arkiv-advanced-price.php';
require_once INC_DIR . '/shortcodes/booking-table.php';
require_once INC_DIR . '/shortcodes/filters-link.php';
require_once INC_DIR . '/shortcodes/icon-list.php';
require_once INC_DIR . '/shortcodes/season-popup.php';
require_once INC_DIR . '/shortcodes/thank-you-booking.php';


/*
 * Load ajax
 */

require_once INC_DIR . '/ajax/get-apartments.php';
require_once INC_DIR . '/ajax/send-booking-mail.php';

/*
 * Load functions
 */
require_once INC_DIR . '/functions/delete-booking-order.php';
require_once INC_DIR . '/functions/send-booking-mail.php';
require_once INC_DIR . '/functions/update-booking-order.php';


/**
 * Admin scripts and styles
 */
add_action('admin_enqueue_scripts', function() {
	/* Booking admin scripts */
	if (get_current_screen()->post_type === 'booking-orders') {
		wp_enqueue_script('custom-save-button', get_stylesheet_directory_uri() . '/assets/booking/js/adminPOSTSaveButton.js', array('jquery'), null, true);
	}

	$user = wp_get_current_user();
	if(isset($_GET["post"])) {
		$postType = get_post_type($_GET["post"]);
		if ($user && in_array("booking_manager", $user->roles) && $postType == "booking-orders") {
			wp_enqueue_style('booking_orders_edit_style', get_stylesheet_directory_uri() . '/assets/css/booking-orders.css');
		}
		if(get_post_type($_GET["post"]) == "booking-orders") {
			wp_enqueue_script('booking_orders_edit_script', get_stylesheet_directory_uri() . '/assets/js/booking-order.js', array('jquery'), null, true);
		}
	}
	if($user && in_array("booking_manager", $user->roles)) {
		wp_enqueue_style('admin_general_style', get_stylesheet_directory_uri() . '/assets/css/admin.css');
	}
});


/**
 * Scripts and styles
 */
add_action('wp_enqueue_scripts', function(){
	/* Booking styles */
	if (is_page(17346)) {
		wp_enqueue_style('bookingTableStyle', get_stylesheet_directory_uri() . '/assets/booking/css/bookingTable.css');
	}

	/* Apartment styles */
	if(is_archive() && get_post_type() === "boliger"){
		wp_enqueue_script('apartmentsArchiveScript', get_stylesheet_directory_uri() . '/assets/apartments/js/archive.js', array('jquery'), null, true);
		wp_localize_script('apartmentsArchiveScript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php'), "lang" => $_GET['lang'] ?? 'da'));
		wp_enqueue_style('apartmentArchiveStyle', get_stylesheet_directory_uri() . '/assets/apartments/css/archive.css');
	}
	else{
		wp_enqueue_script('apartmentsFilterScript', get_stylesheet_directory_uri() . '/assets/apartments/js/filtersLink.js', array('jquery'), null, true);
		wp_localize_script('apartmentsFilterScript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php'), "lang" => $_GET['lang'] ?? 'da'));
		wp_enqueue_style('apartmentFilterStyle', get_stylesheet_directory_uri() . '/assets/apartments/css/filtersLink.css');
	}
});

/**
 * Hourly cron job to send mails
 */
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

// Hook into the 'add_meta_boxes' action for the booking-order post type
add_action('add_meta_boxes', 'add_custom_table_to_booking_orders');

// Function to add custom table
function add_custom_table_to_booking_orders() {
	// Only add this table for the post type 'booking-order'
	add_action('admin_footer', 'display_custom_table_for_booking_orders');
}

// Function to display the custom table
function display_custom_table_for_booking_orders() {
	global $post;

	// Check if we're on the 'booking-orders' post type edit screen
	if ($post->post_type == 'booking-orders') {
		global $wpdb;
		$table = $wpdb->prefix.'booking_mail_status';
		$bookingID = $post->ID;
		$sql = "SELECT * FROM $table WHERE bookingID = $bookingID";
		$results = $wpdb->get_results($sql);
		$mails = [];
		foreach ($results as $result) {
			$mail = new BookingMail($result->mailID, new BookingOrder($result->bookingID));
			$mails[] = array(
				'mailID' => $result->mailID,
				'dateSent' => $mail->getDateSent()->format('d-m-Y H:i:s'),
				'title' => $mail->getTitle()
			);
		}
		?>
		<script type="text/javascript">
            let poststuff = document.querySelector(".metabox-location-normal #poststuff");
            let mails = JSON.parse('<?php echo json_encode($mails); ?>');
            let table = document.createElement('table');
            table.classList.add('wp-list-table', 'widefat', 'fixed', 'striped');
            let thead = document.createElement('thead');
            let tbody = document.createElement('tbody');
            let tr = document.createElement('tr');
            let th1 = document.createElement('th');
            th1.innerHTML = 'Mail ID';
            let th2 = document.createElement('th');
            th2.innerHTML = 'Titel';
            let th3 = document.createElement('th');
            th3.innerHTML = 'Dato sendt';
            tr.appendChild(th1);
            tr.appendChild(th2);
            tr.appendChild(th3);
            thead.appendChild(tr);
            table.appendChild(thead);
            table.appendChild(tbody);

            mails.forEach(mail => {
                let row = document.createElement('tr');
                let mailID = document.createElement('td');
                mailID.innerHTML = mail.mailID;
                let dateSent = document.createElement('td');
                dateSent.innerHTML = mail.dateSent;
                let title = document.createElement('td');
                title.innerHTML = mail.title;
                row.appendChild(mailID);
                row.appendChild(title);
                row.appendChild(dateSent);
                tbody.appendChild(row);
            });

            poststuff.appendChild(table);
		</script>
		<?php
	}
}

