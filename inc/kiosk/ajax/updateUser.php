<?php

use inc\kiosk\classes\User;

add_action('admin_post_nopriv_update_user_profile', 'updateUserProfile');
add_action('admin_post_update_user_profile', 'updateUserProfile');
function updateUserProfile(): void {
    $currentUserID = get_current_user_id();
    $user = new User($currentUserID);
    $user->setFirstname($_POST['firstname']);
    $user->setLastname($_POST['lastname']);
    $user->setEmail($_POST['email']);
    $user->setPhone($_POST['phone']);
    $user->setCountry($_POST['country']);
    $user->setAddress($_POST['address']);
    $user->setZip($_POST['zip']);
    $user->setCity($_POST['city']);
    $user->update();
    wp_redirect("https://elmirador.dk/profil/oplysninger/");
    die();
}

