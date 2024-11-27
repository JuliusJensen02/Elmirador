<?php

namespace booking\classes;

use DateTime;
use DateTimeImmutable;
use booking\classes\BookingDiscount;

class BookingOrder {
    protected int $id;
    protected string $husID;
    protected string $status;
    protected float $bookingPrice;
    protected float $total;
    protected array $extras;
    protected array $manualExtras;
    protected array $dates;
    protected string $advPrices;
    protected string|null $discount;
    protected array|null $discountCode;
	protected int|null $userID;
	protected string|null $comments;
	protected int|null $guests;
	protected DateTimeImmutable $creationDate;
	protected string $email;
	protected string $firstName;
	protected string $lastName;
	protected BookingDiscount|null $discountClass;
	protected string|null $expectedArrival;

    public function __construct(int $id){
        global $wpdb;
        $this->id = $id;
        $this->husID = get_post_meta($id, "hus-id", true);
        $this->status = get_post_meta($id, "status", true);
        $this->bookingPrice = get_post_meta($id, "pris-for-leje", true);
        $this->total = get_post_meta($id, "pris-i-alt", true);
        $extras = get_post_meta($id, "extra-services", true);
        $this->extras = (is_array($extras)) ? $extras : array();
        $mextras = get_post_meta($id, "manual-extras", true);
        $this->manualExtras = (is_array($mextras)) ? $mextras : array();
        $this->dates = explode(" - ", get_post_meta($id, "ankomst-udtjekning", true));
		$this->comments = get_post_meta($id, "kommentar", true);
		$this->guests = intval(get_post_meta($id, "antal-voksne", true)) + intval(get_post_meta($id, "antal-boern", true));
		$this->creationDate = get_post_datetime($id);
		$this->email = get_post_meta($id, "email", true);
		$this->firstName = get_post_meta($id, "fornavn", true);
		$this->lastName = get_post_meta($id, "efternavn", true);

        //Get advanced prices
        $this->advPrices = get_post_meta($id, "adv-price-table", true);
        $this->advPrices = strstr($this->advPrices, '[');
        $this->advPrices = substr($this->advPrices, 0, strpos($this->advPrices, "]")+1);

        //Get discount
        $this->discount = get_post_meta($id, "rabatkode", true);
        $table_name = $wpdb->prefix . "jet_cct_booking_rabatkoder";
        $this->discountCode = $wpdb->get_row("SELECT * FROM $table_name WHERE (discount_code='$this->discount')", ARRAY_A);

		//User
	    $this->userID = intval(get_post_meta($id, "bruger", true));

	    $this->expectedArrival = get_post_meta($id, "expected_arrival", true);
    }

    public function getDiscount(): string {
        return $this->discount;
    }
    public function getDiscountCode(): array|null {
        return $this->discountCode;
    }
    public function getHusID(): string {
        return $this->husID;
    }
    public function getAdvPrices(): array {
        return json_decode($this->advPrices, true);
    }
    public function getID(): int {
        return $this->id;
    }
    public function getStatus(): string {
        return $this->status;
    }
    public function getBookingPrice(): float {
        return $this->bookingPrice;
    }
    public function getTotalPrice(): float {
        return $this->total;
    }
    public function getExtras(): array {
        return $this->extras;
    }
    public function getManualExtras(): array {
        return $this->manualExtras;
    }
    public function getDates(): array{
        return $this->dates;
    }
	public function getUserID(): int|null {
		return $this->userID;
	}

    public function getDatesString(): string {
        return $this->dates[0]." - ".$this->dates[1];
    }

	public function getDepositPrice(): float {
		return $this->total / 4;
	}

	public function getRestPrice(): float {
		return $this->total/4*3;
	}

	public function getCreationDatetime(): DateTimeImmutable {
		return $this->creationDate;
	}

	public function getFirstName(): string {
		return $this->firstName;
	}

	public function getLastName(): string{
		return $this->lastName;
	}

	public function getExtrasForMail($lang="DA"): string {
		$extras = $this->extras;
		$extraServices = "";
		if(count($extras) > 0){
			$extraServices = ($lang === "DA") ? "<strong>Ekstra services:</strong><br>" : "<strong>Extra services:</strong><br>";
			foreach($extras as $extra){
				$extraServices .= get_the_title($extra)."<br>";
			}
		}
		return $extraServices;
	}

    public function getNights(): bool|int {
        $date1 = DateTime::createFromFormat('d.m.Y', $this->dates[0]);
        $date2 = DateTime::createFromFormat('d.m.Y', $this->dates[1]);
        $interval = $date1->diff($date2);
        return $interval->days;
    }
    public function getLastPaymentDate(): string {
        return date('d.m.Y', strtotime($this->dates[0]. ' - 42 days'));
    }

    public function getTotal(): float {
        return ($this->status != "Owner Confirmed") ? $this->total : $this->total - $this->bookingPrice;
    }

