<?php
// Hook into the 'add_meta_boxes' action for the booking-order post type
use classes\BookingMail;
use classes\BookingOrder;

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