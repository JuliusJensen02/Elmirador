<?php
add_shortcode('thank_you_booking', 'thankYouBooking');
function thankYouBooking() {
	$postID = $_GET["inserted_post_id"];
    $post = get_post( $postID );
    $user = get_post_meta( $postID, "bruger", true );
    if ( empty( $user ) && get_post_type( $post ) == "booking-orders" ) {
        $email = get_post_meta( $postID, "email", true );
        $user  = email_exists( $email );
        if ( $user ) {
            update_post_meta( $postID, "bruger", $user );
        }
        else {
            $user_id = wp_insert_user( array(
                'user_login' => $email,
                'user_pass'  => wp_generate_password( 12 ),
                'user_email' => $email,
                'first_name' => get_post_meta( $postID, "fornavn", true ),
                'last_name'  => get_post_meta( $postID, "efternavn", true ),
                'role'       => 'Kunde'
            ) );
            update_post_meta( $postID, "bruger", $user_id );
        }
	    return insertThankyouPage( $postID );
    }
    else {
        if ( is_user_logged_in() ) {
            $current_user_id = get_current_user_id();
            if ( $current_user_id == get_post_meta( $postID, "bruger", true ) ) {
                return insertThankyouPage( $postID );
            }
            else return insertThankyouPage( $postID );
        }
    }
}

function insertThankyouPage($ID){
    ob_start();
    $dates = explode(" - ", get_post_meta($ID, "ankomst-udtjekning", true));
    $date1 = DateTime::createFromFormat('d.m.Y', $dates[0]);
    $date2 = DateTime::createFromFormat('d.m.Y', $dates[1]);
    $interval = $date1->diff($date2);
    $night = $interval->days;
    $total = get_post_meta($ID, "pris-i-alt", true);

    //Discount
    $discountCode = get_post_meta($ID, "rabatkode", true);
	global $wpdb;
	$table_name = $wpdb->prefix . "jet_cct_booking_rabatkoder";
	$discount = $wpdb->get_row("SELECT * FROM $table_name WHERE (discount_code='$discountCode')", ARRAY_A);
	if($discount != null){
		$discountAmount = 0;
		if($discount["type"] == "Pris"){
			$discountAmount = $discount["rabat"];
		}
		else if($discount["type"] == "Procent"){
			$discountAmount = round($total*($discount["rabat"]/100));
		}
	}
?>
<div id="booking">
    <h1><? _e("Tak for din reservation #", "hello-elementor-child"); echo $ID; ?> </h1>
    <p><? _e("Tak for din reservation. Vi vil bekræfte reservationen hurtigst muligt (normalt indenfor 24 timer). Når vi har bekræftet reservationen, modtager du yderligere oplysninger omkring opholdet og betaling.", "hello-elementor-child");?></p>
    <div class="booking-content">
        <div id="info-overview">
            <div class="info-box">
                <span class="heading">Booking nr.</span>
                <span class="content"> <?php echo "#".$ID; ?> </span>
            </div>
            <div class="info-box">
                <span class="heading"><? _e("Navn", "hello-elementor-child");?></span>
                <span class="content"> <?php echo get_post_meta($ID, "fornavn", true)." ".get_post_meta($ID, "efternavn", true); ?> </span>
            </div>
            <div class="info-box">
                <span class="heading"><? _e("Hus", "hello-elementor-child");?></span>
                <span class="content"> <?php echo get_post_meta($ID, "hus-id", true); ?> </span>
            </div>
            <div class="info-box">
                <span class="heading"><? _e("Pris", "hello-elementor-child");?></span>
                <span class="content">€ <?php echo $total; ?></span>
            </div>
            <div class="info-box">
                <span class="heading"><? _e("Voksne / Børn", "hello-elementor-child");?></span>
                <span class="content">
                    <?php
                        echo get_post_meta($ID, "antal-voksne", true)." voksne, ";
                        if(get_post_meta($ID, "antal-boern", true) == ""){
                            _e("0 børn", "hello-elementor-child");
                        }
                        else{
                            echo get_post_meta($ID, "antal-boern", true);
                            _e(" børn", "hello-elementor-child");
                        }
                    ?>
                </span>
            </div>
        </div>
        <div id="payment-overview">
            <div class="payment-table-head">
                <div>
                    <span>Booking</span>
                    <span> <?php echo get_post_meta($ID, "hus-id", true); ?> </span>
                </div>
            </div>
            <div class="payment-table-body">
                <div>
                    <span><? _e("Dato", "hello-elementor-child");?></span>
                    <span> <?php echo get_post_meta($ID, "ankomst-udtjekning", true); ?> </span>
                </div>
                <div class="adv-price-table">
                    <?php
                    $json = get_post_meta($ID, "adv-price-table", true);
                    $json = strstr($json, '[');
                    $json = substr($json, 0, strpos($json, "]")+1);
                    $advPrices = json_decode($json, true);
                    foreach($advPrices as $advPrice){
                        echo "<span>";
                        echo "<div>€".$advPrice['price']." x ".$advPrice['number'];
                        if($advPrice['number'] > 1){
	                        _e(" nætter", "hello-elementor-child");
                        }
                        else{
	                        _e(" nat", "hello-elementor-child");
                        }
                        echo "</div>";
                        echo "<div>€".$advPrice['total']."</div>";
                        echo "</span>";
                    }
                    echo "</div>";
                    ?>

                <?php
                                 $extra = get_post_meta($ID, "extra-services", true);
                                 if(is_array($extra)){
                                     for($i=0; $i<count($extra); $i++){?>
                <div>
                    <span> <?php echo get_the_title($extra[$i]); ?> </span>
                    <span>€
                        <?php
                            if(get_post_meta($extra[$i], "dag", true) == "true"){
                                echo intval(get_post_meta($extra[$i], "pris", true))*$night;
                            }
                            else{
                                echo intval(get_post_meta($extra[$i], "pris", true));
                            }
                        ?>
                    </span>
                </div>
                <?php } }
                if($discount != null){
	                echo "<div><span>";
	                _e("Rabatkode: ", "hello-elementor-child");
	                echo $discountCode."</span>";
	                echo "<span>- €".number_format($discountAmount,0,".",",")."</span></div>";
                }
                                 ?>
            </div>
            <div class="payment-table-foot">
                <div>
                    <span><? _e("Pris i alt", "hello-elementor-child");?></span>
                    <span>€ <?php echo $total; ?></span>
                </div>
            </div>
        </div>
    </div>
    <p class="disclaimer">
        <? _e("* Hvis din reservation ikke ender i din indbakke, kontroller venligst dit spamfilter.", "hello-elementor-child");?>
    </p>
</div>

<script>
    let totalPrice = 0;
    document.querySelectorAll("#payment-overview .advanced-price-table-body span div:last-child").forEach((element) => {
        let price = element.textContent;
        price = price.replace(/\D/g,'');
        totalPrice += parseInt(price);
    });
    document.querySelectorAll("#payment-overview br").forEach((element) => {
        element.remove();
    })
</script>
<?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
?>