    public function getCreationDate(): string {
        return get_the_date('d.m.Y', $this->id);
    }

    private function priceReadyForBookingTable(): bool {
        return $this->status != "Owner Confirmed" && $this->status != "Reservation Created" && $this->status != "Reservation Confirmed";
    }
    public function getBookingPriceForBookingTable(): float {
        $creationDate = DateTime::createFromFormat('d.m.Y', get_the_date('d.m.Y', $this->id));
        $newCalcDate = DateTime::createFromFormat('d.m.Y', '17.08.2024');
        $isAfterNewCalcDate = $creationDate->getTimestamp() > $newCalcDate->getTimestamp();
        if($this->status == "Reservation Deposit Payed"){
            if($isAfterNewCalcDate){
                return $this->bookingPrice*0.25*0.91;
            }
            else{
                return $this->bookingPrice*0.25;
            }
        }
        else{
            if($isAfterNewCalcDate){
                return $this->priceReadyForBookingTable() ? $this->bookingPrice*0.91 : 0;
            }
            else{
                return $this->priceReadyForBookingTable() ? $this->bookingPrice : 0;
            }
        }
    }
    public function getOwnerShare(): float {
        return $this->getBookingPriceForBookingTable() * 0.76;
    }

    public function getFormattedStatus(): string {
        return match ($this->status) {
            "Reservation Created" => "Reservation oprettet",
            "Reservation Confirmed" => "Reservation bekræftet",
            "Reservation Deposit Payed" => "Reservationsdepositum betalt",
            "Reservation Payed" => "Reservation Betalt",
            "Reservation Cancelled" => "Reservation annulleret",
            "Owner Confirmed" => "Ejer bekræftet",
            default => "Ukendt status",
        };
    }

    public function getStartDate(): DateTime {
        return DateTime::createFromFormat('d.m.Y', $this->dates[0]);
    }

	public function daysUntilStartDate(): int {
		$today = new DateTime();
		$startDate = $this->getStartDate();
		$interval = $today->diff($startDate);
		return $interval->days;
	}

	public function getComments(): string|null {
		return $this->comments;
	}

	public function getGuests(): int|null {
		return $this->guests;
	}

	public function getCommentsForMail($lang="DA"): string {
		$comments = $this->comments;
		if(!empty($comments)){
			return ($lang === "DA") ? "<strong>Kommentar:</strong><br>".$comments : "<strong>Comment:</strong><br>".$comments;
		}
		return "";
	}

	public function getDiscountForMail($lang="DA"): string {
		$discount = $this->discount;
		$discountDescription = (isset($this->discountCode["description"])) ? $this->discountCode["description"]."<br>" : "";
		if(!empty($discount)){
			return ($lang === "DA") ? "<strong>Kampagnekode: ".$discount."</strong><br>".$discountDescription : "<strong>Coupon code: ".$discount."</strong><br>".$discountDescription;
		}
		return "";
	}

	public function getManualExtrasForMail($lang="DA"): string {
		$manualExtras = $this->manualExtras;
		$manualExtrasKeys = array_keys($manualExtras);
		$others = "";
		if(count($manualExtras) > 0 && !empty($manualExtras)) {
			$others = ($lang === "DA") ? "<strong>Andet:</strong><br>" : "<strong>Other:</strong><br>";
			for ( $i = 0; $i < count( $manualExtras ); $i ++ ) {
				$others .= $manualExtras[ $manualExtrasKeys[ $i ] ]["overskrift"] . "<br>";
			}
		}
		return $others;
	}

	public function isStartDateXDaysOrLessFromCreationDate($days): bool {
		$startDate = $this->getStartDate();
		$creationDate = $this->getCreationDatetime();
		$interval = $creationDate->diff($startDate, true);
		return $interval->days <= $days;
	}

	public function getDaysSinceCreation(): int {
		$today = new DateTime();
		$creationDate = $this->getCreationDatetime();
		$interval = $creationDate->diff($today, true);
		return $interval->days;
	}

	public function differenceBetweenCreationAndStartDate(): int {
		$startDate = $this->getStartDate();
		$creationDate = $this->getCreationDatetime();
		$interval = $creationDate->diff($startDate, true);
		return $interval->days;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function getContactInfoDA(): string {
		return "<strong>Dine kontaktoplysninger:</strong><br>".get_post_meta($this->id, "email", true)."<br>".get_post_meta($this->id, "landekode", true)." ".get_post_meta($this->id, "telefon", true);
	}

	public function getContactInfoEN(): string {
		return "<strong>Your contact info:</strong><br>".get_post_meta($this->id, "email", true)."<br>".get_post_meta($this->id, "landekode", true)." ".get_post_meta($this->id, "telefon", true);
	}

	public function getExpectedArrival(): string|null {
		return $this->expectedArrival;
	}
}