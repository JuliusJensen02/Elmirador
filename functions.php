<?php
function cb_child_process_location( $location = null ) {
	if ( ! function_exists( 'jet_theme_core' ) ) {
		return false;
	}
	if( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return false;
	}
	$done = jet_theme_core()->locations->do_location( $location );
	return $done;
}



require_once 'script-handler.php';
require_once 'dompdf/autoload.inc.php';
require_once 'inc/loader.php';




add_filter('months_dropdown_results', '__return_empty_array');
// Add custom filter dropdown for 'Ankomst-Udtjekning' month
function custom_booking_orders_filter_by_month() {
	global $typenow;
	if ($typenow == 'booking-orders') {
		$selected_month = isset($_GET['arrival_month_filter']) ? $_GET['arrival_month_filter'] : '';
		echo '<select name="arrival_month_filter">';
		echo '<option value="">Filtrér efter ankomst</option>';

		// Query distinct months from the first date of custom field 'Ankomst-Udtjekning'
		global $wpdb;
		$months = $wpdb->get_results("
            SELECT DISTINCT MONTH(STR_TO_DATE(SUBSTRING_INDEX(pm.meta_value, ' - ', 1), '%d.%m.%Y')) as month_num, 
            YEAR(STR_TO_DATE(SUBSTRING_INDEX(pm.meta_value, ' - ', 1), '%d.%m.%Y')) as year
            FROM $wpdb->posts p
            INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id
            WHERE p.post_type = 'booking-orders'
            AND pm.meta_key = 'ankomst-udtjekning'
            AND pm.meta_value != ''
            ORDER BY year DESC, month_num ASC
        ");

		foreach ($months as $month) {
			$month_num = $month->month_num;
			$year = $month->year;
			$month_name = date("F Y", mktime(0, 0, 0, $month_num, 1, $year));
			$value = $month_num . '-' . $year;
			echo '<option value="' . esc_attr($value) . '" ' . selected($selected_month, $value, false) . '>' . esc_html($month_name) . '</option>';
		}

		echo '</select>';
	}
}

function custom_date_filter($query) {
	if (is_admin() && $query->is_main_query() && $query->get('post_type') == 'booking-orders' && isset($_GET['arrival_month_filter']) && $_GET['arrival_month_filter'] != '') {
		global $wpdb;

		// Get month and year from the filter
		$month_year = explode('-', $_GET['arrival_month_filter']);
		$month = intval($month_year[0]); // Month as an integer
		$year = intval($month_year[1]); // Year as an integer

		// Calculate the start and end timestamps in seconds for the specified month and year
		$start_date = strtotime("{$year}-{$month}-01"); // Start of the month in seconds
		$end_date = strtotime("{$year}-{$month}-" . date('t', strtotime("{$year}-{$month}-01"))); // End of the month in seconds

		// Add join to the jet_apartment_bookings table, selecting only check_in_date
		add_filter('posts_join', function ($join) use ($wpdb) {
			return $join . " LEFT JOIN {$wpdb->prefix}jet_apartment_bookings AS bookings ON bookings.order_id = {$wpdb->prefix}posts.ID";
		});

		// Add the where clause to filter by check_in_date
		add_filter('posts_where', function ($where) use ($start_date, $end_date) {
			return $where . " AND bookings.check_in_date BETWEEN {$start_date} AND {$end_date}";
		});
	}
}

add_action('pre_get_posts', 'custom_date_filter');








add_action('restrict_manage_posts', 'custom_booking_orders_filter_by_month');


// Insert content at the bottom of the list in 'edit.php'
function custom_booking_orders_bottom_content($position) {
	global $typenow;
	if ($typenow == 'booking-orders') {
		$totalPrice = 0;
		$args = array(
			'post_type' => 'booking-orders',
			'posts_per_page' => -1, // Get all posts
		);

		$posts = get_posts($args);
		if (is_admin()) {
			foreach ( $posts as $index => $post ) {
				$dateCheck = false;
				$houseCheck = false;
				$statusCheck = false;
				$createdCheck = false;
				if(isset($_GET["jet_engine_filters"])){
					if(isset($_GET["jet_engine_filters"][0]) && $_GET["jet_engine_filters"][0] != ""){
						$houseFilter = $_GET["jet_engine_filters"][0];
						$house = get_post_meta($post->ID, 'hus-id', true);
						$houseCheck = $house != $houseFilter;
					}
					if(isset($_GET["jet_engine_filters"][1]) && $_GET["jet_engine_filters"][1] != ""){
						$statusFilter = $_GET["jet_engine_filters"][1];
						$status = get_post_meta($post->ID, 'status', true);
						$statusCheck = $status != $statusFilter;
					}
				}
				if(isset($_GET['arrival_month_filter']) && $_GET['arrival_month_filter'] != ""){
					$month_year = explode( '-', $_GET['arrival_month_filter'] );
					$month      = $month_year[0];
					$year       = $month_year[1];
					$date = date( 'm.Y', strtotime( "first day of $year-$month" ) );
					$dates = get_post_meta( $post->ID, 'ankomst-udtjekning', true );
					$dates = explode( ' - ', $dates );
					$dateComp = date( 'm.Y', strtotime( "first day of $dates[0]" ) );
					$dateCheck = $date != $dateComp;
				}
				if(isset($_GET['m']) && $_GET['m'] != 0){
					$month_year = str_split($_GET['m'], 4);
					$month      = $month_year[1];
					$year       = $month_year[0];
					$date = date( 'm.Y', strtotime( "first day of $year-$month" ) );
					$creationDate = date( 'm.Y', strtotime( $post->post_date ) );
					$createdCheck = $date != $creationDate;
				}

				if($dateCheck || $houseCheck || $statusCheck || $createdCheck){
					unset( $posts[$index] );
				}
			}
		}
		foreach ($posts as $post) {
			$totalPrice += (float) get_post_meta($post->ID, 'pris-i-alt', true);
		}
		?>
		<div class="below-list-content">
			<p>Total pris (€): <? echo $totalPrice; ?></p>
		</div>
		<?php
	}
}

add_action('manage_posts_extra_tablenav', 'custom_booking_orders_bottom_content');



function custom_search_meta_fields( $query ) {
	global $pagenow, $post_type;

	if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'booking-orders' && $query->is_main_query() ) {
		$search_query = $query->get( 's' );
		if ( ! empty( $search_query ) ) {
			$search_query = sanitize_text_field( $search_query );
			$search_query = explode(" ", $search_query);
			$meta_query = array(
				'relation' => 'OR',

			);
			foreach ($search_query as $value){
				$meta_query[] = array(
					'key'     => 'fornavn',
					'value'   => $value,
					'compare' => 'LIKE'
				);
			}
			foreach ($search_query as $value){
				$meta_query[] = array(
					'key'     => 'efternavn',
					'value'   => $value,
					'compare' => 'LIKE'
				);
			}
			foreach ($search_query as $value){
				$meta_query[] = array(
					'key'     => 'email',
					'value'   => $value,
					'compare' => 'LIKE'
				);
			}

			$query->set( 's', '' );
			$query->set( 'meta_query', $meta_query );
		}
	}
}
add_action( 'pre_get_posts', 'custom_search_meta_fields' );