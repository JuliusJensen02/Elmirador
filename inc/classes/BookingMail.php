<?php

namespace classes;

use DateTime;
use WP_User;

class BookingMail {
	private int|null $ID;
	private int $mailID;
	private BookingOrder $booking;
	private bool $status;
	private DateTime|null $dateSent;

	private string $subject;
	private string $message;
	private string $title;

	public function __construct(int $mailID, BookingOrder $booking) {
		$this->booking = $booking;
		$this->mailID = $mailID;
		$this->title = get_the_title($mailID);

		global $wpdb;
		$table = $wpdb->prefix.'booking_mail_status';
		$bookingID = $this->booking->getID();
		$sql = "SELECT * FROM $table WHERE bookingID = $bookingID AND mailID = $mailID";
		$result = $wpdb->get_results($sql);
		if (count($result) > 0) {
			$this->status = true;
			$this->ID = $result[0]->ID;
			$this->dateSent = new DateTime($result[0]->dateSent);
		}
		else {
			$this->ID = null;
			$this->status = false;
			$this->dateSent = null;
		}
	}

	public function daysSinceSent(): int {
		if($this->dateSent === null) {
			return -1;
		}
		$now = new DateTime();
		$interval = $now->diff($this->dateSent);
		return $interval->days;
	}

	public function getDateSent(): DateTime|null {
		return $this->dateSent;
	}

	public function isSent():bool {
		return $this->status;
	}

	public function getTitle(): string {
		return $this->title;
	}

	private function insertMail(): void {
		global $wpdb;
		$table = $wpdb->prefix.'booking_mail_status';
		$bookingID = $this->booking->getID();
		$sql = "INSERT INTO $table (mailID, bookingID, status, dateSent) VALUES ($this->mailID, $bookingID, 1, NOW())";
		$wpdb->query($sql);
	}

	private function updateMail(): void {
		global $wpdb;
		$table = $wpdb->prefix.'booking_mail_status';
		$sql = "UPDATE $table SET status = 1, dateSent = NOW() WHERE ID = $this->ID";
		$wpdb->query($sql);
	}

	public function sendMail(array $extraMails=[], bool $attachments=false): void {
		if($this->ID === null) {
			$this->insertMail();
		}
		else {
			$this->updateMail();
		}
		$user = new WP_User($this->booking->getUserID());
		$fromEmail = "booking@elmirador.dk";
		apply_filters("wp_mail_from", $fromEmail);
		add_filter('wp_mail_content_type', function( $content_type ) {
			return 'text/html';
		});
		$to = $this->booking->getEmail();
		$headers = "From: ".$fromEmail."  \r\n
				Reply-to: ".$fromEmail."  \r\n";
		$this->subject = get_post_meta($this->mailID, "email-emne", true);
		$this->message = get_post_meta($this->mailID, "email-indhold", true);

		$this->replaceShortCodes($user);

		if($attachments){
			$attachments = array(ABSPATH."wp-content/uploads".explode("/uploads", get_post_meta($this->booking->getID(), "invoice", true))[1], ABSPATH."wp-content/uploads/Elmirador Handelsbetingelser.pdf");
		}
		else{
			$attachments = [];
		}
		wp_mail($to, $this->subject, $this->message, $headers, $attachments);
		if(count($extraMails) > 0){
			foreach($extraMails as $mail){
				wp_mail($mail, $this->subject, $this->message, $headers, $attachments);
			}
		}
	}

	public function replaceShortCodes(WP_User $user): void{
		$this->replaceShortcode("[name]", $this->booking->getFirstName());
		$this->replaceShortcode("[surname]", $this->booking->getLastName());
		$this->replaceShortcode("[reservationID]", $this->booking->getID());
		$this->replaceShortcode("[house]", $this->booking->getHusID());
		$this->replaceShortcode("[checkin]", $this->booking->getDates()[0]);
		$this->replaceShortcode("[checkout]", $this->booking->getDates()[1]);
		$this->replaceShortcode("[nights]", $this->booking->getNights());
		$this->replaceShortcode("[guests]", $this->booking->getGuests());
		$this->replaceShortcode("[fullPrice]", $this->booking->getTotalPrice());
		$this->replaceShortcode("[depositPrice]", $this->booking->getDepositPrice());
		$this->replaceShortcode("[restPrice]", $this->booking->getRestPrice());

		$this->replaceShortcode("[servicesDA]", $this->booking->getExtrasForMail());
		$this->replaceShortcode("[servicesEN]", $this->booking->getExtrasForMail("EN"));

		$this->replaceShortcode("[commentsDA]", $this->booking->getCommentsForMail());
		$this->replaceShortcode("[commentsEN]", $this->booking->getCommentsForMail("EN"));

		$this->replaceShortcode("[discountCodeDA]", $this->booking->getDiscountForMail());
		$this->replaceShortcode("[discountCodeEN]", $this->booking->getDiscountForMail("EN"));

		$this->replaceShortcode("[otherExpensesDA]", $this->booking->getManualExtrasForMail());
		$this->replaceShortcode("[otherExpensesEN]", $this->booking->getManualExtrasForMail("EN"));

		$this->replaceShortcode("[contactInfoDA]", $this->booking->getContactInfoDA());
		$this->replaceShortcode("[contactInfoEN]", $this->booking->getContactInfoEN());

		$this->replaceShortcode("[expectedArrival]", $this->booking->getExpectedArrival());
	}

	public function replaceShortcode(string $shortcode, mixed $value): void {
		$this->message = str_replace($shortcode, $value, $this->message);
		$this->subject = str_replace($shortcode, $value, $this->subject);
	}
}