<?php
add_shortcode('kioskCheckout', 'kioskCheckout');
function kioskCheckout(): void{
    $soon = new DateTime("now", new DateTimeZone("Europe/Madrid"));
    $soon->add(new DateInterval('PT60M'));
    echo '<div id="kioskCheckout">';

    echo '<div class="delivery">';
    echo '<label>Leveringssted</label>';
    echo '<select id="delivery">';
    echo '<option value="huset">Huset</option>';
    echo '<option value="poolen">Poolen</option>';
    echo '<option value="fitness">Fitness</option>';
    echo '<option value="tapasBar">Tapas bar</option>';
    echo '</select>';
    echo '</div>';

    echo '<div id="time">';
    echo '<label>Tidspunkt</label>';
    echo '<div class="radioGroup">';
    echo '<input name="time" type="radio" value="fast" checked>';
    echo '<label for="time">Hurtigst muligt</label>';
    echo '</div><div class="radioGroup">';
    echo '<input name="time" type="radio" value="choose">';
    echo '<label for="time">Vælg tidspunkt</label>';
    echo '</div>';

    echo '<div id="chooseTime" style="display: none;">';
    echo '<label>Dato og tidspunkt</label>';
    echo '<input type="datetime-local" id="datetime" name="datetime" value="'.$soon->format("Y-m-d H:i").'" min="'.$soon->format("Y-m-d")."T".$soon->format("H:i").'">';
    echo '</div>';
    echo '</div>';

    echo '<label for="note">Evt. kommentar</label>';
    echo '<textarea id="note"></textarea>';

    echo '<div id="remark">';
    echo '<h2>Bemærk:</h2>';
    echo '<p>1) 1) Levering kan tage op til 30 minutter i tidsrummet 08.00 og 20.00.</p>';
    echo '<p>2) Op til 60 minutter i tidsrummet 18.00 - 22.00.</p>';
    echo '<p>3) Ved planlagt levering tidligst 3 timer fra nu, kommer din bestilling på det valgte tidspunkt.</p>';
    echo '</div>';

    echo '</div>';

    echo '<div id="notice"></div>';
    echo '<button id="checkoutBtn">Bekræft bestilling</button>';
}