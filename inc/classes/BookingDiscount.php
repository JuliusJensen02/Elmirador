<?php

namespace classes;

class BookingDiscount {
	protected string $code;

	protected int $ID;
	protected string $type;
	protected string $discount;
	protected string $description;

	public function __construct(string $code){
		global $wpdb;
		$this->code = $code;

		$table = $wpdb->prefix.'booking_rabatkoder';
		$sql = "SELECT * FROM $table WHERE LOWER(discount_code) LIKE '%$code%'";
		$result = $wpdb->get_row($sql, ARRAY_A);
		if (count($result) > 0) {
			$this->ID = $result['_ID'];
			$this->type = $result['type'];
			$this->discount = $result['discount'];
			$this->description = $result['description'];
		}
	}

	public function getID(): int {
		return $this->ID;
	}

	public function getType(): string {
		return $this->type;
	}

	public function getDiscount(): string {
		return $this->discount;
	}

	public function getDescription(): string {
		return $this->description;
	}

	public function getCode(): string {
		return $this->code;
	}

	public function getDiscountForBookingMail(): string {
		return $this->getDescription();
	}
}