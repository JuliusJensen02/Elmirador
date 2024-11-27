<?php
add_shortcode('advPriceBoligArkiv', 'advPriceWidget');
function advPriceWidget() {
	$data = get_post_meta( get_the_ID(), "jet_abaf_price" );
	return "<bdi>". __("Fra ", "hello-elementor-child") ."</bdi>€".$data[0]["_apartment_price"] . "<bdi>". __(" / nat", "hello-elementor-child") ."</bdi>";
}

