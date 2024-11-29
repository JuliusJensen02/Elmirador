<?php

use classes\Apartment;

function get_apartments_custom() {
	$startDate =    !empty($_POST['startDate']) ? $_POST['startDate'] : false;
	$endDate =      !empty($_POST['endDate']) ? $_POST['endDate'] : false;
	$house =        (isset($_POST['house']) && $_POST["house"] !== "") ? intval($_POST["house"]) : false;
	$guests =       isset($_POST['guests']) ? intval($_POST['guests']) : false;
	$bedrooms =     isset($_POST['bedrooms']) ? intval($_POST['bedrooms']) : false;
	$jacuzzi =      isset($_POST['jacuzzi']) && $_POST['jacuzzi'] === "true";
	$pool =         isset($_POST['pool']) && $_POST['pool'] === "true";
	$elevator =     isset($_POST['elevator']) && $_POST['elevator'] === "true";
	$electricCar =  isset($_POST['electricCar']) && $_POST['electricCar'] === "true";

    global $wpdb;
    $apartments = get_posts([
        'post_type' => 'boliger',
        'post_status' => 'publish',
        'posts_per_page' => -1
    ]);
    $lang = $_POST['lang'] ?? 'da';
    $apartmentsArray = [];
    foreach ($apartments as $apartment) {
        $apartment = new Apartment($apartment->ID);
		if($apartment->getLang() === $lang &&
            ($house === false || $apartment->getId() === $house) &&
			($guests === false || $apartment->getMaxGuests() >= $guests) &&
			($bedrooms === false || $apartment->getBedrooms() >= $bedrooms) &&
			(!$jacuzzi || $apartment->hasJacuzzi()) &&
			(!$pool || $apartment->hasPool()) &&
			(!$elevator || $apartment->hasElevator()) &&
            (!$electricCar || $apartment->hasElectricCar()) &&
			($startDate === false || $endDate === false || !$apartment->isBooked($startDate, $endDate))
		) {
			$apartmentsArray[] = $lang === 'da' ? $apartment->getDAArray() : $apartment->getENArray();
		}
    }




	if($lang === 'en') {
		wp_send_json_success($apartmentsArray);
		wp_die();
	}

	$apartmentsSorted = [];
	$order = [12955, 13076, 15113, 13012, 13071, 13080, 12990, 13037, 12985, 12975, 13041, 13066];
	foreach ($order as $orderItem) {
		foreach ($apartmentsArray as $apartmentAgain) {
			if($apartmentAgain["id"] === $orderItem) {
				$apartmentsSorted[] = $apartmentAgain;
				break;
			}
		}
	}

	$furnished = array_slice($apartmentsSorted, 0, 7);
	$unfurnished = array_slice($apartmentsSorted, 7);

	shuffle($furnished);

	$apartmentsSorted = array_merge($furnished, $unfurnished);
	
    wp_send_json_success($apartmentsSorted);
    wp_die();
}

add_action('wp_ajax_get_apartments_custom', 'get_apartments_custom');
add_action('wp_ajax_nopriv_get_apartments_custom', 'get_apartments_custom');