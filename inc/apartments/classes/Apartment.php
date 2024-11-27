<?php
namespace apartments\classes;
use DateTime;

class Apartment {
    private int $id;
    private array $bookedDates;
    private bool $hasJacuzzi;
    private bool $hasPool;
    private bool $hasElevator;
	private bool $hasElectricCar;
    private int $bedrooms;
    private int $maxGuests;
    private int $number;
    private string $lang;
    private string $title;
    private array $gallery;
    private string $url;
    private string $guestsFormatted;
    private int $bathrooms;
    private int $beds;
    private float $lowestPrice;
	private array $seasonPrices;


    public function __construct(int $id) {
        global $wpdb;
        $this->id = $id;
        $this->url = get_permalink($id);
        $this->hasJacuzzi = get_post_meta($id, '_privat_jacuzzi', true) === "true";
        $this->hasPool = get_post_meta($id, '_privat_pool', true) === "true";
        $this->hasElevator = get_post_meta($id, '_elevator', true) === "true";
		$this->hasElectricCar = get_post_meta($id, 'electriccar', true) === "true";
        $this->bedrooms = get_post_meta($id, '_rooms', true);
        $this->maxGuests = get_post_meta($id, 'max_guests', true);
        $this->number = get_post_meta($id, 'husnummer', true);
        $this->guestsFormatted = get_post_meta($id, '_guests', true);
        $title = get_the_title($id);
        $this->title = $title;
        $regex = '/House \d+/';
        if(preg_match($regex, $title) === 1) {
            $this->lang = 'en';
        }
        else {
            $this->lang = 'da';
        }

        $this->gallery = [];
        $images = get_post_meta($id, '_gallery', true);
        $images = explode(',', $images);
        foreach ($images as $image) {
            $this->gallery[] = wp_get_attachment_image($image, 'full');
        }



        $bookings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}jet_apartment_bookings where apartment_id = $id" );
		$this->bookedDates = [];
        foreach ($bookings as $booking) {
            $start = new DateTime();
            $start->setTimestamp($booking->check_in_date);
            $end = new DateTime();
            $end->setTimestamp($booking->check_out_date);
            $this->bookedDates[] = [
                'start' => (int) $booking->check_in_date,
                'end' => (int) $booking->check_out_date
            ];
        }
        $this->lowestPrice = get_post_meta($id, '_apartment_price', true);
        $this->bathrooms = get_post_meta($id, '_baths', true);
        $this->beds = get_post_meta($id, '_beds', true);
		$this->seasonPrices = get_post_meta($id, 'season-price', true);
    }

	public function getDAArray(): array {
		return array(
			"id"              => $this->getId(),
			"images"          => $this->getGallery(),
			"title"           => $this->getTitle(),
			"guests"          => __( "Op til", "Hefa_theme" ) . " " . $this->getGuestsFormatted() . " " . __( "gæster", "Hefa_theme" ),
			"bedrooms"        => $this->getBedrooms() . " " . __( "soveværelser", "Hefa_theme" ),
			"beds"            => $this->getBeds() . " " . __( "senge", "Hefa_theme" ),
			"bathrooms"       => $this->getBathrooms() . " " . __( "badeværelser", "Hefa_theme" ),
			"extras"          => $this->getExtras(),
			"seasonPrices"    => $this->getSeasonPrices(),
			"lowestPrice"     => __("Fra", "Hefa_theme")." <span>€".$this->getLowestPrice()."</span> ".__("/ nat", "Hefa_theme"),
			"seasonPricesBtn" => "Sæsonpriser og tilbud",
			"discounts"       => [
				[ "title" => __( "Rabat v. min. 1 uge", "Hefa_theme" ), "value" => "20%" ],
				[ "title" => __( "Rabat v. min. 2 uger", "Hefa_theme" ), "value" => "25%" ]
			],
			"url"             => $this->getUrl()
		);
	}

	public function getENArray(): array {
		return array(
			"id"              => $this->getId(),
			"images"          => $this->getGallery(),
			"title"           => $this->getTitle(),
			"guests"          => __( "Up to", "Hefa_theme" ) . " " . $this->getGuestsFormatted() . " " . __( "guests", "Hefa_theme" ),
			"bedrooms"        => $this->getBedrooms() . " " . __( "bedrooms", "Hefa_theme" ),
			"beds"            => $this->getBeds() . " " . __( "beds", "Hefa_theme" ),
			"bathrooms"       => $this->getBathrooms() . " " . __( "bathrooms", "Hefa_theme" ),
			"extras"          => $this->getExtras(),
			"seasonPrices"    => $this->getSeasonPrices(),
			"lowestPrice"     => __("From", "Hefa_theme")." <span>€".$this->getLowestPrice()."</span> ".__("/ night", "Hefa_theme"),
			"seasonPricesBtn" => "Season prices and offers",
			"discounts"       => [
				[ "title" => __( "Discount for min. 1 week", "Hefa_theme" ), "value" => "20%" ],
				[ "title" => __( "Discount for min. 2 weeks", "Hefa_theme" ), "value" => "25%" ]
			],
			"url"             => $this->getUrl()
		);
	}


    public function hasJacuzzi(): bool {
        return $this->hasJacuzzi;
    }

    public function hasPool(): bool {
        return $this->hasPool;
    }

    public function hasElevator(): bool {
        return $this->hasElevator;
    }

	public function hasElectricCar(): bool {
		return $this->hasElectricCar;
	}

    public function getBedrooms(): int {
        return $this->bedrooms;
    }

    public function getMaxGuests(): int {
        return $this->maxGuests;
    }

    public function getNumber(): int {
        return $this->number;
    }

    public function getLang(): string {
        return $this->lang;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getGallery(): array {
        return $this->gallery;
    }

    public function isBooked(string $start, string $end): bool {
	    $startTime = new DateTime($start);
		$startTime->setTime(23, 59);
	    $endTime = new DateTime($end);


        foreach ($this->bookedDates as $bookedDate) {
			$bookedStart = new DateTime();
			$bookedStart->setTimestamp($bookedDate['start']);
	        $bookedStart->setTime(14, 0);
			$bookedEnd = new DateTime();
			$bookedEnd->setTimestamp($bookedDate['end']);


            if(($startTime <= $bookedEnd) && ($endTime >= $bookedStart)) {
                return true;
            }
        }
        return false;
    }

	public function debug($start, $end): array {
		$startTime = strtotime($start);
		$endTime = new DateTime($end);
		$endTime->setTime(23, 59, 59);
		$endTime = strtotime($endTime->format('Y-m-d H:i:s'));
		$test = new DateTime("2024-09-11");
		$test->setTime(0, 0, 1);
		$test->format('Y-m-d H:i:s');
		$truth = array();
		foreach ($this->bookedDates as $bookedDate){
			$truth[] = $startTime <= $bookedDate['start'] && $endTime >= $bookedDate["end"];
		}
		return array($test->format('Y-m-d H:i:s'), strtotime($test->format('Y-m-d H:i:s')), $this->number, $this->lang, $startTime, $endTime, $this->bookedDates, $truth);
	}

    public function getGuestsFormatted(): string {
        return $this->guestsFormatted;
    }

    public function getBathrooms(): int {
        return $this->bathrooms;
    }

    public function getBeds(): int {
        return $this->beds;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getLowestPrice(): float {
        return $this->lowestPrice;
    }

    public function getExtras(): string{
        if($this->hasJacuzzi && !$this->hasPool){
            return __('Privat spa', 'hefa_theme');
        }

        if(!$this->hasJacuzzi && $this->hasPool){
            return __('Privat pool', 'hefa_theme');
        }

        if($this->hasPool && $this->hasJacuzzi){
            return __('Privat spa & pool', 'hefa_theme');
        }

        return '';
    }

	public function getSeasonPrices(): array {
		return $this->seasonPrices;
	}

	public function getBookedDates(): array {
		return $this->bookedDates;
	}
}