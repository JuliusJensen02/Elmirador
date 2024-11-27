<?php
define('USER_DIR', INC_DIR . '/user');
require_once USER_DIR . '/user-misc.php';
require_once USER_DIR . '/user-edit.php';



add_action('wp_ajax_get_user', 'get_user_custom');
function get_user_custom() :void{
    $userID = stripslashes(sanitize_text_field($_POST['userID']));
    $user = get_user_by('id', intval($userID));
    $name = $user->first_name." ".$user->last_name;
    if($name == " "){
        $name = $user->display_name;
    }
    wp_send_json_success(array('name' => $name, 'url' => get_edit_user_link($userID)));
    wp_die();
}