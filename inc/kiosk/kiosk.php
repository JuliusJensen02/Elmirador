<?php

namespace inc\kiosk;

define('KIOSK_DIR', INC_DIR . '/kiosk');

/**
 * Register kiosk classes
 */
require_once KIOSK_DIR.'/classes/User.php';
require_once KIOSK_DIR.'/classes/Product.php';
require_once KIOSK_DIR.'/classes/ProductOrder.php';
require_once KIOSK_DIR.'/classes/Order.php';
require_once KIOSK_DIR.'/classes/KioskInvoice.php';

/**
 * Register kiosk shortcodes
 */
require_once KIOSK_DIR.'/shortcodes/productListing.php';
require_once KIOSK_DIR.'/shortcodes/kioskBreadcrumbs.php';
require_once KIOSK_DIR.'/shortcodes/kioskCheckout.php';
require_once KIOSK_DIR.'/shortcodes/cartProductListing.php';
require_once KIOSK_DIR.'/shortcodes/ordersTable.php';
require_once KIOSK_DIR.'/shortcodes/completedOrdersTable.php';
require_once KIOSK_DIR.'/shortcodes/editProfileForm.php';
require_once KIOSK_DIR.'/shortcodes/latestOrdersTable.php';

/**
 * Register kiosk ajax
 */
require_once KIOSK_DIR.'/ajax/createOrder.php';
require_once KIOSK_DIR.'/ajax/getProducts.php';
require_once KIOSK_DIR.'/ajax/getOrders.php';
require_once KIOSK_DIR.'/ajax/sendInvoice.php';
require_once KIOSK_DIR.'/ajax/createInvoice.php';
require_once KIOSK_DIR.'/ajax/updateUser.php';

/**
 * Register admin menu
 */
require_once KIOSK_DIR.'/admin.php';

/**
 * Register kiosk scripts and styles
 */
add_action('wp_enqueue_scripts', function(){
    global $post;
    if (($post->post_parent == 5819 || get_the_ID() == 5819)) {
        wp_enqueue_script('kioskScript', get_stylesheet_directory_uri() . '/assets/kiosk/js/kiosk.js', array('jquery'), null, true);
        wp_localize_script('kioskScript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    //Styles
    if (is_page(6238)) {
        wp_enqueue_style('kioskKasseStyle', get_stylesheet_directory_uri() . '/assets/kiosk/css/kasse.css');
    }
    if (is_page(5919) || is_page(17095)) {
        wp_enqueue_style('kioskBestillingerStyle', get_stylesheet_directory_uri() . '/assets/kiosk/css/orders.css');
    }
    if (is_page(6649)) {
        wp_enqueue_style('kioskProductListingStyle', get_stylesheet_directory_uri() . '/assets/kiosk/css/productListing.css');
    }
});

/**
 * Register admin scripts
 */
add_action('admin_enqueue_scripts', function(){
    if($_GET["cct_action"] == "add" && $_GET["page"] == "jet-cct-ordre") {
        wp_enqueue_script('kioskAddOrdreAdminScript', get_stylesheet_directory_uri() . '/assets/kiosk/js/addOrderAdmin.js', array('jquery'), null, true);
        wp_localize_script('kioskAddOrdreAdminScript', 'ajax_object', array('userid' => $_GET["user_id"]));
    }
    else{
        wp_enqueue_script('kioskAdminScript', get_stylesheet_directory_uri() . '/assets/kiosk/js/kioskAdmin.js', array('jquery'), null, true);
        wp_localize_script('kioskAdminScript', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
});


