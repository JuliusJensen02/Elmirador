<?php

use classes\Apartment;

add_shortcode( 'apartmentSeasonPopup', 'apartmentSeasonPopup' );
function apartmentSeasonPopup(): string {
	wp_enqueue_script('apartmentSeasonPopupScript', get_stylesheet_directory_uri() . '/assets/apartments/js/popup.js', array('jquery'), null, true);
	wp_localize_script('apartmentSeasonPopupScript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php'), "lang" => $_GET['lang'] ?? 'da'));
	wp_enqueue_style('apartmentSeasonPopupStyle', get_stylesheet_directory_uri() . '/assets/apartments/css/popup.css');
	$apartment = new Apartment(get_the_ID());
	ob_start();
	?>
	<button class="openSeasonPopup"><?= __("Sæsonpriser og tilbud", "Hefa_theme") ?></button>
    <div class="seasonPopup">
        <div class="popup-content">
            <div class="popup-top">
	            <h2>Sæsonpriser og tilbud</h2>
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20.696" height="20.696" viewBox="0 0 20.696 20.696">
                        <path id="close_3_" data-name="close (3)"
                              d="M12.244,10.484,20.3,2.425a1.341,1.341,0,0,0-1.9-1.9L10.348,8.588,2.289.529a1.341,1.341,0,0,0-1.9,1.9l8.059,8.059L.393,18.543a1.341,1.341,0,1,0,1.9,1.9l8.059-8.059,8.059,8.059a1.341,1.341,0,0,0,1.9-1.9Zm0,0"
                              transform="translate(0 -0.136)" fill="#1A242F"></path>
                    </svg>
                </button>
            </div>
	        <?php
	        foreach ($apartment->getSeasonPrices() as $season) {
		        ?>
				<div class="season">
					<p><?= $season['period'] ?></p>
					<p><?= $season['price'] ?></p>
				</div>
		        <?php
	        }
	        ?>
            <div class="discount">
	            <p><?= __( "Rabat v. min. 1 uge", "Hefa_theme" ) ?></p>
                <p>20%</p>
            </div>
            <div class="discount">
	            <p><?= __( "Rabat v. min. 2 uge", "Hefa_theme" ) ?></p>
                <p>25%</p>
            </div>
        </div>
    </div>
	<?php
	return ob_get_clean();
}

