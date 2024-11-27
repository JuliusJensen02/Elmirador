<?php
add_action('admin_enqueue_scripts', function(){
    $user = wp_get_current_user();
    if(isset($_GET["post"])) {
        $postType = get_post_type($_GET["post"]);
        if ($user && in_array("booking_manager", $user->roles) && $postType == "booking-orders") {
            wp_enqueue_style('booking_orders_edit_style', get_stylesheet_directory_uri() . '/assets/css/booking-orders.css');
        }
        if(get_post_type($_GET["post"]) == "booking-orders") {
            wp_enqueue_script('booking_orders_edit_script', get_stylesheet_directory_uri() . '/assets/js/booking-order.js', array('jquery'), null, true);
        }
    }
    if($user && in_array("booking_manager", $user->roles)) {
        wp_enqueue_style('admin_general_style', get_stylesheet_directory_uri() . '/assets/css/admin.css');
    }


});
