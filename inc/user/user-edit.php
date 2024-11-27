<?php

use inc\kiosk\classes\Order;
use inc\kiosk\classes\User;

add_action('admin_enqueue_scripts', function(){
    if(isset($_GET["user_id"])) {
        $currentUser = $_GET["user_id"];
        $user = get_userdata($currentUser);
        if ($user && in_array("kunde", $user->roles)) {
            wp_enqueue_style('user_edit_style', get_stylesheet_directory_uri() . '/assets/css/user-edit.css');
        }
    }
});


/*Insert booking table on user page*/

add_action('show_user_profile', 'add_custom_user_fields', 100);
add_action('edit_user_profile', 'add_custom_user_fields', 100);

function add_custom_user_fields($user) {
    $bookings = get_posts(array(
        'post_type' => 'booking-orders',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'bruger',
                'value' => $user->ID,
                'compare' => '='
            )
        )
    ));

    global $wpdb;
    $table = $wpdb->prefix . "jet_cct_ordre";
    $currentUserID = get_current_user_id();
    $user = new User($currentUserID);
    $results = $wpdb->get_results("SELECT * FROM $table WHERE user = $currentUserID");
    $orders = array();
    foreach ($results as $result) {
        $orders[] = new Order($result->_ID);
    }
    ?>
    <h2>Bookings</h2>
    <div id="booking-table">
        <table>
            <thead>
            <tr>
                <th>Booking ID</th>
                <th>Ordre oprettet</th>
                <th>Hus</th>
                <th>Ankomst - Udtjekning</th>
                <th>Totalpris</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody><?php
            foreach ($bookings as $booking){
                echo '<tr>';
                echo '<td><a href="'.get_edit_post_link($booking->ID).'">' . $booking->ID . '</a></td>';
                echo '<td>' . $booking->post_date . '</td>';
                echo '<td>' . get_post_meta($booking->ID, 'hus-id', true) . '</td>';
                echo '<td>' . get_post_meta($booking->ID, 'ankomst-udtjekning', true) . '</td>';
                echo '<td>' . get_post_meta($booking->ID, 'pris-i-alt', true) . '</td>';
                echo '<td>' . get_post_meta($booking->ID, 'status', true) . '</td>';
                echo '</tr>';
            }
            ?></tbody>
        </table>
    </div>


    <h2>Kiosk ordre</h2>
    <div id="booking-table">
        <table>
            <thead>
            <tr>
                <th>Ordre id</th>
                <th>Status</th>
                <th>Hurtigst muligt?</th>
                <th>Afleveringstidspunkt</th>
                <th>Totalpris</th>
                <th>Handlinger</th>
            </tr>
            </thead>
            <tbody><?php
            /** @var Order $order */
            foreach ($orders as $order){
                $order->isFastDelivery() == "1" ? $fastDelivery = "Ja" : $fastDelivery = "Nej";
                echo '<tr>';
                echo "<td>{$order->getID()}</td>";
                echo "<td>{$order->getStatus()}</td>";
                echo "<td>$fastDelivery</td>";
                echo "<td>{$order->getDateTime()->format("d-m-Y-H-i-s")}</td>";
                echo "<td>{$order->getTotal()}</td>";
                echo "<td><a href='https://elmirador.dk/wp-admin/admin.php?page=jet-cct-ordre&cct_action=edit&item_id={$order->getID()}'>Rediger</a></td>";
                echo '</tr>';
            }
            ?></tbody>
        </table>
    </div>
    <a href="https://elmirador.dk/wp-admin/admin.php?page=jet-cct-ordre&cct_action=add&user_id=<?= $user->ID ?>">Tilføj ny ordre</a>
    <?php
}