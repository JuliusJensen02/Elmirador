<?php
add_shortcode('cartProductListing', 'cartProductListing');
function cartProductListing() :void{
    echo '<div id="cart"></div>';
    echo '<div class="hline"></div>';
    echo '<div class="total">';
    echo '<h2>Total <div><span id="total">€0</span><span class="abbr">Inkl. moms</span></div></h2>';
    echo '</div>';
    echo '<div class="hline"></div>';
    echo '<div class="disclaimer">';
    echo '<p>NB: Din ordre ligges på en samlet faktura med evt. andre bestillinger, du har udført. <a href="https://elmirador.dk/profil/mine-bestillinger/">Se dine bestillinger her</a>. Oplysninger brugt til at gennemføre din bestilling, er de aktuelle vist på din <a href="https://elmirador.dk/profil/min-konto/">profil-side</a>.</p>';
    echo '</div>';
}