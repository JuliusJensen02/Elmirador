<?php

add_action('admin_enqueue_scripts', function(){
    if(isset($_GET["user_id"])) {
        $currentUser = $_GET["user_id"];
        $user = get_userdata($currentUser);
        if ($user && in_array("kunde", $user->roles)) {
            wp_enqueue_style('user_edit_style', get_stylesheet_directory_uri() . '/assets/styles/user-edit.css' );
        }
    }
});


/*Insert booking table on user page*/

add_action('show_user_profile', 'add_custom_user_fields', 100);
add_action('edit_user_profile', 'add_custom_user_fields', 100);

function add_custom_user_fields($user): void {
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
    <?php
}