<?php

use inc\kiosk\classes\User;
add_shortcode('editProfileForm', 'editProfileForm');
function editProfileForm(): void {
    $currentUserID = get_current_user_id();
    $user = new User($currentUserID);
    $formUrl = esc_url( admin_url("admin-post.php") );
    echo "<div id='profileForm'>";
    echo "<form id='editProfileForm' method='post' action='$formUrl'>";
    echo "<input type='hidden' name='action' value='update_user_profile'>";
    echo "<h2>Brugeroplysninger</h2>";
    echo "<div class='hline'></div>";

    echo "<div class='formRow'>";
    echo "<div class='formField'>";
    echo "<label>Fornavn</label>";
    echo "<input type='text' name='firstname' value='{$user->getFirstname()}'>";
    echo "</div>";

    echo "<div class='formField'>";
    echo "<label>Efternavn</label>";
    echo "<input type='text' name='lastname' value='{$user->getLastname()}'>";
    echo "</div>";
    echo "</div>";

    echo "<div class='formRow'>";
    echo "<div class='formField'>";
    echo "<label>Email (login og fakturering)</label>";
    echo "<input type='email' name='email' value='{$user->getPhone()}'>";
    echo "</div>";

    echo "<div class='formField'>";
    echo "<label>Telefon</label>";
    echo "<input type='tel' name='phone' value='{$user->getPhone()}'>";
    echo "</div>";
    echo "</div>";

    echo "<h2>Faktureringsadresse</h2>";
    echo "<div class='hline'></div>";

    echo "<div class='formRow'>";
    echo "<div class='formField'>";
    echo "<label>Land</label>";
    echo "<input type='text' name='country' value='{$user->getCountry()}'>";
    echo "</div>";

    echo "<div class='formField'>";
    echo "<label>Adresse</label>";
    echo "<input type='text' name='address' value='{$user->getAddress()}'>";
    echo "</div>";
    echo "</div>";

    echo "<div class='formRow'>";
    echo "<div class='formField'>";
    echo "<label>Postnummer</label>";
    echo "<input type='text' name='zip' value='{$user->getCountry()}'>";
    echo "</div>";

    echo "<div class='formField'>";
    echo "<label>By</label>";
    echo "<input type='text' name='city' value='{$user->getCity()}'>";
    echo "</div>";
    echo "</div>";

    echo "<button type='submit'>Opdatér oplysninger</button>";
    echo "</form>";
    echo "</div>";
}


